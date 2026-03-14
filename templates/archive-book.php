<?php
/**
 * Archive Template for Book
 *
 * @package Hidayah
 */
get_header();

// Get search and sort parameters
$s = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
$orderby = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : 'date';
$order = 'DESC';
$meta_key = '';

if ($orderby === 'price-asc') {
    $meta_key = '_book_price';
    $orderby = 'meta_value_num';
    $order = 'ASC';
} elseif ($orderby === 'price-desc') {
    $meta_key = '_book_price';
    $orderby = 'meta_value_num';
    $order = 'DESC';
} elseif ($orderby === 'popular') {
    $meta_key = '_post_views_count';
    $orderby = 'meta_value_num';
}

$args = array(
    'post_type'      => 'book',
    'posts_per_page' => 12,
    'paged'          => get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1,
    's'              => $s,
    'orderby'        => $orderby,
    'order'          => $order,
);

if ($meta_key) {
    $args['meta_key'] = $meta_key;
}

$book_query = new WP_Query( $args );
?>

<section class="archive-hero">
    <div class="archive-hero-content">
        <h2><?php _e( 'বই বিক্রয় কর্নার', 'hidayah' ); ?></h2>
        <p><?php _e( 'দরবার শরীফ অনুমোদিত ইসলামী কিতাব, তাফসীর, হাদীস ও তাসাউফ বিষয়ক বই সংগ্রহ করুন। বিশ্বস্ত উৎস থেকে, সরাসরি আপনার দোরগোড়ায়।', 'hidayah' ); ?></p>
    </div>
</section>

