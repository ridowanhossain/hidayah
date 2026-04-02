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
                        <strong style="color: #065f46;"><?php esc_html_e( 'YouTube video linked ✓', 'hidayah' ); ?></strong><br>
                        <small style="color: #6b7280;">Video ID: <?php echo esc_html( $yt_vid_id ); ?></small><br>
                        <small style="color: #6b7280;"><?php esc_html_e( 'Video will be hidden and play in audio mode on frontend', 'hidayah' ); ?></small>
                    </div>
                </div>
                <?php endif; ?>
                <p class="description"><?php esc_html_e( 'Providing a YouTube link will hide the video on the site and provide audio controls. If no MP3 is available, this will act as the primary player.', 'hidayah' ); ?></p>
            </td>
        </tr>



        <tr><td colspan="2" class="hidayah-meta-subheading">📋 <?php esc_html_e( 'Lecture Details', 'hidayah' ); ?></td></tr>



        <tr>
            <th><label for="_mahfil_location"><?php esc_html_e( 'Mahfil Location', 'hidayah' ); ?></label></th>
            <td>
                <input type="text" id="_mahfil_location" name="_mahfil_location"
                       value="<?php echo esc_attr( $location ); ?>"
                       placeholder="<?php esc_attr_e( 'e.g. Siddiqia Darbar Sharif, Dhaka', 'hidayah' ); ?>" />
            </td>
        </tr>

        <tr>
            <th><label for="_speaker_role"><?php esc_html_e( 'Speaker Role / Title', 'hidayah' ); ?></label></th>
            <td>
                <input type="text" id="_speaker_role" name="_speaker_role"
                       value="<?php echo esc_attr( $speaker_role ); ?>"
                       placeholder="<?php esc_attr_e( 'e.g. Murshid Qibla, Siddiqia Darbar Sharif', 'hidayah' ); ?>" />
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
        'product', // Only for products
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'hidayah_book_meta_box' );

