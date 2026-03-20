<?php
/**
* The template for displaying comments
*
* @package Qalam
* @since 1.0.0
* @version 2.4.0
*/

/*
* If the current post is protected by a password and
* the visitor has not yet entered the password we will
* return early without loading the comments.
*/
if ( post_password_required() ) {
	return;
}
?>
<div id="comments" class="comments-area">
	<?php
	if ( have_comments() ) : ?>
		<h3 class="comments-title section-title">
			<?php
			$comments_number = get_comments_number();
			if ( '1' === $comments_number ) {
				/* translators: %s: post title */
				esc_html_e( 'One Comment', 'qalam' );
			} else {
				printf(
					/* translators: 1: number of comments, 2: post title */
					_nx(
						'%1$s Comment',
						'%1$s Comments',
						$comments_number,
						'number of comments',
						'qalam'
					),
					number_format_i18n( $comments_number )
				);
			}
			?>
		</h3>

		<ol class="comment-list">
			<?php
			wp_list_comments( array(
				'avatar_size' => 80,
				'style'       => 'ol',
				'short_ping'  => true,
				'reply_text'  => esc_html__( 'Reply', 'qalam' )
			) );
			?>
		</ol>

		<?php the_comments_pagination( array(
			'prev_text' => '<span class="nav-prev">' . esc_html__( 'Previous', 'qalam' ) . '</span>',
			'next_text' => '<span class="nav-next">' . esc_html__( 'Next', 'qalam' ) . '</span>'
		) );

endif; // Check for have_comments().

// If comments are closed and there are comments, let's leave a little note, shall we?
if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>

	<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'qalam' ); ?></p>
<?php
endif;

comment_form();
?>

</div><!-- #comments -->