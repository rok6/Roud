<?php
if (post_password_required()) {
    return;
} else {
    global $RTags;
}
?>

<?php if ( comments_open() && post_type_supports( get_post_type(), 'comments' ) ) : ?>

<div id="post-comments" class="comments-area">

	<?php
    if (have_comments()) :
  ?>

		<h2 class="comments-title">
			<?php
        $comments_number = get_comments_number();
        printf(_x('&ldquo; %2$s &rdquo; へ %1$s 件のコメント', 'comments title'),
          number_format_i18n($comments_number),
          get_the_title()
        );
      ?>
		</h2>

		<ol class="comment-list">
			<?php
        wp_list_comments(array(
            'callback'  => array( $RTags, 'costom_comment_list' ),
        ));
      ?>
		</ol>

		<?php
        the_comments_pagination(array(
            'prev_text' => '<span class="screen-reader-text">' . __('Previous') . '</span>',
            'next_text' => '<span class="screen-reader-text">' . __('Next') . '</span>',
        ));

      endif;

      comment_form();

endif; ?>

</div><!-- #comments -->
