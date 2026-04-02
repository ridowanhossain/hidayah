<?php
/**
 * Single Photo Gallery Template
 *
 * @package Hidayah
 */
get_header();

while ( have_posts() ) : the_post();
    $photos = get_post_meta( get_the_ID(), '_gallery_images', true );
    $count  = is_array($photos) ? count($photos) : 0;
    $loc    = get_post_meta( get_the_ID(), '_gallery_location', true );
    $photog = get_post_meta( get_the_ID(), '_gallery_photographer', true ) ?: __( 'Media Team', 'hidayah' );
?>

<section class="archive-hero">
    <div class="archive-hero-content">
        <h2><?php _e( 'Photo Gallery', 'hidayah' ); ?></h2>
        <p><?php _e( 'A collection of photos from special events and programs.', 'hidayah' ); ?></p>
    </div>
</section>

<section class="archive-section">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="archive-breadcrumb">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php _e( 'Home', 'hidayah' ); ?></a>
            <span class="material-symbols-outlined breadcrumb-sep">chevron_right</span>
            <a href="<?php echo get_post_type_archive_link('photo_gallery'); ?>"><?php _e( 'Photo Gallery', 'hidayah' ); ?></a>
            <span class="material-symbols-outlined breadcrumb-sep">chevron_right</span>
            <span class="breadcrumb-current"><?php the_title(); ?></span>
        </nav>

        <div class="archive-layout">
            <!-- LEFT MAIN -->
            <div class="archive-main">
                <div class="col-inner">
                    <!-- Album Header -->
                    <div class="gallery-single-header" itemscope itemtype="https://schema.org/ImageGallery">
                        <h1 class="gallery-single-title" itemprop="name"><?php the_title(); ?></h1>
                        <div class="gallery-album-meta" style="margin-bottom: 16px">
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
                            <span>
                                <span class="material-symbols-outlined">photo_camera</span>
                                <?php printf( _n( '%s Photo', '%s Photos', $count, 'hidayah' ), $count ); ?>
                            </span>
                        </div>
                        <div class="gallery-single-actions">
                            <a class="btn btn-sm" href="<?php echo esc_url( admin_url( 'admin-ajax.php?action=download_gallery_zip&post_id=' . get_the_ID() ) ); ?>">
                                <span class="material-symbols-outlined">download</span>
                                <?php _e( 'Download All (ZIP)', 'hidayah' ); ?>
                            </a>
                            <div class="archive-view-toggle" data-view-target="#photoGrid">
                                <button class="view-toggle-btn active" data-view="grid" title="<?php esc_attr_e( 'Grid View', 'hidayah' ); ?>">
                                    <span class="material-symbols-outlined">grid_view</span>
                                </button>
                                <button class="view-toggle-btn" data-view="list" title="<?php esc_attr_e( 'List View', 'hidayah' ); ?>">
                                    <span class="material-symbols-outlined">view_list</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Photo Grid -->
                    <div class="gallery-photo-grid" id="photoGrid">
                        <?php if ( is_array($photos) ) : foreach ( $photos as $index => $photo_id ) : 
                            $img_url = wp_get_attachment_image_url($photo_id, 'full');
                            $thumb_url = wp_get_attachment_image_url($photo_id, 'medium_large');
                            $caption = wp_get_attachment_caption($photo_id);
                        ?>
                            <div class="gallery-photo-item" data-caption="<?php echo esc_attr($caption); ?>" data-date="<?php echo get_the_date(); ?>" data-index="<?php echo $index; ?>" data-full="<?php echo esc_url($img_url); ?>">
                                <img alt="<?php echo esc_attr($caption); ?>" loading="lazy" src="<?php echo esc_url($thumb_url); ?>" />
                                <div class="gallery-photo-overlay">
                                    <span class="material-symbols-outlined">zoom_in</span>
                                </div>
                            </div>
                        <?php endforeach; endif; ?>
                    </div>

                    <!-- Album Description -->
                    <div class="sidebar-widget" style="margin-top: 28px; margin-bottom: 20px">
                        <h3 style="font-size: 17px; font-weight: 700; color: var(--text-dark); margin: 0 0 12px"><?php _e( 'Album Description', 'hidayah' ); ?></h3>
                        <div style="font-size: 14px; line-height: 1.8; color: var(--text-dark);">
                            <?php the_content(); ?>
                        </div>
                    </div>

                    <!-- Navigation -->
                    <div class="single-audio-nav">
                        <?php
                        $prev_post = get_previous_post();
                        if ($prev_post) : ?>
                            <a class="single-audio-nav-btn single-audio-nav-prev" href="<?php echo get_permalink($prev_post->ID); ?>">
                                <span class="material-symbols-outlined">arrow_back</span>
                                <div class="single-audio-nav-text">
                                    <span class="nav-label"><?php _e( 'Previous Album', 'hidayah' ); ?></span>
                                    <span class="nav-title-sm"><?php echo get_the_title($prev_post->ID); ?></span>
                                </div>
                            </a>
                        <?php endif; ?>

                        <?php
                        $next_post = get_next_post();
                        if ($next_post) : ?>
                            <a class="single-audio-nav-btn single-audio-nav-next" href="<?php echo get_permalink($next_post->ID); ?>">
                                <div class="single-audio-nav-text">
                                    <span class="nav-label"><?php _e( 'Next Album', 'hidayah' ); ?></span>
                                    <span class="nav-title-sm"><?php echo get_the_title($next_post->ID); ?></span>
                                </div>
                                <span class="material-symbols-outlined">arrow_forward</span>
                            </a>
                        <?php endif; ?>
                    </div>

                    <!-- Share -->
                    <?php get_template_part( 'template-parts/content/share-section' ); ?>

                    <!-- Comments -->
                    <?php if ( comments_open() || get_comments_number() ) comments_template(); ?>
                </div>
            </div>

            <!-- SIDEBAR -->
            <aside class="archive-sidebar">
                <div class="col-inner">
                    <!-- Album Info -->
                    <div class="sidebar-widget">
                        <h4 class="sidebar-widget-title">
                            <span class="material-symbols-outlined">info</span>
                            <?php _e( 'Album Information', 'hidayah' ); ?>
                        </h4>
                        <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 10px">
                            <li style="display: flex; gap: 10px; font-size: 13px">
                                <span class="material-symbols-outlined" style="font-size: 17px; color: var(--primary-green-dark); flex-shrink: 0">event</span>
                                <div>
                                    <strong style="display: block"><?php _e( 'Date', 'hidayah' ); ?></strong>
                                    <span style="color: var(--text-light)"><?php echo get_the_date(); ?></span>
                                </div>
                            </li>
                            <?php if ($loc) : ?>
                                <li style="display: flex; gap: 10px; font-size: 13px">
                                    <span class="material-symbols-outlined" style="font-size: 17px; color: var(--primary-green-dark); flex-shrink: 0">location_on</span>
                                    <div>
                                        <strong style="display: block"><?php _e( 'Location', 'hidayah' ); ?></strong>
                                        <span style="color: var(--text-light)"><?php echo esc_html($loc); ?></span>
                                    </div>
                                </li>
                            <?php endif; ?>
                            <li style="display: flex; gap: 10px; font-size: 13px">
                                <span class="material-symbols-outlined" style="font-size: 17px; color: var(--primary-green-dark); flex-shrink: 0">photo_camera</span>
                                <div>
                                    <strong style="display: block"><?php _e( 'Image Count', 'hidayah' ); ?></strong>
                                    <span style="color: var(--text-light)"><?php printf( _n( '%s Photo', '%s Photos', $count, 'hidayah' ), $count ); ?></span>
                                </div>
                            </li>
                            <li style="display: flex; gap: 10px; font-size: 13px">
                                <span class="material-symbols-outlined" style="font-size: 17px; color: var(--primary-green-dark); flex-shrink: 0">person</span>
                                <div>
                                    <strong style="display: block"><?php _e( 'Photographer', 'hidayah' ); ?></strong>
                                    <span style="color: var(--text-light)"><?php echo esc_html($photog); ?></span>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <!-- Download -->
                    <div class="sidebar-widget">
                        <h4 class="sidebar-widget-title">
                            <span class="material-symbols-outlined">download</span>
                            <?php _e( 'Download', 'hidayah' ); ?>
                        </h4>
                        <div style="display: flex; flex-direction: column; gap: 8px">
                            <a class="btn btn-sm" href="<?php echo esc_url( admin_url( 'admin-ajax.php?action=download_gallery_zip&post_id=' . get_the_ID() ) ); ?>" style="display: flex; align-items: center; gap: 6px; justify-content: center">
                                <span class="material-symbols-outlined">folder_zip</span>
                                <?php _e( 'Full Album (ZIP)', 'hidayah' ); ?>
                            </a>
                        </div>
                    </div>

                    <!-- Related Albums -->
                    <div class="sidebar-widget">
                        <h4 class="sidebar-widget-title">
                            <span class="material-symbols-outlined">photo_library</span>
                            <?php _e( 'Related Albums', 'hidayah' ); ?>
                        </h4>
                        <div class="gallery-sidebar-thumbs">
                            <?php
                            $related = new WP_Query( array(
                                'post_type'      => 'photo_gallery',
                                'posts_per_page' => 3,
                                'post__not_in'   => array( get_the_ID() ),
                            ) );
                            while ( $related->have_posts() ) : $related->the_post();
                            ?>
                                <a class="gallery-sidebar-thumb" href="<?php the_permalink(); ?>">
                                    <?php if ( has_post_thumbnail() ) the_post_thumbnail( 'thumbnail' ); ?>
                                    <span><?php the_title(); ?></span>
                                </a>
                            <?php endwhile; wp_reset_postdata(); ?>
                        </div>
                    </div>

                    <!-- Action Link -->
                    <div class="sidebar-widget" style="text-align: center">
                        <a class="probondho-read-more" href="<?php echo get_post_type_archive_link('photo_gallery'); ?>">
                            <span class="material-symbols-outlined">arrow_back</span>
                            <?php _e( 'View All Albums', 'hidayah' ); ?>
                        </a>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>

<!-- Lightbox Modal (Logic is in script.js) -->
<div class="gallery-lightbox" hidden="" id="lightbox">
    <div class="gallery-lightbox-backdrop" id="lightboxBackdrop"></div>
    <div class="gallery-lightbox-content">
        <button class="gallery-lightbox-close" id="lightboxClose"><span class="material-symbols-outlined">close</span></button>
        <button class="gallery-lightbox-nav prev" id="lightboxPrev"><span class="material-symbols-outlined">arrow_back_ios</span></button>
        <div class="gallery-lightbox-img-wrap"><img alt="" id="lightboxImg" src="" /></div>
        <button class="gallery-lightbox-nav next" id="lightboxNext"><span class="material-symbols-outlined">arrow_forward_ios</span></button>
        <div class="gallery-lightbox-footer">
            <span class="gallery-lightbox-caption" id="lightboxCaption"></span>
            <div style="display: flex; align-items: center; gap: 12px">
                <span class="gallery-lightbox-counter" id="lightboxCounter"></span>
                <a class="gallery-lightbox-dl" download="" href="#" id="lightboxDl"><span class="material-symbols-outlined">download</span></a>
            </div>
        </div>
    </div>
</div>

<?php endwhile; ?>
<?php get_footer(); ?>
