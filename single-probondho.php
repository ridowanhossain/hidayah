<?php
/**
 * Single Probondho (Article) Template
 *
 * @package Hidayah
 */
get_header();

while ( have_posts() ) : the_post();
    // Update view count
    $views = get_post_meta( get_the_ID(), '_post_views_count', true ) ?: 0;
    update_post_meta( get_the_ID(), '_post_views_count', $views + 1 );

    $read_time = get_post_meta( get_the_ID(), '_reading_time', true ) ?: '৫';
    $cats      = get_the_terms( get_the_ID(), 'probondho_cat' );
    $cat       = ! empty( $cats ) ? $cats[0] : null;
?>

<section class="archive-hero">
    <div class="archive-hero-content">
        <h2><?php _e( 'প্রবন্ধ ও লেখনি', 'hidayah' ); ?></h2>
        <p><?php _e( 'ইসলামের বিভিন্ন বিষয়ে হক্কানী আলেমদের লেখা প্রবন্ধ ও বিশ্লেষণধর্মী রচনা।', 'hidayah' ); ?></p>
    </div>
</section>

<section class="archive-section">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="archive-breadcrumb">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php _e( 'হোম', 'hidayah' ); ?></a>
            <span class="material-symbols-outlined breadcrumb-sep">chevron_right</span>
            <a href="<?php echo get_post_type_archive_link( 'probondho' ); ?>"><?php _e( 'প্রবন্ধ', 'hidayah' ); ?></a>
            <?php if ( $cat ) : ?>
                <span class="material-symbols-outlined breadcrumb-sep">chevron_right</span>
                <a href="<?php echo get_term_link( $cat ); ?>"><?php echo esc_html( $cat->name ); ?></a>
            <?php endif; ?>
            <span class="material-symbols-outlined breadcrumb-sep">chevron_right</span>
            <span class="breadcrumb-current"><?php echo wp_trim_words( get_the_title(), 5 ); ?>...</span>
        </nav>

        <!-- Reading Progress Bar -->
        <div class="probondho-progress-bar" id="readingProgress">
            <div class="probondho-progress-fill" id="readingProgressFill"></div>
        </div>

        <div class="archive-layout">
            <!-- LEFT -->
            <div class="archive-main">
                <div class="col-inner">
                    <!-- Article Title -->
                    <div class="probondho-article-header">
                        <?php if ( $cat ) : ?>
                            <span class="probondho-cat-badge"><?php echo esc_html( $cat->name ); ?></span>
                        <?php endif; ?>
                        <h1 class="probondho-article-title"><?php the_title(); ?></h1>
                        
                        <!-- Author Strip -->
                        <div class="probondho-author-strip">
                            <div class="probondho-author-avatar">
                                <?php echo get_avatar( get_the_author_meta( 'ID' ), 60 ); ?>
                            </div>
                            <div class="probondho-author-strip-info">
                                <strong><?php the_author(); ?></strong>
                                <div class="probondho-strip-meta">
                                    <span>
                                        <span class="material-symbols-outlined">calendar_month</span>
                                        <?php echo get_the_date(); ?>
                                    </span>
                                    <span>
                                        <span class="material-symbols-outlined">schedule</span>
                                        <?php printf( __( '%s মিনিটে পড়ুন', 'hidayah' ), hidayah_en_to_bn_number($read_time) ); ?>
                                    </span>
                                    <span>
                                        <span class="material-symbols-outlined">visibility</span>
                                        <?php printf( __( '%s পাঠক', 'hidayah' ), hidayah_en_to_bn_number(number_format_i18n($views)) ); ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Font Size Control -->
                        <div class="probondho-font-ctrl">
                            <span style="font-size: 12px; color: var(--text-light)"><?php _e( 'ফন্ট সাইজ:', 'hidayah' ); ?></span>
                            <button class="probondho-font-btn" data-size="small"><?php _e( 'ছোট', 'hidayah' ); ?></button>
                            <button class="probondho-font-btn active" data-size="medium"><?php _e( 'মাঝারি', 'hidayah' ); ?></button>
                            <button class="probondho-font-btn" data-size="large"><?php _e( 'বড়', 'hidayah' ); ?></button>
                        </div>
                    </div>

                    <!-- Featured Image -->
                    <?php if ( has_post_thumbnail() ) : ?>
                        <figure class="probondho-featured-img-wrap">
                            <?php the_post_thumbnail( 'large' ); ?>
                            <?php if ( get_the_post_thumbnail_caption() ) : ?>
                                <figcaption><?php the_post_thumbnail_caption(); ?></figcaption>
                            <?php endif; ?>
                        </figure>
                    <?php endif; ?>

                    <!-- Article Content -->
                    <div class="probondho-article-body" id="articleBody">
                        <?php the_content(); ?>
                    </div>

                    <!-- Tags -->
                    <?php
                    $tags = get_the_terms( get_the_ID(), 'post_tag' );
                    if ( ! empty( $tags ) ) : ?>
                        <div class="sb-section">
                            <h3 class="sb-section-title"><?php _e( 'ট্যাগসমূহ', 'hidayah' ); ?></h3>
                            <div class="single-audio-tags">
                                <?php foreach ($tags as $tag) : ?>
                                    <a class="single-audio-tag" href="<?php echo get_term_link($tag); ?>"><?php echo esc_html($tag->name); ?></a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Author Bio -->
                    <div class="sb-section sb-author-card" style="border: 1px solid rgba(6, 95, 70, 0.1); border-radius: 14px; padding: 20px">
                        <h3 class="sb-section-title"><?php _e( 'লেখক পরিচিতি', 'hidayah' ); ?></h3>
                        <div class="sb-author-inner">
                            <div class="sb-author-avatar">
                                <?php echo get_avatar( get_the_author_meta( 'ID' ), 100 ); ?>
                            </div>
                            <div class="sb-author-meta">
                                <h5><?php the_author(); ?></h5>
                                <p><?php the_author_meta( 'description' ); ?></p>
                                <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" style="font-size: 13px; color: var(--primary-green-dark); font-weight: 600"><?php _e( 'এই লেখকের সকল প্রবন্ধ →', 'hidayah' ); ?></a>
                            </div>
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
                                    <span class="nav-label"><?php _e( 'পূর্ববর্তী প্রবন্ধ', 'hidayah' ); ?></span>
                                    <span class="nav-title-sm"><?php echo wp_trim_words( get_the_title( $prev_post->ID ), 5 ); ?>...</span>
                                </div>
                            </a>
                        <?php endif; ?>

                        <?php
                        $next_post = get_next_post();
                        if ( ! empty( $next_post ) ) : ?>
                            <a class="single-audio-nav-btn single-audio-nav-next" href="<?php echo get_permalink( $next_post->ID ); ?>">
                                <div class="single-audio-nav-text">
                                    <span class="nav-label"><?php _e( 'পরবর্তী প্রবন্ধ', 'hidayah' ); ?></span>
                                    <span class="nav-title-sm"><?php echo wp_trim_words( get_the_title( $next_post->ID ), 5 ); ?>...</span>
                                </div>
                                <span class="material-symbols-outlined">arrow_forward</span>
                            </a>
                        <?php endif; ?>
                    </div>

                    <!-- Related Articles -->
                    <div class="sb-section">
                        <h3 class="sb-section-title"><?php _e( 'সম্পর্কিত প্রবন্ধ', 'hidayah' ); ?></h3>
                        <div class="probondho-related-grid">
                            <?php
                            $related = new WP_Query( array(
                                'post_type'      => 'probondho',
                                'posts_per_page' => 2,
                                'post__not_in'   => array( get_the_ID() ),
                                'tax_query'      => $cat ? array(
                                    array(
                                        'taxonomy' => 'probondho_cat',
                                        'field'    => 'term_id',
                                        'terms'    => $cat->term_id,
                                    ),
                                ) : array(),
                            ) );
                            while ( $related->have_posts() ) : $related->the_post();
                            ?>
                                <article class="probondho-card">
                                    <div class="probondho-card-img">
                                        <?php if ( has_post_thumbnail() ) the_post_thumbnail( 'medium' ); ?>
                                    </div>
                                    <div class="probondho-card-content">
                                        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                        <p><?php echo wp_trim_words( get_the_excerpt(), 15 ); ?></p>
                                        <a class="probondho-read-more" href="<?php the_permalink(); ?>">
                                            <?php _e( 'পড়ুন', 'hidayah' ); ?>
                                            <span class="material-symbols-outlined">arrow_forward</span>
                                        </a>
                                    </div>
                                </article>
                            <?php endwhile; wp_reset_postdata(); ?>
                        </div>
                    </div>

                    <!-- Share -->
                    <div class="single-audio-share-wrap mb-4">
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
                    <!-- Author Card -->
                    <div class="sidebar-widget sb-author-card">
                        <h4 class="sidebar-widget-title">
                            <span class="material-symbols-outlined">person</span>
                            <?php _e( 'লেখকের তথ্য', 'hidayah' ); ?>
                        </h4>
                        <div class="sb-author-inner">
                            <div class="sb-author-avatar">
                                <?php echo get_avatar( get_the_author_meta( 'ID' ), 60 ); ?>
                            </div>
                            <div class="sb-author-meta">
                                <h5><?php the_author(); ?></h5>
                                <?php
                                $author_posts_count = count_user_posts( get_the_author_meta( 'ID' ), 'probondho' );
                                ?>
                                <p><?php printf( __( 'ইসলামী লেখক। মোট %sটি প্রবন্ধ প্রকাশিত।', 'hidayah' ), hidayah_en_to_bn_number($author_posts_count) ); ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Table of Contents (Logic usually requires a plugin or custom JS parsing) -->
                    <div class="sidebar-widget">
                        <h4 class="sidebar-widget-title">
                            <span class="material-symbols-outlined">list</span>
                            <?php _e( 'সূচীপত্র', 'hidayah' ); ?>
                        </h4>
                        <div id="probondhoTOC" class="sb-toc-list" style="display: block">
                            <!-- TOC items will be injected by JS or hardcoded if meta exists -->
                        </div>
                    </div>

                    <!-- Related Articles (Sidebar) -->
                    <div class="sidebar-widget">
                        <h4 class="sidebar-widget-title">
                            <span class="material-symbols-outlined">article</span>
                            <?php _e( 'সম্পর্কিত প্রবন্ধ', 'hidayah' ); ?>
                        </h4>
                        <ul class="sidebar-recent-list">
                            <?php
                            $related_sb = new WP_Query( array(
                                'post_type'      => 'probondho',
                                'posts_per_page' => 3,
                                'post__not_in'   => array( get_the_ID() ),
                                'tax_query'      => $cat ? array(
                                    array(
                                        'taxonomy' => 'probondho_cat',
                                        'field'    => 'term_id',
                                        'terms'    => $cat->term_id,
                                    ),
                                ) : array(),
                            ) );
                            while ( $related_sb->have_posts() ) : $related_sb->the_post();
                                $rb_time = get_post_meta( get_the_ID(), '_reading_time', true ) ?: '৫';
                            ?>
                                <li>
                                    <a href="<?php the_permalink(); ?>">
                                        <span class="material-symbols-outlined recent-icon">article</span>
                                        <div class="recent-info">
                                            <h5><?php the_title(); ?></h5>
                                            <span><?php echo ($cat ? esc_html($cat->name) : 'সাধারণ') . ' • ' . h_bn_num($rb_time) . ' ' . __( 'মিনিট', 'hidayah' ); ?></span>
                                        </div>
                                    </a>
                                </li>
                            <?php endwhile; wp_reset_postdata(); ?>
                        </ul>
                    </div>

                    <!-- Print Button -->
                    <div class="sidebar-widget">
                        <button class="sb-submit-btn" onclick="window.print()" style="width: 100%; justify-content: center">
                            <span class="material-symbols-outlined">print</span>
                            <?php _e( 'প্রিন্ট করুন', 'hidayah' ); ?>
                        </button>
                    </div>

                    <!-- CTA -->
                    <div class="sidebar-widget">
                        <a class="sb-all-books-cta" href="<?php echo esc_url( home_url( '/submit-article' ) ); ?>">
                            <span class="material-symbols-outlined">edit_note</span>
                            <?php _e( 'লেখা পাঠান', 'hidayah' ); ?>
                            <span class="material-symbols-outlined">arrow_forward</span>
                        </a>
                    </div>

                    <!-- All Link -->
                    <div class="sidebar-widget">
                        <a class="sb-all-books-cta" href="<?php echo get_post_type_archive_link( 'probondho' ); ?>" style="background: var(--gold-accent)">
                            <span class="material-symbols-outlined">article</span>
                            <?php _e( 'সব প্রবন্ধ দেখুন', 'hidayah' ); ?>
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
