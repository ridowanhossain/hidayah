# Dini Jiggasa Pages Modernization Plan (দ্বীনি জিজ্ঞাসা)

## বর্তমান অবস্থা

| ফাইল | অবস্থা | মন্তব্য |
|---|---|---|
| `templates/archive-dini_jiggasa.php` | ⚠️ আংশিক | Category filter slug-ভিত্তিক (GET param), Sort AJAX নেই, Tabs static reload |
| `templates/single-dini_jiggasa.php` | ✅ প্রায় সম্পন্ন | Question box, Answer box, voting, mufti card সব আছে |
| `inc/ajax-jiggasa-filters.php` | ❌ নেই | তৈরি করতে হবে |
| Voting AJAX handler | ❌ নেই | Thumb up/down বাটন কোনো backend ছাড়া |

---

## ফেজ ১: Archive — AJAX ফিল্টারিং

### ১.১ — `inc/ajax-jiggasa-filters.php` তৈরি

```php
add_action( 'wp_ajax_filter_jiggasa', 'hidayah_filter_jiggasa_callback' );
add_action( 'wp_ajax_nopriv_filter_jiggasa', 'hidayah_filter_jiggasa_callback' );
```

**বিশেষত্ব:**
- Filter by: `dini_jiggasa_cat` (term_id)
- Filter by status: `_jiggasa_status` meta (`answered` / `pending`)
- Sort: `newest`, `popular` (by `_post_views_count`)
- Keyword search

```php
function hidayah_render_jiggasa_card() {
    $status    = get_post_meta( get_the_ID(), '_jiggasa_status', true ) ?: 'answered';
    $mufti     = get_post_meta( get_the_ID(), '_jiggasa_mufti', true );
    $asker     = get_post_meta( get_the_ID(), '_jiggasa_asker_name', true ) ?: 'আনোনিমাস';
    $cat_terms = get_the_terms( get_the_ID(), 'dini_jiggasa_cat' );
    // ...কার্ড HTML...
}
```

### ১.২ — `archive-dini_jiggasa.php` রিফ্যাক্টর

**বর্তমান সমস্যা:**
- Category filter `$_GET['jiggasa_cat']` slug-ভিত্তিক — AJAX-এ `term_id` ব্যবহার করতে হবে
- Status filter (Tabs) page reload করে — AJAX-এ convert করতে হবে
- Sort select এখন URL redirect করে

**যা পরিবর্তন করতে হবে:**

1. Search, Sort, Category dropdown-এ IDs যোগ
2. Tab buttons AJAX-triggered করতে হবে:

```html
<!-- বর্তমান (link-based tabs): -->
<a href="<?php echo remove_query_arg('status'); ?>" class="jiggasa-tab">সকল</a>
<a href="<?php echo add_query_arg('status', 'answered'); ?>" class="jiggasa-tab">উত্তরিত</a>

<!-- পরিবর্তিত (AJAX-based): -->
<button class="jiggasa-tab active" data-status="">সকল</button>
<button class="jiggasa-tab" data-status="answered">উত্তরিত</button>
<button class="jiggasa-tab" data-status="pending">অপেক্ষমাণ</button>
```

3. Stats card (`jiggasa-stats-card`) — সমস্যা: `WP_Query` দিয়ে answered/pending count করা ব্যয়বহুল। সমাধান:

```php
// বর্তমানে 2টি আলাদা WP_Query চলছে — optimize করতে হবে:
$counts = array(
    'answered' => (int) $wpdb->get_var("SELECT COUNT(p.ID) FROM $wpdb->posts p
        INNER JOIN $wpdb->postmeta pm ON p.ID = pm.post_id
        WHERE p.post_type = 'dini_jiggasa' AND p.post_status = 'publish'
        AND pm.meta_key = '_jiggasa_status' AND pm.meta_value = 'answered'"),
    'pending' => ...
);
```

4. Content area wrapper:

```html
<div class="jiggasa-grid-wrapper" style="position:relative; min-height:200px;">
    <div id="jiggasaLoader" style="display:none;">...</div>
    <div class="jiggasa-list" id="jiggasaList">...</div>
</div>
<div id="jiggasaPagination">...</div>
```

### ১.৩ — `script.js`-এ AJAX Block

```javascript
// AJAX IDs: jiggasaSearchInput, jiggasaSortSelect, jiggasaCatFilter
// Status filter: .jiggasa-tab[data-status]
// action: 'filter_jiggasa'
// Grid ID: #jiggasaList
// Loader: #jiggasaLoader
// Count: #jiggasaCountBadge
// Pagination: #jiggasaPagination
```

---

## ফেজ ২: Single Dini Jiggasa — উন্নতি

`templates/single-dini_jiggasa.php` মূলত সম্পন্ন। কিন্তু Voting AJAX কাজ করছে না।

### ২.১ — Voting AJAX Handler তৈরি

বর্তমানে `.jiggasa-vote-btn` বাটন আছে কিন্তু backend handler নেই।

**PHP (functions.php বা আলাদা ফাইল):**

