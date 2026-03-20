<?php global $redux_demo;?>
<div class="related-wrapper <?php if( isset($redux_demo['jp_showhidenav']) ){ ?><?php echo esc_html($redux_demo['jp_showhidenav']); ?><?php } ?>"> 
  <div class="related-maintitle">
    <h5>
      <?php esc_html_e('Related Post', 'javapaper'); ?>
    </h5>
  </div>
  <?php
  $cats = wp_get_post_categories($post->ID);
    if ($cats) {
    $first_cat = $cats[0];
    $args=array(
      'cat' => $first_cat, 
      'post__not_in' => array($post->ID),
	  'orderby' => 'rand',
      'showposts'=>4,
      'ignore_sticky_posts'=>1
    );
    $my_query = new WP_Query($args);
    if( $my_query->have_posts() ) {
?>
  <?php while ($my_query->have_posts()) : $my_query->the_post(); ?>
  <div class="related-subwrapper">
      <div class="related-thumb">
<?php the_post_thumbnail('excerpt-thumbnail'); ?>
          <div class="related-title">
		  <h6>			  
		  <a href="<?php the_permalink() ?>" title="<?php the_title(); ?>">
              <?php the_title(); ?>
            </a>			  
            </h6>
            <div class="module4-meta"> 
			<?php esc_html__("By ","javapaper"); ?>		
			<a href="<?php  echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" rel="author"> <?php printf( __( ' %s', 'javapaper' ), get_the_author() ); ?></a>
			<?php esc_html__(" on ","javapaper"); ?>
			<?php echo get_the_date(' F, Y ');?>  
			  </div>
          </div>
      </div>
  </div>
  <?php esc_html__(" XXXXXXXXXXXXX ","javapaper"); ?>
  <?php endwhile;  } 
  } 
 wp_reset_query();  
?>
</div>
