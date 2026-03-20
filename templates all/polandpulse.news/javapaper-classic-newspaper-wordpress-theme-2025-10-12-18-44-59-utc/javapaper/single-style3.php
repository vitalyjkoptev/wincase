<?php global $redux_demo;
/*
 * Template Name: Post Style 3
 * Template Post Type: post
*/
if( isset($redux_demo['header_layout']) ){
  get_header($redux_demo['header_layout']);;	
} else{
    get_header();
}
?>
<div class="feature3-postimg"> <a href="<?php the_permalink() ?>" title="<?php the_title(); ?>">
  <?php $thumb = get_post_thumbnail_id(); 
					$img_url = wp_get_attachment_url( $thumb,'full' ); 
					$image = aq_resize( $img_url, 1350, 555, true,true,true ); ?>
  <img src="<?php if($image) {echo esc_url($image);
					} else {echo javapaper_catch_that_image(); } ?>" alt="<?php  echo get_bloginfo('name'); ?>"/></a> </div>
<div class="single2-wrapper">
  <?php while ( have_posts() ) : the_post(); ?>
  <div id="primary" class="site-content <?php if(isset($redux_demo['jp_sidebar']) ){ ?><?php echo esc_html($redux_demo['jp_sidebar']); ?><?php } ?>">
    <div id="content" role="main">
      <?php get_template_part( 'page-templates/content3', get_post_format() ); ?>
      <nav class="nav-single">
        <div class="assistive-text">
          <?php _e( 'Post navigation', 'javapaper' ); ?>
        </div>
        <span class="nav-previous">
        <?php previous_post_link( '%link', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'javapaper' ) . '</span> %title' ); ?>
        </span> <span class="nav-next">
        <?php next_post_link( '%link', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'javapaper' ) . '</span>' ); ?>
        </span> </nav>
      <!-- author-info -->	  
      <?php  get_template_part( 'inc/author-singlepost' ); ?>	  
      <!-- related post -->
      <?php  get_template_part( 'inc/related-post' ); ?>
      <?php comments_template( '', true ); ?>
      <?php endwhile; // end of the loop. ?>
    </div>
    <!-- #content -->
  </div>
  <div class="sidebar <?php if(isset($redux_demo['jp_sidebar']) ){ ?><?php echo esc_html($redux_demo['jp_sidebar']); ?><?php } ?>">
    <div class="single2-widget">
      <?php get_sidebar(); ?>
    </div>
  </div>
</div>
	<?php if (function_exists("javapaper_set_post_view")) { ?>
   <?php javapaper_set_post_view(); ?><?php } else { ?><?php } ?>
<!-- #primary -->
<?php 
if( isset($redux_demo['footer_layout']) ){
  get_template_part($redux_demo['footer_layout']);
} else{
    get_footer();
}
?>