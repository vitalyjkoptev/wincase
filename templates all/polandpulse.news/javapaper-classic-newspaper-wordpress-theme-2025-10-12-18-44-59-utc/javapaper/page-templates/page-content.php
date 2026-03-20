<?php
/*
 * Content display template for PAGE
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  <?php if ( is_sticky() && is_home() && ! is_paged() ) : // for top sticky post with blue border ?>
  
  <div class="featured-post">
    <?php _e( 'Featured Article', 'javapaper' ); ?>
  </div>
  <?php endif; ?>
  <header class="entry-header">
    <div class="category1-time">
      <?php javapaper_breadcrumb(); ?>
      <div class="module9-view"> 
	<span class="view2"><?php if (function_exists("javapaper_get_post_view")) { ?>
   <?php echo javapaper_get_post_view(); ?><?php } else { ?><?php } ?></span>
    <?php if (function_exists("javapaper_reading_time")) { ?>
	<?php echo javapaper_reading_time(get_the_ID()); ?><?php } else { ?> <?php } ?>
	  <span class="subcomment-singlepost"> <a class="link-comments" href="<?php  comments_link(); ?>">
        <?php comments_number(__('0 ','javapaper'),__('1 ','javapaper'),__('% ','javapaper')); ?>
        </a> </span> </div>
    </div>
    <?php if ( is_single() ) : ?>
    <h1 class="entry-title">
      <?php the_title(); ?>
    </h1>
    <?php else : ?>
    <h2 class="entry-title">
      <?php the_title(); ?>
    </h2>
    <?php endif; // is_single() ?>
    <div class="below-title-meta">
      <div class="submeta-singlepost">
        <?php the_category(', '); ?>
        <div class="subdate-singlepost"> <?php echo get_the_date(' F d, Y ');?> </div>
      </div>
      <div class="adt-comment">
        <div class="features-onsinglepost">
          <?php if ( function_exists( 'sharing_display' ) ) { echo sharing_display(); } ?>
        </div>
      </div>
    </div>
    <!-- below title meta end -->
  </header>
  <!-- .entry-header -->
  <div class="feature-postimg"> <a href="<?php the_permalink() ?>" title="<?php the_title(); ?>">
    <?php $thumb = get_post_thumbnail_id(); 
					$img_url = wp_get_attachment_url( $thumb,'full' ); 
					$image = aq_resize( $img_url, 999, 555, true,true,true ); ?>
    <img src="<?php if($image) {echo esc_url($image);
					} else {echo javapaper_catch_that_image(); } ?>" alt="<?php  echo get_bloginfo('name'); ?>"/></a> </div>
  <?php { $newsintro = get_post_meta ($post->ID, 'newsintro', $single = true);
				if($newsintro !== '') 
				echo '<div class="single2-intro">'.$newsintro.'</div>';							
				} ?>
  <?php if ( is_home() && ( get_theme_mod( 'javapaper_one_full_post' , '1' ) == '1' ) ) : // Check Live Customizer for Full/Excerpts Post Settings ?>
  <?php javapaper_one_excerpts() ?>
  <?php elseif( is_search() || is_category() || is_tag() || is_author() || is_archive()  ): ?>
  <?php javapaper_one_excerpts() ?>
  <?php else : ?>
  <div class="entry-content">
    <?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'javapaper' ) ); ?>
    <?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'javapaper' ), 'after' => '</div>' ) ); ?>
  </div>
  <!-- .entry-content -->
  <?php endif; ?>
</article>
<!-- #post -->
