<?php
/*
 * Javapaper Theme's Functions file, this is the heart of theme, modification directly is not recommended.
 * Javapaper Supports Child Themes, it is the way to go.
 * Use a child theme for customization (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes).
 * @subpackage javapaper_One
 * @since Javapaper 1.0
 */

/** Primary content width according to the design and stylesheet.*/
if ( ! isset( $content_width ) ) {
	$content_width = 665;
}
/** Javapaper supported features and Registering defaults*/
if( !function_exists( 'javapaper_setup' ) ) :

function javapaper_setup() {
/** Making Javapaper ready for translation.
 * Translations can be added to the /languages/ directory. Sample javapaper.pot file is included.*/
	load_theme_textdomain( 'javapaper', get_template_directory() . '/languages' );
	// Adds RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );	
	// Let WordPress manage the document title starting theme version 1.7.1
	add_theme_support( 'title-tag' );
	// Woocommerce support
	add_theme_support( 'woocommerce' );
	// GUTENBERG support
	add_theme_support( 'wp-block-styles' );
	// Adds support for Navigation menu, Javapaper uses wp_nav_menu() in one location.
    add_theme_support('nav_menus');	
	register_nav_menu( 'primary_menu', esc_html__( 'Primary Menu', 'javapaper' ) );
	register_nav_menu( 'footer_menu', esc_html__( 'Footer Menu', 'javapaper' ) );	
	// Uncomment the following two lines to add support for post thumbnails - for classic blog layout
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 980, 999 ); // Unlimited height, soft crop
	//Defining home page thumbnail size
	add_image_size('medium-thumbnail', 800, 450, true);
	add_image_size('excerpt-thumbnail', 250, 150, true);	
}
endif; //Javapaper setup
add_action( 'after_setup_theme', 'javapaper_setup' );
	// adding post format support
	add_theme_support( 'post-formats',
		array(
			'gallery',           // gallery of images
			'video',             // video
			'audio'             // audio		
		)
	);
 
/** Enqueueing scripts and styles for front-end of the javapaper Framework.*/ 
function javapaper_scripts_styles() {
	global $wp_styles;

/** Adds JavaScript to pages with the comment form to support */
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );
 	/*Register jquery*/
	wp_enqueue_script( 'jquery');
 	/*js for GENERAL*/
	wp_enqueue_script('javapaper-general', get_template_directory_uri() . '/js/general.min.js', array(), '1.0', true );
  	/*js for Columns*/
	wp_enqueue_script('javapaper-columns', get_template_directory_uri() . '/js/columnizer.min.js', array(), '1.1', true );  
  	/*js for Sticky Bar*/
	wp_enqueue_script('sticky-sidebar', get_template_directory_uri() . '/js/theia-sticky-sidebar-min.js', array(), '1.2', true );  
   
	/* CSS for FONTAWESOME*/   
	wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/css/font-awesome/css/font-awesome.min.css' );		
	/* CSS for BOOTSTRAP.*/
	wp_enqueue_style( 'javapaper-custom-style', get_template_directory_uri() . '/css/bootstrap.min.css' );	    
	/* Loads javapaper's main stylesheet and the custom stylesheet.*/
	wp_enqueue_style( 'javapaper-style', get_stylesheet_uri(), false, '1.7.8' );
	wp_enqueue_style( 'additional-style', get_template_directory_uri() . '/additional.css' );
}
add_action( 'wp_enqueue_scripts', 'javapaper_scripts_styles' );

/** Default Nav Menu fallback to Pages menu */
function javapaper_page_menu_args( $args ) {
	if ( ! isset( $args['show_home'] ) )
		$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'javapaper_page_menu_args' );	
	
