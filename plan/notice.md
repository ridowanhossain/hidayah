# Notice Pages Modernization Plan (নোটিশ ও ঘোষণা)

## বর্তমান অবস্থা

| ফাইল | অবস্থা | মন্তব্য |
|---|---|---|
| `templates/archive-notice.php` | ⚠️ দুর্বল | Sort select কোনো action করে না (`onchange` নেই), AJAX নেই, Search form ভুল, global `$wp_query` ব্যবহার |
| `templates/single-notice.php` | ✅ ভালো | Urgency badge, attachment download, related notices সব আছে |
| `inc/ajax-notice-filters.php` | ❌ নেই | তৈরি করতে হবে |
| `inc/meta-boxes.php` → notice | ❌ নেই | Meta box নেই |

### আর্কাইভের বিদ্যমান সমস্যাগুলো:

1. Sort select-এ `onchange` নেই — ক্লিক করলে কিছু হয় না
2. Category Tabs ট্যাক্সোনমি আর্কাইভে নিয়ে যায়, কিন্তু `$notice_cats` আর্কাইভ পেজে define হয় না
3. `global $wp_query` দিয়ে count করা হচ্ছে → custom query-তে পরিবর্তন করতে হবে
4. Pinned notices sticky post logic ঠিক থাকলেও AJAX-এ skip করতে হবে

---

## ফেজ ১: Archive — AJAX ফিল্টারিং

### ১.১ — `inc/ajax-notice-filters.php` তৈরি

```php
add_action( 'wp_ajax_filter_notice', 'hidayah_filter_notice_callback' );
add_action( 'wp_ajax_nopriv_filter_notice', 'hidayah_filter_notice_callback' );
```

**বিশেষত্ব:**
- Filter by: `notice_category` taxonomy (term_id)
- Filter by: urgency level — `_notice_urgency` meta (`urgent`, `important`, `general`)
- Sort: `newest`, `oldest`
- Keyword search

```php
function hidayah_render_notice_card($is_pinned = false) {
    $urgency    = get_post_meta( get_the_ID(), '_notice_urgency', true ) ?: 'general';
    $attachment = get_post_meta( get_the_ID(), '_notice_attachment', true );
    $cats       = get_the_terms( get_the_ID(), 'notice_category' );
    // ...কার্ড HTML...
}
```

**AJAX context-এ Pinned Notice:**
- AJAX response-এ sticky posts সাধারণ posts-এর মতোই দেখানো হবে
- Sticky logic শুধু initial page load-এ প্রযোজ্য

### ১.২ — `archive-notice.php` সম্পূর্ণ রিফ্যাক্টর

বর্তমান ফাইলটিকে `archive-book.php`/`archive-video.php`-এর কাঠামোতে নিয়ে আসতে হবে:

```php
// TOP: Custom query দিয়ে শুরু (global $wp_query replace করতে হবে)
$notice_cats = get_terms(['taxonomy' => 'notice_category', 'hide_empty' => true]);
$notice_query = new WP_Query([...]);
```

**IDs যোগ করতে হবে:**
- `id="noticeSearchInput"`, `id="noticeSearchForm"`
- `id="noticeSortSelect"` (সাথে `onchange` remove)
- `id="noticeCatFilter"` — Category filter dropdown toolbar-এ আনতে হবে
- `id="noticeUrgencyFilter"` — Urgency filter dropdown (নতুন Feature):

```html
<select id="noticeUrgencyFilter">
    <option value="">সব ধরণ</option>
    <option value="urgent">জরুরি</option>
    <option value="important">গুরুত্বপূর্ণ</option>
    <option value="general">সাধারণ</option>
</select>
```

- Grid: `id="noticeList"`, Spinner, Pagination `id="noticePagination"`
- Count badge: `id="noticeCountBadge"`

**Category tabs → Buttons:**

