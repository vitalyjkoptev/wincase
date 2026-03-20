<?php
    /**
     * ReduxFramework Sample Config File
     * For full documentation, please visit: http://docs.reduxframework.com/
     */

    if ( ! class_exists( 'Redux' ) ) {
        return;
    }
    // This is your option name where all the Redux data is stored.
    $opt_name = "redux_demo";
    // This line is only for altering the demo. Can be easily removed.
    $opt_name = apply_filters( 'redux_demo/opt_name', $opt_name );

    /*
     *
     * --> Used within different fields. Simply examples. Search for ACTUAL DECLARATION for field examples
     *
     */

    $sampleHTML = '';
    if ( file_exists( dirname( __FILE__ ) . '/info-html.html' ) ) {
        Redux_Functions::initWpFilesystem();

        global $wp_filesystem;

        $sampleHTML = $wp_filesystem->get_contents( dirname( __FILE__ ) . '/info-html.html' );
    }

    // Background Patterns Reader
    $sample_patterns_path = ReduxFramework::$_dir . '../sample/patterns/';
    $sample_patterns_url  = ReduxFramework::$_url . '../sample/patterns/';
    $sample_patterns      = array();
    
    if ( is_dir( $sample_patterns_path ) ) {

        if ( $sample_patterns_dir = opendir( $sample_patterns_path ) ) {
            $sample_patterns = array();

            while ( ( $sample_patterns_file = readdir( $sample_patterns_dir ) ) !== false ) {

                if ( stristr( $sample_patterns_file, '.png' ) !== false || stristr( $sample_patterns_file, '.jpg' ) !== false ) {
                    $name              = explode( '.', $sample_patterns_file );
                    $name              = str_replace( '.' . end( $name ), '', $sample_patterns_file );
                    $sample_patterns[] = array(
                        'alt' => $name,
                        'img' => $sample_patterns_url . $sample_patterns_file
                    );
                }
            }
        }
    }

    /**
     * ---> SET ARGUMENTS
     * All the possible arguments for Redux.
     * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
     * */

    $theme = wp_get_theme(); // For use with some settings. Not necessary.

    $args = array(
        // TYPICAL -> Change these values as you need/desire
        'opt_name'             => $opt_name,
        // This is where your data is stored in the database and also becomes your global variable name.
        'display_name'         => $theme->get( 'Name' ),
        // Name that appears at the top of your panel
        'display_version'      => 'THEME OPTION',
        // Version that appears at the top of your panel
        'menu_type'            => 'menu',
        //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
        'allow_sub_menu'       => true,
        // Show the sections below the admin menu item or not
        'menu_title'           => __( 'JavaPaper Options', 'javapaper' ),
        'page_title'           => __( 'JAVAPAPER OPTIONS', 'javapaper' ),
        // You will need to generate a Google API key to use this feature.
        // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
        'google_api_key'       => '',
        // Set it you want google fonts to update weekly. A google_api_key value is required.
        'google_update_weekly' => false,
        // Must be defined to add google fonts to the typography module
        'async_typography'     => false,
        // Use a asynchronous font on the front end or font string
        //'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
        'admin_bar'            => true,
        // Show the panel pages on the admin bar
        'admin_bar_icon'       => 'dashicons-admin-tool',
        // Choose an icon for the admin bar menu
        'menu_icon'                 => 'dashicons-admin-tool',
        // menu icon		
        'admin_bar_priority'   => 50,
        // Choose an priority for the admin bar menu
        'global_variable'      => '',
        // Set a different name for your global variable other than the opt_name
        'dev_mode'             => true,
        // Show the time the page took to load, etc
        'update_notice'        => true,
        // If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
        'customizer'           => true,
        // Enable basic customizer support
        //'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
        //'disable_save_warn' => true,                    // Disable the save warning when a user changes a field

        // OPTIONAL -> Give you extra features
        'page_priority'        => 2,
        // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
        'page_parent'          => 'themes.php',
        // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
        'page_permissions'     => 'manage_options',
        // Permissions needed to access the options panel.
        'menu_icon'            => '',
        // Specify a custom URL to an icon
        'last_tab'             => '',
        // Force your panel to always open to a specific tab (by id)
        'page_icon'            => 'icon-themes',
        // Icon displayed in the admin panel next to your menu_title
        'page_slug'            => 'redux_demo',
        // Page slug used to denote the panel, will be based off page title then menu title then opt_name if not provided
        'save_defaults'        => true,
        // On load save the defaults to DB before user clicks save or not
        'default_show'         => false,
        // If true, shows the default value next to each field that is not the default value.
        'default_mark'         => '',
        // What to print by the field's title if the value shown is default. Suggested: *
        'show_import_export'   => true,
        // Shows the Import/Export panel when not used as a field.
		 'show_options_object' => false,
        // Shows the options object panel.	
        // CAREFUL -> These options are for advanced use only
        'transient_time'       => 60 * MINUTE_IN_SECONDS,
        'output'               => true,
        // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
        'output_tag'           => true,
        // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
        // 'footer_credit'     => '',                   // Disable the footer credit of Redux. Please leave if you can help it.

        // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
        'database'             => '',
        // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
        'use_cdn'              => true,
        // If you prefer not to use the CDN for Select2, Ace Editor, and others, you may download the Redux Vendor Support plugin yourself and run locally or embed it in your code.

        // HINTS
        'hints'                => array(
            'icon'          => 'el el-question-sign',
            'icon_position' => 'right',
            'icon_color'    => 'lightgray',
            'icon_size'     => 'normal',
            'tip_style'     => array(
                'color'   => 'red',
                'shadow'  => true,
                'rounded' => false,
                'style'   => '',
            ),
            'tip_position'  => array(
                'my' => 'top left',
                'at' => 'bottom right',
            ),
            'tip_effect'    => array(
                'show' => array(
                    'effect'   => 'slide',
                    'duration' => '500',
                    'event'    => 'mouseover',
                ),
                'hide' => array(
                    'effect'   => 'slide',
                    'duration' => '500',
                    'event'    => 'click mouseleave',
                ),
            ),
        )
    );

    // ADMIN BAR LINKS -> Setup custom links in the admin bar menu as external items.
    $args['admin_bar_links'][] = array(
        'id'    => 'redux-docs',
        'href'  => 'http://docs.reduxframework.com/',
        'title' => __( 'Documentation', 'javapaper' ),
    );

    $args['admin_bar_links'][] = array(
        //'id'    => 'redux-support',
        'href'  => 'https://github.com/ReduxFramework/redux-framework/issues',
        'title' => __( 'Support', 'javapaper' ),
    );

    $args['admin_bar_links'][] = array(
        'id'    => 'redux-extensions',
        'href'  => 'reduxframework.com/extensions',
        'title' => __( 'Extensions', 'javapaper' ),
    );

    // SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
    $args['share_icons'][] = array(
        'url'   => 'https://github.com/ReduxFramework/ReduxFramework',
        'title' => 'Visit us on GitHub',
        'icon'  => 'el el-github'
        //'img'   => '', // You can use icon OR img. IMG needs to be a full URL.
    );
    $args['share_icons'][] = array(
        'url'   => 'https://www.facebook.com/pages/Redux-Framework/243141545850368',
        'title' => 'Like us on Facebook',
        'icon'  => 'el el-facebook'
    );
    $args['share_icons'][] = array(
        'url'   => 'http://twitter.com/reduxframework',
        'title' => 'Follow us on Twitter',
        'icon'  => 'el el-twitter'
    );
    $args['share_icons'][] = array(
        'url'   => 'http://www.linkedin.com/company/redux-framework',
        'title' => 'Find us on LinkedIn',
        'icon'  => 'el el-linkedin'
    );

    // Panel Intro text -> before the form
    if ( ! isset( $args['global_variable'] ) || $args['global_variable'] !== false ) {
        if ( ! empty( $args['global_variable'] ) ) {
            $v = $args['global_variable'];
        } else {
            $v = str_replace( '-', '_', $args['opt_name'] );
        }
        $args['intro_text'] = sprintf( __( '<p>Did you know that Redux sets a global variable for you? To access any of your saved options from within your code you can use your global variable: <strong>$%1$s</strong></p>', 'javapaper' ), $v );
    } else {
        $args['intro_text'] = __( '<p>This text is displayed above the options panel. It isn\'t required, but more info is always better! The intro_text field accepts all HTML.</p>', 'javapaper' );
    }

    // Add content after the form.
    $args['footer_text'] = __( '<p>This text is displayed below the options panel. It isn\'t required, but more info is always better! The footer_text field accepts all HTML.</p>', 'javapaper' );

    Redux::setArgs( $opt_name, $args );

    /*
     * ---> END ARGUMENTS
     */


    /*
     * ---> START HELP TABS
     */

    $tabs = array(
        array(
            'id'      => 'redux-help-tab-1',
            'title'   => __( 'Theme Information 1', 'javapaper' ),
            'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'javapaper' )
        ),
        array(
            'id'      => 'redux-help-tab-2',
            'title'   => __( 'Theme Information 2', 'javapaper' ),
            'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'javapaper' )
        )
    );
    Redux::set_help_tab( $opt_name, $tabs );

    // Set the help sidebar
    $content = __( '<p>This is the sidebar content, HTML is allowed.</p>', 'javapaper' );
    Redux::set_help_sidebar( $opt_name, $content );


    /*
     * <--- END HELP TABS
     */


    /*
     *
     * ---> START SECTIONS
     *
     */

    /*

        As of Redux 3.5+, there is an extensive API. This API can be used in a mix/match mode allowing for


     */

    // -> START Basic Fields
    Redux::setSection( $opt_name, array(
        'title'            => __( 'General Settings', 'javapaper' ),
        'id'               => 'basic',
        'desc'             => __( 'These are really basic fields!', 'javapaper' ),
        'customizer_width' => '400px',
        'icon'             => 'el el-home'
    ) );

    Redux::setSection( $opt_name, array(
        'title'            => __( 'Favicon', 'javapaper' ),
        'id'               => 'jp-Text',
        'subsection'       => true,
        'customizer_width' => '700px',
        'fields'           => array(
			array(
				'id'        => 'jp_favicon',
				'type'      => 'media',
				'url'       => true,
				'title'     => __('Custom Favicon', 'javapaper' ),
				'compiler'  => 'false',
				'subtitle'  => __('Upload your logo', 'javapaper' ),
				'default'   => array('url' => get_stylesheet_directory_uri() . '/images/favicon.png'),
			),	
        )
    ) );
	

    Redux::setSection( $opt_name, array(
        'title'      => __( 'Link Color', 'javapaper' ),
        'id'         => 'jp_generallink',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'       => 'jp_linkcolor',
                'type'     => 'color',
                'output'   => array( 'a, a:link, a:visited' ),
                'title'    => __( 'LINK COLOR', 'javapaper' ),
                'subtitle' => __( 'Pick a title color for the theme (default: #000).', 'javapaper' ),
            ),
            array(
                'id'       => 'jp_bovercolor',
                'type'     => 'color',
                'output'   => array( '.widget-area .widget a:hover, a:hover' ),
                'title'    => __( 'HOVER COLOR', 'javapaper' ),
                'subtitle' => __( 'Pick a title color for the theme (default: #999).', 'javapaper' ),
            ),
            array(
                'id'       => 'jp_activecolor',
                'type'     => 'color',
                'output'   => array( 'a:active' ),
                'title'    => __( 'ACTIVE COLOR', 'javapaper' ),
                'subtitle' => __( 'Pick a title color for the theme (default: #000).', 'javapaper' ),
            ),
        )
    ) );