<section class="archive-section">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="archive-breadcrumb">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php _e( 'হোম', 'hidayah' ); ?></a>
            <span class="material-symbols-outlined breadcrumb-sep">chevron_right</span>
            <span class="breadcrumb-current"><?php _e( 'সকল বই', 'hidayah' ); ?></span>
        </nav>

        <div class="archive-layout">
            <!-- Left Column -->
            <div class="archive-main">
                <div class="col-inner">
                    <!-- Search & Sort Bar -->
                    <div class="archive-toolbar">
                        <div class="archive-search-bar">
                            <form id="bookSearchForm" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" style="display: flex; width: 100%; align-items: center;">
                                <span class="material-symbols-outlined">search</span>
                                <input class="archive-search-input" id="bookSearchInput" placeholder="<?php _e( 'বই খুঁজুন...', 'hidayah' ); ?>" type="text" name="s" value="<?php echo esc_attr($s); ?>" />
                                <input type="hidden" name="post_type" value="book" />
                            </form>
                        </div>
                        <div class="archive-toolbar-right">
                            <select class="archive-sort-select" id="bookSortSelect">
                                <option value="newest" <?php selected($_GET['orderby'] ?? '', 'newest'); ?>><?php _e( 'নতুন প্রথমে', 'hidayah' ); ?></option>
                                <option value="price-asc" <?php selected($_GET['orderby'] ?? '', 'price-asc'); ?>><?php _e( 'মূল্য: কম → বেশি', 'hidayah' ); ?></option>
                                <option value="price-desc" <?php selected($_GET['orderby'] ?? '', 'price-desc'); ?>><?php _e( 'মূল্য: বেশি → কম', 'hidayah' ); ?></option>
                                <option value="popular" <?php selected($_GET['orderby'] ?? '', 'popular'); ?>><?php _e( 'জনপ্রিয়', 'hidayah' ); ?></option>
                            </select>
                            <div class="archive-view-toggle" data-view-target="#bookArchiveGrid">
                                <button class="view-toggle-btn active" data-view="grid" title="গ্রিড ভিউ">
                                    <span class="material-symbols-outlined">grid_view</span>
                                </button>
                                <button class="view-toggle-btn" data-view="list" title="লিস্ট ভিউ">
                                    <span class="material-symbols-outlined">view_list</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Book Topbar -->
                    <div class="book-archive-topbar">
                        <div class="archive-filters-toolbar">
                            <div class="archive-count-badge" id="bookCountBadge">
                                <span class="material-symbols-outlined">menu_book</span>
                                <?php printf( __( 'মোট %sটি বই', 'hidayah' ), hidayah_en_to_bn_number( $book_query->found_posts ) ); ?>
                            </div>
                            <div class="archive-taxonomy-filters">
                                <select id="bookGenreFilter">
                                    <option value=""><?php _e( 'ধরণ অনুযায়ী', 'hidayah' ); ?></option>
                                    <?php foreach ( get_terms( array( 'taxonomy' => 'genre' ) ) as $g ) : ?>
                                        <option value="<?php echo esc_attr( $g->term_id ); ?>"><?php echo esc_html( $g->name ); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <select id="bookAuthorFilter">
                                    <option value=""><?php _e( 'লেখক অনুযায়ী', 'hidayah' ); ?></option>
                                    <?php foreach ( get_terms( array( 'taxonomy' => 'book_author' ) ) as $a ) : ?>
                                        <option value="<?php echo esc_attr( $a->term_id ); ?>"><?php echo esc_html( $a->name ); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Book Grid -->
                    <div style="position: relative; min-height: 200px;">
                        <div id="bookLoader" class="archive-ajax-loader" style="display: none;">
                            <span class="material-symbols-outlined rotating">progress_activity</span>
                        </div>
                        <div class="book-archive-grid" id="bookArchiveGrid">
                            <?php if ( $book_query->have_posts() ) : while ( $book_query->have_posts() ) : $book_query->the_post(); 
                            $price      = get_post_meta( get_the_ID(), '_book_price', true );
                            $old_price  = get_post_meta( get_the_ID(), '_book_old_price', true );
                            $badge      = get_post_meta( get_the_ID(), '_book_badge', true );
                            $rating     = get_post_meta( get_the_ID(), '_book_rating', true ) ?: 0;
                            $rating_cnt = get_post_meta( get_the_ID(), '_book_rating_count', true ) ?: 0;
                            $stock_status = get_post_meta( get_the_ID(), '_stock_status', true ) ?: 'instock';
                        ?>
                            <article class="book-archive-card">
                                <a class="book-sales-link" href="<?php the_permalink(); ?>">
                                    <div class="book-sales-cover">
                                        <?php if ( has_post_thumbnail() ) : ?>
                                            <?php the_post_thumbnail( 'medium', array( 'alt' => get_the_title() ) ); ?>
                                        <?php else : ?>
                                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/book-placeholder.png" alt="<?php the_title_attribute(); ?>" />
                                        <?php endif; ?>
                                        <?php if ( $badge ) : ?>
                                            <span class="book-sales-badge"><?php echo esc_html( $badge ); ?></span>
                                        <?php endif; ?>
                                        <?php if ( $stock_status === 'outofstock' ) : ?>
                                            <span class="book-sales-badge book-badge-out"><?php _e( 'স্টক শেষ', 'hidayah' ); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </a>
                                <div class="book-sales-content">
                                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                    <p><?php echo wp_trim_words( get_the_excerpt(), 15 ); ?></p>
                                    
                                    <div class="book-star-rating">
                                        <?php
                                        for ( $i = 1; $i <= 5; $i++ ) {
                                            if ( $rating >= $i ) {
                                                echo '<span class="material-symbols-outlined star filled">star</span>';
                                            } elseif ( $rating > ( $i - 1 ) ) {
                                                echo '<span class="material-symbols-outlined star half">star_half</span>';
                                            } else {
                                                echo '<span class="material-symbols-outlined star empty">star_border</span>';
                                            }
                                        }
                                        ?>
                                        <span class="star-count">(<?php echo hidayah_en_to_bn_number($rating_cnt); ?>)</span>
                                    </div>

                                    <div class="book-sales-meta">
                                        <div class="book-sales-pricing">
                                            <?php if ( $old_price ) : ?>
                                                <span class="book-sales-old-price">৳ <?php echo hidayah_en_to_bn_number($old_price); ?></span>
                                            <?php endif; ?>
                                            <span class="book-sales-price">৳ <?php echo hidayah_en_to_bn_number($price); ?></span>
                                        </div>
                                        <?php if ( $stock_status !== 'outofstock' ) : ?>
                                            <button class="book-sales-order-btn" data-book-id="<?php the_ID(); ?>" data-book-price="<?php echo esc_attr( $price ); ?>" data-book-title="<?php the_title_attribute(); ?>">
                                                <span class="material-symbols-outlined">shopping_cart</span>
                                                <?php _e( 'কার্টে যোগ', 'hidayah' ); ?>
                                            </button>
                                        <?php else : ?>
                                            <button class="book-sales-order-btn" disabled>
                                                <span class="material-symbols-outlined">inventory_2</span>
                                                <?php _e( 'স্টক নেই', 'hidayah' ); ?>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </article>
                        <?php endwhile; ?>
                        <?php else : ?>
                            <div class="col-full">
                                <?php get_template_part( 'template-parts/content/content', 'none' ); ?>
                            </div>
                        <?php endif; ?>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div id="bookPagination">
                        <?php hidayah_pagination( $book_query ); ?>
                    </div>
                </div>
            </div>

            <!-- Right Sidebar -->
            <aside class="archive-sidebar">
                <div class="col-inner">
                    <!-- Genre Filter -->
                    <div class="sidebar-widget">
                        <h4 class="sidebar-widget-title">
                            <span class="material-symbols-outlined">category</span>
                            <?php _e( 'বইয়ের ধরণ', 'hidayah' ); ?>
                        </h4>
                        <ul class="sidebar-filter-list">
                            <?php
                            $genres = get_terms( array('taxonomy' => 'genre', 'number' => 15) );
                            foreach ( $genres as $genre ) : ?>
                                <li>
                                    <a href="<?php echo get_term_link($genre); ?>">
                                        <span><?php echo esc_html($genre->name); ?></span>
                                        <span class="filter-count"><?php echo hidayah_en_to_bn_number($genre->count); ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <!-- Author Filter -->
                    <div class="sidebar-widget">
                        <h4 class="sidebar-widget-title">
                            <span class="material-symbols-outlined">person</span>
                            <?php _e( 'লেখক অনুযায়ী', 'hidayah' ); ?>
                        </h4>
                        <ul class="sidebar-filter-list">
                            <?php
                            $authors = get_terms( array('taxonomy' => 'book_author', 'number' => 15) );
                            foreach ( $authors as $author ) : ?>
                                <li>
                                    <a href="<?php echo get_term_link($author); ?>">
                                        <span><?php echo esc_html($author->name); ?></span>
                                        <span class="filter-count"><?php echo hidayah_en_to_bn_number($author->count); ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
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