function hidayah_book_meta_box_cb( $post ) {
    wp_nonce_field( 'hidayah_book_save', 'hidayah_book_nonce' );
    hidayah_meta_box_base_styles();

    $badge        = get_post_meta( $post->ID, '_book_badge', true );
    $isbn         = get_post_meta( $post->ID, '_book_isbn', true );
    $pages        = get_post_meta( $post->ID, '_book_pages', true );
    $binding      = get_post_meta( $post->ID, '_book_binding', true );
    $weight       = get_post_meta( $post->ID, '_book_weight', true );
    $edition      = get_post_meta( $post->ID, '_book_edition', true );
    $year         = get_post_meta( $post->ID, '_book_year', true );
    $publisher    = get_post_meta( $post->ID, '_book_publisher', true );
    $rating       = get_post_meta( $post->ID, '_rating', true );
    $rating_cnt   = get_post_meta( $post->ID, '_rating_count', true );
    $toc          = get_post_meta( $post->ID, '_book_toc', true );
    $sample_pdf   = get_post_meta( $post->ID, '_book_sample_pdf', true );
    ?>
    <table class="hidayah-meta-table">
        <tr>
            <th><label for="_book_badge"><?php esc_html_e( 'Badge Text', 'hidayah' ); ?></label></th>
            <td><input type="text" id="_book_badge" name="_book_badge" value="<?php echo esc_attr( $badge ); ?>" placeholder="<?php esc_html_e( 'e.g. Best Seller', 'hidayah' ); ?>" /></td>
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
            <th><label for="_rating"><?php esc_html_e( 'Rating (0-5)', 'hidayah' ); ?></label></th>
            <td><input type="number" step="0.1" min="0" max="5" id="_rating" name="_rating" value="<?php echo esc_attr( $rating ); ?>" /></td>
        </tr>
        <tr>
            <th><label for="_rating_count"><?php esc_html_e( 'Rating Count', 'hidayah' ); ?></label></th>
            <td><input type="number" id="_rating_count" name="_rating_count" value="<?php echo esc_attr( $rating_cnt ); ?>" /></td>
        </tr>
        <tr>
            <th><label for="_book_toc"><?php esc_html_e( 'Table of Contents', 'hidayah' ); ?></label></th>
            <td><textarea id="_book_toc" name="_book_toc"><?php echo esc_textarea( $toc ); ?></textarea></td>
        </tr>
        <tr>
            <th><label for="_book_sample_pdf"><?php esc_html_e( 'Sample PDF', 'hidayah' ); ?></label></th>
            <td>
                <div class="hidayah-media-row">
                    <input type="text" id="_book_sample_pdf" name="_book_sample_pdf" value="<?php echo esc_url( $sample_pdf ); ?>" placeholder="https://..." />
                    <button type="button" class="hidayah-upload-btn" data-target="_book_sample_pdf" data-library="application/pdf">📁 <?php esc_html_e( 'Select PDF', 'hidayah' ); ?></button>
                    <?php if ( $sample_pdf ) : ?>
                        <button type="button" class="hidayah-clear-btn" data-target="_book_sample_pdf">✕</button>
                    <?php endif; ?>
                </div>
                <p class="description"><?php esc_html_e( 'Upload or select a sample PDF for this book to show as a preview on the single page.', 'hidayah' ); ?></p>
            </td>
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

    $post_type = get_post_type( $post_id );
    if ( ! in_array( $post_type, array( 'product' ) ) ) {
        return;
    }

    $fields = array(
        '_book_badge'        => 'text',
        '_book_isbn'         => 'text',
        '_book_pages'        => 'number',
        '_book_binding'      => 'text',
        '_book_weight'       => 'number',
        '_book_edition'      => 'text',
        '_book_year'         => 'text',
        '_book_publisher'    => 'text',
        '_rating'            => 'text',
        '_rating_count'      => 'number',
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

    if ( isset( $_POST['_book_sample_pdf'] ) ) {
        update_post_meta( $post_id, '_book_sample_pdf', esc_url_raw( $_POST['_book_sample_pdf'] ) );
    }
}
add_action( 'save_post_product', 'hidayah_book_meta_save' );


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
function hidayah_monthly_magazine_meta_box() {
    add_meta_box(
        'hidayah_monthly_magazine_details',
        __( 'Monthly Issue Details', 'hidayah' ),
        'hidayah_monthly_magazine_meta_box_cb',
        'monthly_magazine',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'hidayah_monthly_magazine_meta_box' );

function hidayah_monthly_magazine_meta_box_cb( $post ) {
    wp_nonce_field( 'hidayah_monthly_magazine_save', 'hidayah_monthly_magazine_nonce' );
    hidayah_meta_box_base_styles();

    $vol       = get_post_meta( $post->ID, '_issue_vol', true );
    $num       = get_post_meta( $post->ID, '_issue_num', true );
    $month     = get_post_meta( $post->ID, '_issue_month', true );
    $special   = get_post_meta( $post->ID, '_is_special_issue', true );
    $editorial = get_post_meta( $post->ID, '_editorial_text', true );
    $toc_short = get_post_meta( $post->ID, '_issue_toc_short', true );
    $toc       = get_post_meta( $post->ID, '_magazine_toc', true );
    $summaries = get_post_meta( $post->ID, '_article_summaries', true );
    $downloads = get_post_meta( $post->ID, '_magazine_downloads', true );
    $topics    = get_post_meta( $post->ID, '_magazine_topics', true );
    ?>
    <table class="hidayah-meta-table">
        <tr><td colspan="2" class="hidayah-meta-subheading">📅 <?php esc_html_e( 'Issue Details', 'hidayah' ); ?></td></tr>
        <tr>
            <th><label for="_issue_vol"><?php esc_html_e( 'Volume', 'hidayah' ); ?></label></th>
            <td><input type="text" id="_issue_vol" name="_issue_vol" value="<?php echo esc_attr( $vol ); ?>" placeholder="e.g. 9" /></td>
        </tr>
        <tr>
            <th><label for="_issue_num"><?php esc_html_e( 'Issue Number', 'hidayah' ); ?></label></th>
            <td><input type="text" id="_issue_num" name="_issue_num" value="<?php echo esc_attr( $num ); ?>" placeholder="e.g. 04" /></td>
        </tr>
        <tr>
            <th><label for="_issue_month"><?php esc_html_e( 'Month & Year', 'hidayah' ); ?></label></th>
            <td><input type="text" id="_issue_month" name="_issue_month" value="<?php echo esc_attr( $month ); ?>" placeholder="e.g. Ramadan-Shawwal 1445" /></td>
        </tr>
        <tr>
            <th><label for="_magazine_pages"><?php esc_html_e( 'Page Count', 'hidayah' ); ?></label></th>
            <td><input type="text" id="_magazine_pages" name="_magazine_pages" value="<?php echo esc_attr( get_post_meta( $post->ID, '_magazine_pages', true ) ); ?>" placeholder="e.g. 64 Pages" /></td>
        </tr>
        <tr>
            <th><label for="_magazine_file_size"><?php esc_html_e( 'File Size', 'hidayah' ); ?></label></th>
            <td><input type="text" id="_magazine_file_size" name="_magazine_file_size" value="<?php echo esc_attr( get_post_meta( $post->ID, '_magazine_file_size', true ) ); ?>" placeholder="e.g. 4.2 MB" /></td>
        </tr>
        <tr>
            <th><label for="_magazine_downloads"><?php esc_html_e( 'Download Count', 'hidayah' ); ?></label></th>
            <td><input type="text" id="_magazine_downloads" name="_magazine_downloads" value="<?php echo esc_attr( $downloads ); ?>" placeholder="e.g. 1,234 downloads" /></td>
        </tr>
        <tr>
            <th><label for="_magazine_topics"><?php esc_html_e( 'Magazine Topics', 'hidayah' ); ?></label></th>
            <td><input type="text" id="_magazine_topics" name="_magazine_topics" value="<?php echo esc_attr( $topics ); ?>" placeholder="Comma separated: Tawheed, Salah, Tasawwuf" /></td>
        </tr>
        <tr>
            <th><label for="_magazine_pdf"><?php esc_html_e( 'Magazine PDF', 'hidayah' ); ?></label></th>
            <td>
                <div class="hidayah-media-row">
                    <input type="text" id="_magazine_pdf" name="_magazine_pdf" value="<?php echo esc_url( get_post_meta( $post->ID, '_magazine_pdf', true ) ); ?>" placeholder="https://..." />
                    <button type="button" class="hidayah-upload-btn" data-target="_magazine_pdf" data-library="application/pdf">📁 <?php esc_html_e( 'Select PDF', 'hidayah' ); ?></button>
                </div>
            </td>
        </tr>
        <tr>
            <th><label for="_is_special_issue"><?php esc_html_e( 'Special Issue', 'hidayah' ); ?></label></th>
            <td><input type="checkbox" id="_is_special_issue" name="_is_special_issue" value="1" <?php checked( $special, '1' ); ?> /></td>
        </tr>
        <tr>
            <th><label for="_issue_toc_short"><?php esc_html_e( 'Short TOC (for Homepage)', 'hidayah' ); ?></label></th>
            <td><textarea id="_issue_toc_short" name="_issue_toc_short" placeholder="bullet points for homepage..."><?php echo esc_textarea( $toc_short ); ?></textarea></td>
        </tr>
        <tr>
            <th><label for="_magazine_toc"><?php esc_html_e( 'Full Table of Contents', 'hidayah' ); ?></label></th>
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

function hidayah_monthly_magazine_meta_save( $post_id ) {
    if ( ! isset( $_POST['hidayah_monthly_magazine_nonce'] ) || ! wp_verify_nonce( $_POST['hidayah_monthly_magazine_nonce'], 'hidayah_monthly_magazine_save' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }
    if ( ! current_user_can( 'edit_post', $post_id ) ) { return; }

    $fields = array(
        '_issue_vol'        => 'text',
        '_issue_num'        => 'text',
        '_issue_month'      => 'text',
        '_magazine_pages'   => 'text',
        '_magazine_file_size' => 'text',
        '_magazine_pdf'     => 'url',
        '_issue_toc_short'  => 'textarea',
        '_magazine_toc'     => 'textarea',
        '_editorial_text'   => 'textarea',
        '_article_summaries'=> 'textarea',
        '_magazine_downloads' => 'text',
        '_magazine_topics'    => 'text',
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
add_action( 'save_post_monthly_magazine', 'hidayah_monthly_magazine_meta_save' );


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
                    <?php 
                    $attach_val = $attach;
                    if ( is_numeric( $attach ) ) {
                        $attach_val = wp_get_attachment_url( $attach );
                    }
                    ?>
                    <input type="text" id="_notice_attachment" name="_notice_attachment" value="<?php echo esc_attr( $attach_val ); ?>" />
                    <button type="button" class="hidayah-upload-btn" data-target="_notice_attachment" data-library="application/pdf" data-store="url">📎 <?php esc_html_e( 'Select File', 'hidayah' ); ?></button>
                    <?php if ( $attach ) : ?>
                        <button type="button" class="hidayah-clear-btn" data-target="_notice_attachment">✕</button>
                    <?php endif; ?>
                </div>
                <p class="description"><?php esc_html_e( 'Stores attachment URL.', 'hidayah' ); ?></p>
            </td>
        </tr>
        <tr>
            <th><label for="_notice_expiry_date"><?php esc_html_e( 'Expiry Date', 'hidayah' ); ?></label></th>
            <td><input type="date" id="_notice_expiry_date" name="_notice_expiry_date" value="<?php echo esc_attr( $expiry ); ?>" /></td>
        </tr>
        <tr>
            <th><label for="_notice_important_dates"><?php esc_html_e( 'Important Information', 'hidayah' ); ?></label></th>
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
        <tr>
            <th><label for="_probondho_references"><?php esc_html_e( 'References', 'hidayah' ); ?></label></th>
            <td><textarea id="_probondho_references" name="_probondho_references" placeholder="<?php esc_attr_e( 'Enter one reference per line...', 'hidayah' ); ?>"><?php echo esc_textarea( get_post_meta( $post->ID, '_probondho_references', true ) ); ?></textarea></td>
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

    if ( isset( $_POST['_probondho_references'] ) ) {
        update_post_meta( $post_id, '_probondho_references', wp_kses_post( $_POST['_probondho_references'] ) );
    } else {
        delete_post_meta( $post_id, '_probondho_references' );
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
            <th><label for="_video_youtube_url"><?php esc_html_e( 'YouTube URL', 'hidayah' ); ?></label></th>
            <td><input type="url" id="_video_youtube_url" name="_video_youtube_url" value="<?php echo esc_url( $yt_url ); ?>" /></td>
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
        '_video_youtube_url' => 'url',
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

    $old_yt_url = get_post_meta( $post_id, '_video_youtube_url', true );
    $yt_url     = isset( $_POST['_video_youtube_url'] ) ? esc_url_raw( $_POST['_video_youtube_url'] ) : '';

    // If URL changed, clear cached duration
    if ( $yt_url !== $old_yt_url ) {
        delete_post_meta( $post_id, '_video_duration' );
    }

    preg_match( '/(?:v=|youtu\.be\/|embed\/)([a-zA-Z0-9_-]{11})/', $yt_url, $m );
    if ( ! empty( $m[1] ) ) {
        update_post_meta( $post_id, '_youtube_video_id', $m[1] );
    } else {
        delete_post_meta( $post_id, '_youtube_video_id' );
    }
}
add_action( 'save_post_video', 'hidayah_video_meta_save' );


// ══════════════════════════════════════════════════════
// ENQUEUE MEDIA UPLOADER SCRIPT (admin only, audio CPT)
// ══════════════════════════════════════════════════════

function hidayah_enqueue_meta_box_scripts( $hook ) {
    global $post_type, $taxonomy;
    $allowed_pts = array( 'audio', 'product', 'dini_jiggasa', 'monthly_hd', 'notice', 'photo_gallery', 'probondho', 'video' );
    $allowed_tax = array( 'speaker', 'topic', 'book_author' );

    $is_post_edit = ( $hook === 'post-new.php' || $hook === 'post.php' ) && in_array( $post_type, $allowed_pts, true );
    $is_tax_edit  = ( $hook === 'edit-tags.php' || $hook === 'term.php' ) && in_array( $taxonomy, $allowed_tax, true );

    if ( $is_post_edit || $is_tax_edit ) {
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


// ══════════════════════════════════════════════════════
// SPEAKER TAXONOMY FIELDS
// ══════════════════════════════════════════════════════

// Add field to 'Add New Speaker' screen
function hidayah_add_speaker_image_field() {
    hidayah_meta_box_base_styles();
    ?>
    <div class="form-field term-group">
        <label for="speaker_image"><?php _e( 'Speaker Image', 'hidayah' ); ?></label>
        <div class="hidayah-media-row">
            <input type="url" id="speaker_image" name="speaker_image" value="" />
            <button type="button" class="hidayah-upload-btn" data-target="speaker_image" data-preview="speaker-image-preview" data-library="image">📁 <?php _e( 'Upload', 'hidayah' ); ?></button>
        </div>
        <div class="hidayah-preview-grid" id="speaker-image-preview" style="display:none;"></div>
        <p><?php _e( 'Featured image for the speaker.', 'hidayah' ); ?></p>
    </div>
    <?php
}
add_action( 'speaker_add_form_fields', 'hidayah_add_speaker_image_field', 10 );

// Add field to 'Edit Speaker' screen
function hidayah_edit_speaker_image_field( $term ) {
    hidayah_meta_box_base_styles();
    $image_url = get_term_meta( $term->term_id, 'speaker_image', true );
    ?>
    <tr class="form-field term-group-wrap">
        <th scope="row"><label for="speaker_image"><?php _e( 'Speaker Image', 'hidayah' ); ?></label></th>
        <td>
            <div class="hidayah-media-row">
                <input type="url" id="speaker_image" name="speaker_image" value="<?php echo esc_url( $image_url ); ?>" />
                <button type="button" class="hidayah-upload-btn" data-target="speaker_image" data-preview="speaker-image-preview" data-library="image">📁 <?php _e( 'Upload', 'hidayah' ); ?></button>
                <?php if ( $image_url ) : ?>
                    <button type="button" class="hidayah-clear-btn" data-target="speaker_image" data-preview="speaker-image-preview">✕</button>
                <?php endif; ?>
            </div>
            <div class="hidayah-preview-grid" id="speaker-image-preview" style="<?php echo $image_url ? 'display:flex;' : 'display:none;'; ?>">
                <?php if ( $image_url ) : ?>
                    <img src="<?php echo esc_url( $image_url ); ?>" alt="" />
                <?php endif; ?>
            </div>
            <p class="description"><?php _e( 'Featured image for the speaker.', 'hidayah' ); ?></p>
        </td>
    </tr>
    <?php
}
add_action( 'speaker_edit_form_fields', 'hidayah_edit_speaker_image_field', 10 );

// Save taxonomy fields
function hidayah_save_speaker_image_field( $term_id ) {
    if ( isset( $_POST['speaker_image'] ) ) {
        update_term_meta( $term_id, 'speaker_image', esc_url_raw( $_POST['speaker_image'] ) );
    }
}
add_action( 'created_speaker', 'hidayah_save_speaker_image_field', 10 );
add_action( 'edited_speaker',  'hidayah_save_speaker_image_field', 10 );

// Show image in Speakers list table
function hidayah_speaker_columns( $columns ) {
    $new_columns = array();
    foreach ( $columns as $key => $value ) {
        if ( $key === 'name' ) {
            $new_columns['speaker_image'] = __( 'Image', 'hidayah' );
        }
        $new_columns[$key] = $value;
    }
    return $new_columns;
}
add_filter( 'manage_edit-speaker_columns', 'hidayah_speaker_columns' );

function hidayah_speaker_column_content( $content, $column_name, $term_id ) {
    if ( $column_name === 'speaker_image' ) {
        $image_url = get_term_meta( $term_id, 'speaker_image', true );
        if ( $image_url ) {
            $content = '<img src="' . esc_url( $image_url ) . '" style="width:50px; height:50px; object-fit:cover; border-radius:4px;" />';
        } else {
            $content = '<span class="dashicons dashicons-admin-users" style="font-size:40px; color:#ccc; width:40px; height:40px;"></span>';
        }
    }
    return $content;
}
add_filter( 'manage_speaker_custom_column', 'hidayah_speaker_column_content', 10, 3 );


/**
 * ── Uttordata Taxonomy Custom Fields ───────────────────
 */

/**
 * Add fields to 'Add New Uttordata' screen
 */
function hidayah_uttordata_add_meta_fields() {
    ?>
    <div class="form-field">
        <label for="uttordata_title"><?php _e( 'Responder Title', 'hidayah' ); ?></label>
        <input type="text" name="uttordata_title" id="uttordata_title" value="">
        <p class="description"><?php _e( 'e.g. Mufti, Maulana, Shaykh etc.', 'hidayah' ); ?></p>
    </div>
    <div class="form-field">
        <label for="uttordata_image"><?php _e( 'Responder Image', 'hidayah' ); ?></label>
        <div class="hidayah-media-row">
            <input type="url" name="uttordata_image" id="uttordata_image" value="">
            <button type="button" class="hidayah-upload-btn" data-target="uttordata_image" data-preview="uttordata-image-preview" data-library="image">📁 <?php _e( 'Upload', 'hidayah' ); ?></button>
        </div>
        <div class="hidayah-preview-grid" id="uttordata-image-preview" style="display:none; margin-top: 10px;"></div>
    </div>
    <?php
}
add_action( 'uttordata_add_form_fields', 'hidayah_uttordata_add_meta_fields', 10 );

/**
 * Add fields to 'Edit Uttordata' screen
 */
function hidayah_uttordata_edit_meta_fields( $term ) {
    $uttordata_title = get_term_meta( $term->term_id, 'uttordata_title', true );
    $uttordata_image = get_term_meta( $term->term_id, 'uttordata_image', true );
    ?>
    <tr class="form-field">
        <th scope="row"><label for="uttordata_title"><?php _e( 'Responder Title', 'hidayah' ); ?></label></th>
        <td>
            <input type="text" name="uttordata_title" id="uttordata_title" value="<?php echo esc_attr( $uttordata_title ); ?>">
            <p class="description"><?php _e( 'e.g. Mufti, Maulana, Shaykh etc.', 'hidayah' ); ?></p>
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row"><label for="uttordata_image"><?php _e( 'Responder Image', 'hidayah' ); ?></label></th>
        <td>
            <div class="hidayah-media-row">
                <input type="url" name="uttordata_image" id="uttordata_image" value="<?php echo esc_url( $uttordata_image ); ?>">
                <button type="button" class="hidayah-upload-btn" data-target="uttordata_image" data-preview="uttordata-image-preview" data-library="image">📁 <?php _e( 'Upload', 'hidayah' ); ?></button>
                <?php if ( $uttordata_image ) : ?>
                    <button type="button" class="hidayah-clear-btn" data-target="uttordata_image" data-preview="uttordata-image-preview">✕</button>
                <?php endif; ?>
            </div>
            <div class="hidayah-preview-grid" id="uttordata-image-preview" style="<?php echo $uttordata_image ? 'display:flex;' : 'display:none;'; ?> margin-top: 10px;">
                <?php if ( $uttordata_image ) : ?>
                    <img src="<?php echo esc_url( $uttordata_image ); ?>" style="max-width:150px; border-radius: 5px;" />
                <?php endif; ?>
            </div>
        </td>
    </tr>
    <?php
}
add_action( 'uttordata_edit_form_fields', 'hidayah_uttordata_edit_meta_fields', 10 );

/**
 * Save Uttordata meta fields
 */
function hidayah_save_uttordata_meta( $term_id ) {
    if ( isset( $_POST['uttordata_title'] ) ) {
        update_term_meta( $term_id, 'uttordata_title', sanitize_text_field( $_POST['uttordata_title'] ) );
    }
    if ( isset( $_POST['uttordata_image'] ) ) {
        update_term_meta( $term_id, 'uttordata_image', esc_url_raw( $_POST['uttordata_image'] ) );
    }
}
add_action( 'edited_uttordata', 'hidayah_save_uttordata_meta', 10 );
add_action( 'create_uttordata', 'hidayah_save_uttordata_meta', 10 );

