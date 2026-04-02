<?php
/**
 * Single Product Template (Refactored from Single Book)
 *
 * @package Hidayah
 */

get_header();

while ( have_posts() ) : the_post();
    global $product;
    $product = wc_get_product( get_the_ID() );
    
    if ( ! $product ) {
        continue;
    }

    $price         = $product->get_price();
    $regular_price = $product->get_regular_price();
    $sale_price    = $product->get_sale_price();
    $badge         = get_post_meta( get_the_ID(), '_book_badge', true );
    $isbn          = get_post_meta( get_the_ID(), '_book_isbn', true );
    $book_pages    = get_post_meta( get_the_ID(), '_book_pages', true );
    $binding       = get_post_meta( get_the_ID(), '_book_binding', true );
    $weight        = get_post_meta( get_the_ID(), '_book_weight', true ) ?: $product->get_weight();
    $edition       = get_post_meta( get_the_ID(), '_book_edition', true );
    $publisher     = get_post_meta( get_the_ID(), '_book_publisher', true );
    $year          = get_post_meta( get_the_ID(), '_book_year', true );
    $stock_status  = $product->get_stock_status();
    $rating        = $product->get_average_rating() ?: 0;
    $rating_cnt    = $product->get_review_count() ?: 0;
    $toc           = get_post_meta( get_the_ID(), '_book_toc', true );
    $sample_pdf    = get_post_meta( get_the_ID(), '_book_sample_pdf', true );
    
    $authors       = get_the_terms( get_the_ID(), 'book_author' );
    $genres        = get_the_terms( get_the_ID(), 'genre' );
?>

