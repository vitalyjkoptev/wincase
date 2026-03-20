<?php
/****************************************************
/* ONE CLICK DEMO IMPORT 
*****************************************************/
function ocdi_import_files() {
    return array(
        array(
            'import_file_name'             => 'Home',
            'categories'                   => array( 'Classic','Modern','Simple'  ),
            'local_import_file'            => trailingslashit( get_template_directory() ) . '/inc/installation/javapaper.xml',
            'local_import_widget_file' => trailingslashit( get_template_directory() ) . '/inc/installation/javapaper-widget.wie',
            'local_import_customizer_file' => trailingslashit( get_template_directory() ) . '/inc/installation/javapaper.dat',			
			'local_import_megamenu_file'   => trailingslashit( get_template_directory() ) . '/inc/installation/mega-main-menu.txt',  			
            'local_import_redux'           => array(
                array(
                    'file_path'   => trailingslashit( get_template_directory() ) . '/inc/installation/redux.json',
                    'option_name' => 'redux_demo',
                ),
            ),
            'import_preview_image_url'     => get_stylesheet_directory_uri() . '/inc/installation/oc1.jpg',			
            'import_notice'                => __( 'After import demo data, you will need to:<br>1. Import Menu Navigation setup separately.<br>2. Import Mega Menu setting.', 'javapaper' ),
            'preview_url'                  => 'https://javapaper.wpbromo.com/',
        ),	
        array(
            'import_file_name'             => 'Home 2',
            'categories'                   => array( 'Modern', 'Blog' ),
            'local_import_file'            => trailingslashit( get_template_directory() ) . '/inc/installation/post-javapaper.xml',
            'local_import_widget_file' => trailingslashit( get_template_directory() ) . '/inc/installation/javapaper-widget.wie',
            'local_import_customizer_file' => trailingslashit( get_template_directory() ) . '/inc/installation/javapaper.dat',			
			'local_import_megamenu_file'   => trailingslashit( get_template_directory() ) . '/inc/installation/mega-main-menu.txt',  			
            'local_import_redux'           => array(
                array(
                    'file_path'   => trailingslashit( get_template_directory() ) . '/inc/installation/redux.json',
                    'option_name' => 'redux_demo',
                ),
            ),			
            'import_preview_image_url'     => get_stylesheet_directory_uri() . '/inc/installation/oc1.jpg',		
			'import_notice'                => __( 'A special note for this import.', 'javapaper' ),
            'preview_url'                  => 'https://javapaper.wpbromo.com//home2/',
        ),
        array(
            'import_file_name'             => 'Home 3',
            'categories'                   => array( 'Hybrid' ),
            'local_import_file'            => trailingslashit( get_template_directory() ) . '/inc/installation/javapaper.xml',
            'local_import_widget_file' => trailingslashit( get_template_directory() ) . '/inc/installation/javapaper-widget.wie',
            'local_import_customizer_file' => trailingslashit( get_template_directory() ) . '/inc/installation/javapaper.dat',			
			'local_import_megamenu_file'   => trailingslashit( get_template_directory() ) . '/inc/installation/mega-main-menu.txt',  			
            'local_import_redux'           => array(
                array(
                    'file_path'   => trailingslashit( get_template_directory() ) . '/inc/installation/redux.json',
                    'option_name' => 'redux_demo',
                ),
            ),
            'import_preview_image_url'     => get_stylesheet_directory_uri() . '/inc/installation/oc1.jpg',		
            'import_notice'                => __( 'A special note for this import.', 'javapaper' ),
            'preview_url'                  => 'https://javapaper.wpbromo.com//home-3/',
        ),	
        array(
            'import_file_name'             => 'Home 4',
            'categories'                   => array( 'One Page','Simple' ),
            'local_import_file'            => trailingslashit( get_template_directory() ) . '/inc/installation/javapaper.xml',
            'local_import_widget_file' => trailingslashit( get_template_directory() ) . '/inc/installation/javapaper-widget.wie',
            'local_import_customizer_file' => trailingslashit( get_template_directory() ) . '/inc/installation/javapaper.dat',			
			'local_import_megamenu_file'   => trailingslashit( get_template_directory() ) . '/inc/installation/mega-main-menu.txt',  			
            'local_import_redux'           => array(
                array(
                    'file_path'   => trailingslashit( get_template_directory() ) . '/inc/installation/redux.json',
                    'option_name' => 'redux_demo',
                ),
            ),
            'import_preview_image_url'     => get_stylesheet_directory_uri() . '/inc/installation/oc1.jpg',		
            'import_notice'                => __( 'A special note for this import.', 'javapaper' ),
            'preview_url'                  => 'https://javapaper.wpbromo.com//home-4/',
        ),	
        array(
            'import_file_name'             => 'Home 5',
            'categories'                   => array( 'One Page','Simple' ),
            'local_import_file'            => trailingslashit( get_template_directory() ) . '/inc/installation/javapaper.xml',
            'local_import_widget_file' => trailingslashit( get_template_directory() ) . '/inc/installation/javapaper-widget.wie',
            'local_import_customizer_file' => trailingslashit( get_template_directory() ) . '/inc/installation/javapaper.dat',			
			'local_import_megamenu_file'   => trailingslashit( get_template_directory() ) . '/inc/installation/mega-main-menu.txt',  			
            'local_import_redux'           => array(
                array(
                    'file_path'   => trailingslashit( get_template_directory() ) . '/inc/installation/redux.json',
                    'option_name' => 'redux_demo',
                ),
            ),
            'import_preview_image_url'     => get_stylesheet_directory_uri() . '/inc/installation/oc1.jpg',		
            'import_notice'                => __( 'A special note for this import.', 'javapaper' ),
            'preview_url'                  => 'https://javapaper.wpbromo.com//home-5/',
        ),
        array(
            'import_file_name'             => 'Home 6',
            'categories'                   => array( 'One Page','Simple' ),
            'local_import_file'            => trailingslashit( get_template_directory() ) . '/inc/installation/javapaper.xml',
            'local_import_widget_file' => trailingslashit( get_template_directory() ) . '/inc/installation/javapaper-widget.wie',
            'local_import_customizer_file' => trailingslashit( get_template_directory() ) . '/inc/installation/javapaper.dat',			
			'local_import_megamenu_file'   => trailingslashit( get_template_directory() ) . '/inc/installation/mega-main-menu.txt',  			
            'local_import_redux'           => array(
                array(
                    'file_path'   => trailingslashit( get_template_directory() ) . '/inc/installation/redux.json',
                    'option_name' => 'redux_demo',
                ),
            ),
            'import_preview_image_url'     => get_stylesheet_directory_uri() . '/inc/installation/oc1.jpg',		
            'import_notice'                => __( 'A special note for this import.', 'javapaper' ),
            'preview_url'                  => 'https://javapaper.wpbromo.com//home-6/',
        ),
        array(
            'import_file_name'             => 'Home 7',
            'categories'                   => array( 'One Page','Simple' ),
            'local_import_file'            => trailingslashit( get_template_directory() ) . '/inc/installation/javapaper.xml',
            'local_import_widget_file' => trailingslashit( get_template_directory() ) . '/inc/installation/javapaper-widget.wie',
            'local_import_customizer_file' => trailingslashit( get_template_directory() ) . '/inc/installation/javapaper.dat',			
			'local_import_megamenu_file'   => trailingslashit( get_template_directory() ) . '/inc/installation/mega-main-menu.txt',  			
            'local_import_redux'           => array(
                array(
                    'file_path'   => trailingslashit( get_template_directory() ) . '/inc/installation/redux.json',
                    'option_name' => 'redux_demo',
                ),
            ),
            'import_preview_image_url'     => get_stylesheet_directory_uri() . '/inc/installation/oc1.jpg',		
            'import_notice'                => __( 'A special note for this import.', 'javapaper' ),
            'preview_url'                  => 'https://javapaper.wpbromo.com//home-7/',
        ),
        array(
            'import_file_name'             => 'Home 8',
            'categories'                   => array( 'One Page','Simple' ),
            'local_import_file'            => trailingslashit( get_template_directory() ) . '/inc/installation/javapaper.xml',
            'local_import_widget_file' => trailingslashit( get_template_directory() ) . '/inc/installation/javapaper-widget.wie',
            'local_import_customizer_file' => trailingslashit( get_template_directory() ) . '/inc/installation/javapaper.dat',			
			'local_import_megamenu_file'   => trailingslashit( get_template_directory() ) . '/inc/installation/mega-main-menu.txt',  			
            'local_import_redux'           => array(
                array(
                    'file_path'   => trailingslashit( get_template_directory() ) . '/inc/installation/redux.json',
                    'option_name' => 'redux_demo',
                ),
            ),
            'import_preview_image_url'     => get_stylesheet_directory_uri() . '/inc/installation/oc1.jpg',		
            'import_notice'                => __( 'A special note for this import.', 'javapaper' ),
            'preview_url'                  => 'https://javapaper.wpbromo.com//home-8/',
        ),
        array(
            'import_file_name'             => 'Home 9',
            'categories'                   => array( 'One Page','Simple' ),
            'local_import_file'            => trailingslashit( get_template_directory() ) . '/inc/installation/javapaper.xml',
            'local_import_widget_file' => trailingslashit( get_template_directory() ) . '/inc/installation/javapaper-widget.wie',
            'local_import_customizer_file' => trailingslashit( get_template_directory() ) . '/inc/installation/javapaper.dat',			
			'local_import_megamenu_file'   => trailingslashit( get_template_directory() ) . '/inc/installation/mega-main-menu.txt',  			
            'local_import_redux'           => array(
                array(
                    'file_path'   => trailingslashit( get_template_directory() ) . '/inc/installation/redux.json',
                    'option_name' => 'redux_demo',
                ),
            ),
            'import_preview_image_url'     => get_stylesheet_directory_uri() . '/inc/installation/oc1.jpg',		
            'import_notice'                => __( 'A special note for this import.', 'javapaper' ),
            'preview_url'                  => 'https://javapaper.wpbromo.com//home-9/',
        ),	
        array(
            'import_file_name'             => 'Home 10',
            'categories'                   => array( 'One Page','Simple' ),
            'local_import_file'            => trailingslashit( get_template_directory() ) . '/inc/installation/javapaper.xml',
            'local_import_widget_file' => trailingslashit( get_template_directory() ) . '/inc/installation/javapaper-widget.wie',
            'local_import_customizer_file' => trailingslashit( get_template_directory() ) . '/inc/installation/javapaper.dat',			
			'local_import_megamenu_file'   => trailingslashit( get_template_directory() ) . '/inc/installation/mega-main-menu.txt',  			
            'local_import_redux'           => array(
                array(
                    'file_path'   => trailingslashit( get_template_directory() ) . '/inc/installation/redux.json',
                    'option_name' => 'redux_demo',
                ),
            ),
            'import_preview_image_url'     => get_stylesheet_directory_uri() . '/inc/installation/oc1.jpg',		
            'import_notice'                => __( 'A special note for this import.', 'javapaper' ),
            'preview_url'                  => 'https://javapaper.wpbromo.com//home-10/',
        ),	
        array(
            'import_file_name'             => 'Home 11',
            'categories'                   => array( 'One Page','Simple' ),
            'local_import_file'            => trailingslashit( get_template_directory() ) . '/inc/installation/javapaper.xml',
            'local_import_widget_file' => trailingslashit( get_template_directory() ) . '/inc/installation/javapaper-widget.wie',
            'local_import_customizer_file' => trailingslashit( get_template_directory() ) . '/inc/installation/javapaper.dat',			
			'local_import_megamenu_file'   => trailingslashit( get_template_directory() ) . '/inc/installation/mega-main-menu.txt',  			
            'local_import_redux'           => array(
                array(
                    'file_path'   => trailingslashit( get_template_directory() ) . '/inc/installation/redux.json',
                    'option_name' => 'redux_demo',
                ),
            ),
            'import_preview_image_url'     => get_stylesheet_directory_uri() . '/inc/installation/oc1.jpg',		
            'import_notice'                => __( 'A special note for this import.', 'javapaper' ),
            'preview_url'                  => 'https://javapaper.wpbromo.com//home-11/',
        ),	
        array(
            'import_file_name'             => 'Home 12',
            'categories'                   => array( 'One Page','Simple' ),
            'local_import_file'            => trailingslashit( get_template_directory() ) . '/inc/installation/javapaper.xml',
            'local_import_widget_file' => trailingslashit( get_template_directory() ) . '/inc/installation/javapaper-widget.wie',
            'local_import_customizer_file' => trailingslashit( get_template_directory() ) . '/inc/installation/javapaper.dat',			
			'local_import_megamenu_file'   => trailingslashit( get_template_directory() ) . '/inc/installation/mega-main-menu.txt',  			
            'local_import_redux'           => array(
                array(
                    'file_path'   => trailingslashit( get_template_directory() ) . '/inc/installation/redux.json',
                    'option_name' => 'redux_demo',
                ),
            ),
            'import_preview_image_url'     => get_stylesheet_directory_uri() . '/inc/installation/oc1.jpg',		
            'import_notice'                => __( 'A special note for this import.', 'javapaper' ),
            'preview_url'                  => 'https://javapaper.wpbromo.com//home-12/',
        ),	
        array(
            'import_file_name'             => 'Home 13',
            'categories'                   => array( 'One Page','Simple' ),
            'local_import_file'            => trailingslashit( get_template_directory() ) . '/inc/installation/javapaper.xml',
            'local_import_widget_file' => trailingslashit( get_template_directory() ) . '/inc/installation/javapaper-widget.wie',
            'local_import_customizer_file' => trailingslashit( get_template_directory() ) . '/inc/installation/javapaper.dat',			
			'local_import_megamenu_file'   => trailingslashit( get_template_directory() ) . '/inc/installation/mega-main-menu.txt',  			
            'local_import_redux'           => array(
                array(
                    'file_path'   => trailingslashit( get_template_directory() ) . '/inc/installation/redux.json',
                    'option_name' => 'redux_demo',
                ),
            ),
            'import_preview_image_url'     => get_stylesheet_directory_uri() . '/inc/installation/oc1.jpg',		
            'import_notice'                => __( 'A special note for this import.', 'javapaper' ),
            'preview_url'                  => 'https://javapaper.wpbromo.com//home-13/',
        ),	
        array(
            'import_file_name'             => 'Home 14',
            'categories'                   => array( 'One Page','Simple' ),
            'local_import_file'            => trailingslashit( get_template_directory() ) . '/inc/installation/javapaper.xml',
            'local_import_widget_file' => trailingslashit( get_template_directory() ) . '/inc/installation/javapaper-widget.wie',
            'local_import_customizer_file' => trailingslashit( get_template_directory() ) . '/inc/installation/javapaper.dat',			
			'local_import_megamenu_file'   => trailingslashit( get_template_directory() ) . '/inc/installation/mega-main-menu.txt',  			
            'local_import_redux'           => array(
                array(
                    'file_path'   => trailingslashit( get_template_directory() ) . '/inc/installation/redux.json',
                    'option_name' => 'redux_demo',
                ),
            ),
            'import_preview_image_url'     => get_stylesheet_directory_uri() . '/inc/installation/oc1.jpg',		
            'import_notice'                => __( 'A special note for this import.', 'javapaper' ),
            'preview_url'                  => 'https://javapaper.wpbromo.com//home-14/',
        ),		
        array(
            'import_file_name'             => 'Home 15',
            'categories'                   => array( 'One Page','Simple' ),
            'local_import_file'            => trailingslashit( get_template_directory() ) . '/inc/installation/javapaper.xml',
            'local_import_widget_file' => trailingslashit( get_template_directory() ) . '/inc/installation/javapaper-widget.wie',
            'local_import_customizer_file' => trailingslashit( get_template_directory() ) . '/inc/installation/javapaper.dat',			
			'local_import_megamenu_file'   => trailingslashit( get_template_directory() ) . '/inc/installation/mega-main-menu.txt',  			
            'local_import_redux'           => array(
                array(
                    'file_path'   => trailingslashit( get_template_directory() ) . '/inc/installation/redux.json',
                    'option_name' => 'redux_demo',
                ),
            ),
            'import_preview_image_url'     => get_stylesheet_directory_uri() . '/inc/installation/oc1.jpg',		
            'import_notice'                => __( 'A special note for this import.', 'javapaper' ),
            'preview_url'                  => 'https://javapaper.wpbromo.com//home-15/',
        ),			
    );
}
add_filter( 'pt-ocdi/import_files', 'ocdi_import_files' );
//SET FRONTPAGE TEMPLATE BY DEMO NAME
function ocdi_after_import_setup( $selected_import ) {
    // Assign front page based on demo
    $front_pages = array(
        'Home'   => 'Home',	
        'Home 2'   => 'Home 2',
        'Home 3'   => 'Home 3',
        'Home 4'   => 'Home 4',	
        'Home 5'   => 'Home 5',
        'Home 6'   => 'Home 6',
        'Home 7'   => 'Home 7',
        'Home 8'   => 'Home 8',
        'Home 9'   => 'Home 9',
        'Home 10'   => 'Home 10',
        'Home 11'   => 'Home 11',	
        'Home 12'   => 'Home 12',
        'Home 13'   => 'Home 13',
        'Home 14'   => 'Home 14',
        'Home 15'   => 'Home 15',		
    );
    
    if ( array_key_exists( $selected_import['import_file_name'], $front_pages ) ) {
        $page = get_page_by_title( $front_pages[$selected_import['import_file_name']] );
        if ( isset( $page->ID ) ) {
            update_option( 'page_on_front', $page->ID );
            update_option( 'show_on_front', 'page' );
        }
    }
    
 // Assign menus based on demo
    $menu_locations = array();    
    // Check if this is any of the supported demos
	if (strpos($selected_import['import_file_name'], 'Home') === 0) {
		$main_menu = get_term_by('name', 'Main Menu', 'nav_menu');
		$footer_menu = get_term_by('name', 'Footer menu', 'nav_menu');
		
		if ($main_menu) {
			$menu_locations['primary_menu'] = $main_menu->term_id;
		}
		if ($footer_menu) {
			$menu_locations['footer_menu'] = $footer_menu->term_id;
		}
	}	
    // Set the menus if we found them
    if ( ! empty( $menu_locations ) ) {
        set_theme_mod( 'nav_menu_locations', $menu_locations );
    }    
    // Additional setup can go here
    if ( isset( $selected_import['local_import_megamenu_file'] ) ) {
        $megamenu_file = $selected_import['local_import_megamenu_file'];
        
        if ( file_exists( $megamenu_file ) ) {
            // Read the .txt file (could be JSON, serialized PHP, or raw settings)
            $settings = file_get_contents( $megamenu_file );
            
            // Case 1: If the .txt contains JSON data
            $json_settings = json_decode( $settings, true );
            if ( json_last_error() === JSON_ERROR_NONE ) {
                update_option( 'mega_main_menu_options', $json_settings );
            }
            // Case 2: If the .txt contains serialized PHP data
            elseif ( maybe_unserialize( $settings ) !== false ) {
                $unserialized = maybe_unserialize( $settings );
                if ( $unserialized ) {
                    update_option( 'mega_main_menu_options', $unserialized );
                }
            }
            // Case 3: Raw settings (directly update the option)
            else {
                update_option( 'mega_main_menu_options', $settings );
            }
        }
    }	
}
add_action( 'pt-ocdi/after_import', 'ocdi_after_import_setup' );

