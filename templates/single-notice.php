<?php
/**
 * Single Notice Template
 *
 * @package Hidayah
 */
get_header();
?>

<?php while ( have_posts() ) : the_post(); 
    $urgency         = get_post_meta( get_the_ID(), '_notice_urgency', true ) ?: 'general';
    $attachment_id   = get_post_meta( get_the_ID(), '_notice_attachment', true );
    $expiry_date     = get_post_meta( get_the_ID(), '_notice_expiry_date', true );
    $important_dates = get_post_meta( get_the_ID(), '_notice_important_dates', true ); // Assuming it's a formatted string or array
    $cats            = get_the_terms( get_the_ID(), 'notice_category' );

    $file_url = '';
    if ( ! empty( $attachment_id ) ) {
        if ( is_numeric( $attachment_id ) ) {
            $file_url = wp_get_attachment_url( $attachment_id );
        } else {
            $file_url = $attachment_id;
            // Try to recover ID from URL for title and size
            $attachment_id = attachment_url_to_postid( $file_url );
        }
    }

    $file_name        = $attachment_id ? get_the_title( $attachment_id ) : '';
    $attached_path    = $attachment_id ? get_attached_file( $attachment_id ) : '';
    $file_size        = $attached_path && file_exists( $attached_path ) ? size_format( filesize( $attached_path ) ) : '';
    $file_ext         = $attached_path ? strtoupper( pathinfo( $attached_path, PATHINFO_EXTENSION ) ) : '';
