<?php
/*
 * Comment display template of Javapaper.
 */
if ( post_password_required() )
	return;
?>

<div id="comments" class="comments-area">
  <?php // You can start editing here -- including this comment! ?>
  <?php if ( have_comments() ) : ?>
  <h5 class="comments-title">
    <?php
				printf( _n( 'One comment on &ldquo;%2$s&rdquo;', '%1$s comments on &ldquo;%2$s&rdquo;', get_comments_number(), 'javapaper' ),
					number_format_i18n( get_comments_number() ), '<span>' . get_the_title() . '</span>' );
			?>
  </h5>
  <ul class="commentlist">
    <?php wp_list_comments( array( 'callback' => 'javapaper_comment', 'style' => 'ol' ) ); ?>
  </ul>
  <!-- .commentlist -->
  <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
  <nav id="comment-nav-below" class="navigation" role="navigation">
    <h1 class="assistive-text section-heading">
      <?php _e( 'Comment navigation', 'javapaper' ); ?>
    </h1>
    <div class="nav-previous">
      <?php previous_comments_link( __( '&larr; Older Comments', 'javapaper' ) ); ?>
    </div>
    <div class="nav-next">
      <?php next_comments_link( __( 'Newer Comments &rarr;', 'javapaper' ) ); ?>
    </div>
  </nav>
  <?php endif; // check for comment navigation ?>
  <?php
		/* If there are no comments and comments are closed, let's leave a note.
		 * But we only want the note on posts and pages that had comments in the first place.
		 */
		if ( ! comments_open() && get_comments_number() ) : ?>
  <p class="nocomments">
    <?php _e( 'Comments are closed.' , 'javapaper' ); ?>
  </p>
  <?php endif; ?>
  <?php endif; // have_comments() ?>
  <!-- You can start editing here. -->
  <?php if ( have_comments() ) : ?>
  <?php else : // this is displayed if there are no comments so far ?>
  <?php if ( comments_open() ) : ?>
  <!-- If comments are open, but there are no comments. -->
  <?php else : // comments are closed ?>
  <!-- If comments are closed. -->
  <!--p class="nocomments"><?php esc_html_e( 'Comments are closed.', 'javapaper' ); ?></p-->
  <?php endif; ?>
  <?php endif; ?>
  <?php if ( comments_open() ) : ?>
  <section id="respond" class="respond-form">
    <h5 id="comment-form-title">
      <?php comment_form_title( esc_attr__( 'Leave a Reply', 'javapaper' ), esc_attr__( 'Leave a Reply to %s', 'javapaper' )); ?>
    </h5>
    <div id="cancel-comment-reply">
      <p class="small">
        <?php cancel_comment_reply_link(); ?>
      </p>
    </div>
    <?php if ( get_option('comment_registration') && !is_user_logged_in() ) : ?>
    <div class="alert alert-info"> <?php printf( esc_attr__( 'You must be %1$slogged in%2$s to post a comment.', 'javapaper' ), '', '' ); ?> </div>
    <?php else : ?>
    <form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
      <?php if ( is_user_logged_in() ) : ?>
      <div class="comments-logged-in-as">
        <?php esc_attr_e( 'Logged in as', 'javapaper' ); ?>
        <a href="<?php echo get_option( 'siteurl' ); ?>/wp-admin/profile.php"><?php echo esc_attr ($user_identity); ?></a> <a href="<?php echo wp_logout_url( get_permalink() ); ?>" title="<?php esc_attr_e( 'Log out of this account', 'javapaper' ); ?>">
        <?php esc_attr_e( 'Log out', 'javapaper' ); ?>
        <?php esc_attr_e( '&raquo;', 'javapaper' ); ?>
        </a></div>
      <?php else : ?>
      <div class="comments-author">
        <input type="text" name="author" id="author" value="<?php echo esc_attr($comment_author); ?>" placeholder="<?php esc_attr_e( 'Your Name*', 'javapaper' ); ?>" tabindex="1" <?php if ($req) echo "aria-required='true'"; ?> />
      </div>
      <div class="comments-email">
        <input type="email" name="email" id="email" value="<?php echo esc_attr($comment_author); ?>" placeholder="<?php esc_attr_e( 'Your E-Mail*', 'javapaper' ); ?>" tabindex="2" <?php if ($req) echo "aria-required='true'"; ?> />
      </div>
      <div class="comments-url">
        <input type="url" name="url" id="url" value="<?php echo esc_attr($comment_author_url); ?>" placeholder="<?php esc_attr_e( 'Got a website?', 'javapaper' ); ?>" tabindex="3" />
      </div>
      <?php endif; ?>
      <p>
        <textarea name="comment" id="comment" placeholder="<?php esc_attr_e( 'Your Comment here...', 'javapaper' ); ?>"  rows="5" tabindex="4"></textarea>
      </p>
      <input name="submit" type="submit" id="submit" class="primary" tabindex="5" value="<?php esc_attr_e( 'Submit', 'javapaper' ); ?>" />
      <?php comment_id_fields(); ?>
      <!--<div class="alert alert-info">
		<p id="allowed_tags" class="small"><strong>XHTML:</strong> <?php esc_attr_e( 'You can use these tags', 'javapaper' ); ?>: <code><?php echo allowed_tags(); ?></code></p>
	</div>-->
      <?php do_action( 'javapaper_comment_form()', $post->ID ); ?>
    </form>
    <?php endif; // If registration required and not logged in ?>
  </section>
  <?php endif; // if you delete this the sky will fall on your head ?>
</div>
<!-- #comments .comments-area -->
