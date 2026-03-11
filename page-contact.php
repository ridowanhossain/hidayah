<?php
/**
 * Template Name: Contact Page
 *
 * @package Hidayah
 */
get_header();

while ( have_posts() ) : the_post();
?>

<!-- HERO SECTION -->
<section class="archive-hero">
    <div class="archive-hero-content">
        <h2><?php the_title(); ?></h2>
        <p><?php echo get_post_meta( get_the_ID(), '_contact_hero_text', true ) ?: __( 'আপনার যেকোনো জিজ্ঞাসা, পরামর্শ বা সহযোগিতার জন্য আমাদের সাথে যোগাযোগ করতে পারেন।', 'hidayah' ); ?></p>
    </div>
</section>

<!-- CONTACT CONTENT -->
<section class="archive-section">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="archive-breadcrumb">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php _e( 'হোম', 'hidayah' ); ?></a>
            <span class="material-symbols-outlined breadcrumb-sep">chevron_right</span>
            <span class="breadcrumb-current"><?php the_title(); ?></span>
        </nav>

        <div class="archive-layout contact-layout-top">
            <div class="archive-main">
                <div class="col-inner">
                    <div class="darbar-section contact-form-section">
                        <div class="contact-form-header">
                            <div class="contact-form-icon-badge">
                                <span class="material-symbols-outlined">mail</span>
                            </div>
                            <div>
                                <h3 class="darbar-section-title" style="margin:0 0 4px;"><?php _e( 'বার্তা পাঠান', 'hidayah' ); ?></h3>
                                <p style="font-size:13px; color:var(--text-light); margin:0;"><?php _e( 'আমরা ২৪ ঘণ্টার মধ্যে জবাব দেওয়ার চেষ্টা করি।', 'hidayah' ); ?></p>
                            </div>
                        </div>
                        <?php the_content(); ?>
                        <form class="contact-form-modern">
                            <div class="contact-form-row">
                                <div class="contact-field-wrap">
                                    <span class="material-symbols-outlined contact-field-icon">person</span>
                                    <input type="text" placeholder="<?php _e( 'আপনার পুরো নাম', 'hidayah' ); ?>" class="contact-field-input" required />
                                </div>
                                <div class="contact-field-wrap">
                                    <span class="material-symbols-outlined contact-field-icon">mail</span>
                                    <input type="email" placeholder="<?php _e( 'ইমেইল ঠিকানা', 'hidayah' ); ?>" class="contact-field-input" required />
                                </div>
                            </div>
                            <div class="contact-field-wrap">
                                <span class="material-symbols-outlined contact-field-icon">phone</span>
                                <input type="tel" placeholder="<?php _e( 'মোবাইল নম্বর (ঐচ্ছিক)', 'hidayah' ); ?>" class="contact-field-input" />
                            </div>
                            <div class="contact-field-wrap">
                                <span class="material-symbols-outlined contact-field-icon">edit_note</span>
                                <input type="text" placeholder="<?php _e( 'বার্তার বিষয়', 'hidayah' ); ?>" class="contact-field-input" required />
                            </div>
                            <div class="contact-field-wrap contact-textarea-wrap">
                                <span class="material-symbols-outlined contact-field-icon" style="top:16px;">chat</span>
                                <textarea placeholder="<?php _e( 'এখানে আপনার বার্তা বিস্তারিত লিখুন...', 'hidayah' ); ?>" rows="6" class="contact-field-input contact-field-textarea" required></textarea>
                            </div>
                            <div class="contact-form-footer-row">
                                <p class="contact-form-note">
                                    <span class="material-symbols-outlined" style="font-size:16px; vertical-align:middle;">lock</span>
                                    <?php _e( 'আপনার তথ্য সম্পূর্ণ গোপন রাখা হবে', 'hidayah' ); ?>
                                </p>
                                <button type="submit" class="btn contact-submit-btn">
                                    <span class="material-symbols-outlined">send</span>
                                    <?php _e( 'বার্তা পাঠান', 'hidayah' ); ?>
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="darbar-section contact-section-spacer">
                        <h3 class="darbar-section-title">
                            <span class="material-symbols-outlined">map</span>
                            <?php _e( 'আমাদের অবস্থান', 'hidayah' ); ?>
                        </h3>
                        <div class="contact-map-container">
                            <?php echo h_opt('contact_map_iframe', '<iframe src="https://www.google.com/maps/embed?..." width="100%" height="100%" style="border: 0" allowfullscreen="" loading="lazy"></iframe>'); ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SIDEBAR -->
            <aside class="archive-sidebar">
                <div class="col-inner">
                    <div class="sidebar-widget">
                        <h4 class="sidebar-widget-title">
                            <span class="material-symbols-outlined">call</span>
                            <?php _e( 'সরাসরি যোগাযোগ', 'hidayah' ); ?>
                        </h4>
                        <div class="sidebar-contact-list">
                            <div class="sidebar-contact-link">
                                <span class="material-symbols-outlined sidebar-contact-icon">location_on</span>
                                <div>
                                    <strong><?php _e( 'ঠিকানা', 'hidayah' ); ?></strong>
                                    <p class="contact-info-text"><?php echo h_opt('darbar_address', 'মিরপুর-১, ঢাকা, বাংলাদেশ'); ?></p>
                                </div>
                            </div>
                            <a href="tel:<?php echo h_opt('darbar_phone', '+8801234567890'); ?>" class="sidebar-contact-link">
                                <span class="material-symbols-outlined sidebar-contact-icon">call</span>
                                <div>
                                    <strong><?php _e( 'ফোন', 'hidayah' ); ?></strong>
                                    <p class="contact-info-text"><?php echo h_opt('darbar_phone', '+৮৮০ ১২৩৪ ৫৬৭৮৯০'); ?></p>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Office Hours -->
                    <div class="sidebar-widget">
                        <h4 class="sidebar-widget-title">
                            <span class="material-symbols-outlined">schedule</span>
                            <?php _e( 'কার্যালয়ের সময়', 'hidayah' ); ?>
                        </h4>
                        <ul class="sidebar-recent-list" style="gap: 8px">
                            <li style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid var(--border-light)">
                                <span style="font-size: 14px; font-weight: 600; color: var(--text-dark)"><?php _e( 'শনি – বৃহস্পতি', 'hidayah' ); ?></span>
                                <span style="font-size: 13px; color: var(--text-light)"><?php _e( 'সকাল ৯টা – রাত ৮টা', 'hidayah' ); ?></span>
                            </li>
                            <!-- More hours -->
                        </ul>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>

<?php endwhile; ?>
<?php get_footer(); ?>
