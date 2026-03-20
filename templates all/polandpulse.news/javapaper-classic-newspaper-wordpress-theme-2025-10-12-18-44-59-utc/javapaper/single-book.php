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
      </div>
      <div class="col-md-8">
        <?php get_template_part( 'page-templates/contentbook', get_post_format() ); ?>
        <?php comments_template( '', true ); ?>
        <?php endwhile; // end of the loop. ?>
      </div>
    </div>
    <!-- #content -->
  </div>
  <div class="sidebar <?php if(isset($redux_demo['jp_sidebar']) ){ ?><?php echo esc_html($redux_demo['jp_sidebar']); ?><?php } ?>">
    <div class="cpt-sidebar">
      <div class="cpt-desc">
        <div class="row">
          <?php if( get_post_meta($post->ID, "book_author", true) ): ?>
          <div class="col-md-12 cptfield">
            <div class="cpt-inside"><?php echo get_post_meta($post->ID, "book_author", true); ?> </div>
          </div>
          <?php else : ?>
          <?php endif; ?>
          <?php if( get_post_meta($post->ID, "total_pages", true) ): ?>
          <div class="col-md-12 cptfield">
            <div class="cpt-inside"><?php echo get_post_meta($post->ID, "total_pages", true); ?> </div>
          </div>
          <?php else : ?>
          <?php endif; ?>
          <?php if( get_post_meta($post->ID, "isbn", true) ): ?>
          <div class="col-md-12 cptfield">
            <div class="cpt-inside"><?php echo get_post_meta($post->ID, "isbn", true); ?> </div>
          </div>
          <?php else : ?>
          <?php endif; ?>
          <?php if( get_post_meta($post->ID, "original_language", true) ): ?>
          <div class="col-md-12 cptfield">
            <div class="cpt-inside"><?php echo get_post_meta($post->ID, "original_language", true); ?> </div>
          </div>
          <?php else : ?>
          <?php endif; ?>
          <?php if( get_post_meta($post->ID, "literary_awards", true) ): ?>
          <div class="col-md-12 cptfield">
            <div class="cpt-inside"> <?php echo  get_post_meta($post->ID, "literary_awards", true ); ?> </div>
          </div>
          <?php else : ?>
          <?php endif; ?>
          <?php if( get_post_meta($post->ID, "book_description", true) ): ?>
          <div class="col-md-12 cptfield">
            <div class="cpt-inside"> <?php echo  get_post_meta($post->ID, "book_description", true ); ?> </div>
          </div>
          <?php else : ?>
          <?php endif; ?>
        </div>
      </div>
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