<?php
/**
 * Notice Archive Template
 *
 * @package Hidayah
 */
get_header();

$paged   = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
$search  = isset( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : '';
$orderby = isset( $_GET['orderby'] ) ? sanitize_text_field( $_GET['orderby'] ) : 'newest';
$urgency = isset( $_GET['urgency'] ) ? sanitize_text_field( $_GET['urgency'] ) : '';
$cat_id  = isset( $_GET['notice_cat'] ) ? absint( $_GET['notice_cat'] ) : 0;
if ( is_tax( 'notice_category' ) ) {
    $term = get_queried_object();
    $cat_id = $term ? (int) $term->term_id : $cat_id;
}

$args = array(
    'post_type'      => 'notice',
    'posts_per_page' => 10,
    'paged'          => $paged,
    'orderby'        => 'date',
    'order'          => $orderby === 'oldest' ? 'ASC' : 'DESC',
    'post_status'    => 'publish',
);

if ( $search ) {
    $args['s'] = $search;
}

if ( $urgency ) {
    $args['meta_query'] = array(
        array(
            'key'   => '_notice_urgency',
            'value' => $urgency,
        ),
    );
}

if ( $cat_id ) {
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'notice_category',
            'field'    => 'term_id',
            'terms'    => $cat_id,
        ),
    );
}

$notice_cats  = get_terms( array( 'taxonomy' => 'notice_category', 'hide_empty' => true ) );
$notice_query = new WP_Query( $args );
?>

<section class="archive-hero">
    <div class="archive-hero-content">
        <h2><?php echo hidayah_get_archive_title(); ?></h2>
        <p><?php _e( 'All official notices, emergency announcements, and Mahfil schedules of Darbar Sharif.', 'hidayah' ); ?></p>
    </div>
</section>

