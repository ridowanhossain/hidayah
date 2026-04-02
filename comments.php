<?php
/**
 * Custom Comments Template
 * Premium styled comment section for all single pages.
 *
 * @package Hidayah
 */

if ( post_password_required() ) {
    return;
}

$is_product_review = ( get_post_type() === 'product' );
$rating_stats      = array(
    'average' => 0,
    'count'   => 0,
);

if ( $is_product_review ) {
    $product_comments = get_comments(
        array(
            'post_id' => get_the_ID(),
            'status'  => 'approve',
        )
    );

    $rating_total = 0;
    $rating_count = 0;

    foreach ( $product_comments as $product_comment ) {
        $rating = (int) get_comment_meta( $product_comment->comment_ID, 'rating', true );
        if ( $rating >= 1 && $rating <= 5 ) {
            $rating_total += $rating;
            $rating_count++;
        }
    }

    if ( $rating_count > 0 ) {
        $rating_stats['average'] = round( $rating_total / $rating_count, 1 );
        $rating_stats['count']   = $rating_count;
    }
}
?>

<div class="hidayah-comments-section" id="comments">

    <?php if ( have_comments() ) : ?>
    <div class="hc-comments-wrap">
        <h3 class="hc-title">
            <span class="material-symbols-outlined">forum</span>
            <?php
            $comments_count = get_comments_number();
            printf(
                esc_html( _n( '%s Comment', '%s Comments', $comments_count, 'hidayah' ) ),
                '<span class="hc-count">' . number_format_i18n( $comments_count ) . '</span>'
            );
            ?>
        </h3>

        <?php if ( $is_product_review ) : ?>
            <div class="hc-rating-summary">
                <div class="hc-rating-stars" aria-label="<?php esc_attr_e( 'Average Rating', 'hidayah' ); ?>">
                    <?php
                    for ( $i = 1; $i <= 5; $i++ ) :
                        if ( $rating_stats['average'] >= $i ) {
                            echo '<span class="material-symbols-outlined hc-rating-star filled">star</span>';
                        } elseif ( $rating_stats['average'] > ( $i - 1 ) ) {
                            echo '<span class="material-symbols-outlined hc-rating-star half">star_half</span>';
                        } else {
                            echo '<span class="material-symbols-outlined hc-rating-star empty">star</span>';
                        }
                    endfor;
                    ?>
                </div>
                <span class="hc-rating-value">
                    <?php
                    $avg_display = number_format_i18n( $rating_stats['average'], 1 );
                    echo esc_html( $avg_display );
                    ?>
                </span>
                <span class="hc-rating-note">
                    <?php
                    $count_display = number_format_i18n( $rating_stats['count'] );
                    printf(
                        esc_html__( '%s Reviews', 'hidayah' ),
                        esc_html( $count_display )
                    );
                    ?>
                </span>
            </div>
        <?php endif; ?>

        <ol class="hc-list">
            <?php
            wp_list_comments( array(
                'style'       => 'ol',
                'short_ping'  => true,
                'avatar_size' => 48,
                'callback'    => 'hidayah_comment_callback',
            ) );
            ?>
        </ol>

        <?php
        the_comments_pagination( array(
            'prev_text' => '<span class="material-symbols-outlined">arrow_back</span> ' . __( 'Previous', 'hidayah' ),
            'next_text' => __( 'Next', 'hidayah' ) . ' <span class="material-symbols-outlined">arrow_forward</span>',
            'class'     => 'hc-pagination',
        ) );
        ?>
    </div>
    <?php endif; ?>

    <?php if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
        <p class="hc-closed"><?php _e( 'Comments are closed.', 'hidayah' ); ?></p>
    <?php endif; ?>

    <?php if ( comments_open() ) : ?>
    <div class="hc-form-wrap" id="respond">
        <h3 class="hc-form-title">
            <span class="material-symbols-outlined">edit</span>
            <?php
            $commenter = wp_get_current_commenter();
            $user      = wp_get_current_user();

            if ( $user->exists() ) {
                _e( 'Write your comment', 'hidayah' );
            } else {
                _e( 'Leave a comment', 'hidayah' );
            }
            ?>
        </h3>

        <?php
        $replying_to = get_comment( absint( get_query_var('replytocom') ) );
        if ( $replying_to ) : ?>
            <p class="hc-replying-to">
                <span class="material-symbols-outlined">reply</span>
                <?php printf( __( 'Replying to %s', 'hidayah' ), '<strong>' . esc_html( get_comment_author( $replying_to ) ) . '</strong>' ); ?>
                <a href="<?php echo esc_url( get_permalink() ); ?>#respond" class="hc-cancel-reply" id="cancel-comment-reply-link">
                    <?php _e( 'Cancel', 'hidayah' ); ?>
                </a>
            </p>
        <?php endif; ?>

        <?php if ( $user->exists() ) : ?>
            <p class="hc-logged-in-as">
                <span class="material-symbols-outlined">account_circle</span>
                <?php printf(
                    __( 'Logged in as %s. <a href="%s">Log out?</a>', 'hidayah' ),
                    '<strong>' . esc_html( $user->display_name ) . '</strong>',
                    esc_url( wp_logout_url( get_permalink() ) )
                ); ?>
            </p>
        <?php endif; ?>

        <form action="<?php echo esc_url( site_url( '/wp-comments-post.php' ) ); ?>"
              class="hc-form"
              id="commentform"
              method="post"
              novalidate>

            <?php if ( ! $user->exists() ) : ?>
            <div class="hc-form-grid">
                <div class="hc-field-group">
                    <label class="hc-label" for="author">
                        <?php _e( 'Name', 'hidayah' ); ?>
                        <span class="hc-required">*</span>
                    </label>
                    <div class="hc-input-wrap">
                        <span class="material-symbols-outlined hc-input-icon">person</span>
                        <input
                            class="hc-input"
                            id="author"
                            name="author"
                            placeholder="<?php _e( 'Your Name', 'hidayah' ); ?>"
                            required
                            type="text"
                            value="<?php echo esc_attr( $commenter['comment_author'] ); ?>"
                        />
                    </div>
                </div>

                <div class="hc-field-group">
                    <label class="hc-label" for="email">
                        <?php _e( 'Email', 'hidayah' ); ?>
                        <span class="hc-required">*</span>
                    </label>
                    <div class="hc-input-wrap">
                        <span class="material-symbols-outlined hc-input-icon">email</span>
                        <input
                            class="hc-input"
                            id="email"
                            name="email"
                            placeholder="<?php _e( 'Your email (will not be published)', 'hidayah' ); ?>"
                            required
                            type="email"
                            value="<?php echo esc_attr( $commenter['comment_author_email'] ); ?>"
                        />
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <div class="hc-field-group">
                <?php if ( $is_product_review ) : ?>
                <label class="hc-label">
                    <?php _e( 'Rating', 'hidayah' ); ?>
                    <span class="hc-required">*</span>
                </label>
                <div aria-label="<?php esc_attr_e( 'Select Rating', 'hidayah' ); ?>" class="sb-star-picker hc-star-picker" id="sbStarPicker" role="group">
                    <input type="hidden" name="rating" id="hc-rating" value="0" required="required" />
                    <button aria-label="1" class="sb-star-pick" data-val="1" type="button"><span class="material-symbols-outlined">star</span></button>
                    <button aria-label="2" class="sb-star-pick" data-val="2" type="button"><span class="material-symbols-outlined">star</span></button>
                    <button aria-label="3" class="sb-star-pick" data-val="3" type="button"><span class="material-symbols-outlined">star</span></button>
                    <button aria-label="4" class="sb-star-pick" data-val="4" type="button"><span class="material-symbols-outlined">star</span></button>
                    <button aria-label="5" class="sb-star-pick" data-val="5" type="button"><span class="material-symbols-outlined">star</span></button>
                </div>
                <div id="rating-error" class="hc-rating-error"><?php _e( 'Please provide a rating', 'hidayah' ); ?></div>
                <?php endif; ?>

                <label class="hc-label" for="comment">
                    <?php _e( 'Comment', 'hidayah' ); ?>
                    <span class="hc-required">*</span>
                </label>
                <textarea
                    class="hc-textarea"
                    cols="45"
                    id="comment"
                    name="comment"
                    placeholder="<?php _e( 'Write your comment here...', 'hidayah' ); ?>"
                    required
                    rows="5"
                ></textarea>
            </div>

            <?php if ( has_action( 'set_comment_cookies', 'wp_set_comment_cookies' ) && get_option( 'show_comments_cookies_opt_in' ) ) : ?>
            <div class="hc-cookie-consent">
                <label class="hc-checkbox-label">
                    <input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes" />
                    <?php _e( 'Save my name and email in this browser for the next time I comment.', 'hidayah' ); ?>
                </label>
            </div>
            <?php endif; ?>

            <?php comment_id_fields(); ?>
            <?php if ( ! $is_product_review ) do_action( 'comment_form', get_the_ID() ); ?>

            <button class="hc-submit-btn" id="submit" name="submit" type="submit">
                <span class="material-symbols-outlined">send</span>
                <?php _e( 'Send Comment', 'hidayah' ); ?>
            </button>

        </form>
    </div>
    <?php endif; ?>

