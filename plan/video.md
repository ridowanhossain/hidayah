# Video Pages Modernization Plan (ভিডিও লেকচার)

## বর্তমান অবস্থা

| ফাইল | অবস্থা | মন্তব্য |
|---|---|---|
| `templates/archive-video.php` | ⚠️ আংশিক | Search ও Sort GET-based reload, AJAX নেই |
| `templates/single-video.php` | ⚠️ আংশিক | Content empty-check নেই, speaker role static |
| `inc/ajax-video-filters.php` | ❌ নেই | তৈরি করতে হবে |
| `inc/meta-boxes.php` → video | ❌ নেই | Video meta box নেই |
| `inc/helpers.php` → video duration | ❌ নেই | `h_get_video_duration()` নেই |
| `inc/custom-post-types.php` | ⚠️ অতিরিক্ত | `excerpt`, `custom-fields` support আছে — সরাতে হবে |

> **⚠️ Meta Key নোট:** `single-video.php`-এ বাস্তবে `_youtube_video_id` (YouTube video ID string) ব্যবহার হচ্ছে, `_video_youtube_url` নয়। Meta box-এ উভয় field রাখতে হবে — URL থেকে ID auto-extract করার option সহ।

## Audio vs Video তুলনা

| বিষয় | Audio (সম্পন্ন) | Video (করতে হবে) |
|---|---|---|
| AJAX filter | ✅ `filter_audio` action | ❌ `filter_video` action তৈরি করতে হবে |
| Render card function | ✅ `hidayah_render_audio_card()` | ❌ `hidayah_render_video_card()` তৈরি |
| Duration helper | ✅ `h_get_audio_duration()` | ❌ `h_get_video_duration()` তৈরি |
| Meta box | ✅ Audio meta fields | ❌ Video meta fields নেই |
| AJAX handler file | ✅ `inc/ajax-audio-filters.php` | ❌ `inc/ajax-video-filters.php` নেই |

---

## ফেজ ১: Archive Video — AJAX ফিল্টারিং

### ১.১ — `inc/ajax-video-filters.php` তৈরি

`inc/ajax-audio-filters.php`-এর কাঠামো অনুসরণ করে তৈরি করতে হবে:

