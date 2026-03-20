<?php
/**
* The template for displaying all single posts
*
* @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
*
* @package WordPress
* @subpackage Qalam
* @since 1.0
* @version 1.0
*/

get_header();

$sng_header = get_theme_mod( 'sng_header', 'inline' );
$schema =  get_theme_mod( 'schema', 0 );

$content_class = '';

if ( is_active_sidebar( 'default-sidebar' ) ) {
    $content_class .= ' has-sba';
}

if ( is_active_sidebar( 'sidebar-b' ) ) {
    $content_class .= ' has-sbb';
}

if ( ! is_active_sidebar( 'default-sidebar' ) && ! ( in_array( get_theme_mod( 'sb_pos', 'ca' ), array( 'cba', 'acb', 'bca', 'abc' ) ) && is_active_sidebar( 'sidebar-b' ) ) ) {
    $content_class = ' full-width';
}

if ( 'full' == $sng_header ) {
	?>
	<header class="entry-header qlm-col w-100">
		<?php

		// Get post label
		$post_label = get_post_meta( get_queried_object_id(), 'post_label', true );
		$allowed_html = array(
		    'i' => array( 'class' => array()  ),
		    'span' => array( 'class' => array() )
		);

		the_title( '<h1 class="entry-title single-post-title">' . ( isset( $post_label ) && '' !== $post_label ? wp_kses( $post_label, $allowed_html ) : '' ), '</h1>' );
		if ( has_excerpt() ) {
			echo '<h2 class="entry-sub-title">' . esc_html( get_the_excerpt() ) . '</h2>';
		}

		/**
		 * Hook: qlm_single_content_after
		 *
		 * @hooked qlm_meta_output - 10
		 */
		do_action( 'qlm_entry_sub_title_after' );
		?>
	</header><!-- .entry-header -->
	<?php
}
?>
<div id="primary" class="site-content<?php echo esc_attr( $content_class ); ?>">
	<div class="primary-row">
		<div id="content" role="main">
			<?php
			$show_feat = get_theme_mod( 'show_feat', 1 );

			// Start the Loop
			while ( have_posts() ) : the_post();

				/**
				 * Hook: qlm_single_content_before
				 *
				 * @hooked qlm_widget_area_before_post
				 */
				do_action( 'qlm_single_content_before' );
				?>
				<article<?php if ( $schema ) { echo ' itemscope="" itemtype="https://schema.org/BlogPosting" itemprop="blogPost"'; } ?> id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<?php
				if ( 'full' == $sng_header ) {
					// Show hidden meta for heading itemprop
					if ( $schema ) {
						echo '<meta' . ( $schema ? ' itemprop="headline mainEntityOfPage"' : '' ) . ' content="' . the_title_attribute( array( 'echo' => false ) ) . '">';
						if ( has_excerpt() ) {
							echo '<meta itemprop="description" content="' . esc_attr( get_the_excerpt() ) . '">';
						}
					}
					if ( $show_feat ) {
						get_template_part( 'formats/format' );
					}
				} else {
					?>
					<header class="entry-header">
						<?php
						
						// Get post label
						$post_label = get_post_meta( get_the_id(), 'post_label', true );
						$allowed_html = array(
						    'i' => array( 'class' => array()  ),
						    'span' => array( 'class' => array() )
						);						
							
						the_title( '<h1' . ( $schema ? ' itemprop="headline mainEntityOfPage"' : '' ) . ' class="entry-title single-post-title">' . ( isset( $post_label ) && '' !== $post_label ? wp_kses( $post_label, $allowed_html ) : '' ), '</h1>' );
						if ( has_excerpt() ) {
							echo '<h2 itemprop="description" class="entry-sub-title">' . esc_html( get_the_excerpt() ) . '</h2>';
						}

						/**
						 * Hook: qlm_single_content_after
						 *
						 * @hooked qlm_meta_output - 10
						 */
						do_action( 'qlm_entry_sub_title_after' );

						if ( $show_feat ) {
							get_template_part( 'formats/format' );
						}
						?>
					</header><!-- .entry-header -->
					<?php
				}
				?>
				<div class="entry-content article-body"<?php if ( $schema ) { echo ' itemprop="articleBody"'; } ?>>
					<?php
					the_content();

					wp_link_pages( array(
						'before'      => '<div class="page-links">' . esc_html__( 'Pages:', 'qalam' ),
						'after'       => '</div>',
						'link_before' => '<span class="page-number">',
						'link_after'  => '</span>',
					) );
					?>
				</div><!-- .entry-content -->
			</article><!-- #post-## -->

			<?php
			/**
			 * Hook: qlm_single_content_after
			 *
			 * @hooked qlm_widget_area_after_post - 10
			 * @hooked qlm_tag_list - 15
			 * @hooked qlm_author_box - 20
			 * @hooked qlm_related_posts - 25
			 * @hooked qlm_comments_single - 30
			 * @hooked qlm_posts_navigation - 35
			 */
			do_action( 'qlm_single_content_after' );

		endwhile;
		?>
	</div><!-- #content -->
	<?php
	qalam_sidebar_b();
	?>
</div><!-- .primary-row -->
</div><!-- #primary -->
<?php
if ( 'no-sb' != get_theme_mod( 'sb_pos_sng', 'ca' ) ) {
	get_sidebar();
}
get_footer();