<?php global $redux_demo;
if( isset($redux_demo['header_layout']) ){
  get_header($redux_demo['header_layout']);;	
} else{
    get_header();
}
?>
<div class="single2-wrapper">
  <?php while ( have_posts() ) : the_post(); ?>
  <div id="primary" class="site-content <?php if(isset($redux_demo['jp_sidebar']) ){ ?><?php echo esc_html($redux_demo['jp_sidebar']); ?><?php } ?>">
    <div id="content" role="main">
      <?php get_template_part( 'page-templates/content', get_post_format() ); ?>
      <!-- .nav-single -->	  
      <nav class="nav-single">
        <div class="assistive-text">
          <?php _e( 'Post navigation', 'javapaper' ); ?>
        </div>
        <span class="nav-previous">
        <?php previous_post_link( '%link', '<span class="meta-nav">' . esc_html( '&larr;', 'Previous post link', 'javapaper' ) . '</span> %title' ); ?>
        </span> <span class="nav-next">
        <?php next_post_link( '%link', '%title <span class="meta-nav">' . esc_html( '&rarr;', 'Next post link', 'javapaper' ) . '</span>' ); ?>
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