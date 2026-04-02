<?php
/**
 * Template Name: Question & Doa Form
 *
 * @package Hidayah
 */
get_header();

while ( have_posts() ) : the_post();
?>

<section class="archive-hero">
    <div class="archive-hero-content">
        <h2><?php the_title(); ?></h2>
        <p><?php echo get_post_meta( get_the_ID(), '_qd_hero_text', true ) ?: __( 'Send your religious questions or request for prayers.', 'hidayah' ); ?></p>
    </div>
</section>

<section class="archive-section">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="archive-breadcrumb">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php _e( 'Home', 'hidayah' ); ?></a>
            <span class="material-symbols-outlined breadcrumb-sep">chevron_right</span>
            <a href="<?php echo esc_url( home_url( '/dini-jiggasa/' ) ); ?>"><?php _e( 'Dini Jiggasa', 'hidayah' ); ?></a>
            <span class="material-symbols-outlined breadcrumb-sep">chevron_right</span>
            <span class="breadcrumb-current"><?php the_title(); ?></span>
        </nav>

        <div class="qdform-wrap">
            <!-- Tabs -->
            <div class="qdform-tabs">
                <button class="qdform-tab active" data-tab="question">
                    <span class="material-symbols-outlined">help</span>
                    <?php _e( 'Ask a Question', 'hidayah' ); ?>
                </button>
                <button class="qdform-tab" data-tab="doa" id="doa">
                    <span class="material-symbols-outlined">volunteer_activism</span>
                    <?php _e( 'Prayer Request', 'hidayah' ); ?>
                </button>
            </div>

            <!-- Guidelines -->
            <div class="qdform-guideline">
                <h4><span class="material-symbols-outlined">info</span><?php _e( 'Guidelines for Asking Questions', 'hidayah' ); ?></h4>
                <ul>
                    <li><?php _e( 'Write the question clearly and concisely.', 'hidayah' ); ?></li>
                    <li><?php _e( 'Use at least 50 words for detailed questions.', 'hidayah' ); ?></li>
                    <!-- More guidelines -->
                </ul>
            </div>

            <!-- Panel: Question -->
            <div class="qdform-panel active" id="panel-question">
                <?php the_content(); ?>
                <!-- WP Contact Form 7 or custom form logic should go here -->
                <form class="qdform" id="questionForm">
                    <!-- Form fields -->
                </form>
            </div>

            <!-- Panel: Doa -->
            <div class="qdform-panel" id="panel-doa">
                <form class="qdform" id="doaForm">
                    <!-- Form fields -->
                </form>
            </div>

            <!-- Contact Card -->
            <div class="qdform-contact-card">
                <h4><span class="material-symbols-outlined">contact_phone</span><?php _e( 'Direct Contact', 'hidayah' ); ?></h4>
                <div class="qdform-contact-grid">
                    <div class="qdform-contact-item">
                        <span class="material-symbols-outlined">call</span>
                        <div><strong><?php _e( 'Phone', 'hidayah' ); ?></strong><span><?php echo h_opt('darbar_phone', '+8801234567890'); ?></span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php endwhile; ?>
<?php get_footer(); ?>
