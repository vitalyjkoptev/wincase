<?php
/*
Template Name: Template: Blog
*/
global $redux_demo;
if( isset($redux_demo['header_layout']) ){
  get_header($redux_demo['header_layout']);
} else{
    get_header();
}
?>
<div class="index-wrapper">
  <?php
$args = array( 'meta_key' => 'post_views_count',  'orderby' => 'meta_value_num','numberposts' => 3 );
$postslist = get_posts( $args );
foreach ($postslist as $post) :  setup_postdata($post); ?>
  <div class="col-md-4 indextop-wrapper">
    <div class="index-thumb">
      <?php $thumb = get_post_thumbnail_id(); 
					$img_url = wp_get_attachment_url( $thumb,'full' ); 
					$image = aq_resize( $img_url, 400, 200, true,true,true ); ?>
      <img src="<?php if($image) {echo esc_url($image);
					} else {echo javapaper_catch_that_image(); } ?>"  width="400" height="200"alt="<?php  echo get_bloginfo('name'); ?>"/> </div>
    <div class="category1-time">
      <?php $category = get_the_category();
				if ($category) {
				echo '<a href="' . esc_url( get_category_link( $category[0]->term_id ) ) . '" title="' . sprintf( esc_attr__( "View all posts in %s","javapaper"), $category[0]->name ) . '" ' . '>' . $category[0]->name.'</a> ';
				}?>
      <div class="module9-view"> <span class="view2"><?php if (function_exists("javapaper_get_post_view")) { ?>
   <?php echo javapaper_get_post_view(); ?><?php } else { ?><?php } ?></span> </div>
    </div>
    <?php echo'<h3 class="module9-titlebig"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';?>
    <div class="index-footertop">
      <?php the_time('M j, Y') ?>
      <span>
      <?php esc_attr_e(" By ","javapaper"); ?>
      </span> <a href="<?php  echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"> <?php printf( __( '%s', 'javapaper' ), get_the_author() ); ?></a>
      <div class="module9-view"> <a href="<?php  the_permalink(); ?>">
        <?php esc_attr_e('Read the Post &#8594;	', 'javapaper'); ?>
        </a> </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>
<div class="single2-wrapper">
  <div id="primary" class="site-content <?php if(isset($redux_demo['jp_sidebar']) ){ ?><?php echo esc_html($redux_demo['jp_sidebar']); ?><?php } ?>">
    <div id="content" role="main">
      <div class="indextop-maintitle ">
        <div class="moduletitle-wrapper">
          <div class="jmodule-maintitle">
            <h3>
              <?php esc_html_e( 'LATEST', 'javapaper' ); ?>
              <?php esc_html_e( 'POST', 'javapaper' ); ?>
            </h3>
          </div>
        </div>
      </div>
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
        <div <?php post_class('category3-jtop col-md-6 clearfix'); ?> >
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