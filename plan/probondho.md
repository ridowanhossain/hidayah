# Probondho (প্রবন্ধ ও লেখনি) Pages Modernization Plan

## বর্তমান অবস্থা

| ফাইল | অবস্থা | মন্তব্য |
|---|---|---|
| `templates/archive-probondho.php` | ⚠️ আংশিক | Category filter slug-ভিত্তিক reload, Sort reload-based |
| `templates/single-probondho.php` | ✅ ভালো | Reading progress, font control, author bio, TOC stub আছে |
| `inc/ajax-probondho-filters.php` | ❌ নেই | তৈরি করতে হবে |
| TOC auto-generation | ❌ নেই | `#probondhoTOC` খালি, JS দিয়ে H-tags parse করতে হবে |
| `inc/meta-boxes.php` → probondho | ❌ অসম্পূর্ণ | শুধু `_reading_time` দরকার |

---

## ফেজ ১: Archive — AJAX ফিল্টারিং

### ১.১ — `inc/ajax-probondho-filters.php` তৈরি

```php
add_action( 'wp_ajax_filter_probondho', 'hidayah_filter_probondho_callback' );
add_action( 'wp_ajax_nopriv_filter_probondho', 'hidayah_filter_probondho_callback' );
```

**বিশেষত্ব:**
- Filter by: `probondho_cat` taxonomy (term_id)
- Sort: `newest`, `popular` (`_post_views_count` meta)
- Keyword search

```php
function hidayah_render_probondho_card() {
    $read_time = get_post_meta( get_the_ID(), '_reading_time', true ) ?: '৫';
    $cats      = get_the_terms( get_the_ID(), 'probondho_cat' );
    $cat_name  = !empty($cats) ? $cats[0]->name : 'সাধারণ';
    // ...কার্ড HTML...
}
```

### ১.২ — `archive-probondho.php` রিফ্যাক্টর

**বর্তমান সমস্যা:**
- Category filter topbar dropdown দিয়ে `?probondho_cat=slug` redirect করে
- Sort URL redirect করে
- `onchange="window.location.href=this.value"` — সরাতে হবে

**পরিবর্তন:**

1. IDs যোগ: `id="probondhoSearchInput"`, `id="probondhoSortSelect"`
2. Category dropdown AJAX-triggered:

```html
<select id="probondhoCatFilter">
    <option value="">বিষয় অনুযায়ী</option>
    <?php foreach ($cats as $ct) : ?>
        <option value="<?php echo $ct->term_id; ?>">
            <?php echo esc_html($ct->name); ?> (<?php echo hidayah_en_to_bn_number($ct->count); ?>)
        </option>
    <?php endforeach; ?>
</select>
```

3. Count badge: `id="probondhoCountBadge"`
4. Grid: `id="probondhoArchiveList"` (ইতিমধ্যে আছে)
5. Spinner + Pagination: `id="probondhoPagination"`

### ১.৩ — `script.js`-এ AJAX Block

```javascript
// action: 'filter_probondho'
// Grid: #probondhoArchiveList
// Loader: #probondhoLoader
// Count: #probondhoCountBadge
// Pagination: #probondhoPagination
// Filters: probondhoCatFilter, probondhoSortSelect, probondhoSearchInput
```

---

## ফেজ ২: Single Probondho — JavaScript উন্নতি

### ২.১ — Auto Table of Contents (TOC)

`#probondhoTOC` খালি। Script.js-এ article headings parse করে TOC তৈরি:

```javascript
(function() {
    var toc  = document.getElementById('probondhoTOC');
    var body = document.getElementById('articleBody');
    if (!toc || !body) return;

    var headings = body.querySelectorAll('h2, h3');
    if (!headings.length) {
        toc.closest('.sidebar-widget').style.display = 'none';
        return;
    }
    headings.forEach(function(h, i) {
        if (!h.id) h.id = 'toc-' + i;
        var a = document.createElement('a');
        a.href = '#' + h.id;
        a.className = h.tagName === 'H3' ? 'toc-sub-item' : 'toc-main-item';
        a.textContent = h.textContent;
        toc.appendChild(a);
    });
})();
```

### ২.২ — Reading Progress Bar

`#readingProgressFill` scroll-এ width পরিবর্তন হবে:

```javascript
(function() {
    var fill = document.getElementById('readingProgressFill');
    var body = document.getElementById('articleBody');
    if (!fill || !body) return;
    window.addEventListener('scroll', function() {
        var top    = body.getBoundingClientRect().top + window.scrollY;
        var height = body.offsetHeight;
        var pct    = Math.min(100, Math.max(0, ((window.scrollY - top) / height) * 100));
        fill.style.width = pct + '%';
    });
})();
```

### ২.৩ — Font Size Control

`.probondho-font-btn` ক্লিক করলে article body-র font size পরিবর্তন:

```javascript
document.querySelectorAll('.probondho-font-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.probondho-font-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        var sizes = { small: '14px', medium: '16px', large: '19px' };
        var target = document.getElementById('articleBody') || document.getElementById('jiggasaAnswerBody');
        if (target) target.style.fontSize = sizes[this.dataset.size] || '16px';
    });
});
```

> **Note:** Font control JS একটাই — `probondho` ও `dini_jiggasa` উভয়ের জন্য কাজ করবে।

---

## ফেজ ৩: Meta Box

`_reading_time` meta box — `inc/meta-boxes.php`-এ `probondho` CPT-এর জন্য:

| ফিল্ড নাম | Meta Key | ধরন |
|---|---|---|
| পড়ার সময় (মিনিট) | `_reading_time` | number |

> `probondho` CPT WordPress author (`the_author()`) ব্যবহার করে, আলাদা author meta দরকার নেই।

---

## Implementation Order

```
① script.js-এ TOC + Reading Progress + Font Control JS (সবচেয়ে জরুরি)
② inc/ajax-probondho-filters.php তৈরি
③ archive-probondho.php রিফ্যাক্টর (IDs, AJAX dropdown)
④ script.js-এ probondho AJAX block
⑤ meta-boxes.php-এ _reading_time field
```

## ফাইল পরিবর্তনের সারসংক্ষেপ

| ফাইল | কী করতে হবে |
|---|---|
| `assets/js/script.js` | **আপডেট** — TOC, progress bar, font control JS |
| `inc/ajax-probondho-filters.php` | **নতুন তৈরি** |
| `templates/archive-probondho.php` | **রিফ্যাক্টর** |
| `assets/js/script.js` | **আপডেট** — probondho AJAX block |
| `inc/meta-boxes.php` | **ছোট update** — `_reading_time` field যোগ |
| `functions.php` | **আপডেট** — ajax include |