// ->================================================================================================================
   // -> START HEADER AREA	
    Redux::setSection( $opt_name, array(
        'title'      => __( 'Header Area', 'javapaper' ),
        'id'         => 'select-select',
        'desc'             => __( 'SET THE HEADER!', 'javapaper' ),
        'customizer_width' => '400px',
	    'icon'  => 'el el-th-large'	
    ) );

	
    Redux::setSection( $opt_name, array(
        'title'            => __( 'Header Layout', 'javapaper' ),
        'id'               => 'basic-headerlayout',
        'subsection'       => true,
        'fields'     => array(
            array(
                'id'       => 'header_layout',
                'type'     => 'image_select',
                'title'    => __( 'HEADER LAYOUT', 'javapaper' ),
                'subtitle' => __( 'Choose the header layout', 'javapaper' ),
                'options'  => array(
                    'default' => array(
						'title' => 'Default',
                        'alt' => 'Default',
                        'img' => get_stylesheet_directory_uri() . '/images/header-styledefault.png'
                    ),				
                    'style2' => array(
						'title' => 'Style 2',					
                        'alt' => '2 Column',
                        'img' => get_stylesheet_directory_uri() . '/images/header-style2.png'
                    ),

                    'style3' => array(
						'title' => 'Style 3',						
                        'alt' => '3 Column',
                        'img' => get_stylesheet_directory_uri() . '/images/header-style3.png'
                    ),

                    'style4' => array(
						'title' => 'Style 4',						
                        'alt' => '4 Column',
                        'img' => get_stylesheet_directory_uri() . '/images/header-style4.png'
                    ),

                    'style5' => array(
						'title' => 'Style 5',						
                        'alt' => '5 Column',
                        'img' => get_stylesheet_directory_uri() . '/images/header-style4.png'
                    ),
                    'style6' => array(
						'title' => 'Style 6',						
                        'alt' => '6 Column',
                        'img' => get_stylesheet_directory_uri() . '/images/header-style4.png'
                    ),
                    'style7' => array(
						'title' => 'Style 7',						
                        'alt' => '7 Column',
                        'img' => get_stylesheet_directory_uri() . '/images/header-style4.png'
                    )
                ),
                'default'  => 'default'
            ),

        )
    ) );
	
    Redux::setSection( $opt_name, array(
        'title'            => __( 'Header Logo', 'javapaper' ),
        'id'               => 'header-logo',
        'subsection'       => true,
        'fields'     => array(
			array(
				'id'        => 'opt_header_logo',
				'type'      => 'media',
				'url'       => true,
				'title'     => __('IMAGE LOGO', 'javapaper' ),
				'compiler'  => 'false',
	            'default' => array(
                                'url' => get_template_directory_uri().'/images/logo.png'
                                ),
				'subtitle'  => __('If you dont upload image, then type your logos name bellow', 'javapaper' ),
			), 
	
            array(
                'id'       => 'opt_header_text',
                'type'     => 'text',
                'title'    => __( 'TEXT LOGO', 'javapaper' ),
                'subtitle' => __( 'Enter your text logo here.', 'javapaper' ),
                'desc'     => __( 'If you dont use image as logo, then this text will appear.', 'javapaper' ),
				"default" => 'JAVAPAPER',
				'allowed_html' => array(
					'strong' => array()
				)				
            ),	

            array(
                'id'       => 'logo_typography',
                'type'     => 'typography',
                'title'    => __( 'Text Logo Typography', 'javapaper' ),
                'subtitle' => __( 'Specify the header font properties.', 'javapaper' ),
                'google'   => true,
                'text-transform'   => true,	
                'letter-spacing'   => true,					
                'output' => array('.javapaperlogo h1, h1.javapaperlogo'),
                'default'  => array(
                    'font-family' => 'Times New Roman',				
                    'font-size'   => '48px',
                    'line-height'   => '56px',					
                    'font-weight' => 'bold',
                ),
            ),	
            array(
                'id'       => 'logo_typographycolor',
                'type'     => 'color',
                'output'   => array( '.javapaperlogo h1 a' ),
                'title'    => __( 'TEXT COLOR', 'javapaper' ),
                'subtitle' => __( 'Pick a title color for the logo.', 'javapaper' ),
            ),			
        )
    ) );
	
    Redux::setSection( $opt_name, array(
        'title'            => __( 'Header Decoration', 'javapaper' ),
        'id'               => 'jp_headerdecor',
        'subsection'       => true,
        'fields'           => array(
            array(
                'id'       => 'header-background',
                'type'     => 'background',
                'output'   => array( '.header-middle' ),
                'title'    => __( 'Header Background', 'javapaper' ),
                'subtitle' => __( 'Header background with image or color.', 'javapaper' ),
                'default'   => '#FFFFFF',
            ),
        )
    ) );	
	
    Redux::setSection( $opt_name, array(
        'title'            => __( 'Navigation Layout', 'javapaper' ),
        'desc'             => __( 'Setting up the navigation layout', 'javapaper' ),
        'id'               => 'basic-navigation',
        'subsection'       => true,
        'fields'           => array(
            array(
                'id'       => 'header-bordertop',
                'type'     => 'border',
                'title'    => __( 'Top Navigation Border', 'javapaper' ),
                'subtitle' => __( 'Only color validation can be done on this field type', 'javapaper' ),
                'output'   => array( '.header-top' ),
                'all'      => false,
                'desc'     => __( 'This is the description field, again good for additional info.', 'javapaper' ),
            ),		
            array(
                'id'       => 'opt-header-border2',
                'type'     => 'border',
                'title'    => __( 'Main Navigation Border', 'javapaper' ),
                'subtitle' => __( 'Only color validation can be done on this field type', 'javapaper' ),
                'output'   => array( '.nav-mainwrapper' ),
                'all'      => false,
                'desc'     => __( 'This is the description field, again good for additional info.', 'javapaper' ),
            ),
            array(
                'id'       => 'jp_headernavtop',
                'type'     => 'background',
                'output'   => array( '.header-top' ),
                'title'    => __( 'Top Navigation Background', 'javapaper' ),
                'subtitle' => __( 'Nav background with image or color.', 'javapaper' ),
                'default'   => '#FFFFFF',
            ),			
            array(
                'id'       => 'jp_headernavback',
                'type'     => 'background',
                'output'   => array( '.nav-mainwrapper' ),
                'title'    => __( 'Main Navigation Background', 'javapaper' ),
                'subtitle' => __( 'Nav background with image or color.', 'javapaper' ),
                'default'   => '#FFFFFF',
            ),			

        )
    ) );		
	
    Redux::setSection( $opt_name, array(
        'title'            => __( 'Header FAQ', 'javapaper' ),
        'id'               => 'jp_headerfaq',
        'subsection'       => true,
            'fields' => array(
                array(
                    'id'       => 'faqj',
                    'type'     => 'raw',
                    'markdown' => true,
                    'content_path' => dirname( __FILE__ ) . '/FAQ-header.txt', // FULL PATH, not relative please
                    //'content' => 'Raw content here',
                ),
            ),
    ) );	
