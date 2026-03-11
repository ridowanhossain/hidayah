# Hidayah থিম — ফাইল হায়ারার্কি
> সম্পূর্ণ থিম ফোল্ডার: `wp-content/themes/hidayah/`
> সর্বশেষ আপডেট: ২০২৬-০৩-১১

---

## ফাইল কল চেইন (কে কাকে ডাকে)

```
WordPress Core
    │
    ├──► functions.php                        ← সবার আগে লোড হয়
    │         │
    │         ├── inc/codestar-framework/codestar-framework.php  ← Codestar লোড (সবার আগে)
    │         │
    │         ├── inc/theme-support.php
    │         ├── inc/enqueue.php
    │         ├── inc/menus.php
    │         ├── inc/custom-post-types.php
    │         ├── inc/taxonomies.php
    │         ├── inc/widgets.php
    │         ├── inc/shortcodes.php
    │         ├── inc/helpers.php
    │         └── inc/cs-options.php           ← Codestar থিম অপশন প্যানেল
    │
    └──► index.php  (বা অন্য টেমপ্লেট)
              │
              ├── get_header() ──► header.php
              │                       └── get_template_part() ──► template-parts/header/site-header.php
              │
              ├── [পেজ কন্টেন্ট]
              │       └── get_template_part() ──► template-parts/content/content.php
              │
              └── get_footer() ──► footer.php
                                      └── get_template_part() ──► template-parts/footer/site-footer.php
```

---

## ফাইলসমূহ ও তাদের কাজ

### ROOT ফাইল (থিমের মূল)

| ফাইল | কাজ | স্ট্যাটাস |
|---|---|---|
| `style.css` | থিম হেডার (নাম, ভার্সন) + সমস্ত CSS | ✅ সম্পন্ন |
| `functions.php` | Codestar + সব `inc/` ফাইল লোড করে | ✅ সম্পন্ন |
| `index.php` | হোমপেজ — সব সেকশন WP_Query দিয়ে dynamic | ✅ সম্পন্ন |
| `header.php` | HTML boilerplate + `<head>` + `wp_head()` | ✅ সম্পন্ন |
| `footer.php` | `wp_footer()` + `</body></html>` | ✅ সম্পন্ন |
| `sidebar.php` | Widget Area রেন্ডার করে | ✅ সম্পন্ন |
| `single.php` | যেকোনো single পোস্টের fallback | ✅ সম্পন্ন |
| `archive.php` | যেকোনো archive-এর fallback | ✅ সম্পন্ন |
| `page.php` | Static পেজ টেমপ্লেট | ✅ সম্পন্ন |
| `404.php` | পেজ না পেলে দেখায় | ✅ সম্পন্ন |
| `search.php` | সার্চ রেজাল্ট পেজ | ✅ সম্পন্ন |
| `searchform.php` | সার্চ ফর্ম HTML | ✅ সম্পন্ন |

---

### `inc/` — PHP ফাংশন ও হুক

| ফাইল | কাজ | স্ট্যাটাস |
|---|---|---|
| `theme-support.php` | `add_theme_support()` — title-tag, thumbnail, html5, custom-logo | ✅ |
| `enqueue.php` | Google Fonts, Material Icons, `style.css`, `script.js` লোড করে | ✅ |
| `menus.php` | ৩টি মেনু লোকেশন: primary, footer, social | ✅ |
| `custom-post-types.php` | ৮টি CPT: audio, video, book, monthly_hd, dini_jiggasa, probondho, notice, photo_gallery | ✅ |
| `taxonomies.php` | ৪টি Taxonomy: topic, speaker, book_author, pub_year | ✅ |
| `widgets.php` | ৪টি Widget Area: sidebar-main, footer-1, footer-2, footer-3 | ✅ |
| `shortcodes.php` | `[hijri_date]` এবং `[donation_btn]` শর্টকোড | ✅ |
| `helpers.php` | `hidayah_bangla_time_ago()`, `hidayah_en_to_bn_number()`, `hidayah_pagination()`, `hidayah_asset()` | ✅ |
| `cs-options.php` | **Codestar Framework** থিম অপশন প্যানেল (৬টি সেকশন) | ✅ |
| `codestar-framework/` | Codestar Framework কোর ফাইল (v2.3.1) | ✅ |

---

### Codestar থিম অপশন প্যানেল বিস্তারিত (`inc/cs-options.php`)

Admin Menu: **Appearance → থিম অপশন**
হেল্পার ফাংশন: `hidayah_opt('key', 'default')`

