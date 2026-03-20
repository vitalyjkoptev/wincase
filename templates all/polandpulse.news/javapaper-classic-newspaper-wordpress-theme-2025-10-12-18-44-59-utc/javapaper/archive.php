<?php global $redux_demo;
if( isset($redux_demo['header_layout']) ){
  get_header($redux_demo['header_layout']);
} else{
    get_header();
}
?>
<div class="single2-wrapper">
  <div id="primary" class="site-content <?php if(isset($redux_demo['jp_sidebar']) ){ ?><?php echo esc_html($redux_demo['jp_sidebar']); ?><?php } ?>">
    <header class="archive-header">
		<?php javapaper_breadcrumb(); ?>
        <div class="cat-count"> <?php echo esc_html ($wp_query->found_posts) ?>
          <?php esc_html_e( 'Posts', 'javapaper' ); ?>
        </div>
      <h1 class="archive-title">
        <?php
			if ( is_day() ) :
				printf( __( 'Daily Archives: %s', 'javapaper' ), '<span>' . get_the_date() . '</span>' );
			elseif ( is_month() ) :
				printf( __( 'Monthly Archives: %s', 'javapaper' ), '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'javapaper' ) ) . '</span>' );
			elseif ( is_year() ) :
				printf( __( 'Yearly Archives: %s', 'javapaper' ), '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'javapaper' ) ) . '</span>' );
			else :
				_e( 'Archives', 'javapaper' );
			endif;
		?>
      </h1>
    </header>
    <!-- .archive-header -->
    <div id="content" role="main">
      <?php if ( have_posts() ) : ?>
      <div class="category1-wrapper">
        <?php while(have_posts()) :
				the_post();
				?>
        <div class="category3-jtop col-md-6" <?php post_class('clearfix'); ?> >
          <div <?php post_class('clearfix'); ?> >
            <?php get_template_part( 'page-templates/catcontent3', get_post_format() ); ?>
          </div>
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
<?php 
if( isset($redux_demo['footer_layout']) ){
  get_template_part($redux_demo['footer_layout']);
} else{
    get_footer();
}
?>