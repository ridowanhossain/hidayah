<?php
/**
 * Single Book Template
 *
 * @package Hidayah
 */
get_header();

while ( have_posts() ) : the_post();
    $price        = get_post_meta( get_the_ID(), '_book_price', true );
    $old_price    = get_post_meta( get_the_ID(), '_book_old_price', true );
    $isbn         = get_post_meta( get_the_ID(), '_book_isbn', true );
    $pages        = get_post_meta( get_the_ID(), '_book_pages', true );
    $binding      = get_post_meta( get_the_ID(), '_book_binding', true );
    $weight       = get_post_meta( get_the_ID(), '_book_weight', true );
    $edition      = get_post_meta( get_the_ID(), '_book_edition', true );
    $publisher    = get_post_meta( get_the_ID(), '_book_publisher', true );
    $year         = get_post_meta( get_the_ID(), '_book_year', true );
    $stock_status = get_post_meta( get_the_ID(), '_stock_status', true ) ?: 'instock';
    $rating       = get_post_meta( get_the_ID(), '_book_rating', true ) ?: 0;
    $rating_cnt   = get_post_meta( get_the_ID(), '_book_rating_count', true ) ?: 0;
    $toc          = get_post_meta( get_the_ID(), '_book_toc', true );
    $samples      = get_post_meta( get_the_ID(), '_book_samples', true ); // Expecting array of URLs
    
    $authors      = get_the_terms( get_the_ID(), 'book_author' );
    $genres       = get_the_terms( get_the_ID(), 'genre' );
?>

<section class="archive-hero">
    <div class="archive-hero-content">
        <h2><?php _e( 'বই বিক্রয় কর্নার', 'hidayah' ); ?></h2>
        <p><?php _e( 'দরবার শরীফ অনুমোদিত ইসলামী কিতাব, তাফসীর, হাদীস ও তাসাউফ বিষয়ক বই সংগ্রহ করুন।', 'hidayah' ); ?></p>
    </div>
</section>

