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
    $asker       = get_post_meta( get_the_ID(), '_jiggasa_asker_name', true ) ?: __( 'আনোনিমাস', 'hidayah' );
    $asker_loc   = get_post_meta( get_the_ID(), '_jiggasa_asker_location', true );
    $mufti       = get_post_meta( get_the_ID(), '_jiggasa_mufti', true );
    $mufti_title = get_post_meta( get_the_ID(), '_jiggasa_mufti_title', true );
    $mufti_img   = get_post_meta( get_the_ID(), '_jiggasa_mufti_image', true );
    $mufti_cnt   = get_post_meta( get_the_ID(), '_jiggasa_mufti_ans_count', true ) ?: 0;
    $dalil       = get_post_meta( get_the_ID(), '_jiggasa_dalil', true );
    $arabic_ref  = get_post_meta( get_the_ID(), '_jiggasa_arabic_ref', true );
    $votes_up    = get_post_meta( get_the_ID(), '_jiggasa_votes_up', true ) ?: 0;
    $votes_down  = get_post_meta( get_the_ID(), '_jiggasa_votes_down', true ) ?: 0;
    
    $cats = get_the_terms( get_the_ID(), 'dini_jiggasa_cat' );
    $cat  = ! empty( $cats ) ? $cats[0] : null;
?>

<section class="archive-hero">
    <div class="archive-hero-content">
        <h2><?php _e( 'দ্বীনি জিজ্ঞাসা', 'hidayah' ); ?></h2>
        <p><?php _e( 'ইসলামী জীবনের বিভিন্ন বিষয়ে দলীলভিত্তিক উত্তর।', 'hidayah' ); ?></p>
    </div>
</section>

