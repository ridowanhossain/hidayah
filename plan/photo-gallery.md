# Photo Gallery Pages Modernization Plan (ফটো গ্যালারি)

## বর্তমান অবস্থা

| ফাইল | অবস্থা | মন্তব্য |
|---|---|---|
| `templates/archive-photo_gallery.php` | ⚠️ আংশিক | Category/Year filter reload-based, Sort AJAX নেই |
| `templates/single-photo_gallery.php` | ✅ ভালো | Photo grid, lightbox modal, album info sidebar আছে |
| `inc/ajax-gallery-filters.php` | ❌ নেই | তৈরি করতে হবে |
| Lightbox JS | ⚠️ Stub | `script.js`-এ lightbox logic নেই বা incomplete |
| ZIP Download | ❌ নেই | "সম্পূর্ণ এলবাম (ZIP)" বাটনের `href="#"` — কাজ করে না |
| `inc/meta-boxes.php` → gallery | ❌ নেই | Meta box নেই |

---

## ফেজ ১: Archive — AJAX ফিল্টারিং

### ১.১ — `inc/ajax-gallery-filters.php` তৈরি

```php
add_action( 'wp_ajax_filter_gallery', 'hidayah_filter_gallery_callback' );
add_action( 'wp_ajax_nopriv_filter_gallery', 'hidayah_filter_gallery_callback' );
```

**বিশেষত্ব:**
- Filter by: `gallery_cat` taxonomy (term_id)
- Filter by: `gallery_year` taxonomy (term_id)
- Sort: `newest`, `popular` (`_post_views_count`)
- Keyword search

```php
function hidayah_render_gallery_card() {
    $photos = get_post_meta( get_the_ID(), '_gallery_images', true );
    $count  = is_array($photos) ? count($photos) : 0;
    $loc    = get_post_meta( get_the_ID(), '_gallery_location', true );
    // ...Album card HTML...
}
```

### ১.২ — `archive-photo_gallery.php` রিফ্যাক্টর

**পরিবর্তন:**

1. IDs যোগ: `id="gallerySearchInput"`, `id="gallerySortSelect"`
2. Category + Year filter sidebar links → Toolbar dropdowns:

```html
<div class="archive-filters-toolbar">
    <select id="galleryCatFilter">
        <option value="">ইভেন্ট অনুযায়ী</option>
        <?php foreach ($gal_cats as $gc) : ?>
            <option value="<?php echo $gc->term_id; ?>"><?php echo esc_html($gc->name); ?></option>
        <?php endforeach; ?>
    </select>
    <select id="galleryYearFilter">
        <option value="">সাল অনুযায়ী</option>
        <?php foreach ($gal_years as $gy) : ?>
            <option value="<?php echo $gy->term_id; ?>"><?php echo hidayah_en_to_bn_number($gy->name); ?></option>
        <?php endforeach; ?>
    </select>
</div>
```

3. Count badge: `id="galleryCountBadge"`
4. Grid: `id="photoGalleryGrid"` (ইতিমধ্যে আছে)
5. Spinner + Pagination: `id="galleryPagination"`

### ১.৩ — `script.js`-এ AJAX Block

```javascript
// action: 'filter_gallery'
// Grid: #photoGalleryGrid
// Loader: #galleryLoader
// Count: #galleryCountBadge
// Pagination: #galleryPagination
// Filters: galleryCatFilter, galleryYearFilter, gallerySortSelect, gallerySearchInput
```

---

## ফেজ ২: Single Photo Gallery — Lightbox ও উন্নতি

### ২.১ — Lightbox JS (সবচেয়ে গুরুত্বপূর্ণ)

