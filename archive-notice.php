<?php
/**
 * Notice Archive Template
 *
 * @package Hidayah
 */
get_header();
?>

<section class="archive-hero">
    <div class="archive-hero-content">
      <h2><?php echo hidayah_get_archive_title(); ?></h2>
      <p><?php _e( 'দরবার শরীফের সকল সরকারি নোটিশ, জরুরি ঘোষণা ও মাহফিলের সময়সূচি।', 'hidayah' ); ?></p>
    </div>
</section>

<section class="archive-section">
    <div class="container">
      <!-- Breadcrumb -->
      <nav aria-label="breadcrumb" class="archive-breadcrumb">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php _e( 'হোম', 'hidayah' ); ?></a>
        <span class="material-symbols-outlined breadcrumb-sep"> chevron_right </span>
        <span class="breadcrumb-current"><?php echo hidayah_get_archive_title(); ?></span>
      </nav>

      <div class="archive-layout">
        <!-- LEFT MAIN -->
        <div class="archive-main">
          <div class="col-inner">
            <!-- Toolbar -->
            <div class="archive-toolbar">
              <div class="archive-search-bar">
                <span class="material-symbols-outlined"> search </span>
                <form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get">
                    <input class="archive-search-input" name="s" placeholder="<?php _e( 'নোটিশ খুঁজুন...', 'hidayah' ); ?>" type="text" />
                    <input type="hidden" name="post_type" value="notice" />
                </form>
              </div>
              <div class="archive-toolbar-right">
                <select class="archive-sort-select">
                  <option value="newest"><?php _e( 'নতুন প্রথমে', 'hidayah' ); ?></option>
                  <option value="oldest"><?php _e( 'পুরাতন প্রথমে', 'hidayah' ); ?></option>
                </select>
              </div>
            </div>

            <!-- Tabs / Filter (Static for now, but could be dynamic) -->
            <div class="jiggasa-tabs">
                <a href="<?php echo get_post_type_archive_link('notice'); ?>" class="jiggasa-tab <?php echo !is_tax('notice_category') ? 'active' : ''; ?>"><?php _e( 'সকল', 'hidayah' ); ?></a>
                <?php
                $notice_cats = get_terms( array( 'taxonomy' => 'notice_category', 'hide_empty' => true ) );
                foreach ( $notice_cats as $cat ) : ?>
                    <a href="<?php echo esc_url( get_term_link( $cat ) ); ?>" class="jiggasa-tab <?php echo is_tax('notice_category', $cat->slug) ? 'active' : ''; ?>">
                        <?php echo esc_html( $cat->name ); ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- Count -->
            <?php 
            global $wp_query;
            $found_posts = $wp_query->found_posts;
            ?>
            <div class="book-archive-topbar">
              <div class="archive-count-badge">
                <span class="material-symbols-outlined"> campaign </span>
                <?php printf( __( 'মোট %sটি নোটিশ', 'hidayah' ), hidayah_en_to_bn_number($found_posts) ); ?>
              </div>
            </div>

            <!-- Pinned Notices (Sticky) -->
            <?php
            $sticky = get_option( 'sticky_posts' );
            if ( ! empty( $sticky ) && is_archive() && !is_paged() ) :
                $sticky_query = new WP_Query( array(
                    'post_type'      => 'notice',
                    'post__in'       => $sticky,
                    'ignore_sticky_posts' => 1,
                    'posts_per_page' => 3
                ) );
                if ( $sticky_query->have_posts() ) : ?>
                <div class="notice-pinned-section">
                    <h4 class="notice-pinned-label">
                      <span class="material-symbols-outlined"> push_pin </span>
                      <?php _e( 'পিন করা নোটিশ', 'hidayah' ); ?>
                    </h4>
                    <?php while ( $sticky_query->have_posts() ) : $sticky_query->the_post(); 
                        $urgency = get_post_meta( get_the_ID(), '_notice_urgency', true ) ?: 'general';
                        $attachment = get_post_meta( get_the_ID(), '_notice_attachment', true );
                        $cats = get_the_terms( get_the_ID(), 'notice_category' );
                    ?>
                        <article <?php post_class( "notice-card $urgency pinned" ); ?>>
                          <div class="notice-card-header">
                            <span class="notice-urgency-badge <?php echo esc_attr($urgency); ?>">
                              <span class="material-symbols-outlined">
                                <?php echo ($urgency === 'urgent') ? 'emergency' : (($urgency === 'important') ? 'priority_high' : 'info'); ?>
                              </span>
                              <?php 
                              if ($urgency === 'urgent') _e( 'জরুরি', 'hidayah' );
                              elseif ($urgency === 'important') _e( 'গুরুত্বপূর্ণ', 'hidayah' );
                              else _e( 'সাধারণ', 'hidayah' );
                              ?>
                            </span>
                            <?php if ( ! empty( $cats ) ) : ?>
                                <span class="notice-cat-badge"><?php echo esc_html( $cats[0]->name ); ?></span>
                            <?php endif; ?>
                            <?php if ( $attachment ) : ?>
                                <span class="notice-attach-icon">
                                  <span class="material-symbols-outlined"> attach_file </span>
                                </span>
                            <?php endif; ?>
                          </div>
                          <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                          <p><?php echo wp_trim_words( get_the_excerpt(), 20 ); ?></p>
                          <div class="notice-card-footer">
                            <span class="notice-date">
                              <span class="material-symbols-outlined"> calendar_month </span>
                              <?php echo get_the_date(); ?>
                            </span>
                            <a class="notice-read-link" href="<?php the_permalink(); ?>">
                              <?php _e( 'বিস্তারিত', 'hidayah' ); ?>
                              <span class="material-symbols-outlined"> arrow_forward </span>
                            </a>
                          </div>
                        </article>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>
            <?php endif; endif; ?>

            <!-- All Notices -->
            <div class="notice-list">
                <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); 
                    if ( is_sticky() && !is_paged() ) continue; // Skip sticky posts as they are shown above
                    $urgency = get_post_meta( get_the_ID(), '_notice_urgency', true ) ?: 'general';
                    $attachment = get_post_meta( get_the_ID(), '_notice_attachment', true );
                    $cats = get_the_terms( get_the_ID(), 'notice_category' );
                ?>
                    <article <?php post_class( "notice-card $urgency" ); ?>>
                      <div class="notice-card-header">
                        <span class="notice-urgency-badge <?php echo esc_attr($urgency); ?>">
                          <span class="material-symbols-outlined">
                            <?php echo ($urgency === 'urgent') ? 'emergency' : (($urgency === 'important') ? 'priority_high' : 'info'); ?>
                          </span>
                          <?php 
                          if ($urgency === 'urgent') _e( 'জরুরি', 'hidayah' );
                          elseif ($urgency === 'important') _e( 'গুরুত্বপূর্ণ', 'hidayah' );
                          else _e( 'সাধারণ', 'hidayah' );
                          ?>
                        </span>
                        <?php if ( ! empty( $cats ) ) : ?>
                            <span class="notice-cat-badge"><?php echo esc_html( $cats[0]->name ); ?></span>
                        <?php endif; ?>
                        <?php if ( $attachment ) : ?>
                            <span class="notice-attach-icon">
                              <span class="material-symbols-outlined"> attach_file </span>
                            </span>
                        <?php endif; ?>
                      </div>
                      <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                      <p><?php echo wp_trim_words( get_the_excerpt(), 20 ); ?></p>
                      <div class="notice-card-footer">
                        <span class="notice-date">
                          <span class="material-symbols-outlined"> calendar_month </span>
                          <?php echo get_the_date(); ?>
                        </span>
                        <a class="notice-read-link" href="<?php the_permalink(); ?>">
                          <?php _e( 'বিস্তারিত', 'hidayah' ); ?>
                          <span class="material-symbols-outlined"> arrow_forward </span>
                        </a>
                      </div>
                    </article>
                <?php endwhile; else : ?>
                    <?php get_template_part( 'template-parts/content/content', 'none' ); ?>
                <?php endif; ?>
            </div>

            <!-- Pagination -->
            <div class="archive-pagination">
                <?php hidayah_pagination(); ?>
            </div>
          </div>
        </div>

        <!-- SIDEBAR -->
        <aside class="archive-sidebar">
          <div class="col-inner">
            <!-- Search -->
            <div class="sidebar-widget">
              <h4 class="sidebar-widget-title">
                <span class="material-symbols-outlined"> search </span>
                <?php _e( 'অনুসন্ধান', 'hidayah' ); ?>
              </h4>
              <div class="sidebar-search-box">
                <form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get">
                    <input name="s" placeholder="<?php _e( 'নোটিশ খুঁজুন...', 'hidayah' ); ?>" type="text" />
                    <input type="hidden" name="post_type" value="notice" />
                    <button type="submit">
                      <span class="material-symbols-outlined"> search </span>
                    </button>
                </form>
              </div>
            </div>

            <!-- Category Filter -->
            <div class="sidebar-widget">
              <h4 class="sidebar-widget-title">
                <span class="material-symbols-outlined"> category </span>
                <?php _e( 'ক্যাটাগরি অনুযায়ী', 'hidayah' ); ?>
              </h4>
              <ul class="sidebar-filter-list">
                <?php foreach ( $notice_cats as $cat ) : ?>
                    <li>
                      <a href="<?php echo esc_url( get_term_link( $cat ) ); ?>">
                        <span><?php echo esc_html( $cat->name ); ?></span>
                        <span class="filter-count"><?php echo hidayah_en_to_bn_number($cat->count); ?></span>
                      </a>
                    </li>
                <?php endforeach; ?>
              </ul>
            </div>

            <!-- Latest Notices -->
            <div class="sidebar-widget">
              <h4 class="sidebar-widget-title">
                <span class="material-symbols-outlined"> schedule </span>
                <?php _e( 'সর্বশেষ নোটিশ', 'hidayah' ); ?>
              </h4>
              <ul class="sidebar-recent-list">
                <?php
                $latest_notices = new WP_Query( array(
                    'post_type'      => 'notice',
                    'posts_per_page' => 5
                ) );
                while ( $latest_notices->have_posts() ) : $latest_notices->the_post(); 
                    $urgency = get_post_meta( get_the_ID(), '_notice_urgency', true ) ?: 'general';
                ?>
                    <li>
                      <a href="<?php the_permalink(); ?>">
                        <span class="notice-urgency-dot <?php echo esc_attr($urgency); ?>"></span>
                        <div class="recent-info">
                          <h5><?php the_title(); ?></h5>
                          <span><?php echo get_the_date(); ?></span>
                        </div>
                      </a>
                    </li>
                <?php endwhile; wp_reset_postdata(); ?>
              </ul>
            </div>

            <!-- Download Center (List notices with attachments) -->
            <div class="sidebar-widget">
              <h4 class="sidebar-widget-title">
                <span class="material-symbols-outlined"> download </span>
                <?php _e( 'ডাউনলোড সেন্টার', 'hidayah' ); ?>
              </h4>
              <ul class="notice-download-list">
                <?php
                $download_query = new WP_Query( array(
                    'post_type'  => 'notice',
                    'meta_query' => array(
                        array(
                            'key'     => '_notice_attachment',
                            'compare' => 'EXISTS',
                        ),
                    ),
                    'posts_per_page' => 5
                ) );
                while ( $download_query->have_posts() ) : $download_query->the_post(); 
                    $attachment_id = get_post_meta( get_the_ID(), '_notice_attachment', true );
                    if ( ! $attachment_id ) continue;
                    $file_url = wp_get_attachment_url( $attachment_id );
                ?>
                    <li>
                      <a href="<?php echo esc_url($file_url); ?>" download>
                        <span class="material-symbols-outlined"> picture_as_pdf </span>
                        <div>
                          <span><?php the_title(); ?></span>
                        </div>
                        <span class="material-symbols-outlined"> download </span>
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

<?php get_footer(); ?>