| সেকশন | fields | ব্যবহার |
|---|---|---|
| সাধারণ সেটিংস | লোগো, ফেভিকন, হিরো টাইটেল, সাবটাইটেল, About টেক্সট, Promo টেক্সট | `hidayah_opt('hero_title')` |
| যোগাযোগ তথ্য | ইমেইল, ফোন (বাংলা), ফোন (raw/link), ঠিকানা, WhatsApp | `hidayah_opt('contact_email')` |
| সোশ্যাল মিডিয়া | Facebook, YouTube, WhatsApp, Telegram URL | `hidayah_opt('social_facebook')` |
| ফুটার সেটিংস | ফুটার বিবরণ, কপিরাইট, নিউজলেটার toggle | `hidayah_opt('footer_about_text')` |
| রঙ সেটিংস | Primary, Secondary, Accent রঙ + Custom CSS | `hidayah_opt('primary_color')` |
| Header/Footer কোড | GTM, Meta Pixel, Analytics স্ক্রিপ্ট ইঞ্জেক্ট | `hidayah_opt('header_scripts')` |

---

### `template-parts/` — রিইউজেবল HTML পার্ট

| ফাইল | কাজ | কে ডাকে | স্ট্যাটাস |
|---|---|---|---|
| `header/site-header.php` | লোগো, হিজরি তারিখ, নেভ মেনু, কার্ট, হাদিয়া বাটন | `header.php` | ✅ |
| `footer/site-footer.php` | ৪-কলাম ফুটার, নামাজের widget, ভিডিও মডাল, কার্ট মডাল | `footer.php` | ✅ |
| `content/content.php` | Generic পোস্ট কার্ড (fallback) | `index.php`, `archive.php` | ✅ |
| `content/content-none.php` | কিছু না পেলে দেখায় | সব আর্কাইভ | ✅ |

---

### `index.php` হোমপেজ সেকশনসমূহ

| সেকশন | ডেটা সোর্স | WP_Query |
|---|---|---|
| হিরো | `hidayah_opt('hero_title')` | — |
| নোটিশ মার্কি | `notice` CPT | ✅ |
| অডিও ট্যাব | `audio` CPT + `_audio_file_url` meta | ✅ |
| ভিডিও ট্যাব | `video` CPT + `_youtube_video_id` meta | ✅ |
| বই কর্নার স্লাইডার | `book` CPT + `_book_price` meta | ✅ |
| দরবার সম্পর্কে | `hidayah_opt('about_text')` | — |
| মাসিক হিদায়াহ | `monthly_hd` CPT + `_pdf_download_url` meta | ✅ |
| সর্বশেষ প্রবন্ধ | `probondho` CPT | ✅ |
| দরবার প্রমো ব্যানার | `hidayah_opt('promo_text')` | — |
| দ্বীনি জিজ্ঞাসা স্লাইডার | `dini_jiggasa` CPT | ✅ |
| যোগাযোগ CTA | `hidayah_opt('contact_email')` | — |

---

### CPT Single টেমপ্লেট

| ফাইল | HTML মকআপ | স্ট্যাটাস |
|---|---|---|
| `single-audio.php` | `single-audio.html` | ⏳ কনভার্ট বাকি |
| `single-video.php` | `single-video.html` | ⏳ কনভার্ট বাকি |
| `single-book.php` | `single-book.html` | ⏳ কনভার্ট বাকি |
| `single-monthly_hd.php` | `single-monthly-hd.html` | ⏳ কনভার্ট বাকি |
| `single-dini_jiggasa.php` | `single-dini-jiggasa.html` | ⏳ কনভার্ট বাকি |
| `single-probondho.php` | `single-probondho.html` | ⏳ কনভার্ট বাকি |
| `single-notice.php` | `single-notice.html` | ⏳ কনভার্ট বাকি |

---

### CPT Archive টেমপ্লেট

| ফাইল | HTML মকআপ | স্ট্যাটাস |
|---|---|---|
| `archive-audio.php` | `audio-archive.html` | ⏳ কনভার্ট বাকি |
| `archive-video.php` | `video-archive.html` | ⏳ কনভার্ট বাকি |
| `archive-book.php` | `book-archive.html` | ⏳ কনভার্ট বাকি |
| `archive-monthly_hd.php` | `monthly-hd-archive.html` | ⏳ কনভার্ট বাকি |
| `archive-dini_jiggasa.php` | `dini-jiggasa-archive.html` | ⏳ কনভার্ট বাকি |
| `archive-probondho.php` | `probondho-archive.html` | ⏳ কনভার্ট বাকি |
| `archive-notice.php` | `notice-archive.html` | ⏳ কনভার্ট বাকি |

