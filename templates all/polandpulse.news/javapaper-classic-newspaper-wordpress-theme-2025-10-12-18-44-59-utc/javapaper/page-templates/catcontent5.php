<?php
/*
 * Content display template for category 5
 */
?>
<!-- START LOOP -->

<div class="category5-jbottom">
  <div class="sticky-text">
    <?php the_time('M j, Y') ?>
  </div>
  <div class="category1-time"> <span>
    <?php esc_attr_e(" By ","javapaper"); ?>
    </span> <a href="<?php  echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" rel="author"> <?php printf( __( ' %s', 'javapaper' ), get_the_author() ); ?></a>
    <div class="module9-view">
	<span class="view2"><?php if (function_exists("javapaper_get_post_view")) { ?>
   <?php echo javapaper_get_post_view(); ?><?php } else { ?><?php } ?></span>
    <?php if (function_exists("javapaper_reading_time")) { ?>
	<?php echo javapaper_reading_time(get_the_ID()); ?><?php } else { ?> <?php } ?>
	</div>
  </div>
  <div class="module9-titlebig">
    <h2><a href="<?php  the_permalink(); ?>">
      <?php  the_title(); ?>
      </a></h2>
  </div>
  <div class="module31-content">
    <?php the_post_thumbnail('excerpt-thumbnail'); ?>
	   <div class="hide-thumb">
	  <?php javapaper_one_excerpts() ?>
	  </div>
    <div class="module9-readmore"> <a href="<?php  the_permalink(); ?>">
      <?php esc_attr_e('Read the Post &#8594;	', 'javapaper'); ?>
      </a> </div>
  </div>
</div>
