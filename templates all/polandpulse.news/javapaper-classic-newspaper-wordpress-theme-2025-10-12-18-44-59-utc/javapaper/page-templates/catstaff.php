<?php
/*
 * Content display template for staff category
 */
?>


  <!-- START LOOP -->
  <div class="cpt-boxcategory">
    <div class="cpt-jbottom">
      <div class="col-md-4">
        <div class="cpt-catthumbnail">
		<?php the_post_thumbnail('post-thumbnail'); ?>
		</div>
      </div>
      <div class="col-md-8">
        <div class="module9-jbottomright2">
          <div class="module9-titlebig">
            <h2><a href="<?php  the_permalink(); ?>">
              <?php  the_title(); ?>
              </a></h2>
          </div>		  
          <div class="cpt-desc">
            <div class="row">
              <?php if( get_post_meta($post->ID, "staff_Description1", true) ): ?>
              <div class="col-md-12 cptfield">
                <div class="cpt-inside"> <?php echo get_post_meta($post->ID, "staff_Description1", true); ?> </div>
              </div>
              <?php else : ?>
              <?php endif; ?>
              <?php if( get_post_meta($post->ID, "staff_Description2", true) ): ?>
              <div class="col-md-12 cptfield">
                <div class="cpt-inside"><?php echo get_post_meta($post->ID, "staff_Description2", true); ?> </div>
              </div>
              <?php else : ?>
              <?php endif; ?>
              <?php if( get_post_meta($post->ID, "staff_Description3", true) ): ?>
              <div class="col-md-12 cptfield">
                <div class="cpt-inside"><?php echo get_post_meta($post->ID, "staff_Description3", true); ?> </div>
              </div>
              <?php else : ?>
              <?php endif; ?>
              <?php if( get_post_meta($post->ID, "staff_Description4", true) ): ?>
              <div class="col-md-12 cptfield">
                <div class="cpt-inside"><?php echo get_post_meta($post->ID, "staff_Description4", true); ?> </div>
              </div>
              <?php else : ?>
              <?php endif; ?>
              <?php if( get_post_meta($post->ID, "staff_Description5", true) ): ?>
              <div class="col-md-12 cptfield">
                <div class="cpt-inside"> <?php echo  get_post_meta($post->ID, "staff_Description5", true ); ?> </div>
              </div>
              <?php else : ?>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

