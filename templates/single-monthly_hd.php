<?php
/**
 * Single Magazine Template
 *
 * @package Hidayah
 */
get_header();

while ( have_posts() ) : the_post();
    // Update view count
    $views = get_post_meta( get_the_ID(), '_post_views_count', true ) ?: 0;
    update_post_meta( get_the_ID(), '_post_views_count', $views + 1 );

    $pages      = get_post_meta( get_the_ID(), '_magazine_pages', true );
    $pdf_url    = get_post_meta( get_the_ID(), '_pdf_file_url', true );
    $pdf_size   = get_post_meta( get_the_ID(), '_pdf_file_size', true );
    $downloads  = get_post_meta( get_the_ID(), '_pdf_download_count', true ) ?: 0;
    $toc        = get_post_meta( get_the_ID(), '_magazine_toc', true );
    $editorial  = get_post_meta( get_the_ID(), '_editorial_text', true );
    $summaries  = get_post_meta( get_the_ID(), '_article_summaries', true ); // Expecting array of [title, desc]
    if ( is_string( $summaries ) ) {
        $decoded = json_decode( $summaries, true );
        if ( json_last_error() === JSON_ERROR_NONE ) {
            $summaries = $decoded;
        }
    }
?>

<section class="archive-hero">
    <div class="archive-hero-content">
        <h2><?php _e( 'মাসিক হক্বের দা\'ওয়াত', 'hidayah' ); ?></h2>
        <p><?php _e( 'দরবার শরীফের মাসিক ইসলামী ম্যাগাজিন — পড়ুন, ডাউনলোড করুন এবং শেয়ার করুন।', 'hidayah' ); ?></p>
    </div>
</section>

