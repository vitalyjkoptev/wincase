<?php
/*
 * Content display template for category 1
 */
?>
<!-- START LOOP -->

<div class="category1-jtop">
  <div class="module9-thumbnail">
    <div class="sticky-text">
      <?php esc_attr_e("FEATURE","javapaper"); ?>
    </div>
    <?php the_post_thumbnail('post-thumbnail'); ?>
  </div>
  <div class="category1-jbottom">
    <div class="category1-jbottomleft">
      <div class="category1-time"> <a href="<?php  echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" rel="author"> <?php printf( __( ' %s', 'javapaper' ), get_the_author() ); ?></a><span>
        <?php esc_attr_e(" on ","javapaper"); ?>
        </span>
        <?php the_time('M j, Y') ?>
    <div class="module9-view">
	<span class="view2"><?php if (function_exists("javapaper_get_post_view")) { ?>
   <?php echo javapaper_get_post_view(); ?><?php } else { ?><?php } ?></span>
    <?php if (function_exists("javapaper_reading_time")) { ?>
	<?php echo javapaper_reading_time(get_the_ID()); ?><?php } else { ?> <?php } ?>
	</div>
      </div>
      <div class="module9-titlebig">
        <h2><a href="<?php  the_permalink(); ?>">
          <?php  the_title(); ?>
          </a></h2>
      </div>
      <div class="module31-content">
	   <div class="hide-thumb">
	  <?php javapaper_one_excerpts() ?>
	  </div>
        <div class="module9-readmore"> <a href="<?php  the_permalink(); ?>">
          <?php esc_attr_e('Read the Post &#8594;	', 'javapaper'); ?>
          </a> </div>
      </div>
    </div>
    <div class="category1-jbottomright">
      <div class="module9-jbottomright2">
        <div  class="authors-top10">
          <div class="category1-authoravatarwrapper"> <?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'javapaper_author_bio_avatar_size', 225 ) ); ?> </div>
          <div class="category1-desc10">
            <div class="category1-job10"> <?php echo get_the_author_meta('Position');?> </div>
            <div  class="category1-name">
              <h4> <a href="<?php  echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" rel="author"> <?php printf( __( '%s', 'javapaper' ), get_the_author() ); ?></a> </h4>
            </div>
            <div class="category1-job10">
              <?php the_author_posts(); ?>
              <a href="<?php  echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>">
              <?php esc_attr_e('POSTS', 'javapaper'); ?>
              </a> </div>
          </div>
          <div class="category1-desc"> <?php echo substr( get_the_author_meta('user_description') , 0 , 130 );?>
            <?php esc_attr_e(' ...', 'javapaper'); ?>
            <div class="module9-readmore"> <a href="<?php  echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" rel="author">
              <?php esc_attr_e('All Articles by This Author &#8594;', 'javapaper'); ?>
              </a> </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