<section class="archive-section">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="archive-breadcrumb">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php _e( 'হোম', 'hidayah' ); ?></a>
            <span class="material-symbols-outlined breadcrumb-sep">chevron_right</span>
            <a href="<?php echo get_post_type_archive_link( 'dini_jiggasa' ); ?>"><?php _e( 'দ্বীনি জিজ্ঞাসা', 'hidayah' ); ?></a>
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
                    <div class="jiggasa-question-box">
                        <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 14px">
                            <?php if ($cat) : ?>
                                <span class="jiggasa-cat-badge"><?php echo esc_html($cat->name); ?></span>
                            <?php endif; ?>
                            <span class="jiggasa-status <?php echo esc_attr($status); ?>-badge">
                                <span class="material-symbols-outlined"><?php echo $status === 'answered' ? 'check_circle' : 'schedule'; ?></span>
                                <?php echo $status === 'answered' ? __( 'উত্তরিত', 'hidayah' ) : __( 'অপেক্ষমাণ', 'hidayah' ); ?>
                            </span>
                        </div>
                        <h1><?php the_title(); ?></h1>
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

                    <!-- Answer Box -->
                    <div class="jiggasa-answer-box">
                        <div class="jiggasa-answer-label">
                            <span class="material-symbols-outlined">task_alt</span>
                            <?php _e( 'উত্তর', 'hidayah' ); ?>
                        </div>
                        
                        <!-- Font Size Control (Client-side JS handled) -->
                        <div class="probondho-font-ctrl">
                            <span><?php _e( 'ফন্ট সাইজ:', 'hidayah' ); ?></span>
                            <button class="probondho-font-btn" data-size="small"><?php _e( 'ছোট', 'hidayah' ); ?></button>
                            <button class="probondho-font-btn active" data-size="medium"><?php _e( 'মাঝারি', 'hidayah' ); ?></button>
                            <button class="probondho-font-btn" data-size="large"><?php _e( 'বড়', 'hidayah' ); ?></button>
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
                                <p><?php _e( 'এই প্রশ্নের উত্তর এখনো প্রদান করা হয়নি। অনুগ্রহ করে অপেক্ষা করুন।', 'hidayah' ); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- References -->
                    <?php if ( $dalil ) : ?>
                        <div class="sidebar-widget" style="margin-bottom: 20px">
                            <h4 class="sidebar-widget-title">
                                <span class="material-symbols-outlined">menu_book</span>
                                <?php _e( 'তথ্যসূত্র', 'hidayah' ); ?>
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
                                <?php _e( 'ট্যাগসমূহ', 'hidayah' ); ?>
                            </h4>
                            <div class="probondho-tag-cloud">
                                <?php foreach ($tags as $tag) : ?>
                                    <a class="probondho-tag" href="<?php echo get_term_link($tag); ?>"><?php echo esc_html($tag->name); ?></a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Mufti Sign (Bottom) -->
                    <?php if ( $status === 'answered' && $mufti ) : ?>
                        <div class="sidebar-widget" style="background: #f9faf9; margin-bottom: 24px">
                            <div style="display: flex; align-items: center; gap: 14px">
                                <?php if ($mufti_img) : ?>
                                    <img alt="<?php echo esc_attr($mufti); ?>" src="<?php echo esc_url($mufti_img); ?>" style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover; border: 2px solid var(--primary-green-dark)" />
                                <?php else : ?>
                                    <div style="width: 60px; height: 60px; border-radius: 50%; background: #eee; display: flex; align-items: center; justify-content: center;">
                                        <span class="material-symbols-outlined">person</span>
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <strong style="font-size: 15px; display: block; color: var(--text-dark)"><?php echo esc_html($mufti); ?></strong>
                                    <span style="font-size: 12px; color: var(--text-light)"><?php echo esc_html($mufti_title); ?></span>
                                    <?php if ($mufti_cnt) : ?>
                                        <div style="margin-top: 6px">
                                            <span class="jiggasa-mufti-count"><?php printf( __( 'মোট উত্তর: %s', 'hidayah' ), hidayah_en_to_bn_number($mufti_cnt) ); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Voting -->
                    <div class="jiggasa-vote-row">
                        <span><?php _e( 'এই উত্তর কি সহায়ক ছিল?', 'hidayah' ); ?></span>
                        <button class="jiggasa-vote-btn yes" data-id="<?php the_ID(); ?>" data-type="up">
                            <span class="material-symbols-outlined">thumb_up</span>
                            <?php _e( 'হ্যাঁ', 'hidayah' ); ?> (<?php echo hidayah_en_to_bn_number($votes_up); ?>)
                        </button>
                        <button class="jiggasa-vote-btn no" data-id="<?php the_ID(); ?>" data-type="down">
                            <span class="material-symbols-outlined">thumb_down</span>
                            <?php _e( 'না', 'hidayah' ); ?> (<?php echo hidayah_en_to_bn_number($votes_down); ?>)
                        </button>
                    </div>

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
                                    <span class="nav-label"><?php _e( 'পূর্ববর্তী প্রশ্ন', 'hidayah' ); ?></span>
                                    <span class="nav-title-sm"><?php echo wp_trim_words( get_the_title( $prev_post->ID ), 5 ); ?>...</span>
                                </div>
                            </a>
                        <?php endif; ?>

                        <?php
                        $next_post = get_next_post();
                        if ( ! empty( $next_post ) ) : ?>
                            <a class="single-audio-nav-btn single-audio-nav-next" href="<?php echo get_permalink( $next_post->ID ); ?>">
                                <div class="single-audio-nav-text">
                                    <span class="nav-label"><?php _e( 'পরবর্তী প্রশ্ন', 'hidayah' ); ?></span>
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
                    <?php if ( $status === 'answered' && $mufti ) : ?>
                        <div class="sidebar-widget">
                            <h4 class="sidebar-widget-title">
                                <span class="material-symbols-outlined">support_agent</span>
                                <?php _e( 'উত্তরদাতা মুফতী', 'hidayah' ); ?>
                            </h4>
                            <div class="jiggasa-mufti-card">
                                <?php if ($mufti_img) : ?>
                                    <img alt="<?php echo esc_attr($mufti); ?>" class="jiggasa-mufti-avatar" src="<?php echo esc_url($mufti_img); ?>" />
                                <?php else : ?>
                                    <div class="jiggasa-mufti-avatar" style="background: #eee; display: flex; align-items: center; justify-content: center;"><span class="material-symbols-outlined">person</span></div>
                                <?php endif; ?>
                                <h4><?php echo esc_html($mufti); ?></h4>
                                <p><?php echo esc_html($mufti_title); ?></p>
                                <span class="jiggasa-mufti-count"><?php printf( __( 'মোট উত্তর: %s', 'hidayah' ), hidayah_en_to_bn_number($mufti_cnt) ); ?></span>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Related Questions -->
                    <div class="sidebar-widget">
                        <h4 class="sidebar-widget-title">
                            <span class="material-symbols-outlined">category</span>
                            <?php _e( 'সম্পর্কিত প্রশ্নোত্তর', 'hidayah' ); ?>
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
                            <?php _e( 'প্রিন্ট করুন', 'hidayah' ); ?>
                        </button>
                    </div>

                    <!-- CTA -->
                    <div class="sidebar-widget" style="background: linear-gradient(135deg, #0f6b3f, #065f3e); border-radius: 14px; padding: 20px; color: white; text-align: center">
                        <span class="material-symbols-outlined" style="font-size: 36px; color: var(--gold-accent)">help</span>
                        <h4 style="color: white; margin: 8px 0; font-size: 15px"><?php _e( 'আরো প্রশ্ন করুন', 'hidayah' ); ?></h4>
                        <p style="font-size: 13px; margin: 0 0 12px; opacity: 0.85"><?php _e( 'আপনার দ্বীনি প্রশ্ন পাঠান।', 'hidayah' ); ?></p>
                        <a class="btn btn-sm" href="<?php echo esc_url( home_url( '/ask-question' ) ); ?>" style="width: 100%; margin-bottom: 8px"><?php _e( 'প্রশ্ন করুন', 'hidayah' ); ?></a>
                        <a class="btn btn-sm" href="<?php echo esc_url( home_url( '/ask-question#doa' ) ); ?>" style="width: 100%; background: rgba(255, 255, 255, 0.15); border: 1px solid rgba(255, 255, 255, 0.4); color: white"><?php _e( 'দোয়ার আবেদন', 'hidayah' ); ?></a>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>

<?php endwhile; ?>

<?php get_footer(); ?>