// handle POP UP BOX
function my_theme_ocdi_confirmation_dialog_options ( $options ) {
	return array_merge( $options, array(
		'width'       => 450,
		'dialogClass' => 'wp-dialog',
		'resizable'   => true,
		'height'      => 'auto',
		'modal'       => true,
	) );
}
add_filter( 'pt-ocdi/confirmation_dialog_options', 'my_theme_ocdi_confirmation_dialog_options', 10, 1 );





	
// handle div by CSS on admin dashboard
add_action('admin_head', 'hide_theme_options');
function hide_theme_options() {
  echo '<style>
.ocdi__theme-about {
    border:1px solid#ddd;
	background:#fff;
	padding:25px;
}  
.ocdi__gl-item {
    border:1px solid#ccc;
	padding:10px;
	box-shadow: 0 1px 1px rgb(0 0 0 / 0%);	
}
.redux-group-tab h2{
	font-size:26px; 
	font-weight:bold;	
	line-height:1.5em;
	border-bottom:1px solid#ddd;
	padding-bottom:15px;
}
.redux-sidebar .redux-group-menu li a {
    padding: 16px 4px 15px 14px;
} 
.notice-error, div.error,.rAds {
	display:none!important;
}

@media (min-width: 1120px){

.redux-container {
	background:#000;
    border: 1px solid transparent!important;
    -webkit-box-shadow: 0 0 0 transparent!important;
    box-shadow: 0 0 0 transparent!important;
    -moz-box-shadow: 0 0 0 transparent!important;
}		
.redux-container #redux-header .display_header h2 {
    font-size: 36px;
	text-transform:uppercase;
	margin:2px;
}		
.redux-container #redux-header .display_header span.redux-dev-mode-notice,.redux-container #redux-intro-text {
	display: none !important;
	}	
