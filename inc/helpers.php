<?php
/**
 * Helper Functions
 * Custom utility functions used throughout the Hidayah theme.
 *
 * @package Hidayah
 */

/**
 * Returns a human-readable "time ago" string for a post.
 * All strings are translatable via .po/.mo language files.
 *
 * @param int|null $post_id Post ID. Defaults to current post.
 * @return string
 */
if ( ! function_exists( 'hidayah_bangla_time_ago' ) ) :
    function hidayah_bangla_time_ago( $post_id = null ) {
        $post_id = $post_id ?? get_the_ID();
        $time    = get_the_time( 'U', $post_id );
        $diff    = time() - $time;

        if ( $diff < 60 ) {
            /* translators: %d = number of seconds */
            return sprintf( _n( '%d second ago', '%d seconds ago', $diff, 'hidayah' ), $diff );
        } elseif ( $diff < 3600 ) {
            $mins = floor( $diff / 60 );
            /* translators: %d = number of minutes */
            return sprintf( _n( '%d minute ago', '%d minutes ago', $mins, 'hidayah' ), $mins );
        } elseif ( $diff < 86400 ) {
            $hours = floor( $diff / 3600 );
            /* translators: %d = number of hours */
            return sprintf( _n( '%d hour ago', '%d hours ago', $hours, 'hidayah' ), $hours );
        } elseif ( $diff < 604800 ) {
            $days = floor( $diff / 86400 );
            /* translators: %d = number of days */
            return sprintf( _n( '%d day ago', '%d days ago', $days, 'hidayah' ), $days );
        } elseif ( $diff < 2592000 ) {
            $weeks = floor( $diff / 604800 );
            /* translators: %d = number of weeks */
            return sprintf( _n( '%d week ago', '%d weeks ago', $weeks, 'hidayah' ), $weeks );
        } elseif ( $diff < 31536000 ) {
            $months = floor( $diff / 2592000 );
            /* translators: %d = number of months */
            return sprintf( _n( '%d month ago', '%d months ago', $months, 'hidayah' ), $months );
        } else {
            $years = floor( $diff / 31536000 );
            /* translators: %d = number of years */
            return sprintf( _n( '%d year ago', '%d years ago', $years, 'hidayah' ), $years );
        }
    }
endif;

/**
 * Converts English (ASCII) digits to Bangla digits.
 *
 * @param string|int $number The number to convert.
 * @return string
 */
if ( ! function_exists( 'hidayah_en_to_bn_number' ) ) :
    function hidayah_en_to_bn_number( $number ) {
        $en = array( '0', '1', '2', '3', '4', '5', '6', '7', '8', '9' );
        $bn = array( '০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯' );
        return str_replace( $en, $bn, $number );
    }
endif;


/**
 * Returns the archive/page title for banner headings.
 *
 * @return string
 */
if ( ! function_exists( 'hidayah_get_archive_title' ) ) :
    function hidayah_get_archive_title() {
        if ( is_search() ) {
            /* translators: %s = search query string */
            return sprintf( __( 'Search Results for "%s"', 'hidayah' ), get_search_query() );
        }
        if ( is_tax() || is_category() || is_tag() ) {
            return single_term_title( '', false );
        }
        if ( is_post_type_archive() ) {
            return post_type_archive_title( '', false );
        }
        if ( is_author() ) {
            return get_the_author();
        }
        return get_the_title();
    }
endif;

/**
 * Outputs an accessible numbered pagination.
 *
 * @return void
 */
if ( ! function_exists( 'hidayah_pagination' ) ) :
    function hidayah_pagination() {
        $paginate = paginate_links( array(
            'prev_text' => '&laquo; ' . __( 'Previous', 'hidayah' ),
            'next_text' => __( 'Next', 'hidayah' ) . ' &raquo;',
            'type'      => 'array',
        ) );

        if ( $paginate ) {
            echo '<nav class="hidayah-pagination" aria-label="' . esc_attr__( 'Pagination', 'hidayah' ) . '"><ul>';
            foreach ( $paginate as $link ) {
                echo '<li>' . $link . '</li>';
            }
            echo '</ul></nav>';
        }
    }
endif;

/**
 * Returns the full URL to a theme asset file.
 *
 * Usage: hidayah_asset( 'images/logo.png' )
 *
 * @param string $path Relative path inside assets/.
 * @return string
 */
if ( ! function_exists( 'hidayah_asset' ) ) :
    function hidayah_asset( $path ) {
        return HIDAYAH_URI . '/assets/' . ltrim( $path, '/' );
    }
endif;


// ── Short Aliases (used in index.php and other templates) ──────────────

/**
 * Short alias for hidayah_opt().
 *
 * @param string $key
 * @param mixed  $default
 * @return mixed
 */
