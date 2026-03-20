<?php
/*
 * Content display template.
 */
?>

<header class="cpt-titleheader">
  <div class="entry-cat"> <?php echo javapaper_taxonomies_terms_links(', '); ?> </div>
  <?php if ( is_single() ) : ?>
  <h1 class="entry-title">
    <?php the_title(); ?>
  </h1>
  <?php else : ?>
  <h2 class="entry-title"> <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'javapaper' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark">
    <?php the_title(); ?>
    </a> </h2>
  <?php endif; // is_single() ?>
  <?php if( get_post_meta($post->ID, "bsubtitle", true) ): ?>
  <div class="cpt-subtitle"><?php echo get_post_meta($post->ID, "bsubtitle", true); ?> </div>
  <?php else : ?>
  <?php endif; ?>
</header>
<!-- .entry-header -->
<div class="cpt-content">
  <div class="entry-content ctest">
    <?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'javapaper' ) ); ?>
  </div>
  <!-- .entry-content -->
</div>
