<?php
/**
 * Template Name: Admission Info
 *
 * @package Hidayah
 */
get_header();

while ( have_posts() ) : the_post();
?>

<section class="archive-hero">
    <div class="archive-hero-content">
        <h2><?php the_title(); ?></h2>
        <p><?php echo get_post_meta( get_the_ID(), '_adm_hero_text', true ) ?: __( 'দরবার শরীফ মাদ্রাসায় ভর্তির সম্পূর্ণ তথ্য, প্রক্রিয়া ও প্রয়োজনীয় নির্দেশিকা।', 'hidayah' ); ?></p>
    </div>
</section>

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
                    <!-- Admission Steps -->
                    <div class="adm-section">
                        <h2 class="adm-section-title">
                            <span class="material-symbols-outlined">route</span>
                            <?php _e( 'ভর্তির ধাপসমূহ', 'hidayah' ); ?>
                        </h2>
                        <div class="adm-steps">
                            <div class="adm-step">
                                <div class="adm-step-num">১</div>
                                <div class="adm-step-content">
                                    <h4><?php _e( 'ফর্ম সংগ্রহ', 'hidayah' ); ?></h4>
                                    <p><?php _e( 'অনলাইনে ডাউনলোড করুন অথবা সরাসরি মাদ্রাসা অফিস থেকে সংগ্রহ করুন।', 'hidayah' ); ?></p>
                                </div>
                            </div>
                            <div class="adm-step">
                                <div class="adm-step-num">২</div>
                                <div class="adm-step-content">
                                    <h4><?php _e( 'আবেদন জমা', 'hidayah' ); ?></h4>
                                    <p><?php _e( 'সকল কাগজপত্রসহ নির্ধারিত তারিখের মধ্যে আবেদন ফর্ম জমা দিন।', 'hidayah' ); ?></p>
                                </div>
                            </div>
                            <!-- ... more steps ... -->
                        </div>
                    </div>

                    <!-- Department & Seats -->
                    <div class="adm-section">
                        <h2 class="adm-section-title">
                            <span class="material-symbols-outlined">table_chart</span>
                            <?php _e( 'বিভাগ ও আসন সংখ্যা', 'hidayah' ); ?>
                        </h2>
                        <div class="adm-table-wrap">
                            <table class="notice-table">
                                <thead>
                                    <tr>
                                        <th><?php _e( 'বিভাগ/শ্রেণী', 'hidayah' ); ?></th>
                                        <th><?php _e( 'মোট আসন', 'hidayah' ); ?></th>
                                        <th><?php _e( 'ন্যূনতম যোগ্যতা', 'hidayah' ); ?></th>
                                        <th><?php _e( 'বয়সসীমা', 'hidayah' ); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr><td><?php _e( 'নুরানী বিভাগ', 'hidayah' ); ?></td><td>৬০</td><td>৫+ বছর</td><td>৫-৮ বছর</td></tr>
                                    <!-- ... rows ... -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Checklist -->
                    <div class="adm-section">
                        <h2 class="adm-section-title">
                            <span class="material-symbols-outlined">checklist</span>
                            <?php _e( 'প্রয়োজনীয় কাগজপত্র', 'hidayah' ); ?>
                        </h2>
                        <div class="adm-checklist">
                            <div class="adm-check-item"><span class="material-symbols-outlined adm-check-icon">check_box</span><span><?php _e( 'পূরণকৃত ভর্তি ফর্ম', 'hidayah' ); ?></span></div>
                            <div class="adm-check-item"><span class="material-symbols-outlined adm-check-icon">check_box</span><span><?php _e( 'জন্ম নিবন্ধন সনদ', 'hidayah' ); ?></span></div>
                        </div>
                    </div>

                    <!-- Fees -->
                    <div class="adm-section">
                        <h2 class="adm-section-title">
                            <span class="material-symbols-outlined">payments</span>
                            <?php _e( 'ফি ও বেতন তালিকা', 'hidayah' ); ?>
                        </h2>
                        <div class="adm-table-wrap">
                            <table class="notice-table">
                                <!-- Table content -->
                            </table>
                        </div>
                    </div>

                    <!-- FAQ -->
                    <div class="adm-section">
                        <h2 class="adm-section-title">
                            <span class="material-symbols-outlined">help_center</span>
                            <?php _e( 'সচরাচর জিজ্ঞাসা (FAQ)', 'hidayah' ); ?>
                        </h2>
                        <div class="adm-faq">
                            <details class="adm-faq-item">
                                <summary><?php _e( 'ভর্তির জন্য কি কোনো পূর্ব অভিজ্ঞতা প্রয়োজন?', 'hidayah' ); ?></summary>
                                <p><?php _e( 'নুরানী বিভাগে ভর্তির জন্য কোনো পূর্ব অভিজ্ঞতা প্রয়োজন নেই।', 'hidayah' ); ?></p>
                            </details>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SIDEBAR -->
            <aside class="archive-sidebar">
                <div class="col-inner">
                    <!-- Important Dates -->
                    <div class="sidebar-widget adm-dates-card">
                        <h4 class="sidebar-widget-title"><span class="material-symbols-outlined">event</span><?php _e( 'গুরুত্বপূর্ণ তারিখ', 'hidayah' ); ?></h4>
                        <!-- Date list -->
                    </div>

                    <!-- Apply CTA -->
                    <div class="sidebar-widget adm-apply-cta-widget">
                        <span class="material-symbols-outlined adm-apply-cta-icon">school</span>
                        <h4 class="adm-apply-cta-title"><?php _e( 'এখনই আবেদন করুন', 'hidayah' ); ?></h4>
                        <a class="btn adm-apply-cta-btn" href="<?php echo esc_url(home_url('/apply')); ?>"><?php _e( 'আবেদন করুন', 'hidayah' ); ?></a>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>

<?php endwhile; ?>
<?php get_footer(); ?>
