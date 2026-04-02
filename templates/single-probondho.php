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

    $read_time = get_post_meta( get_the_ID(), '_reading_time', true ) ?: '5';
    $cats      = get_the_terms( get_the_ID(), 'probondho_cat' );
    $cat       = ! empty( $cats ) ? $cats[0] : null;
?>

<section class="archive-hero">
    <div class="archive-hero-content">
        <h2><?php _e( 'Articles & Writings', 'hidayah' ); ?></h2>
        <p><?php _e( 'Articles and analytical compositions written by Haqqani scholars on various Islamic topics.', 'hidayah' ); ?></p>
    </div>
</section>

<section class="archive-section">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="archive-breadcrumb">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php _e( 'Home', 'hidayah' ); ?></a>
            <span class="material-symbols-outlined breadcrumb-sep">chevron_right</span>
            <a href="<?php echo get_post_type_archive_link( 'probondho' ); ?>"><?php _e( 'Articles', 'hidayah' ); ?></a>
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
                <div class="col-inner" itemscope itemtype="https://schema.org/Article">
                    <!-- Article Title -->
                    <div class="probondho-article-header">
                        <?php if ( $cat ) : ?>
                            <span class="probondho-cat-badge"><?php echo esc_html( $cat->name ); ?></span>
                        <?php endif; ?>
                        <h1 class="probondho-article-title" itemprop="headline"><?php the_title(); ?></h1>
                        
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
                                        <?php printf( __( '%s Min Read', 'hidayah' ), $read_time ); ?>
                                    </span>
                                    <span>
                                        <span class="material-symbols-outlined">visibility</span>
                                        <?php printf( __( '%s Readers', 'hidayah' ), number_format_i18n($views) ); ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Font Size Control -->
                        <div class="probondho-font-ctrl">
                            <span style="font-size: 12px; color: var(--text-light)"><?php _e( 'Font Size:', 'hidayah' ); ?></span>
                            <button class="probondho-font-btn" data-size="small"><?php _e( 'Small', 'hidayah' ); ?></button>
                            <button class="probondho-font-btn active" data-size="medium"><?php _e( 'Medium', 'hidayah' ); ?></button>
                            <button class="probondho-font-btn" data-size="large"><?php _e( 'Large', 'hidayah' ); ?></button>
                        </div>
                    </div>

                    <!-- Featured Image -->
                    <?php if ( has_post_thumbnail() ) : ?>
                        <figure class="probondho-featured-img-wrap">
                            <?php the_post_thumbnail( 'full' ); ?>
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
                            <h3 class="sb-section-title"><?php _e( 'Tags', 'hidayah' ); ?></h3>
                            <div class="single-audio-tags">
                                <?php foreach ($tags as $tag) : ?>
                                    <a class="single-audio-tag" href="<?php echo get_term_link($tag); ?>"><?php echo esc_html($tag->name); ?></a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- References -->
                    <?php
                    $references_raw = get_post_meta( get_the_ID(), '_probondho_references', true );
                    if ( ! empty( $references_raw ) ) :
                        $refs = explode( "\n", str_replace( "\r", "", $references_raw ) );
                        $refs = array_filter( array_map( 'trim', $refs ) );
                        if ( ! empty( $refs ) ) : ?>
                            <div class="sb-section">
                                <h3 class="sb-section-title"><?php _e( 'References', 'hidayah' ); ?></h3>
                                <ol class="mhd-toc-list">
                                    <?php foreach ( $refs as $ref ) : ?>
                                        <li style="justify-content: flex-start; text-align: left;"><?php echo esc_html( $ref ); ?></li>
                                    <?php endforeach; ?>
                                </ol>
                            </div>
                        <?php endif;
                    endif; ?>

                    <!-- Author Bio -->
                    <div class="sb-section sb-author-card" style="border: 1px solid rgba(6, 95, 70, 0.1); border-radius: 14px; padding: 20px">
                        <h3 class="sb-section-title"><?php _e( 'About the Author', 'hidayah' ); ?></h3>
                        <div class="sb-author-inner">
                            <div class="sb-author-avatar">
                                <?php echo get_avatar( get_the_author_meta( 'ID' ), 100 ); ?>
                            </div>
                            <div class="sb-author-meta">
                                <h5><?php the_author(); ?></h5>
                                <p><?php the_author_meta( 'description' ); ?></p>
                                <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" style="font-size: 13px; color: var(--primary-green-dark); font-weight: 600"><?php _e( 'All articles by this author →', 'hidayah' ); ?></a>
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
                                    <span class="nav-label"><?php _e( 'Previous Article', 'hidayah' ); ?></span>
                                    <span class="nav-title-sm"><?php echo wp_trim_words( get_the_title( $prev_post->ID ), 5 ); ?>...</span>
                                </div>
                            </a>
                        <?php endif; ?>

                        <?php
                        $next_post = get_next_post();
                        if ( ! empty( $next_post ) ) : ?>
                            <a class="single-audio-nav-btn single-audio-nav-next" href="<?php echo get_permalink( $next_post->ID ); ?>">
                                <div class="single-audio-nav-text">
                                    <span class="nav-label"><?php _e( 'Next Article', 'hidayah' ); ?></span>
                                    <span class="nav-title-sm"><?php echo wp_trim_words( get_the_title( $next_post->ID ), 5 ); ?>...</span>
                                </div>
                                <span class="material-symbols-outlined">arrow_forward</span>
                            </a>
                        <?php endif; ?>
                    </div>

                    <!-- Related Articles -->
                    <div class="sb-section">
                        <h3 class="sb-section-title"><?php _e( 'Related Articles', 'hidayah' ); ?></h3>
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
                                            <?php _e( 'Read More', 'hidayah' ); ?>
                                            <span class="material-symbols-outlined">arrow_forward</span>
                                        </a>
                                    </div>
                                </article>
                            <?php endwhile; wp_reset_postdata(); ?>
                        </div>
                    </div>

                    <!-- Share -->
                    <?php get_template_part( 'template-parts/content/share-section' ); ?>

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
                            <?php _e( 'Author Info', 'hidayah' ); ?>
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
                                <p><?php printf( __( 'Islamic Author. Total %s articles published.', 'hidayah' ), $author_posts_count ); ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Table of Contents (Logic usually requires a plugin or custom JS parsing) -->
                    <div class="sidebar-widget">
                        <h4 class="sidebar-widget-title">
                            <span class="material-symbols-outlined">list</span>
                            <?php _e( 'Table of Contents', 'hidayah' ); ?>
                        </h4>
                        <div id="probondhoTOC" class="sb-toc-list" style="display: block">
                            <!-- TOC items will be injected by JS or hardcoded if meta exists -->
                        </div>
                    </div>

                    <!-- Related Articles (Sidebar) -->
                    <div class="sidebar-widget">
                        <h4 class="sidebar-widget-title">
                            <span class="material-symbols-outlined">article</span>
                            <?php _e( 'Related Articles', 'hidayah' ); ?>
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
                                $rb_time = get_post_meta( get_the_ID(), '_reading_time', true ) ?: '5';
                            ?>
                                <li>
                                    <a href="<?php the_permalink(); ?>">
                                        <span class="material-symbols-outlined recent-icon">article</span>
                                        <div class="recent-info">
                                            <h5><?php the_title(); ?></h5>
                                            <span><?php echo ($cat ? esc_html($cat->name) : 'General') . ' • ' . $rb_time . ' ' . __( 'Min', 'hidayah' ); ?></span>
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
                            <?php _e( 'Print', 'hidayah' ); ?>
                        </button>
                    </div>

                    <!-- CTA -->
                    <div class="sidebar-widget">
                        <a class="sb-all-books-cta" href="<?php echo esc_url( home_url( '/submit-article' ) ); ?>">
                            <span class="material-symbols-outlined">edit_note</span>
                            <?php _e( 'Submit Article', 'hidayah' ); ?>
                            <span class="material-symbols-outlined">arrow_forward</span>
                        </a>
                    </div>

                    <!-- All Link -->
                    <div class="sidebar-widget">
                        <a class="sb-all-books-cta" href="<?php echo get_post_type_archive_link( 'probondho' ); ?>" style="background: var(--gold-accent)">
                            <span class="material-symbols-outlined">article</span>
                            <?php _e( 'View All Articles', 'hidayah' ); ?>
                            <span class="material-symbols-outlined">arrow_forward</span>
                        </a>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>

