# Book Pages Modernization Plan (বই বিক্রয় কর্নার)

## বর্তমান অবস্থা

| ফাইল | অবস্থা | মন্তব্য |
|---|---|---|
| `templates/archive-book.php` | ⚠️ আংশিক | Static reload-based sort আছে, AJAX নেই; Genre/Author filter sidebar-link ভিত্তিক |
| `templates/single-book.php` | ✅ ভালো | Cart, rating, TOC, details table সব আছে |
| `inc/ajax-book-filters.php` | ❌ নেই | তৈরি করতে হবে |
| `inc/meta-boxes.php` → book section | ❌ নেই | Book meta box নেই (price, ISBN ইত্যাদি) |

---

## ফেজ ১: Archive Book — AJAX ফিল্টারিং

### ১.১ — `inc/ajax-book-filters.php` তৈরি

```php
add_action( 'wp_ajax_filter_book', 'hidayah_filter_book_callback' );
add_action( 'wp_ajax_nopriv_filter_book', 'hidayah_filter_book_callback' );
```

**বিশেষত্ব:**
- Sort options: `newest`, `oldest`, `price-asc`, `price-desc`, `popular`
- Filter by: `genre` taxonomy (term_id), `book_author` taxonomy (term_id)
- `_book_price` meta key দিয়ে price sort করবে

```php
function hidayah_render_book_card() {
    $price        = get_post_meta( get_the_ID(), '_book_price', true );
    $old_price    = get_post_meta( get_the_ID(), '_book_old_price', true );
    $badge        = get_post_meta( get_the_ID(), '_book_badge', true );
    $rating       = get_post_meta( get_the_ID(), '_book_rating', true ) ?: 0;
    $rating_cnt   = get_post_meta( get_the_ID(), '_book_rating_count', true ) ?: 0;
    $stock_status = get_post_meta( get_the_ID(), '_stock_status', true ) ?: 'instock';
    // ...কার্ড HTML...
}
```

### ১.২ — `archive-book.php` রিফ্যাক্টর

**যোগ করতে হবে:**
- `id="bookSearchInput"`, `id="bookSearchForm"` — search field-এ
- `id="bookSortSelect"` — sort dropdown-এ (remove `onchange` redirect)
- Taxonomy dropdown filters (Genre + Author) টুলবারে যোগ:

```html
<div class="archive-filters-toolbar">
    <div class="archive-count-badge" id="bookCountBadge">
        <span class="material-symbols-outlined">menu_book</span>
        মোট ...টি বই
    </div>
    <div class="archive-taxonomy-filters">
        <select id="bookGenreFilter">
            <option value="">ধরণ অনুযায়ী</option>
            <?php foreach (get_terms(['taxonomy'=>'genre']) as $g) : ?>
                <option value="<?php echo $g->term_id; ?>"><?php echo esc_html($g->name); ?></option>
            <?php endforeach; ?>
        </select>
        <select id="bookAuthorFilter">
            <option value="">লেখক অনুযায়ী</option>
            <?php foreach (get_terms(['taxonomy'=>'book_author']) as $a) : ?>
                <option value="<?php echo $a->term_id; ?>"><?php echo esc_html($a->name); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>
```

- Grid wrapper-এ ID যোগ: `id="bookArchiveGrid"`
- Spinner wrapper
- Pagination wrapper: `id="bookPagination"`

### ১.৩ — `script.js`-এ AJAX Block

```javascript
// AJAX IDs: bookSearchInput, bookSortSelect, bookGenreFilter, bookAuthorFilter
// action: 'filter_book'
// Grid ID: #bookArchiveGrid
// Loader ID: #bookLoader
// Count badge: #bookCountBadge
// Pagination: #bookPagination
```

**বইয়ের বিশেষ feature:** AJAX response-এ "কার্টে যোগ" বাটনের event re-binding করতে হবে:

```javascript
// After AJAX success:
if (typeof window.initCartButtons === 'function') {
    window.initCartButtons();
}
```

---

## ফেজ ২: Single Book — উন্নতি

`templates/single-book.php` মূলত সম্পন্ন। ছোট উন্নতি:

### ২.১ — Empty Content Check (সারসংক্ষেপ)

