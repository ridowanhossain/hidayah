<?php
/**
 * Template Name: Single Monthly HD
 * Template Post Type: monthly_magazine
 */

get_header();

if (have_posts()) :
    while (have_posts()) : the_post();
        $post_id = get_the_ID();
        
        // Metadata
        $pdf_url   = get_post_meta($post_id, '_magazine_pdf', true);
        $pages     = get_post_meta($post_id, '_magazine_pages', true);
        $size      = get_post_meta($post_id, '_magazine_file_size', true);
        $vol       = get_post_meta($post_id, '_issue_vol', true);
        $num       = get_post_meta($post_id, '_issue_num', true);
        $month_yr  = get_post_meta($post_id, '_issue_month', true);
        $downloads = get_post_meta($post_id, '_magazine_downloads', true);
        $topics_raw = get_post_meta($post_id, '_magazine_topics', true);
        
        $editorial = get_post_meta($post_id, '_editorial_text', true);
        $toc       = get_post_meta($post_id, '_magazine_toc', true);
        $summaries = get_post_meta($post_id, '_article_summaries', true);
?>

<section class="archive-hero single-monthly-hero">
    <div class="archive-hero-content">
        <h2><?php _e( 'Monthly Haquer Dawat', 'hidayah' ); ?></h2>
        <p><?php _e( 'The monthly Islamic magazine of Darbar Sharif — read, download, and share.', 'hidayah' ); ?></p>
    </div>
</section>

