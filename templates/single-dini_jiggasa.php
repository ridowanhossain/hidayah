<?php
/**
 * Single Dini Jiggasa Template
 *
 * @package Hidayah
 */
get_header();

while ( have_posts() ) : the_post();
    // Update view count
    $views = get_post_meta( get_the_ID(), '_post_views_count', true ) ?: 0;
    update_post_meta( get_the_ID(), '_post_views_count', $views + 1 );

    $status      = get_post_meta( get_the_ID(), '_jiggasa_status', true ) ?: 'answered';
    $asker       = get_post_meta( get_the_ID(), '_jiggasa_asker_name', true ) ?: __( 'Anonymous', 'hidayah' );
    $asker_loc   = get_post_meta( get_the_ID(), '_jiggasa_asker_location', true );
    
    // Get Uttordata from taxonomy
    $uttordatas      = get_the_terms( get_the_ID(), 'uttordata' );
    $uttordata_term  = ! empty( $uttordatas ) ? $uttordatas[0] : null;
    $uttordata       = $uttordata_term ? $uttordata_term->name : '';
    $uttordata_title = $uttordata_term ? get_term_meta( $uttordata_term->term_id, 'uttordata_title', true ) : '';
    $uttordata_img   = $uttordata_term ? get_term_meta( $uttordata_term->term_id, 'uttordata_image', true ) : '';
    $uttordata_cnt   = $uttordata_term ? h_get_uttordata_ans_count( $uttordata_term->term_id ) : 0;

    $dalil       = get_post_meta( get_the_ID(), '_jiggasa_dalil', true );
    $arabic_ref  = get_post_meta( get_the_ID(), '_jiggasa_arabic_ref', true );
    $votes_up    = get_post_meta( get_the_ID(), '_jiggasa_votes_up', true ) ?: 0;
    $votes_down  = get_post_meta( get_the_ID(), '_jiggasa_votes_down', true ) ?: 0;
    
    $cats = get_the_terms( get_the_ID(), 'dini_jiggasa_cat' );
    $cat  = ! empty( $cats ) ? $cats[0] : null;
?>

<section class="archive-hero">
    <div class="archive-hero-content">
        <h2><?php _e( 'Religious Q&A', 'hidayah' ); ?></h2>
        <p><?php _e( 'Evidence-based answers on various aspects of Islamic life.', 'hidayah' ); ?></p>
    </div>
</section>

