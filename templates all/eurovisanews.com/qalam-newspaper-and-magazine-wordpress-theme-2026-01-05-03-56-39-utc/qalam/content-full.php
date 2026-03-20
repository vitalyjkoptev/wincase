<?php
/**
 * Content Loop for archives - Classic Style.
 */

if ( ! have_posts() ) {
    qalam_no_posts();
}

$schema =  get_theme_mod( 'schema', 0 );
$social = true;

while ( have_posts() ) :
    the_post(); ?>
    <article<?php if ( $schema ) { echo ' itemscope="" itemtype="https://schema.org/BlogPosting" itemprop="blogPost"'; } ?> id="post-<?php the_ID(); ?>" <?php post_class( 'entry-full' ); ?>>

        <header class="entry-header">
            <?php

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

            if ( has_excerpt() ) {
                echo '<h2 itemprop="description" class="entry-sub-title">' . get_the_excerpt() . '</h2>';
            }

            /**
             * Hook: qlm_after_full_content_title
             *
             * @hooked qlm_after_full_content_title_meta - 10
             */
            do_action( 'qlm_after_full_content_title' );

            get_template_part( 'formats/format' );
            ?>

        </header><!-- .entry-header -->

        <div class="entry-content article-body"<?php if ( $schema ) { echo ' itemprop="articleBody"'; } ?>>
            <?php
            /* translators: %s: Name of current post */
            the_content( sprintf(
                __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'qalam' ),
                get_the_title()
            ) );

            wp_link_pages( array(
                'before'      => '<div class="page-links">' . esc_html__( 'Pages:', 'qalam' ),
                'after'       => '</div>',
                'link_before' => '<span class="page-number">',
                'link_after'  => '</span>',
            ) );
            ?>
        </div><!-- .entry-content -->
    </article><!-- #post-## -->

<?php endwhile; // End the loop

// Previous/next page navigation.
the_posts_pagination( array(
    'prev_text'          => esc_html__( 'Previous page', 'qalam' ),
    'next_text'          => esc_html__( 'Next page', 'qalam' ),
    'before_page_number' => '<span class="meta-nav screen-reader-text">' . esc_html__( 'Page', 'qalam' ) . ' </span>',
) );