---

### `assets/` — স্ট্যাটিক ফাইল

| পাথ | কাজ | স্ট্যাটাস |
|---|---|---|
| `assets/js/script.js` | মোবাইল মেনু, অডিও প্লেয়ার, ভিডিও মডাল, বুক স্লাইডার, হিজরি তারিখ, নামাজের সময় API (AlAdhan), Scroll Reveal | ✅ |
| `assets/images/` | লোগো ও অন্যান্য ছবি | — |
| `assets/mp3/` | অডিও ফাইল | — |

---

### `languages/` — অনুবাদ

| ফাইল | কাজ |
|---|---|
| `hidayah.pot` | অনুবাদের জন্য base ফাইল |

---

## Custom Post Type — Meta Fields রেফারেন্স

| CPT | Meta Key | মান |
|---|---|---|
| `audio` | `_audio_file_url` | অডিও ফাইলের URL |
| `audio` | `_audio_duration` | সময় (যেমন: ৪৫:৩০) |
| `audio` | `_audio_location` | স্থান (যেমন: ঢাকা) |
| `audio` | `_featured_audio` | `1` = ফিচার্ড |
| `video` | `_youtube_video_id` | YouTube video ID |
| `video` | `_video_location` | স্থান |
| `book` | `_book_price` | মূল্য |
| `book` | `_book_old_price` | পুরনো মূল্য (কাটা দাম) |
| `book` | `_book_badge` | ব্যাজ টেক্সট |
| `monthly_hd` | `_pdf_download_url` | PDF লিংক |
| `monthly_hd` | `_online_read_url` | অনলাইন পড়ার লিংক |
| `monthly_hd` | `_issue_contents` | বিষয়বস্তু (newline আলাদা) |
| `monthly_hd` | `_is_special_issue` | `1` = বিশেষ সংখ্যা |
| `dini_jiggasa` | `_post_views_count` | পাঠক সংখ্যা |

---

## WordPress টেমপ্লেট হায়ারার্কি (কোন URL-এ কোন ফাইল লোড হয়)

```
hoquerdawat.ridowan.online/               → index.php
hoquerdawat.ridowan.online/audio/[slug]  → single-audio.php
hoquerdawat.ridowan.online/audio/        → archive-audio.php
hoquerdawat.ridowan.online/video/[slug]  → single-video.php
hoquerdawat.ridowan.online/video/        → archive-video.php
hoquerdawat.ridowan.online/book/[slug]   → single-book.php
hoquerdawat.ridowan.online/book/         → archive-book.php
hoquerdawat.ridowan.online/[page-slug]   → page.php
hoquerdawat.ridowan.online/?s=query      → search.php
hoquerdawat.ridowan.online/xyz-nai       → 404.php
```

NOTE: সবচেয়ে নির্দিষ্ট ফাইল আগে লোড হয়।
`single-audio.php` না থাকলে → `single.php` → `index.php`

---

## পরবর্তী কাজের ক্রম

```
✅ 1.  functions.php + inc/ সেটআপ
✅ 2.  header.php + template-parts/header/site-header.php
✅ 3.  footer.php + template-parts/footer/site-footer.php
✅ 4.  index.php (হোমপেজ — সব সেকশন dynamic)
✅ 5.  assets/js/script.js (সব JS একসাথে)
✅ 6.  Codestar Framework সেটআপ (inc/codestar-framework/ + inc/cs-options.php)
✅ 7.  WooCommerce dependency bug fix (wc_get_cart_url → function_exists চেক)

⏳ 8.  single-audio.php    ← পরবর্তী
⏳ 9.  archive-audio.php
⏳ 10. single-video.php
⏳ 11. archive-video.php
⏳ 12. single-book.php
⏳ 13. archive-book.php
⏳ 14. single-monthly_hd.php
⏳ 15. archive-monthly_hd.php
⏳ 16. single-dini_jiggasa.php
⏳ 17. archive-dini_jiggasa.php
⏳ 18. single-probondho.php
⏳ 19. archive-probondho.php
⏳ 20. single-notice.php
⏳ 21. archive-notice.php
⏳ 22. page-contact.php (যোগাযোগ পেজ)
⏳ 23. page-darbar.php (দরবার পরিচিতি)
⏳ 24. page-admission.php (ভর্তি তথ্য)
⏳ 25. page-photo-gallery.php (ছবি গ্যালারি)
```
