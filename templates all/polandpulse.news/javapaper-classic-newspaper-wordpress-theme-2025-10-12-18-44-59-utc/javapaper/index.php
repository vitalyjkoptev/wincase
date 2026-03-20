<?php global $redux_demo;
if( isset($redux_demo['header_layout']) ){
  get_header($redux_demo['header_layout']);;	
} else{
    get_header();
}
?>
<div class="single2-wrapper"><div id="primary" class="site-content <?php if(isset($redux_demo['jp_sidebar']) ){ ?><?php echo esc_html($redux_demo['jp_sidebar']); ?><?php } ?>">
    <div id="content" role="main">
      <?php if ( have_posts() ) : ?>
      <div class="index-wrapper">
        <?php if ( get_query_var('paged') ) {
				$paged = get_query_var('paged');
				} else if ( get_query_var('page') ) {
				$paged = get_query_var('page');
				} else {
				$paged = 1;
				}
				query_posts( array( 'showposts' => '8', 'paged' => $paged ) ); 
				?>
        <?php while(have_posts()) :
				the_post();
				?>
          <div <?php post_class('category3-jtop col-md-6'); ?> >
            <?php get_template_part( 'page-templates/index-content', get_post_format() ); ?>
          </div>
        <?php endwhile; ?>
        <?php else : ?>
        <?php get_template_part( 'page-templates/content2', 'none' ); ?>
        <?php endif; ?>
      </div>
    </div>
    <!-- #content -->
    <?php if (function_exists("javapaper_numbered_pages")) { ?>
    <?php javapaper_numbered_pages(); ?>
    <?php } else { ?>
    <nav>
      <ul class="pager">
        <li class="previous">
          <?php next_posts_link( esc_attr__( '&laquo; Older Entries', 'javapaper' ) ); ?>
        </li>
        <li class="next">
          <?php previous_posts_link( esc_attr__( 'Newer Entries &raquo;', 'javapaper' ) ); ?>
        </li>
      </ul>
    </nav>
    <?php } ?>
  </div>
  <!-- #primary -->
  <div class="sidebar <?php if(isset($redux_demo['jp_sidebar']) ){ ?><?php echo esc_html($redux_demo['jp_sidebar']); ?><?php } ?>">  
      <?php get_sidebar(); ?>
  </div>
</div>
<!-- #primary -->
<?php 
if( isset($redux_demo['footer_layout']) ){
  get_template_part($redux_demo['footer_layout']);
} else{
    get_footer();
}
?>