</div>

<?php
/**
 * Custom comment callback for themed comment bubbles.
 */
if ( ! function_exists( 'hidayah_comment_callback' ) ) :
function hidayah_comment_callback( $comment, $args, $depth ) {
    $tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
    $add_below = 'comment';
    ?>
    <<?php echo $tag; ?> <?php comment_class( 'hc-comment-item' ); ?> id="comment-<?php comment_ID(); ?>">

        <div class="hc-comment-avatar">
            <?php echo get_avatar( $comment, 48 ); ?>
        </div>

        <div class="hc-comment-body">
            <div class="hc-comment-meta">
                <span class="hc-comment-author"><?php comment_author(); ?></span>
                <?php
                if ( get_post_type( $comment->comment_post_ID ) === 'product' ) :
                    $comment_rating = (int) get_comment_meta( $comment->comment_ID, 'rating', true );
                    if ( $comment_rating >= 1 && $comment_rating <= 5 ) :
                ?>
                    <span class="hc-comment-rating" aria-label="<?php esc_attr_e( 'Rating', 'hidayah' ); ?>">
                        <?php
                        for ( $i = 1; $i <= 5; $i++ ) {
                            $star_class = $i <= $comment_rating ? 'filled' : 'empty';
                            echo '<span class="material-symbols-outlined hc-rating-star ' . esc_attr( $star_class ) . '">star</span>';
                        }
                        ?>
                    </span>
                <?php
                    endif;
                endif;
                ?>
                <span class="hc-comment-date">
                    <span class="material-symbols-outlined">schedule</span>
                    <time datetime="<?php comment_date( 'c' ); ?>"><?php comment_date( get_option('date_format') ); ?></time>
                </span>
                <?php if ( $comment->comment_approved == '0' ) : ?>
                    <span class="hc-awaiting-moderation">
                        <span class="material-symbols-outlined">pending</span>
                        <?php _e( 'Awaiting moderation', 'hidayah' ); ?>
                    </span>
                <?php endif; ?>
            </div>

            <div class="hc-comment-text">
                <?php comment_text(); ?>
            </div>

            <div class="hc-comment-actions">
                <?php
                comment_reply_link( array_merge( $args, array(
                    'add_below' => $add_below,
                    'depth'     => $depth,
                    'max_depth' => $args['max_depth'],
                    'before'    => '<span class="hc-reply-link">',
                    'after'     => '</span>',
                ) ) );
                ?>
                <?php edit_comment_link( __( 'Edit', 'hidayah' ), '<span class="hc-edit-link"><span class="material-symbols-outlined">edit</span>', '</span>' ); ?>
            </div>
        </div>

    </<?php echo $tag; ?>>
    <?php
}
endif;