if ( ! function_exists( 'h_opt' ) ) :
    function h_opt( $key, $default = '' ) {
        return hidayah_opt( $key, $default );
    }
endif;

/**
 * Short alias for hidayah_en_to_bn_number().
 *
 * @param string|int $number
 * @return string
 */
if ( ! function_exists( 'h_bn_num' ) ) :
    function h_bn_num( $number ) {
        return hidayah_en_to_bn_number( $number );
    }
endif;
/**
 * Auto-grabs audio duration from attachment metadata or URL.
 *
 * @param int $post_id Post ID.
 * @return string|false
 */
if ( ! function_exists( 'h_get_audio_duration' ) ) :
    function h_get_audio_duration( $post_id ) {
        // 1. Try to get it from the media library attachment FIRST
        $audio_url = get_post_meta( $post_id, '_audio_url', true );
        if ( $audio_url ) {
            $attachment_id = attachment_url_to_postid( $audio_url );
            if ( $attachment_id ) {
                $metadata = wp_get_attachment_metadata( $attachment_id );
                if ( ! empty( $metadata['length_formatted'] ) ) {
                    return $metadata['length_formatted'];
                }
                if ( ! empty( $metadata['length'] ) ) {
                    return floor( $metadata['length'] / 60 ); // Return minutes
                }
            }
        }

        // 2. Fallback to saved meta (for YouTube or external links)
        $duration = get_post_meta( $post_id, '_audio_duration', true );
        if ( ! empty( $duration ) ) {
            return $duration;
        }

        return false;
    }
endif;

/**
 * Get video duration from meta or YouTube API (optional).
 *
 * @param int $post_id
 * @return string
 */
if ( ! function_exists( 'h_get_video_duration' ) ) :
    function h_get_video_duration( $post_id ) {
        $manual = get_post_meta( $post_id, '_video_duration', true );
        if ( $manual ) {
            return $manual;
        }

        $yt_url = get_post_meta( $post_id, '_video_youtube_url', true );
        if ( $yt_url ) {
            preg_match( '/(?:v=|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $yt_url, $m );
            if ( isset( $m[1] ) ) {
                $api_key = h_opt( 'youtube_api_key' );
                if ( $api_key ) {
                    $cache_key = 'yt_dur_' . $m[1];
                    $cached = get_transient( $cache_key );
                    if ( $cached ) {
                        return $cached;
                    }

                    $response = wp_remote_get(
                        "https://www.googleapis.com/youtube/v3/videos?id={$m[1]}&part=contentDetails&key={$api_key}"
                    );
                    if ( ! is_wp_error( $response ) ) {
                        $data = json_decode( wp_remote_retrieve_body( $response ), true );
                        if ( isset( $data['items'][0]['contentDetails']['duration'] ) ) {
                            $iso = $data['items'][0]['contentDetails']['duration'];
                            preg_match( '/PT(?:(\d+)H)?(?:(\d+)M)?(?:(\d+)S)?/', $iso, $parts );
                            $h = isset( $parts[1] ) ? intval( $parts[1] ) : 0;
                            $min = isset( $parts[2] ) ? intval( $parts[2] ) : 0;
                            $sec = isset( $parts[3] ) ? intval( $parts[3] ) : 0;
                            $dur = $h > 0
                                ? sprintf( '%d:%02d:%02d', $h, $min, $sec )
                                : sprintf( '%d:%02d', $min, $sec );
                            set_transient( $cache_key, $dur, DAY_IN_SECONDS );
                            update_post_meta( $post_id, '_video_duration', $dur );
                            return $dur;
                        }
                    }
                }
            }
        }

        return '';
    }
endif;

/**
 * Returns the total number of answered questions for a specific uttordata term.
 *
 * @param int $uttordata_id The ID of the uttordata taxonomy term.
 * @return int
 */
if ( ! function_exists( 'h_get_uttordata_ans_count' ) ) :
    function h_get_uttordata_ans_count( $uttordata_id ) {
        if ( empty( $uttordata_id ) ) return 0;
        
        $cache_key = 'uttordata_count_tax_' . $uttordata_id;
        $cached    = get_transient( $cache_key );
        
        if ( false !== $cached ) {
            return (int) $cached;
        }

        $args = array(
            'post_type'      => 'dini_jiggasa',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'fields'         => 'ids',
            'tax_query'      => array(
                array(
                    'taxonomy' => 'uttordata',
                    'field'    => 'term_id',
                    'terms'    => $uttordata_id,
                ),
            ),
            'meta_query'     => array(
                array(
                    'key'     => '_jiggasa_status',
                    'value'   => 'answered',
                    'compare' => '='
                )
            )
        );
        
        $query = new WP_Query( $args );
        $count = $query->found_posts;
        
        // Cache the result for 12 hours
        set_transient( $cache_key, $count, 12 * HOUR_IN_SECONDS );
        
        return $count;
    }
endif;
