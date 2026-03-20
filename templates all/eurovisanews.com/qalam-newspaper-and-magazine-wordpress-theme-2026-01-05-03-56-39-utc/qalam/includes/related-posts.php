<?php
/**
 * Related posts
 *
 * @package Qalam
 * @since 1.0.0
 * @version 2.4.0
 */

$args = NULL;
$related_posts_query = NULL;
$schema =  get_theme_mod( 'schema', 0 );
global $post;

	$categories = get_the_category( $post->ID );
	$tags = wp_get_post_tags( $post->ID );

		$category_ids = $tag_ids = array();
		if ( isset( $categories ) && is_array( $categories ) ) {
			foreach( $categories as $cat ) {
				$category_ids[] = $cat->term_id;
			}
		}

		if ( isset( $categories ) && is_array( $categories ) ) {
			foreach ( $tags as $tag ) {
				$tag_ids[] = $tag->term_id;
			}
		}

		$args = array(
			'category__in' 			=> $category_ids,
			'post__not_in' 			=> array( $post->ID ),
			'tag__in' 				=> $tag_ids,
			'posts_per_page'		=> get_theme_mod( 'related_num', 3 ),
			'orderby'				=> apply_filters( 'qalam_rp_order', 'rand' )
		);


$related_posts_query = new WP_Query( $args );

if ( $related_posts_query->have_posts() ) : ?>

    <div class="related-posts-container">

		<?php
		$related_title = get_theme_mod( 'related_title', __( 'Related Stories', 'qalam' ) );
		if ( '' != $related_title ) {
			printf( '<h3 class="related-posts-title section-title">%s</h3>', esc_html( $related_title ) );
		}
		?>
        <div class="related-posts clearfix columns-<?php echo get_theme_mod( 'related_col', 3 ); ?>">
        <?php
        while( $related_posts_query->have_posts() ) :
            $related_posts_query->the_post();
            $thumbnail = '';
			?>
            <article<?php if ( $schema ) { echo ' itemscope="" itemtype="https://schema.org/BlogPosting" itemprop="blogPost"'; } ?> id="post-<?php the_ID();?>" <?php post_class(); ?>>
                <?php

                get_template_part( 'formats/rp-format', get_post_format() );

                ?>
                <div class="entry-content">
                    <?php
                	the_title(
						sprintf( '<h3%1$s class="entry-title"><a href="%2$s" title="%3$s">',
							$schema ? ' itemprop="headline mainEntityOfPage"' : '',
							esc_url( get_permalink() ),
							the_title_attribute( array( 'echo' => false ) )
						),
						'</a></h3>'
					);
                	?>
                </div><!-- /.entry-content -->
            </article>
        <?php
        endwhile; // while have posts ?>
	</div><!-- .related-posts -->
</div><!-- .related-posts-container -->
<?php endif; // if have posts
wp_reset_postdata();