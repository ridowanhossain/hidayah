<?php
/**
 * Custom Meta Boxes
 * Registers admin meta boxes for all custom post types.
 *
 * @package Hidayah
 */

if ( ! function_exists( 'hidayah_meta_box_base_styles' ) ) {
    function hidayah_meta_box_base_styles() {
        ?>
        <style>
            .hidayah-meta-table { width: 100%; border-collapse: collapse; }
            .hidayah-meta-table th { width: 200px; text-align: left; padding: 10px 12px; font-weight: 600; vertical-align: top; background: #f9f9f9; border-bottom: 1px solid #eee; }
            .hidayah-meta-table td { padding: 10px 12px; border-bottom: 1px solid #eee; }
            .hidayah-meta-table input[type="text"],
            .hidayah-meta-table textarea,
            .hidayah-meta-table input[type="url"],
            .hidayah-meta-table input[type="number"],
            .hidayah-meta-table input[type="date"],
            .hidayah-meta-table select { width: 100%; }
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
            .hidayah-preview-grid { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 8px; }
            .hidayah-preview-grid img { width: 80px; height: 80px; object-fit: cover; border-radius: 4px; border: 1px solid #e5e7eb; }
        </style>
        <?php
    }
}

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

    <?php hidayah_meta_box_base_styles(); ?>

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
// BOOK META BOX
// ══════════════════════════════════════════════════════
function hidayah_book_meta_box() {
    add_meta_box(
        'hidayah_book_details',
        __( 'Book Details', 'hidayah' ),
        'hidayah_book_meta_box_cb',
        'book',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'hidayah_book_meta_box' );

function hidayah_book_meta_box_cb( $post ) {
    wp_nonce_field( 'hidayah_book_save', 'hidayah_book_nonce' );
    hidayah_meta_box_base_styles();

    $price        = get_post_meta( $post->ID, '_book_price', true );
    $old_price    = get_post_meta( $post->ID, '_book_old_price', true );
    $badge        = get_post_meta( $post->ID, '_book_badge', true );
    $isbn         = get_post_meta( $post->ID, '_book_isbn', true );
    $pages        = get_post_meta( $post->ID, '_book_pages', true );
    $binding      = get_post_meta( $post->ID, '_book_binding', true );
    $weight       = get_post_meta( $post->ID, '_book_weight', true );
    $edition      = get_post_meta( $post->ID, '_book_edition', true );
    $year         = get_post_meta( $post->ID, '_book_year', true );
    $publisher    = get_post_meta( $post->ID, '_book_publisher', true );
    $stock_status = get_post_meta( $post->ID, '_stock_status', true ) ?: 'instock';
    $rating       = get_post_meta( $post->ID, '_book_rating', true );
    $rating_cnt   = get_post_meta( $post->ID, '_book_rating_count', true );
    $toc          = get_post_meta( $post->ID, '_book_toc', true );
    $samples      = get_post_meta( $post->ID, '_book_samples', true );
    $samples_text = '';
    if ( is_array( $samples ) ) {
        $samples_text = implode( "\n", array_map( 'esc_url', $samples ) );
    }
    ?>
    <table class="hidayah-meta-table">
        <tr><td colspan="2" class="hidayah-meta-subheading">📘 <?php esc_html_e( 'Pricing', 'hidayah' ); ?></td></tr>
        <tr>
            <th><label for="_book_price"><?php esc_html_e( 'Price', 'hidayah' ); ?></label></th>
            <td><input type="number" id="_book_price" name="_book_price" value="<?php echo esc_attr( $price ); ?>" /></td>
        </tr>
        <tr>
            <th><label for="_book_old_price"><?php esc_html_e( 'Old Price', 'hidayah' ); ?></label></th>
            <td><input type="number" id="_book_old_price" name="_book_old_price" value="<?php echo esc_attr( $old_price ); ?>" /></td>
        </tr>
        <tr>
            <th><label for="_book_badge"><?php esc_html_e( 'Badge Text', 'hidayah' ); ?></label></th>
            <td><input type="text" id="_book_badge" name="_book_badge" value="<?php echo esc_attr( $badge ); ?>" /></td>
        </tr>

        <tr><td colspan="2" class="hidayah-meta-subheading">📚 <?php esc_html_e( 'Book Info', 'hidayah' ); ?></td></tr>
        <tr>
            <th><label for="_book_isbn"><?php esc_html_e( 'ISBN', 'hidayah' ); ?></label></th>
            <td><input type="text" id="_book_isbn" name="_book_isbn" value="<?php echo esc_attr( $isbn ); ?>" /></td>
        </tr>
        <tr>
            <th><label for="_book_pages"><?php esc_html_e( 'Pages', 'hidayah' ); ?></label></th>
            <td><input type="number" id="_book_pages" name="_book_pages" value="<?php echo esc_attr( $pages ); ?>" /></td>
        </tr>
        <tr>
            <th><label for="_book_binding"><?php esc_html_e( 'Binding', 'hidayah' ); ?></label></th>
            <td><input type="text" id="_book_binding" name="_book_binding" value="<?php echo esc_attr( $binding ); ?>" /></td>
        </tr>
        <tr>
            <th><label for="_book_weight"><?php esc_html_e( 'Weight (g)', 'hidayah' ); ?></label></th>
            <td><input type="number" id="_book_weight" name="_book_weight" value="<?php echo esc_attr( $weight ); ?>" /></td>
        </tr>
        <tr>
            <th><label for="_book_edition"><?php esc_html_e( 'Edition', 'hidayah' ); ?></label></th>
            <td><input type="text" id="_book_edition" name="_book_edition" value="<?php echo esc_attr( $edition ); ?>" /></td>
        </tr>
        <tr>
            <th><label for="_book_year"><?php esc_html_e( 'Publication Year', 'hidayah' ); ?></label></th>
            <td><input type="text" id="_book_year" name="_book_year" value="<?php echo esc_attr( $year ); ?>" /></td>
        </tr>
        <tr>
            <th><label for="_book_publisher"><?php esc_html_e( 'Publisher', 'hidayah' ); ?></label></th>
            <td><input type="text" id="_book_publisher" name="_book_publisher" value="<?php echo esc_attr( $publisher ); ?>" /></td>
        </tr>
        <tr>
            <th><label for="_stock_status"><?php esc_html_e( 'Stock Status', 'hidayah' ); ?></label></th>
            <td>
                <select id="_stock_status" name="_stock_status">
                    <option value="instock" <?php selected( $stock_status, 'instock' ); ?>><?php esc_html_e( 'In Stock', 'hidayah' ); ?></option>
                    <option value="outofstock" <?php selected( $stock_status, 'outofstock' ); ?>><?php esc_html_e( 'Out of Stock', 'hidayah' ); ?></option>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="_book_rating"><?php esc_html_e( 'Rating (0-5)', 'hidayah' ); ?></label></th>
            <td><input type="number" step="0.1" min="0" max="5" id="_book_rating" name="_book_rating" value="<?php echo esc_attr( $rating ); ?>" /></td>
        </tr>
        <tr>
            <th><label for="_book_rating_count"><?php esc_html_e( 'Rating Count', 'hidayah' ); ?></label></th>
            <td><input type="number" id="_book_rating_count" name="_book_rating_count" value="<?php echo esc_attr( $rating_cnt ); ?>" /></td>
        </tr>
        <tr>
            <th><label for="_book_toc"><?php esc_html_e( 'Table of Contents', 'hidayah' ); ?></label></th>
            <td><textarea id="_book_toc" name="_book_toc"><?php echo esc_textarea( $toc ); ?></textarea></td>
        </tr>
        <tr>
            <th><label for="_book_samples"><?php esc_html_e( 'Sample Page URLs (one per line)', 'hidayah' ); ?></label></th>
            <td><textarea id="_book_samples" name="_book_samples" placeholder="https://example.com/sample1.jpg
https://example.com/sample2.jpg"><?php echo esc_textarea( $samples_text ); ?></textarea></td>
        </tr>
    </table>
    <?php
}

function hidayah_book_meta_save( $post_id ) {
    if ( ! isset( $_POST['hidayah_book_nonce'] ) || ! wp_verify_nonce( $_POST['hidayah_book_nonce'], 'hidayah_book_save' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }
    if ( ! current_user_can( 'edit_post', $post_id ) ) { return; }

    $fields = array(
        '_book_price'        => 'number',
        '_book_old_price'    => 'number',
        '_book_badge'        => 'text',
        '_book_isbn'         => 'text',
        '_book_pages'        => 'number',
        '_book_binding'      => 'text',
        '_book_weight'       => 'number',
        '_book_edition'      => 'text',
        '_book_year'         => 'text',
        '_book_publisher'    => 'text',
        '_stock_status'      => 'text',
        '_book_rating'       => 'text',
        '_book_rating_count' => 'number',
        '_book_toc'          => 'textarea',
    );

    foreach ( $fields as $key => $type ) {
        if ( ! isset( $_POST[ $key ] ) ) {
            delete_post_meta( $post_id, $key );
            continue;
        }
        $value = $_POST[ $key ];
        if ( $type === 'number' ) {
            $value = $value === '' ? '' : sanitize_text_field( $value );
        } elseif ( $type === 'textarea' ) {
            $value = wp_kses_post( $value );
        } else {
            $value = sanitize_text_field( $value );
        }
        update_post_meta( $post_id, $key, $value );
    }

    if ( isset( $_POST['_book_samples'] ) ) {
        $raw = sanitize_textarea_field( $_POST['_book_samples'] );
        $lines = preg_split( '/\r\n|\r|\n/', $raw );
        $lines = array_filter( array_map( 'trim', $lines ) );
        $samples = array();
        foreach ( $lines as $line ) {
            $url = esc_url_raw( $line );
            if ( $url ) {
                $samples[] = $url;
            }
        }
        if ( ! empty( $samples ) ) {
            update_post_meta( $post_id, '_book_samples', $samples );
        } else {
            delete_post_meta( $post_id, '_book_samples' );
        }
    }
}
add_action( 'save_post_book', 'hidayah_book_meta_save' );


// ══════════════════════════════════════════════════════
// Dini Jiggasa META BOX
// ══════════════════════════════════════════════════════
function hidayah_jiggasa_meta_box() {
    add_meta_box(
        'hidayah_jiggasa_details',
        __( 'Jiggasa Details', 'hidayah' ),
        'hidayah_jiggasa_meta_box_cb',
        'dini_jiggasa',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'hidayah_jiggasa_meta_box' );

function hidayah_jiggasa_meta_box_cb( $post ) {
    wp_nonce_field( 'hidayah_jiggasa_save', 'hidayah_jiggasa_nonce' );
    hidayah_meta_box_base_styles();

    $asker_name  = get_post_meta( $post->ID, '_jiggasa_asker_name', true );
    $asker_loc   = get_post_meta( $post->ID, '_jiggasa_asker_location', true );
    $mufti       = get_post_meta( $post->ID, '_jiggasa_mufti', true );
    $mufti_title = get_post_meta( $post->ID, '_jiggasa_mufti_title', true );
    $mufti_img   = get_post_meta( $post->ID, '_jiggasa_mufti_image', true );
    $mufti_cnt   = get_post_meta( $post->ID, '_jiggasa_mufti_ans_count', true );
    $dalil       = get_post_meta( $post->ID, '_jiggasa_dalil', true );
    $arabic_ref  = get_post_meta( $post->ID, '_jiggasa_arabic_ref', true );
    $status      = get_post_meta( $post->ID, '_jiggasa_status', true ) ?: 'answered';
    ?>
    <table class="hidayah-meta-table">
        <tr><td colspan="2" class="hidayah-meta-subheading">🧑 <?php esc_html_e( 'Asker Info', 'hidayah' ); ?></td></tr>
        <tr>
            <th><label for="_jiggasa_asker_name"><?php esc_html_e( 'Asker Name', 'hidayah' ); ?></label></th>
            <td><input type="text" id="_jiggasa_asker_name" name="_jiggasa_asker_name" value="<?php echo esc_attr( $asker_name ); ?>" /></td>
        </tr>
        <tr>
            <th><label for="_jiggasa_asker_location"><?php esc_html_e( 'Asker Location', 'hidayah' ); ?></label></th>
            <td><input type="text" id="_jiggasa_asker_location" name="_jiggasa_asker_location" value="<?php echo esc_attr( $asker_loc ); ?>" /></td>
        </tr>

        <tr><td colspan="2" class="hidayah-meta-subheading">🧑‍⚖️ <?php esc_html_e( 'Mufti Info', 'hidayah' ); ?></td></tr>
        <tr>
            <th><label for="_jiggasa_mufti"><?php esc_html_e( 'Mufti Name', 'hidayah' ); ?></label></th>
            <td><input type="text" id="_jiggasa_mufti" name="_jiggasa_mufti" value="<?php echo esc_attr( $mufti ); ?>" /></td>
        </tr>
        <tr>
            <th><label for="_jiggasa_mufti_title"><?php esc_html_e( 'Mufti Title', 'hidayah' ); ?></label></th>
            <td><input type="text" id="_jiggasa_mufti_title" name="_jiggasa_mufti_title" value="<?php echo esc_attr( $mufti_title ); ?>" /></td>
        </tr>
        <tr>
            <th><label for="_jiggasa_mufti_image"><?php esc_html_e( 'Mufti Image', 'hidayah' ); ?></label></th>
            <td>
                <div class="hidayah-media-row">
                    <input type="url" id="_jiggasa_mufti_image" name="_jiggasa_mufti_image" value="<?php echo esc_url( $mufti_img ); ?>" />
                    <button type="button" class="hidayah-upload-btn" data-target="_jiggasa_mufti_image" data-preview="jiggasa-mufti-preview" data-library="image">📁 <?php esc_html_e( 'Upload', 'hidayah' ); ?></button>
                    <?php if ( $mufti_img ) : ?>
                        <button type="button" class="hidayah-clear-btn" data-target="_jiggasa_mufti_image" data-preview="jiggasa-mufti-preview">✕</button>
                    <?php endif; ?>
                </div>
                <div class="hidayah-preview-grid" id="jiggasa-mufti-preview" style="<?php echo $mufti_img ? 'display:flex;' : 'display:none;'; ?>">
                    <?php if ( $mufti_img ) : ?>
                        <img src="<?php echo esc_url( $mufti_img ); ?>" alt="" />
                    <?php endif; ?>
                </div>
            </td>
        </tr>
        <tr>
            <th><label for="_jiggasa_mufti_ans_count"><?php esc_html_e( 'Mufti Answer Count', 'hidayah' ); ?></label></th>
            <td><input type="number" id="_jiggasa_mufti_ans_count" name="_jiggasa_mufti_ans_count" value="<?php echo esc_attr( $mufti_cnt ); ?>" /></td>
        </tr>

        <tr><td colspan="2" class="hidayah-meta-subheading">📌 <?php esc_html_e( 'Answer Meta', 'hidayah' ); ?></td></tr>
        <tr>
            <th><label for="_jiggasa_status"><?php esc_html_e( 'Status', 'hidayah' ); ?></label></th>
            <td>
                <select id="_jiggasa_status" name="_jiggasa_status">
                    <option value="answered" <?php selected( $status, 'answered' ); ?>><?php esc_html_e( 'Answered', 'hidayah' ); ?></option>
                    <option value="pending" <?php selected( $status, 'pending' ); ?>><?php esc_html_e( 'Pending', 'hidayah' ); ?></option>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="_jiggasa_dalil"><?php esc_html_e( 'Dalil / References', 'hidayah' ); ?></label></th>
            <td><textarea id="_jiggasa_dalil" name="_jiggasa_dalil"><?php echo esc_textarea( $dalil ); ?></textarea></td>
        </tr>
        <tr>
            <th><label for="_jiggasa_arabic_ref"><?php esc_html_e( 'Arabic References', 'hidayah' ); ?></label></th>
            <td><textarea id="_jiggasa_arabic_ref" name="_jiggasa_arabic_ref"><?php echo esc_textarea( $arabic_ref ); ?></textarea></td>
        </tr>
    </table>
    <?php
}

function hidayah_jiggasa_meta_save( $post_id ) {
    if ( ! isset( $_POST['hidayah_jiggasa_nonce'] ) || ! wp_verify_nonce( $_POST['hidayah_jiggasa_nonce'], 'hidayah_jiggasa_save' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }
    if ( ! current_user_can( 'edit_post', $post_id ) ) { return; }

    $fields = array(
        '_jiggasa_asker_name'      => 'text',
        '_jiggasa_asker_location'  => 'text',
        '_jiggasa_mufti'           => 'text',
        '_jiggasa_mufti_title'     => 'text',
        '_jiggasa_mufti_image'     => 'url',
        '_jiggasa_mufti_ans_count' => 'number',
        '_jiggasa_dalil'           => 'textarea',
        '_jiggasa_arabic_ref'      => 'textarea',
        '_jiggasa_status'          => 'text',
    );

    foreach ( $fields as $key => $type ) {
        if ( ! isset( $_POST[ $key ] ) ) {
            delete_post_meta( $post_id, $key );
            continue;
        }
        $value = $_POST[ $key ];
        if ( $type === 'url' ) {
            $value = esc_url_raw( $value );
        } elseif ( $type === 'textarea' ) {
            $value = wp_kses_post( $value );
        } elseif ( $type === 'number' ) {
            $value = sanitize_text_field( $value );
        } else {
            $value = sanitize_text_field( $value );
        }
        update_post_meta( $post_id, $key, $value );
    }
}
add_action( 'save_post_dini_jiggasa', 'hidayah_jiggasa_meta_save' );


// ══════════════════════════════════════════════════════
// MONTHLY HD META BOX
// ══════════════════════════════════════════════════════
function hidayah_monthly_hd_meta_box() {
    add_meta_box(
        'hidayah_monthly_hd_details',
        __( 'Monthly Issue Details', 'hidayah' ),
        'hidayah_monthly_hd_meta_box_cb',
        'monthly_hd',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'hidayah_monthly_hd_meta_box' );

function hidayah_monthly_hd_meta_box_cb( $post ) {
    wp_nonce_field( 'hidayah_monthly_hd_save', 'hidayah_monthly_hd_nonce' );
    hidayah_meta_box_base_styles();

    $pages     = get_post_meta( $post->ID, '_magazine_pages', true );
    $pdf_url   = get_post_meta( $post->ID, '_pdf_file_url', true );
    $pdf_size  = get_post_meta( $post->ID, '_pdf_file_size', true );
    $special   = get_post_meta( $post->ID, '_is_special_issue', true );
    $editorial = get_post_meta( $post->ID, '_editorial_text', true );
    $toc       = get_post_meta( $post->ID, '_magazine_toc', true );
    $summaries = get_post_meta( $post->ID, '_article_summaries', true );
    ?>
    <table class="hidayah-meta-table">
        <tr><td colspan="2" class="hidayah-meta-subheading">📄 <?php esc_html_e( 'PDF Details', 'hidayah' ); ?></td></tr>
        <tr>
            <th><label for="_magazine_pages"><?php esc_html_e( 'Page Count', 'hidayah' ); ?></label></th>
            <td><input type="number" id="_magazine_pages" name="_magazine_pages" value="<?php echo esc_attr( $pages ); ?>" /></td>
        </tr>
        <tr>
            <th><label for="_pdf_file_url"><?php esc_html_e( 'PDF File URL', 'hidayah' ); ?></label></th>
            <td>
                <div class="hidayah-media-row">
                    <input type="url" id="_pdf_file_url" name="_pdf_file_url" value="<?php echo esc_url( $pdf_url ); ?>" />
                    <button type="button" class="hidayah-upload-btn" data-target="_pdf_file_url" data-library="application/pdf">📁 <?php esc_html_e( 'Upload', 'hidayah' ); ?></button>
                    <?php if ( $pdf_url ) : ?>
                        <button type="button" class="hidayah-clear-btn" data-target="_pdf_file_url">✕</button>
                    <?php endif; ?>
                </div>
            </td>
        </tr>
        <tr>
            <th><label for="_pdf_file_size"><?php esc_html_e( 'PDF Size (MB)', 'hidayah' ); ?></label></th>
            <td><input type="number" id="_pdf_file_size" name="_pdf_file_size" step="0.1" value="<?php echo esc_attr( $pdf_size ); ?>" /></td>
        </tr>
        <tr>
            <th><label for="_is_special_issue"><?php esc_html_e( 'Special Issue', 'hidayah' ); ?></label></th>
            <td><input type="checkbox" id="_is_special_issue" name="_is_special_issue" value="1" <?php checked( $special, '1' ); ?> /></td>
        </tr>
        <tr>
            <th><label for="_magazine_toc"><?php esc_html_e( 'Table of Contents', 'hidayah' ); ?></label></th>
            <td><textarea id="_magazine_toc" name="_magazine_toc"><?php echo esc_textarea( $toc ); ?></textarea></td>
        </tr>
        <tr>
            <th><label for="_editorial_text"><?php esc_html_e( 'Editorial', 'hidayah' ); ?></label></th>
            <td><textarea id="_editorial_text" name="_editorial_text"><?php echo esc_textarea( $editorial ); ?></textarea></td>
        </tr>
        <tr>
            <th><label for="_article_summaries"><?php esc_html_e( 'Article Summaries (JSON)', 'hidayah' ); ?></label></th>
            <td>
                <textarea id="_article_summaries" name="_article_summaries" placeholder='[{"title":"...","desc":"..."}]'><?php echo esc_textarea( $summaries ); ?></textarea>
                <p class="description"><?php esc_html_e( 'Provide JSON array: [{"title":"...","desc":"..."}]', 'hidayah' ); ?></p>
            </td>
        </tr>
    </table>
    <?php
}

function hidayah_monthly_hd_meta_save( $post_id ) {
    if ( ! isset( $_POST['hidayah_monthly_hd_nonce'] ) || ! wp_verify_nonce( $_POST['hidayah_monthly_hd_nonce'], 'hidayah_monthly_hd_save' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }
    if ( ! current_user_can( 'edit_post', $post_id ) ) { return; }

    $fields = array(
        '_magazine_pages'   => 'number',
        '_pdf_file_url'     => 'url',
        '_pdf_file_size'    => 'number',
        '_magazine_toc'     => 'textarea',
        '_editorial_text'   => 'textarea',
        '_article_summaries'=> 'textarea',
    );
    foreach ( $fields as $key => $type ) {
        if ( ! isset( $_POST[ $key ] ) ) {
            delete_post_meta( $post_id, $key );
            continue;
        }
        $value = $_POST[ $key ];
        if ( $type === 'url' ) {
            $value = esc_url_raw( $value );
        } elseif ( $type === 'textarea' ) {
            $value = wp_kses_post( $value );
        } else {
            $value = sanitize_text_field( $value );
        }
        update_post_meta( $post_id, $key, $value );
    }

    $special = isset( $_POST['_is_special_issue'] ) ? '1' : '';
    if ( $special ) {
        update_post_meta( $post_id, '_is_special_issue', '1' );
    } else {
        delete_post_meta( $post_id, '_is_special_issue' );
    }
}
add_action( 'save_post_monthly_hd', 'hidayah_monthly_hd_meta_save' );


// ══════════════════════════════════════════════════════
// NOTICE META BOX
// ══════════════════════════════════════════════════════
function hidayah_notice_meta_box() {
    add_meta_box(
        'hidayah_notice_details',
        __( 'Notice Details', 'hidayah' ),
        'hidayah_notice_meta_box_cb',
        'notice',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'hidayah_notice_meta_box' );

function hidayah_notice_meta_box_cb( $post ) {
    wp_nonce_field( 'hidayah_notice_save', 'hidayah_notice_nonce' );
    hidayah_meta_box_base_styles();

    $urgency  = get_post_meta( $post->ID, '_notice_urgency', true ) ?: 'general';
    $attach   = get_post_meta( $post->ID, '_notice_attachment', true );
    $expiry   = get_post_meta( $post->ID, '_notice_expiry_date', true );
    $dates    = get_post_meta( $post->ID, '_notice_important_dates', true );
    ?>
    <table class="hidayah-meta-table">
        <tr>
            <th><label for="_notice_urgency"><?php esc_html_e( 'Urgency', 'hidayah' ); ?></label></th>
            <td>
                <select id="_notice_urgency" name="_notice_urgency">
                    <option value="urgent" <?php selected( $urgency, 'urgent' ); ?>><?php esc_html_e( 'Urgent', 'hidayah' ); ?></option>
                    <option value="important" <?php selected( $urgency, 'important' ); ?>><?php esc_html_e( 'Important', 'hidayah' ); ?></option>
                    <option value="general" <?php selected( $urgency, 'general' ); ?>><?php esc_html_e( 'General', 'hidayah' ); ?></option>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="_notice_attachment"><?php esc_html_e( 'Attachment', 'hidayah' ); ?></label></th>
            <td>
                <div class="hidayah-media-row">
                    <input type="text" id="_notice_attachment" name="_notice_attachment" value="<?php echo esc_attr( $attach ); ?>" />
                    <button type="button" class="hidayah-upload-btn" data-target="_notice_attachment" data-library="application/pdf" data-store="id">📎 <?php esc_html_e( 'Select File', 'hidayah' ); ?></button>
                    <?php if ( $attach ) : ?>
                        <button type="button" class="hidayah-clear-btn" data-target="_notice_attachment">✕</button>
                    <?php endif; ?>
                </div>
                <p class="description"><?php esc_html_e( 'Stores attachment ID.', 'hidayah' ); ?></p>
            </td>
        </tr>
        <tr>
            <th><label for="_notice_expiry_date"><?php esc_html_e( 'Expiry Date', 'hidayah' ); ?></label></th>
            <td><input type="date" id="_notice_expiry_date" name="_notice_expiry_date" value="<?php echo esc_attr( $expiry ); ?>" /></td>
        </tr>
        <tr>
            <th><label for="_notice_important_dates"><?php esc_html_e( 'Important Dates', 'hidayah' ); ?></label></th>
            <td><textarea id="_notice_important_dates" name="_notice_important_dates"><?php echo esc_textarea( $dates ); ?></textarea></td>
        </tr>
    </table>
    <?php
}

function hidayah_notice_meta_save( $post_id ) {
    if ( ! isset( $_POST['hidayah_notice_nonce'] ) || ! wp_verify_nonce( $_POST['hidayah_notice_nonce'], 'hidayah_notice_save' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }
    if ( ! current_user_can( 'edit_post', $post_id ) ) { return; }

    $fields = array(
        '_notice_urgency'         => 'text',
        '_notice_attachment'      => 'int',
        '_notice_expiry_date'     => 'text',
        '_notice_important_dates' => 'textarea',
    );
    foreach ( $fields as $key => $type ) {
        if ( ! isset( $_POST[ $key ] ) ) {
            delete_post_meta( $post_id, $key );
            continue;
        }
        $value = $_POST[ $key ];
        if ( $type === 'int' ) {
            $value = absint( $value );
        } elseif ( $type === 'textarea' ) {
            $value = wp_kses_post( $value );
        } else {
            $value = sanitize_text_field( $value );
        }
        update_post_meta( $post_id, $key, $value );
    }
}
add_action( 'save_post_notice', 'hidayah_notice_meta_save' );


// ══════════════════════════════════════════════════════
// PHOTO GALLERY META BOX
// ══════════════════════════════════════════════════════
function hidayah_gallery_meta_box() {
    add_meta_box(
        'hidayah_gallery_details',
        __( 'Gallery Details', 'hidayah' ),
        'hidayah_gallery_meta_box_cb',
        'photo_gallery',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'hidayah_gallery_meta_box' );

function hidayah_gallery_meta_box_cb( $post ) {
    wp_nonce_field( 'hidayah_gallery_save', 'hidayah_gallery_nonce' );
    hidayah_meta_box_base_styles();

    $images = get_post_meta( $post->ID, '_gallery_images', true );
    $images = is_array( $images ) ? $images : array_filter( array_map( 'absint', explode( ',', (string) $images ) ) );
    $loc    = get_post_meta( $post->ID, '_gallery_location', true );
    $photog = get_post_meta( $post->ID, '_gallery_photographer', true );
    ?>
    <table class="hidayah-meta-table">
        <tr>
            <th><label for="_gallery_images"><?php esc_html_e( 'Gallery Images', 'hidayah' ); ?></label></th>
            <td>
                <div id="galleryImagesPreview" class="hidayah-preview-grid">
                    <?php foreach ( $images as $img_id ) : ?>
                        <img src="<?php echo esc_url( wp_get_attachment_image_url( $img_id, 'thumbnail' ) ); ?>" alt="" />
                    <?php endforeach; ?>
                </div>
                <input type="hidden" name="_gallery_images" id="galleryImagesInput" value="<?php echo esc_attr( implode( ',', $images ) ); ?>" />
                <button type="button" id="gallerySelectImages" class="hidayah-upload-btn" data-target="galleryImagesInput" data-preview="galleryImagesPreview" data-library="image" data-multiple="true">📷 <?php esc_html_e( 'Select Images', 'hidayah' ); ?></button>
            </td>
        </tr>
        <tr>
            <th><label for="_gallery_location"><?php esc_html_e( 'Location', 'hidayah' ); ?></label></th>
            <td><input type="text" id="_gallery_location" name="_gallery_location" value="<?php echo esc_attr( $loc ); ?>" /></td>
        </tr>
        <tr>
            <th><label for="_gallery_photographer"><?php esc_html_e( 'Photographer', 'hidayah' ); ?></label></th>
            <td><input type="text" id="_gallery_photographer" name="_gallery_photographer" value="<?php echo esc_attr( $photog ); ?>" /></td>
        </tr>
    </table>
    <?php
}

function hidayah_gallery_meta_save( $post_id ) {
    if ( ! isset( $_POST['hidayah_gallery_nonce'] ) || ! wp_verify_nonce( $_POST['hidayah_gallery_nonce'], 'hidayah_gallery_save' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }
    if ( ! current_user_can( 'edit_post', $post_id ) ) { return; }

    if ( isset( $_POST['_gallery_images'] ) ) {
        $raw = sanitize_text_field( $_POST['_gallery_images'] );
        $ids = array_filter( array_map( 'absint', explode( ',', $raw ) ) );
        if ( ! empty( $ids ) ) {
            update_post_meta( $post_id, '_gallery_images', $ids );
        } else {
            delete_post_meta( $post_id, '_gallery_images' );
        }
    }

    $fields = array(
        '_gallery_location'   => 'text',
        '_gallery_photographer' => 'text',
    );
    foreach ( $fields as $key => $type ) {
        if ( ! isset( $_POST[ $key ] ) ) {
            delete_post_meta( $post_id, $key );
            continue;
        }
        $value = sanitize_text_field( $_POST[ $key ] );
        update_post_meta( $post_id, $key, $value );
    }
}
add_action( 'save_post_photo_gallery', 'hidayah_gallery_meta_save' );


// ══════════════════════════════════════════════════════
// PROBONDHO META BOX
// ══════════════════════════════════════════════════════
function hidayah_probondho_meta_box() {
    add_meta_box(
        'hidayah_probondho_details',
        __( 'Article Details', 'hidayah' ),
        'hidayah_probondho_meta_box_cb',
        'probondho',
        'side',
        'default'
    );
}
add_action( 'add_meta_boxes', 'hidayah_probondho_meta_box' );

function hidayah_probondho_meta_box_cb( $post ) {
    wp_nonce_field( 'hidayah_probondho_save', 'hidayah_probondho_nonce' );
    hidayah_meta_box_base_styles();
    $reading_time = get_post_meta( $post->ID, '_reading_time', true );
    ?>
    <table class="hidayah-meta-table">
        <tr>
            <th><label for="_reading_time"><?php esc_html_e( 'Reading Time (minutes)', 'hidayah' ); ?></label></th>
            <td><input type="number" id="_reading_time" name="_reading_time" value="<?php echo esc_attr( $reading_time ); ?>" /></td>
        </tr>
    </table>
    <?php
}

function hidayah_probondho_meta_save( $post_id ) {
    if ( ! isset( $_POST['hidayah_probondho_nonce'] ) || ! wp_verify_nonce( $_POST['hidayah_probondho_nonce'], 'hidayah_probondho_save' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }
    if ( ! current_user_can( 'edit_post', $post_id ) ) { return; }

    if ( isset( $_POST['_reading_time'] ) ) {
        $value = sanitize_text_field( $_POST['_reading_time'] );
        update_post_meta( $post_id, '_reading_time', $value );
    } else {
        delete_post_meta( $post_id, '_reading_time' );
    }
}
add_action( 'save_post_probondho', 'hidayah_probondho_meta_save' );


// ══════════════════════════════════════════════════════
// VIDEO META BOX
// ══════════════════════════════════════════════════════
function hidayah_video_meta_box() {
    add_meta_box(
        'hidayah_video_details',
        __( 'Video Details', 'hidayah' ),
        'hidayah_video_meta_box_cb',
        'video',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'hidayah_video_meta_box' );

function hidayah_video_meta_box_cb( $post ) {
    wp_nonce_field( 'hidayah_video_save', 'hidayah_video_nonce' );
    hidayah_meta_box_base_styles();

    $yt_id     = get_post_meta( $post->ID, '_youtube_video_id', true );
    $yt_url    = get_post_meta( $post->ID, '_video_youtube_url', true );
    $direct    = get_post_meta( $post->ID, '_video_direct_url', true );
    $duration  = get_post_meta( $post->ID, '_video_duration', true );
    $location  = get_post_meta( $post->ID, '_video_location', true );
    $role      = get_post_meta( $post->ID, '_speaker_role', true );

    $preview_id = $yt_id;
    if ( ! $preview_id && $yt_url ) {
        preg_match( '/(?:v=|youtu\.be\/|embed\/)([a-zA-Z0-9_-]{11})/', $yt_url, $m );
        $preview_id = $m[1] ?? '';
    }
    ?>
    <table class="hidayah-meta-table">
        <tr>
            <th><label for="_youtube_video_id"><?php esc_html_e( 'YouTube ID', 'hidayah' ); ?></label></th>
            <td><input type="text" id="_youtube_video_id" name="_youtube_video_id" value="<?php echo esc_attr( $yt_id ); ?>" /></td>
        </tr>
        <tr>
            <th><label for="_video_youtube_url"><?php esc_html_e( 'YouTube URL', 'hidayah' ); ?></label></th>
            <td><input type="url" id="_video_youtube_url" name="_video_youtube_url" value="<?php echo esc_url( $yt_url ); ?>" /></td>
        </tr>
        <tr>
            <th><label for="_video_direct_url"><?php esc_html_e( 'Direct Video URL', 'hidayah' ); ?></label></th>
            <td><input type="url" id="_video_direct_url" name="_video_direct_url" value="<?php echo esc_url( $direct ); ?>" /></td>
        </tr>
        <tr>
            <th><label for="_video_duration"><?php esc_html_e( 'Video Duration', 'hidayah' ); ?></label></th>
            <td><input type="text" id="_video_duration" name="_video_duration" value="<?php echo esc_attr( $duration ); ?>" /></td>
        </tr>
        <tr>
            <th><label for="_video_location"><?php esc_html_e( 'Location', 'hidayah' ); ?></label></th>
            <td><input type="text" id="_video_location" name="_video_location" value="<?php echo esc_attr( $location ); ?>" /></td>
        </tr>
        <tr>
            <th><label for="_speaker_role"><?php esc_html_e( 'Speaker Role', 'hidayah' ); ?></label></th>
            <td><input type="text" id="_speaker_role" name="_speaker_role" value="<?php echo esc_attr( $role ); ?>" /></td>
        </tr>
        <?php if ( $preview_id ) : ?>
            <tr>
                <th><?php esc_html_e( 'Preview', 'hidayah' ); ?></th>
                <td>
                    <div style="margin-top: 6px;">
                        <iframe width="300" height="169" src="https://www.youtube.com/embed/<?php echo esc_attr( $preview_id ); ?>" frameborder="0" allowfullscreen></iframe>
                    </div>
                </td>
            </tr>
        <?php endif; ?>
    </table>
    <?php
}

function hidayah_video_meta_save( $post_id ) {
    if ( ! isset( $_POST['hidayah_video_nonce'] ) || ! wp_verify_nonce( $_POST['hidayah_video_nonce'], 'hidayah_video_save' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }
    if ( ! current_user_can( 'edit_post', $post_id ) ) { return; }

    $fields = array(
        '_youtube_video_id'  => 'text',
        '_video_youtube_url' => 'url',
        '_video_direct_url'  => 'url',
        '_video_duration'    => 'text',
        '_video_location'    => 'text',
        '_speaker_role'      => 'text',
    );
    foreach ( $fields as $key => $type ) {
        if ( ! isset( $_POST[ $key ] ) ) {
            delete_post_meta( $post_id, $key );
            continue;
        }
        $value = $_POST[ $key ];
        if ( $type === 'url' ) {
            $value = esc_url_raw( $value );
        } else {
            $value = sanitize_text_field( $value );
        }
        update_post_meta( $post_id, $key, $value );
    }

    $yt_url = isset( $_POST['_video_youtube_url'] ) ? esc_url_raw( $_POST['_video_youtube_url'] ) : '';
    if ( $yt_url ) {
        preg_match( '/(?:v=|youtu\.be\/|embed\/)([a-zA-Z0-9_-]{11})/', $yt_url, $m );
        if ( ! empty( $m[1] ) ) {
            update_post_meta( $post_id, '_youtube_video_id', $m[1] );
        }
    }
}
add_action( 'save_post_video', 'hidayah_video_meta_save' );


// ══════════════════════════════════════════════════════
// ENQUEUE MEDIA UPLOADER SCRIPT (admin only, audio CPT)
// ══════════════════════════════════════════════════════

function hidayah_enqueue_meta_box_scripts( $hook ) {
    global $post_type;
    $allowed = array( 'audio', 'book', 'dini_jiggasa', 'monthly_hd', 'notice', 'photo_gallery', 'probondho', 'video' );
    if ( ( $hook === 'post-new.php' || $hook === 'post.php' ) && in_array( $post_type, $allowed, true ) ) {
        wp_enqueue_media();
        wp_add_inline_script( 'jquery-core', '
            jQuery(function($) {
                function openMediaFrame(btn) {
                    var targetId  = $(btn).data("target");
                    var previewId = $(btn).data("preview");
                    var library   = $(btn).data("library") || null;
                    var store     = $(btn).data("store") || "url";
                    var multiple  = String($(btn).data("multiple")) === "true";

                    var frame = wp.media({
                        title: "Select or Upload File",
                        button: { text: "Use this file" },
                        library: library ? { type: library } : undefined,
                        multiple: multiple
                    });

                    frame.on("select", function() {
                        var selection = frame.state().get("selection");
                        if (multiple) {
                            var ids = [];
                            var urls = [];
                            selection.each(function(item) {
                                var data = item.toJSON();
                                ids.push(data.id);
                                urls.push(data.url);
                            });
                            $("#" + targetId).val(ids.join(","));
                            if (previewId) {
                                var $preview = $("#" + previewId);
                                $preview.empty();
                                urls.forEach(function(u) {
                                    $preview.append("<img src=\"" + u + "\" alt=\"\" />");
                                });
                                $preview.show();
                            }
                        } else {
                            var attachment = selection.first().toJSON();
                            var value = store === "id" ? attachment.id : attachment.url;
                            $("#" + targetId).val(value);
                            if (previewId) {
                                var $preview = $("#" + previewId);
                                if ($preview.find("audio").length) {
                                    $preview.find("audio").attr("src", attachment.url);
                                    $preview.show();
                                } else if ($preview.length) {
                                    $preview.empty().append("<img src=\"" + attachment.url + "\" alt=\"\" />").show();
                                }
                            }
                        }
                    });

                    frame.open();
                }

                $(".hidayah-upload-btn").on("click", function(e) {
                    e.preventDefault();
                    openMediaFrame(this);
                });

                $(".hidayah-clear-btn").on("click", function(e) {
                    e.preventDefault();
                    var targetId  = $(this).data("target");
                    var previewId = $(this).data("preview");
                    $("#" + targetId).val("");
                    if (previewId) {
                        var $preview = $("#" + previewId);
                        $preview.hide();
                        $preview.find("audio").attr("src", "");
                        $preview.empty();
                    }
                });

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
