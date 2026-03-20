<?php
/**
 * The sidebar containing the main widget area.
 */
?>
<?php if ( is_active_sidebar( 'javapaper-sidebar' ) ) : ?>
  <?php dynamic_sidebar( 'javapaper-sidebar' ); ?>
  <?php else : ?>
<?php the_widget( 'WP_Widget_Search' ); ?> 
<?php the_widget( 'WP_Widget_Tag_Cloud' ); ?> 
 <?php the_widget( 'WP_Widget_Recent_Posts' ); ?> 
  <?php endif; ?>