// ->END OF HEADER AREA ================================================================================================================// 
   // -> START footer AREA
   Redux::setSection( $opt_name, array(
        'title'      => __( 'Footer Area', 'javapaper' ),
        'id'         => 'footer-select',
	    'icon'  => 'el el-website'	,
        'desc'     => __( 'Change your footer layout.', 'javapaper' ),
        'customizer_width' => '400px',
    ) );
    Redux::setSection( $opt_name, array(
        'title'            => __( 'Footer Layout', 'javapaper' ),
        'id'               => 'basic-footerlayout',
        'subsection'       => true,
        'fields'     => array(
            array(
                'id'       => 'footer_layout',
                'type'     => 'image_select',
                'title'    => __( 'footer LAYOUT', 'javapaper' ),
                'subtitle' => __( 'Choose the footer layout', 'javapaper' ),
                //Must provide key => value(array:title|img) pairs for radio options
                'options'  => array(
                    'footer' => array(
						'title' => 'Default',	
                        'alt' => 'Default',
                        'img' => get_stylesheet_directory_uri() . '/images/footer-style2.png'
                    ),				
                    'page-templates/footer-style2' => array(
						'title' => 'Style 2',						
                        'alt' => 'Style 2',
                        'img' => get_stylesheet_directory_uri() . '/images/footer-style2.png'
                    ),

                    'page-templates/footer-style3' => array(
						'title' => 'Style 3',						
                        'alt' => 'Style 3',
                        'img' => get_stylesheet_directory_uri() . '/images/footer-style2.png'
                    ),

                    'page-templates/footer-style4' => array(
						'title' => 'Style 4',						
                        'alt' => 'Style 4',
                        'img' => get_stylesheet_directory_uri() . '/images/footer-style2.png'
                    ),

                    'page-templates/footer-style5' => array(
						'title' => 'Style 5',						
                        'alt' => 'Style 5',
                        'img' => get_stylesheet_directory_uri() . '/images/footer-style2.png'
                    ),
                    'page-templates/footer-style6' => array(
						'title' => 'Style 6',						
                        'alt' => 'Style 6',
                        'img' => get_stylesheet_directory_uri() . '/images/footer-style2.png'
                    ),
                ),
                'default'  => 'footer'
            ),

        )
    ) );
    Redux::setSection( $opt_name, array(
        'title'            => __( 'Footer Text and Logo', 'javapaper' ),
        'id'               => 'jp_footertextlogo',
        'subsection'       => true,
        'fields'           => array(
			array(
				'id'        => 'opt_footer_logo',
				'type'      => 'media',
				'url'       => true,
				'title'     => __('Footer Logo', 'javapaper' ),
				'compiler'  => 'false',
				'subtitle'  => __('Upload your logo', 'javapaper' ),
				'default'   => array('url' => get_stylesheet_directory_uri() . '/images/logo.png'),
			),
            array(
                'id'       => 'footer_text',
                'type'     => 'textarea',
                'title'    => __( 'Footer Text', 'javapaper' ),
                'subtitle' => __( 'Enter your custom footer text here. You can also insert HTML and image', 'javapaper' ),
                'desc'     => __( 'This is the description field, again good for additional info.', 'javapaper' ),
				"default" => "&copy; Copyright ".date('Y').' - '.get_bloginfo('name'). '. All Rights Reserved',
				'allowed_html' => array(
					'a' => array(
					'href' => array(),
					'title' => array()
					),
					'br' => array(),
					'em' => array(),
					'b' => array(),		
					'strong' => array()
				)				
            ),
        )
    ) );
	
    Redux::setSection( $opt_name, array(
        'title'            => __( 'Main Footer Decoration', 'javapaper' ),
        'id'               => 'jp_mainfooterdecor',
        'subsection'       => true,
        'fields'           => array(
            array(
                'id'       => 'jp_footerscheme',
                'type'     => 'image_select',
                'title'    => __( 'Background Color Scheme', 'javapaper' ),
                'subtitle' => __( 'Define the scheme will handle the footer content color', 'javapaper' ),
                'options'  => array(
                    'dark' => array(
						'title' => 'Dark',
                        'alt' => 'Dark Footer',
                        'img' => get_stylesheet_directory_uri() . '/images/header-styledefault.png'
                    ),				
                    'light' => array(
						'title' => 'Light',					
                        'alt' => 'Light Footer',
                        'img' => get_stylesheet_directory_uri() . '/images/header-style2.png'
                    ),					
                ),				
                'default'  => 'light'
            ),	
		
            array(
                'id'       => 'footer-background',
                'type'     => 'background',
                'output'   => array( '.footer-wrapinside, .footer7-subtitle2' ),
                'title'    => __( 'MAIN FOOTER BACKGROUND', 'javapaper' ),
                'subtitle' => __( 'footer background with image or color.', 'javapaper' ),
                'default'   => '#FFFFFF',
            ),			
            array(
                'id'       => 'jp_border2',
                'type'     => 'border',
                'title'    => __( 'FOOTER BORDER', 'javapaper' ),
                'subtitle' => __( 'Change options for footer border', 'javapaper' ),
                'output'   => array( '.footer-wrapinside, .footer7-subwrapper' ),
                'all'      => false,
                'desc'     => __( 'If you want to hide the border, just put "0"(zero) value into the box.', 'javapaper' ),
            ),			
            array(
                'id'       => 'jp_footercolor1',
                'type'     => 'color',
                'output'   => array( '.footer-topinside .widget-title,.footer-topinside aside.widget, .footer h3.widgettitle,.footer-payment2,.footer7-subtitle2,.footer7-subtitle2 h2,
.dark .footer-topinside .widget-title, .dark .footer-topinside aside.widget,.dark .footer h3.widgettitle, .dark .footer-payment2, .dark .footer7-subtitle2, .dark .footer7-subtitle2 h2				
				' ),
                'title'    => __( 'TEXT COLOR', 'javapaper' ),
                'subtitle' => __( 'Pick a title color for the theme (default: #000).', 'javapaper' ),
            ),			
            array(
                'id'       => 'jp_footerlinkcolor',
                'type'     => 'color',
                'output'   => array( '.footer-topinside aside.widget a, .footer-widgetinside a:link, .footer-widgetinside a:visited	' ),
                'title'    => __( 'LINK COLOR', 'javapaper' ),
                'subtitle' => __( 'Pick a title color for the theme (default: #000).', 'javapaper' ),
            ),
            array(
                'id'       => 'jp_footerbovercolor',
                'type'     => 'color',
                'output'   => array( '.footer-topinside aside.widget a:hover,.footer-widgetinside a:hover' ),
                'title'    => __( 'HOVER COLOR', 'javapaper' ),
                'subtitle' => __( 'Pick a title color for the theme (default: #000).', 'javapaper' ),
            ),
            array(
                'id'       => 'jp_footeractivecolor',
                'type'     => 'color',
                'output'   => array( '.footer-widgetinside a:active,
				.mega_main_menu.footer-links > .menu_holder > .menu_inner > .nav_logo > .mobile_toggle > .mobile_button, .mega_main_menu.footer-links > .menu_holder > .menu_inner > ul > li > .item_link a:active, 
				.mega_main_menu.footer-links > .menu_holder > .menu_inner > ul > li > .item_link *a:active' ),
                'title'    => __( 'ACTIVE COLOR', 'javapaper' ),
            ),			
        )
    ) );		


    Redux::setSection( $opt_name, array(
        'title'            => __( 'Bottom Footer Decoration', 'javapaper' ),
        'id'               => 'jp_bottomfooterdecor',
        'subsection'       => true,
        'fields'           => array(
            array(
                'id'       => 'bottomfooter-background',
                'type'     => 'background',
                'output'   => array( '.footer-bottom-wrapper' ),
                'title'    => __( 'BOTTOM FOOTER BACKGROUND', 'javapaper' ),
                'subtitle' => __( 'footer background with image or color.', 'javapaper' ),
                'default'   => '#FFFFFF',
            ),			
            array(
                'id'       => 'jp_bottomborder2',
                'type'     => 'border',
                'title'    => __( 'FOOTER BORDER', 'javapaper' ),
                'subtitle' => __( 'Change options for footer border', 'javapaper' ),
                'output'   => array( '.footer-bottom-wrapper' ),
                'all'      => false,
                'desc'     => __( 'If you want to hide the border, just put "0"(zero) value into the box.', 'javapaper' ),
            ),
			
		
			
            array(
                'id'       => 'jp_bottomfootercolor',
                'type'     => 'color',
                'output'   => array( '.footer-bottom-wrapper, .site-wordpress' ),
                'title'    => __( 'TEXT COLOR', 'javapaper' ),
                'subtitle' => __( 'Pick a title color for the theme (default: #000).', 'javapaper' ),
            ),			
            array(
                'id'       => 'jp_bottomfooterlinkcolor',
                'type'     => 'color',
                'output'   => array( '.footer-bottom-wrapper .javapaper-nav li a' ),
                'title'    => __( 'LINK COLOR', 'javapaper' ),
                'subtitle' => __( 'Pick a title color for the theme (default: #000).', 'javapaper' ),
            ),
            array(
                'id'       => 'jp_bottomfooterbovercolor',
                'type'     => 'color',
                'output'   => array( '.footer-bottom-wrapper a:hover' ),
                'title'    => __( 'HOVER COLOR', 'javapaper' ),
                'subtitle' => __( 'Pick a title color for the theme (default: #000).', 'javapaper' ),
            ),
            array(
                'id'       => 'jp_bottomfooteractivecolor',
                'type'     => 'color',
                'output'   => array( '.footer-bottom-wrapper a' ),
                'title'    => __( 'ACTIVE COLOR', 'javapaper' ),
            ),			
        )
    ) );

    Redux::setSection( $opt_name, array(
        'title'            => __( 'FAQ Footer', 'javapaper' ),
        'id'               => 'jp_footerfaq',
        'subsection'       => true,
            'fields' => array(
                array(
                    'id'       => 'faqfooter',
                    'type'     => 'raw',
                    'markdown' => true,
                    'content_path' => dirname( __FILE__ ) . '/FAQ-footer.txt', // FULL PATH, not relative please
                    //'content' => 'Raw content here',
                ),
            ),
    ) );	
// ->END OF footer AREA ================================================================================================================

// ->START OF TYPOGRAPHY ================================================================================================================

    Redux::setSection( $opt_name, array(
        'title'  => __( 'Typography', 'javapaper' ),
        'id'     => 'typography',
        'icon'   => 'el el-bold',
        'fields' => array(
            array(
                'id'       => 'javapaper_typographybody',
                'type'     => 'typography',
                'title'    => __( 'Body Font', 'javapaper' ),
                'subtitle' => __( 'Specify the body font properties.', 'javapaper' ),
                'google'   => true,
                'text-transform'   => true,	
                'letter-spacing'   => true,					
                'output' => array('body'),
            ),		
            array(
                'id'       => 'javapaper_typography',
                'type'     => 'typography',
                'title'    => __( 'h1 Font', 'javapaper' ),
                'subtitle' => __( 'Specify the body font properties.', 'javapaper' ),
                'google'   => true,
                'text-transform'   => true,	
                'letter-spacing'   => true,					
                'output' => array('.entry-header .entry-title, .entry-content h1, h1'),
                'default'  => array(
                    'font-family' => 'Playfair Display',				
                    'font-size'   => '48px',
                    'line-height'   => '54px',	
                    'font-weight' => 'bold',
                ),
            ),
            array(
                'id'       => 'javapaper_typography2',
                'type'     => 'typography',
                'title'    => __( 'h2 Font', 'javapaper' ),
                'subtitle' => __( 'Specify the body font properties.', 'javapaper' ),
                'google'   => true,
                'text-transform'   => true,	
                'letter-spacing'   => true,					
                'output' => array('.entry-content h2, h2'),
                'default'  => array(
                    'font-family' => 'Playfair Display',				
                    'font-size'   => '26px',
                    'line-height'   => '30px',	
                    'font-weight' => 'bold',
                ),				
            ),	
            array(
                'id'       => 'javapaper_typography3',
                'type'     => 'typography',
                'title'    => __( 'h3 Font', 'javapaper' ),
                'subtitle' => __( 'Specify the body font properties.', 'javapaper' ),
                'google'   => true,
                'text-transform'   => true,	
                'letter-spacing'   => true,					
                'output' => array('.entry-content h3, h3'),
                'default'  => array(
                    'font-family' => 'Playfair Display',				
                    'font-size'   => '24px',
                    'line-height'   => '28px',	
                    'font-weight' => 'bold',
                ),				
            ),	
            array(
                'id'       => 'javapaper_typography4',
                'type'     => 'typography',
                'title'    => __( 'h4 Font', 'javapaper' ),
                'subtitle' => __( 'Specify the body font properties.', 'javapaper' ),
                'google'   => true,
                'text-transform'   => true,	
                'letter-spacing'   => true,					
                'output' => array('.entry-content h4, h4'),
                'default'  => array(
                    'font-family' => 'Playfair Display',				
                    'font-size'   => '22px',
                    'line-height'   => '26px',	
                    'font-weight' => 'Normal',
                ),					
            ),	
            array(
                'id'       => 'javapaper_typography5',
                'type'     => 'typography',
                'title'    => __( 'h5 Font', 'javapaper' ),
                'subtitle' => __( 'Specify the body font properties.', 'javapaper' ),
                'google'   => true,
                'text-transform'   => true,	
                'letter-spacing'   => true,					
                'output' => array('.entry-content h5, h5, h5 a'),
                'default'  => array(
                    'font-family' => 'Playfair Display',				
                    'font-size'   => '18px',
                    'line-height'   => '22px',	
                    'font-weight' => '500',
                ),					
            ),		
            array(
                'id'       => 'javapaper_typography6',
                'type'     => 'typography',
                'title'    => __( 'h6 Font', 'javapaper' ),
                'subtitle' => __( 'Specify the body font properties.', 'javapaper' ),
                'google'   => true,
                'text-transform'   => true,	
                'letter-spacing'   => true,					
                'output' => array('.entry-content h6, h6'),
                'default'  => array(
                    'font-family' => 'Playfair Display',				
                    'font-size'   => '16px',
                    'line-height'   => '22px',	
                    'font-weight' => '500',
                ),					
            ),	
            array(
                'id'       => 'Widget_Title',
                'type'     => 'typography',
                'title'    => __( 'Widget Title', 'javapaper' ),
                'subtitle' => __( 'Specify the body font properties.', 'javapaper' ),
                'google'   => true,
                'text-transform'   => true,	
                'letter-spacing'   => true,					
                'output' => array('.widget-title'),
                'default'  => array(
                    'font-family' => 'Playfair Display',				
                    'font-size'   => '24px',
                    'line-height'   => '26px',	
                    'font-weight' => 'bold',
                ),	
            ),				

        )
    ) );
// ->END OF TYPOGRAPHY ================================================================================================================	
// -> START SIDEBAR STYLE================================================================================================================
    Redux::setSection( $opt_name, array(
        'title'  => __( 'Sidebar Style', 'javapaper' ),
        'id'     => 'jp_sidebargeneral',
        'icon'   => 'el el-list',
        'fields' => array(
            array(
                'id'       => 'jp_sidebar',
                'type'     => 'image_select',
                'title'    => __( 'SELECT SIDEBAR STYLE', 'javapaper' ),
                'subtitle' => __( '3 Styles Available', 'javapaper' ),				
                'options'  => array(
                    'right' => array(
						'title' => 'right',
                        'alt' => 'right',
                        'img' => get_stylesheet_directory_uri() . '/images/header-styledefault.png'
                    ),				
                    'left' => array(
						'title' => 'left',					
                        'alt' => 'left',
                        'img' => get_stylesheet_directory_uri() . '/images/header-style2.png'
                    )					
                ),
				
                'default'  => 'right'
            ),			
	
        )
    ) );
// ->END SIDEBAR STYLE ================================================================================================================
// ->START CATEGORY TEMPLATE================================================================================================================
    Redux::setSection( $opt_name, array(
        'title'      => __( 'Category Template', 'javapaper' ),
        'id'         => 'jp_cattemplategeneral',
        'desc'             => __( 'You can display your category page with different style', 'javapaper' ),
        'customizer_width' => '400px',
	    'icon'  => 'el el-lines'	,
        'fields'           => array(
			array(
				'id' => 'jp_category1',
				'type' => 'select',
				'multi'    => true,
				'data' => 'categories',
				'title' => __('Select Category 1', 'javapaper'),
				'subtitle' => __('Select 1 or more Category', 'javapaper'),
			),	
            array(
                'id'       => 'jp_cattemplate1',
                'type'     => 'button_set',
                'title'    => __( 'Select Template for Category 1', 'javapaper' ),
                'subtitle' => __( 'Select template', 'javapaper' ),
                'options'  => array(
                    'page-templates/category_default' => 'Default',
                    'page-templates/category1' => 'Layout 1',
                    'page-templates/category2' => 'Layout 2',
                    'page-templates/category3' => 'Layout 3',
                    'page-templates/category4' => 'Layout 4',
                    'page-templates/category5' => 'Layout 5',
                ),
                'default'  => 'page-templates/category_default',				
            ),			
			array(
				'id' => 'jp_category2',
				'type' => 'select',
				'multi'    => true,
				'data' => 'categories',
				'title' => __('Select Category 2', 'javapaper'),
				'subtitle' => __('Select 1 or more Category', 'javapaper'),
			),	
            array(
                'id'       => 'jp_cattemplate2',
                'type'     => 'button_set',
                'title'    => __( 'Select Template for Category 2', 'javapaper' ),
                'subtitle' => __( 'Select template', 'javapaper' ),
                'options'  => array(
                    'page-templates/category_default' => 'Default',
                    'page-templates/category1' => 'Layout 1',
                    'page-templates/category2' => 'Layout 2',
                    'page-templates/category3' => 'Layout 3',
                    'page-templates/category4' => 'Layout 4',
                    'page-templates/category5' => 'Layout 5',
                ),
                'default'  => 'page-templates/category_default',				
            ),		
			array(
				'id' => 'jp_category3',
				'type' => 'select',
				'multi'    => true,
				'data' => 'categories',
				'title' => __('Select Category 3', 'javapaper'),
				'subtitle' => __('Select 1 or more Category', 'javapaper'),
			),	
            array(
                'id'       => 'jp_cattemplate3',
                'type'     => 'button_set',
                'title'    => __( 'Select Template for Category 3', 'javapaper' ),
                'subtitle' => __( 'Select template', 'javapaper' ),
                'options'  => array(
                    'page-templates/category_default' => 'Default',
                    'page-templates/category1' => 'Layout 1',
                    'page-templates/category2' => 'Layout 2',
                    'page-templates/category3' => 'Layout 3',
                    'page-templates/category4' => 'Layout 4',
                    'page-templates/category5' => 'Layout 5',
                ),
                'default'  => 'page-templates/category_default',				
            ),	
			array(
				'id' => 'jp_category4',
				'type' => 'select',
				'multi'    => true,
				'data' => 'categories',
				'title' => __('Select Category 4', 'javapaper'),
				'subtitle' => __('Select 1 or more Category', 'javapaper'),
			),	
            array(
                'id'       => 'jp_cattemplate4',
                'type'     => 'button_set',
                'title'    => __( 'Select Template for Category 4', 'javapaper' ),
                'subtitle' => __( 'Select template', 'javapaper' ),
                'options'  => array(
                    'page-templates/category_default' => 'Default',
                    'page-templates/category1' => 'Layout 1',
                    'page-templates/category2' => 'Layout 2',
                    'page-templates/category3' => 'Layout 3',
                    'page-templates/category4' => 'Layout 4',
                    'page-templates/category5' => 'Layout 5',
                ),
                'default'  => 'page-templates/category_default',				
            ),				
			array(
				'id' => 'jp_category5',
				'type' => 'select',
				'multi'    => true,
				'data' => 'categories',
				'title' => __('Select Category 5', 'javapaper'),
				'subtitle' => __('Select 1 or more Category', 'javapaper'),
			),	
            array(
                'id'       => 'jp_cattemplate5',
                'type'     => 'button_set',
                'title'    => __( 'Select Template for Category 5', 'javapaper' ),
                'subtitle' => __( 'Select template', 'javapaper' ),
                'options'  => array(
                    'page-templates/category_default' => 'Default',
                    'page-templates/category1' => 'Layout 1',
                    'page-templates/category2' => 'Layout 2',
                    'page-templates/category3' => 'Layout 3',
                    'page-templates/category4' => 'Layout 4',
                    'page-templates/category5' => 'Layout 5',
                ),
                'default'  => 'page-templates/category_default',				
            ),
			array(
				'id' => 'jp_category6',
				'type' => 'select',
				'multi'    => true,
				'data' => 'categories',
				'title' => __('Select Category 6', 'javapaper'),
				'subtitle' => __('Select 1 or more Category', 'javapaper'),
			),	
            array(
                'id'       => 'jp_cattemplate6',
                'type'     => 'button_set',
                'title'    => __( 'Select Template for Category 6', 'javapaper' ),
                'subtitle' => __( 'Select template', 'javapaper' ),
                'options'  => array(
                    'page-templates/category_default' => 'Default',
                    'page-templates/category1' => 'Layout 1',
                    'page-templates/category2' => 'Layout 2',
                    'page-templates/category3' => 'Layout 3',
                    'page-templates/category4' => 'Layout 4',
                    'page-templates/category5' => 'Layout 5',
                ),
                'default'  => 'page-templates/category_default',				
            ),	
			array(
				'id' => 'jp_category7',
				'type' => 'select',
				'multi'    => true,
				'data' => 'categories',
				'title' => __('Select Category 7', 'javapaper'),
				'subtitle' => __('Select 1 or more Category', 'javapaper'),
			),	
            array(
                'id'       => 'jp_cattemplate7',
                'type'     => 'button_set',
                'title'    => __( 'Select Template for Category 7', 'javapaper' ),
                'subtitle' => __( 'Select template', 'javapaper' ),
                'options'  => array(
                    'page-templates/category_default' => 'Default',
                    'page-templates/category1' => 'Layout 1',
                    'page-templates/category2' => 'Layout 2',
                    'page-templates/category3' => 'Layout 3',
                    'page-templates/category4' => 'Layout 4',
                    'page-templates/category5' => 'Layout 5',
                ),
                'default'  => 'page-templates/category_default',				
            ),	
        )
    ) );		
// ->END OF CATEGORY TEMPLATE ================================================================================================================	


// ->START TAGS TEMPLATE================================================================================================================
    Redux::setSection( $opt_name, array(
        'title'      => __( 'TAGS Template', 'javapaper' ),
        'id'         => 'jp_tagtemplategeneral',
        'desc'             => __( 'You can display your tag page with different style', 'javapaper' ),
        'customizer_width' => '400px',
	    'icon'  => 'el el-lines'	,
        'fields'           => array(
			array(
				'id' => 'jp_tag1',
				'type' => 'select',
				'multi'    => true,
				'data' => 'tags',
				'title' => __('Select tag 1', 'javapaper'),
				'subtitle' => __('Select 1 or more tag', 'javapaper'),
			),	
            array(
                'id'       => 'jp_tagtemplate1',
                'type'     => 'button_set',
                'title'    => __( 'Select Template for tag 1', 'javapaper' ),
                'subtitle' => __( 'Select template', 'javapaper' ),
                'options'  => array(
                    'page-templates/tag_default' => 'Default',
                    'page-templates/tag1' => 'Layout 1',
                    'page-templates/tag2' => 'Layout 2',
                    'page-templates/tag3' => 'Layout 3',
                    'page-templates/tag4' => 'Layout 4',
                    'page-templates/tag5' => 'Layout 5',
                ),
                'default'  => 'page-templates/tag_default',				
            ),
			
			array(
				'id' => 'jp_tag2',
				'type' => 'select',
				'data' => 'tags',
				'title' => __('Select tag 2', 'javapaper'),
				'subtitle' => __('Select 1 or more tag', 'javapaper'),
			),	
            array(
                'id'       => 'jp_tagtemplate2',
                'type'     => 'button_set',
                'title'    => __( 'Select Template for tag 2', 'javapaper' ),
                'subtitle' => __( 'Select template', 'javapaper' ),
                'options'  => array(
                    'page-templates/tag_default' => 'Default',
                    'page-templates/tag1' => 'Layout 1',
                    'page-templates/tag2' => 'Layout 2',
                    'page-templates/tag3' => 'Layout 3',
                    'page-templates/tag4' => 'Layout 4',
                    'page-templates/tag5' => 'Layout 5',
                ),
                'default'  => 'page-templates/tag_default',				
            ),
			
        )
    ) );		
// ->END OF TAGS TEMPLATE ================================================================================================================	


// ->START SOCIAL MEDIA================================================================================================================
    Redux::setSection( $opt_name, array(
        'title' => __( 'Social Media', 'javapaper' ),
        'id'    => 'jp_generalsocmed',
        'desc'  => __( 'SOCIAL MEDIA', 'javapaper' ),
        'icon'  => 'el el-network'
    ) );
    Redux::setSection( $opt_name, array(
        'title'      => __( 'Social Media 1', 'javapaper' ),
        'id'         => 'jp_socmed1',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'         => 'jp_socmedimg1',
                'type'       => 'media',
                'title'      => __( 'Social media 2 Image', 'javapaper' ),
                'subtitle' => __( 'Upload Your Social media image', 'javapaper' ),				
				'url'       => true,    
                'mode'       => false,
            ),
            array(
                'id'       => 'jp_socmedlink1',
                'type'     => 'text',
                'title'    => __( 'Social media 1 Link', 'javapaper' ),
                'subtitle' => __( 'Type Your Social Media Link', 'javapaper' )				
            ),	
            array(
                'id'       => 'jp_socmedalt1',
                'type'     => 'text',
                'title'    => __( 'Social media 1 tooltip', 'javapaper' ),
                'subtitle' => __( 'Type For Tooltip When Hovering', 'javapaper' )	
            ),			
        )
    ) );

    Redux::setSection( $opt_name, array(
        'title'      => __( 'Social Media 2', 'javapaper' ),
        'id'         => 'jp_socmed2',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'         => 'jp_socmedimg2',
                'type'       => 'media',
                'title'      => __( 'Social media 2 Image', 'javapaper' ),
                'subtitle' => __( 'Upload Your Social media image', 'javapaper' ),					
				'url'       => true,    
                'mode'       => false,
            ),
            array(
                'id'       => 'jp_socmedlink2',
                'type'     => 'text',
                'title'    => __( 'Social media 2 Link', 'javapaper' ),
                'subtitle' => __( 'Type Your Social Media Link', 'javapaper' )	
            ),	
            array(
                'id'       => 'jp_socmedalt2',
                'type'     => 'text',
                'title'    => __( 'Social media 2 tooltip', 'javapaper' ),
                'subtitle' => __( 'Type For Tooltip When Hovering', 'javapaper' )	
            ),			
        )
    ) );
    Redux::setSection( $opt_name, array(
        'title'      => __( 'Social Media 3', 'javapaper' ),
        'id'         => 'jp_socmed3',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'         => 'jp_socmedimg3',
                'type'       => 'media',
                'title'      => __( 'Social media 3 Image', 'javapaper' ),
                'subtitle' => __( 'Upload Your Social media image', 'javapaper' ),					
				'url'       => true,    
                'mode'       => false,
            ),
            array(
                'id'       => 'jp_socmedlink3',
                'type'     => 'text',
                'title'    => __( 'Social media 3 Link', 'javapaper' ),
                'subtitle' => __( 'Type Your Social Media Link', 'javapaper' )	
		
            ),	
            array(
                'id'       => 'jp_socmedalt3',
                'type'     => 'text',
                'title'    => __( 'Social media 3 tooltip', 'javapaper' ),
                'subtitle' => __( 'Type For Tooltip When Hovering', 'javapaper' )	
            ),			
        )
    ) );
    Redux::setSection( $opt_name, array(
        'title'      => __( 'Social Media 4', 'javapaper' ),
        'id'         => 'jp_socmed4',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'         => 'jp_socmedimg4',
                'type'       => 'media',
                'title'      => __( 'Social media 4 Image', 'javapaper' ),
                'subtitle' => __( 'Upload Your Social media image', 'javapaper' ),					
				'url'       => true,    
                'mode'       => false,
            ),
            array(
                'id'       => 'jp_socmedlink4',
                'type'     => 'text',
                'title'    => __( 'Social media 4 Link', 'javapaper' ),
                'subtitle' => __( 'Type Your Social Media Link', 'javapaper' )	
            ),	
            array(
                'id'       => 'jp_socmedalt4',
                'type'     => 'text',
                'title'    => __( 'Social media 4 tooltip', 'javapaper' ),
                'subtitle' => __( 'Type For Tooltip When Hovering', 'javapaper' )	
            ),			
        )
    ) );
    Redux::setSection( $opt_name, array(
        'title'      => __( 'Social Media 5', 'javapaper' ),
        'id'         => 'jp_socmed5',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'         => 'jp_socmedimg5',
                'type'       => 'media',
                'title'      => __( 'Social media 5 Image', 'javapaper' ),
                'subtitle' => __( 'Upload Your Social media image', 'javapaper' ),					
				'url'       => true,    
                'mode'       => false,
            ),
            array(
                'id'       => 'jp_socmedlink5',
                'type'     => 'text',
                'title'    => __( 'Social media 5 Link', 'javapaper' ),
                'subtitle' => __( 'Type Your Social Media Link', 'javapaper' )	
            ),	
            array(
                'id'       => 'jp_socmedalt5',
                'type'     => 'text',
                'title'    => __( 'Social media 5 tooltip', 'javapaper' ),
                'subtitle' => __( 'Type For Tooltip When Hovering', 'javapaper' )	
            ),			
        )
    ) );	
    Redux::setSection( $opt_name, array(
        'title'      => __( 'Social Media 6', 'javapaper' ),
        'id'         => 'jp_socmed6',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'         => 'jp_socmedimg6',
                'type'       => 'media',
                'title'      => __( 'Social media 6 Image', 'javapaper' ),
                'subtitle' => __( 'Upload Your Social media image', 'javapaper' ),					
				'url'       => true,    
                'mode'       => false,
            ),
            array(
                'id'       => 'jp_socmedlink6',
                'type'     => 'text',
                'title'    => __( 'Social media 6 Link', 'javapaper' ),
                'subtitle' => __( 'Type Your Social Media Link', 'javapaper' )	
            ),	
            array(
                'id'       => 'jp_socmedalt6',
                'type'     => 'text',
                'title'    => __( 'Social media 6 tooltip', 'javapaper' ),
                'subtitle' => __( 'Type For Tooltip When Hovering', 'javapaper' )	
            ),			
        )
    ) );	
