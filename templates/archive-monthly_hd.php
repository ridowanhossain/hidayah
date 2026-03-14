<?php
/**
 * Archive Template for Monthly HD (Magazine)
 *
 * @package Hidayah
 */
get_header();

$args = array(
    'post_type'      => 'monthly_hd',
    'posts_per_page' => 12,
    'paged'          => get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1,
    'orderby'        => 'date',
    'order'          => 'DESC',
);

if ( ! empty( $_GET['s'] ) ) {
    $args['s'] = sanitize_text_field( $_GET['s'] );
}

if ( ! empty( $_GET['issue_year'] ) ) {
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'issue_year',
            'field'    => 'slug',
            'terms'    => sanitize_text_field( $_GET['issue_year'] ),
        ),
    );
}

$mag_query = new WP_Query( $args );
?>

<section class="archive-hero">
    <div class="archive-hero-content">
        <h2><?php _e( 'মাসিক হক্বের দা\'ওয়াত', 'hidayah' ); ?></h2>
        <p><?php _e( 'দরবার শরীফের মাসিক ইসলামী ম্যাগাজিনের সকল সংখ্যা এখানে পাওয়া যাচ্ছে। পড়ুন, ডাউনলোড করুন এবং শেয়ার করুন।', 'hidayah' ); ?></p>
    </div>
</section>

