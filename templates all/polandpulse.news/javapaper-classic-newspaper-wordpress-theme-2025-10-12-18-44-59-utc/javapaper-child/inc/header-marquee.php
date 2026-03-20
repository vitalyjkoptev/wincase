  <?php
        $post_formats = array('audio', 'image', 'video', 'link', 'gallery');
            $blog = array(
                'post_type'         => 'post',
                'post_status'       => 'publish',
                'posts_per_page'    => 5,
                'ignore_sticky_posts' => 1,
                'tax_query' => array( array(
                    'taxonomy' => 'post_format',
                    'field' => 'slug',
                    'terms' => array('post-format-aside', 'post-format-gallery', 'post-format-link', 'post-format-image', 'post-format-quote', 'post-format-status', 'post-format-audio', 'post-format-chat', 'post-format-video'),
                    'operator' => 'NOT IN'
                    ) 
                    ),
                    );
            ?>
    <!-- START LOOP -->

      <?php 
	  $count = 0;
                $the_query = new WP_Query( $blog );
                    if($the_query->have_posts()):
                        while($the_query->have_posts()): 
                            $the_query->the_post(); 
	
	?>
      <li> <a class="entry-title" href="<?php the_permalink();?>"> <?php echo wp_trim_words( get_the_title(), 8 ); ?> </a> </li>
      <?php  endwhile; ?>
      <?php  wp_reset_query(); 
			endif;?>
