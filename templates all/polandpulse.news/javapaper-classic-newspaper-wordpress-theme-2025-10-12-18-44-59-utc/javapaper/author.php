<?php global $redux_demo;
if( isset($redux_demo['header_layout']) ){
  get_header($redux_demo['header_layout']);
} else{
    get_header();
}
?>
<div class="single2-wrapper">
  <div id="primary" class="site-content 
  <?php if(isset($redux_demo['jp_sidebar']) ){ ?>
<?php echo esc_html($redux_demo['jp_sidebar']); ?>
<?php } ?>">
    <div class="author-wrapper">
      <div class="author-info">
        <div class="author-avatar"> <?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'javapaper_author_bio_avatar_size', 200 ) ); ?> </div>
        <!-- .author-avatar -->
        <div class="author-description"> <?php echo get_the_author_meta('Position');?>
		<?php _e( ' / ', 'javapaper' ); ?>
		<?php printf( 'Published posts: %d', count_user_posts( get_the_author_meta('ID') ) ); ?>
          <h2><?php printf( __( 'About %s', 'javapaper' ), get_the_author() ); ?></h2>
          <p>
            <?php the_author_meta( 'description' ); ?>
          </p>
          <div class="author-contact-wrapper">
            <?php if(get_the_author_meta('twitter')){ ?>
            <div class ="author-socmed-wrapper "> <a rel="me" href="http://twitter.com/<?php the_author_meta('twitter'); ?>">
              <div class ="author-twitter"> </div>
              </a> <span class="tooltiptext"><?php _e( 'Twitter', 'javapaper' ); ?></span> 
			</div>			  
            <?php } 
				if(get_the_author_meta('facebook')){ ?>
            <div class ="author-socmed-wrapper "> <a rel="me" href="http://facebook.com/<?php the_author_meta('facebook'); ?>">
              <div class ="author-facebook"> </div>
              </a> <span class="tooltiptext"><?php _e( 'Facebook', 'javapaper' ); ?></span>
			</div>
            <?php }
				if(get_the_author_meta('youtube')){ ?>
            <div class ="author-socmed-wrapper "> <a rel="me" href="http://youtube.com/<?php the_author_meta('youtube'); ?>">
              <div class ="author-youtube"> </div>
              </a> <span class="tooltiptext"><?php _e( 'Youtube', 'javapaper' ); ?></span> </div>
            <?php }
				if(get_the_author_meta('vimeo')){ ?>
            <div class ="author-socmed-wrapper "> <a rel="me" href="http://vimeo.com/<?php the_author_meta('vimeo'); ?>">
              <div class ="author-vimeo"> </div>
               </a> <span class="tooltiptext"><?php _e( 'Vimeo', 'javapaper' ); ?></span> </div>
            <?php }
				if(get_the_author_meta('linkedin')){ ?>
            <div class ="author-socmed-wrapper "> <a rel="me" href="http://linkedin.com/<?php the_author_meta('linkedin'); ?>" data-placement="top" data-toggle="tooltip" class="tip-top" data-original-title="Linkedin" >
              <div class ="author-linkedin"> </div>
              </a> <span class="tooltiptext"><?php _e( 'Linkedin', 'javapaper' ); ?></span> </div>
            <?php }
				if(get_the_author_meta('devianart')){ ?>
            <div class ="author-socmed-wrapper "> <a rel="me" href="http://devianart.com/<?php the_author_meta('devianart'); ?>" data-placement="top" data-toggle="tooltip" class="tip-top" data-original-title="Devian-art" >
              <div class ="author-devianart"> </div>
             </a> <span class="tooltiptext"><?php _e( 'Deviant', 'javapaper' ); ?></span> </div>
            <?php }
				if(get_the_author_meta('dribble')){ ?>
            <div class ="author-socmed-wrapper "> <a rel="me" href="http://dribble.com/<?php the_author_meta('dribble'); ?>" data-placement="top" data-toggle="tooltip" class="tip-top" data-original-title="Dribble" >
              <div class ="author-dribble"> </div>
              </a> <span class="tooltiptext"><?php _e( 'Dribble', 'javapaper' ); ?></span> </div>
            <?php }
				if(get_the_author_meta('flickr')){ ?>
            <div class ="author-socmed-wrapper "> <a rel="me" href="http://flickr.com/<?php the_author_meta('flickr'); ?>" data-placement="top" data-toggle="tooltip" class="tip-top" data-original-title="Flickr" >
              <div class ="author-flickr"> </div>
             </a> <span class="tooltiptext"><?php _e( 'Flickr', 'javapaper' ); ?></span> </div>
            <?php }
				if(get_the_author_meta('instagram')){ ?>
            <div class ="author-socmed-wrapper "> <a rel="me" href="http://instagram.com/<?php the_author_meta('instagram'); ?>" data-placement="top" data-toggle="tooltip" class="tip-top" data-original-title="Instagram" >
              <div class ="author-instagram"> </div>
             </a> <span class="tooltiptext"><?php _e( 'Instagram', 'javapaper' ); ?></span> </div>
            <?php }
				if(get_the_author_meta('behance')){ ?>
            <div class ="author-socmed-wrapper "> <a rel="me" href="http://behance.com/<?php the_author_meta('behance'); ?>" data-placement="top" data-toggle="tooltip" class="tip-top" data-original-title="Behance" >
              <div class ="author-behance"> </div>
              </a> <span class="tooltiptext"><?php _e( 'Behance', 'javapaper' ); ?></span> </div>
            <?php }
				if(get_the_author_meta('reddit')){ ?>
            <div class ="author-socmed-wrapper "> <a rel="me" href="http://reddit.com/<?php the_author_meta('reddit'); ?>" data-placement="top" data-toggle="tooltip" class="tip-top" data-original-title="Reddit" >
              <div class ="author-reddit"> </div>
              </a> <span class="tooltiptext"><?php _e( 'Reddit', 'javapaper' ); ?></span> </div>
            <?php }
				if(get_the_author_meta('forrst')){ ?>
            <div class ="author-socmed-wrapper "> <a rel="me" href="http://forrst.com/<?php the_author_meta('forrst'); ?>" data-placement="top" data-toggle="tooltip" class="tip-top" data-original-title="Forrst" >
              <div class ="author-forrst"> </div>
              </a> <span class="tooltiptext"><?php _e( 'Forrst', 'javapaper' ); ?></span> </div>
            <?php }
				if(get_the_author_meta('github')){ ?>
            <div class ="author-socmed-wrapper "> <a rel="me" href="http://github.com/<?php the_author_meta('github'); ?>" data-placement="top" data-toggle="tooltip" class="tip-top" data-original-title="Github" >
              <div class ="author-github"> </div>
             </a> <span class="tooltiptext"><?php _e( 'Github', 'javapaper' ); ?></span> </div>
            <?php }
				if(get_the_author_meta('pinterest')){ ?>
            <div class ="author-socmed-wrapper "> <a rel="me" href="http://pinterest.com/<?php the_author_meta('pinterest'); ?>" data-placement="top" data-toggle="tooltip" class="tip-top" data-original-title="Pinterest" >
              <div class ="author-pinterest"> </div>
             </a> <span class="tooltiptext"><?php _e( 'Pinterest', 'javapaper' ); ?></span> </div>
            <?php }	?>
          </div>
        </div>
        <!-- .author-description	-->
      </div>
      <!-- .author-info -->
    </div>
    <div id="content" role="main">
      <?php javapaper_breadcrumb(); ?>
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
<!-- #primary -->
<?php 
if( isset($redux_demo['footer_layout']) ){
  get_template_part($redux_demo['footer_layout']);
} else{
    get_footer();
}
?>