<section class="archive-section">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="archive-breadcrumb">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php _e( 'হোম', 'hidayah' ); ?></a>
            <span class="material-symbols-outlined breadcrumb-sep">chevron_right</span>
            <a href="<?php echo get_post_type_archive_link( 'book' ); ?>"><?php _e( 'বই', 'hidayah' ); ?></a>
            <?php if ( ! empty( $genres ) ) : ?>
                <span class="material-symbols-outlined breadcrumb-sep">chevron_right</span>
                <a href="<?php echo get_term_link( $genres[0] ); ?>"><?php echo esc_html( $genres[0]->name ); ?></a>
            <?php endif; ?>
            <span class="material-symbols-outlined breadcrumb-sep">chevron_right</span>
            <span class="breadcrumb-current"><?php the_title(); ?></span>
        </nav>

        <div class="archive-layout">
            <!-- LEFT COLUMN -->
            <div class="archive-main">
                <div class="col-inner">
                    <div class="sb-hero">
                        <!-- Left: Gallery -->
                        <div class="sb-gallery">
                            <div class="sb-main-cover">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <?php the_post_thumbnail( 'large', array( 'id' => 'sbMainImg', 'alt' => get_the_title() ) ); ?>
                                <?php else : ?>
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/book-placeholder.png" id="sbMainImg" alt="<?php the_title_attribute(); ?>" />
                                <?php endif; ?>
                                <span class="sb-gallery-zoom-hint">
                                    <span class="material-symbols-outlined">zoom_in</span>
                                    <?php _e( 'হোভার করে জুম করুন', 'hidayah' ); ?>
                                </span>
                            </div>
                        </div>

                        <!-- Right: Info -->
                        <div class="sb-info">
                            <div class="sb-title-block">
                                <h1 class="sb-title"><?php the_title(); ?></h1>
                                <?php if ( ! empty( $authors ) ) : ?>
                                    <p class="sb-author">
                                        <span class="material-symbols-outlined sb-meta-icon">person</span>
                                        <a href="<?php echo get_term_link( $authors[0] ); ?>"><?php echo esc_html( $authors[0]->name ); ?></a>
                                    </p>
                                <?php endif; ?>
                                <div class="sb-quick-meta">
                                    <?php if ( $year ) : ?>
                                        <span class="sb-meta-chip">
                                            <span class="material-symbols-outlined">calendar_month</span>
                                            <?php printf( __( 'প্রকাশ: %s', 'hidayah' ), hidayah_en_to_bn_number($year) ); ?>
                                        </span>
                                    <?php endif; ?>
                                    <?php if ( $pages ) : ?>
                                        <span class="sb-meta-chip">
                                            <span class="material-symbols-outlined">menu_book</span>
                                            <?php printf( __( '%s পৃষ্ঠা', 'hidayah' ), hidayah_en_to_bn_number($pages) ); ?>
                                        </span>
                                    <?php endif; ?>
                                    <?php if ( $isbn ) : ?>
                                        <span class="sb-meta-chip">
                                            <span class="material-symbols-outlined">qr_code</span>
                                            ISBN: <?php echo esc_html($isbn); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="sb-rating-summary">
                                <div class="sb-rating-stars">
                                    <?php 
                                    for ($i=1; $i<=5; $i++) {
                                        if ($rating >= $i) echo '<span class="material-symbols-outlined star filled">star</span>';
                                        elseif ($rating > ($i-1)) echo '<span class="material-symbols-outlined star half">star_half</span>';
                                        else echo '<span class="material-symbols-outlined star empty">star_border</span>';
                                    }
                                    ?>
                                    <span class="sb-rating-value"><?php echo hidayah_en_to_bn_number($rating); ?></span>
                                </div>
                                <span class="sb-rating-count"><?php printf( __( '%s জনের মতামত', 'hidayah' ), hidayah_en_to_bn_number($rating_cnt) ); ?></span>
                            </div>

                            <div class="sb-purchase-box">
                                <div class="sb-pricing">
                                    <?php if ( $old_price ) : ?>
                                        <span class="sb-old-price">৳ <?php echo hidayah_en_to_bn_number($old_price); ?></span>
                                    <?php endif; ?>
                                    <span class="sb-price">৳ <?php echo hidayah_en_to_bn_number($price); ?></span>
                                    <?php if ($old_price && $price) : 
                                        $discount = round((($old_price - $price) / $old_price) * 100);
                                    ?>
                                        <span class="sb-discount-badge"><?php echo hidayah_en_to_bn_number($discount); ?>% <?php _e( 'ছাড়', 'hidayah' ); ?></span>
                                    <?php endif; ?>
                                    
                                    <div class="sb-stock-badge <?php echo $stock_status === 'instock' ? 'sb-stock-ok' : 'sb-stock-out'; ?>">
                                        <span class="material-symbols-outlined"><?php echo $stock_status === 'instock' ? 'check_circle' : 'error'; ?></span>
                                        <?php echo $stock_status === 'instock' ? __( 'স্টকে আছে', 'hidayah' ) : __( 'স্টক শেষ', 'hidayah' ); ?>
                                    </div>
                                </div>

                                <div class="sb-buy-controls">
                                    <div class="sb-qty-wrap">
                                        <label class="sb-qty-label"><?php _e( 'পরিমাণ:', 'hidayah' ); ?></label>
                                        <div class="sb-qty-stepper">
                                            <button type="button" class="sb-qty-btn" onclick="document.getElementById('sbQtyInput').stepDown()">
                                                <span class="material-symbols-outlined">remove</span>
                                            </button>
                                            <input class="sb-qty-input" id="sbQtyInput" max="20" min="1" type="number" value="1"/>
                                            <button type="button" class="sb-qty-btn" onclick="document.getElementById('sbQtyInput').stepUp()">
                                                <span class="material-symbols-outlined">add</span>
                                            </button>
                                        </div>
                                    </div>
                                    <?php if ($stock_status === 'instock') : ?>
                                        <button class="sb-add-to-cart-btn add-to-cart-btn" data-book-id="<?php the_ID(); ?>" data-book-price="<?php echo esc_attr( $price ); ?>" data-book-title="<?php the_title_attribute(); ?>">
                                            <span class="material-symbols-outlined">shopping_cart</span>
                                            <?php _e( 'কার্টে যোগ করুন', 'hidayah' ); ?>
                                        </button>
                                    <?php else : ?>
                                        <button class="sb-add-to-cart-btn" disabled style="opacity: 0.6; cursor: not-allowed;">
                                            <span class="material-symbols-outlined">inventory_2</span>
                                            <?php _e( 'স্টক নেই', 'hidayah' ); ?>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Share buttons -->
                    <div class="sb-share-buttons-wrap">
                        <div class="sb-share-grid-full">
                            <h3 class="sb-share-title">
                                <span class="material-symbols-outlined">share</span>
                                <?php _e( 'শেয়ার করুন', 'hidayah' ); ?>
                            </h3>
                            <div class="sb-share-actions">
                                <a class="share-btn share-facebook" href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>" target="_blank">
                                    <span class="material-symbols-outlined">thumb_up</span>
                                    Facebook
                                </a>
                                <a class="share-btn share-whatsapp" href="https://api.whatsapp.com/send?text=<?php the_permalink(); ?>" target="_blank">
                                    <span class="material-symbols-outlined">chat</span>
                                    WhatsApp
                                </a>
                                <button class="share-btn share-copy" onclick="navigator.clipboard.writeText(window.location.href); alert('<?php _e( 'লিঙ্ক কপি হয়েছে!', 'hidayah' ); ?>');">
                                    <span class="material-symbols-outlined">link</span>
                                    <?php _e( 'লিঙ্ক কপি করুন', 'hidayah' ); ?>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Book Content -->
                    <div class="sb-section entry-content">
                        <h2 class="sb-section-title"><?php _e( 'বইয়ের সারসংক্ষেপ', 'hidayah' ); ?></h2>
                        <?php the_content(); ?>
                    </div>

                    <!-- Details Table -->
                    <div class="sb-section">
                        <h2 class="sb-section-title"><?php _e( 'বইয়ের বিস্তারিত তথ্য', 'hidayah' ); ?></h2>
                        <table class="sb-details-table">
                            <tbody>
                                <?php if ($publisher) : ?>
                                    <tr><th><?php _e( 'প্রকাশনী', 'hidayah' ); ?></th><td><?php echo esc_html($publisher); ?></td></tr>
                                <?php endif; ?>
                                <?php if ($edition) : ?>
                                    <tr><th><?php _e( 'সংস্করণ', 'hidayah' ); ?></th><td><?php echo esc_html($edition); ?></td></tr>
                                <?php endif; ?>
                                <?php if ($pages) : ?>
                                    <tr><th><?php _e( 'পৃষ্ঠা সংখ্যা', 'hidayah' ); ?></th><td><?php echo hidayah_en_to_bn_number($pages); ?></td></tr>
                                <?php endif; ?>
                                <?php if ($binding) : ?>
                                    <tr><th><?php _e( 'বাইন্ডিং', 'hidayah' ); ?></th><td><?php echo esc_html($binding); ?></td></tr>
                                <?php endif; ?>
                                <?php if (!empty($genres)) : ?>
                                    <tr><th><?php _e( 'বিষয়', 'hidayah' ); ?></th><td><?php echo esc_html($genres[0]->name); ?></td></tr>
                                <?php endif; ?>
                                <?php if ($isbn) : ?>
                                    <tr><th>ISBN</th><td><?php echo esc_html($isbn); ?></td></tr>
                                <?php endif; ?>
                                <?php if ($weight) : ?>
                                    <tr><th><?php _e( 'ওজন', 'hidayah' ); ?></th><td><?php echo hidayah_en_to_bn_number($weight); ?> <?php _e( 'গ্রাম', 'hidayah' ); ?></td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- TOC -->
                    <?php if ($toc) : ?>
                        <div class="sb-section">
                            <h2 class="sb-section-title"><?php _e( 'সূচীপত্র', 'hidayah' ); ?></h2>
                            <div class="sb-toc">
                                <div class="sb-toc-list">
                                    <?php echo wpautop($toc); ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Samples -->
                    <?php if (!empty($samples)) : ?>
                        <div class="sb-section">
                            <h2 class="sb-section-title"><?php _e( 'নমুনা পাতা', 'hidayah' ); ?></h2>
                            <div class="sb-preview-strip">
                                <?php foreach ($samples as $url) : ?>
                                    <a class="sb-preview-thumb" href="<?php echo esc_url($url); ?>" target="_blank">
                                        <img src="<?php echo esc_url($url); ?>" alt="Preview" />
                                        <span class="sb-preview-overlay"><span class="material-symbols-outlined">zoom_in</span></span>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Reviews -->
                    <?php
                    if ( comments_open() || get_comments_number() ) :
                        comments_template();
                    endif;
                    ?>
                </div>
            </div>

            <!-- RIGHT SIDEBAR -->
            <aside class="archive-sidebar">
                <div class="col-inner">
                    <!-- Author Card -->
                    <?php if ( ! empty( $authors ) ) : 
                        $author = $authors[0];
                    ?>
                        <div class="sidebar-widget author-card-widget">
                            <h4 class="sidebar-widget-title"><?php _e( 'লেখক পরিচিতি', 'hidayah' ); ?></h4>
                            <div class="sidebar-author-box">
                                <div class="sa-avatar"><span class="material-symbols-outlined">person</span></div>
                                <div class="sa-info">
                                    <h5><?php echo esc_html($author->name); ?></h5>
                                    <p><?php echo esc_html(wp_trim_words($author->description, 20)); ?></p>
                                    <a href="<?php echo get_term_link($author); ?>" class="sa-link"><?php _e( 'লেখকের সব বই', 'hidayah' ); ?> →</a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Delivery/Trust Widget -->
                    <div class="sidebar-widget trust-widget">
                        <div class="trust-item">
                            <span class="material-symbols-outlined">local_shipping</span>
                            <div class="trust-text">
                                <strong><?php _e( 'সারাদেশে হোম ডেলিভারি', 'hidayah' ); ?></strong>
                                <p><?php _e( '২-৫ কর্মদিবসের মধ্যে দ্রুত ডেলিভারি', 'hidayah' ); ?></p>
                            </div>
                        </div>
                        <div class="trust-item">
                            <span class="material-symbols-outlined">verified_user</span>
                            <div class="trust-text">
                                <strong><?php _e( 'নিরাপদ পেমেন্ট', 'hidayah' ); ?></strong>
                                <p><?php _e( 'ক্যাশ অন ডেলিভারি সুবিধা', 'hidayah' ); ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Related Books -->
                    <div class="sidebar-widget">
                        <h4 class="sidebar-widget-title"><?php _e( 'সম্পর্কিত বই', 'hidayah' ); ?></h4>
                        <?php
                        $rel_books = new WP_Query( array(
                            'post_type'      => 'book',
                            'posts_per_page' => 5,
                            'post__not_in'   => array( get_the_ID() ),
                        ) );
                        while ( $rel_books->have_posts() ) : $rel_books->the_post(); 
                            $r_price = get_post_meta( get_the_ID(), '_book_price', true );
                        ?>
                            <div class="sidebar-video-card">
                                <div class="sidebar-thumb-wrapper" onclick="window.location.href='<?php the_permalink(); ?>'">
                                    <?php the_post_thumbnail('thumbnail'); ?>
                                </div>
                                <div class="sidebar-card-content">
                                    <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                    <div class="sidebar-meta">
                                        <span class="price-bn">৳ <?php echo hidayah_en_to_bn_number($r_price); ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; wp_reset_postdata(); ?>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>

<?php endwhile; ?>

<?php get_footer(); ?>