`single-photo_gallery.php`-এ lightbox HTML আছে (#lightbox, #lightboxImg, #lightboxClose, #lightboxPrev, #lightboxNext)। `script.js`-এ logic যোগ করতে হবে:

```javascript
(function() {
    var lb      = document.getElementById('lightbox');
    var lbImg   = document.getElementById('lightboxImg');
    var lbCap   = document.getElementById('lightboxCaption');
    var lbCount = document.getElementById('lightboxCounter');
    var lbDl    = document.getElementById('lightboxDl');
    if (!lb) return;

    var items   = Array.from(document.querySelectorAll('.gallery-photo-item'));
    var current = 0;

    function openLightbox(index) {
        current = index;
        var item = items[index];
        lbImg.src  = item.dataset.full;
        lbCap.textContent  = item.dataset.caption || '';
        lbCount.textContent = (index + 1) + ' / ' + items.length;
        lbDl.href = item.dataset.full;
        lbDl.download = 'photo-' + index;
        lb.hidden = false;
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        lb.hidden = true;
        document.body.style.overflow = '';
    }

    items.forEach(function(item, i) {
        item.addEventListener('click', function() { openLightbox(i); });
    });

    document.getElementById('lightboxClose').addEventListener('click', closeLightbox);
    document.getElementById('lightboxBackdrop').addEventListener('click', closeLightbox);
    document.getElementById('lightboxPrev').addEventListener('click', function() {
        openLightbox((current - 1 + items.length) % items.length);
    });
    document.getElementById('lightboxNext').addEventListener('click', function() {
        openLightbox((current + 1) % items.length);
    });
    document.addEventListener('keydown', function(e) {
        if (lb.hidden) return;
        if (e.key === 'ArrowLeft')  openLightbox((current - 1 + items.length) % items.length);
        if (e.key === 'ArrowRight') openLightbox((current + 1) % items.length);
        if (e.key === 'Escape')     closeLightbox();
    });
})();
```

### ২.২ — ZIP Download Feature

বর্তমানে "সম্পূর্ণ এলবাম (ZIP)" বাটনটি `href="#"` — কার্যকর করা যেতে পারে দু'ভাবে:

**Option A (Simple — Recommended):** PHP দিয়ে ZipArchive generate করে download:

```php
// download-gallery-zip.php endpoint অথবা AJAX action
function hidayah_download_gallery_zip() {
    $post_id = absint($_GET['post_id']);
    $photos  = get_post_meta($post_id, '_gallery_images', true);
    if (!$post_id || !is_array($photos)) wp_die();

    $zip = new ZipArchive();
    $tmp = sys_get_temp_dir() . '/gallery-' . $post_id . '.zip';
    $zip->open($tmp, ZipArchive::CREATE | ZipArchive::OVERWRITE);
    foreach ($photos as $id) {
        $file = get_attached_file($id);
        if ($file && file_exists($file)) $zip->addFile($file, basename($file));
    }
    $zip->close();
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="album.zip"');
    readfile($tmp);
    unlink($tmp);
    exit;
}
add_action('wp_ajax_download_gallery_zip', 'hidayah_download_gallery_zip');
add_action('wp_ajax_nopriv_download_gallery_zip', 'hidayah_download_gallery_zip');
```

**Option B (Disable button):** ZIP feature সরিয়ে individual image download রাখা (lightbox-এ download বাটন আছে)।

---

## ফেজ ৩: Meta Box — Gallery Fields

| ফিল্ড নাম | Meta Key | ধরন |
|---|---|---|
| গ্যালারি ছবিসমূহ | `_gallery_images` | Multiple image upload (IDs array) |
| স্থান (Location) | `_gallery_location` | text |
| ফটোগ্রাফার | `_gallery_photographer` | text |

**Multiple Image Upload UI (Admin):**

```php
// meta-boxes.php-এ:
function hidayah_gallery_meta_box_html($post) {
    $images = get_post_meta($post->ID, '_gallery_images', true) ?: [];
    ?>
    <div id="galleryImagesPreview">
        <?php foreach ($images as $img_id) : ?>
            <img src="<?php echo wp_get_attachment_image_url($img_id, 'thumbnail'); ?>" style="width:80px;height:80px;object-fit:cover;margin:4px;">
        <?php endforeach; ?>
    </div>
    <input type="hidden" name="_gallery_images" id="galleryImagesInput" 
           value="<?php echo esc_attr(implode(',', $images)); ?>">
    <button type="button" id="gallerySelectImages" class="button">ছবি নির্বাচন করুন</button>
    <?php
}
```

---

## Implementation Order

```
① script.js-এ Lightbox JS (single page-এ সবচেয়ে জরুরি)
② inc/ajax-gallery-filters.php তৈরি
③ archive-photo_gallery.php রিফ্যাক্টর (AJAX dropdowns)
④ script.js-এ gallery AJAX block
⑤ meta-boxes.php-এ gallery meta box (multiple image upload)
⑥ ZIP download feature সিদ্ধান্ত নিয়ে implement
```

## ফাইল পরিবর্তনের সারসংক্ষেপ

| ফাইল | কী করতে হবে |
|---|---|
| `assets/js/script.js` | **আপডেট** — Lightbox JS যোগ |
| `inc/ajax-gallery-filters.php` | **নতুন তৈরি** |
| `templates/archive-photo_gallery.php` | **রিফ্যাক্টর** |
| `assets/js/script.js` | **আপডেট** — gallery AJAX block |
| `inc/meta-boxes.php` | **আপডেট** — gallery meta box (multiple images) |
| `functions.php` | **আপডেট** — ZIP download handler + ajax include |
| `templates/single-photo_gallery.php` | **ছোট fix** — ZIP button href fix |