<?php endwhile; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const content = document.getElementById('articleBody');
    const toc = document.getElementById('probondhoTOC');
    if (!content || !toc) return;

    const headings = content.querySelectorAll('h2');
    if (headings.length === 0) {
        const widget = toc.closest('.sidebar-widget');
        if (widget) widget.style.display = 'none';
        return;
    }

    const ol = document.createElement('ol');
    ol.className = 'sb-toc-listm'; // Using a helper class or style
    ol.style.listStyle = 'none';
    ol.style.padding = '0';
    ol.style.margin = '0';

    headings.forEach((heading, index) => {
        const id = 'heading-' + index;
        heading.setAttribute('id', id);

        const li = document.createElement('li');
        li.style.marginBottom = '10px';
        li.style.paddingBottom = '10px';
        li.style.borderBottom = '1px dashed rgba(6, 95, 70, 0.1)';

        const a = document.createElement('a');
        a.href = '#' + id;
        a.textContent = heading.textContent;
        a.style.color = 'var(--primary-green-dark)';
        a.style.textDecoration = 'none';
        a.style.fontSize = '14px';
        a.style.fontWeight = '500';
        a.style.display = 'block';
        
        li.appendChild(a);
        ol.appendChild(li);
    });

    toc.innerHTML = '';
    toc.appendChild(ol);

    // Reading Progress Bar
    window.onscroll = function() {
        const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
        const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        const scrolled = (winScroll / height) * 100;
        const progressFill = document.getElementById("readingProgressFill");
        if (progressFill) {
            progressFill.style.width = scrolled + "%";
        }
    };
});
</script>

<?php get_footer(); ?>