/** Registers the main widgetized sidebar area. */
function javapaper_widgets_init() {
	register_sidebar( array(
		'name' => esc_html__( 'Main Sidebar', 'javapaper' ),
		'id' => 'javapaper-sidebar',
		'description' => esc_html__( 'This is a Sitewide sidebar which appears on posts and pages', 'javapaper' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<p class="widget-title">',
		'after_title' => '</p>',
	) );
	register_sidebar( array(
		'name' => esc_html__( 'Main Header 1', 'javapaper' ),
		'id' => 'javapaper-header1',
		'description' => esc_html__( 'This is a Sitewide header which appears on posts and pages', 'javapaper' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<p class="widget-title">',
		'after_title' => '</p>',
	) );	
	register_sidebar( array(
		'name' => esc_html__( 'Main Header 2', 'javapaper' ),
		'id' => 'javapaper-header2',
		'description' => esc_html__( 'This is a Sitewide header which appears on posts and pages', 'javapaper' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<p class="widget-title">',
		'after_title' => '</p>',
	) );	
	register_sidebar( array(
		'name' => esc_html__( 'Footer 1', 'javapaper' ),
		'id' => 'javapaper-footer1',
		'description' => esc_html__( 'This is a Sitewide sidebar which appears on posts and pages', 'javapaper' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<div class="widget-title">',
		'after_title' => '</div>',
	) );	
	register_sidebar( array(
		'name' => esc_html__( 'Footer 2', 'javapaper' ),
		'id' => 'javapaper-footer2',
		'description' => esc_html__( 'This is a Sitewide sidebar which appears on posts and pages', 'javapaper' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<p class="widget-title">',
		'after_title' => '</p>',
	) );
	register_sidebar( array(
		'name' => esc_html__( 'Footer 3', 'javapaper' ),
		'id' => 'javapaper-footer3',
		'description' => esc_html__( 'This is a Sitewide sidebar which appears on posts and pages', 'javapaper' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<p class="widget-title">',
		'after_title' => '</p>',
	) );
	register_sidebar( array(
		'name' => esc_html__( 'Footer 4', 'javapaper' ),
		'id' => 'javapaper-footer4',
		'description' => esc_html__( 'This is a Sitewide sidebar which appears on posts and pages', 'javapaper' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<p class="widget-title">',
		'after_title' => '</p>',
	) );
	register_sidebar( array(
		'name' => esc_html__( 'Footer 5', 'javapaper' ),
		'id' => 'javapaper-footer5',
		'description' => esc_html__( 'This is a Sitewide sidebar which appears on posts and pages', 'javapaper' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<p class="widget-title">',
		'after_title' => '</p>',
	) );	
	register_sidebar( array(
		'name' => esc_html__( 'Staff Sidebar', 'javapaper' ),
		'id' => 'staff-sidebar',
		'description' => esc_html__( 'This is a Sitewide header which appears on slide Menu', 'javapaper' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<p class="widget-title">',
		'after_title' => '</p>',
	) );	
	register_sidebar( array(
		'name' => esc_html__( 'Event Sidebar', 'javapaper' ),
		'id' => 'event-sidebar',
		'description' => esc_html__( 'This is a Sitewide header which appears on slide Menu', 'javapaper' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<p class="widget-title">',
		'after_title' => '</p>',
	) );	
	register_sidebar( array(
		'name' => esc_html__( 'Book Sidebar', 'javapaper' ),
		'id' => 'book-sidebar',
		'description' => esc_html__( 'This is a Sitewide header which appears on slide Menu', 'javapaper' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<p class="widget-title">',
		'after_title' => '</p>',
	) );			
	register_sidebar( array(
		'name' => esc_html__( 'Slide Menu', 'javapaper' ),
		'id' => 'javapaper-slidemenu',
		'description' => esc_html__( 'This is a Sitewide header which appears on slide Menu', 'javapaper' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<p class="widget-title">',
		'after_title' => '</p>',
	) );	
}
add_action( 'widgets_init', 'javapaper_widgets_init' );

if ( ! function_exists( 'javapaper_content_nav' ) ) :
/**
 * Displays navigation to next/previous pages when applicable.
 *
 * @since Javapaper 1.0
 */
function javapaper_content_nav( $html_id ) {
	global $wp_query;
	$html_id = esc_attr( $html_id );
	if ( $wp_query->max_num_pages > 1 ) : ?>
		<nav id="<?php echo ent2ncr($html_id); ?>" class="navigation" role="navigation">
			<div class="assistive-text"><?php _e( 'Post navigation', 'javapaper' ); ?></div>
			<div class="nav-previous alignleft"><?php next_posts_link( esc_html__( '<span class="meta-nav">&larr;</span> Older posts', 'javapaper' ) ); ?></div>
			<div class="nav-next alignright"><?php previous_posts_link( esc_html__( 'Newer posts <span class="meta-nav">&rarr;</span>', 'javapaper' ) ); ?></div>
		</nav>
	<?php endif;
}
endif;

if ( ! function_exists( 'javapaper_comment' ) ) :
/**
 * Template for comments and pingbacks.
 * Used as a callback by wp_list_comments() for displaying the comments.
 */
function javapaper_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
		// Display trackbacks differently than normal comments.
	?>
	<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
		<p><?php esc_html__( 'Pingback:', 'javapaper' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( esc_html__( '(Edit)', 'javapaper' ), '<span class="edit-link">', '</span>' ); ?></p>
	<?php break;
		default :
		// Proceed with normal comments.
		global $post;
	?>
	<div <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<header class="comment-partleft">
				<?php echo get_avatar( $comment, 115 );	?>
			</header><!-- .comment-meta -->			
			<header class="comment-partrigh">
				<?php
					printf( '<cite class="fn">%1$s %2$s</cite>',
						get_comment_author_link(),
						// Adds Post Author to comments posted by the article writer
						( $comment->user_id === $post->post_author ) ? '<span> ' . esc_html__( 'Post author', 'javapaper' ) . '</span>' : ''
					);
					echo "<div class='comment-time'>";
					printf( '<a href="%1$s"><time datetime="%2$s">%3$s</time></a>',
						esc_url( get_comment_link( $comment->comment_ID ) ),
						get_comment_time( 'c' ),
						/* translators: 1: date */
						sprintf( esc_html__( '%1$s', 'javapaper' ), get_comment_date() )
					);
					echo "</div>";
				?>
			<?php if ( '0' == $comment->comment_approved ) : ?>
				<div class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'javapaper' ); ?></div>
			<?php endif; ?>

			<section class="comment-content comment">
				<?php comment_text(); ?>
				<?php edit_comment_link( esc_html__( 'Edit', 'javapaper' ), '<div class="edit-link">', '</div>' ); ?>
			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => esc_html__( 'Leave Reply', 'javapaper' ),  'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->	
			</section><!-- .comment-content -->					
			</header><!-- .comment-meta -->
		</article><!-- #comment-## -->
		</div>
	<?php
		break;
	endswitch; // end comment_type check
}
endif;

if ( ! function_exists( 'javapaper_entry_meta' ) ) :
/**For Meta information for categories, tags, permalink, author, and date.*/
function javapaper_entry_meta() {
	// Translators: used between list items, there is a space after the comma.
	$categories_list = get_the_category_list( esc_html__( ', ', 'javapaper' ) );

	// Translators: used between list items, there is a space after the comma.
	$tag_list = get_the_tag_list( '', esc_html__( ', ', 'javapaper' ) );

	$date = sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a>',
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() )
	);

	$author = sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( esc_html__( 'View all posts by %s', 'javapaper' ), get_the_author() ) ),
		get_the_author()
	);

// Translators: 1 is category, 2 is tag, 3 is the date and 4 is the author's name.
	if ( $tag_list ) {
		$utility_text = esc_html__( 'This entry was posted in %1$s and tagged %2$s on %3$s<span class="by-author"> by %4$s</span>.', 'javapaper' );
	} elseif ( $categories_list ) {
		$utility_text = esc_html__( 'This entry was posted in %1$s on %3$s<span class="by-author"> by %4$s</span>.', 'javapaper' );
	} else {
		$utility_text = esc_html__( 'This entry was posted on %3$s<span class="by-author"> by %4$s</span>.', 'javapaper' );
	}
	printf(
		$utility_text,
		$categories_list,
		$tag_list,
		$date,
		$author
	);
}
endif;
/** WordPress body class Extender */
function javapaper_body_class( $classes ) {
	$background_color = get_background_color();

	if ( is_page_template( 'page-templates/full-width.php' ) )
		$classes[] = 'full-width';

	if ( empty( $background_color ) )
		$classes[] = 'custom-background-empty';
	elseif ( in_array( $background_color, array( 'fff', 'ffffff' ) ) )
		$classes[] = 'custom-background-white';

	// Enable custom font class only if the font CSS is queued to load.
	if ( wp_style_is( 'javapaper-fonts', 'queue' ) )
		$classes[] = 'custom-font-enabled';

	if ( ! is_multi_author() )
		$classes[] = 'single-author';

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}
	return $classes;
}
add_filter( 'body_class', 'javapaper_body_class' );

/*
 * Adjusts content_width value for full-width and single image attachment
 * templates, and when there are no active widgets in the sidebar.
 *
 * @since Javapaper 1.0
 */
function javapaper_content_width() {
	if ( is_page_template( 'page-templates/full-width.php' ) || is_attachment() || ! is_active_sidebar( 'javapaper-sidebar' ) ) {
		global $content_width;
		$content_width = 1040;
	}
}
add_action( 'template_redirect', 'javapaper_content_width' );

/* Javapaper welcome text */
require_once( get_template_directory() . '/inc/tgm.php' );
require_once( get_template_directory() . '/inc/extra-functions.php' );
require_once( get_template_directory() . '/inc/redux_one_click.php' );
require_once( get_template_directory() . '/inc/javapaper-sguide.php' );
require_once(get_template_directory() . '/aq_resizer.php');

// Load Redux-related options via hook with proper priority
function javapaper_load_theme_files() {
    /* tribunalex welcome text - Check if theme was just activated */
    if (is_admin() && isset($_GET['activated'])) {
        global $pagenow;
        if ($pagenow == "themes.php") {
            wp_redirect('themes.php?page=javapaper_after_instalation');
        }
    }    
    require_once(get_template_directory() . '/inc/javapaper-options.php'); // Redux config
}
add_action('after_setup_theme', 'javapaper_load_theme_files', 5); // Early priority (5)

/*Delete unnecessary script value on js */
add_action( 'template_redirect', function(){
    ob_start( function( $buffer ){
        $buffer = str_replace( array( 'type="text/javascript"', "type='text/javascript'" ), '', $buffer );        
        return $buffer;
    });
});
 
/****************************************************
/* TOTAL CONTENT'S WORD FOR MODULE 
*****************************************************/
function javapaper_content($limit) {
  $content = explode(' ', get_the_content(), $limit);
  if (count($content)>=$limit) {
    array_pop($content);
    $content = implode(" ",$content).'.';
  } else {
    $content = implode(" ",$content);
  }	
	$content = preg_replace("/<img[^>]+\>/i", "", $content); // removes images
	$content = preg_replace('/<iframe.*?>/', "", $content); // removes iframes  
	$content = strip_tags($content, '<strong>'); // Temporarily keep <strong>
	$content = str_replace(['<strong>', '</strong>'], '', $content); // Remove <strong> tags completely
	$content =  preg_replace("/\<h1(.*)\>(.*)\<\/h1\>/","", $content); //remove <h1>	
	$content =  preg_replace("/\<h2(.*)\>(.*)\<\/h2\>/","", $content); //remove <h2>	
	$content =  preg_replace("/\<h3(.*)\>(.*)\<\/h3\>/","", $content); //remove <h3>	
	$content =  preg_replace("/\<h4(.*)\>(.*)\<\/h4\>/","", $content); //remove <h4>	
	$content =  preg_replace("/\<h5(.*)\>(.*)\<\/h5\>/","", $content); //remove <h5>	
	$content =  preg_replace("/\<h6(.*)\>(.*)\<\/h6\>/","", $content); //remove <h6>		
	$content = preg_replace('/\[.+\]/','', $content);	
	$content = apply_filters('the_content', $content); 
	$content = str_replace(']]>', ']]&gt;', $content);
	return $content;
}
/****************************************************
/* LIMIT WORD FOR MODULE
*****************************************************/
if( ! function_exists( 'javapaper_limit_words' ) ) {
	function javapaper_limit_words($string, $word_limit) {
		$words = explode(' ', $string);
		return implode(' ', array_slice($words, 0, $word_limit));
	}
}
/****************************************************
/* Author: Ramez Bdiwi
*****************************************************/
! defined( 'ABSPATH' ) and exit;
if ( ! function_exists( 'javapaper_fb_set_featured_image' ) ) {
	add_action( 'save_post', 'javapaper_fb_set_featured_image' );
	function javapaper_fb_set_featured_image() {	
			if ( ! isset( $GLOBALS['post']->ID ) )
				return NULL;				
            if ( has_post_thumbnail( get_the_ID() ) )
                return NULL;				
            $args = array(
                'numberposts' => 1,
                'order' => 'ASC', // DESC for the last image
                'post_mime_type' => 'image',
                'post_parent' => get_the_ID(),
                'post_status' => NULL,
                'post_type' => 'attachment'
			);			
            $attached_image = get_children( $args );
            if ( $attached_image ) {
                foreach ( $attached_image as $attachment_id => $attachment )
					set_post_thumbnail( get_the_ID(), $attachment_id );
			}			
	}
}
/****************************************************
/* BREADCRUMB
*****************************************************/
function javapaper_breadcrumb() {
  $delimiter = ' > ';
  $home = esc_html__( 'Home', 'javapaper' ); // text for home link
  $before = ''; // tag before the current crumb
  $after = ''; // tag after the current crumb
  if ( !is_home() && !is_front_page() || is_paged() ) {
    echo '<div class="crumbs">';
    global $post;
    $homeLink = esc_url(home_url('/')) ;
    echo esc_url($before). '<a href="' . $homeLink . '">' . $home . '</a> ' . $delimiter . ' '. $after;
    if ( is_category() ) {
      global $wp_query;
      $cat_obj = $wp_query->get_queried_object();
      $thisCat = $cat_obj->term_id;
      $thisCat = get_category($thisCat);
      $parentCat = get_category($thisCat->parent);
      if ($thisCat->parent != 0) echo(get_category_parents($parentCat, TRUE, ' ' . $delimiter . ' '));
      echo esc_html($before)  . single_cat_title('', false)  . $after;

    } elseif ( is_day() ) {
      echo esc_url($before).'<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' '. $after;
      echo esc_url($before).'<a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' '. $after;
      echo esc_html($before) . get_the_time('d') . $after;

    } elseif ( is_month() ) {
      echo esc_url($before) .'<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' '.$after;
      echo esc_html($before) . get_the_time('F') . $after;

    } elseif ( is_year() ) {
      echo esc_html($before) . get_the_time('Y') . $after;

    } elseif ( is_single() && !is_attachment() ) {
      if ( get_post_type() != 'post' ) {
        $post_type = get_post_type_object(get_post_type());
        $slug = $post_type->rewrite;
        echo esc_url($before).'<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a> ' . $delimiter . ' '.$after;
        echo esc_html($before) . esc_attr(get_the_title()) . $after;
      } else {
        $cat = get_the_category(); $cat = $cat[0];
        echo get_category_parents($cat, TRUE, ' ' .  ' ');
      }

    } elseif ( is_attachment() ) {
      $parent = get_post($post->post_parent);
      $cat = get_the_category($parent->ID); $cat = $cat[0];
      echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
      echo esc_url($before).'<a href="' . esc_url( get_permalink($parent) ) . '">' . $parent->post_title . '</a> ' . $delimiter . ' '.$after;
      echo esc_html($before) . esc_attr(get_the_title()) . $after;

    } elseif ( is_page() && !$post->post_parent ) {
      echo esc_html($before) . esc_attr(get_the_title()) . $after;

    } elseif ( is_page() && $post->post_parent ) {
      $parent_id  = $post->post_parent;
      $breadcrumbs = array();
      while ($parent_id) {
        $page = get_page($parent_id);
        $breadcrumbs[] = esc_url($before).'<a href="' . esc_url( get_permalink($page->ID) ). '">' . esc_attr(get_the_title($page->ID)) . '</a>'. $after;
        $parent_id  = $page->post_parent;
      }
      $breadcrumbs = array_reverse($breadcrumbs);
      foreach ($breadcrumbs as $crumb) echo esc_html($crumb) . ' ' . $delimiter . ' ';
      echo esc_html($before) . esc_attr(get_the_title()) . $after;

    } elseif ( is_search() ) {
      echo esc_html($before) . 'Search results for "' . esc_attr(get_search_query()) . '"' . $after;

    } elseif ( is_tag() ) {
      echo esc_html($before) . 'Posts tagged "' . esc_attr(single_tag_title('', false)) . '"' . $after;

    } elseif ( is_author() ) {
       global $author;
      $userdata = get_userdata($author);
      echo esc_html($before) . 'Articles posted by ' . esc_attr($userdata->display_name) . $after;

    } elseif ( is_404() ) {
      echo esc_html($before) . 'Error 404' . $after;
    }

    if ( get_query_var('paged') ) {
      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
      echo esc_attr__('Page', 'javapaper') . ' ' . get_query_var('paged');
      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
    }

    echo '</div>';

  }
} // end the_breadcrumbs()	

/*The tag */
add_filter( 'widget_tag_cloud_args', 'javapaper_tag_cloud_args' );
function javapaper_tag_cloud_args( $args ) {
	$args['number'] = 20; // show less tags
	$args['largest'] = 16; // make largest and smallest the same - i don't like the varying font-size look
	$args['smallest'] = 16;
	$args['unit'] = 'px';
	return $args;
}
// Body open for theme test
if ( ! function_exists( 'wp_body_open' ) ) {
    function wp_body_open() {
        do_action( 'wp_body_open' );
    }
}
/**Display Bootstrap numbered pagination code.*/
function javapaper_numbered_pages($args = null) {
    $defaults = array(
        'page' => null, 
        'pages' => null, 
        'range' => 3, 
        'gap' => 3, 
        'anchor' => 1,
        'before' => '<ul class="pagination">', 
        'after' => '</ul>',
        'nextpage' => esc_attr__('&raquo;','javapaper'), 
        'previouspage' => esc_attr__('&laquo;','javapaper'),
        'echo' => 1
    );
    $r = wp_parse_args($args, $defaults);
    extract($r, EXTR_SKIP);
    if (!$page && !$pages) {
        global $wp_query;
        $page = get_query_var('paged');
        $page = !empty($page) ? intval($page) : 1;
        $posts_per_page = intval(get_query_var('posts_per_page'));
        $pages = intval(ceil($wp_query->found_posts / $posts_per_page));
    }
    
    $output = "";
    if ($pages > 1) {   
        $output .= "$before";
        $ellipsis = "<li></li>";
        if ($page > 1 && !empty($previouspage)) {
            $output .= "<li><a href='" . get_pagenum_link($page - 1) . "'>$previouspage</a></li>";
        }        
        $min_links = $range * 2 + 1;
        $block_min = min($page - $range, $pages - $min_links);
        $block_high = max($page + $range, $min_links);
        $left_gap = (($block_min - $anchor - $gap) > 0) ? true : false;
        $right_gap = (($block_high + $anchor + $gap) < $pages) ? true : false;

        if ($left_gap && !$right_gap) {
            $output .= sprintf('%s%s%s', 
                javapaper_numbered_pages_loop(1, $anchor), 
                $ellipsis, 
                javapaper_numbered_pages_loop($block_min, $pages, $page)
            );
        }
        else if ($left_gap && $right_gap) {
            $output .= sprintf('%s%s%s%s%s', 
                javapaper_numbered_pages_loop(1, $anchor), 
                $ellipsis, 
                javapaper_numbered_pages_loop($block_min, $block_high, $page), 
                $ellipsis, 
                javapaper_numbered_pages_loop(($pages - $anchor + 1), $pages)
            );
        }
        else if ($right_gap && !$left_gap) {
            $output .= sprintf('%s%s%s', 
                javapaper_numbered_pages_loop(1, $block_high, $page),
                $ellipsis,
                javapaper_numbered_pages_loop(($pages - $anchor + 1), $pages)
            );
        }
        else {
            $output .= javapaper_numbered_pages_loop(1, $pages, $page);
        }
        if ($page < $pages && !empty($nextpage)) {
            $output .= "<li><a href='" . get_pagenum_link($page + 1) . "'>$nextpage</a></li>";
        }
        $output .= $after;
    }
    if ($echo) {
        echo wp_kses_post($output);
    }
    return $output;
}
/* Helper function for pagination which builds the page links. */
function javapaper_numbered_pages_loop($start, $max, $page = 0) {
    $output = "";
    for ($i = $start; $i <= $max; $i++) {
        $output .= ($page === intval($i)) 
            ? "<li><span class='emm-page emm-current'>$i</span></li>" 
            : "<li><a href='" . get_pagenum_link($i) . "' class='emm-page'>$i</a></li>";
    }
    return $output;
}
// Registering GoogleFont
function javapaper_fonts_url() {
    $font_url = '';
    if ( 'off' !== _x( 'on', 'Google font: on or off', 'javapaper' ) ) {
        $font_url = add_query_arg( 'family', urlencode( 'Playfair Display|Roboto:400,500,600,700,700italic,700&subset=latin,latin-ext' ), "//fonts.googleapis.com/css" );
    }
    return $font_url;
}
function javapaper_scripts() {
    wp_enqueue_style( 'javapaper_studio-fonts', javapaper_fonts_url(), array(), '1.0.0' );
}
add_action( 'wp_enqueue_scripts', 'javapaper_scripts' );