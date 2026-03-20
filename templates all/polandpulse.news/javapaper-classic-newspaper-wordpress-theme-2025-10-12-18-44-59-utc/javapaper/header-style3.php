<?php
/*
 * Header Section of Javapaper
 * Displays all of the <head> section and everything up till <div id="main">
 */
?>
<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php // Loads HTML5 JavaScript file to add support for HTML5 elements in older IE versions. ?>
<!--[if lt IE 9]>
<script src="<img src="<?php echo esc_url( get_template_directory_uri() ); ?>">/js/html5.js" type="text/javascript"></script>
<![endif]-->
<?php global $redux_demo; if ( ! function_exists( 'has_site_icon' ) || ! has_site_icon() ) { ?>
<link rel="shortcut icon" href="<?php if( isset($redux_demo['jp_favicon']['url']) ){ ?>
			<?php echo esc_url($redux_demo['jp_favicon']['url']); ?>
			<?php } else { ?>
			<?php } ?>" />
<?php } else { ?>
<?php } ?>
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div class="wrapper-header">
  <header id="masthead" class="site-header" role="banner">
    <div class="header-top <?php if( isset($redux_demo['jp_showhidenav']) ){ ?><?php echo esc_html($redux_demo['jp_showhidenav']); ?><?php } ?>">
      <div class="header-topinside">
        <div class="row align-items-center">
          <div class="col-md-6">
            <!-- open Sidebar1 menu -->
            <a class="btn btn-customized open-menu" href="#" role="button"> </a>
            <div class="switch">
              <input class="switch__input" type="checkbox" id="themeSwitch">
              <label aria-hidden="true" class="switch__label" for="themeSwitch"> </label>
              </input>
              <div aria-hidden="true" class="switch__marker"></div>
            </div>
          </div>
          <div class="col-md-6"> <span>
            <?php if(isset($redux_demo['jp_socmedlink1']) && $redux_demo['jp_socmedlink1']): ?>
            <div class="sosmed"> <a href="<?php  echo esc_url($redux_demo['jp_socmedlink1']); ?>"> <img src="<?php if($redux_demo['jp_socmedimg1']['url']): 
				echo esc_url($redux_demo['jp_socmedimg1']['url']); endif; ?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo esc_attr($redux_demo['jp_socmedalt1']); ?>" class="tip-bottom" alt="<?php esc_attr_e('socmed', 'javapaper' ); ?>"> </a> </div>
            <?php endif; ?>
            <?php if(isset($redux_demo['jp_socmedlink2']) && $redux_demo['jp_socmedlink2']): ?>
            <div class="sosmed"> <a href="<?php  echo esc_url($redux_demo['jp_socmedlink2']); ?>"> <img src="<?php if($redux_demo['jp_socmedimg2']['url']): 
				echo esc_url($redux_demo['jp_socmedimg2']['url']); endif; ?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo esc_attr($redux_demo['jp_socmedalt2']); ?>" class="tip-bottom" alt="<?php esc_attr_e('socmed', 'javapaper' ); ?>"> </a> </div>
            <?php endif; ?>
            <?php if(isset($redux_demo['jp_socmedlink3']) && $redux_demo['jp_socmedlink3']): ?>
            <div class="sosmed"> <a href="<?php  echo esc_url($redux_demo['jp_socmedlink3']); ?>"> <img src="<?php if($redux_demo['jp_socmedimg3']['url']): 
				echo esc_url($redux_demo['jp_socmedimg3']['url']); endif; ?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo esc_attr($redux_demo['jp_socmedalt3']); ?>" class="tip-bottom" alt="<?php esc_attr_e('socmed', 'javapaper' ); ?>"> </a> </div>
            <?php endif; ?>
            <?php if(isset($redux_demo['jp_socmedlink4']) && $redux_demo['jp_socmedlink4']): ?>
            <div class="sosmed"> <a href="<?php  echo esc_url($redux_demo['jp_socmedlink4']); ?>"> <img src="<?php if($redux_demo['jp_socmedimg4']['url']): 
				echo esc_url($redux_demo['jp_socmedimg4']['url']); endif; ?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo esc_attr($redux_demo['jp_socmedalt4']); ?>" class="tip-bottom" alt="<?php esc_attr_e('socmed', 'javapaper' ); ?>"> </a> </div>
            <?php endif; ?>
            <?php if(isset($redux_demo['jp_socmedlink5']) && $redux_demo['jp_socmedlink5']): ?>
            <div class="sosmed"> <a href="<?php  echo esc_url($redux_demo['jp_socmedlink5']); ?>"> <img src="<?php if($redux_demo['jp_socmedimg5']['url']): 
				echo esc_url($redux_demo['jp_socmedimg5']['url']); endif; ?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo esc_attr($redux_demo['jp_socmedalt5']); ?>" class="tip-bottom" alt="<?php esc_attr_e('socmed', 'javapaper' ); ?>"> </a> </div>
            <?php endif; ?>
            <?php if(isset($redux_demo['jp_socmedlink6']) && $redux_demo['jp_socmedlink6']): ?>
            <div class="sosmed"> <a href="<?php  echo esc_url($redux_demo['jp_socmedlink6']); ?>"> <img src="<?php if($redux_demo['jp_socmedimg6']['url']): 
				echo esc_url($redux_demo['jp_socmedimg6']['url']); endif; ?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo esc_attr($redux_demo['jp_socmedalt6']); ?>" class="tip-bottom" alt="<?php esc_attr_e('socmed', 'javapaper' ); ?>"> </a> </div>
            <?php endif; ?>
            </span> </div>
        </div>
      </div>
    </div>
    <div class="header-middle">
      <div class="header-middleinside">
        <div class="header-style3">
          <div class="col-md-4 first">
		  <?php if (is_active_sidebar('javapaper-header1')) :?>
		  <?php dynamic_sidebar( 'javapaper-header1' ); ?>
		  <?php endif; ?>  
          </div>
          <div class="col-md-4 mainheader">
            <div class="javapaperlogo">
              <?php global $redux_demo; if ( isset($redux_demo['opt_header_logo']['url']) ){ ?>
              <a href="<?php echo esc_url( home_url( '/' ) ); ?>"> <img alt="<?php echo get_bloginfo('name'); ?>" src="
<?php if( isset($redux_demo['opt_header_logo']) ){ ?>
<?php echo esc_url($redux_demo['opt_header_logo']['url']); ?>
<?php } ?>"> </a>
              <?php } 