```php
add_action( 'wp_ajax_filter_video', 'hidayah_filter_video_callback' );
add_action( 'wp_ajax_nopriv_filter_video', 'hidayah_filter_video_callback' );

function hidayah_filter_video_callback() {
    check_ajax_referer( 'hidayah_ajax_nonce', 'nonce' );

    $search  = sanitize_text_field( $_POST['search'] ?? '' );
    $sort    = sanitize_text_field( $_POST['sort'] ?? 'newest' );
    $topic   = absint( $_POST['topic'] ?? 0 );   // taxonomy: video_topic
    $speaker = absint( $_POST['speaker'] ?? 0 ); // taxonomy: speaker
    $paged   = absint( $_POST['paged'] ?? 1 );

    $args = [
        'post_type'      => 'video',
        'posts_per_page' => 12,
        'paged'          => $paged,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ];

    if ( $search ) $args['s'] = $search;

    if ( $sort === 'oldest' ) {
        $args['order'] = 'ASC';
    } elseif ( $sort === 'popular' ) {
        $args['meta_key'] = '_post_views_count';
        $args['orderby']  = 'meta_value_num';
    }

    $tax_query = [];
    if ( $topic ) {
        $tax_query[] = [ 'taxonomy' => 'video_topic', 'field' => 'term_id', 'terms' => $topic ];
    }
    if ( $speaker ) {
        $tax_query[] = [ 'taxonomy' => 'speaker', 'field' => 'term_id', 'terms' => $speaker ];
    }
    if ( $tax_query ) {
        $args['tax_query'] = array_merge( [ 'relation' => 'AND' ], $tax_query );
    }

    $query = new WP_Query( $args );
    ob_start();
    if ( $query->have_posts() ) :
        while ( $query->have_posts() ) : $query->the_post();
            hidayah_render_video_card();
        endwhile;
    else :
        echo '<p class="no-results">' . __( 'কোনো ভিডিও পাওয়া যায়নি।', 'hidayah' ) . '</p>';
    endif;
    $html = ob_get_clean();
    wp_reset_postdata();

    wp_send_json_success( [
        'html'        => $html,
        'found_posts' => $query->found_posts,
        'max_pages'   => $query->max_num_pages,
        'paged'       => $paged,
    ] );
}

function hidayah_render_video_card() {
    $yt_id    = get_post_meta( get_the_ID(), '_youtube_video_id', true );  // YouTube video ID (e.g. dQw4w9WgXcQ)
    $duration = h_get_video_duration( get_the_ID() );
    $views    = get_post_meta( get_the_ID(), '_post_views_count', true ) ?: 0;
    $location = get_post_meta( get_the_ID(), '_video_location', true );
    $speakers = get_the_terms( get_the_ID(), 'speaker' );
    $topics   = get_the_terms( get_the_ID(), 'video_topic' );

    // Thumbnail: YouTube thumbnail বা featured image
    $thumb_url = '';
    if ( $yt_id ) {
        $thumb_url = 'https://img.youtube.com/vi/' . $yt_id . '/hqdefault.jpg';
    }
    if ( !$thumb_url && has_post_thumbnail() ) {
        $thumb_url = get_the_post_thumbnail_url( get_the_ID(), 'medium' );
    }
    ?>
    <article class="video-card" data-video-id="<?php echo esc_attr($yt_id); ?>">
        <a class="video-card-thumb-link" href="<?php the_permalink(); ?>">
            <div class="video-thumb-wrapper">
                <?php if ($thumb_url) : ?>
                    <img src="<?php echo esc_url($thumb_url); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" />
                <?php endif; ?>
                <div class="video-play-overlay">
                    <span class="material-symbols-outlined">play_circle</span>
                </div>
                <?php if ($duration) : ?>
                    <span class="video-duration-badge"><?php echo esc_html($duration); ?></span>
                <?php endif; ?>
            </div>
        </a>
        <div class="video-card-content">
            <?php if (!empty($topics)) : ?>
                <span class="video-topic-badge"><?php echo esc_html($topics[0]->name); ?></span>
            <?php endif; ?>
            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            <div class="video-card-meta">
                <?php if (!empty($speakers)) : ?>
                    <span>
                        <span class="material-symbols-outlined">person</span>
                        <?php echo esc_html($speakers[0]->name); ?>
                    </span>
                <?php endif; ?>
                <span>
                    <span class="material-symbols-outlined">calendar_month</span>
                    <?php echo get_the_date(); ?>
                </span>
                <?php if ($location) : ?>
                    <span>
                        <span class="material-symbols-outlined">location_on</span>
                        <?php echo esc_html($location); ?>
                    </span>
                <?php endif; ?>
                <?php if ($views) : ?>
                    <span>
                        <span class="material-symbols-outlined">visibility</span>
                        <?php echo hidayah_en_to_bn_number(number_format_i18n($views)); ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </article>
    <?php
}
```

### ১.২ — `archive-video.php` রিফ্যাক্টর

**বর্তমান সমস্যা:**
- Search form `method="get"` দিয়ে page reload করে
- Sort select `onchange="window.location.href=this.value"` — redirect করে
- Grid wrapper-এ কোনো ID নেই

**পরিবর্তন:**

1. Toolbar IDs যোগ:

```html
<div class="archive-search-bar">
    <span class="material-symbols-outlined">search</span>
    <input class="archive-search-input" id="videoSearchInput"
           placeholder="<?php _e( 'ভিডিও খুঁজুন...', 'hidayah' ); ?>" type="text" />
</div>
<div class="archive-toolbar-right">
    <select class="archive-sort-select" id="videoSortSelect">
        <option value="newest"><?php _e( 'নতুন প্রথমে', 'hidayah' ); ?></option>
        <option value="oldest"><?php _e( 'পুরাতন প্রথমে', 'hidayah' ); ?></option>
        <option value="popular"><?php _e( 'জনপ্রিয়', 'hidayah' ); ?></option>
    </select>
</div>
```