<section class="archive-section">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="archive-breadcrumb">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php _e( 'Home', 'hidayah' ); ?></a>
            <span class="material-symbols-outlined breadcrumb-sep">chevron_right</span>
            <span class="breadcrumb-current"><?php echo hidayah_get_archive_title(); ?></span>
        </nav>

        <div class="archive-layout">
            <!-- LEFT MAIN -->
            <div class="archive-main">
                <div class="col-inner">
                    <!-- Toolbar -->
                    <div class="archive-toolbar">
                        <div class="archive-search-bar">
                            <form id="noticeSearchForm" action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get">
                                <span class="material-symbols-outlined">search</span>
                                <input class="archive-search-input" id="noticeSearchInput" name="s" placeholder="<?php _e( 'Search notices...', 'hidayah' ); ?>" type="text" value="<?php echo esc_attr( $search ); ?>" />
                                <input type="hidden" name="post_type" value="notice" />
                            </form>
                        </div>
                        <div class="archive-toolbar-right">
                            <select class="archive-sort-select" id="noticeSortSelect">
                                <option value="newest" <?php selected( $orderby, 'newest' ); ?>><?php _e( 'Newest First', 'hidayah' ); ?></option>
                                <option value="oldest" <?php selected( $orderby, 'oldest' ); ?>><?php _e( 'Oldest First', 'hidayah' ); ?></option>
                            </select>
                        </div>
                    </div>

                    <!-- Tabs / Filter -->
                    <div class="jiggasa-tabs jiggasa-tabs-container">
                        <button class="jiggasa-tab <?php echo $cat_id ? '' : 'active'; ?>" data-cat=""><?php _e( 'All', 'hidayah' ); ?></button>
                        <?php foreach ( $notice_cats as $cat ) : ?>
                            <button class="jiggasa-tab <?php echo $cat_id === (int) $cat->term_id ? 'active' : ''; ?>" data-cat="<?php echo esc_attr( $cat->term_id ); ?>">
                                <?php echo esc_html( $cat->name ); ?>
                            </button>
                        <?php endforeach; ?>
                    </div>

                    <div class="book-archive-topbar">
                        <div class="archive-filters-toolbar">
                            <div class="archive-count-badge" id="noticeCountBadge">
                                <span class="material-symbols-outlined">campaign</span>
                                <?php printf( __( 'Total %s Notices', 'hidayah' ), $notice_query->found_posts ); ?>
                            </div>
                            <div class="archive-taxonomy-filters">
                                <select id="noticeCatFilter">
                                    <option value=""><?php _e( 'By Category', 'hidayah' ); ?></option>
                                    <?php foreach ( $notice_cats as $cat ) : ?>
                                        <option value="<?php echo esc_attr( $cat->term_id ); ?>" <?php selected( $cat_id, (int) $cat->term_id ); ?>><?php echo esc_html( $cat->name ); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <select id="noticeUrgencyFilter">
                                    <option value=""><?php _e( 'All Types', 'hidayah' ); ?></option>
                                    <option value="urgent" <?php selected( $urgency, 'urgent' ); ?>><?php _e( 'Urgent', 'hidayah' ); ?></option>
                                    <option value="important" <?php selected( $urgency, 'important' ); ?>><?php _e( 'Important', 'hidayah' ); ?></option>
                                    <option value="general" <?php selected( $urgency, 'general' ); ?>><?php _e( 'General', 'hidayah' ); ?></option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Pinned Notices (Sticky) -->
                    <?php
                    $sticky = get_option( 'sticky_posts' );
                    if ( ! empty( $sticky ) && ! is_paged() ) :
                        $sticky_query = new WP_Query( array(
                            'post_type'           => 'notice',
                            'post__in'            => $sticky,
                            'ignore_sticky_posts' => 1,
                            'posts_per_page'      => 3,
                        ) );
                        if ( $sticky_query->have_posts() ) : ?>
                            <div class="notice-pinned-section">
                                <h4 class="notice-pinned-label">
                                    <span class="material-symbols-outlined">push_pin</span>
                                    <?php _e( 'Pinned Notices', 'hidayah' ); ?>
                                </h4>
                                <?php while ( $sticky_query->have_posts() ) : $sticky_query->the_post();
                                    $s_urgency = get_post_meta( get_the_ID(), '_notice_urgency', true ) ?: 'general';
                                    $attachment = get_post_meta( get_the_ID(), '_notice_attachment', true );
                                    $cats = get_the_terms( get_the_ID(), 'notice_category' );
                                    ?>
                                    <article <?php post_class( "notice-card $s_urgency pinned" ); ?>>
                                        <div class="notice-card-header">
                                            <span class="notice-urgency-badge <?php echo esc_attr( $s_urgency ); ?>">
                                                <span class="material-symbols-outlined">
                                                    <?php echo ( $s_urgency === 'urgent' ) ? 'emergency' : ( ( $s_urgency === 'important' ) ? 'priority_high' : 'info' ); ?>
                                                </span>
                                                <?php
                                                if ( $s_urgency === 'urgent' ) _e( 'Urgent', 'hidayah' );
                                                elseif ( $s_urgency === 'important' ) _e( 'Important', 'hidayah' );
                                                else _e( 'General', 'hidayah' );
                                                ?>
                                            </span>
                                            <?php if ( ! empty( $cats ) ) : ?>
                                                <span class="notice-cat-badge"><?php echo esc_html( $cats[0]->name ); ?></span>
                                            <?php endif; ?>
                                            <?php if ( $attachment ) : ?>
                                                <span class="notice-attach-icon"><span class="material-symbols-outlined">attach_file</span></span>
                                            <?php endif; ?>
                                        </div>
                                        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

                                        <div class="notice-card-footer">
                                            <span class="notice-date">
                                                <span class="material-symbols-outlined">calendar_month</span>
                                                <?php echo get_the_date(); ?>
                                            </span>
                                            <a class="notice-read-link" href="<?php the_permalink(); ?>">
                                                <?php _e( 'Details', 'hidayah' ); ?>
                                                <span class="material-symbols-outlined">arrow_forward</span>
                                            </a>
                                        </div>
                                    </article>
                                <?php endwhile; wp_reset_postdata(); ?>
                            </div>
                        <?php endif; endif; ?>

                    <!-- All Notices -->
                    <div style="position: relative; min-height: 200px;">
                        <div id="noticeLoader" class="archive-ajax-loader" style="display: none;">
                            <span class="material-symbols-outlined rotating">progress_activity</span>
                        </div>
                        <div class="notice-list" id="noticeList">
                            <?php if ( $notice_query->have_posts() ) : while ( $notice_query->have_posts() ) : $notice_query->the_post();
                                if ( is_sticky() && ! is_paged() ) { continue; }
                                $n_urgency = get_post_meta( get_the_ID(), '_notice_urgency', true ) ?: 'general';
                                $attachment = get_post_meta( get_the_ID(), '_notice_attachment', true );
                                $cats = get_the_terms( get_the_ID(), 'notice_category' );
                                ?>
                                <article <?php post_class( "notice-card $n_urgency" ); ?>>
                                    <div class="notice-card-header">
                                        <span class="notice-urgency-badge <?php echo esc_attr( $n_urgency ); ?>">
                                            <span class="material-symbols-outlined">
                                                <?php echo ( $n_urgency === 'urgent' ) ? 'emergency' : ( ( $n_urgency === 'important' ) ? 'priority_high' : 'info' ); ?>
                                            </span>
                                            <?php
                                            if ( $n_urgency === 'urgent' ) _e( 'Urgent', 'hidayah' );
                                            elseif ( $n_urgency === 'important' ) _e( 'Important', 'hidayah' );
                                            else _e( 'General', 'hidayah' );
                                            ?>
                                        </span>
                                        <?php if ( ! empty( $cats ) ) : ?>
                                            <span class="notice-cat-badge"><?php echo esc_html( $cats[0]->name ); ?></span>
                                        <?php endif; ?>
                                        <?php if ( $attachment ) : ?>
                                            <span class="notice-attach-icon"><span class="material-symbols-outlined">attach_file</span></span>
                                        <?php endif; ?>
                                    </div>
                                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

                                    <div class="notice-card-footer">
                                        <span class="notice-date">
                                            <span class="material-symbols-outlined">calendar_month</span>
                                            <?php echo get_the_date(); ?>
                                        </span>
                                        <a class="notice-read-link" href="<?php the_permalink(); ?>">
                                            <?php _e( 'Details', 'hidayah' ); ?>
                                            <span class="material-symbols-outlined">arrow_forward</span>
                                        </a>
                                    </div>
                                </article>
                            <?php endwhile; else : ?>
                                <?php get_template_part( 'template-parts/content/content', 'none' ); ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div id="noticePagination">
                        <?php hidayah_pagination( $notice_query ); ?>
                    </div>
                </div>
            </div>

            <!-- SIDEBAR -->
            <aside class="archive-sidebar">
                <div class="col-inner">
                    <!-- Search -->
                    <div class="sidebar-widget">
                        <h4 class="sidebar-widget-title">
                            <span class="material-symbols-outlined">search</span>
                            <?php _e( 'Search', 'hidayah' ); ?>
                        </h4>
                        <div class="sidebar-search-box">
                            <form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get">
                                <input name="s" placeholder="<?php _e( 'Search notices...', 'hidayah' ); ?>" type="text" />
                                <input type="hidden" name="post_type" value="notice" />
                                <button type="submit"><span class="material-symbols-outlined">search</span></button>
                            </form>
                        </div>
                    </div>

                    <!-- Category Filter -->
                    <div class="sidebar-widget">
                        <h4 class="sidebar-widget-title">
                            <span class="material-symbols-outlined">category</span>
                            <?php _e( 'By Category', 'hidayah' ); ?>
                        </h4>
                        <ul class="sidebar-filter-list">
                            <?php foreach ( $notice_cats as $cat ) : ?>
                                <li>
                                    <a href="<?php echo esc_url( get_term_link( $cat ) ); ?>">
                                        <span><?php echo esc_html( $cat->name ); ?></span>
                                        <span class="filter-count"><?php echo $cat->count; ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <!-- Latest Notices -->
                    <div class="sidebar-widget">
                        <h4 class="sidebar-widget-title">
                            <span class="material-symbols-outlined">schedule</span>
                            <?php _e( 'Latest Notices', 'hidayah' ); ?>
                        </h4>
                        <ul class="sidebar-recent-list">
                            <?php
                            $latest_notices = new WP_Query( array(
                                'post_type'      => 'notice',
                                'posts_per_page' => 5,
                            ) );
                            while ( $latest_notices->have_posts() ) : $latest_notices->the_post();
                                $l_urgency = get_post_meta( get_the_ID(), '_notice_urgency', true ) ?: 'general';
                                ?>
                                <li>
                                    <a href="<?php the_permalink(); ?>">
                                        <span class="notice-urgency-dot <?php echo esc_attr( $l_urgency ); ?>"></span>
                                        <div class="recent-info">
                                            <h5><?php the_title(); ?></h5>
                                            <span><?php echo get_the_date(); ?></span>
                                        </div>
                                    </a>
                                </li>
                            <?php endwhile; wp_reset_postdata(); ?>
                        </ul>
                    </div>

                    <!-- Download Center -->
                    <div class="sidebar-widget">
                        <h4 class="sidebar-widget-title">
                            <span class="material-symbols-outlined">download</span>
                            <?php _e( 'Download Center', 'hidayah' ); ?>
                        </h4>
                        <ul class="notice-download-list">
                            <?php
                            $download_query = new WP_Query( array(
                                'post_type'      => 'notice',
                                'posts_per_page' => 5,
                                'meta_query'     => array(
                                    array(
                                        'key'     => '_notice_attachment',
                                        'compare' => 'EXISTS',
                                    ),
                                ),
                            ) );
                            while ( $download_query->have_posts() ) : $download_query->the_post();
                                $attachment_id = get_post_meta( get_the_ID(), '_notice_attachment', true );
                                if ( ! $attachment_id ) { continue; }
                                $file_url = wp_get_attachment_url( $attachment_id );
                                ?>
                                <li>
                                    <a href="<?php echo esc_url( $file_url ); ?>" download>
                                        <span class="material-symbols-outlined">picture_as_pdf</span>
                                        <div><span><?php the_title(); ?></span></div>
                                        <span class="material-symbols-outlined">download</span>
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
