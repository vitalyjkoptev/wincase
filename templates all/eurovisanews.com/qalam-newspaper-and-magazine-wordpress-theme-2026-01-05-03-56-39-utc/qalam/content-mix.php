<?php
/**
 * Content Loop for archives - Grid Style.
 */

if ( ! have_posts() ) {
	qalam_no_posts();
}
$schema =  get_theme_mod( 'schema', 0 );
$count = 1;

while ( have_posts() ) :
	the_post();

	// Set post count for use in get_template_part( 'formats/format.php' )
	set_query_var( 'qlm_post_count', $count );

	// Get post label
    $post_label = get_post_meta( get_the_id(), 'post_label', true );
    $allowed_html = array(
        'i' => array( 'class' => array()  ),
        'span' => array( 'class' => array() )
    );

	if ( $count == 1 ) {
		?>
		<div class="qlm-row hero-section">
			<div class="qlm-col w-67">
				<article<?php if ( $schema ) { echo ' itemscope="" itemtype="https://schema.org/BlogPosting" itemprop="blogPost"'; } ?> id="post-<?php the_ID();?>" <?php post_class( 'hero-post' ); ?>>
				<div class="entry-content">
					<?php
					/**
					 * Hook: qlm_before_mix_hero_title
					 *
					 * @hooked none
					 */
					do_action( 'qlm_before_mix_hero_title' );

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
					 * Hook: qlm_after_mix_hero_content
					 *
					 * @hooked qlm_after_grid_content_meta
					 */
					do_action( 'qlm_after_mix_hero_content' );
					?>
				</div><!-- /.entry-content -->
				<?php
				get_template_part( 'formats/format', get_post_format() );
				?>
			</article><!-- #post-<?php the_ID();?> -->
		</div><!-- /.w-67 -->
		<?php
		}

		if ( $count == 2 ) {
			?>
			<div class="qlm-col w-33 narrow-single-module">
			<?php
			}
			if ( $count > 1 && $count <= 3 ) {
				?>
				<article<?php if ( $schema ) { echo ' itemscope="" itemtype="https://schema.org/BlogPosting" itemprop="blogPost"'; } ?> id="post-<?php the_ID();?>" <?php post_class(); ?>>
					<div class="entry-content">
						<?php
						/**
						 * Hook: qlm_before_mix_sub_title
						 *
						 * @hooked none
						 */
						do_action( 'qlm_before_mix_sub_title' );

						the_title(
							sprintf( '<h2%1$s class="entry-title"><a href="%2$s" title="%3$s">%4$s',
								$schema ? ' itemprop="headline mainEntityOfPage"' : '',
								esc_url( get_permalink() ),
								the_title_attribute( array( 'echo' => false ) ),
								isset( $post_label ) && '' !== $post_label ? wp_kses( $post_label, $allowed_html ) : ''
							),
							'</a></h2>'
						);

						/**
						 * Hook: qlm_after_mix_sub_content
						 *
						 * @hooked none
						 */
						do_action( 'qlm_after_mix_sub_content' );
						?>
					</div><!-- /.entry-content -->
					<?php
					get_template_part( 'formats/format', get_post_format() );
					?>
				</article><!-- #post-<?php the_ID();?> -->
			<?php
			if ( $count == 3 ) {
			?>
				</div><!-- /.w-33 -->
			</div><!-- /.qlm-row -->
			<?php
			}
		}

		if ( $count > 3 )  {
			if ( $count == 4 ) {
				echo '<div class="qlm-list">';
			}
			?>
			<article<?php if ( $schema ) { echo ' itemscope="" itemtype="https://schema.org/BlogPosting" itemprop="blogPost"'; } ?> id="post-<?php the_ID();?>" <?php post_class(); ?>>
				<div class="entry-content">
					<?php
					/**
					 * Hook: qlm_before_mix_list_title
					 *
					 * @hooked none
					 */
					do_action( 'qlm_before_mix_list_title' );

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
					 * Hook: qlm_after_mix_list_content
					 *
					 * @hooked qlm_after_grid_content_meta
					 */
					do_action( 'qlm_after_mix_list_content' );
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
		}
	$count++;
endwhile; // End the loop

echo '</div><!-- /.qlm-list -->';

// Previous/next page navigation.
the_posts_pagination( array(
	'prev_text'          => esc_html__( 'Previous page', 'qalam' ),
	'next_text'          => esc_html__( 'Next page', 'qalam' ),
	'before_page_number' => '<span class="meta-nav screen-reader-text">' . esc_html__( 'Page', 'qalam' ) . ' </span>',
) );