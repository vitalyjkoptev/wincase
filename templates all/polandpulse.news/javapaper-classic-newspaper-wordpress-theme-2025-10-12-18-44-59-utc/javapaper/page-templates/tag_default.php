<div class="category1-topheader">
  <div class="category1-topinside">
    <div class="row align-items-center">
      <div class="col-md-6 cattitle">
        <div class="cat-count"> <?php echo esc_html ($wp_query->found_posts) ?>
          <?php esc_html_e( 'Posts', 'javapaper' ); ?>
          <?php esc_html_e( 'On  This Category', 'javapaper' ); ?>
        </div>
        <header class="category1-header">
          <h1 class="archive-title">
            <?php single_cat_title(); ?>
          </h1>
        </header>
        <!-- .archive-header -->
      </div>
      <div class="col-md-6">
        <div class="cat-about"> 
          <?php esc_html_e( 'ABOUT THIS TAG', 'javapaper' ); ?>
        </div>	  
		<?php if ( category_description() ) : // Show an optional Category description ?>
        <?php echo'<span>' .category_description() . '</span>';?>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
<div class="category1-wrapperinside">
  <div id="primary" class="site-content 
  <?php if(isset($redux_demo['jp_sidebar']) ){ ?>
<?php echo esc_html($redux_demo['jp_sidebar']); ?>
<?php } ?>">
    <div id="content" role="main">
      <?php if ( have_posts() ) : ?>
      <div class="category1-wrapper">
        <?php if ( get_query_var('paged') ) {
				$paged = get_query_var('paged');
				} else if ( get_query_var('page') ) {
				$paged = get_query_var('page');
				} else {
				$paged = 1;
				}
				?>
        <?php while(have_posts()) :
				the_post();
				?>
        <div <?php post_class('clearfix'); ?> >
          <div class="categorydefault-wrapper">
            <?php get_template_part( 'page-templates/catcontent', get_post_format() ); ?>
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
    <div class="single2-widget">
      <?php get_sidebar(); ?>
    </div>
  </div>
</div>
