<?php
/*
 * Content display template for index page
 */
?>
<!-- START LOOP -->

<div class="index-jbottom">  
  <div class="sticky-text">
    <?php esc_attr_e("FEATURE","javapaper"); ?>
  </div>
  <?php echo'<h2 class="module9-titlebig"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h2>';?>
  <div class="category1-time">
    <?php $category = get_the_category();
		if ($category) {
		echo '<a href="' . esc_url( get_category_link( $category[0]->term_id ) ) . '" title="' . sprintf( esc_attr__( "View all posts in %s","javapaper"), $category[0]->name ) . '" ' . '>' . $category[0]->name.'</a> ';
		}?>
    <div class="module9-view">
	<span class="view2"><?php if (function_exists("javapaper_get_post_view")) { ?>
   <?php echo javapaper_get_post_view(); ?><?php } else { ?><?php } ?></span>
    <?php if (function_exists("javapaper_reading_time")) { ?>
	<?php echo javapaper_reading_time(get_the_ID()); ?><?php } else { ?> <?php } ?>
	</div>
  </div>    
  <!-- .entry-header -->
  <?php if ( is_home() && ( get_theme_mod( 'javapaper_one_full_post' , '1' ) == '1' ) ) : // Check Live Customizer for Full/Excerpts Post Settings ?>
    <div class="module31-content">
  <?php javapaper_one_excerpts() ?>
  </div>
  <?php elseif( is_search() || is_category() || is_tag() || is_author() || is_archive()  ): ?>
  <?php javapaper_one_excerpts() ?>
  <?php else : ?>
  <div class="module31-content">
      <?php the_post_thumbnail('excerpt-thumbnail'); ?>
    <?php echo javapaper_content(44); ?> 
</div>
  <!-- .entry-content -->
  <?php endif; ?>
  
  <div class="index-footer">
	<?php the_time(get_option('date_format')) ?>
    <span>
    <?php esc_attr_e(" By ","javapaper"); ?>
    </span> <a href="<?php  echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" rel="author"> <?php printf( __( ' %s', 'javapaper' ), get_the_author() ); ?></a>
    <div class="module9-view"> <a href="<?php  the_permalink(); ?>">
      <?php esc_attr_e('Read the Post &#8594;	', 'javapaper'); ?>
      </a> </div>
  </div>
</div>