```php
// বর্তমান:
<div class="sb-section entry-content">
    <h2>বইয়ের সারসংক্ষেপ</h2>
    <?php the_content(); ?>
</div>

// সঠিক:
<?php if ( get_the_content() ) : ?>
    <div class="sb-section entry-content">
        <h2 class="sb-section-title"><?php _e( 'বইয়ের সারসংক্ষেপ', 'hidayah' ); ?></h2>
        <?php the_content(); ?>
    </div>
<?php endif; ?>
```

### ২.২ — Share Buttons `alert()` → Toast Notification

বর্তমানে `alert('লিঙ্ক কপি হয়েছে!')` ব্যবহার হচ্ছে। এটি `single-audio.php`-এর মতো toast নোটিফিকেশনে পরিবর্তন করতে হবে।

### ২.৩ — Sticky Buy Box (ভবিষ্যৎ উন্নতি)

Scroll করলে `.sb-purchase-box` sticky হয়ে যাবে। CSS দিয়ে করা যাবে:

```css
.sb-purchase-box {
    position: sticky;
    top: 20px;
}
```

---

## ফেজ ৩: Book Meta Box যোগ

`inc/meta-boxes.php`-এ book CPT-এর জন্য meta box তৈরি করতে হবে।

### ৩.১ — Book Meta Box ফিল্ডস

| ফিল্ড নাম | Meta Key | ধরন | বিবরণ |
|---|---|---|---|
| বইয়ের মূল্য | `_book_price` | number | e.g. ৩৫০ |
| পুরনো মূল্য | `_book_old_price` | number | ছাড়ের আগের মূল্য |
| ব্যাজ | `_book_badge` | text | e.g. "নতুন", "bestseller" |
| ISBN | `_book_isbn` | text | |
| পৃষ্ঠা সংখ্যা | `_book_pages` | number | |
| বাইন্ডিং | `_book_binding` | text | e.g. হার্ডকভার |
| ওজন (গ্রাম) | `_book_weight` | number | |
| সংস্করণ | `_book_edition` | text | e.g. ৩য় সংস্করণ |
| প্রকাশকাল | `_book_year` | text | |
| প্রকাশনী | `_book_publisher` | text | |
| স্টক স্ট্যাটাস | `_stock_status` | select | `instock` / `outofstock` |
| রেটিং | `_book_rating` | number | 0-5 |
| রেটিং সংখ্যা | `_book_rating_count` | number | |
| সূচীপত্র | `_book_toc` | textarea | |
| নমুনা ছবি | `_book_samples` | repeater/json | Image URLs |

---

## ফেজ ৪: Cart System উন্নতি

বর্তমানে cart button click করলে JS দিয়ে localStorage-এ যোগ হচ্ছে (বা হওয়ার কথা)। `page-cart.php` ও `page-checkout.php` বিদ্যমান আছে।

### ৪.১ — Cart Button Re-initialization after AJAX

AJAX-এ নতুন card load হলে `.book-sales-order-btn` ও `.add-to-cart-btn`-এ event listener পুনরায় বাঁধতে হবে।

### ৪.২ — Stock Status Check

AJAX filter সময় out-of-stock items আলাদাভাবে দেখানো বা ভিন্ন UI দেওয়া।

---

## Implementation Order

```
① inc/ajax-book-filters.php তৈরি
② archive-book.php রিফ্যাক্টর (IDs + Dropdown filters + Spinner)
③ script.js-এ book AJAX block + initCartButtons() hook
④ meta-boxes.php-এ book meta box যোগ
⑤ single-book.php-এ Empty content check + Toast notification
```

## ফাইল পরিবর্তনের সারসংক্ষেপ

| ফাইল | কী করতে হবে |
|---|---|
| `inc/ajax-book-filters.php` | **নতুন তৈরি** — filter callback + render card function |
| `templates/archive-book.php` | **রিফ্যাক্টর** — IDs, dropdown filter, spinner, AJAX-ready |
| `assets/js/script.js` | **আপডেট** — book AJAX block |
| `inc/meta-boxes.php` | **আপডেট** — book meta box যোগ |
| `templates/single-book.php` | **ছোট fix** — empty content check |
| `functions.php` | **আপডেট** — ajax-book-filters.php include |
