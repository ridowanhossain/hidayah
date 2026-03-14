<?php
/**
 * Template Name: Darbar Intro
 *
 * @package Hidayah
 */
get_header();

while ( have_posts() ) : the_post();
?>

<!-- Heritage Banner -->
<div class="darbar-heritage-banner">
    <?php if ( has_post_thumbnail() ) : ?>
        <?php the_post_thumbnail( 'full' ); ?>
    <?php else : ?>
        <img alt="<?php the_title(); ?>" src="<?php echo get_template_directory_uri(); ?>/assets/images/darbar-banner.jpg" />
    <?php endif; ?>
    <div class="darbar-heritage-overlay">
        <h1><?php the_title(); ?></h1>
        <p><?php echo get_post_meta( get_the_ID(), '_darbar_founded', true ) ?: __( 'প্রতিষ্ঠাকাল: ১৩৯৫ হিজরি / ১৯৭৫ খ্রিস্টাব্দ', 'hidayah' ); ?></p>
    </div>
</div>

<section class="archive-section">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="archive-breadcrumb">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php _e( 'হোম', 'hidayah' ); ?></a>
            <span class="material-symbols-outlined breadcrumb-sep">chevron_right</span>
            <span class="breadcrumb-current"><?php the_title(); ?></span>
        </nav>

        <div class="archive-layout">
            <!-- LEFT MAIN -->
            <div class="archive-main">
                <div class="col-inner">
                    <!-- Overview -->
                    <div class="darbar-section">
                        <div class="darbar-lead">
                            <?php the_content(); ?>
                        </div>
                    </div>

                    <!-- Share Section -->
                    <div class="single-audio-share-wrap">
                        <h3 class="section-heading-sm">
                            <span class="material-symbols-outlined">share</span>
                            <?php _e( 'শেয়ার ও সোশ্যাল মিডিয়া', 'hidayah' ); ?>
                        </h3>
                        <div class="main-share-buttons">
                            <a class="share-btn share-facebook" href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>" target="_blank">
                                <span class="material-symbols-outlined">thumb_up</span>
                                Facebook
                            </a>
                            <a class="share-btn share-whatsapp" href="https://api.whatsapp.com/send?text=<?php the_permalink(); ?>" target="_blank">
                                <span class="material-symbols-outlined">chat</span>
                                WhatsApp
                            </a>
                            <button class="share-btn share-copy" id="copyLinkBtn" onclick="navigator.clipboard.writeText(window.location.href); alert('<?php _e( 'লিঙ্ক কপি হয়েছে', 'hidayah' ); ?>');">
                                <span class="material-symbols-outlined">link</span>
                                <?php _e( 'লিঙ্ক কপি করুন', 'hidayah' ); ?>
                            </button>
                        </div>
                    </div>

                    <!-- Timeline (Placeholder for dynamic content or logic) -->
                    <div class="darbar-section">
                        <h2 class="darbar-section-title">
                            <span class="material-symbols-outlined">timeline</span>
                            <?php _e( 'ইতিহাসের মাইলস্টোন', 'hidayah' ); ?>
                        </h2>
                        <div class="darbar-timeline">
                            <!-- Static content for now as it's a specific page -->
                            <div class="darbar-tl-item">
                                <div class="darbar-tl-year">১৯৭৫</div>
                                <div class="darbar-tl-content">
                                    <h4><?php _e( 'দরবার শরীফ প্রতিষ্ঠা', 'hidayah' ); ?></h4>
                                    <p><?php _e( 'হযরত মুর্শিদ ক্বিবলা (রহ.) কর্তৃক দরবার শরীফ প্রতিষ্ঠিত হয়।', 'hidayah' ); ?></p>
                                </div>
                            </div>
                            <!-- ... more items ... -->
                        </div>
                    </div>

                    <!-- Mission & Vision -->
                    <div class="darbar-section">
                        <h2 class="darbar-section-title">
                            <span class="material-symbols-outlined">flag</span>
                            <?php _e( 'মিশন ও ভিশন', 'hidayah' ); ?>
                        </h2>
                        <div class="darbar-mv-grid">
                            <div class="darbar-mv-card mission">
                                <span class="material-symbols-outlined">emoji_objects</span>
                                <h3><?php _e( 'মিশন', 'hidayah' ); ?></h3>
                                <p><?php echo h_opt('mission_text', 'কুরআন ও সুন্নাহর আলোকে ইসলামী জ্ঞান প্রচার...'); ?></p>
                            </div>
                            <div class="darbar-mv-card vision">
                                <span class="material-symbols-outlined">visibility</span>
                                <h3><?php _e( 'ভিশন', 'hidayah' ); ?></h3>
                                <p><?php echo h_opt('vision_text', 'একটি আদর্শ ইসলামী সমাজ গঠনে নেতৃত্ব দেওয়া...'); ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Murshid Profile -->
                    <div class="darbar-section darbar-murshid">
                        <?php 
                        $murshid_img = h_opt('murshid_image');
                        $murshid_name = h_opt('murshid_name', 'হযরত মাওলানা সিদ্দীক আহমাদ (দা.বা.)');
                        $murshid_bio = h_opt('murshid_bio', 'হযরত মাওলানা সাহেব (দা.বা.) দেশের একজন বিশিষ্ট আলেমে দ্বীন...');
                        ?>
                        <div class="darbar-murshid-img">
                            <img alt="<?php echo esc_attr($murshid_name); ?>" src="<?php echo esc_url($murshid_img ?: 'https://i.pravatar.cc/220?img=60'); ?>" />
                        </div>
                        <div class="darbar-murshid-info">
                            <span class="probondho-cat-badge darbar-profile-badge"><?php _e( 'বর্তমান পীর সাহেব', 'hidayah' ); ?></span>
                            <h2><?php echo esc_html($murshid_name); ?></h2>
                            <p class="darbar-profile-bio"><?php echo esc_html($murshid_bio); ?></p>
                            <blockquote class="darbar-quote-box"><?php echo esc_html(h_opt('murshid_quote', '"আল্লাহর পথে চলাই মানুষের একমাত্র মুক্তির পথ।"')); ?></blockquote>
                        </div>
                    </div>

                    <!-- Activities Table -->
                    <div class="darbar-section">
                        <h2 class="darbar-section-title">
                            <span class="material-symbols-outlined">schedule</span>
                            <?php _e( 'দৈনিক ও সাপ্তাহিক কার্যক্রম', 'hidayah' ); ?>
                        </h2>
                        <div class="adm-table-wrap">
                            <table class="notice-table">
                                <thead>
                                    <tr>
                                        <th><?php _e( 'সময়', 'hidayah' ); ?></th>
                                        <th><?php _e( 'কার্যক্রম', 'hidayah' ); ?></th>
                                        <th><?php _e( 'বিবরণ', 'hidayah' ); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Static or Dynamic Rows -->
                                    <tr><td><?php _e( 'ফজরের পর', 'hidayah' ); ?></td><td><?php _e( 'তিলাওয়াত ও যিকির', 'hidayah' ); ?></td><td><?php _e( 'সকালের নিয়মিত আমল', 'hidayah' ); ?></td></tr>
                                    <!-- More rows -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Donation -->
                    <div class="darbar-section darbar-donation">
                        <span class="material-symbols-outlined darbar-donation-icon-main">favorite</span>
                        <h2><?php _e( 'দান ও সহযোগিতা', 'hidayah' ); ?></h2>
                        <p><?php _e( 'দরবার শরীফের কার্যক্রম চালিয়ে যেতে আপনার আর্থিক সহযোগিতা অত্যন্ত প্রয়োজন।', 'hidayah' ); ?></p>
                        <a class="btn darbar-donation-action-btn" href="<?php echo esc_url(home_url('/donation')); ?>">
                            <span class="material-symbols-outlined">volunteer_activism</span>
                            <?php _e( 'হাদিয়া প্রদান করুন', 'hidayah' ); ?>
                        </a>
                    </div>
                </div>
            </div>

            <!-- SIDEBAR -->
            <aside class="archive-sidebar">
                <div class="col-inner">
                    <!-- Quick Info -->
                    <div class="sidebar-widget">
                        <h4 class="sidebar-widget-title">
                            <span class="material-symbols-outlined">info</span>
                            <?php _e( 'দ্রুত তথ্য', 'hidayah' ); ?>
                        </h4>
                        <ul class="sidebar-list-unstyled">
                            <li class="sidebar-list-item-flex">
                                <span class="material-symbols-outlined sidebar-list-icon-sm">history</span>
                                <div><strong><?php _e( 'প্রতিষ্ঠাকাল', 'hidayah' ); ?></strong><span>১৯৭৫ খ্রিস্টাব্দ</span></div>
                            </li>
                            <li class="sidebar-list-item-flex">
                                <span class="material-symbols-outlined sidebar-list-icon-sm">location_on</span>
                                <div><strong><?php _e( 'অবস্থান', 'hidayah' ); ?></strong><span>মিরপুর-১, ঢাকা</span></div>
                            </li>
                        </ul>
                    </div>

                    <!-- Recent Notices -->
                    <div class="sidebar-widget">
                        <h4 class="sidebar-widget-title">
                            <span class="material-symbols-outlined">campaign</span>
                            <?php _e( 'সাম্প্রতিক নোটিশ', 'hidayah' ); ?>
                        </h4>
                        <ul class="sidebar-recent-list">
                            <?php
                            $notices = new WP_Query( array( 'post_type' => 'notice', 'posts_per_page' => 3 ) );
                            while ( $notices->have_posts() ) : $notices->the_post();
                            ?>
                                <li>
                                    <a href="<?php the_permalink(); ?>">
                                        <span class="notice-urgency-dot general"></span>
                                        <div class="recent-info">
                                            <h5><?php the_title(); ?></h5>
                                            <span><?php echo get_the_date(); ?></span>
                                        </div>
                                    </a>
                                </li>
                            <?php endwhile; wp_reset_postdata(); ?>
                        </ul>
                    </div>

                    <!-- Map -->
                    <div class="sidebar-widget">
                        <h4 class="sidebar-widget-title">
                            <span class="material-symbols-outlined">map</span>
                            <?php _e( 'দর্শন করুন', 'hidayah' ); ?>
                        </h4>
                        <div class="adm-map-placeholder">
                            <span class="material-symbols-outlined">location_on</span>
                            <p><?php echo h_opt('darbar_address', 'মিরপুর-১, ঢাকা-১২১৬'); ?></p>
                            <a class="btn btn-sm mt-8" href="https://maps.google.com" target="_blank"><?php _e( 'Google Maps এ দেখুন', 'hidayah' ); ?></a>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>

<?php endwhile; ?>
<?php get_footer(); ?>