<section class="archive-section">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="archive-breadcrumb">
            <a href="<?php echo esc_url(home_url('/')); ?>"><?php _e('Home', 'hidayah'); ?></a>
            <span class="material-symbols-outlined breadcrumb-sep">chevron_right</span>
            <a href="<?php echo get_post_type_archive_link('monthly_magazine'); ?>"><?php _e('Monthly Haquer Dawat', 'hidayah'); ?></a>
            <span class="material-symbols-outlined breadcrumb-sep">chevron_right</span>
            <span class="breadcrumb-current"><?php the_title(); ?></span>
        </nav>

        <div class="archive-layout">
            <!-- Main Content -->
            <main class="archive-main">
                <div class="col-inner" itemscope itemtype="https://schema.org/PublicationIssue">
                    <!-- Single Header -->
                    <div class="mhd-single-header">
                        <div class="mhd-single-cover">
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('full', array('alt' => get_the_title())); ?>
                            <?php else : ?>
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/placeholder-magazine.jpg" alt="<?php the_title_attribute(); ?>">
                            <?php endif; ?>
                        </div>
                        
                        <div class="mhd-single-info">
                            <h1 class="mhd-single-title" itemprop="name"><?php the_title(); ?></h1>
                            <div class="mhd-single-chips">
                                <span class="sb-meta-chip">
                                    <span class="material-symbols-outlined">calendar_month</span>
                                    <?php echo hidayah_en_to_bn_number(get_the_date()); ?>
                                </span>
                                <?php if ($pages) : ?>
                                    <span class="sb-meta-chip">
                                        <span class="material-symbols-outlined">menu_book</span>
                                        <?php echo hidayah_en_to_bn_number(esc_html($pages)); ?>
                                    </span>
                                <?php endif; ?>
                                <?php if ($size) : ?>
                                    <span class="sb-meta-chip">
                                        <span class="material-symbols-outlined">description</span>
                                        <?php echo hidayah_en_to_bn_number(esc_html($size)); ?>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <?php if (has_excerpt()) : ?>
                                <p class="mhd-single-desc"><?php echo get_the_excerpt(); ?></p>
                            <?php endif; ?>

                            <div class="mhd-single-tags">
                                <?php
                                if ($topics_raw) {
                                    $topics = explode(',', $topics_raw);
                                    foreach ($topics as $topic) {
                                        echo '<span class="mhd-tag">' . esc_html(trim($topic)) . '</span>';
                                    }
                                }
                                ?>
                            </div>

                            <?php if ($pdf_url) : ?>
                                <a href="<?php echo esc_url($pdf_url); ?>" class="mhd-dl-primary" target="_blank">
                                    <div class="mhd-dl-label">
                                        <span class="material-symbols-outlined">download</span>
                                        <?php _e('Download PDF', 'hidayah'); ?>
                                    </div>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- PDF Viewer -->
                    <div class="sb-section">
                        <h2 class="sb-section-title"><?php _e('Read Magazine', 'hidayah'); ?></h2>
                        <div class="mhd-pdf-viewer">
                            <div class="mhd-pdf-placeholder">
                                <span class="material-symbols-outlined">picture_as_pdf</span>
                                <p><?php _e('PDF viewer will load here', 'hidayah'); ?></p>
                                <p style="font-size: 13px; color: var(--text-light)"><?php _e('Or use the download button above', 'hidayah'); ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Table of Contents -->
                    <?php if ($toc) : ?>
                    <div class="sb-section">
                        <h2 class="sb-section-title"><?php _e('Table of Contents', 'hidayah'); ?></h2>
                        <ol class="mhd-toc-list">
                            <?php 
                            $toc_lines = explode("\n", $toc);
                            foreach ($toc_lines as $line) {
                                if (trim($line)) {
                                    $parts = explode('|', $line);
                                    $title = trim($parts[0]);
                                    $page  = isset($parts[1]) ? trim($parts[1]) : '';
                                    ?>
                                    <li>
                                        <a href="<?php echo esc_url($pdf_url ?: '#'); ?>"><?php echo esc_html($title); ?></a>
                                        <?php if ($page): ?><span><?php echo esc_html($page); ?></span><?php endif; ?>
                                    </li>
                                    <?php
                                }
                            }
                            ?>
                        </ol>
                    </div>
                    <?php endif; ?>

                    <!-- Article Summaries -->
                    <?php if ($summaries) : 
                        $summary_data = json_decode($summaries, true);
                        if (!empty($summary_data)) : ?>
                        <div class="sb-section">
                            <h2 class="sb-section-title"><?php _e('Article Summaries', 'hidayah'); ?></h2>
                            <div class="mhd-article-summaries">
                                <?php foreach ($summary_data as $item) : ?>
                                    <div class="mhd-article-summary">
                                        <h4><?php echo esc_html($item['title']); ?></h4>
                                        <p><?php echo esc_html($item['content'] ?? $item['desc'] ?? ''); ?></p>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; endif; ?>

                    <!-- Editorial -->
                    <?php if ($editorial) : ?>
                        <div class="sb-section">
                            <h2 class="sb-section-title"><?php _e('Editorial', 'hidayah'); ?></h2>
                            <div class="mhd-editorial">
                                <blockquote><?php echo wpautop(wp_kses_post($editorial)); ?></blockquote>
                                <p class="mhd-editorial-author">— <?php _e('Murshid Qibla, Editorial', 'hidayah'); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Share Section -->
                    <?php get_template_part( 'template-parts/content/share-section' ); ?>

                    <!-- Post Nav -->
                    <div class="single-audio-nav">
                        <?php
                        $prev_post = get_previous_post();
                        if ($prev_post) : ?>
                            <a class="single-audio-nav-btn single-audio-nav-prev" href="<?php echo get_permalink($prev_post->ID); ?>">
                                <span class="material-symbols-outlined">arrow_back</span>
                                <div class="single-audio-nav-text">
                                    <span class="nav-label"><?php _e('Previous Issue', 'hidayah'); ?></span>
                                    <span class="nav-title-sm"><?php echo get_the_title($prev_post->ID); ?></span>
                                </div>
                            </a>
                        <?php endif; ?>

                        <?php
                        $next_post = get_next_post();
                        if ($next_post) : ?>
                            <a class="single-audio-nav-btn single-audio-nav-next" href="<?php echo get_permalink($next_post->ID); ?>">
                                <div class="single-audio-nav-text">
                                    <span class="nav-label"><?php _e('Next Issue', 'hidayah'); ?></span>
                                    <span class="nav-title-sm"><?php echo get_the_title($next_post->ID); ?></span>
                                </div>
                                <span class="material-symbols-outlined">arrow_forward</span>
                            </a>
                        <?php endif; ?>
                    </div>

                    <!-- Comments Section -->
                    <?php 
                    if (comments_open() || get_comments_number()) :
                        comments_template();
                    endif;
                    ?>
                </div>
            </main>

            <!-- Sidebar -->
            <aside class="archive-sidebar">
                <div class="col-inner">
                    <!-- Issue Info Table -->
                    <div class="sidebar-widget">
                        <h4 class="sidebar-widget-title">
                            <span class="material-symbols-outlined">info</span>
                            <?php _e('Magazine Details', 'hidayah'); ?>
                        </h4>
                        <table class="sb-details-table">
                            <tbody>
                                <?php if ($vol): ?>
                                <tr>
                                    <th><?php _e('Volume', 'hidayah'); ?></th>
                                    <td><?php echo hidayah_en_to_bn_number(esc_html($vol)); ?></td>
                                </tr>
                                <?php endif; ?>
                                <?php if ($num): ?>
                                <tr>
                                    <th><?php _e('Number', 'hidayah'); ?></th>
                                    <td><?php echo hidayah_en_to_bn_number(esc_html($num)); ?></td>
                                </tr>
                                <?php endif; ?>
                                <?php if ($month_yr): ?>
                                <tr>
                                    <th><?php _e('Month & Year', 'hidayah'); ?></th>
                                    <td><?php echo hidayah_en_to_bn_number(esc_html($month_yr)); ?></td>
                                </tr>
                                <?php endif; ?>
                                <?php if ($pages): ?>
                                <tr>
                                    <th><?php _e('Total Pages', 'hidayah'); ?></th>
                                    <td><?php echo hidayah_en_to_bn_number(esc_html($pages)); ?></td>
                                </tr>
                                <?php endif; ?>
                                <?php if ($size): ?>
                                <tr>
                                    <th><?php _e('File Size', 'hidayah'); ?></th>
                                    <td><?php echo hidayah_en_to_bn_number(esc_html($size)); ?></td>
                                </tr>
                                <?php endif; ?>
                                <?php if ($downloads): ?>
                                <tr>
                                    <th><?php _e('Downloads', 'hidayah'); ?></th>
                                    <td><?php echo esc_html($downloads); ?></td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Prev/Next Issues (with cover) -->
                    <div class="sidebar-widget">
                        <h4 class="sidebar-widget-title">
                            <span class="material-symbols-outlined">swap_horiz</span>
                            <?php _e('Previous/Next Issues', 'hidayah'); ?>
                        </h4>
                        <ul class="sidebar-popular-books">
                            <?php if ($prev_post) : ?>
                            <li>
                                <a href="<?php echo get_permalink($prev_post->ID); ?>">
                                    <div class="popular-book-thumb">
                                        <?php echo get_the_post_thumbnail($prev_post->ID, 'thumbnail'); ?>
                                    </div>
                                    <div class="popular-book-info">
                                        <h5><?php echo get_the_title($prev_post->ID); ?></h5>
                                        <span class="popular-book-price"><?php _e('Previous Issue', 'hidayah'); ?></span>
                                    </div>
                                </a>
                            </li>
                            <?php endif; ?>
                            
                            <?php if ($next_post) : ?>
                            <li>
                                <a href="<?php echo get_permalink($next_post->ID); ?>">
                                    <div class="popular-book-thumb">
                                        <?php echo get_the_post_thumbnail($next_post->ID, 'thumbnail'); ?>
                                    </div>
                                    <div class="popular-book-info">
                                        <h5><?php echo get_the_title($next_post->ID); ?></h5>
                                        <span class="popular-book-price"><?php _e('Next Issue', 'hidayah'); ?></span>
                                    </div>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <!-- Year Archive Widget -->
                    <div class="sidebar-widget">
                        <h4 class="sidebar-widget-title">
                            <span class="material-symbols-outlined">list</span>
                            <?php _e('Issues of this Year', 'hidayah'); ?>
                        </h4>
                        <ul class="sidebar-recent-list">
                            <?php
                            // Get other issues from the same year
                            $this_year = get_the_date('Y');
                            $year_issues = new WP_Query(array(
                                'post_type'      => 'monthly_magazine',
                                'posts_per_page' => 5,
                                'year'           => $this_year,
                                'post__not_in'   => array($post_id),
                            ));

                            if ($year_issues->have_posts()) :
                                while ($year_issues->have_posts()) : $year_issues->the_post();
                            ?>
                                    <li>
                                        <a href="<?php the_permalink(); ?>">
                                            <span class="material-symbols-outlined recent-icon">newspaper</span>
                                            <div class="recent-info">
                                                <h5><?php the_title(); ?></h5>
                                                <span><?php echo get_post_meta(get_the_ID(), '_magazine_pages', true); ?></span>
                                            </div>
                                        </a>
                                    </li>
                            <?php
                                endwhile;
                                wp_reset_postdata();
                            else :
                                echo '<li>' . __('No issues found.', 'hidayah') . '</li>';
                            endif;
                            ?>
                        </ul>
                    </div>

                    <!-- All Issues CTA -->
                    <div class="sidebar-widget">
                        <a class="sb-all-books-cta" href="<?php echo get_post_type_archive_link('monthly_magazine'); ?>">
                            <span class="material-symbols-outlined">newspaper</span>
                            <?php _e('View All Issues', 'hidayah'); ?>
                            <span class="material-symbols-outlined">arrow_forward</span>
                        </a>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>

<?php
    endwhile;
endif;

get_footer();
?>