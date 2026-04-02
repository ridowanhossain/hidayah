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
        <p><?php echo get_post_meta( get_the_ID(), '_adm_hero_text', true ) ?: __( 'Complete information, process, and necessary guidelines for admission to Darbar Sharif Madrasa.', 'hidayah' ); ?></p>
    </div>
</section>

<section class="archive-section">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="archive-breadcrumb">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php _e( 'Home', 'hidayah' ); ?></a>
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
                            <?php _e( 'Admission Steps', 'hidayah' ); ?>
                        </h2>
                        <div class="adm-steps">
                            <div class="adm-step">
                                <div class="adm-step-num">1</div>
                                <div class="adm-step-content">
                                    <h4><?php _e( 'Form Collection', 'hidayah' ); ?></h4>
                                    <p><?php _e( 'Download online or collect directly from the madrasa office.', 'hidayah' ); ?></p>
                                </div>
                            </div>
                            <div class="adm-step">
                                <div class="adm-step-num">2</div>
                                <div class="adm-step-content">
                                    <h4><?php _e( 'Submit Application', 'hidayah' ); ?></h4>
                                    <p><?php _e( 'Submit the application form with all documents within the scheduled date.', 'hidayah' ); ?></p>
                                </div>
                            </div>
                            <!-- ... more steps ... -->
                        </div>
                    </div>

                    <!-- Department & Seats -->
                    <div class="adm-section">
                        <h2 class="adm-section-title">
                            <span class="material-symbols-outlined">table_chart</span>
                            <?php _e( 'Departments & Seat Capacity', 'hidayah' ); ?>
                        </h2>
                        <div class="adm-table-wrap">
                            <table class="notice-table">
                                <thead>
                                    <tr>
                                        <th><?php _e( 'Department/Class', 'hidayah' ); ?></th>
                                        <th><?php _e( 'Total Seats', 'hidayah' ); ?></th>
                                        <th><?php _e( 'Minimum Qualification', 'hidayah' ); ?></th>
                                        <th><?php _e( 'Age Limit', 'hidayah' ); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr><td><?php _e( 'Noorani Department', 'hidayah' ); ?></td><td>60</td><td>5+ Years</td><td>5-8 Years</td></tr>
                                    <!-- ... rows ... -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Checklist -->
                    <div class="adm-section">
                        <h2 class="adm-section-title">
                            <span class="material-symbols-outlined">checklist</span>
                            <?php _e( 'Required Documents', 'hidayah' ); ?>
                        </h2>
                        <div class="adm-checklist">
                            <div class="adm-check-item"><span class="material-symbols-outlined adm-check-icon">check_box</span><span><?php _e( 'Completed Admission Form', 'hidayah' ); ?></span></div>
                            <div class="adm-check-item"><span class="material-symbols-outlined adm-check-icon">check_box</span><span><?php _e( 'Birth Registration Certificate', 'hidayah' ); ?></span></div>
                        </div>
                    </div>

                    <!-- Fees -->
                    <div class="adm-section">
                        <h2 class="adm-section-title">
                            <span class="material-symbols-outlined">payments</span>
                            <?php _e( 'Fees & Salary List', 'hidayah' ); ?>
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
                            <?php _e( 'Frequently Asked Questions (FAQ)', 'hidayah' ); ?>
                        </h2>
                        <div class="adm-faq">
                            <details class="adm-faq-item">
                                <summary><?php _e( 'Is any prior experience required for admission?', 'hidayah' ); ?></summary>
                                <p><?php _e( 'No prior experience is required for admission to the Noorani department.', 'hidayah' ); ?></p>
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
                        <h4 class="sidebar-widget-title"><span class="material-symbols-outlined">event</span><?php _e( 'Important Dates', 'hidayah' ); ?></h4>
                        <!-- Date list -->
                    </div>

                    <!-- Apply CTA -->
                    <div class="sidebar-widget adm-apply-cta-widget">
                        <span class="material-symbols-outlined adm-apply-cta-icon">school</span>
                        <h4 class="adm-apply-cta-title"><?php _e( 'Apply Now', 'hidayah' ); ?></h4>
                        <a class="btn adm-apply-cta-btn" href="<?php echo esc_url(home_url('/apply')); ?>"><?php _e( 'Apply Online', 'hidayah' ); ?></a>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>

<?php endwhile; ?>
<?php get_footer(); ?>
