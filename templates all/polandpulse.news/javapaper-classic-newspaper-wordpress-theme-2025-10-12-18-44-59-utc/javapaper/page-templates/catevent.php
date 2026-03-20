<?php
/*
 * Content display template for single book
 */
?>

<div class="category1-wrapper">
  <!-- START LOOP -->
  <div class="cpt-boxcategory">
    <div class="sticky-text">
      <?php esc_attr_e("FEATURE","javapaper"); ?>
    </div>
    <div class="cpt-jbottom">
      <div class="col-md-4">
        <div class="cpt-sidebar">
          <div class="row">
            <?php if( get_post_meta($post->ID, "event_schedule", true) ): ?>
            <div class="col-md-12 cptfield">
              <div class="cpt-inside">
                <?php echo  get_post_meta($post->ID, "event_schedule", true ); ?> </div>
            </div>
            <?php else : ?>
            <?php endif; ?>
            <?php if( get_post_meta($post->ID, "Place", true) ): ?>
            <div class="col-md-12 cptfield">
              <div class="cpt-inside">
                <?php echo get_post_meta($post->ID, "Place", true); ?> </div>
            </div>
            <?php else : ?>
            <?php endif; ?>
			
            <?php if( get_post_meta($post->ID, "Contact", true) ): ?>
            <div class="col-md-12 cptfield">
              <div class="cpt-inside">
                <?php echo get_post_meta($post->ID, "Contact", true); ?> </div>
            </div>
            <?php else : ?>
            <?php endif; ?>
			
            <?php if( get_post_meta($post->ID, "event_description1", true) ): ?>
            <div class="col-md-12 cptfield">
              <div class="cpt-inside">
                <?php echo get_post_meta($post->ID, "event_description1", true); ?> </div>
            </div>
            <?php else : ?>
            <?php endif; ?>
			
            <?php if( get_post_meta($post->ID, "Event_description2", true) ): ?>
            <div class="col-md-12 cptfield">
              <div class="cpt-inside">
                <?php echo get_post_meta($post->ID, "Event_description2", true); ?> </div>
            </div>
            <?php else : ?>
            <?php endif; ?>
            <?php if( get_post_meta($post->ID, "Event_description3", true) ): ?>
            <div class="col-md-12 cptfield">
              <div class="cpt-inside">
                <?php echo get_post_meta($post->ID, "Event_description3", true); ?> </div>
            </div>
            <?php else : ?>
            <?php endif; ?>
            </div>
		</div>
      </div>
      <div class="col-md-8">
        <div class="module9-jbottomright2">
		<?php single_cat_title(); ?>
          <div class="module9-titlebig">
            <h1><a href="<?php  the_permalink(); ?>">
              <?php  the_title(); ?>
              </a></h1>
          </div>
          <div class="cpt-desc">
		  <?php the_post_thumbnail('post-thumbnail'); ?>
		  <div class="cpt-eventcontent"> <?php echo javapaper_content(30); ?>
			<div class="module9-readmore">
			<a href="<?php the_permalink(); ?>"><?php esc_html_e('Event Detail &#8594;	', 'javapaper'); ?></a></div>
        </div>	
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