<section class="archive-section">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="archive-breadcrumb">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php _e( 'হোম', 'hidayah' ); ?></a>
            <span class="material-symbols-outlined breadcrumb-sep">chevron_right</span>
            <a href="<?php echo get_post_type_archive_link( 'monthly_hd' ); ?>"><?php _e( 'মাসিক হক্বের দা\'ওয়াত', 'hidayah' ); ?></a>
            <span class="material-symbols-outlined breadcrumb-sep">chevron_right</span>
            <span class="breadcrumb-current"><?php the_title(); ?></span>
        </nav>

        <div class="archive-layout">
            <!-- LEFT -->
            <div class="archive-main">
                <div class="col-inner">
                    <!-- Header -->
                    <div class="mhd-single-header">
                        <div class="mhd-single-cover">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <?php the_post_thumbnail( 'large', array( 'alt' => get_the_title() ) ); ?>
                            <?php else : ?>
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/mag-placeholder.png" alt="<?php the_title_attribute(); ?>" />
                            <?php endif; ?>
                        </div>
                        <div class="mhd-single-info">
                            <h1 class="mhd-single-title"><?php the_title(); ?></h1>
                            <div class="mhd-single-chips">
                                <span class="sb-meta-chip">
                                    <span class="material-symbols-outlined">calendar_month</span>
                                    <?php echo get_the_date(); ?>
                                </span>
                                <?php if ( $pages ) : ?>
                                    <span class="sb-meta-chip">
                                        <span class="material-symbols-outlined">menu_book</span>
                                        <?php printf( __( '%s পৃষ্ঠা', 'hidayah' ), hidayah_en_to_bn_number($pages) ); ?>
                                    </span>
                                <?php endif; ?>
                                <?php if ( $pdf_size ) : ?>
                                    <span class="sb-meta-chip">
                                        <span class="material-symbols-outlined">description</span>
                                        <?php echo hidayah_en_to_bn_number($pdf_size); ?> MB
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="mhd-single-desc">
                                <?php the_excerpt(); ?>
                            </div>
                            <div class="mhd-single-tags">
                                <?php
                                $tags = get_the_terms( get_the_ID(), 'post_tag' );
                                if ( ! empty( $tags ) ) {
                                    foreach ( $tags as $tag ) {
                                        echo '<span class="mhd-tag">' . esc_html( $tag->name ) . '</span>';
                                    }
                                }
                                ?>
                            </div>
                            <?php if ( $pdf_url ) : ?>
                                <a class="mhd-dl-primary" href="<?php echo esc_url($pdf_url); ?>" download onclick="updateDownloadCount(<?php the_ID(); ?>)">
                                    <div class="mhd-dl-label">
                                        <span class="material-symbols-outlined">download</span>
                                        <?php _e( 'PDF ডাউনলোড করুন', 'hidayah' ); ?>
                                    </div>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- PDF Viewer -->
                    <?php if ( $pdf_url ) : ?>
                        <div class="sb-section">
                            <h2 class="sb-section-title"><?php _e( 'পত্রিকা পড়ুন', 'hidayah' ); ?></h2>
                            <div class="mhd-pdf-viewer">
                                <iframe src="<?php echo esc_url( $pdf_url ); ?>#toolbar=0" width="100%" height="600px" style="border: none; border-radius: 8px;" onerror="this.parentElement.innerHTML='<p>PDF preview লোড হচ্ছে না। <a href=\'<?php echo esc_url( $pdf_url ); ?>\' target=\'_blank\'>এখানে ক্লিক করুন</a>।</p>'"></iframe>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- TOC -->
                    <?php if ( $toc ) : ?>
                        <div class="sb-section">
                            <h2 class="sb-section-title"><?php _e( 'সূচীপত্র', 'hidayah' ); ?></h2>
                            <div class="mhd-toc-list">
                                <?php echo wpautop($toc); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Summaries -->
                    <?php if ( ! empty( $summaries ) ) : ?>
                        <div class="sb-section">
                            <h2 class="sb-section-title"><?php _e( 'মূল প্রবন্ধসমূহের সারসংক্ষেপ', 'hidayah' ); ?></h2>
                            <div class="mhd-article-summaries">
                                <?php foreach ( $summaries as $art ) : ?>
                                    <div class="mhd-article-summary">
                                        <h4><?php echo esc_html($art['title']); ?></h4>
                                        <p><?php echo esc_html($art['desc']); ?></p>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Editorial -->
                    <?php if ( $editorial ) : ?>
                        <div class="sb-section">
                            <h2 class="sb-section-title"><?php _e( 'সম্পাদকীয়', 'hidayah' ); ?></h2>
                            <div class="mhd-editorial">
                                <blockquote>"<?php echo esc_html($editorial); ?>"</blockquote>
                                <p class="mhd-editorial-author"><?php _e( '— মুর্শিদ ক্বিবলা, সম্পাদকীয়', 'hidayah' ); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Share -->
                    <div class="single-audio-share-wrap">
                        <h3 class="section-heading-sm">
                            <span class="material-symbols-outlined">share</span>
                            <?php _e( 'শেয়ার করুন', 'hidayah' ); ?>
                        </h3>
                        <div class="main-share-buttons">
                            <a class="share-btn facebook" href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>" target="_blank">
                                <span class="material-symbols-outlined">thumb_up</span>
                                Facebook
                            </a>
                            <a class="share-btn whatsapp" href="https://api.whatsapp.com/send?text=<?php the_permalink(); ?>" target="_blank">
                                <span class="material-symbols-outlined">chat</span>
                                WhatsApp
                            </a>
                            <button class="share-btn copy-link" onclick="navigator.clipboard.writeText(window.location.href); alert('<?php _e( 'লিঙ্ক কপি হয়েছে!', 'hidayah' ); ?>');">
                                <span class="material-symbols-outlined">link</span>
                                <?php _e( 'লিঙ্ক কপি', 'hidayah' ); ?>
                            </button>
                        </div>
                    </div>

                    <!-- Navigation -->
                    <div class="single-audio-nav">
                        <?php
                        $prev_post = get_previous_post();
                        if ( ! empty( $prev_post ) ) : ?>
                            <a class="single-audio-nav-btn single-audio-nav-prev" href="<?php echo get_permalink( $prev_post->ID ); ?>">
                                <span class="material-symbols-outlined">arrow_back</span>
                                <div class="single-audio-nav-text">
                                    <span class="nav-label"><?php _e( 'পূর্ববর্তী সংখ্যা', 'hidayah' ); ?></span>
                                    <span class="nav-title-sm"><?php echo get_the_title( $prev_post->ID ); ?></span>
                                </div>
                            </a>
                        <?php endif; ?>

                        <?php
                        $next_post = get_next_post();
                        if ( ! empty( $next_post ) ) : ?>
                            <a class="single-audio-nav-btn single-audio-nav-next" href="<?php echo get_permalink( $next_post->ID ); ?>">
                                <div class="single-audio-nav-text">
                                    <span class="nav-label"><?php _e( 'পরবর্তী সংখ্যা', 'hidayah' ); ?></span>
                                    <span class="nav-title-sm"><?php echo get_the_title( $next_post->ID ); ?></span>
                                </div>
                                <span class="material-symbols-outlined">arrow_forward</span>
                            </a>
                        <?php endif; ?>
                    </div>

                    <!-- Comments -->
                    <?php
                    if ( comments_open() || get_comments_number() ) :
                        comments_template();
                    endif;
                    ?>
                </div>
            </div>

            <!-- SIDEBAR -->
            <aside class="archive-sidebar">
                <div class="col-inner">
                    <!-- Issue Info -->
                    <div class="sidebar-widget">
                        <h4 class="sidebar-widget-title">
                            <span class="material-symbols-outlined">info</span>
                            <?php _e( 'সংখ্যার তথ্য', 'hidayah' ); ?>
                        </h4>
                        <table class="sb-details-table">
                            <tbody>
                                <tr><th><?php _e( 'সংখ্যা', 'hidayah' ); ?></th><td><?php the_title(); ?></td></tr>
                                <?php if ($pages) : ?>
                                    <tr><th><?php _e( 'পৃষ্ঠা', 'hidayah' ); ?></th><td><?php echo hidayah_en_to_bn_number($pages); ?></td></tr>
                                <?php endif; ?>
                                <?php if ($pdf_size) : ?>
                                    <tr><th><?php _e( 'ফাইল সাইজ', 'hidayah' ); ?></th><td><?php echo hidayah_en_to_bn_number($pdf_size); ?> MB</td></tr>
                                <?php endif; ?>
                                <tr><th><?php _e( 'ডাউনলোড', 'hidayah' ); ?></th><td><?php printf( __( '%s বার ডাউনলোড', 'hidayah' ), hidayah_en_to_bn_number(number_format_i18n($downloads)) ); ?></td></tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Other Issues -->
                    <div class="sidebar-widget">
                        <h4 class="sidebar-widget-title">
                            <span class="material-symbols-outlined">list</span>
                            <?php _e( 'সাম্প্রতিক সংখ্যাসমূহ', 'hidayah' ); ?>
                        </h4>
                        <ul class="sidebar-recent-list">
                            <?php
                            $recent_mag = new WP_Query( array(
                                'post_type'      => 'monthly_hd',
                                'posts_per_page' => 5,
                                'post__not_in'   => array( get_the_ID() ),
                            ) );
                            while ( $recent_mag->have_posts() ) : $recent_mag->the_post();
                            ?>
                                <li>
                                    <a href="<?php the_permalink(); ?>">
                                        <span class="material-symbols-outlined recent-icon">newspaper</span>
                                        <div class="recent-info">
                                            <h5><?php the_title(); ?></h5>
                                            <span><?php echo get_the_date(); ?></span>
                                        </div>
                                    </a>
                                </li>
                            <?php endwhile; wp_reset_postdata(); ?>
                        </ul>
                    </div>

                    <!-- All Issues -->
                    <div class="sidebar-widget">
                        <a class="sb-all-books-cta" href="<?php echo get_post_type_archive_link( 'monthly_hd' ); ?>">
                            <span class="material-symbols-outlined">newspaper</span>
                            <?php _e( 'সব সংখ্যা দেখুন', 'hidayah' ); ?>
                            <span class="material-symbols-outlined">arrow_forward</span>
                        </a>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>

<?php endwhile; ?>

<?php get_footer(); ?>