2. Taxonomy Filters ব্লক অ্যাড করতে হবে (টপিক + বক্তা):

```html
<div class="archive-filters-toolbar">
    <div class="archive-count-badge" id="videoCountBadge">
        <span class="material-symbols-outlined">videocam</span>
        <?php printf( __( 'মোট %sটি ভিডিও', 'hidayah' ), hidayah_en_to_bn_number($found_posts) ); ?>
    </div>
    <div class="archive-taxonomy-filters">
        <select id="videoTopicFilter">
            <option value=""><?php _e( 'বিষয় অনুযায়ী', 'hidayah' ); ?></option>
            <?php foreach (get_terms(['taxonomy' => 'video_topic']) as $t) : ?>
                <option value="<?php echo $t->term_id; ?>"><?php echo esc_html($t->name); ?></option>
            <?php endforeach; ?>
        </select>
        <select id="videoSpeakerFilter">
            <option value=""><?php _e( 'বক্তা অনুযায়ী', 'hidayah' ); ?></option>
            <?php foreach (get_terms(['taxonomy' => 'speaker']) as $sp) : ?>
                <option value="<?php echo $sp->term_id; ?>"><?php echo esc_html($sp->name); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>
```

3. Grid ও pagination wrapper:

```html
<div style="position:relative; min-height: 200px;">
    <div id="videoLoader" class="archive-ajax-loader" style="display:none;">
        <span class="material-symbols-outlined rotating">progress_activity</span>
    </div>
    <div class="video-archive-grid" id="videoArchiveGrid">
        <?php /* PHP-rendered initial cards */ ?>
    </div>
</div>
<div id="videoPagination">
    <?php hidayah_pagination(); ?>
</div>
```

### ১.৩ — `script.js`-এ AJAX Block

`archive-audio.php`-এর JS-এর মতো করে:

```javascript
(function() {
    var grid   = document.getElementById('videoArchiveGrid');
    var loader = document.getElementById('videoLoader');
    var count  = document.getElementById('videoCountBadge');
    var paging = document.getElementById('videoPagination');
    if (!grid) return;

    var searchInput   = document.getElementById('videoSearchInput');
    var sortSelect    = document.getElementById('videoSortSelect');
    var topicFilter   = document.getElementById('videoTopicFilter');
    var speakerFilter = document.getElementById('videoSpeakerFilter');
    var currentPage   = 1;
    var debounceTimer;

    function fetchVideos(page) {
        page = page || 1;
        currentPage = page;
        loader.style.display = 'flex';
        grid.style.opacity   = '0.4';

        var data = new FormData();
        data.append('action',  'filter_video');
        data.append('nonce',   hidayahAjax.nonce);
        data.append('search',  searchInput ? searchInput.value : '');
        data.append('sort',    sortSelect  ? sortSelect.value  : 'newest');
        data.append('topic',   topicFilter   ? topicFilter.value   : '');
        data.append('speaker', speakerFilter ? speakerFilter.value : '');
        data.append('paged',   page);

        fetch(hidayahAjax.url, { method: 'POST', body: data })
            .then(function(r) { return r.json(); })
            .then(function(res) {
                loader.style.display  = 'none';
                grid.style.opacity    = '1';
                if (!res.success) return;
                grid.innerHTML = res.data.html;
                if (count) {
                    count.innerHTML = '<span class="material-symbols-outlined">videocam</span> মোট ' +
                        res.data.found_posts + 'টি ভিডিও';
                }
                buildPagination(res.data.found_posts, res.data.max_pages, page);
                // Re-initialize YouTube players after AJAX load
                if (typeof window.initVideoPlayers === 'function') window.initVideoPlayers();
            });
    }

    function buildPagination(total, maxPages, current) { /* audio-এর মতো */ }

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(function() { fetchVideos(1); }, 400);
        });
    }
    [sortSelect, topicFilter, speakerFilter].forEach(function(el) {
        if (el) el.addEventListener('change', function() { fetchVideos(1); });
    });
})();
```

