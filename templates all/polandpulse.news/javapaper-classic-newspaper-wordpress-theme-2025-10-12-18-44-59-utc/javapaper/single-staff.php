<?php global $redux_demo;
if( isset($redux_demo['header_layout']) ){
  get_header($redux_demo['header_layout']);;	
} else{
    get_header();
}
?>
<div class="single2-wrapper">
<div class="singlecpt-wrapper">
  <?php while ( have_posts() ) : the_post(); ?>
  <div id="primary" class="site-content <?php if(isset($redux_demo['jp_sidebar']) ){ ?><?php echo esc_html($redux_demo['jp_sidebar']); ?><?php } ?>">
    <div id="content" role="main">
      <div class="col-md-4">
        <div class="feature-postimg">
          <?php the_post_thumbnail('post-thumbnail'); ?>
        </div>
        <div class="cpt-sidebar">
          <div class="cpt-desc">
            <div class="row">
              <?php if( get_post_meta($post->ID, "staff_Description1", true) ): ?>
              <div class="col-md-12 cptfield">
                <div class="cpt-inside"> <?php echo get_post_meta($post->ID, "staff_Description1", true); ?> </div>
              </div>
              <?php else : ?>
              <?php endif; ?>
              <?php if( get_post_meta($post->ID, "staff_Description2", true) ): ?>
              <div class="col-md-12 cptfield">
                <div class="cpt-inside"><?php echo get_post_meta($post->ID, "staff_Description2", true); ?> </div>
              </div>
              <?php else : ?>
              <?php endif; ?>
              <?php if( get_post_meta($post->ID, "staff_Description3", true) ): ?>
              <div class="col-md-12 cptfield">
                <div class="cpt-inside"><?php echo get_post_meta($post->ID, "staff_Description3", true); ?> </div>
              </div>
              <?php else : ?>
              <?php endif; ?>
              <?php if( get_post_meta($post->ID, "staff_Description4", true) ): ?>
              <div class="col-md-12 cptfield">
                <div class="cpt-inside"><?php echo get_post_meta($post->ID, "staff_Description4", true); ?> </div>
              </div>
              <?php else : ?>
              <?php endif; ?>
              <?php if( get_post_meta($post->ID, "staff_Description5", true) ): ?>
              <div class="col-md-12 cptfield">
                <div class="cpt-inside"> <?php echo  get_post_meta($post->ID, "staff_Description5", true ); ?> </div>
              </div>
              <?php else : ?>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-8">
        <?php get_template_part( 'page-templates/contentstaff', get_post_format() ); ?>
        <?php comments_template( '', true ); ?>
        <?php endwhile; // end of the loop. ?>
      </div>
    </div>
    <!-- #content -->
  </div>
  <div class="sidebar <?php if(isset($redux_demo['jp_sidebar']) ){ ?><?php echo esc_html($redux_demo['jp_sidebar']); ?><?php } ?>">
    <div class="cpt-sidebar">
		  <?php if (is_active_sidebar('staff-sidebar')) :?>
		  <?php dynamic_sidebar( 'staff-sidebar' ); ?>
		  <?php endif; ?>  		
    </div>
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