// ->START 404 MEDIA================================================================================================================
    Redux::setSection( $opt_name, array(
        'title' => __( '404', 'javapaper' ),
        'id'    => 'jp_404general',
        'icon'  => 'el el-compass',
        'fields'     => array(
            array(
                'id'         => 'jp_404image',
                'type'       => 'media',
                'title'      => __( '404 IMAGE', 'javapaper' ),
				'url'       => true,    
				'default'   => array('url' => get_stylesheet_directory_uri() . '/images/404.png'),				
                'mode'       => false,
                // Can be set to false to allow any media type, or can also be set to any mime type.
            ),
            array(
                'id'       => 'jp_404text1',
                'type'     => 'textarea',
                'title'    => __( 'MAIN TITLE FOR 404', 'javapaper' ),
				'default'  => 'Epic 404 - Article Not Found'				
		
            ),	
            array(
                'id'       => 'jp_404text2',
                'type'     => 'textarea',
                'title'    => __( 'SUBTITLE FOR 404', 'javapaper' ),
				'default'  => 'This is subtitle for the 404. Insert from the option panel'				
		
            ),			
        )
    ) );

// -> START SHOW/HIDE================================================================================================================
    Redux::setSection( $opt_name, array(
        'title'  => __( 'Show/Hide Elements', 'javapaper' ),
        'id'     => 'jp_showhidegeneral',
        'icon'   => 'el el-off',
        'fields' => array(
            array(
                'id'       => 'jp_showhidenav',
                'type'     => 'button_set',
                'title'    => __( 'HIDE/SHOW TOP NAVIGATION', 'javapaper' ),
                'subtitle' => __( 'Hide/show top navigation', 'javapaper' ),
                'options'  => array(
                    'show' => 'Show Element',
                    'hide' => 'Hide Element'
                ),
                'default'  => 'show'
            ),			
            array(
                'id'       => 'jp_showhiderelated',
                'type'     => 'button_set',
                'title'    => __( 'HIDE/SHOW RELATED POST', 'javapaper' ),
                'subtitle' => __( 'Hide/show related posts', 'javapaper' ),
                'options'  => array(
                    'show' => 'Show Element',
                    'hide' => 'Hide Element'
                ),
                'default'  => 'show'
            ),
        )
    ) );
