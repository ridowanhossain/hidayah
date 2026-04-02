<?php
/**
 * Archive Template for Photo Gallery
 *
 * @package Hidayah
 */
get_header();

$args = array(
    'post_type'      => 'photo_gallery',
    'posts_per_page' => 12,
    'paged'          => get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1,
    'orderby'        => 'date',
    'order'          => 'DESC',
);

// Search
if ( ! empty( $_GET['s'] ) ) {
    $args['s'] = sanitize_text_field( $_GET['s'] );
}

// Category
if ( ! empty( $_GET['gallery_cat'] ) ) {
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'gallery_cat',
            'field'    => 'slug',
            'terms'    => sanitize_text_field( $_GET['gallery_cat'] ),
        ),
    );
}

// Year
if ( ! empty( $_GET['gallery_year'] ) ) {
    $args['tax_query'][] = array(
        'taxonomy' => 'gallery_year',
        'field'    => 'slug',
        'terms'    => sanitize_text_field( $_GET['gallery_year'] ),
    );
}

$gal_query = new WP_Query( $args );

// Total counts
$total_albums = wp_count_posts('photo_gallery')->publish;
// For total photos, we would need to sum a meta field, skipping for now as it's static in mockup.
$gal_cats = get_terms( array( 'taxonomy' => 'gallery_cat' ) );
$gal_years = get_terms( array( 'taxonomy' => 'gallery_year' ) );
?>

<section class="archive-hero">
    <div class="archive-hero-content">
        <h2><?php _e( 'Photo Gallery', 'hidayah' ); ?></h2>
        <p><?php _e( 'A collection of memorable photos from Darbar Sharif\'s Mahfils, events, and special moments.', 'hidayah' ); ?></p>
    </div>
</section>

