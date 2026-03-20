<?php
/*
 * Content display template for category default
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  <?php if ( is_sticky() && is_home() && ! is_paged() ) : // for top sticky post with blue border ?>
  
  <div class="sticky-text">
    <?php _e( 'FEATURE', 'javapaper' ); ?>
  </div>
  <?php endif; ?>
  <header class="entry-header">
    <div class="category1-time">
      <?php the_category(', '); ?>
      <div class="subdate-singlepost"> <?php echo get_the_date(' F d, Y ');?> </div>
      <div class="module9-view"> 
	<span class="view2"><?php if (function_exists("javapaper_get_post_view")) { ?>
   <?php echo javapaper_get_post_view(); ?><?php } else { ?><?php } ?></span>
    <?php if (function_exists("javapaper_reading_time")) { ?>
	<?php echo javapaper_reading_time(get_the_ID()); ?><?php } else { ?> <?php } ?>
	  <span class="subcomment-singlepost"> <a class="link-comments" href="<?php  comments_link(); ?>">
        <?php comments_number(__('0 ','javapaper'),__('1 ','javapaper'),__('% ','javapaper')); ?>
        </a> </span> </div>
    </div>
    <?php if ( is_single() ) : ?>
    <h1 class="entry-title">
      <?php the_title(); ?>
    </h1>
    <?php else : ?>
    <h2 class="entry-title"> <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'javapaper' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark">
      <?php the_title(); ?>
      </a> </h2>
    <?php endif; // is_single() ?>
  </header>
  <!-- .entry-header -->
  <?php if ( is_home() && ( get_theme_mod( 'javapaper_one_full_post' , '1' ) == '1' ) ) : // Check Live Customizer for Full/Excerpts Post Settings ?>
  <?php javapaper_one_excerpts() ?>
  <?php elseif( is_search() || is_category() || is_tag() || is_author() || is_archive()  ): ?>
  <?php javapaper_one_excerpts() ?>
  <?php else : ?>
  <div class="entry-content">
    <?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'javapaper' ) ); ?>
    <?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'javapaper' ), 'after' => '</div>' ) ); ?>
  </div>
  <!-- .entry-content -->
  <?php endif; ?>
  </article>
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
