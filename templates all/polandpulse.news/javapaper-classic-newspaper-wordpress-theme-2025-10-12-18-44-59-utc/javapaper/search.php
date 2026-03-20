<?php global $redux_demo;
if( isset($redux_demo['header_layout']) ){
  get_header($redux_demo['header_layout']);;	
} else{
    get_header();
}
?>
<div class="single2-wrapper">
  <div id="primary" class="site-content 
  <?php if(isset($redux_demo['jp_sidebar']) ){ ?>
<?php echo esc_html($redux_demo['jp_sidebar']); ?>
<?php } ?>">
    <div id="content" role="main">
      <?php if ( have_posts() ) : ?>
      <header class="page-header">
        <div class="cat-count"> <?php echo esc_html ($wp_query->found_posts) ?>
          <?php esc_html_e( 'Search Result For:', 'javapaper' ); ?>
        </div>
        <h1 class="page-title"><?php printf( __( '%s', 'javapaper' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
      </header>
      <?php /* Start the Loop */ ?>
      <?php while ( have_posts() ) : the_post(); ?>
      <div class="search-jtop col-md-6">
        <div class="search-jtopinside">
          <?php get_template_part( 'page-templates/content-search', get_post_format() ); ?>
        </div>
      </div>
      <?php endwhile; ?>
      <?php if (function_exists("javapaper_numbered_pages")) { ?>
      <?php javapaper_numbered_pages(); ?>
      <?php } else { ?>
      <?php } ?>
      <?php else : ?>
      <article id="post-0" class="post no-results not-found">
        <header class="page-header">
          <h1 class="entry-title">
            <?php _e( 'Nothing Found', 'javapaper' ); ?>
          </h1>
        </header>
        <div class="entry-content">
          <div class="row">
            <div class="d-flex align-items-center">
              <div class="col-md-6 cattitle">
                <?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'javapaper' ); ?>
              </div>
              <div class="col-md-6">
                <?php get_search_form(); ?>
              </div>
            </div>
          </div>
        </div>
        <!-- .entry-content -->
      </article>
      <!-- #post-0 -->
      <?php endif; ?>
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