<section class="archive-section">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="archive-breadcrumb">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php _e( 'Home', 'hidayah' ); ?></a>
            <span class="material-symbols-outlined breadcrumb-sep">chevron_right</span>
            <span class="breadcrumb-current"><?php _e( 'Photo Gallery', 'hidayah' ); ?></span>
        </nav>

        <div class="archive-layout">
            <!-- LEFT -->
            <div class="archive-main">
                <div class="col-inner">
                    <!-- Toolbar -->
                    <div class="archive-toolbar">
                        <div class="archive-search-bar">
                            <form role="search" id="gallerySearchForm" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" style="display: flex; width: 100%; align-items: center;">
                                <span class="material-symbols-outlined">search</span>
                                <input class="archive-search-input" id="gallerySearchInput" placeholder="<?php _e( 'Search albums...', 'hidayah' ); ?>" type="text" name="s" value="<?php echo get_search_query(); ?>" />
                                <input type="hidden" name="post_type" value="photo_gallery" />
                            </form>
                        </div>
                        <div class="archive-toolbar-right">
                            <select class="archive-sort-select" id="gallerySortSelect">
                                <option value="newest"><?php _e( 'Newest First', 'hidayah' ); ?></option>
                                <option value="popular"><?php _e( 'Popular', 'hidayah' ); ?></option>
                            </select>
                            <div class="archive-view-toggle" data-view-target="#photoGalleryGrid">
                                <button class="view-toggle-btn active" data-view="grid">
                                    <span class="material-symbols-outlined">grid_view</span>
                                </button>
                                <button class="view-toggle-btn" data-view="list">
                                    <span class="material-symbols-outlined">dashboard</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Count & Filter Chips -->
                    <div class="book-archive-topbar">
                        <div class="archive-filters-toolbar">
                        <div class="archive-count-badge" id="galleryCountBadge">
                            <span class="material-symbols-outlined">photo_library</span>
                            <?php printf( __( 'Total %s Albums', 'hidayah' ), $gal_query->found_posts ); ?>
                        </div>
                        <div class="archive-taxonomy-filters">
                            <select id="galleryCatFilter">
                                <option value=""><?php _e( 'By Event', 'hidayah' ); ?></option>
                                <?php foreach ( $gal_cats as $gc ) : ?>
                                    <option value="<?php echo esc_attr( $gc->term_id ); ?>"><?php echo esc_html( $gc->name ); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <select id="galleryYearFilter">
                                <option value=""><?php _e( 'By Year', 'hidayah' ); ?></option>
                                <?php foreach ( $gal_years as $gy ) : ?>
                                    <option value="<?php echo esc_attr( $gy->term_id ); ?>"><?php echo $gy->name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        </div>
                    </div>

                    <!-- Album Grid -->
                    <div style="position: relative; min-height: 200px;">
                        <div id="galleryLoader" class="archive-ajax-loader" style="display: none;">
                            <span class="material-symbols-outlined rotating">progress_activity</span>
                        </div>
                        <div class="gallery-album-grid" id="photoGalleryGrid">
                        <?php if ( $gal_query->have_posts() ) : while ( $gal_query->have_posts() ) : $gal_query->the_post(); 
                            $photos = get_post_meta( get_the_ID(), '_gallery_images', true ); // CSS Framework Gallery Field
                            $count  = is_array($photos) ? count($photos) : 0;
                            $loc    = get_post_meta( get_the_ID(), '_gallery_location', true );
                        ?>
                            <article class="gallery-album-card">
                                <a class="gallery-album-cover-link" href="<?php the_permalink(); ?>">
                                    <?php if ( has_post_thumbnail() ) : ?>
                                        <?php the_post_thumbnail( 'medium' ); ?>
                                    <?php else : ?>
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/gallery-placeholder.png" alt="" />
                                    <?php endif; ?>
                                    <span class="gallery-photo-count">
                                        <span class="material-symbols-outlined">photo_camera</span>
                                        <?php echo $count; ?>
                                    </span>
                                    <div class="gallery-album-hover">
                                        <span class="material-symbols-outlined">open_in_full</span>
                                    </div>
                                </a>
                                <div class="gallery-album-info">
                                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                    <div class="gallery-album-meta">
                                        <span>
                                            <span class="material-symbols-outlined">event</span>
                                            <?php echo get_the_date(); ?>
                                        </span>
                                        <?php if ($loc) : ?>
                                            <span>
                                                <span class="material-symbols-outlined">location_on</span>
                                                <?php echo esc_html($loc); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <!-- Preview Thumbs -->
                                    <?php if ( is_array($photos) && count($photos) > 0 ) : ?>
                                        <div class="gallery-preview-thumbs small">
                                            <?php 
                                            $preview = array_slice($photos, 0, 3);
                                            foreach ($preview as $p_id) : ?>
                                                <img src="<?php echo wp_get_attachment_image_url($p_id, 'thumbnail'); ?>" alt="" />
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </article>
                        <?php endwhile; ?>
                        <?php else : ?>
                            <?php get_template_part( 'template-parts/content/content', 'none' ); ?>
                        <?php endif; ?>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div id="galleryPagination">
                        <?php hidayah_pagination($gal_query); ?>
                    </div>
                </div>
            </div>

            <!-- SIDEBAR -->
            <aside class="archive-sidebar">
                <div class="col-inner">
                    <!-- Search Widget -->
                    <div class="sidebar-widget">
                        <h4 class="sidebar-widget-title">
                            <span class="material-symbols-outlined">search</span>
                            <?php _e( 'Search', 'hidayah' ); ?>
                        </h4>
                        <div class="sidebar-search-box">
                            <form method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" style="display: flex; width: 100%;">
                                <input placeholder="<?php _e( 'Search albums...', 'hidayah' ); ?>" type="text" name="s" value="<?php echo get_search_query(); ?>" />
                                <input type="hidden" name="post_type" value="photo_gallery" />
                                <button type="submit">
                                    <span class="material-symbols-outlined">search</span>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Event Categories -->
                    <div class="sidebar-widget">
                        <h4 class="sidebar-widget-title">
                            <span class="material-symbols-outlined">category</span>
                            <?php _e( 'By Event', 'hidayah' ); ?>
                        </h4>
                        <ul class="sidebar-filter-list">
                            <?php
                            $gal_cats = get_terms( array('taxonomy' => 'gallery_cat') );
                            foreach ( $gal_cats as $gc ) : ?>
                                <li>
                                    <a href="<?php echo add_query_arg('gallery_cat', $gc->slug); ?>">
                                        <span><?php echo esc_html($gc->name); ?></span>
                                        <span class="filter-count"><?php echo $gc->count; ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <!-- Yearly Filter -->
                    <div class="sidebar-widget">
                        <h4 class="sidebar-widget-title">
                            <span class="material-symbols-outlined">calendar_month</span>
                            <?php _e( 'By Year', 'hidayah' ); ?>
                        </h4>
                        <ul class="sidebar-filter-list">
                            <?php
                            $gal_years = get_terms( array('taxonomy' => 'gallery_year') );
                            foreach ( $gal_years as $gy ) : ?>
                                <li>
                                    <a href="<?php echo add_query_arg('gallery_year', $gy->slug); ?>">
                                        <span><?php echo esc_html($gy->name); ?></span>
                                        <span class="filter-count"><?php echo $gy->count; ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <!-- Recent Albums -->
                    <div class="sidebar-widget">
                        <h4 class="sidebar-widget-title">
                            <span class="material-symbols-outlined">schedule</span>
                            <?php _e( 'Recent Albums', 'hidayah' ); ?>
                        </h4>
                        <div class="gallery-sidebar-thumbs">
                            <?php
                            $recent_gal = new WP_Query( array('post_type' => 'photo_gallery', 'posts_per_page' => 3) );
                            while ( $recent_gal->have_posts() ) : $recent_gal->the_post();
                            ?>
                                <a class="gallery-sidebar-thumb" href="<?php the_permalink(); ?>">
                                    <?php if ( has_post_thumbnail() ) the_post_thumbnail( 'thumbnail' ); ?>
                                    <span><?php the_title(); ?></span>
                                </a>
                            <?php endwhile; wp_reset_postdata(); ?>
                        </div>
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