<section class="archive-section">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="archive-breadcrumb">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php _e( 'হোম', 'hidayah' ); ?></a>
            <span class="material-symbols-outlined breadcrumb-sep">chevron_right</span>
            <span class="breadcrumb-current"><?php _e( 'মাসিক হক্বের দা\'ওয়াত', 'hidayah' ); ?></span>
        </nav>

        <div class="archive-layout">
            <!-- LEFT -->
            <div class="archive-main">
                <div class="col-inner">
                    <!-- Toolbar -->
                    <div class="archive-toolbar">
                        <div class="archive-search-bar">
                            <form role="search" id="monthlyHdSearchForm" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" style="display: flex; width: 100%; align-items: center;">
                                <span class="material-symbols-outlined">search</span>
                                <input class="archive-search-input" id="monthlyHdSearchInput" placeholder="<?php _e( 'সংখ্যা খুঁজুন...', 'hidayah' ); ?>" type="text" name="s" value="<?php echo get_search_query(); ?>" />
                                <input type="hidden" name="post_type" value="monthly_hd" />
                            </form>
                        </div>
                        <div class="archive-toolbar-right">
                            <select class="archive-sort-select" id="monthlyHdSortSelect">
                                <option value="newest" <?php selected($_GET['order'] ?? 'DESC', 'DESC'); ?>><?php _e( 'নতুন প্রথমে', 'hidayah' ); ?></option>
                                <option value="oldest" <?php selected($_GET['order'] ?? '', 'ASC'); ?>><?php _e( 'পুরাতন প্রথমে', 'hidayah' ); ?></option>
                            </select>
                            <div class="archive-view-toggle" data-view-target="#monthlyHdGrid">
                                <button class="view-toggle-btn active" data-view="grid" title="গ্রিড ভিউ">
                                    <span class="material-symbols-outlined">grid_view</span>
                                </button>
                                <button class="view-toggle-btn" data-view="list" title="লিস্ট ভিউ">
                                    <span class="material-symbols-outlined">view_list</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Count & Filters -->
                    <div class="archive-count-header">
                        <div class="archive-count-badge" id="monthlyHdCountBadge">
                            <span class="material-symbols-outlined">newspaper</span>
                            <?php printf( __( 'মোট %sটি সংখ্যা', 'hidayah' ), hidayah_en_to_bn_number( $mag_query->found_posts ) ); ?>
                        </div>
                        <div class="archive-year-filter">
                            <select id="monthlyHdYearFilter">
                                <option value=""><?php _e( 'সব সাল', 'hidayah' ); ?></option>
                                <?php foreach ( get_terms( array( 'taxonomy' => 'issue_year' ) ) as $yr ) : ?>
                                    <option value="<?php echo esc_attr( $yr->term_id ); ?>"><?php echo hidayah_en_to_bn_number( $yr->name ); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="archive-year-filter">
                            <select id="monthlyHdCategoryFilter">
                                <option value=""><?php _e( 'বিশেষ সংখ্যা', 'hidayah' ); ?></option>
                                <?php foreach ( get_terms( array( 'taxonomy' => 'issue_category' ) ) as $cat ) : ?>
                                    <option value="<?php echo esc_attr( $cat->term_id ); ?>"><?php echo esc_html( $cat->name ); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Magazine Grid -->
                    <div style="position: relative; min-height: 200px;">
                        <div id="monthlyHdLoader" class="archive-ajax-loader" style="display: none;">
                            <span class="material-symbols-outlined rotating">progress_activity</span>
                        </div>
                        <div class="mhd-grid" id="monthlyHdGrid">
                            <?php if ( $mag_query->have_posts() ) : while ( $mag_query->have_posts() ) : $mag_query->the_post();
                                $pages      = get_post_meta( get_the_ID(), '_magazine_pages', true );
                                $pdf_url    = get_post_meta( get_the_ID(), '_pdf_file_url', true );
                                $is_special = get_post_meta( get_the_ID(), '_is_special_issue', true );
                                ?>
                                <article class="mhd-card <?php echo $is_special ? 'mhd-special' : ''; ?>">
                                    <div class="mhd-cover">
                                        <?php if ( has_post_thumbnail() ) : ?>
                                            <?php the_post_thumbnail( 'medium', array( 'alt' => get_the_title() ) ); ?>
                                        <?php else : ?>
                                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/mag-placeholder.png" alt="<?php the_title_attribute(); ?>" />
                                        <?php endif; ?>
                                        <?php if ( $pages ) : ?>
                                            <span class="mhd-page-badge"><?php printf( __( '%s পৃষ্ঠা', 'hidayah' ), hidayah_en_to_bn_number( $pages ) ); ?></span>
                                        <?php endif; ?>
                                        <?php if ( $is_special ) : ?>
                                            <span class="mhd-special-badge"><?php _e( 'বিশেষ সংখ্যা', 'hidayah' ); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="mhd-info">
                                        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                        <p class="mhd-date">
                                            <span class="material-symbols-outlined">calendar_month</span>
                                            <?php echo get_the_date(); ?>
                                        </p>
                                        <div class="mhd-actions">
                                            <a class="mhd-read-btn" href="<?php the_permalink(); ?>">
                                                <span class="material-symbols-outlined">menu_book</span>
                                                <?php _e( 'পড়ুন', 'hidayah' ); ?>
                                            </a>
                                            <?php if ( $pdf_url ) : ?>
                                                <a class="mhd-dl-btn" href="<?php echo esc_url( $pdf_url ); ?>" download>
                                                    <span class="material-symbols-outlined">download</span>
                                                    <?php _e( 'ডাউনলোড', 'hidayah' ); ?>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </article>
                            <?php endwhile; else : ?>
                                <div class="col-full">
                                    <?php get_template_part( 'template-parts/content/content', 'none' ); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div id="monthlyHdPagination">
                        <?php hidayah_pagination( $mag_query ); ?>
                    </div>
                </div>
            </div>

            <!-- SIDEBAR -->
            <aside class="archive-sidebar">
                <div class="col-inner">
                    <!-- Special Issues Filter (Using Meta Query in Customizer or Taxonomy) -->
                    <div class="sidebar-widget">
                        <h4 class="sidebar-widget-title">
                            <span class="material-symbols-outlined">star</span>
                            <?php _e( 'বিশেষ সংখ্যা', 'hidayah' ); ?>
                        </h4>
                        <ul class="sidebar-filter-list">
                            <?php
                            $special_types = get_terms( array( 'taxonomy' => 'issue_category' ) );
                            foreach ( $special_types as $type ) : ?>
                                <li>
                                    <a href="<?php echo get_term_link( $type ); ?>">
                                        <span><?php echo esc_html( $type->name ); ?></span>
                                        <span class="filter-count"><?php echo hidayah_en_to_bn_number( $type->count ); ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <!-- Latest Issue Highlight -->
                    <div class="sidebar-widget">
                        <h4 class="sidebar-widget-title">
                            <span class="material-symbols-outlined">new_releases</span>
                            <?php _e( 'সর্বশেষ সংখ্যা', 'hidayah' ); ?>
                        </h4>
                        <?php
                        $latest_mag = new WP_Query( array( 'post_type' => 'monthly_hd', 'posts_per_page' => 1 ) );
                        while ( $latest_mag->have_posts() ) : $latest_mag->the_post();
                            $l_pages = get_post_meta( get_the_ID(), '_magazine_pages', true );
                            ?>
                            <div class="mhd-latest-card">
                                <?php the_post_thumbnail( 'medium' ); ?>
                                <div class="mhd-latest-info">
                                    <h5><?php the_title(); ?></h5>
                                    <p><?php if ( $l_pages ) printf( __( '%s পৃষ্ঠা', 'hidayah' ), hidayah_en_to_bn_number( $l_pages ) ); ?> • <?php echo get_the_date(); ?></p>
                                    <a class="btn btn-sm" href="<?php the_permalink(); ?>">
                                        <span class="material-symbols-outlined">menu_book</span>
                                        <?php _e( 'পড়ুন ও ডাউনলোড', 'hidayah' ); ?>
                                    </a>
                                </div>
                            </div>
                        <?php endwhile; wp_reset_postdata(); ?>
                    </div>

                    <!-- Magazine About -->
                    <div class="sidebar-widget">
                        <h4 class="sidebar-widget-title">
                            <span class="material-symbols-outlined">info</span>
                            <?php _e( 'পত্রিকা পরিচিতি', 'hidayah' ); ?>
                        </h4>
                        <p class="fs-14 text-light lh-1-7">
                            <?php echo h_opt( 'magazine_intro', 'মাসিক হক্বের দা\'ওয়াত — দরবার শরীফ পরিচালিত একটি ইসলামী মাসিক পত্রিকা। ইসলামী জ্ঞান, তাসাউফ, ফিকহ ও সমসাময়িক বিষয়ে সমৃদ্ধ এই পত্রিকা ২০১৬ সাল থেকে প্রকাশিত হচ্ছে।' ); ?>
                        </p>
                    </div>

                    <!-- Most Read -->
                    <div class="sidebar-widget">
                        <h4 class="sidebar-widget-title">
                            <span class="material-symbols-outlined">trending_up</span>
                            <?php _e( 'সর্বাধিক পঠিত', 'hidayah' ); ?>
                        </h4>
                        <ul class="sidebar-recent-list">
                            <?php
                            $most_read = new WP_Query( array(
                                'post_type'      => 'monthly_hd',
                                'posts_per_page' => 5,
                                'meta_key'       => '_post_views_count',
                                'orderby'        => 'meta_value_num',
                                'order'          => 'DESC',
                            ) );
                            while ( $most_read->have_posts() ) : $most_read->the_post();
                                $v_count = get_post_meta( get_the_ID(), '_post_views_count', true ) ?: 0;
                                ?>
                                <li>
                                    <a href="<?php the_permalink(); ?>">
                                        <span class="material-symbols-outlined recent-icon">newspaper</span>
                                        <div class="recent-info">
                                            <h5><?php the_title(); ?></h5>
                                            <span><?php printf( __( '%s বার পড়া হয়েছে', 'hidayah' ), hidayah_en_to_bn_number( number_format_i18n( $v_count ) ) ); ?></span>
                                        </div>
                                    </a>
                                </li>
                            <?php endwhile; wp_reset_postdata(); ?>
                        </ul>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>

<?php
wp_reset_postdata();
get_footer();
?>