---

## ফেজ ২: Single Video — উন্নতি

### ২.১ — Empty Content Check

```php
// বর্তমান: unconditional section
// সঠিক:
<?php if ( get_the_content() ) : ?>
    <div class="single-video-desc sb-section entry-content">
        <h2 class="sb-section-title"><?php _e( 'ভিডিওর সারসংক্ষেপ', 'hidayah' ); ?></h2>
        <?php the_content(); ?>
    </div>
<?php endif; ?>
```

### ২.২ — Speaker Role Dynamic Display

বর্তমানে speaker role static text দেখানো হচ্ছে। Meta থেকে role নেওয়া উচিত:

```php
// বর্তমান:
<?php if ( !empty($speakers) ) : ?>
    <p><?php echo esc_html($speakers[0]->name); ?></p>
    <span>ভাষণকারী</span> <!-- Static! -->

// সঠিক:
<?php
$speaker_role = get_post_meta( get_the_ID(), '_speaker_role', true )
    ?: ( !empty($speakers) ? term_description($speakers[0]->term_id) : __('ভাষণকারী', 'hidayah') );
?>
    <p><?php echo esc_html($speakers[0]->name); ?></p>
    <span><?php echo esc_html(wp_strip_all_tags($speaker_role)); ?></span>
```

### ২.৩ — Video Player Re-initialization (YouTube)

AJAX load-এর পর YouTube IFrame-গুলো reinitialize করতে হবে:

```javascript
window.initVideoPlayers = function() {
    // YouTube embed iframe গুলো reload করা
    document.querySelectorAll('.video-card-thumb-link[data-yt]').forEach(function(el) {
        // lazy-load thumbnail → click to embed
    });
};
```

Single page-এ `single-video.php`-এ YouTube IFrame বা `<video>` tag থাকে। বর্তমানে audio-র মতো player init নেই। `initVideoPlayers()` function archive AJAX callback-এও call হবে।

---

## ফেজ ৩: Helpers — `h_get_video_duration()`

`inc/helpers.php`-এ `h_get_audio_duration()`-এর মতো যোগ করতে হবে:

```php
/**
 * Get video duration from meta or YouTube API.
 *
 * @param int $post_id
 * @return string Duration string e.g. "১:৩০:০০" or ""
 */
function h_get_video_duration( $post_id ) {
    // Priority 1: Manually saved meta
    $manual = get_post_meta( $post_id, '_video_duration', true );
    if ( $manual ) return $manual;

    // Priority 2: YouTube Data API (Optional — requires API Key)
    $yt_url = get_post_meta( $post_id, '_video_youtube_url', true );
    if ( $yt_url ) {
        preg_match( '/(?:v=|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $yt_url, $m );
        if ( isset( $m[1] ) ) {
            $api_key  = h_opt( 'youtube_api_key' );
            if ( $api_key ) {
                $cache_key = 'yt_dur_' . $m[1];
                $cached    = get_transient( $cache_key );
                if ( $cached ) return $cached;

                $response = wp_remote_get(
                    "https://www.googleapis.com/youtube/v3/videos?id={$m[1]}&part=contentDetails&key={$api_key}"
                );
                if ( !is_wp_error($response) ) {
                    $data = json_decode( wp_remote_retrieve_body($response), true );
                    if ( isset($data['items'][0]['contentDetails']['duration']) ) {
                        $iso = $data['items'][0]['contentDetails']['duration']; // e.g. PT1H30M0S
                        preg_match('/PT(?:(\d+)H)?(?:(\d+)M)?(?:(\d+)S)?/', $iso, $parts);
                        $h   = isset($parts[1]) ? intval($parts[1]) : 0;
                        $min = isset($parts[2]) ? intval($parts[2]) : 0;
                        $sec = isset($parts[3]) ? intval($parts[3]) : 0;
                        $dur = $h > 0
                            ? sprintf('%d:%02d:%02d', $h, $min, $sec)
                            : sprintf('%d:%02d', $min, $sec);
                        // Bengali number convert
                        $dur = hidayah_en_to_bn_number($dur);
                        set_transient($cache_key, $dur, DAY_IN_SECONDS);
                        update_post_meta($post_id, '_video_duration', $dur); // Cache in meta too
                        return $dur;
                    }
                }
            }
        }
    }
    return '';
}
```