?>

    <section class="archive-hero">
      <div class="archive-hero-content">
        <h2><?php _e( 'Notices & Announcements', 'hidayah' ); ?></h2>
        <p><?php _e( 'Official notices and urgent announcements.', 'hidayah' ); ?></p>
      </div>
    </section>

    <section class="archive-section">
      <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="archive-breadcrumb">
          <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php _e( 'Home', 'hidayah' ); ?></a>
          <span class="material-symbols-outlined breadcrumb-sep">chevron_right</span>
          <a href="<?php echo get_post_type_archive_link( 'notice' ); ?>"><?php _e( 'Notices', 'hidayah' ); ?></a>
          <?php if ( ! empty( $cats ) ) : ?>
              <span class="material-symbols-outlined breadcrumb-sep">chevron_right</span>
              <a href="<?php echo get_term_link( $cats[0] ); ?>"><?php echo esc_html( $cats[0]->name ); ?></a>
          <?php endif; ?>
          <span class="material-symbols-outlined breadcrumb-sep">chevron_right</span>
          <span class="breadcrumb-current"><?php the_title(); ?></span>
        </nav>

        <div class="archive-layout">
          <!-- LEFT MAIN -->
          <div class="archive-main">
            <div class="col-inner">
              <!-- Notice Header -->
              <div class="notice-single-header" itemscope itemtype="https://schema.org/SpecialAnnouncement">
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 14px; flex-wrap: wrap">
                  <span class="notice-urgency-badge <?php echo esc_attr($urgency); ?>">
                    <span class="material-symbols-outlined">
                        <?php echo ($urgency === 'urgent') ? 'emergency' : (($urgency === 'important') ? 'priority_high' : 'info'); ?>
                    </span>
                    <?php 
                    if ($urgency === 'urgent') _e( 'Urgent', 'hidayah' );
                    elseif ($urgency === 'important') _e( 'Important', 'hidayah' );
                    else _e( 'General', 'hidayah' );
                    ?>
                  </span>
                  <?php if ( ! empty( $cats ) ) : ?>
                      <span class="notice-cat-badge"><?php echo esc_html( $cats[0]->name ); ?></span>
                  <?php endif; ?>
                  <?php if ( $attachment_id ) : ?>
                      <span class="notice-attach-icon">
                        <span class="material-symbols-outlined">attach_file</span>
                        <?php _e( 'Attachment', 'hidayah' ); ?>
                      </span>
                  <?php endif; ?>
                </div>
                <h1 class="notice-single-title" itemprop="name"><?php the_title(); ?></h1>
                <div class="notice-single-meta">
                  <span>
                    <span class="material-symbols-outlined">calendar_month</span>
                    <?php printf( __( 'Published: %s', 'hidayah' ), get_the_date() ); ?>
                  </span>
                  <span>
                    <span class="material-symbols-outlined">update</span>
                    <?php printf( __( 'Updated: %s', 'hidayah' ), get_the_modified_date() ); ?>
                  </span>
                  <?php if ( $expiry_date ) : ?>
                  <span>
                    <span class="material-symbols-outlined">event_available</span>
                    <?php printf( __( 'Expires: %s', 'hidayah' ), esc_html($expiry_date) ); ?>
                  </span>
                  <?php endif; ?>
                </div>
                <?php if ( $expiry_date ) :
                    $expired = strtotime( $expiry_date ) < time();
                ?>
                    <div class="notice-expiry-warning <?php echo $expired ? 'expired' : 'active'; ?>">
                        <span class="material-symbols-outlined">
                            <?php echo $expired ? 'event_busy' : 'event_available'; ?>
                        </span>
                        <?php if ( $expired ) : ?>
                            <strong><?php _e( 'This notice has expired.', 'hidayah' ); ?></strong>
                        <?php else : ?>
                            <strong><?php printf( __( 'Expires: %s', 'hidayah' ), esc_html( $expiry_date ) ); ?></strong>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
              </div>

              <!-- Notice Body -->
              <div class="notice-single-body">
                <?php the_content(); ?>
              </div>

              <!-- Important Dates -->
              <?php
              if ( ! empty( $important_dates ) ) :
                  $dates = explode( "\n", str_replace( "\r", "", $important_dates ) );
                  $dates = array_filter( array_map( 'trim', $dates ) );
                  if ( ! empty( $dates ) ) : ?>
                      <div class="sb-section">
                          <h3 class="sb-section-title">
                            <span class="material-symbols-outlined" style="vertical-align: middle; margin-right: 8px;">info</span>
                            <?php _e( 'Important Information', 'hidayah' ); ?>
                          </h3>
                          <ol class="mhd-toc-list">
                              <?php foreach ( $dates as $date ) : ?>
                                  <li style="justify-content: flex-start; text-align: left;"><?php echo esc_html( $date ); ?></li>
                              <?php endforeach; ?>
                          </ol>
                      </div>
                  <?php endif;
              endif; ?>

              <!-- Attachments -->
              <?php if ( $attachment_id ) : ?>
                  <div class="notice-attachments">
                    <h3>
                      <span class="material-symbols-outlined">attach_file</span>
                      <?php _e( 'Attachments', 'hidayah' ); ?>
                    </h3>
                    <div class="notice-attach-list">
                      <a class="notice-attach-item" href="<?php echo esc_url($file_url); ?>" download>
                        <span class="material-symbols-outlined notice-attach-icon-lg">picture_as_pdf</span>
                        <div class="notice-attach-info">
                          <strong><?php echo esc_html($file_name); ?></strong>
                          <span><?php echo $file_ext; ?> · <?php echo $file_size; ?></span>
                        </div>
                        <span class="material-symbols-outlined">download</span>
                      </a>
                    </div>
                  </div>
              <?php endif; ?>

              <!-- Tags -->
              <?php if ( has_tag() ) : ?>
              <div style="margin: 20px 0">
                <div class="probondho-tag-cloud">
                  <?php the_tags('', '', ''); ?>
                </div>
              </div>
              <?php endif; ?>

              <!-- Prev/Next Nav -->
              <div class="single-audio-nav">
                <?php
                $prev_post = get_previous_post();
                if ( ! empty( $prev_post ) ) : ?>
                    <a class="single-audio-nav-btn single-audio-nav-prev" href="<?php echo get_permalink( $prev_post->ID ); ?>">
                      <span class="material-symbols-outlined">arrow_back</span>
                      <div class="single-audio-nav-text">
                        <span class="nav-label"><?php _e( 'Previous Notice', 'hidayah' ); ?></span>
                        <span class="nav-title-sm"><?php echo get_the_title( $prev_post->ID ); ?></span>
                      </div>
                    </a>
                <?php endif; ?>

                <?php
                $next_post = get_next_post();
                if ( ! empty( $next_post ) ) : ?>
                    <a class="single-audio-nav-btn single-audio-nav-next" href="<?php echo get_permalink( $next_post->ID ); ?>">
                      <div class="single-audio-nav-text">
                        <span class="nav-label"><?php _e( 'Next Notice', 'hidayah' ); ?></span>
                        <span class="nav-title-sm"><?php echo get_the_title( $next_post->ID ); ?></span>
                      </div>
                      <span class="material-symbols-outlined">arrow_forward</span>
                    </a>
                <?php endif; ?>
              </div>

              <!-- Related Notices -->
              <?php if ( ! empty( $cats ) ) : ?>
              <div>
                <h3 style="font-size: 18px; font-weight: 700; color: var(--text-dark); margin-bottom: 14px"><?php _e( 'Related Notices', 'hidayah' ); ?></h3>
                <div class="notice-list" style="gap: 12px">
                  <?php
                  $related = new WP_Query( array(
                      'post_type'      => 'notice',
                      'posts_per_page' => 2,
                      'post__not_in'   => array( get_the_ID() ),
                      'tax_query'      => array(
                          array(
                              'taxonomy' => 'notice_category',
                              'field'    => 'term_id',
                              'terms'    => $cats[0]->term_id,
                          ),
                      ),
                  ) );
                  if ( $related->have_posts() ) : while ( $related->have_posts() ) : $related->the_post(); 
                    $rel_urgency = get_post_meta( get_the_ID(), '_notice_urgency', true ) ?: 'general';
                  ?>
                      <article class="notice-card <?php echo esc_attr($rel_urgency); ?>" style="padding: 14px">
                        <div class="notice-card-header">
                          <span class="notice-urgency-badge <?php echo esc_attr($rel_urgency); ?>">
                            <span class="material-symbols-outlined">
                                <?php echo ($rel_urgency === 'urgent') ? 'emergency' : (($rel_urgency === 'important') ? 'priority_high' : 'info'); ?>
                            </span>
                            <?php 
                            if ($rel_urgency === 'urgent') _e( 'Urgent', 'hidayah' );
                            elseif ($rel_urgency === 'important') _e( 'Important', 'hidayah' );
                            else _e( 'General', 'hidayah' );
                            ?>
                          </span>
                        </div>
                        <h3 style="font-size: 14px"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                        <div class="notice-card-footer">
                          <span class="notice-date"><?php echo get_the_date(); ?></span>
                          <a class="notice-read-link" href="<?php the_permalink(); ?>">
                            <?php _e( 'Details', 'hidayah' ); ?>
                            <span class="material-symbols-outlined">arrow_forward</span>
                          </a>
                        </div>
                      </article>
                  <?php endwhile; wp_reset_postdata(); endif; ?>
                </div>
              </div>
              <?php endif; ?>

              <!-- Share Buttons -->
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
              <!-- Notice Info -->
              <div class="sidebar-widget">
                <h4 class="sidebar-widget-title">
                  <span class="material-symbols-outlined">info</span>
                  <?php _e( 'Notice Information', 'hidayah' ); ?>
                </h4>
                <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 10px">
                  <li style="display: flex; gap: 10px; font-size: 13px">
                    <span class="material-symbols-outlined" style="font-size: 17px; color: var(--primary-green-dark); flex-shrink: 0">calendar_month</span>
                    <div>
                      <strong style="display: block"><?php _e( 'Publish Date', 'hidayah' ); ?></strong>
                      <span style="color: var(--text-light)"><?php echo get_the_date(); ?></span>
                    </div>
                  </li>
                  <li style="display: flex; gap: 10px; font-size: 13px">
                    <span class="material-symbols-outlined" style="font-size: 17px; color: var(--primary-green-dark); flex-shrink: 0">update</span>
                    <div>
                      <strong style="display: block"><?php _e( 'Last Updated', 'hidayah' ); ?></strong>
                      <span style="color: var(--text-light)"><?php echo get_the_modified_date(); ?></span>
                    </div>
                  </li>
                  <?php if ( ! empty( $cats ) ) : ?>
                  <li style="display: flex; gap: 10px; font-size: 13px">
                    <span class="material-symbols-outlined" style="font-size: 17px; color: var(--primary-green-dark); flex-shrink: 0">category</span>
                    <div>
                      <strong style="display: block"><?php _e( 'Category', 'hidayah' ); ?></strong>
                      <span style="color: var(--text-light)"><?php echo esc_html($cats[0]->name); ?></span>
                    </div>
                  </li>
                  <?php endif; ?>
                  <?php if ( $expiry_date ) : ?>
                  <li style="display: flex; gap: 10px; font-size: 13px">
                    <span class="material-symbols-outlined" style="font-size: 17px; color: #ef4444; flex-shrink: 0">event_available</span>
                    <div>
                      <strong style="display: block"><?php _e( 'Expires', 'hidayah' ); ?></strong>
                      <span style="color: #ef4444; font-weight: 600"><?php echo esc_html($expiry_date); ?></span>
                    </div>
                  </li>
                  <?php endif; ?>
                </ul>
              </div>

              <!-- Attachment Download (Sidebar) -->
              <?php if ( $attachment_id ) : ?>
                  <div class="sidebar-widget">
                    <h4 class="sidebar-widget-title">
                      <span class="material-symbols-outlined">download</span>
                      <?php _e( 'Download Attachment', 'hidayah' ); ?>
                    </h4>
                    <ul class="notice-download-list">
                      <li>
                        <a href="<?php echo esc_url($file_url); ?>" download>
                          <span class="material-symbols-outlined">picture_as_pdf</span>
                          <div>
                            <span><?php echo esc_html($file_name); ?></span>
                            <small><?php echo $file_ext; ?> · <?php echo $file_size; ?></small>
                          </div>
                          <span class="material-symbols-outlined">download</span>
                        </a>
                      </li>
                    </ul>
                  </div>
              <?php endif; ?>

              <!-- Recent Notices (Sidebar) -->
              <div class="sidebar-widget">
                <h4 class="sidebar-widget-title">
                  <span class="material-symbols-outlined">campaign</span>
                  <?php _e( 'Recent Notices', 'hidayah' ); ?>
                </h4>
                <ul class="sidebar-recent-list">
                  <?php
                  $latest = new WP_Query( array(
                      'post_type'      => 'notice',
                      'posts_per_page' => 4,
                      'post__not_in'   => array( get_the_ID() )
                  ) );
                  while ( $latest->have_posts() ) : $latest->the_post(); 
                    $lat_urgency = get_post_meta( get_the_ID(), '_notice_urgency', true ) ?: 'general';
                  ?>
                    <li>
                      <a href="<?php the_permalink(); ?>">
                        <span class="notice-urgency-dot <?php echo esc_attr($lat_urgency); ?>"></span>
                        <div class="recent-info">
                          <h5><?php the_title(); ?></h5>
                          <span><?php echo get_the_date(); ?></span>
                        </div>
                      </a>
                    </li>
                  <?php endwhile; wp_reset_postdata(); ?>
                </ul>
              </div>

              <!-- Print Button -->
              <div class="sidebar-widget">
                <button class="btn" onclick="window.print()" style="width: 100%; display: flex; align-items: center; gap: 8px; justify-content: center">
                  <span class="material-symbols-outlined">print</span>
                  <?php _e( 'Print Now', 'hidayah' ); ?>
                </button>
              </div>

              <!-- Back Link -->
              <div class="sidebar-widget" style="text-align: center">
                <a class="probondho-read-link" href="<?php echo get_post_type_archive_link( 'notice' ); ?>">
                  <span class="material-symbols-outlined">arrow_back</span>
                  <?php _e( 'View All Notices', 'hidayah' ); ?>
                </a>
              </div>
            </div>
          </aside>
        </div>
      </div>
    </section>

<?php endwhile; ?>

<?php get_footer(); ?>