```html
<!-- বর্তমান: taxonomy link-based tabs -->
<!-- পরিবর্তিত: AJAX button tabs -->
<div class="jiggasa-tabs">
    <button class="jiggasa-tab active" data-cat="">সকল</button>
    <?php foreach ($notice_cats as $cat) : ?>
        <button class="jiggasa-tab" data-cat="<?php echo $cat->term_id; ?>">
            <?php echo esc_html($cat->name); ?>
        </button>
    <?php endforeach; ?>
</div>
```

### ১.৩ — `script.js`-এ AJAX Block

```javascript
// action: 'filter_notice'
// Grid: #noticeList
// Loader: #noticeLoader
// Count: #noticeCountBadge
// Pagination: #noticePagination
// Tab buttons: .jiggasa-tab[data-cat]
// Urgency filter: #noticeUrgencyFilter
// Sort: #noticeSortSelect
// Search: #noticeSearchInput
```

---

## ফেজ ২: Single Notice — উন্নতি

`templates/single-notice.php` মূলত ভালো। ছোট সমস্যা:

### ২.১ — Expiry Date Warning

নোটিশের মেয়াদ সীমা যদি পার হয়ে গেছে, তাহলে warning দেখাতে হবে:

```php
<?php if ( $expiry_date ) :
    $expired = strtotime($expiry_date) < time();
?>
    <div class="notice-expiry-warning <?php echo $expired ? 'expired' : 'active'; ?>">
        <span class="material-symbols-outlined">
            <?php echo $expired ? 'event_busy' : 'event_available'; ?>
        </span>
        <?php if ($expired) : ?>
            <strong>এই নোটিশের মেয়াদ শেষ হয়েছে।</strong>
        <?php else : ?>
            <strong>মেয়াদ: <?php echo esc_html($expiry_date); ?> পর্যন্ত</strong>
        <?php endif; ?>
    </div>
<?php endif; ?>
```

### ২.২ — `$file_url` ও `$file_size` Variable Scope

বর্তমানে `_notice_attachment` থেকে attachment data নেওয়া হয় Notice Header-এ, কিন্তু sidebar-এও `$file_url` use করা হয়। Variable scope নিশ্চিত করতে হবে।

```php
// একবার নিয়ে সব জায়গায় ব্যবহার করতে হবে:
$file_url  = $attachment_id ? wp_get_attachment_url($attachment_id) : '';
$file_name = $attachment_id ? get_the_title($attachment_id) : '';
$file_size = $attachment_id && get_attached_file($attachment_id)
             ? size_format(filesize(get_attached_file($attachment_id))) : '';
$file_ext  = $attachment_id && get_attached_file($attachment_id)
             ? strtoupper(pathinfo(get_attached_file($attachment_id), PATHINFO_EXTENSION)) : '';
```

---

## ফেজ ৩: Meta Box — Notice Urgency & Attachment

| ফিল্ড নাম | Meta Key | ধরন |
|---|---|---|
| জরুরি মাত্রা | `_notice_urgency` | select (urgent/important/general) |
| সংযুক্তি ফাইল | `_notice_attachment` | file/media upload (attachment ID store করবে) |
| মেয়াদ তারিখ | `_notice_expiry_date` | date input |
| গুরুত্বপূর্ণ তারিখ | `_notice_important_dates` | textarea (formatted list) |

---

## Implementation Order

```
① archive-notice.php সম্পূর্ণ রিফ্যাক্টর (সবচেয়ে বেশি দরকার)
② inc/ajax-notice-filters.php তৈরি
③ script.js-এ notice AJAX block
④ meta-boxes.php-এ notice meta box
⑤ single-notice.php-এ expiry warning + variable scope fix
```

## ফাইল পরিবর্তনের সারসংক্ষেপ

| ফাইল | কী করতে হবে |
|---|---|
| `templates/archive-notice.php` | **সম্পূর্ণ রিফ্যাক্টর** — sort fix, AJAX tabs, custom query |
| `inc/ajax-notice-filters.php` | **নতুন তৈরি** |
| `assets/js/script.js` | **আপডেট** — notice AJAX block |
| `inc/meta-boxes.php` | **আপডেট** — notice meta box |
| `templates/single-notice.php` | **ছোট fix** — expiry warning, variable scope |
| `functions.php` | **আপডেট** — ajax include |
