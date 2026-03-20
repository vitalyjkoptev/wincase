<?php
/*
Template Name: Template: Custom Frontpage
*/
global $redux_demo;
if( isset($redux_demo['header_layout']) ){
  get_header($redux_demo['header_layout']);
} else{
    get_header();
}
?>
<div class="top-divider"> </div>
<div id="contentfrontpage">
  <div id="full-width" class="clearfix" role="main">
    <div class="custom-page">
      <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
      <article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> >
        <div class="customfrontpage-wrapper">
          <?php the_content(); ?>
        </div>
        <?php edit_post_link( esc_attr__( 'Edit', 'javapaper' ), '<span class="edit-link">', '</span>' ); ?>
      </article>
      <?php endwhile; else : ?>
      <?php endif; ?>
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