<section class="archive-section">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="archive-breadcrumb">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php _e( 'Home', 'hidayah' ); ?></a>
            <span class="material-symbols-outlined breadcrumb-sep">chevron_right</span>
            <a href="<?php echo get_post_type_archive_link( 'dini_jiggasa' ); ?>"><?php _e( 'Religious Q&A', 'hidayah' ); ?></a>
            <?php if ( $cat ) : ?>
                <span class="material-symbols-outlined breadcrumb-sep">chevron_right</span>
                <a href="<?php echo get_term_link( $cat ); ?>"><?php echo esc_html( $cat->name ); ?></a>
            <?php endif; ?>
            <span class="material-symbols-outlined breadcrumb-sep">chevron_right</span>
            <span class="breadcrumb-current"><?php echo wp_trim_words( get_the_title(), 5 ); ?>...</span>
        </nav>

        <div class="archive-layout">
            <!-- LEFT MAIN -->
            <div class="archive-main">
                <div class="col-inner">
                    <!-- Question Box -->
                    <div class="jiggasa-question-box" itemscope itemtype="https://schema.org/QAPage">
                        <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 14px">
                            <?php if ($cat) : ?>
                                <span class="jiggasa-cat-badge"><?php echo esc_html($cat->name); ?></span>
                            <?php endif; ?>
                            <span class="jiggasa-status <?php echo esc_attr($status); ?>-badge">
                                <span class="material-symbols-outlined"><?php echo $status === 'answered' ? 'check_circle' : 'schedule'; ?></span>
                                <?php echo $status === 'answered' ? __( 'Answered', 'hidayah' ) : __( 'Pending', 'hidayah' ); ?>
                            </span>
                        </div>
                        <h1 itemprop="name"><?php the_title(); ?></h1>
                        <div class="jiggasa-question-asker">
                            <span>
                                <span class="material-symbols-outlined">person</span>
                                <?php echo esc_html($asker); ?><?php echo $asker_loc ? ', ' . esc_html($asker_loc) : ''; ?>
                            </span>
                            <span>
                                <span class="material-symbols-outlined">calendar_month</span>
                                <?php echo get_the_date(); ?>
                            </span>
                        </div>
                    </div>

                    <?php if ( $status === 'pending' ) : ?>
                        <div class="jiggasa-pending-notice">
                            <span class="material-symbols-outlined">schedule</span>
                            <h3><?php _e( 'The answer to this question is in progress', 'hidayah' ); ?></h3>
                            <p><?php _e( 'Expert scholars will provide the answer here. Thank you for your patience.', 'hidayah' ); ?></p>
                        </div>
                    <?php endif; ?>

                    <!-- Answer Box -->
                    <div class="jiggasa-answer-box">
                        <div class="jiggasa-answer-label">
                            <span class="material-symbols-outlined">task_alt</span>
                            <?php _e( 'Answer', 'hidayah' ); ?>
                        </div>
                        
                        <!-- Font Size Control (Client-side JS handled) -->
                        <div class="probondho-font-ctrl">
                            <span><?php _e( 'Font Size:', 'hidayah' ); ?></span>
                            <button class="probondho-font-btn" data-size="small"><?php _e( 'Small', 'hidayah' ); ?></button>
                            <button class="probondho-font-btn active" data-size="medium"><?php _e( 'Medium', 'hidayah' ); ?></button>
                            <button class="probondho-font-btn" data-size="large"><?php _e( 'Large', 'hidayah' ); ?></button>
                        </div>

                        <div class="jiggasa-answer-body" id="jiggasaAnswerBody">
                            <?php if ( $status === 'answered' ) : ?>
                                <?php the_content(); ?>

                                <?php if ( $arabic_ref ) : ?>
                                    <div class="jiggasa-arabic-ref">
                                        <?php echo wpautop($arabic_ref); ?>
                                    </div>
                                <?php endif; ?>
                            <?php else : ?>
                                <p><?php _e( 'The answer to this question has not been provided yet. Please wait.', 'hidayah' ); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- References -->
                    <?php if ( $dalil ) : ?>
                        <div class="sidebar-widget" style="margin-bottom: 20px">
                            <h4 class="sidebar-widget-title">
                                <span class="material-symbols-outlined">menu_book</span>
                                <?php _e( 'References', 'hidayah' ); ?>
                            </h4>
                            <div class="dalil-content" style="font-size: 14px; line-height: 2; color: var(--text-dark)">
                                <?php echo wpautop($dalil); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Tags -->
                    <?php
                    $tags = get_the_terms( get_the_ID(), 'post_tag' );
                    if ( ! empty( $tags ) ) : ?>
                        <div style="margin-bottom: 20px">
                            <h4 class="sidebar-widget-title" style="margin-bottom: 10px">
                                <span class="material-symbols-outlined">tag</span>
                                <?php _e( 'Tags', 'hidayah' ); ?>
                            </h4>
                            <div class="probondho-tag-cloud">
                                <?php foreach ($tags as $tag) : ?>
                                    <a class="probondho-tag" href="<?php echo get_term_link($tag); ?>"><?php echo esc_html($tag->name); ?></a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Uttordata Sign (Bottom) -->
                    <?php if ( $status === 'answered' && $uttordata ) : ?>
                        <div class="sidebar-widget" style="background: #f9faf9; margin-bottom: 24px">
                            <div style="display: flex; align-items: center; gap: 14px">
                                <?php if ($uttordata_img) : ?>
                                    <img alt="<?php echo esc_attr($uttordata); ?>" src="<?php echo esc_url($uttordata_img); ?>" style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover; border: 2px solid var(--primary-green-dark)" />
                                <?php else : ?>
                                    <div style="width: 60px; height: 60px; border-radius: 50%; background: #eee; display: flex; align-items: center; justify-content: center;">
                                        <span class="material-symbols-outlined">person</span>
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <strong style="font-size: 15px; display: block; color: var(--text-dark)"><?php echo esc_html($uttordata); ?></strong>
                                    <span style="font-size: 12px; color: var(--text-light)"><?php echo esc_html($uttordata_title); ?></span>
                                    <?php if ($uttordata_cnt > 0) : ?>
                                        <div style="margin-top: 6px">
                                            <span class="jiggasa-uttordata-count"><?php printf( __( 'Total Answers: %s', 'hidayah' ), $uttordata_cnt ); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Voting -->
                    <div class="jiggasa-vote-row">
                        <span><?php _e( 'Was this answer helpful?', 'hidayah' ); ?></span>
                        <button class="jiggasa-vote-btn yes" data-id="<?php the_ID(); ?>" data-type="up">
                            <span class="material-symbols-outlined">thumb_up</span>
                            <?php _e( 'Yes', 'hidayah' ); ?> (<?php echo $votes_up; ?>)
                        </button>
                        <button class="jiggasa-vote-btn no" data-id="<?php the_ID(); ?>" data-type="down">
                            <span class="material-symbols-outlined">thumb_down</span>
                            <?php _e( 'No', 'hidayah' ); ?> (<?php echo $votes_down; ?>)
                        </button>
                    </div>

                    <!-- Share -->
                    <?php get_template_part( 'template-parts/content/share-section' ); ?>

                    <!-- Navigation -->
                    <div class="single-audio-nav">
                        <?php
                        $prev_post = get_previous_post();
                        if ( ! empty( $prev_post ) ) : ?>
                            <a class="single-audio-nav-btn single-audio-nav-prev" href="<?php echo get_permalink( $prev_post->ID ); ?>">
                                <span class="material-symbols-outlined">arrow_back</span>
                                <div class="single-audio-nav-text">
                                    <span class="nav-label"><?php _e( 'Previous Question', 'hidayah' ); ?></span>
                                    <span class="nav-title-sm"><?php echo wp_trim_words( get_the_title( $prev_post->ID ), 5 ); ?>...</span>
                                </div>
                            </a>
                        <?php endif; ?>

                        <?php
                        $next_post = get_next_post();
                        if ( ! empty( $next_post ) ) : ?>
                            <a class="single-audio-nav-btn single-audio-nav-next" href="<?php echo get_permalink( $next_post->ID ); ?>">
                                <div class="single-audio-nav-text">
                                    <span class="nav-label"><?php _e( 'Next Question', 'hidayah' ); ?></span>
                                    <span class="nav-title-sm"><?php echo wp_trim_words( get_the_title( $next_post->ID ), 5 ); ?>...</span>
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
                    <!-- Scholar Card -->
                    <?php if ( $status === 'answered' && $uttordata ) : ?>
                        <div class="sidebar-widget">
                            <h4 class="sidebar-widget-title">
                                <span class="material-symbols-outlined">support_agent</span>
                                <?php _e( 'Answered By', 'hidayah' ); ?>
                            </h4>
                            <div class="jiggasa-uttordata-card">
                                <?php if ($uttordata_img) : ?>
                                    <img alt="<?php echo esc_attr($uttordata); ?>" class="jiggasa-uttordata-avatar" src="<?php echo esc_url($uttordata_img); ?>" />
                                <?php else : ?>
                                    <div class="jiggasa-uttordata-avatar" style="background: #eee; display: flex; align-items: center; justify-content: center;"><span class="material-symbols-outlined">person</span></div>
                                <?php endif; ?>
                                <h4><?php echo esc_html($uttordata); ?></h4>
                                <p><?php echo esc_html($uttordata_title); ?></p>
                                <?php if ($uttordata_cnt > 0) : ?>
                                    <span class="jiggasa-uttordata-count"><?php printf( __( 'Total Answers: %s', 'hidayah' ), $uttordata_cnt ); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Related Questions -->
                    <div class="sidebar-widget">
                        <h4 class="sidebar-widget-title">
                            <span class="material-symbols-outlined">category</span>
                            <?php _e( 'Related Q&A', 'hidayah' ); ?>
                        </h4>
                        <ul class="sidebar-recent-list">
                            <?php
                            $related = new WP_Query( array(
                                'post_type'      => 'dini_jiggasa',
                                'posts_per_page' => 5,
                                'post__not_in'   => array( get_the_ID() ),
                                'tax_query'      => $cat ? array(
                                    array(
                                        'taxonomy' => 'dini_jiggasa_cat',
                                        'field'    => 'term_id',
                                        'terms'    => $cat->term_id,
                                    ),
                                ) : array(),
                            ) );
                            while ( $related->have_posts() ) : $related->the_post();
                            ?>
                                <li>
                                    <a href="<?php the_permalink(); ?>">
                                        <span class="material-symbols-outlined recent-icon">help</span>
                                        <div class="recent-info">
                                            <h5><?php the_title(); ?></h5>
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
                            <?php _e( 'Print', 'hidayah' ); ?>
                        </button>
                    </div>

                    <!-- CTA -->
                    <div class="sidebar-widget" style="background: linear-gradient(135deg, #0f6b3f, #065f3e); border-radius: 14px; padding: 20px; color: white; text-align: center">
                        <span class="material-symbols-outlined" style="font-size: 36px; color: var(--gold-accent)">help</span>
                        <h4 style="color: white; margin: 8px 0; font-size: 15px"><?php _e( 'Ask More Questions', 'hidayah' ); ?></h4>
                        <p style="font-size: 13px; margin: 0 0 12px; opacity: 0.85"><?php _e( 'Submit your religious query.', 'hidayah' ); ?></p>
                        <a class="btn btn-sm" href="<?php echo esc_url( home_url( '/ask-question' ) ); ?>" style="width: 100%; margin-bottom: 8px"><?php _e( 'Ask Question', 'hidayah' ); ?></a>
                        <a class="btn btn-sm" href="<?php echo esc_url( home_url( '/ask-question#doa' ) ); ?>" style="width: 100%; background: rgba(255, 255, 255, 0.15); border: 1px solid rgba(255, 255, 255, 0.4); color: white"><?php _e( 'Doa Request', 'hidayah' ); ?></a>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>

<?php endwhile; ?>

<?php get_footer(); ?>
