# Monthly HD (মাসিক হক্বের দা'ওয়াত) Pages Modernization Plan

## বর্তমান অবস্থা

| ফাইল | অবস্থা | মন্তব্য |
|---|---|---|
| `templates/archive-monthly_hd.php` | ⚠️ আংশিক | Year filter slug-ভিত্তিক reload, custom year dropdown আছে, AJAX নেই |
| `templates/single-monthly_hd.php` | ✅ ভালো | Cover, PDF viewer, TOC, editorial সব আছে |
| `inc/ajax-monthly-hd-filters.php` | ❌ নেই | তৈরি করতে হবে |
| Download count AJAX handler | ❌ নেই | `updateDownloadCount()` function defined নেই |
| `inc/meta-boxes.php` → monthly_hd | ❌ নেই | Meta box নেই |

---

## ফেজ ১: Archive — AJAX ফিল্টারিং

### ১.১ — `inc/ajax-monthly-hd-filters.php` তৈরি

```php
add_action( 'wp_ajax_filter_monthly_hd', 'hidayah_filter_monthly_hd_callback' );
add_action( 'wp_ajax_nopriv_filter_monthly_hd', 'hidayah_filter_monthly_hd_callback' );
```

**বিশেষত্ব:**
- Sort: `newest` (DESC), `oldest` (ASC)
- Filter by: `issue_year` taxonomy (term_id)
- Filter by: `issue_category` taxonomy (special issues)
- Keyword search

```php
function hidayah_render_monthly_hd_card() {
    $pages      = get_post_meta( get_the_ID(), '_magazine_pages', true );
    $pdf_url    = get_post_meta( get_the_ID(), '_pdf_file_url', true );
    $is_special = get_post_meta( get_the_ID(), '_is_special_issue', true );
    // ...কার্ড HTML...
}
```

### ১.২ — `archive-monthly_hd.php` রিফ্যাক্টর

**বর্তমান সমস্যা:**
- Year dropdown custom button-click করে URL redirect করে
- `.year-dropdown-btn` click handler script.js-এ দরকার, কিন্তু AJAX-এও convert করতে হবে

**পরিবর্তন:**

1. Search, Sort IDs যোগ: `id="monthlyHdSearchInput"`, `id="monthlyHdSortSelect"`
2. Year filter dropdown → AJAX-triggered করতে হবে:

```html
<!-- বর্তমান: year-dropdown -->
<!-- পরিবর্তিত: select dropdown যা AJAX trigger করে -->
<select id="monthlyHdYearFilter">
    <option value="">সব সাল</option>
    <?php foreach (get_terms(['taxonomy'=>'issue_year']) as $yr) : ?>
        <option value="<?php echo $yr->term_id; ?>"><?php echo hidayah_en_to_bn_number($yr->name); ?></option>
    <?php endforeach; ?>
</select>
```

3. Grid wrapper: `id="monthlyHdGrid"`
4. Spinner + Pagination wrapper: `id="monthlyHdPagination"`
5. Count badge: `id="monthlyHdCountBadge"`

### ১.৩ — `script.js`-এ AJAX Block

```javascript
// action: 'filter_monthly_hd'
// Grid: #monthlyHdGrid
// Loader: #monthlyHdLoader
// Count: #monthlyHdCountBadge
// Pagination: #monthlyHdPagination
// Filters: monthlyHdYearFilter, monthlyHdSortSelect, monthlyHdSearchInput
```

---

## ফেজ ২: Single Monthly HD — উন্নতি

### ২.১ — Download Count AJAX

বর্তমানে `onclick="updateDownloadCount(<?php the_ID(); ?>)"` আছে কিন্তু function নেই।

**PHP handler:**

```php
function hidayah_update_download_count() {
    $post_id = absint($_POST['post_id']);
    if (!$post_id) wp_send_json_error();

    $count = (int) get_post_meta($post_id, '_pdf_download_count', true);
    update_post_meta($post_id, '_pdf_download_count', $count + 1);

    wp_send_json_success(['count' => $count + 1]);
}
add_action('wp_ajax_update_dl_count', 'hidayah_update_download_count');
add_action('wp_ajax_nopriv_update_dl_count', 'hidayah_update_download_count');
```

**JavaScript:**

```javascript
function updateDownloadCount(postId) {
    var data = new FormData();
    data.append('action', 'update_dl_count');
    data.append('nonce', hidayahAjax.nonce);
    data.append('post_id', postId);
    fetch(hidayahAjax.url, { method: 'POST', body: data });
}
```

### ২.২ — PDF Viewer Fallback

বর্তমানে `<iframe src="pdf#toolbar=0">` ব্যবহার হচ্ছে। কিছু browser এটি block করে।

```php
// Fallback সহ:
<?php if ( $pdf_url ) : ?>
    <div class="mhd-pdf-viewer">
        <iframe src="<?php echo esc_url($pdf_url); ?>#toolbar=0" 
                onerror="this.parentElement.innerHTML='<p>PDF preview লোড হচ্ছে না। <a href=\'<?php echo esc_url($pdf_url); ?>\' target=\'_blank\'>এখানে ক্লিক করুন</a>।</p>'">
        </iframe>
    </div>
<?php endif; ?>
```

### ২.৩ — Article Summaries Meta Field

`_article_summaries` meta-তে array of `[title, desc]` store হওয়ার কথা। এটির জন্য admin-এ repeater field তৈরি করতে হবে।

---

## ফেজ ৩: Meta Box

| ফিল্ড নাম | Meta Key | ধরন |
|---|---|---|
| পৃষ্ঠা সংখ্যা | `_magazine_pages` | number |
| PDF ফাইল | `_pdf_file_url` | file upload |
| PDF সাইজ (MB) | `_pdf_file_size` | number |
| বিশেষ সংখ্যা? | `_is_special_issue` | checkbox |
| সম্পাদকীয় | `_editorial_text` | textarea |
| সূচীপত্র | `_magazine_toc` | textarea |
| প্রবন্ধ সারসংক্ষেপ | `_article_summaries` | repeater (JSON) |

**Repeater field সহজ implementation:**

```php
// Admin-এ: textarea-তে JSON সেভ হবে
// Frontend-এ: json_decode() করবে
$summaries = json_decode( get_post_meta( get_the_ID(), '_article_summaries', true ), true );
```

---

## Implementation Order

```
① inc/ajax-monthly-hd-filters.php তৈরি
② archive-monthly_hd.php রিফ্যাক্টর (Year filter AJAX, IDs)
③ script.js-এ monthly-hd AJAX block
④ Download count AJAX handler + JS function fix
⑤ meta-boxes.php-এ monthly_hd meta box (পৃষ্ঠা, PDF, editorial)
⑥ single-monthly_hd.php-এ PDF viewer fallback
```

## ফাইল পরিবর্তনের সারসংক্ষেপ

| ফাইল | কী করতে হবে |
|---|---|
| `inc/ajax-monthly-hd-filters.php` | **নতুন তৈরি** |
| `templates/archive-monthly_hd.php` | **রিফ্যাক্টর** |
| `assets/js/script.js` | **আপডেট** — monthly-hd AJAX + `updateDownloadCount()` fix |
| `inc/meta-boxes.php` | **আপডেট** — monthly_hd meta box |
| `templates/single-monthly_hd.php` | **ছোট fix** — PDF fallback |
| `functions.php` | **আপডেট** — download count handler + ajax include |