---

## ফেজ ৪: Meta Box — Video Fields

`inc/meta-boxes.php`-এ `video` CPT-এর জন্য meta box তৈরি করতে হবে:

| ফিল্ড নাম | Meta Key | ধরন | বিবরণ |
|---|---|---|---|
| YouTube ID | `_youtube_video_id` | text | YouTube video ID (বর্তমান theme-এ ব্যবহৃত) |
| YouTube URL | `_video_youtube_url` | url | YouTube embed link (URL দিলে ID auto-extract হবে) |
| সরাসরি ভিডিও URL | `_video_direct_url` | url | MP4 বা অন্য ভিডিও |
| ভিডিও ডিউরেশন | `_video_duration` | text | e.g. ১:৩০:০০ |
| স্থান (Location) | `_video_location` | text | ভিডিওর স্থান/মাহফিল |
| বক্তার ভূমিকা | `_speaker_role` | text | e.g. পীর-ই-কামেল |

**Preview in Admin:**

```php
// YouTube URL সেভ হলে admin-এ preview দেখাবে (audio meta box-এর মতো):
if ( $yt_url ) {
    $vid_id = /* extract ID */;
    echo '<div class="yt-preview-wrap">';
    echo '<iframe width="300" height="169" src="https://www.youtube.com/embed/' . esc_attr($vid_id) . '" frameborder="0" allowfullscreen></iframe>';
    echo '</div>';
}
```

---

## ফেজ ৫: CPT Cleanup

`inc/custom-post-types.php`-এ `video` CPT থেকে অপ্রয়োজনীয় supports সরাতে হবে:

```php
// বর্তমান:
'supports' => ['title', 'thumbnail', 'excerpt', 'custom-fields'],

// সঠিক:
'supports' => ['title', 'thumbnail'],
```

---

## Implementation Order

```
① inc/ajax-video-filters.php তৈরি (hidayah_render_video_card সহ)
② archive-video.php রিফ্যাক্টর (IDs, Taxonomy dropdowns, Spinner)
③ script.js-এ video AJAX block + initVideoPlayers()
④ inc/helpers.php-এ h_get_video_duration() যোগ
⑤ inc/meta-boxes.php-এ video meta box (YouTube URL, duration, speaker role)
⑥ inc/custom-post-types.php-এ CPT supports cleanup
⑦ templates/single-video.php-এ empty content check + speaker role fix
⑧ functions.php-এ ajax-video-filters.php include
```

## ফাইল পরিবর্তনের সারসংক্ষেপ

| ফাইল | কী করতে হবে |
|---|---|
| `inc/ajax-video-filters.php` | **নতুন তৈরি** — filter callback + render card function |
| `templates/archive-video.php` | **রিফ্যাক্টর** — IDs, taxonomy dropdowns, spinner, pagination wrapper |
| `assets/js/script.js` | **আপডেট** — video AJAX block + `initVideoPlayers()` |
| `inc/helpers.php` | **আপডেট** — `h_get_video_duration()` যোগ |
| `inc/meta-boxes.php` | **আপডেট** — video meta box যোগ |
| `inc/custom-post-types.php` | **আপডেট** — `excerpt`, `custom-fields` support সরানো |
| `templates/single-video.php` | **ছোট fix** — empty content check + speaker role fix |
| `functions.php` | **আপডেট** — ajax-video-filters.php include |
