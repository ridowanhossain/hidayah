<?php
/**
 * Custom Meta Boxes
 * Registers admin meta boxes for all custom post types.
 *
 * @package Hidayah
 */

// ══════════════════════════════════════════════════════
// AUDIO LECTURE META BOX
// ══════════════════════════════════════════════════════

function hidayah_audio_meta_box() {
    add_meta_box(
        'hidayah_audio_details',
        __( 'Audio Details', 'hidayah' ),
        'hidayah_audio_meta_box_cb',
        'audio',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'hidayah_audio_meta_box' );

function hidayah_audio_meta_box_cb( $post ) {
    wp_nonce_field( 'hidayah_audio_save', 'hidayah_audio_nonce' );

    $audio_url     = get_post_meta( $post->ID, '_audio_url',          true );
    $audio_embed   = get_post_meta( $post->ID, '_audio_embed',        true );
    $youtube_url   = get_post_meta( $post->ID, '_youtube_url',        true );
    $location      = get_post_meta( $post->ID, '_mahfil_location',    true );
    $speaker_role  = get_post_meta( $post->ID, '_speaker_role',       true );
    ?>

    <style>
        .hidayah-meta-table { width: 100%; border-collapse: collapse; }
        .hidayah-meta-table th { width: 200px; text-align: left; padding: 10px 12px; font-weight: 600; vertical-align: top; background: #f9f9f9; border-bottom: 1px solid #eee; }
        .hidayah-meta-table td { padding: 10px 12px; border-bottom: 1px solid #eee; }
        .hidayah-meta-table input[type="text"],
        .hidayah-meta-table textarea,
        .hidayah-meta-table input[type="url"],
        .hidayah-meta-table input[type="number"] { width: 100%; }
        .hidayah-meta-table textarea { height: 80px; resize: vertical; }
        .hidayah-meta-subheading { background: #065f46; color: #fff; padding: 8px 12px; font-weight: 700; font-size: 13px; letter-spacing: 0.5px; }
        .hidayah-media-row { display: flex; gap: 8px; align-items: center; }
        .hidayah-media-row input { flex: 1; }
        .hidayah-upload-btn { display: inline-flex; align-items: center; gap: 5px; padding: 6px 14px; background: #065f46; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-size: 13px; white-space: nowrap; text-decoration: none; }
        .hidayah-upload-btn:hover { background: #064e3b; color: #fff; }
        .hidayah-clear-btn { display: inline-flex; align-items: center; padding: 6px 10px; background: #cc1818; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-size: 12px; }
        .hidayah-clear-btn:hover { background: #a31212; color: #fff; }
        .hidayah-audio-preview { margin-top: 8px; display: none; }
        .hidayah-audio-preview audio { width: 100%; height: 40px; }
    </style>

    <table class="hidayah-meta-table">

        <tr><td colspan="2" class="hidayah-meta-subheading">🔊 <?php esc_html_e( 'Audio File', 'hidayah' ); ?></td></tr>

        <tr>
            <th><label for="_audio_url"><?php esc_html_e( 'Audio File (MP3)', 'hidayah' ); ?></label></th>
            <td>
                <div class="hidayah-media-row">
                    <input type="url" id="_audio_url" name="_audio_url"
                           value="<?php echo esc_url( $audio_url ); ?>"
                           placeholder="https://..." />
                    <button type="button" class="hidayah-upload-btn" data-target="_audio_url" data-preview="audio-preview">
                        📁 <?php esc_html_e( 'Upload / Select', 'hidayah' ); ?>
                    </button>
                    <?php if ( $audio_url ) : ?>
                    <button type="button" class="hidayah-clear-btn" data-target="_audio_url" data-preview="audio-preview">✕</button>
                    <?php endif; ?>
                </div>
                <div class="hidayah-audio-preview" id="audio-preview" style="<?php echo $audio_url ? 'display:block;' : ''; ?>">
                    <audio controls src="<?php echo esc_url( $audio_url ); ?>"></audio>
                </div>
                <p class="description"><?php esc_html_e( 'Upload an MP3 from your Media Library or enter a direct URL. Used for the inline audio player.', 'hidayah' ); ?></p>
            </td>
        </tr>

        <tr>
            <th><label for="_audio_embed"><?php esc_html_e( 'Audio Embed Code', 'hidayah' ); ?></label></th>
            <td>
                <textarea id="_audio_embed" name="_audio_embed"
                          placeholder="<?php esc_attr_e( 'Paste SoundCloud, Mixcloud, or custom iframe embed code here...', 'hidayah' ); ?>"><?php echo esc_textarea( $audio_embed ); ?></textarea>
                <p class="description"><?php esc_html_e( 'Optional: Paste an embed code from SoundCloud, Podbean, etc. Overrides the MP3 player if provided.', 'hidayah' ); ?></p>
            </td>
        </tr>

        <tr>
            <th><label for="_youtube_url"><?php esc_html_e( 'YouTube URL (Audio Mode)', 'hidayah' ); ?></label></th>
            <td>
                <input type="url" id="_youtube_url" name="_youtube_url"
                       value="<?php echo esc_url( $youtube_url ); ?>"
                       placeholder="https://www.youtube.com/watch?v=..." />
                <?php
                $yt_vid_id = '';
                if ( $youtube_url ) {
                    preg_match( '/(?:v=|youtu\.be\/|embed\/)([a-zA-Z0-9_-]{11})/', $youtube_url, $m );
                    $yt_vid_id = $m[1] ?? '';
                }
                ?>
                <?php if ( $yt_vid_id ) : ?>
                <div style="margin-top: 8px; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 6px; padding: 10px; display: flex; align-items: center; gap: 10px;">
                    <img src="https://img.youtube.com/vi/<?php echo esc_attr( $yt_vid_id ); ?>/default.jpg"
                         style="width: 80px; height: 60px; object-fit: cover; border-radius: 4px;" />
                    <div>
                        <strong style="color: #065f46;"><?php esc_html_e( 'YouTube ভিডিও সংযুক্ত হয়েছে ✓', 'hidayah' ); ?></strong><br>
                        <small style="color: #6b7280;">Video ID: <?php echo esc_html( $yt_vid_id ); ?></small><br>
                        <small style="color: #6b7280;"><?php esc_html_e( 'ফ্রন্টএন্ডে ভিডিও লুকিয়ে অডিও মোডে প্লে হবে', 'hidayah' ); ?></small>
                    </div>
                </div>
                <?php endif; ?>
                <p class="description"><?php esc_html_e( 'YouTube লিঙ্ক দিলে সাইটে ভিডিও লুকিয়ে শুধু অডিও কন্ট্রোল দিয়ে শোনার ব্যবস্থা হবে। MP3 না থাকলে এটি প্রাইমারি প্লেয়ার হিসেবে কাজ করবে।', 'hidayah' ); ?></p>
            </td>
        </tr>



        <tr><td colspan="2" class="hidayah-meta-subheading">📋 <?php esc_html_e( 'Lecture Details', 'hidayah' ); ?></td></tr>



        <tr>
            <th><label for="_mahfil_location"><?php esc_html_e( 'Mahfil Location', 'hidayah' ); ?></label></th>
            <td>
                <input type="text" id="_mahfil_location" name="_mahfil_location"
                       value="<?php echo esc_attr( $location ); ?>"
                       placeholder="<?php esc_attr_e( 'e.g. সিদ্দীক্বিয়া দরবার শরীফ, ঢাকা', 'hidayah' ); ?>" />
            </td>
        </tr>

        <tr>
            <th><label for="_speaker_role"><?php esc_html_e( 'Speaker Role / Title', 'hidayah' ); ?></label></th>
            <td>
                <input type="text" id="_speaker_role" name="_speaker_role"
                       value="<?php echo esc_attr( $speaker_role ); ?>"
                       placeholder="<?php esc_attr_e( 'e.g. মুর্শিদ ক্বিবলা, সিদ্দীক্বিয়া দরবার শরীফ', 'hidayah' ); ?>" />
                <p class="description"><?php esc_html_e( 'Shown in the sidebar speaker card.', 'hidayah' ); ?></p>
            </td>
        </tr>





    </table>
    <?php
}

function hidayah_audio_meta_save( $post_id ) {
    if ( ! isset( $_POST['hidayah_audio_nonce'] ) ||
         ! wp_verify_nonce( $_POST['hidayah_audio_nonce'], 'hidayah_audio_save' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }
    if ( ! current_user_can( 'edit_post', $post_id ) ) { return; }

    $text_fields = array(
        '_audio_url'        => 'url',
        '_audio_embed'      => 'textarea',
        '_youtube_url'      => 'url',
        '_mahfil_location'  => 'text',
        '_speaker_role'     => 'text',
    );

    foreach ( $text_fields as $key => $type ) {
        if ( ! isset( $_POST[ $key ] ) ) {
            delete_post_meta( $post_id, $key );
            continue;
        }
        switch ( $type ) {
            case 'url':
                $value = esc_url_raw( $_POST[ $key ] );
                break;
            case 'textarea':
                $value = wp_kses_post( $_POST[ $key ] );
                break;
            case 'int':
                $value = absint( $_POST[ $key ] );
                break;
            default:
                $value = sanitize_text_field( $_POST[ $key ] );
        }
        update_post_meta( $post_id, $key, $value );
    }

}
add_action( 'save_post_audio', 'hidayah_audio_meta_save' );


// ══════════════════════════════════════════════════════
// ENQUEUE MEDIA UPLOADER SCRIPT (admin only, audio CPT)
// ══════════════════════════════════════════════════════

function hidayah_enqueue_meta_box_scripts( $hook ) {
    global $post_type;
    // Load only on audio post edit screens
    if ( ( $hook === 'post-new.php' || $hook === 'post.php' ) && $post_type === 'audio' ) {
        wp_enqueue_media();
        wp_add_inline_script( 'jquery-core', '
            jQuery(function($) {

                // Upload / Select button
                $(".hidayah-upload-btn").on("click", function(e) {
                    e.preventDefault();
                    var targetId  = $(this).data("target");
                    var previewId = $(this).data("preview");
                    var frame = wp.media({
                        title: "Select or Upload Audio File",
                        button: { text: "Use this file" },
                        library: { type: "audio" },
                        multiple: false
                    });
                    frame.on("select", function() {
                        var attachment = frame.state().get("selection").first().toJSON();
                        $("#" + targetId).val(attachment.url);
                        if (previewId) {
                            var $preview = $("#" + previewId);
                            $preview.find("audio").attr("src", attachment.url);
                            $preview.show();
                        }
                    });
                    frame.open();
                });

                // Clear button
                $(".hidayah-clear-btn").on("click", function(e) {
                    e.preventDefault();
                    var targetId  = $(this).data("target");
                    var previewId = $(this).data("preview");
                    $("#" + targetId).val("");
                    if (previewId) {
                        $("#" + previewId).hide().find("audio").attr("src", "");
                    }
                });

                // Auto-show preview if URL already filled on load
                $("#_audio_url").on("change input", function() {
                    var url = $(this).val();
                    var $preview = $("#audio-preview");
                    if (url) {
                        $preview.find("audio").attr("src", url);
                        $preview.show();
                    } else {
                        $preview.hide();
                    }
                });

            });
        ' );
    }
}
add_action( 'admin_enqueue_scripts', 'hidayah_enqueue_meta_box_scripts' );