```php
function hidayah_jiggasa_vote() {
    check_ajax_referer('hidayah_ajax_nonce', 'nonce');
    $post_id = absint($_POST['post_id']);
    $type    = sanitize_text_field($_POST['type']); // 'up' or 'down'

    if (!$post_id || !in_array($type, ['up', 'down'])) {
        wp_send_json_error();
    }

    // Cookie দিয়ে double-vote রোধ
    $cookie_key = 'jiggasa_vote_' . $post_id;
    if (isset($_COOKIE[$cookie_key])) {
        wp_send_json_error(['message' => 'already_voted']);
    }

    $meta_key = $type === 'up' ? '_jiggasa_votes_up' : '_jiggasa_votes_down';
    $current  = (int) get_post_meta($post_id, $meta_key, true);
    update_post_meta($post_id, $meta_key, $current + 1);

    setcookie($cookie_key, '1', time() + 86400 * 30, '/');

    wp_send_json_success([
        'up'   => (int) get_post_meta($post_id, '_jiggasa_votes_up', true),
        'down' => (int) get_post_meta($post_id, '_jiggasa_votes_down', true),
    ]);
}
add_action('wp_ajax_jiggasa_vote', 'hidayah_jiggasa_vote');
add_action('wp_ajax_nopriv_jiggasa_vote', 'hidayah_jiggasa_vote');
```

**JavaScript:**

```javascript
document.querySelectorAll('.jiggasa-vote-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var data = new FormData();
        data.append('action', 'jiggasa_vote');
        data.append('nonce', hidayahAjax.nonce);
        data.append('post_id', this.dataset.id);
        data.append('type', this.dataset.type);

        fetch(hidayahAjax.url, { method: 'POST', body: data })
            .then(r => r.json())
            .then(function(res) {
                if (res.success) {
                    // Update count display
                    document.querySelector('.jiggasa-vote-btn.yes').innerHTML = '...' + res.data.up;
                    document.querySelector('.jiggasa-vote-btn.no').innerHTML = '...' + res.data.down;
                } else if (res.data?.message === 'already_voted') {
                    alert('আপনি আগেই ভোট দিয়েছেন।');
                }
            });
    });
});
```

### ২.২ — Pending Status Block

প্রশ্নের উত্তর না থাকলে একটি ভালো দেখানো "অপেক্ষমাণ" UI দেখানো:

```php
<?php if ( $status === 'pending' ) : ?>
    <div class="jiggasa-pending-notice">
        <span class="material-symbols-outlined">schedule</span>
        <h3>এই প্রশ্নের উত্তর প্রক্রিয়াধীন</h3>
        <p>বিশেষজ্ঞ আলেম উত্তর প্রদান করলে এখানে প্রদর্শিত হবে। ধন্যবাদ আপনার ধৈর্যের জন্য।</p>
    </div>
<?php endif; ?>
```

### ২.৩ — Print Optimization

বর্তমানে `window.print()` আছে। Print CSS যোগ করতে হবে যা vote section, sidebar, share বাটন সব hide করবে।

---

## ফেজ ৩: Meta Box — Jiggasa Meta Box

`inc/meta-boxes.php`-এ `dini_jiggasa` CPT-এর জন্য meta box তৈরি করতে হবে।

| ফিল্ড নাম | Meta Key | ধরন |
|---|---|---|
| প্রশ্নকর্তার নাম | `_jiggasa_asker_name` | text |
| প্রশ্নকর্তার জেলা | `_jiggasa_asker_location` | text |
| উত্তরদানকারী মুফতী | `_jiggasa_mufti` | text |
| মুফতীর পদবী | `_jiggasa_mufti_title` | text |
| মুফতীর ছবি | `_jiggasa_mufti_image` | image upload |
| মুফতীর উত্তর সংখ্যা | `_jiggasa_mufti_ans_count` | number |
| উত্তরের দলীল | `_jiggasa_dalil` | textarea |
| আরবি রেফারেন্স | `_jiggasa_arabic_ref` | textarea |
| স্ট্যাটাস | `_jiggasa_status` | select (answered/pending) |

---

## Implementation Order

```
① inc/ajax-jiggasa-filters.php তৈরি
② archive-dini_jiggasa.php রিফ্যাক্টর (Tabs AJAX, IDs, Spinner)
③ script.js-এ jiggasa AJAX block
④ Voting AJAX handler তৈরি + script.js-এ voting JS
⑤ meta-boxes.php-এ jiggasa meta box
⑥ single-dini_jiggasa.php-এ Pending UI + Print CSS
```

## ফাইল পরিবর্তনের সারসংক্ষেপ

| ফাইল | কী করতে হবে |
|---|---|
| `inc/ajax-jiggasa-filters.php` | **নতুন তৈরি** |
| `templates/archive-dini_jiggasa.php` | **রিফ্যাক্টর** — Tabs AJAX, IDs, Spinner |
| `assets/js/script.js` | **আপডেট** — jiggasa AJAX + voting JS |
| `inc/meta-boxes.php` | **আপডেট** — jiggasa meta box |
| `templates/single-dini_jiggasa.php` | **ছোট fix** — Pending UI |
| `functions.php` | **আপডেট** — voting handler + ajax-jiggasa-filters.php include |
