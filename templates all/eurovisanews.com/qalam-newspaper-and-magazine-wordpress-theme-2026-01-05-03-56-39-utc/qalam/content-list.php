<?php
/**
* Content Loop for archives - List Style.
*/

if ( ! have_posts() ) {
	qalam_no_posts();
}

$schema =  get_theme_mod( 'schema', 0 );
?>
<div class="qlm-list">
	<?php
	while ( have_posts()) :
		the_post();
		?>
		<article<?php if ( $schema ) { echo ' itemscope="" itemtype="https://schema.org/BlogPosting" itemprop="blogPost"'; } ?> id="post-<?php the_ID();?>" <?php post_class(); ?>>
			<div class="entry-content">
				<?php
				/**
				 * Hook: qlm_before_list_title
				 *
				 * @hooked none
				 */
				do_action( 'qlm_before_list_title' );

				// Get post label
				$post_label = get_post_meta( get_the_id(), 'post_label', true );
				$allowed_html = array(
				    'i' => array( 'class' => array()  ),
				    'span' => array( 'class' => array() )
				);

				the_title(
					sprintf( '<h2%1$s class="entry-title"><a href="%2$s" title="%3$s">%4$s',
						$schema ? ' itemprop="headline mainEntityOfPage"' : '',
						esc_url( get_permalink() ),
						the_title_attribute( array( 'echo' => false ) ),
						isset( $post_label ) && '' !== $post_label ? wp_kses( $post_label, $allowed_html ) : ''
					),
					'</a></h2>'
				);

				if (  get_theme_mod( 'show_excerpt', 'true' ) ) { ?>
					<p<?php if ( $schema ) { echo ' itemprop="text"'; } ?> class="post-excerpt">
					<?php echo wp_trim_words( get_the_excerpt(), get_theme_mod( 'excerpt_length', 20 ) );
					?>
					</p>
				<?php
				}

			/**
			 * Hook: qlm_after_list_content
			 *
			 * @hooked qlm_after_grid_content_meta
			 */
			do_action( 'qlm_after_list_content' );
			?>
		</div><!-- /.entry-content -->
		<?php
		if ( 'video' == get_post_format() && get_theme_mod( 'show_embed', 0 ) ) {
			echo '<div class="post-img">';
			get_template_part( 'formats/format', get_post_format() );
			echo '</div>';
		}
		else {
			get_template_part( 'formats/format', get_post_format() );
		}
		?>
	</article><!-- #post-<?php the_ID();?> -->
<?php
endwhile; // End the loop
?>
</div><!-- /.qlm-list -->
<?php

// Previous/next page navigation.
the_posts_pagination( array(
	'prev_text'          => esc_html__( 'Previous page', 'qalam' ),
	'next_text'          => esc_html__( 'Next page', 'qalam' ),
	'before_page_number' => '<span class="meta-nav screen-reader-text">' . esc_html__( 'Page', 'qalam' ) . ' </span>',
) );