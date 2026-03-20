<?php
/*
 * Content display template
 */
?>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  <div class="entry-headersearch">
    <div class="category1-time"> <a href="<?php  echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" rel="author"> <?php printf( __( ' %s', 'javapaper' ), get_the_author() ); ?></a><span>
      <?php esc_attr_e(" on ","javapaper"); ?>
      </span>
      <?php the_time('M j, Y') ?>
    <div class="module9-view">
	<span class="view2"><?php if (function_exists("javapaper_get_post_view")) { ?>
   <?php echo javapaper_get_post_view(); ?><?php } else { ?><?php } ?></span>
    <?php if (function_exists("javapaper_reading_time")) { ?>
	<?php echo javapaper_reading_time(get_the_ID()); ?><?php } else { ?> <?php } ?>
	</div>
    </div>
    <div class="search-titlebig">
      <?php if ( is_single() ) : ?>
      <h2><a href="<?php  the_permalink(); ?>">
        <?php  the_title(); ?>
        </a></h2>
      <?php else : ?>
      <h2><a href="<?php  the_permalink(); ?>">
        <?php  the_title(); ?>
        </a></h2>
      <?php endif; // is_single() ?>
    </div>
  </div>
  <!-- .entry-header -->
  <?php if ( is_home() && ( get_theme_mod( 'javapaper_one_full_post' , '1' ) == '1' ) ) : // Check Live Customizer for Full/Excerpts Post Settings ?>
  <?php javapaper_one_excerpts() ?>
  <?php elseif( is_search() || is_category() || is_tag() || is_author() || is_archive()  ): ?>
  <?php javapaper_one_excerpts() ?>
  <?php else : ?>
  <div class="entry-content ctest">
    <?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'javapaper' ) ); ?>
    <?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'javapaper' ), 'after' => '</div>' ) ); ?>
  </div>
  <!-- .entry-content -->
  <?php endif; ?>
</div>
<!-- #post -->
<footer class="entry-meta">
  <?php if ( is_home() && ( get_theme_mod( 'javapaper_one_tag_home' , '1' ) == '1' ) ) : ?>
  <span>
  <?php the_tags(); ?>
  </span>
  <?php elseif( !is_home() ): ?>
  <span>
  <?php the_tags(); ?>
  </span>
  <?php endif; ?>
  <?php edit_post_link( __( 'Edit', 'javapaper' ), '<span class="edit-link">', '</span>' ); ?>
  <?php if ( is_singular() && get_the_author_meta( 'description' ) && is_multi_author() ) : // If a user has filled out their description and this is a multi-author blog, show a bio on their entries. ?>
  <?php endif; ?>
</footer>
<!-- .entry-meta -->
