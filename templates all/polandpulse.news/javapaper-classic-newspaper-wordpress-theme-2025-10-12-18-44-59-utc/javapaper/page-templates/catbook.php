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
              <?php if( get_post_meta($post->ID, "book_author", true) ): ?>
              <div class="col-md-12 cptfield">
                <div class="cpt-inside"> <?php echo get_post_meta($post->ID, "book_author", true); ?> </div>
              </div>
              <?php else : ?>
              <?php endif; ?>
              <?php if( get_post_meta($post->ID, "total_pages", true) ): ?>
              <div class="col-md-12 cptfield">
                <div class="cpt-inside"><?php echo get_post_meta($post->ID, "total_pages", true); ?> </div>
              </div>
              <?php else : ?>
              <?php endif; ?>
              <?php if( get_post_meta($post->ID, "isbn", true) ): ?>
              <div class="col-md-12 cptfield">
                <div class="cpt-inside"><?php echo get_post_meta($post->ID, "isbn", true); ?> </div>
              </div>
              <?php else : ?>
              <?php endif; ?>
              <?php if( get_post_meta($post->ID, "original_language", true) ): ?>
              <div class="col-md-12 cptfield">
                <div class="cpt-inside"><?php echo get_post_meta($post->ID, "original_language", true); ?> </div>
              </div>
              <?php else : ?>
              <?php endif; ?>
              <?php if( get_post_meta($post->ID, "literary_awards", true) ): ?>
              <div class="col-md-12 cptfield">
                <div class="cpt-inside"> <?php echo  get_post_meta($post->ID, "literary_awards", true ); ?> </div>
              </div>
              <?php else : ?>
              <?php endif; ?>
              <?php if( get_post_meta($post->ID, "book_description", true) ): ?>
              <div class="col-md-12 cptfield">
                <div class="cpt-inside"> <?php echo  get_post_meta($post->ID, "book_description", true ); ?> </div>
              </div>
              <?php else : ?>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
