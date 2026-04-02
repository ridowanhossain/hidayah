<?php
/**
 * Hidayah Theme - SEO Engine
 *
 * Handles:
 *  1. Page title tag customisation
 *  2. Meta description
 *  3. Canonical URL
 *  4. Robots meta (noindex for archives when opted-in)
 *  5. Search engine verification codes
 *  6. JSON-LD structured data:
 *       - WebSite + Organization (homepage)
 *       - Article         (probondho)
 *       - AudioObject     (audio)
 *       - VideoObject     (video)
 *       - QAPage          (dini_jiggasa)
 *       - SpecialAnnouncement (notice)
 *       - ImageGallery    (photo_gallery)
 *       - PublicationIssue (monthly_magazine)
 *       - BreadcrumbList  (all singular pages)
 *
 * All output is controlled by toggles in Hidayah Theme Settings > SEO.
 * If Yoast SEO / Rank Math / AIOSEO is active, this file is fully skipped.
 *
 * @package Hidayah
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// ──────────────────────────────────────────────────────
// Bail if a dedicated SEO plugin is active
// ──────────────────────────────────────────────────────
function hidayah_seo_plugin_active() {
    return defined( 'WPSEO_VERSION' )       // Yoast
        || defined( 'RANK_MATH_VERSION' )   // Rank Math
        || defined( 'AIOSEO_VERSION' );     // All in One SEO
}

// ──────────────────────────────────────────────────────
// Helper: build the OG / share image URL for a post
// ──────────────────────────────────────────────────────
function hidayah_seo_get_image( $post_id ) {
    // 1. Featured image
    if ( has_post_thumbnail( $post_id ) ) {
        $src = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'large' );
        if ( $src ) return $src[0];
    }

    // 2. Post-type-specific meta
    $fallback_keys = [
        '_magazine_cover', '_audio_thumbnail', '_thumbnail_url',
    ];
    foreach ( $fallback_keys as $key ) {
        $val = get_post_meta( $post_id, $key, true );
        if ( ! $val ) continue;
        if ( is_numeric( $val ) ) {
            $src = wp_get_attachment_image_src( (int) $val, 'large' );
            if ( $src ) return $src[0];
        }
        if ( filter_var( $val, FILTER_VALIDATE_URL ) ) return esc_url( $val );
    }

    // 3. First image from gallery
    $gallery = get_post_meta( $post_id, '_gallery_images', true );
    if ( is_array( $gallery ) && ! empty( $gallery ) ) {
        $src = wp_get_attachment_image_src( $gallery[0], 'large' );
        if ( $src ) return $src[0];
    }

    // 4. Default OG image from SEO settings
    $default_og = hidayah_opt( 'seo_default_og_image', [] );
    if ( ! empty( $default_og['url'] ) ) return esc_url( $default_og['url'] );

    // 5. Site logo
    $logo = hidayah_opt( 'seo_org_logo', [] );
    if ( ! empty( $logo['url'] ) ) return esc_url( $logo['url'] );

    $custom_logo_id = get_theme_mod( 'custom_logo' );
    if ( $custom_logo_id ) {
        $src = wp_get_attachment_image_src( $custom_logo_id, 'full' );
        if ( $src ) return $src[0];
    }

    return '';
}

// ──────────────────────────────────────────────────────
// Helper: build meta description for a post
// ──────────────────────────────────────────────────────
function hidayah_seo_get_description( $post_id = 0 ) {
    if ( $post_id && has_excerpt( $post_id ) ) {
        return wp_strip_all_tags( get_the_excerpt( $post_id ) );
    }
    if ( $post_id ) {
        $content = get_post_field( 'post_content', $post_id );
        if ( $content ) {
            return wp_trim_words( wp_strip_all_tags( $content ), 25, '...' );
        }
    }
    return wp_strip_all_tags( hidayah_opt( 'seo_site_description', get_bloginfo( 'description' ) ) );
}

// ──────────────────────────────────────────────────────
// 1. Customise <title> tag
// ──────────────────────────────────────────────────────
add_filter( 'pre_get_document_title', function( $title ) {
    if ( hidayah_seo_plugin_active() ) return $title;

    $sep       = esc_html( hidayah_opt( 'seo_title_separator', '|' ) );
    $site_name = get_bloginfo( 'name' );

    if ( is_singular() ) {
        return get_the_title() . ' ' . $sep . ' ' . $site_name;
    }
    if ( is_home() || is_front_page() ) {
        $desc = hidayah_opt( 'seo_site_description', get_bloginfo( 'description' ) );
        return $site_name . ( $desc ? ' ' . $sep . ' ' . $desc : '' );
    }
    if ( is_tax() || is_category() || is_tag() ) {
        $term = get_queried_object();
        return ( $term ? $term->name : '' ) . ' ' . $sep . ' ' . $site_name;
    }
    if ( is_post_type_archive() ) {
        return post_type_archive_title( '', false ) . ' ' . $sep . ' ' . $site_name;
    }
    return $title;
} );

// ──────────────────────────────────────────────────────
// 2. wp_head: meta description, canonical, robots,
//    verification codes, JSON-LD
// ──────────────────────────────────────────────────────
add_action( 'wp_head', 'hidayah_seo_head_tags', 4 );

function hidayah_seo_head_tags() {
    if ( hidayah_seo_plugin_active() ) return;

    global $post;

    $post_id      = is_singular() && $post ? $post->ID : 0;
    $enable_schema      = hidayah_opt( 'seo_enable_schema', true );
    $enable_breadcrumb  = hidayah_opt( 'seo_enable_breadcrumb_schema', true );
    $enable_org         = hidayah_opt( 'seo_enable_org_schema', true );
    $enable_canonical   = hidayah_opt( 'seo_enable_canonical', true );
    $noindex_archives   = hidayah_opt( 'seo_noindex_archives', false );

    // ── meta description ──────────────────────────────
    $description = hidayah_seo_get_description( $post_id );
    if ( $description ) {
        echo '<meta name="description" content="' . esc_attr( $description ) . '">' . "\n";
    }

    // ── Robots ───────────────────────────────────────
    if ( $noindex_archives && ( is_tax() || is_category() || is_tag() || is_date() ) ) {
        echo '<meta name="robots" content="noindex,follow">' . "\n";
    }

    // ── Canonical URL ────────────────────────────────
    if ( $enable_canonical ) {
        if ( is_singular() && $post_id ) {
            $canonical = get_permalink( $post_id );
        } elseif ( is_home() || is_front_page() ) {
            $canonical = home_url( '/' );
        } else {
            $canonical = '';
        }
        if ( $canonical ) {
            echo '<link rel="canonical" href="' . esc_url( $canonical ) . '">' . "\n";
        }
    }

    // ── Verification Codes ────────────────────────────
    $google_code = hidayah_opt( 'seo_google_verification' );
    if ( $google_code ) {
        echo '<meta name="google-site-verification" content="' . esc_attr( $google_code ) . '">' . "\n";
    }
    $bing_code = hidayah_opt( 'seo_bing_verification' );
    if ( $bing_code ) {
        echo '<meta name="msvalidate.01" content="' . esc_attr( $bing_code ) . '">' . "\n";
    }

    // ── JSON-LD Schema ────────────────────────────────
    $schemas = [];

    // (a) WebSite + Organization on homepage
    if ( ( is_home() || is_front_page() ) && $enable_org ) {
        $org_name  = hidayah_opt( 'seo_org_name', get_bloginfo( 'name' ) );
        $org_type  = hidayah_opt( 'seo_org_type', 'ReligiousOrganization' );
        $org_desc  = hidayah_opt( 'seo_org_description', get_bloginfo( 'description' ) );
        $org_year  = hidayah_opt( 'seo_founding_year' );
        $founder   = hidayah_opt( 'seo_founder_name' );
        $logo_opt  = hidayah_opt( 'seo_org_logo', [] );
        $logo_url  = ! empty( $logo_opt['url'] ) ? $logo_opt['url'] : '';
        if ( ! $logo_url ) {
            $logo_id = get_theme_mod( 'custom_logo' );
            if ( $logo_id ) {
                $lsrc = wp_get_attachment_image_src( $logo_id, 'full' );
                $logo_url = $lsrc ? $lsrc[0] : '';
            }
        }

        $fb_url       = hidayah_opt( 'social_facebook' );
        $yt_url       = hidayah_opt( 'social_youtube' );
        $wa_url       = hidayah_opt( 'social_whatsapp' );
        $tw_handle    = hidayah_opt( 'seo_twitter_handle' );
        $same_as      = array_filter( [
            $fb_url,
            $yt_url,
            $wa_url,
            $tw_handle ? 'https://twitter.com/' . $tw_handle : '',
        ] );

        $org_schema = [
            '@context' => 'https://schema.org',
            '@type'    => $org_type,
            'name'     => $org_name,
            'url'      => home_url( '/' ),
            'description' => $org_desc,
        ];
        if ( $logo_url )   $org_schema['logo']        = $logo_url;
        if ( $org_year )   $org_schema['foundingDate'] = $org_year;
        if ( $founder )    $org_schema['founder']     = [ '@type' => 'Person', 'name' => $founder ];
        if ( $same_as )    $org_schema['sameAs']      = array_values( $same_as );

        $schemas[] = $org_schema;

        // WebSite (enables sitelinks searchbox)
        $schemas[] = [
            '@context'        => 'https://schema.org',
            '@type'           => 'WebSite',
            'name'            => $org_name,
            'url'             => home_url( '/' ),
            'potentialAction' => [
                '@type'       => 'SearchAction',
                'target'      => [
                    '@type'       => 'EntryPoint',
                    'urlTemplate' => home_url( '/?s={search_term_string}' ),
                ],
                'query-input' => 'required name=search_term_string',
            ],
        ];
    }

    // (b) Singular post schemas
    if ( is_singular() && $post_id && $enable_schema ) {
        $post_type   = get_post_type( $post_id );
        $title       = get_the_title( $post_id );
        $url         = get_permalink( $post_id );
        $date_pub    = get_the_date( 'c', $post_id );
        $date_mod    = get_the_modified_date( 'c', $post_id );
        $image       = hidayah_seo_get_image( $post_id );
        $description = hidayah_seo_get_description( $post_id );
        $org_name    = hidayah_opt( 'seo_org_name', get_bloginfo( 'name' ) );
        $founder     = hidayah_opt( 'seo_founder_name', $org_name );

        $publisher = [
            '@type' => hidayah_opt( 'seo_org_type', 'ReligiousOrganization' ),
            'name'  => $org_name,
            'url'   => home_url( '/' ),
        ];
        $logo_opt = hidayah_opt( 'seo_org_logo', [] );
        if ( ! empty( $logo_opt['url'] ) ) $publisher['logo'] = $logo_opt['url'];

        switch ( $post_type ) {

            // ── Article (Probondho) ───────────────────
            case 'probondho':
                $read_time = get_post_meta( $post_id, '_reading_time', true );
                $schema = [
                    '@context'         => 'https://schema.org',
                    '@type'            => 'Article',
                    'headline'         => $title,
                    'url'              => $url,
                    'datePublished'    => $date_pub,
                    'dateModified'     => $date_mod,
                    'description'      => $description,
                    'publisher'        => $publisher,
                    'author'           => [ '@type' => 'Person', 'name' => $founder ],
                    'inLanguage'       => 'bn-BD',
                    'articleSection'   => 'Islamic Articles',
                ];
                if ( $image )     $schema['image']          = $image;
                if ( $read_time ) $schema['timeRequired']   = 'PT' . intval( $read_time ) . 'M';
                $schemas[] = $schema;
                break;

            // ── AudioObject (Audio) ───────────────────
            case 'audio':
                $audio_url  = get_post_meta( $post_id, '_audio_url', true );
                $duration   = get_post_meta( $post_id, '_audio_duration', true );
                $location   = get_post_meta( $post_id, '_mahfil_location', true );
                $speakers   = get_the_terms( $post_id, 'speaker' );
                $spk_name   = ! empty( $speakers ) ? $speakers[0]->name : $founder;

                $schema = [
                    '@context'      => 'https://schema.org',
                    '@type'         => 'AudioObject',
                    'name'          => $title,
                    'url'           => $url,
                    'datePublished' => $date_pub,
                    'description'   => $description,
                    'author'        => [ '@type' => 'Person', 'name' => $spk_name ],
                    'publisher'     => $publisher,
                    'inLanguage'    => 'bn-BD',
                ];
                if ( $image )     $schema['thumbnailUrl'] = $image;
                if ( $audio_url ) $schema['contentUrl']   = $audio_url;
                if ( $duration )  $schema['duration']     = 'PT' . intval( $duration ) . 'M';
                if ( $location )  $schema['locationCreated'] = [ '@type' => 'Place', 'name' => $location ];
                $schemas[] = $schema;
                break;

            // ── VideoObject (Video) ───────────────────
            case 'video':
                $yt_id    = get_post_meta( $post_id, '_youtube_video_id', true );
                $duration = get_post_meta( $post_id, '_video_duration', true );
                $speakers = get_the_terms( $post_id, 'speaker' );
                $spk_name = ! empty( $speakers ) ? $speakers[0]->name : $founder;

                $schema = [
                    '@context'      => 'https://schema.org',
                    '@type'         => 'VideoObject',
                    'name'          => $title,
                    'url'           => $url,
                    'datePublished' => $date_pub,
                    'description'   => $description,
                    'author'        => [ '@type' => 'Person', 'name' => $spk_name ],
                    'publisher'     => $publisher,
                    'inLanguage'    => 'bn-BD',
                ];
                if ( $image )  $schema['thumbnailUrl'] = $image;
                if ( $yt_id )  $schema['embedUrl']     = 'https://www.youtube.com/embed/' . $yt_id;
                if ( $duration ) $schema['duration']   = 'PT' . intval( $duration ) . 'M';
                $schemas[] = $schema;
                break;

            // ── QAPage (Dini Jiggasa) ─────────────────
            case 'dini_jiggasa':
                $answer_text = wp_strip_all_tags( get_the_content( null, false, $post_id ) );
                $schema = [
                    '@context' => 'https://schema.org',
                    '@type'    => 'QAPage',
                    'name'     => $title,
                    'url'      => $url,
                    'mainEntity' => [
                        '@type'          => 'Question',
                        'name'           => $title,
                        'dateCreated'    => $date_pub,
                        'answerCount'    => 1,
                        'acceptedAnswer' => [
                            '@type'       => 'Answer',
                            'text'        => wp_trim_words( $answer_text, 60, '...' ),
                            'dateCreated' => $date_pub,
                            'author'      => [ '@type' => 'Person', 'name' => $founder ],
                            'url'         => $url,
                        ],
                    ],
                ];
                $schemas[] = $schema;
                break;

            // ── SpecialAnnouncement (Notice) ──────────
            case 'notice':
                $urgency     = get_post_meta( $post_id, '_notice_urgency', true );
                $expiry      = get_post_meta( $post_id, '_notice_expiry_date', true );
                $schema = [
                    '@context'      => 'https://schema.org',
                    '@type'         => 'SpecialAnnouncement',
                    'name'          => $title,
                    'url'           => $url,
                    'datePublished' => $date_pub,
                    'dateModified'  => $date_mod,
                    'text'          => $description,
                    'category'      => 'https://www.wikidata.org/wiki/Q7275', // Public Notice
                    'announcementLocation' => [
                        '@type' => 'Organization',
                        'name'  => $org_name,
                        'url'   => home_url( '/' ),
                    ],
                ];
                if ( $expiry ) $schema['expires'] = $expiry;
                if ( $image )  $schema['image']   = $image;
                $schemas[] = $schema;
                break;

            // ── ImageGallery (Photo Gallery) ──────────
            case 'photo_gallery':
                $photos     = get_post_meta( $post_id, '_gallery_images', true );
                $photog     = get_post_meta( $post_id, '_gallery_photographer', true );
                $associated = [];
                if ( is_array( $photos ) ) {
                    foreach ( array_slice( $photos, 0, 10 ) as $img_id ) {
                        $src = wp_get_attachment_image_src( $img_id, 'large' );
                        if ( $src ) {
                            $associated[] = [
                                '@type'       => 'ImageObject',
                                'url'         => $src[0],
                                'contentUrl'  => $src[0],
                                'caption'     => wp_get_attachment_caption( $img_id ) ?: $title,
                            ];
                        }
                    }
                }
                $schema = [
                    '@context'        => 'https://schema.org',
                    '@type'           => 'ImageGallery',
                    'name'            => $title,
                    'url'             => $url,
                    'datePublished'   => $date_pub,
                    'description'     => $description,
                    'author'          => [ '@type' => 'Person', 'name' => $photog ?: $founder ],
                    'publisher'       => $publisher,
                ];
                if ( ! empty( $associated ) ) $schema['associatedMedia'] = $associated;
                if ( $image )                 $schema['image']           = $image;
                $schemas[] = $schema;
                break;

            // ── PublicationIssue (Monthly Magazine) ───
            case 'monthly_magazine':
                $vol     = get_post_meta( $post_id, '_issue_vol', true );
                $num     = get_post_meta( $post_id, '_issue_num', true );
                $month   = get_post_meta( $post_id, '_issue_month', true );
                $pdf_url = get_post_meta( $post_id, '_magazine_pdf', true );
                $schema = [
                    '@context'       => 'https://schema.org',
                    '@type'          => 'PublicationIssue',
                    'name'           => $title,
                    'url'            => $url,
                    'datePublished'  => $date_pub,
                    'description'    => $description,
                    'publisher'      => $publisher,
                    'isPartOf'       => [
                        '@type'     => 'Periodical',
                        'name'      => $org_name,
                        'publisher' => $publisher,
                    ],
                ];
                if ( $vol )     $schema['volumeNumber'] = $vol;
                if ( $num )     $schema['issueNumber']  = $num;
                if ( $month )   $schema['name']         = $title . ' — ' . $month;
                if ( $image )   $schema['image']        = $image;
                if ( $pdf_url ) $schema['sameAs']       = $pdf_url;
                $schemas[] = $schema;
                break;

        } // end switch
    }

    // (c) BreadcrumbList for all singular pages
    if ( is_singular() && $post_id && $enable_breadcrumb ) {
        $post_type   = get_post_type( $post_id );
        $archive_url = get_post_type_archive_link( $post_type );

        $items = [
            [
                '@type'    => 'ListItem',
                'position' => 1,
                'name'     => __( 'Home', 'hidayah' ),
                'item'     => home_url( '/' ),
            ],
        ];

        $pos = 2;

        // Post-type archive level
        $pt_labels = [
            'audio'           => __( 'Audio', 'hidayah' ),
            'video'           => __( 'Video', 'hidayah' ),
            'probondho'       => __( 'Articles', 'hidayah' ),
            'dini_jiggasa'    => __( 'Islamic Q&A', 'hidayah' ),
            'notice'          => __( 'Notice', 'hidayah' ),
            'photo_gallery'   => __( 'Photo Gallery', 'hidayah' ),
            'monthly_magazine'=> __( 'Monthly Hidayah', 'hidayah' ),
        ];

        if ( isset( $pt_labels[ $post_type ] ) && $archive_url ) {
            $items[] = [
                '@type'    => 'ListItem',
                'position' => $pos++,
                'name'     => $pt_labels[ $post_type ],
                'item'     => $archive_url,
            ];
        }

        // Taxonomy level (topic / probondho_cat / uttordata / notice_category)
        $tax_map = [
            'audio'        => 'topic',
            'video'        => 'topic',
            'probondho'    => 'probondho_cat',
            'dini_jiggasa' => 'uttordata',
            'notice'       => 'notice_category',
        ];
        if ( isset( $tax_map[ $post_type ] ) ) {
            $terms = get_the_terms( $post_id, $tax_map[ $post_type ] );
            if ( $terms && ! is_wp_error( $terms ) ) {
                $items[] = [
                    '@type'    => 'ListItem',
                    'position' => $pos++,
                    'name'     => $terms[0]->name,
                    'item'     => get_term_link( $terms[0] ),
                ];
            }
        }

        // Current post
        $items[] = [
            '@type'    => 'ListItem',
            'position' => $pos,
            'name'     => get_the_title( $post_id ),
            'item'     => get_permalink( $post_id ),
        ];

        $schemas[] = [
            '@context'        => 'https://schema.org',
            '@type'           => 'BreadcrumbList',
            'itemListElement' => $items,
        ];
    }

    // ── Output all schemas ────────────────────────────
    foreach ( $schemas as $schema ) {
        echo '<script type="application/ld+json">' . "\n"
           . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT )
           . "\n</script>\n";
    }
}

// ──────────────────────────────────────────────────────
// 3. robots.txt modification
//    Explicitly allow Facebook/WhatsApp crawlers to fix sharing issues
// ──────────────────────────────────────────────────────
add_filter( 'robots_txt', function( $output, $public ) {
    // If not public, standard WP behaviour
    if ( '0' == $public ) {
        return $output;
    }

    $custom = "\n" . '# Allow Meta/Facebook/WhatsApp Crawlers' . "\n";
    $custom .= 'User-agent: facebookexternalhit' . "\n";
    $custom .= 'Allow: /' . "\n";
    $custom .= 'User-agent: meta-externalagent' . "\n";
    $custom .= 'Allow: /' . "\n";
    $custom .= 'User-agent: WhatsApp' . "\n";
    $custom .= 'Allow: /' . "\n";

    return $output . $custom;
}, 20, 2 );
