<?php
/**
 * Qalam functions and definitions
 *
 * @package Qalam
 * @since 1.0.0
 * @version 2.4.0
 */

/**
 * Sets up theme defaults and registers various WordPress features
 * that Qalam supports.
 */
if ( ! function_exists( 'qalam_setup' ) ) :
	function qalam_setup() {

		// Makes theme available for translation.
		load_theme_textdomain( 'qalam', get_template_directory() . '/languages' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		// Add visual editor stylesheet support
		add_editor_style();

		// Adds RSS feed links to <head> for posts and comments.
		add_theme_support( 'automatic-feed-links' );

		// Add post formats
		add_theme_support( 'post-formats', array( 'video' ) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Add navigation menus
		register_nav_menus( array(
			'mobile'	=> esc_attr__( 'Mobile Menu', 'qalam' ),
			'top'		=> esc_attr__( 'Top Menu', 'qalam' ),
			'primary'	=> esc_attr__( 'Primary Menu', 'qalam' )
		) );

		// Add support for custom background and set default color
		add_theme_support( 'custom-background', array(
			'default-color' => 'ffffff',
			'default-image' => ''
		) );

		// Add suport for post thumbnails
		add_theme_support( 'post-thumbnails' );

		// Add theme support for Custom Logo.
		add_theme_support( 'custom-logo', array(
			'header-text' => array( 'site-title', 'site-description' ),
			'height'      => 32,
			'width'       => 200,
			'flex-width'  => true,
			'flex-height' => true,
		) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		// Add support for Block Styles.
		add_theme_support( 'wp-block-styles' );

		// Add support for full and wide align images.
		add_theme_support( 'align-wide' );

		// Add support for editor styles.
		add_theme_support( 'editor-styles' );

		// Enqueue editor styles.
		add_editor_style( 'style-editor.css' );

		// Add support for responsive embedded content.
		add_theme_support( 'responsive-embeds' );

		// Declare theme as WooCommerce compatible
		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );

	}

endif;
add_action( 'after_setup_theme', 'qalam_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function qalam_content_width() {

	$content_width = 800;

	/**
	 * Filter Qalam content width of the theme.
	 *
	 * @since Qalam 1.0
	 *
	 * @param $content_width integer
	 */
	$GLOBALS['content_width'] = apply_filters( 'qalam_content_width', $content_width );
}


// Include required files
require_once( 'includes/kirki-config.php' );
require_once( 'includes/bfi_thumb.php' );
require_once( 'includes/breadcrumbs.php' );
require_once( 'includes/class-tgm-plugin-activation.php' );
require_once( 'woocommerce/woocommerce-hooks.php' );

// Register custom fonts
function qalam_fonts_url() {
	$fonts_url = '';

	// If default font Open Sans is not disabled
	if ( get_theme_mod( 'default_font', 1 ) ) {
		$font_families = array();

		$font_families[] = 'Open Sans:300,300i,400,400i,600,600i,700,700i,800,800i';

		$query_args = array(
			'family' => urlencode( implode( '|', $font_families ) ),
			'subset' => urlencode( 'latin,latin-ext' ),
		);

		$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}

	return esc_url_raw( $fonts_url );
}

/**
 * Add preconnect for Google Fonts.
 *
 * @since Qalam 1.0
 *
 * @param array  $urls           URLs to print for resource hints.
 * @param string $relation_type  The relation type the URLs are printed.
 * @return array $urls           URLs to print for resource hints.
 */
function qalam_resource_hints( $urls, $relation_type ) {
	if ( wp_style_is( 'qalam-fonts', 'queue' ) && 'preconnect' === $relation_type ) {
		$urls[] = array(
			'href' => 'https://fonts.gstatic.com',
			'crossorigin',
		);
	}

	return $urls;
}
add_filter( 'wp_resource_hints', 'qalam_resource_hints', 10, 2 );

// Enqueue scripts and styles for front-end.
function qalam_scripts_styles() {

	// Add custom fonts, used in the main stylesheet.
	if ( get_theme_mod( 'default_font', 1 ) ) {
		wp_enqueue_style( 'qalam-fonts', qalam_fonts_url(), array(), null );
	}

	// Theme stylesheet.
	wp_enqueue_style( 'qalam-style', get_stylesheet_uri(), array(), wp_get_theme()->get( 'Version' ) );

	// RTL stylesheet
	if ( is_rtl() ) {
		wp_register_style( 'qalam-rtl', get_template_directory_uri() . '/rtl.css', array(), '' );
		wp_enqueue_style( 'qalam-rtl' );
	}

	// WooCommerce Custom stylesheet
	if ( class_exists( 'woocommerce' )  ) {
		wp_register_style( 'woocommerce-custom', get_theme_file_uri( '/woocommerce/woocommerce-custom.css' ), array(), '1.0' );
		wp_enqueue_style( 'woocommerce-custom' );
	}

	$qalam_l10n = array(
		'expand' => esc_attr__( 'Toggle child menu', 'qalam' ),
		'sticky_nav' => get_theme_mod( 'sticky_check', 0 ),
		'collapse_lists' => 'true'
	);

	wp_enqueue_script( 'qalam-frontend', get_theme_file_uri( '/assets/js/qalam.frontend.js' ), array( 'jquery' ), '1.0', true );

	wp_localize_script( 'qalam-frontend', 'qlm_frontend', $qalam_l10n );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}

add_action( 'wp_enqueue_scripts', 'qalam_scripts_styles' );

// Load heavy CSS libraries in footer
function qalam_footer_styles() {

	wp_enqueue_style( 'wppm-el-fontawesome', get_theme_file_uri( '/assets/css/all.min.css' ), array(), '1.0' );
}

add_action( 'wp_footer', 'qalam_footer_styles', 19 );

// Register theme widget areas
function qalam_widgets_init() {

	register_sidebar( array(
		'name' 			=> esc_attr__( 'Sidebar A', 'qalam' ),
		'id' 			=> 'default-sidebar',
		'description' 	=> esc_attr__( 'The main sidebar', 'qalam' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' 	=> "</aside>",
		'before_title' 	=> '<h3 class="widget-title">',
		'after_title' 	=> '</h3>',
		'handler'		=> 'sidebar'
	) );

	register_sidebar( array(
		'name' 			=> esc_attr__( 'Sidebar B', 'qalam' ),
		'id' 			=> 'sidebar-b',
		'description' 	=> esc_attr__( 'Additional sidebar for two sidebar layouts', 'qalam' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' 	=> "</aside>",
		'before_title' 	=> '<h3 class="widget-title">',
		'after_title' 	=> '</h3>',
		'handler'		=> 'sidebar'
	) );

	register_sidebar( array(
		'name' 			=> esc_attr__( 'Top widget area', 'qalam' ),
		'id' 			=> 'top-widget-area',
		'description' 	=> esc_attr__( 'Top widget area', 'qalam' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'	=> "</aside>",
		'before_title' 	=> '<h3 class="widget-title">',
		'after_title' 	=> '</h3>'
	) );

	register_sidebar( array(
		'name' 			=> esc_attr__( 'Header widget area', 'qalam' ),
		'id' 			=> 'header-widget-area',
		'description' 	=> esc_attr__( 'Header widget area', 'qalam' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'	=> "</aside>",
		'before_title' 	=> '<h3 class="widget-title">',
		'after_title' 	=> '</h3>'
	) );

	register_sidebar( array(
		'name' 			=> esc_attr__( 'Widget area before content', 'qalam' ),
		'id' 			=> 'widget-area-before-content',
		'description' 	=> esc_attr__( 'Widget area before content', 'qalam' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'	=> "</aside>",
		'before_title' 	=> '<h3 class="widget-title">',
		'after_title' 	=> '</h3>'
	) );

	register_sidebar( array(
		'name' 			=> esc_attr__( 'Widget area after content', 'qalam' ),
		'id' 			=> 'widget-area-after-content',
		'description' 	=> esc_attr__( 'Widget area after content', 'qalam' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'	=> "</aside>",
		'before_title' 	=> '<h3 class="widget-title">',
		'after_title' 	=> '</h3>'
	) );

	register_sidebar( array(
		'name' 			=> esc_attr__( 'Inline widget area before post', 'qalam' ),
		'id' 			=> 'widget-area-before-post',
		'description' 	=> esc_attr__( 'Inline widget area before post content', 'qalam' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'	=> "</aside>",
		'before_title' 	=> '<h3 class="widget-title">',
		'after_title' 	=> '</h3>'
	) );

	register_sidebar( array(
		'name' 			=> esc_attr__( 'Inline widget area after post', 'qalam' ),
		'id' 			=> 'widget-area-after-post',
		'description' 	=> esc_attr__( 'Inline widget area after post content', 'qalam' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'	=> "</aside>",
		'before_title' 	=> '<h3 class="widget-title">',
		'after_title' 	=> '</h3>'
	) );

	register_sidebar( array(
		'name' 			=> esc_attr__( 'Secondary widget area', 'qalam' ),
		'id' 			=> 'secondary-widget-area',
		'description' 	=> esc_attr__( 'Secondary widget area', 'qalam' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' 	=> "</aside>",
		'before_title' 	=> '<h3 class="widget-title">',
		'after_title' 	=> '</h3>',
	) );

	register_sidebar( array(
		'name' 			=> esc_attr__( 'Footer widget area', 'qalam' ),
		'id' 			=> 'footer-widget-area',
		'description' 	=> esc_attr__( 'Footer widget area', 'qalam' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' 	=> "</aside>",
		'before_title' 	=> '<h3 class="widget-title">',
		'after_title' 	=> '</h3>',
	) );
}

add_action( 'widgets_init', 'qalam_widgets_init' );

// Add layout width and additional CSS in head node
function qalam_custom_css() {

	$shop_columns = floatval( get_option( 'woocommerce_catalog_columns', 3 ) );
	?>
	<style id="qalam-frontend-css" type="text/css">
		<?php
		if ( 3 != $shop_columns ) {
			echo '.woocommerce ul.products li.product, .woocommerce-page ul.products li.product { width:' . floatval( 100 / $shop_columns ) . '%; }';
		}
		?>
	</style>
<?php
}
add_action( 'wp_head', 'qalam_custom_css' );


// Add body class to the theme depending upon options and templates
function qalam_body_class( $classes ) {
	global $post;
	if ( ! is_single() && ( 'ac' == get_theme_mod( 'sb_pos', 'ca' ) ) && ! ( is_page_template( 'templates/page-full.php' ) ) ) {
		$classes[] = 'layout-ac';
	}

	$classes[] = 'is-' . get_theme_mod( 'layout', 'stretched' );

	// Sidebar layout classes
	if ( is_single() && 'post' == get_post_type() ) {
		$sng_layout = get_theme_mod( 'sb_pos_sng', 'ca' );
		if ( 'no-sb' != $sng_layout ) {
				if ( ! ( $sng_layout == 'ac' || $sng_layout == 'ca' ) ) {
					$classes[] = 'two-sidebars';
				}
		}
		$classes[] = 'layout-' . esc_attr( $sng_layout );
	}
	else {
		if ( ! ( is_single() || is_page_template( 'templates/page-full.php' ) || is_singular( 'product' ) ) ) {
			$qlm_sb_pos = get_theme_mod( 'sb_pos', 'ca' );
			if ( ! ( $qlm_sb_pos == 'ac' || $qlm_sb_pos == 'ca' || $qlm_sb_pos == 'no-sb' ) ) {
				$classes[] = 'two-sidebars';
			}
			$classes[] = 'layout-' . esc_attr( get_theme_mod( 'sb_pos', 'ca' ) );
		}
	}

	if ( is_archive() && get_theme_mod( 'archive_fw', false ) ) {
		$classes[] = 'archive-full';
	}
	return $classes;
}
add_filter( 'body_class', 'qalam_body_class' );

// Adds itemprop="url" attribute to nav menu links
function qalam_add_attribute( $atts, $item, $args ) {
	if ( get_theme_mod( 'schema', 0 ) ) {
		$atts['itemprop'] = 'url';
	}
	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'qalam_add_attribute', 10, 3 );


// Posts not found message
if ( ! function_exists( 'qalam_no_posts' ) ) :
 	function qalam_no_posts() {
	?>
        <article id="post-0" class="post no-results not-found">
            <h2 class="entry-title"><?php esc_html_e( 'Nothing Found', 'qalam' ); ?></h2>
            <div class="entry-content">
                <p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'qalam' ); ?></p>
                <?php get_search_form(); ?>
            </div><!-- .entry-content -->
        </article><!-- #post-0 -->
	<?php
    }
endif;

// Sidebar B
if ( ! function_exists( 'qalam_sidebar_b' ) ) :
	function qalam_sidebar_b() {
		$flag = false;
		$global_layout = get_theme_mod( 'sb_pos', 'ca' );

		if ( is_single() ) {
			global $post;
			$sng_layout = get_theme_mod( 'sb_pos_sng', 'ca' );

			if ( $sng_layout == 'ac' || $sng_layout == 'ca' || 'no-sb' == $sng_layout ) {
				$flag = false;
			}
			else {
				$flag = 'true';
			}

		} else {
			if ( $global_layout == 'ac' || $global_layout == 'ca' ) {
				$flag = false;
			}
			else {
				$flag = 'true';
			}
		}
		if ( $flag && ( 'no-sb' != $global_layout ) ) {
			if ( is_active_sidebar( 'sidebar-b' ) ) {
				echo '<div id="sidebar-b" class="widget-area">';
				dynamic_sidebar( 'sidebar-b' );
				echo '</div><!-- /#sidebar-b -->';
			}
		}
	}
endif;

// Single post meta
if ( ! function_exists( 'qalam_single_meta' ) ) :
	function qalam_single_meta( $schema = false, $show_avatar = true ) {
		if ( ! get_theme_mod( 'show_meta_single', 1 ) ) {
			return;
		} else {

			global $post;
			$author_id=$post->post_author;

			$protocol = is_ssl() ? 'https' : 'http';
			$out = '';
			$date = get_the_time( get_option( 'date_format' ) );
			$date_format = get_option( 'date_format' );

			if ( ! empty( $date_format ) ) {
				if ( $date_format == 'human' ) {
					$date = sprintf( _x( '%s ago', 'human time difference. E.g. 10 days ago', 'qalam' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) );
				}
				else {
					$date = sprintf( _x( '%s<span class="sep time-sep"></span><span class="publish-time">%s</span>', 'date - separator - time', 'qalam' ),
						get_the_time( esc_html( $date_format ) ),
						get_the_time( get_option( 'time_format' ) )
					);
				}
			}

			if ( $schema ) :
				if ( $show_avatar ) {
					$out .= sprintf( '<div itemscope="" itemtype="https://schema.org/Person" itemprop="author" class="author-avatar-40"><a itemprop="name" href="%s" title="%s"><span itemprop="image">%s<span class="hidden" itemprop="name">%s</span></span></a></div>',
						esc_url( get_author_posts_url( $author_id ) ),
						sprintf( esc_attr__( 'More posts by %s', 'qalam' ), esc_attr( get_the_author_meta( 'display_name', $author_id ) ) ),
						get_avatar( get_the_author_meta( 'user_email', $author_id ), 40 ),
						get_the_author_meta( 'display_name', $author_id )
					);
				}

				$out .= sprintf( '<ul class="entry-meta%s">',
						$show_avatar ? ' avatar-enabled' : ''
					);

				$custom_logo_id = get_theme_mod( 'custom_logo' );
				$logo = wp_get_attachment_image_src( $custom_logo_id , 'full' );
				$out .= sprintf( '<li itemscope="" itemtype="https://schema.org/Person" itemprop="author" class="post-author"><span class="screen-reader-text">%s</span>%s</li>
					<li class="publisher-schema" itemscope="" itemtype="https://schema.org/Organization" itemprop="publisher">
						<meta itemprop="name" content="%s">
						<div itemprop="logo" itemscope="" itemtype="https://schema.org/ImageObject"><img itemprop="url" src="%s" alt="%s"></div>
					</li>
					%s',
					esc_html_x( 'Author', 'Used before post author name.', 'qalam' ),
					sprintf( '<span class="author-label">%s</span><a href="%s"><span itemprop="name">%s</span></a>',
						esc_html__( 'Posted by', 'qalam' ),
						esc_url( get_author_posts_url( $author_id ) ),
						get_the_author_meta( 'display_name', $author_id )
					),
					esc_html( get_bloginfo( 'name' ) ),
					isset( $logo ) && is_array( $logo ) ? esc_url( $logo[0] ) : '',
					esc_html( get_bloginfo( 'name' ) ),
					sprintf( '<li class="post-time%1$s"><span class="published-label">%2$s</span><span class="posted-on"><time%3$s class="entry-date" datetime="%4$s">%5$s</time></span></li>%6$s',
							'',
							_x( 'Published', 'post published date label', 'qalam' ),
							' itemprop="datePublished"',
							esc_attr( get_the_date( 'c' ) ),
							$date,
							is_single() && get_the_date('H:i') != get_the_modified_date('H:i') 	?
								sprintf( '<li class="updated-time"><span class="updated-label"><meta itemprop="dateModified" content="%s">%s</span><span class="updated-on">%s<span class="sep updated-sep"></span><span class="updated-time">%s</span></span></li>',
									get_the_modified_date( DATE_W3C ),
									esc_html__( 'Updated', 'qalam' ),
									get_the_date() != get_the_modified_date() ? '<span class="updated-date">' . get_the_modified_date( get_option('date_format') ) . '</span>' : '',
									get_the_modified_date( get_option('time_format') )
								)
							: ''
					)
				);

			else :

				if ( $show_avatar ) {
					$out .= sprintf( '<div class="author-avatar-40"><a href="%s" title="%s">%s</a></div>',
						esc_url( get_author_posts_url( $author_id ) ),
						sprintf( esc_attr__( 'More posts by %s', 'qalam' ), esc_attr( get_the_author_meta( 'display_name', $author_id ) ) ),
						get_avatar( get_the_author_meta( 'user_email', $author_id ), 40 )
					);
				}

				$out .= sprintf( '<ul class="entry-meta%s">',
						$show_avatar ? ' avatar-enabled' : ''
					);

				$out .= sprintf( '<li class="post-author"><span class="screen-reader-text">%1$s</span>%2$s</li><li class="post-time"><span class="published-label">%3$s</span><span class="posted-on"><time class="entry-date" datetime="%4$s">%5$s</time></span></li>%6$s',
							esc_html_x( 'Author', 'Used before post author name.', 'qalam' ),
							sprintf( '<span class="author-label">%s</span><a href="%s"><span itemprop="name">%s</span></a>',
								esc_html__( 'Posted by', 'qalam' ),
								esc_url( get_author_posts_url( $author_id ) ),
								get_the_author_meta( 'display_name', $author_id )
							),
							_x( 'Published', 'post published date label', 'qalam' ),
							esc_attr( get_the_date( 'c' ) ),
							$date,
							is_single() && get_the_date('H:i') != get_the_modified_date('H:i') 	?
								sprintf( '<li class="updated-time"><span class="updated-label">%s</span><span class="updated-on">%s<span class="sep updated-sep"></span><span class="updated-time">%s</span></span></li>',
									esc_html__( 'Updated', 'qalam' ),
									get_the_date() != get_the_modified_date() ? '<span class="updated-date">' . get_the_modified_date( get_option('date_format') ) . '</span>' : '',
									get_the_modified_date( get_option('time_format') )
								)
							: ''
						);

			endif;

			$out .= '</ul>';

			return $out;
		}
	}

endif;


function qalam_mobile_meta() {
	$author_id = get_the_author_meta( 'ID' );
	printf( '<div class="single-meta entry-meta mobile-only">' );
	printf( esc_attr__( 'Posted by %s on %s. %s', 'qalam' ),
		sprintf( '<a href="%s">%s</a>',
			esc_url( get_author_posts_url( $author_id ) ),
			get_the_author_meta( 'display_name', $author_id )
		),
		'<span class="posted-on">' . get_the_time( get_option( 'date_format' ) ) . '</span>',
		get_the_date('H:i') != get_the_modified_date('H:i') 	?
			sprintf( '%s<span class="updated-on">%s<span class="sep updated-sep"></span><span class="updated-time">%s</span></span>',
				esc_html__( 'Updated: ', 'qalam' ),
				get_the_date() != get_the_modified_date() ? '<span class="updated-date">' . get_the_modified_date( get_option('date_format') ) . '</span>' : '',
				get_the_modified_date( get_option('time_format') )
			)
		: ''
	);
	printf( '</div>' );
}

add_action( 'qlm_entry_sub_title_after', 'qalam_mobile_meta', 11 );

// Add Social buttons for Author profile
function qalam_user_social_links( $profile_fields ) {
	// Add new fields
	$profile_fields['twitter'] = esc_html__( 'X (Twitter) URL', 'qalam' );
	$profile_fields['facebook-f'] = esc_html__( 'Facebook URL', 'qalam' );
	$profile_fields['linkedin-in'] = esc_html__( 'LinkedIn URL', 'qalam' );
	$profile_fields['instagram'] = esc_html__( 'Instagram URL', 'qalam' );
	$profile_fields['youtube'] = esc_html__( 'YouTube URL', 'qalam' );
	$profile_fields['threads'] = esc_html__( 'Threads URL', 'qalam' );

	return $profile_fields;
}
add_filter( 'user_contactmethods', 'qalam_user_social_links' );

// Post meta in archive posts
function qlm_after_grid_content_meta() {
	$social_links = false;
	$social = get_theme_mod( 'archive_social', array( 'twitter', 'facebook-f', 'linkedin-in' ) );
	if ( get_theme_mod( 'show_sharing', true ) && function_exists( 'qalam_social_sharing' ) && ! empty( $social ) ) {
		$social_links = qalam_social_sharing( $social, 'false' );
	}

	if ( get_theme_mod( 'show_meta', true ) ) {
		$meta_format = get_theme_mod( 'archive_meta_format', '' );
		$meta_format = ( '' == $meta_format ) ? '%1$s' : $meta_format;
		printf( '<div class="single-meta archive">%s%s</div>',
				sprintf( '<div class="%s">%s</div>',
							$social_links ? 'col-70' : 'col-100',
							sprintf( $meta_format,
								get_the_date(),
								get_the_author(),
								get_comments_number()
							) ),
				$social_links ? '<div class="w-30">' . $social_links . '</div>' : ''
		);
	}
}

add_action( 'qlm_after_grid_content', 'qlm_after_grid_content_meta' );
add_action( 'qlm_after_list_content', 'qlm_after_grid_content_meta' );
add_action( 'qlm_after_mix_hero_content', 'qlm_after_grid_content_meta' );
add_action( 'qlm_after_mix_list_content', 'qlm_after_grid_content_meta' );

// Post meta for full content style archives
function qlm_after_full_content_title_meta() {
	$social = get_theme_mod( 'archive_social', array( 'twitter', 'facebook-f', 'linkedin-in' ) );
	printf( '<div class="single-meta"><div class="%s">%s</div>%s</div>',
		! empty( $social ) ? 'col-70' : 'col-100',
		qalam_single_meta( 'true', 'true'),
		! empty( $social ) && function_exists( 'qalam_social_sharing' ) ? '<div class="w-30">' . qalam_social_sharing( $social, 'true' ) . '</div>' : ''
	);
}
add_action( 'qlm_after_full_content_title', 'qlm_after_full_content_title_meta', 10 );

// Widget area before post content
function qlm_widget_area_before_post() {
	if ( is_active_sidebar( 'widget-area-before-post' ) ) {
		echo '<div class="widget-area before-post">';
		dynamic_sidebar( 'widget-area-before-post' );
		echo '</div>';
	}
}
add_action( 'qlm_single_content_before', 'qlm_widget_area_before_post' );

// Widget area after post content
function qlm_widget_area_after_post() {
	if ( is_active_sidebar( 'widget-area-after-post' ) ) {
		echo '<div class="widget-area after-post">';
		dynamic_sidebar( 'widget-area-after-post' );
		echo '</div>';
	}
}
add_action( 'qlm_single_content_after', 'qlm_widget_area_after_post', 10 );

function qlm_meta_output() {
	if ( ! get_theme_mod( 'show_meta_single', 1 ) ) {
		return;
	} else {
		$social_links = false;
		$schema =  get_theme_mod( 'schema', 0 );
		$social = get_theme_mod( 'single_social', array( 'twitter', 'facebook-f', 'linkedin-in' ) );
		if ( function_exists( 'qalam_social_sharing' ) && ! empty( $social ) ) {
			$social_links = qalam_social_sharing( $social, 'true' );
		}
		printf( '<div class="single-meta"><div class="meta-grid">%s</div>%s</div>',
			qalam_single_meta( $schema, 1 ),
			$social_links ? '<div class="social-grid">' . $social_links . '</div>' : ''
		);
	}
}
add_action( 'qlm_entry_sub_title_after', 'qlm_meta_output', 10 );

// Tag list after single post content
function qlm_tag_list() {
	the_tags( '<ul class="tag-list"><li>', '</li><li>', '</li></ul>' );
}
add_action( 'qlm_single_content_after', 'qlm_tag_list', 15 );

/**
 * Author Social links
 *
 * Used in qlm_author_box
 */
function qlm_author_social_links( $xclass = '' ) {
		if ( get_the_author_meta('twitter') || get_the_author_meta('facebook-f') || get_the_author_meta('linkedin-in') || get_the_author_meta('google-plus-g') || get_the_author_meta('instagram') || get_the_author_meta('user_email') || get_the_author_meta('youtube-play') ) {
		$out = '<ul class="sa-social author-social' . esc_attr( $xclass ) . '">';
		$links = array();
		$links ['fab fa-x-twitter'] = get_the_author_meta( 'twitter', '' );
		$links ['fab fa-facebook-f'] = get_the_author_meta( 'facebook-f', '' );
		$links ['fab fa-linkedin-in'] = get_the_author_meta( 'linkedin-in', '' );
		$links ['fab fa-instagram'] = get_the_author_meta( 'instagram', '' );
		$links ['fab fa-youtube'] = get_the_author_meta( 'youtube', '' );
		$links ['fab fa-threads'] = get_the_author_meta( 'threads', '' );
		$links ['fa fa-envelope'] = get_the_author_meta( 'user_email', '' );
		$links ['fa fa-link'] = get_the_author_meta( 'user_url', '' );

		foreach( $links as $key => $value ) {
			if ( '' != $value ) {
				$out .= '<li><a class="' . esc_attr( $key ) . '" href="' . esc_url( $value ) . '"></a></li>';
			}
		}
		return $out . '</ul>';
	}
}

// Author box after single post content
function qlm_author_box() {
	if (  get_theme_mod( 'show_author', 1 ) && get_the_author_meta( 'description' ) ) : ?>
		<div class="author-info clearfix">
			<div class="author-avatar">
				<?php echo get_avatar( get_the_author_meta( 'user_email' ), 80 ); ?>
			</div><!-- /.author-avatar -->

			<div class="author-description">
				<?php
				printf( '<h4 class="author-title"><a href="%s" rel="author">%s</a></h4>',
					esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
					get_the_author()
				);
				echo '<p>' . wp_kses_post( get_the_author_meta( 'description' ) ) . '</p>';
				echo qlm_author_social_links();
				?>
			</div><!-- /.author-description -->
		</div><!-- /.author-info -->
	<?php endif;
}
add_action( 'qlm_single_content_after', 'qlm_author_box', 20 );

// Related Posts after single post content
function qlm_related_posts() {
	if ( get_theme_mod( 'show_related', 1 ) ) {
		get_template_part( 'includes/related-posts' );
	}
}
add_action( 'qlm_single_content_after', 'qlm_related_posts', 25 );

// Comments after single post content
function qlm_comments_single() {
		$comments_number = get_comments_number();
		if ( '1' === $comments_number ) {
			$btn_text = esc_html__( 'One Comment', 'qalam' );
		}
		elseif ( '0' === $comments_number ) {
			$btn_text = esc_html__( 'Leave a comment', 'qalam' );
		} else {
			$btn_text = sprintf(
				_nx(
					'%1$s Comment',
					'%1$s Comments',
					$comments_number,
					'number of comments',
					'qalam'
				),
				number_format_i18n( $comments_number )
			);
		}
		comments_template( '', true );

		if ( ( have_comments() || comments_open() ) && ! post_password_required( get_the_id() ) ) {
			printf( '<a href="#" id="comments-trigger" class="comments-trigger">%s</a>', esc_html( $btn_text ) );
		}
}
add_action( 'qlm_single_content_after', 'qlm_comments_single', 30 );
add_action( 'qlm_page_content_after', 'qlm_comments_single', 30 );


// Posts navigation after single post content
function qlm_posts_navigation() {
	if ( get_theme_mod( 'posts_nav', 1 ) ) {
		$next_post = get_next_post();
    	$prev_post = get_previous_post();
    	$prev_img = isset( $prev_post->ID ) ? get_the_post_thumbnail( $prev_post->ID, array( 96, 96, 'bfi_thumb' => get_theme_mod( 'bfi_thumb', 0 ) ) ) : false;
    	$next_img = isset( $next_post->ID ) ? get_the_post_thumbnail( $next_post->ID, array( 96, 96, 'bfi_thumb' => get_theme_mod( 'bfi_thumb', 0 ) ) ) : false;
		the_post_navigation( array(
			'prev_text' => '<span class="screen-reader-text">' . esc_html__( 'Previous Post', 'qalam' ) . '</span><span class="nav-title' . ( $prev_img ? ' has-img' : '' ) . '"><i class="nav-icon fa fa-angle-left"></i>' . $prev_img . '%title</span>',
			'next_text' => '<span class="screen-reader-text">' . esc_html__( 'Next Post', 'qalam' ) . '</span><span class="nav-title' . ( $next_img ? ' has-img' : '' ) . '">%title' . $next_img . '<i class="nav-icon fa fa-angle-right"></i></span>',
		) );
	}
}
add_action( 'qlm_single_content_after', 'qlm_posts_navigation', 35 );

// Widget area before main content
function qlm_widget_area_before_content() {
	if ( get_theme_mod( 'wa_before_content', 0 ) && is_active_sidebar( 'widget-area-before-content' ) ) : ?>
		<div id="widget-area-before-content">
			<div class="container">
			<?php dynamic_sidebar( 'widget-area-before-content' ); ?>
			</div><!--.container -->
		</div><!-- #widget-area-before-content -->
	<?php endif;
}
add_action( 'qalam_after_header', 'qlm_widget_area_before_content', 10 );

// Widget area after main content
function qlm_widget_area_after_content() {
    if ( get_theme_mod( 'wa_after_content', 0 ) && is_active_sidebar( 'widget-area-after-content' ) ) : ?>
        <div id="widget-area-after-content">
            <div class="container">
                <?php dynamic_sidebar( 'widget-area-after-content' ); ?>
            </div><!--.container -->
        </div><!-- #widget-area-after-content -->
    <?php endif;
}
add_action( 'qalam_after_main', 'qlm_widget_area_after_content', 10 );

// Secondary widget area before footer
function qlm_secondary_widget_area() {
	if ( get_theme_mod( 'sec_wa', 0 ) && is_active_sidebar( 'secondary-widget-area' ) ) :
        ?>
        <div id="secondary" class="widget-area columns-<?php echo esc_attr( get_theme_mod( 'sec_cols', 5 ) ); ?>">
            <div class="container clearfix">
                <div class="row">
                <?php
                	dynamic_sidebar( 'secondary-widget-area' );
               	?>
                </div><!-- /.row -->
            </div><!-- #secondary .container -->
        </div><!-- #secondary -->
    <?php endif; // hide secondary
}
add_action( 'qalam_after_main', 'qlm_secondary_widget_area', 15 );

// Footer widget area
function qlm_footer_widget_area() {
	if ( get_theme_mod( 'footer_wa', 1 ) ) :
        ?>
	    <footer id="footer" class="widget-area columns-<?php echo esc_attr( get_theme_mod( 'footer_cols', 1 ) ); ?>">
	        <div class="container clearfix">
	            <?php
	            	if ( is_active_sidebar( 'footer-widget-area' ) ) {
	            		echo '<div class="row">';
	            		dynamic_sidebar( 'footer-widget-area' );
	            		echo '</div><!-- /.row -->';
	            	} else {
	            		printf( esc_html__( '&copy; %s %s. All rights reserved.', 'qalam' ), date( 'Y' ), get_bloginfo( 'name' ) );
	            	}
	            ?>
	        </div><!-- #footer .container -->
	    </footer><!-- #footer -->
    <?php endif;
}
add_action( 'qalam_after_main', 'qlm_footer_widget_area', 20 );

// Fixed widget area left side
function qlm_fixed_widget_area_left() {
	if ( is_active_sidebar( 'fixed-widget-area-left' ) ) :
        ?>
	    <div class="fixed-widget-bar fixed-left">
	        <?php
	            dynamic_sidebar( 'fixed-widget-area-left' );
	        ?>
	    </div><!-- /.fixed-left -->
    <?php endif;
}
//add_action( 'qalam_after_main', 'qlm_fixed_widget_area_left', 25 );

// Fixed widget area right side
function qlm_fixed_widget_area_right() {
	if ( is_active_sidebar( 'fixed-widget-area-right' ) ) :
        ?>
	    <div class="fixed-widget-bar fixed-right">
	        <?php
	            dynamic_sidebar( 'fixed-widget-area-right' );
	        ?>
	    </div><!-- /.fixed-right -->
    <?php endif;
}
//add_action( 'qalam_after_main', 'qlm_fixed_widget_area_right', 30 );

// Scroll to top button in footer
function qlm_footer_elements() {
	?>
	<div class="scroll-to-top"><a href="#" title="<?php esc_html_e( 'Scroll to top', 'qalam' ); ?>"><span class="sr-only"><?php esc_html_e( 'scroll to top', 'qalam' ); ?></span></a></div><!-- .scroll-to-top -->
	<div class="overlay-mask"></div><!-- /.overlay-mask -->
	<?php
}
add_action( 'wp_footer', 'qlm_footer_elements', 25 );

// Breadcrumbs in header
function qlm_show_breadcrumbs() {
	if ( get_theme_mod( 'breadcrumbs_check', true ) && ! ( is_page_template( 'templates/canvas.php' ) ) ) {
		echo '<div class="breadcrumbs-wrap container">';
		qlm_breadcrumbs();
		echo '</div>';
	}
}
add_action( 'qalam_after_header', 'qlm_show_breadcrumbs', 15 );

/**
 * Generate custom search form
 * @param string $form Form HTML.
 * @return string Modified form HTML.
 */
function qlm_frontend_search_form( $form ) {

	$form = '<form role="search" method="get" class="search-form" action="' . home_url( '/' ) . '">
				<label>
					<span class="screen-reader-text">' . esc_html__( 'Search for:', 'qalam' ) . '</span>
					<input type="search" class="search-field" placeholder="' . esc_attr__( 'Search for...', 'qalam' ) . '" value="' . get_search_query() . '" name="s" id="s">
				</label>
				<input type="submit" class="search-submit" value="'. esc_attr__( 'Search', 'qalam' ) .'">
			</form>';
    return $form;
}
add_filter( 'get_search_form', 'qlm_frontend_search_form' );

/**
 * Social links in header
 *
 * Used in qlm_add_social_links()
 */
function qlm_header_social_links( $xclass = '' ) {
	if ( get_theme_mod( 'social_check', 1 ) ) {
		$out = '<ul class="qlm-sharing-inline' . esc_attr( $xclass ) . '">';
		$links = array();
		$links ['x-twitter'] = get_theme_mod( 'twitter', '' );
		$links ['facebook-f'] = get_theme_mod( 'facebook', '' );
		$links ['linkedin-in'] = get_theme_mod( 'linkedin', '' );
		$links ['instagram'] = get_theme_mod( 'instagram', '' );
		$links ['youtube'] = get_theme_mod( 'youtube', '' );
		$links ['threads'] = get_theme_mod( 'threads', '' );

		foreach( $links as $key => $value ) {
			if ( '' != $value ) {
				$out .= '<li class="qlm-' . esc_attr( $key ) . '"><a href="' . esc_url( $value ) . '"><i class="fa-brands fa-' . esc_attr( $key ) . '"></i></a></li>';
			}
		}
		return  $out . '</ul>';
	}
}

// Adds Social links to Utility bar in header
function qlm_add_social_links() {
	if ( '1' == get_theme_mod( 'header_style', '1' ) || '4' == get_theme_mod( 'header_style', '1' ) ) {
		echo qlm_header_social_links();
	}
}
add_action( 'qalam_utility_links', 'qlm_add_social_links', 10 );

// WooCommerce cart to utility links in header
function qlm_woo_cart() {
    if ( class_exists( 'woocommerce' ) && 'main' == get_theme_mod( 'cart_pos', 'none' ) ) {
        printf( '<div class="cart-status"><a class="cart-contents" href="%s" title="%s"><i class="fas fa-shopping-basket"></i>%s</a><div class="cart-submenu">
<div class="widget_shopping_cart_content"></div><!-- /.widget_shopping_cart_content -->
</div><!-- /.cart-submenu --></div>',
            esc_url( wc_get_cart_url() ),
            esc_html__( 'View your shopping cart', 'qalam' ),
             '<sup class="amount">' . WC()->cart->get_cart_contents_count() . '</sup>'
        );
        ?>
    <?php
	}
}
add_action( 'qalam_utility_links', 'qlm_woo_cart', 15 );

// Search panel in header
function qlm_search_panel() {
    if ( get_theme_mod( 'search_panel', true ) ) {
        ?>
        <div class="search-icon">
            <?php
            if ( 'overlay' == get_theme_mod( 'search_type', 'inline') ) {
            ?>
            <a class="search-trigger ovrlay" href="#"><span class="screen-reader-text"><?php esc_html_e( 'Search', 'qalam' ); ?></span></a>
                <div class="search-overlay">
                    <div class="search-inner">
                        <a class="search-close-btn" href="#"></a>
                    <?php
                    /**
					 * Hook: qalam_search_form
					 *
					 * @hooked qlm_add_searchform
					 */
                    do_action( 'qalam_search_form' );
                    ?></div>
                </div>
            <?php
            }
            if ( 'inline' == get_theme_mod( 'search_type', 'inline') ) {
                ?>
                <a class="search-trigger inline" href="#"><span class="screen-reader-text"><?php esc_html_e( 'Search', 'qalam' ); ?></span></a>
                <?php
                echo '<div class="search-drawer">';
                   /**
					 * Hook: qalam_search_form
					 *
					 * @hooked qlm_add_searchform
					 */
                    do_action( 'qalam_search_form' );
                echo '</div><!-- /.search-drawer --> ';
            }
            ?>
        </div><!-- /.search-icon -->
    <?php
   }
}
add_action( 'qalam_utility_links', 'qlm_search_panel', 20 );


// Add search form to custom search panel
function qlm_add_searchform() {
	get_search_form();
}
add_action( 'qalam_search_form', 'qlm_add_searchform' );

/**
 * Allow editing of theme locations via Elementor
 *
 * Usefful if using pro version of Elementor
 */
function qalam_register_elementor_locations( $elementor_theme_manager ) {
	$elementor_theme_manager->register_all_core_location();
}
add_action( 'elementor/theme/register_locations', 'qalam_register_elementor_locations' );

/**
 * Backward compatibility for
 * wp_body_open
 */
if ( ! function_exists( 'wp_body_open' ) ) {
    function wp_body_open() {
        do_action( 'wp_body_open' );
    }
}


/**
 * Remove "Colors" native section from Customizer
 *
 * New section named "Colors" is added with more options
 */
function qalam_remove_customizer_section( $wp_customize ) {
	$wp_customize->remove_section( 'colors' );
}
add_action( 'customize_register', 'qalam_remove_customizer_section' );

// Required and recommended plugins via TGM
function qalam_register_required_plugins() {
	$plugins = array(
		array(
            'name'               => 'WP Post Modules',
            'slug'               => 'wp-post-modules-el',
            'source'             => get_template_directory() . '/plugins/wp-post-modules-el.zip',
            'required'           => true,
            'version'            => '2.7.0',
            'force_activation'   => false,
            'force_deactivation' => false
        ),

		// Kirki
		array(
            'name'      => 'Kirki',
            'slug'      => 'kirki',
            'required'  => true,
        ),

		// Elementor Page Builder
		array(
            'name'      => 'Elementor',
            'slug'      => 'elementor',
            'required'  => true,
        ),

		// Anywhere Elementor
		array(
            'name'      => 'Anywhere Elementor',
            'slug'      => 'anywhere-elementor',
            'required'  => false,
        ),

        // Anywhere Elementor
		array(
            'name'      => 'Customizer Reset',
            'slug'      => 'customizer-reset-by-wpzoom',
            'required'  => false,
        ),

		// WP Review
		array(
            'name'      => 'WP Review',
            'slug'      => 'wp-review',
            'required'  => false,
        ),

		// Post Views Counter
		array(
            'name'      => 'Post Views Counter',
            'slug'      => 'post-views-counter',
            'required'  => false,
        )
    );

	/*
	 * Array of configuration settings. Amend each line as needed.
	 *
	 * TGMPA will start providing localized text strings soon. If you already have translations of our standard
	 * strings available, please help us make TGMPA even better by giving us access to these translations or by
	 * sending in a pull-request with .po file(s) with the translations.
	 *
	 * Only uncomment the strings in the config array if you want to customize the strings.
	 */
	$config = array(
		'id'           => 'qalam',             // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.
	);

    tgmpa( $plugins, $config );
}
add_action( 'tgmpa_register', 'qalam_register_required_plugins' );