else if ( isset($redux_demo['opt_header_logo']) ){ ?>
              <h1> <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                <?php if( isset($redux_demo['opt_header_text']) ){ ?>
                <?php echo esc_html($redux_demo['opt_header_text']); ?>
                <?php } ?>
                </a> </h1>
              <?php }
else { ?>
              <img src="<?php echo esc_url( get_template_directory_uri() . '/images/logo.png' ); ?>">
              <?php } 
			  ?>
            </div>
            <div class="blogdescription"> <?php echo get_option('blogdescription');?> </div>
          </div>
          <div class="col-md-4 last">
		  <?php if (is_active_sidebar('javapaper-header2')) :?>
		  <?php dynamic_sidebar( 'javapaper-header2' ); ?>
		  <?php endif; ?>  
          </div>
        </div>
      </div>
    </div>
    <div class="header-bottom">
      <div class="mainnav-navwrapperboxed">
        <div class="nav-mainwrapper">
          <div class="mainnav-inside">
            <div class="nav-main">
              <nav id="site-navigation" class="javapaper-nav" role="navigation"> <a class="assistive-text" href="#main" title="<?php esc_attr_e( 'Skip to content', 'javapaper' ); ?>">
                <?php _e( 'Skip to content', 'javapaper' ); ?>
                </a>
                <?php wp_nav_menu( array( 'theme_location' => 'primary_menu', 'menu_id' => 'menu-top', 'menu_class' => 'nav-menu', 'container' => 'ul' ) ); ?>
              </nav>
              <!-- #site-navigation -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>
  <!-- Sidebar1 -->
  <div class="Sidebar1">
    <!-- close Sidebar1 menu -->
    <div class="dismiss"> </div>
    <div class="logo">
      <div class="javapaperlogo">
        <?php global $redux_demo; if ( isset($redux_demo['opt_header_logo']['url']) ){ ?>
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>"> <img alt="<?php echo get_bloginfo('name'); ?>" src="
<?php if( isset($redux_demo['opt_header_logo']) ){ ?>
<?php echo esc_url($redux_demo['opt_header_logo']['url']); ?>
<?php } ?>"> </a>
        <?php } 
else if ( isset($redux_demo['opt_header_logo']) ){ ?>
        <h1> <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
          <?php if( isset($redux_demo['opt_header_text']) ){ ?>
          <?php echo esc_html($redux_demo['opt_header_text']); ?>
          <?php } ?>
          </a> </h1>
        <?php } 
			  
else { ?>
        <img src="<?php echo esc_url( get_template_directory_uri() . '/images/logo.png' ); ?>">
        <?php } 
			  ?>
      </div>
    </div>
    <div class="sidebar1-insidewrapper">
      <?php if ( is_active_sidebar( 'javapaper-slidemenu' ) ) : ?>
      <div class="widget-area" role="complementary">
        <?php dynamic_sidebar( 'javapaper-slidemenu' ); ?>
      </div>
      <?php endif; ?>
    </div>
  </div>
  <!-- End Sidebar1 -->
  <div class="overlay"></div>
  <!-- Dark overlay -->
</div>
<div class="wrapper-body <?php if( isset($redux_demo['jp_wideheader']) ){ ?><?php echo esc_html($redux_demo['jp_wideheader']); ?><?php } ?>">
<div id="main" class="wrapper"> </div>