.redux-main #redux-sticky {
    margin-left: -21px;
    margin-top: -10px;	
}	
.redux-main #redux-footer-sticky {
    margin-left: -21px;
}
.admin-color-fresh .redux-field-container .ui-buttonset .ui-state-active,
.redux-container #redux-footer,	
.redux-container #info_bar,.redux-main #redux-sticky {
    background: #000!important;	
	border-color: #000!important;
	color:#fff!important;
	border-bottom: 3px solid #000;
	box-shadow: inset 0 1px 0 #000!important;
	}
.admin-color-fresh #redux-header, .wp-customizer #redux-header {
	border-color: #32373C;
	background:#32373C;	
	}
.admin-color-fresh #redux-footer #redux-share a, .wp-customizer #redux-footer #redux-share a {
    color: #fff;
	}
.redux-sidebar {
    background: #000!important;	
}	
.redux-sidebar .redux-group-tab-link-a i {
    font-size: 0.9em;
	bottom:13px;
}
.admin-color-fresh .redux-sidebar .redux-group-menu li.activeChild.hasSubSections a, .wp-customizer .redux-sidebar .redux-group-menu li.activeChild.hasSubSections a {
    text-shadow: 0px 0px #54595d;
}
.redux-sidebar .redux-group-menu li a{
	color:#fff;
    opacity: 0.8;	
    border-bottom-color: #444;	
}
.admin-color-fresh .redux-sidebar .redux-group-menu li.activeChild.hasSubSections ul.subsection li.active a {
    background: #444!important;	
}
.admin-color-fresh .redux-sidebar .redux-group-menu li.activeChild.hasSubSections ul.subsection li a {
    background: #333!important;	
	padding-left: 35px;		
}
.redux-sidebar .redux-group-menu li a:hover {
    background: #444;
    color: #fff;
}
.admin-color-fresh .redux-sidebar .redux-group-menu li.activeChild.hasSubSections ul.subsection li a:before {
    content: "";
    height: 1px;
    width: 5px;
    left: 23px;
    bottom: 14px;
    position: absolute;
	background-color:#aaa;
}
.redux-sidebar .redux-group-tab-link-a span.group_title {
    padding-left: 25px;
}
.redux-container .redux-main {
    background: #fff;
	border-left: 1px solid #fff;}
.redux-container .ui-button,	
#footer_text-textarea, .select2-container .select2-choice,.redux-main .button, .redux-container .redux-main input {
    border-radius:0px;
	color:#000!important;
    border-color: #000!important;
	background: #fff;
	margin-right:2px!important;
}
.button.redux-background-upload,
.button.removeCSS.redux-remove-background,
.button.media_upload_button,
.button.remove-image
{
	background: #000;
	color:#fff!important;
}
.redux-main .button.remove-image:hover, .redux-main .removeCSS:hover, .button.media_upload_button:hover {
    color: #000!important;
}
#redux_save,
#redux-defaults,
#redux-defaults-section,
#redux-defaults-sticky,
#redux-defaults-section-sticky,
#redux_save_sticky {
    border-radius:0px;
    border-color: #777;
	background:#444;
	color:#fff;
}
#redux-defaults:hover,
#redux-defaults-section:hover,
#redux_save:hover,
#redux-defaults-sticky:hover,
#redux-defaults-section-sticky:hover,
#redux_save_sticky:hover {
	background:#777;
}
.redux-container .redux-main .input-append .add-on, .redux-container .redux-main .input-prepend .add-on {
    line-height: 22px;
    text-shadow: 0 1px 0 transparent;
    background-color: #000;
	color:#fff;
    border: 1px solid #000;
	border-radius:0;
}
.wp-picker-container .wp-color-result.button {
    padding: 0 5px 0 30px;
}
.redux-container-image_select .redux-image-select img, .redux-container-image_select .redux-image-select .tiles {
    border-color: transparent;
	opacity:0.3;
    border-width: 1px;	
	margin-right:5px;
}
.redux-container-image_select .redux-image-select img:hover {
    border-color: #000;
	opacity:0.5;	
}
.admin-color-fresh .redux-container-image_select .redux-image-select-selected img, .wp-customizer .redux-container-image_select .redux-image-select-selected img {
    border-color: #000;
	opacity:1;	
}

.redux-main .redux-typography-container .input_wrapper input.mini{
    border-radius:0px;
}
.redux-group-tab > h2 {
	text-transform:uppercase;
}
.redux-container .expand_options {
    border: 1px solid #fff;
    -webkit-border-radius: 0;
    -moz-border-radius: 0;
    border-radius: 0;
	color:#000;
}
.redux-container .notice-yellow {
    border-bottom: 1px solid #32373C;
    background-color: #32373C;
    text-shadow: 0 1px 0 rgba(255,255,255,0);
	color:#ff0;
}
  </style>';
}

?>