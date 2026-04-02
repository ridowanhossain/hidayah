<?php
/**
 * Custom Comments Template for Single Book
 */

if ( post_password_required() ) {
    return;
}
?>

<div id="sb-reviews" class="sb-section sb-reviews-section">
    <h2 class="sb-section-title">
        <?php _e( 'Reader Reviews & Ratings', 'hidayah' ); ?>
    </h2>

    <?php 
    $comments = get_comments(array(
        'post_id' => get_the_ID(),
        'status' => 'approve'
    ));

    // Calculate rating averages
    $total_rating = 0;
    $rating_counts = array(1=>0, 2=>0, 3=>0, 4=>0, 5=>0);
    $valid_rating_count = 0;

    foreach($comments as $c) {
        $r = intval(get_comment_meta($c->comment_ID, 'rating', true));
        if ($r >= 1 && $r <= 5) {
            $total_rating += $r;
            $rating_counts[$r]++;
            $valid_rating_count++;
        }
    }

    $avg_rating = $valid_rating_count > 0 ? round($total_rating / $valid_rating_count, 1) : 0;
    ?>

    <?php if ( $comments ) : ?>
        <!-- Rating Overview -->
        <div class="sb-rating-overview">
            <div class="sb-rating-big">
                <span class="sb-rating-big-number"><?php echo $avg_rating; ?></span>
                <div class="sb-rating-stars">
                    <?php 
                    for ($i=1; $i<=5; $i++) {
                        if ($avg_rating >= $i) echo '<span class="material-symbols-outlined star filled" style="color:var(--gold-accent)">star</span>';
                        elseif ($avg_rating > ($i-1)) echo '<span class="material-symbols-outlined star half" style="color:var(--gold-accent)">star_half</span>';
                        else echo '<span class="material-symbols-outlined star empty" style="color:#d1d5db">star_border</span>';
                    }
                    ?>
                </div>
                <span class="sb-rating-total"><?php printf( _n( 'Based on %s review', 'Based on %s reviews', $valid_rating_count, 'hidayah' ), $valid_rating_count ); ?></span>
            </div>
            
            <div class="sb-rating-bars">
                <?php for($i=5; $i>=1; $i--) : 
                    $pct = $valid_rating_count > 0 ? round(($rating_counts[$i] / $valid_rating_count) * 100) : 0;
                ?>
                <div class="sb-rating-bar-row">
                    <span><?php echo $i; ?> ★</span>
                    <div class="sb-bar-track">
                        <div class="sb-bar-fill" style="width:<?php echo esc_attr($pct); ?>%"></div>
                    </div>
                    <span><?php echo $rating_counts[$i]; ?></span>
                </div>
                <?php endfor; ?>
            </div>
        </div>

        <div class="sb-review-list">
            <?php foreach($comments as $comment) : 
                $rating = get_comment_meta($comment->comment_ID, 'rating', true);
            ?>
            <div class="sb-review-card" id="comment-<?php echo esc_attr($comment->comment_ID); ?>">
                <div class="sb-review-header">
                    <div class="sb-reviewer-avatar">
                        <?php 
                        $author_name = get_comment_author($comment);
                        echo esc_html(mb_substr($author_name, 0, 1)); 
                        ?>
                    </div>
                    <div class="sb-reviewer-info">
                        <strong><?php echo esc_html($author_name); ?></strong>
                        <div class="sb-review-stars">
                            <?php 
                            $r = intval($rating);
                            for($i=1; $i<=5; $i++) {
                                if($i <= $r) echo '<span class="material-symbols-outlined star filled" style="color:var(--gold-accent)">star</span>';
                                else echo '<span class="material-symbols-outlined star empty" style="color:#d1d5db">star_border</span>';
                            }
                            ?>
                        </div>
                    </div>
                    <span class="sb-review-date"><?php echo get_comment_date('j F Y', $comment); ?></span>
                </div>
                <div class="sb-review-text"><?php echo wpautop(get_comment_text($comment)); ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php
    if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
        ?>
        <p class="no-comments"><?php _e( 'Reviews are closed.', 'hidayah' ); ?></p>
    <?php endif; ?>

    <?php
    $commenter = wp_get_current_commenter();
    
    $args = array(
        'class_container' => 'sb-section sb-review-form-container',
        'title_reply_before' => '<h2 class="sb-section-title">',
        'title_reply_after'  => '</h2>',
        'title_reply'        => __( 'Write your review', 'hidayah' ),
        'class_form'         => 'sb-review-form',
        'submit_button'      => '<button name="%1$s" type="submit" id="%2$s" class="sb-submit-btn %3$s" value="%4$s"><span class="material-symbols-outlined">send</span> %4$s</button>',
        'submit_field'       => '%1$s %2$s',
        'label_submit'       => __( 'Submit Review', 'hidayah' ),
        'comment_field'      => '<div class="sb-form-group">
            <label class="sb-form-label" for="comment">' . _x( 'Comment', 'noun', 'hidayah' ) . ' <span class="required">*</span></label>
            <textarea id="comment" name="comment" class="sb-form-textarea" placeholder="' . esc_attr__( 'Write your review...', 'hidayah' ) . '" required="required" rows="5"></textarea>
        </div>',
        'fields'             => array(
            'author' => '<div class="sb-form-row"><div class="sb-form-group">
                            <label class="sb-form-label" for="author">' . __( 'Your Name', 'hidayah' ) . ' <span class="required">*</span></label>
                            <input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" class="sb-form-input" placeholder="' . esc_attr__( 'Enter Name', 'hidayah' ) . '" required="required" />
                        </div>',
            'email'  => '<div class="sb-form-group">
                            <label class="sb-form-label" for="email">' . __( 'Email', 'hidayah' ) . '</label>
                            <input id="email" name="email" type="email" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" class="sb-form-input" placeholder="' . esc_attr__( 'Email (will not be published)', 'hidayah' ) . '" />
                        </div></div>',
        ),
    );
    
    $rating_html = '
    <div class="sb-form-group">
        <label class="sb-form-label">' . __( 'Give Rating', 'hidayah' ) . ' <span class="required">*</span></label>
        <div aria-label="' . esc_attr__( 'Rating', 'hidayah' ) . '" class="sb-star-picker" id="sbStarPicker" role="group">
            <input type="hidden" name="rating" id="rating" value="0" required="required">
            <button aria-label="1" class="sb-star-pick" data-val="1" type="button"><span class="material-symbols-outlined">star</span></button>
            <button aria-label="2" class="sb-star-pick" data-val="2" type="button"><span class="material-symbols-outlined">star</span></button>
            <button aria-label="3" class="sb-star-pick" data-val="3" type="button"><span class="material-symbols-outlined">star</span></button>
            <button aria-label="4" class="sb-star-pick" data-val="4" type="button"><span class="material-symbols-outlined">star</span></button>
            <button aria-label="5" class="sb-star-pick" data-val="5" type="button"><span class="material-symbols-outlined">star</span></button>
        </div>
        <div id="rating-error" style="color:#b91c1c; font-size:12px; display:none; margin-top:5px;">' . __( 'Please provide a rating', 'hidayah' ) . '</div>
    </div>';

    $args['comment_field'] = $rating_html . $args['comment_field'];

    comment_form( $args );
    ?>
</div>