// ->END SHOW/HIDE================================================================================================================	

    if ( file_exists( dirname( __FILE__ ) . '/../README.md' ) ) {
        $section = array(
            'icon'   => 'el el-list-alt',
            'title'  => __( 'Documentation', 'javapaper' ),
            'fields' => array(
                array(
                    'id'       => '17',
                    'type'     => 'raw',
                    'markdown' => true,
                    'content_path' => dirname( __FILE__ ) . '/../README.md', // FULL PATH, not relative please
                    //'content' => 'Raw content here',
                ),
            ),
        );
        Redux::setSection( $opt_name, $section );
    }
    /*
     * <--- END SECTIONS
     */


    /*
     *
     * YOU MUST PREFIX THE FUNCTIONS BELOW AND ACTION FUNCTION CALLS OR ANY OTHER CONFIG MAY OVERRIDE YOUR CODE.
     *
     */

    /*
    *
    * --> Action hook examples
    *
    */

    // If Redux is running as a plugin, this will remove the demo notice and links


    // Function to test the compiler hook and demo CSS output.
    // Above 10 is a priority, but 2 in necessary to include the dynamically generated CSS to be sent to the function.
    //add_filter('redux/options/' . $opt_name . '/compiler', 'compiler_action', 10, 3);

    // Change the arguments after they've been declared, but before the panel is created
    //add_filter('redux/options/' . $opt_name . '/args', 'change_arguments' );

    // Change the default value of a field after it's been set, but before it's been useds
    //add_filter('redux/options/' . $opt_name . '/defaults', 'change_defaults' );

    // Dynamically add a section. Can be also used to modify sections/fields
    //add_filter('redux/options/' . $opt_name . '/sections', 'dynamic_section');

    /**
     * This is a test function that will let you see when the compiler hook occurs.
     * It only runs if a field    set with compiler=>true is changed.
     * */
    if ( ! function_exists( 'compiler_action' ) ) {
        function compiler_action( $options, $css, $changed_values ) {
            echo '<h1>The compiler hook has run!</h1>';
            echo "<pre>";
            print_r( $changed_values ); // Values that have changed since the last save
            echo "</pre>";
            //print_r($options); //Option values
            //print_r($css); // Compiler selector CSS values  compiler => array( CSS SELECTORS )
        }
    }

    /**
     * Custom function for the callback validation referenced above
     * */
    if ( ! function_exists( 'redux_validate_callback_function' ) ) {
        function redux_validate_callback_function( $field, $value, $existing_value ) {
            $error   = false;
            $warning = false;

            //do your validation
            if ( $value == 1 ) {
                $error = true;
                $value = $existing_value;
            } elseif ( $value == 2 ) {
                $warning = true;
                $value   = $existing_value;
            }

            $return['value'] = $value;

            if ( $error == true ) {
                $field['msg']    = 'your custom error message';
                $return['error'] = $field;
            }

            if ( $warning == true ) {
                $field['msg']      = 'your custom warning message';
                $return['warning'] = $field;
            }

            return $return;
        }
    }

    /**
     * Custom function for the callback referenced above
     */
    if ( ! function_exists( 'redux_my_custom_field' ) ) {
        function redux_my_custom_field( $field, $value ) {
            print_r( $field );
            echo '<br/>';
            print_r( $value );
        }
    }

    /**
     * Custom function for filtering the sections array. Good for child themes to override or add to the sections.
     * Simply include this function in the child themes functions.php file.
     * NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
     * so you must use get_template_directory_uri() if you want to use any of the built in icons
     * */
    if ( ! function_exists( 'dynamic_section' ) ) {
        function dynamic_section( $sections ) {
            //$sections = array();
            $sections[] = array(
                'title'  => __( 'Section via hook', 'javapaper' ),
                'desc'   => __( '<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'javapaper' ),
                'icon'   => 'el el-paper-clip',
                // Leave this as a blank section, no options just some intro text set above.
                'fields' => array()
            );

            return $sections;
        }
    }

    /**
     * Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.
     * */
    if ( ! function_exists( 'change_arguments' ) ) {
        function change_arguments( $args ) {
            //$args['dev_mode'] = true;

            return $args;
        }
    }

    /**
     * Filter hook for filtering the default value of any given field. Very useful in development mode.
     * */
    if ( ! function_exists( 'change_defaults' ) ) {
        function change_defaults( $defaults ) {
            $defaults['str_replace'] = 'Testing filter hook!';

            return $defaults;
        }
    }