<section class="archive-section">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="archive-breadcrumb">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php _e( 'Home', 'hidayah' ); ?></a>
            <span class="material-symbols-outlined breadcrumb-sep">chevron_right</span>
            <a href="<?php echo get_post_type_archive_link( 'product' ); ?>"><?php _e( 'Books', 'hidayah' ); ?></a>
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
                                <?php if ( $badge ) : ?>
                                    <span class="book-sales-badge"><?php echo esc_html( $badge ); ?></span>
                                <?php elseif ( $stock_status !== 'instock' ) : ?>
                                    <span class="book-sales-badge book-badge-out"><?php _e( 'Out of Stock', 'hidayah' ); ?></span>
                                <?php endif; ?>
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <?php the_post_thumbnail( 'large', array( 'id' => 'sbMainImg', 'class' => 'sb-img' ) ); ?>
                                <?php else : ?>
                                    <img id="sbMainImg" class="sb-img" src="<?php echo get_template_directory_uri(); ?>/assets/images/default-book.png" alt="<?php the_title_attribute(); ?>">
                                <?php endif; ?>
                                <div class="sb-gallery-zoom-hint">
                                    <span class="material-symbols-outlined">zoom_in</span>
                                    <?php _e( 'Hover to Zoom', 'hidayah' ); ?>
                                </div>
                            </div>

                            <?php
                            $gallery_ids = $product->get_gallery_image_ids();
                            if ( ! empty( $gallery_ids ) || has_post_thumbnail() ) :
                            ?>
                                <div class="sb-thumb-strip">
                                    <?php if ( has_post_thumbnail() ) : 
                                        $full_img = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' );
                                    ?>
                                        <button class="sb-thumb active" data-src="<?php echo esc_url($full_img[0]); ?>">
                                            <?php the_post_thumbnail( 'thumbnail' ); ?>
                                        </button>
                                    <?php endif; ?>
                                    
                                    <?php foreach ( $gallery_ids as $attachment_id ) : 
                                        $thumb_img = wp_get_attachment_image_src( $attachment_id, 'thumbnail' );
                                        $full_img  = wp_get_attachment_image_src( $attachment_id, 'large' );
                                    ?>
                                        <button class="sb-thumb" data-src="<?php echo esc_url($full_img[0]); ?>">
                                            <img src="<?php echo esc_url($thumb_img[0]); ?>" alt="<?php _e( 'Book Page', 'hidayah' ); ?>" />
                                        </button>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Right: Info -->
                        <div class="sb-info">
                            <div class="sb-title-block">
                                <h1 class="sb-title"><?php the_title(); ?></h1>
                                <?php if ( ! empty( $authors ) ) : ?>
                                    <p class="sb-author">
                                        <span class="material-symbols-outlined sb-meta-icon">person</span>
                                        <?php echo esc_html( $authors[0]->name ); ?>
                                    </p>
                                <?php endif; ?>
                                <div class="sb-quick-meta">
                                    <?php if ( $year ) : ?>
                                        <span class="sb-meta-chip">
                                            <span class="material-symbols-outlined">calendar_month</span>
                                            <?php printf( __( 'Published: %s', 'hidayah' ), $year ); ?>
                                        </span>
                                    <?php endif; ?>
                                    <?php if ( $book_pages ) : ?>
                                        <span class="sb-meta-chip">
                                            <span class="material-symbols-outlined">menu_book</span>
                                            <?php printf( __( '%s Pages', 'hidayah' ), $book_pages ); ?>
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
                                      else echo '<span class="material-symbols-outlined star empty">star</span>';
                                  }
                                  ?>
                                  <span class="sb-rating-value"><?php echo $rating; ?></span>
                               </div>
                               <span class="sb-rating-count"><?php printf( _n( '%s Review', '%s Reviews', $rating_cnt, 'hidayah' ), $rating_cnt ); ?></span>
                               <a class="sb-rating-link" href="#comments"><?php _e( 'View Reviews', 'hidayah' ); ?></a>
                            </div>

                            <div class="sb-purchase-box">
                                <div class="sb-pricing">
                                    <?php if ( $product->is_on_sale() && $regular_price ) : ?>
                                        <span class="sb-old-price"><?php echo __('Tk.', 'hidayah'); ?> <?php echo $regular_price; ?></span>
                                    <?php endif; ?>
                                    <span class="sb-price"><?php echo __('Tk.', 'hidayah'); ?> <?php echo $price; ?></span>
                                    <?php if ( $product->is_on_sale() && $regular_price && $price ) : 
                                        $discount = round((($regular_price - $price) / $regular_price) * 100);
                                    ?>
                                        <span class="sb-discount-badge"><?php echo $discount; ?>% <?php _e( 'Off', 'hidayah' ); ?></span>
                                    <?php endif; ?>
                                    
                                    <div class="sb-stock-badge <?php echo $stock_status === 'instock' ? 'sb-stock-ok' : 'sb-stock-out'; ?>">
                                        <span class="material-symbols-outlined"><?php echo $stock_status === 'instock' ? 'check_circle' : 'error'; ?></span>
                                        <?php echo $stock_status === 'instock' ? __( 'In Stock', 'hidayah' ) : __( 'Out of Stock', 'hidayah' ); ?>
                                    </div>
                                </div>

                                <div class="sb-buy-controls">
                                    <?php 
                                    if ( function_exists( 'woocommerce_template_single_add_to_cart' ) ) {
                                        woocommerce_template_single_add_to_cart();
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Share buttons -->
                    <?php get_template_part( 'template-parts/content/share-section' ); ?>

                    <!-- Book Content -->
                    <?php if ( get_the_content() ) : ?>
                        <div class="sb-section entry-content">
                            <h2 class="sb-section-title"><?php _e( 'Book Summary', 'hidayah' ); ?></h2>
                            <?php the_content(); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Details Table -->
                    <div class="sb-section">
                        <h2 class="sb-section-title"><?php _e( 'Book Details', 'hidayah' ); ?></h2>
                        <table class="sb-details-table">
                            <tbody>
                                <?php if ($publisher) : ?>
                                    <tr><th><?php _e( 'Publisher', 'hidayah' ); ?></th><td><?php echo esc_html($publisher); ?></td></tr>
                                <?php endif; ?>
                                <?php if ($edition) : ?>
                                    <tr><th><?php _e( 'Edition', 'hidayah' ); ?></th><td><?php echo esc_html($edition); ?></td></tr>
                                <?php endif; ?>
                                <?php if ($book_pages) : ?>
                                    <tr><th><?php _e( 'Number of Pages', 'hidayah' ); ?></th><td><?php echo $book_pages; ?></td></tr>
                                <?php endif; ?>
                                <?php if ($binding) : ?>
                                    <tr><th><?php _e( 'Binding', 'hidayah' ); ?></th><td><?php echo esc_html($binding); ?></td></tr>
                                <?php endif; ?>
                                <?php if (!empty($genres)) : ?>
                                    <tr><th><?php _e( 'Subject', 'hidayah' ); ?></th><td><?php echo esc_html($genres[0]->name); ?></td></tr>
                                <?php endif; ?>
                                <?php if ($isbn) : ?>
                                    <tr><th>ISBN</th><td><?php echo esc_html($isbn); ?></td></tr>
                                <?php endif; ?>
                                <?php if ($weight) : ?>
                                    <tr><th><?php _e( 'Weight', 'hidayah' ); ?></th><td><?php echo $weight; ?> <?php _e( 'grams', 'hidayah' ); ?></td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- TOC -->
                    <?php if ($toc) : ?>
                        <div class="sb-section">
                            <h2 class="sb-section-title"><?php _e( 'Table of Contents', 'hidayah' ); ?></h2>
                            <div class="sb-toc">
                                <button type="button" class="sb-toc-toggle" id="sbTocToggle">
                                    <span class="material-symbols-outlined">list</span>
                                    <?php _e( 'View Table of Contents', 'hidayah' ); ?>
                                    <span class="material-symbols-outlined sb-toc-arrow">expand_more</span>
                                </button>
                                <div class="sb-toc-list" id="sbTocList">
                                    <?php
                                    $toc_raw = strip_tags( $toc );
                                    $toc_lines = preg_split( '/\r\n|\r|\n/', $toc_raw );
                                    $toc_lines = array_filter( array_map( 'trim', $toc_lines ) );
                                    if ( ! empty( $toc_lines ) ) :
                                    ?>
                                        <ol>
                                            <?php foreach ( $toc_lines as $line ) : ?>
                                                <li><?php echo esc_html( $line ); ?></li>
                                            <?php endforeach; ?>
                                        </ol>
                                    <?php else : ?>
                                        <?php echo wpautop( $toc ); ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Sample PDF -->
                    <?php if ( ! empty( $sample_pdf ) ) : ?>
                        <div class="sb-section">
                            <h2 class="sb-section-title"><?php _e( 'Sample Pages (PDF)', 'hidayah' ); ?></h2>
                            <div class="sb-pdf-preview">
                                <div class="sb-pdf-info">
                                    <div class="sb-pdf-meta">
                                        <span class="material-symbols-outlined sb-pdf-icon">picture_as_pdf</span>
                                        <div class="sb-pdf-text">
                                            <h4><?php _e( 'Read Sample Pages of the Book', 'hidayah' ); ?></h4>
                                            <p><?php _e( 'You can read some parts of the book online before buying.', 'hidayah' ); ?></p>
                                        </div>
                                    </div>
                                    <a href="<?php echo esc_url( $sample_pdf ); ?>" class="sb-pdf-btn" target="_blank">
                                        <span class="material-symbols-outlined">open_in_new</span>
                                        <?php _e( 'Read in New Tab', 'hidayah' ); ?>
                                    </a>
                                </div>
                                <div class="sb-pdf-embed-wrapper" id="pdfWrapper">
                                    <div id="pdfContainer" class="sb-pdf-js-container">
                                        <div class="sb-pdf-loading">
                                            <div class="sb-pdf-spinner"></div>
                                            <p><?php _e( 'PDF is loading...', 'hidayah' ); ?></p>
                                        </div>
                                    </div>
                                    <div id="pdfFallback" style="display:none; text-align:center; padding: 40px;">
                                        <p><?php printf( __( 'Your browser does not support PDF. %sClick here%s to download.', 'hidayah' ), '<a href="' . esc_url( $sample_pdf ) . '">', '</a>' ); ?></p>
                                    </div>
                                </div>

                                <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>
                                <script>
                                    (function() {
                                        const url = '<?php echo esc_js($sample_pdf); ?>';
                                        const container = document.getElementById('pdfContainer');
                                        const loading = container.querySelector('.sb-pdf-loading');
                                        const fallback = document.getElementById('pdfFallback');
                                        
                                        if (typeof window['pdfjs-dist/build/pdf'] === 'undefined') {
                                            console.error('PDF.js not loaded');
                                            return;
                                        }

                                        const pdfjsLib = window['pdfjs-dist/build/pdf'];
                                        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.worker.min.js';

                                        pdfjsLib.getDocument(url).promise.then(function(pdf) {
                                            loading.style.display = 'none';
                                            
                                            // Render all pages for the sample
                                            for(let i = 1; i <= pdf.numPages; i++) {
                                                pdf.getPage(i).then(function(page) {
                                                    const canvas = document.createElement('canvas');
                                                    canvas.className = 'sb-pdf-page';
                                                    container.appendChild(canvas);
                                                    
                                                    const context = canvas.getContext('2d');
                                                    const unscaledViewport = page.getViewport({scale: 1});
                                                    
                                                    // Responsive scaling
                                                    const targetWidth = container.clientWidth;
                                                    const scale = targetWidth / unscaledViewport.width;
                                                    const viewport = page.getViewport({scale: Math.min(scale, 1.5)});
                                                    
                                                    canvas.height = viewport.height;
                                                    canvas.width = viewport.width;
                                                    
                                                    page.render({
                                                        canvasContext: context,
                                                        viewport: viewport
                                                    });
                                                });
                                            }
                                        }).catch(function(err) {
                                            console.error('PDF.js error:', err);
                                            loading.style.display = 'none';
                                            fallback.style.display = 'block';
                                        });
                                    })();
                                </script>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Reviews -->
                    <?php
                    if ( comments_open() || get_comments_number() ) {
                        comments_template();
                    }
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
                            <h4 class="sidebar-widget-title"><?php _e( 'Author Introduction', 'hidayah' ); ?></h4>
                            <div class="sidebar-author-box">
                                <div class="sa-avatar"><span class="material-symbols-outlined">person</span></div>
                                <div class="sa-info">
                                    <h5><?php echo esc_html($author->name); ?></h5>
                                    <p><?php echo esc_html(wp_trim_words($author->description, 20)); ?></p>
                                    <a href="<?php echo get_term_link($author); ?>" class="sa-link"><?php _e( 'All Books of the Author', 'hidayah' ); ?> →</a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Delivery/Trust Widget -->
                    <div class="sidebar-widget trust-widget">
                        <div class="trust-item">
                            <span class="material-symbols-outlined">local_shipping</span>
                            <div class="trust-text">
                                <strong><?php _e( 'Home Delivery Nationwide', 'hidayah' ); ?></strong>
                                <p><?php _e( 'Fast delivery within 2-5 working days', 'hidayah' ); ?></p>
                            </div>
                        </div>
                        <div class="trust-item">
                            <span class="material-symbols-outlined">verified_user</span>
                            <div class="trust-text">
                                <strong><?php _e( 'Secure Payment', 'hidayah' ); ?></strong>
                                <p><?php _e( 'Cash on Delivery facility', 'hidayah' ); ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Related Products -->
                    <div class="sidebar-widget">
                        <h4 class="sidebar-widget-title"><?php _e( 'Related Books', 'hidayah' ); ?></h4>
                        <ul class="sidebar-popular-books">
                        <?php
                        $rel_products = new WP_Query( array(
                            'post_type'      => 'product',
                            'posts_per_page' => 5,
                            'post__not_in'   => array( get_the_ID() ),
                        ) );
                        while ( $rel_products->have_posts() ) : $rel_products->the_post(); 
                            $r_product = wc_get_product( get_the_ID() );
                            $r_price = $r_product->get_price();
                        ?>
                            <li>
                                <a href="<?php the_permalink(); ?>">
                                    <div class="popular-book-thumb">
                                        <?php the_post_thumbnail('thumbnail'); ?>
                                    </div>
                                    <div class="popular-book-info">
                                        <h5><?php the_title(); ?></h5>
                                        <span class="popular-book-price"><?php echo __('Tk.', 'hidayah'); ?> <?php echo $r_price; ?></span>
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

<!-- Image Lightbox Modal -->
<div id="sbLightbox" class="modal">
    <div class="modal-content sb-lightbox-content">
        <span class="close-modal" id="sbLightboxClose">&times;</span>
        <button class="sb-lightbox-nav sb-prev" id="sbLightboxPrev" aria-label="<?php esc_attr_e( 'Previous', 'hidayah' ); ?>">
            <span class="material-symbols-outlined">chevron_left</span>
        </button>
        <div class="sb-lightbox-img-container">
            <img id="sbLightboxImg" src="" alt="<?php esc_attr_e( 'Large Image', 'hidayah' ); ?>">
        </div>
        <button class="sb-lightbox-nav sb-next" id="sbLightboxNext" aria-label="<?php esc_attr_e( 'Next', 'hidayah' ); ?>">
            <span class="material-symbols-outlined">chevron_right</span>
        </button>
    </div>
</div>

<?php endwhile; ?>

<?php get_footer(); ?>
