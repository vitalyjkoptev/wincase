<?php global $redux_demo;
if( isset($redux_demo['header_layout']) ){
  get_header($redux_demo['header_layout']);;	
} else{
    get_header();
}
?>
<div class="single2-wrapper">
  <?php while ( have_posts() ) : the_post(); ?>
  <div id="primary" class="site-content 
  <?php if(isset($redux_demo['jp_sidebar']) ){ ?>
<?php echo esc_html($redux_demo['jp_sidebar']); ?>
<?php } ?>">
    <div id="content" role="main">
      <?php get_template_part( 'page-templates/content', get_post_format() ); ?>
      <?php comments_template( '', true ); ?>
      <?php endwhile; // end of the loop. ?>
    </div>
    <!-- #content -->
  </div>
  <div class="sidebar 
  <?php if(isset($redux_demo['jp_sidebar']) ){ ?>
<?php echo esc_html($redux_demo['jp_sidebar']); ?>
<?php } ?>">
    <div class="single2-widget">
      <?php get_sidebar(); ?>
    </div>
  </div>
</div>
<?php 
if( isset($redux_demo['footer_layout']) ){
  get_template_part($redux_demo['footer_layout']);
} else{
    get_footer();
}
?>