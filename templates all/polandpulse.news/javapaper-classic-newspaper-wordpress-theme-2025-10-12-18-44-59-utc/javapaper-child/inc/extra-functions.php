<?php
/**
 * Javapaper Extra Functions
 */
function javapaper_one_excerpts() { ?>

<div class="entry-summary">
  <div class="excerpt-thumb">
    <?php if (  (function_exists('has_post_thumbnail')) && (has_post_thumbnail())) : ?>
    <a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'javapaper' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark">
    <?php the_post_thumbnail('excerpt-thumbnail', 'class=alignleft'); ?>
    </a>
    <?php endif;?>
  </div>
  <?php the_excerpt(); ?>
</div>
<!-- .entry-summary -->
<?php }

?>
