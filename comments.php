<?php
/**
 * The template for displaying comments
 *
 * Fully integrated with Hoi An Travel Design System.
 * Supports WordPress comments hierarchy, pagination, reply flow, and modern responsive form styling.
 *
 * @package hoiantravel
 */

if (post_password_required()) {
    return;
}
?>

<div id="comments" class="post-comments-section">
    <?php if (have_comments()): ?>
        <div class="comments-header">
            <h3 class="comments-title widget-title">
                <?php
                $comments_number = get_comments_number();
                if ('1' === $comments_number) {
                    esc_html_e('1 Comment', 'hoiantravel');
                } else {
                    printf(
                        esc_html(_nx('%1$s Comment', '%1$s Comments', $comments_number, 'comments title', 'hoiantravel')),
                        number_format_i18n($comments_number)
                    );
                }
                ?>
            </h3>
        </div>

        <ol class="comment-list">
            <?php
            wp_list_comments(array(
                'style'       => 'ol',
                'short_ping'  => true,
                'avatar_size' => 48,
            ));
            ?>
        </ol>

        <?php if (get_comment_pages_count() > 1 && get_option('page_comments')): ?>
            <nav class="comment-navigation" role="navigation" aria-label="<?php esc_attr_e('Comments Navigation', 'hoiantravel'); ?>">
                <div class="nav-links hel-pagination">
                    <?php paginate_comments_links(array('prev_text' => '&laquo; Prev', 'next_text' => 'Next &raquo;')); ?>
                </div>
            </nav>
        <?php endif; ?>
    <?php endif; ?>

    <?php if (!comments_open() && get_comments_number() && post_type_supports(get_post_type(), 'comments')): ?>
        <p class="no-comments"><?php esc_html_e('Comments are closed.', 'hoiantravel'); ?></p>
    <?php endif; ?>

    <?php
    $commenter = wp_get_current_commenter();
    $req       = get_option('require_name_email');
    $aria_req  = ($req ? " aria-required='true'" : '');

    $fields = array(
        'author' => '<div class="comment-form-group comment-form-author">' .
            '<label for="author" class="form-label">' . esc_html__('Name', 'hoiantravel') . ($req ? ' <span class="required text-danger">*</span>' : '') . '</label>' .
            '<input id="author" name="author" type="text" placeholder="' . esc_attr__('Your name', 'hoiantravel') . '" value="' . esc_attr($commenter['comment_author']) . '" size="30"' . $aria_req . ' class="form-control" />' .
            '</div>',
        'email'  => '<div class="comment-form-group comment-form-email">' .
            '<label for="email" class="form-label">' . esc_html__('Email', 'hoiantravel') . ($req ? ' <span class="required text-danger">*</span>' : '') . '</label>' .
            '<input id="email" name="email" type="email" placeholder="' . esc_attr__('Your email', 'hoiantravel') . '" value="' . esc_attr($commenter['comment_author_email']) . '" size="30"' . $aria_req . ' class="form-control" />' .
            '</div>',
        'url'    => '<div class="comment-form-group comment-form-url">' .
            '<label for="url" class="form-label">' . esc_html__('Website', 'hoiantravel') . '</label>' .
            '<input id="url" name="url" type="url" placeholder="' . esc_attr__('Website URL (optional)', 'hoiantravel') . '" value="' . esc_attr($commenter['comment_author_url']) . '" size="30" class="form-control" />' .
            '</div>',
    );

    $comments_args = array(
        'fields'        => $fields,
        'comment_field' => '<div class="comment-form-group comment-form-comment">' .
            '<label for="comment" class="form-label">' . esc_html__('Comment', 'hoiantravel') . ' <span class="required text-danger">*</span></label>' .
            '<textarea id="comment" name="comment" placeholder="' . esc_attr__('Share your thoughts, experiences, or questions about Hoi An...', 'hoiantravel') . '" cols="45" rows="5" aria-required="true" class="form-control"></textarea>' .
            '</div>',
        'class_container'      => 'comment-respond ha-comment-respond',
        'class_form'           => 'comment-form ha-comment-form',
        'class_submit'         => 'submit ha-button',
        'title_reply_before'   => '<div class="comment-reply-header"><h3 id="reply-title" class="comment-reply-title widget-title">',
        'title_reply_after'    => '</h3></div>',
        'title_reply'          => esc_html__('Leave a Comment', 'hoiantravel'),
        'title_reply_to'       => esc_html__('Leave a Reply to %s', 'hoiantravel'),
        'cancel_reply_link'    => esc_html__('Cancel reply', 'hoiantravel'),
        'label_submit'         => esc_html__('Post Comment', 'hoiantravel'),
        'submit_button'        => '<button type="submit" name="%1$s" id="%2$s" class="%3$s"><span>%4$s</span>' . ha_svg_icon('send') . '</button>',
        'submit_field'         => '<div class="form-submit">%1$s %2$s</div>',
        'comment_notes_before' => '<p class="comment-notes text-muted"><span id="email-notes">' . esc_html__('Your email address will not be published. Required fields are marked *', 'hoiantravel') . '</span></p>',
    );

    comment_form($comments_args);
    ?>
</div>
