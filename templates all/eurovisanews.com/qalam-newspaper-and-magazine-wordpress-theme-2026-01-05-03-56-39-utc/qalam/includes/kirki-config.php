<?php
/**
* Kirki config
*/



add_action( 'init', 'qalam_kirki_config' );

function qalam_kirki_config() {

    if ( ! class_exists( 'Kirki' ) ) {
        return;
    }
	

	Kirki::add_config( 'qlm', array(
		'capability'    => 'edit_theme_options',
		'option_type'   => 'theme_mod',
	) );

// General
	Kirki::add_section( 'general', array(
		'title'          => esc_attr__( 'Layout', 'qalam' ),
		'priority'       => 1
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'select',
		'settings'    => 'layout',
		'label'       => esc_attr__( 'Layout style', 'qalam' ),
		'section'     => 'general',
		'default'     => 'stretched',
		'priority'    => 10,
		'multiple'    => 1,
		'choices'     => array(
			'stretched' => esc_attr__( 'Stretched', 'qalam' ),
			'boxed' => esc_attr__( 'Boxed', 'qalam' )
		),
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'slider',
		'settings'    => 'layout_width',
		'label'       => esc_attr__( 'Layout width (in px)', 'qalam' ),
		'section'     => 'general',
		'default'     => 980,
		'choices'     => array(
			'min'  => 300,
			'max'  => 6000,
			'step' => 1,
		),
		'transport' => 'auto',
		'output'    => array(
			array(
				'element'  => '#page,.container',
				'property' => 'max-width',
				'units' => 'px'
			),
			array(
				'element'  => '.is-boxed .container',
				'property' => 'max-width',
				'value_pattern' => 'calc($px - 48px)'
			)
		)
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'radio-image',
		'settings'    => 'sb_pos',
		'label'       => esc_attr__( 'Sidebars Layout', 'qalam' ),
		'section'     => 'general',
		'default'     => 'ca',
		'priority'    => 10,
		'multiple'    => 0,
		'choices'     => array(
			'ca' => get_template_directory_uri() . '/images/kirki/ca.png',
			'ac' => get_template_directory_uri() . '/images/kirki/ac.png',
			'cba' => get_template_directory_uri() . '/images/kirki/cba.png',
			'acb' => get_template_directory_uri() . '/images/kirki/acb.png',
			'bca' => get_template_directory_uri() . '/images/kirki/bca.png',
			'abc' => get_template_directory_uri() . '/images/kirki/abc.png',
			'no-sb' => get_template_directory_uri() . '/images/kirki/nosb.png',
		)
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'slider',
		'settings'    => 'sba_ratio',
		'label'       => esc_attr__( 'Sidebar A ratio', 'qalam' ),
		'description' => esc_attr__( 'Set a width ratio (in %) for Sidebar A', 'qalam' ),
		'section'     => 'general',
		'default'     => 33,
		'choices'     => array(
			'min'  => 10,
			'max'  => 90,
			'step' => 1,
		),
		'transport' => 'auto',
		'output'    => array(
			array(
				'element'  => '#primary,#container',
				'media_query' => '@media (min-width: 769px)',
				'property' => 'width',
				'value_pattern' => 'calc(100% - $%)'
			),
			array(
				'element'  => '#sidebar',
				'media_query' => '@media (min-width: 769px)',
				'property' => 'width',
				'units' => '%'
			),
		)
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'slider',
		'settings'    => 'sbb_ratio',
		'label'       => esc_attr__( 'Sidebar B ratio', 'qalam' ),
		'description' => esc_attr__( 'Set a width ratio (in %) for Sidebar B', 'qalam' ),
		'section'     => 'general',
		'default'     => 22,
		'choices'     => array(
			'min'  => 10,
			'max'  => 90,
			'step' => 1,
		),
		'transport' => 'auto',
		'output'    => array(
			array(
				'element'  => '.two-sidebars .site-content:not(.full-width) #content',
				'media_query' => '@media (min-width: 769px)',
				'property' => 'width',
				'value_pattern' => 'calc(100% - $%)'
			),
			array(
				'element'  => '.two-sidebars #sidebar-b',
				'media_query' => '@media (min-width: 769px)',
				'property' => 'width',
				'units' => '%'
			),
		)
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'slider',
		'settings'    => 'gutter_main',
		'label'       => esc_attr__( 'Gutter width (in px)', 'qalam' ),
		'description' => esc_attr__( 'Set a gutter width between main content and sidebars.', 'qalam' ),
		'section'     => 'general',
		'default'     => 32,
		'choices'     => array(
			'min'  => 0,
			'max'  => 200,
			'step' => 1,
		),
		'transport' => 'auto',
		'output'    => array(
			array(
				'element'  => '.main-row,.two-sidebars .primary-row,.widget-area .row',
				'media_query' => '@media (min-width: 769px)',
				'property' => 'margin',
				'value_pattern' => '0 calc(-$px / 2)'
			),
			array(
				'element'  => 'body:not(.layout-no-sb) #primary,#container,#sidebar,.two-sidebars #content,.two-sidebars #sidebar-b,.widget-area .row > .widget,.entry-header.qlm-col',
				'media_query' => '@media (min-width: 769px)',
				'property' => 'padding',
				'value_pattern' => '0 calc($px / 2)'
			),
		)
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'toggle',
		'settings'    => 'wa_before_content',
		'label'       => esc_attr__( 'Widget area before content', 'qalam' ),
		'section'     => 'general',
		'default'     => 0
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'toggle',
		'settings'    => 'wa_after_content',
		'label'       => esc_attr__( 'Widget area after content', 'qalam' ),
		'section'     => 'general',
		'default'     => 0
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'toggle',
		'settings'    => 'sec_wa',
		'label'       => esc_attr__( 'Secondary widget area', 'qalam' ),
		'section'     => 'general',
		'default'     => 0
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'toggle',
		'settings'    => 'footer_wa',
		'label'       => esc_attr__( 'Footer widget area', 'qalam' ),
		'section'     => 'general',
		'default'     => 1
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'toggle',
		'settings'    => 'schema',
		'label'       => esc_attr__( 'Schema Microdata', 'qalam' ),
		'section'     => 'general',
		'default'     => 1
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'toggle',
		'settings'    => 'breadcrumbs_check',
		'label'       => esc_attr__( 'Breadcrumbs', 'qalam' ),
		'section'     => 'general',
		'default'     => 1
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'toggle',
		'settings'    => 'page_titles_check',
		'label'       => esc_attr__( 'Page Titles', 'qalam' ),
		'section'     => 'general',
		'default'     => 1
	) );


// Header
	Kirki::add_panel( 'p_header', array(
		'priority'    => 2,
		'title'       => esc_attr__( 'Header', 'qalam' ),
		'description' => esc_attr__( 'Header area settings', 'qalam' ),
	) );

	// Top Bar
	Kirki::add_section( 'top_bar', array(
		'title'          => esc_attr__( 'Top Bar', 'qalam' ),
		'priority'       => 1,
		'panel'     => 'p_header'
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'toggle',
		'settings'    => 'topbar_check',
		'label'       => esc_attr__( 'Top Bar', 'qalam' ),
		'description' => esc_attr__( 'Enable top utility bar.', 'qalam' ),
		'section'     => 'top_bar',
		'default'     => 1
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'slider',
		'settings'    => 'topbar_height',
		'label'       => esc_attr__( 'Top Bar Height', 'qalam' ),
		'section'     => 'top_bar',
		'default'     => 32,
		'choices'     => array(
			'min'  => 20,
			'max'  => 1000,
			'step' => 1,
		),
		'transport' => 'auto',
		'output'    => [
			[

				'element'  => '.top-bar .flex',
				'property' => 'min-height',
				'units' => 'px'
			],
			[
				'element'  => '.top-bar .account-nav > li > a',
				'property' => 'padding',
				'value_pattern' => 'calc( ( $px - 20px ) / 2 ) .75em'
			],
		]
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'select',
		'settings'    => 'cb_left',
		'label'       => esc_attr__( 'Top bar left section', 'qalam' ),
		'description' => esc_attr__( 'Choose what to show in left side of top bar.', 'qalam' ),
		'section'     => 'top_bar',
		'default'     => 'wp_menu',
		'priority'    => 10,
		'multiple'    => 1,
		'choices'     => array(
			'text' => esc_attr__( 'Custom Text', 'qalam' ),
			'wp_menu' => esc_attr__( 'WordPress Menu', 'qalam' )
		)
	) );

	Kirki::add_field( 'qlm', array(
		'type'     => 'textarea',
		'settings' => 'cb_left_text',
		'label'    => esc_attr__( 'Custom text for left section', 'qalam' ),
		'section'  => 'top_bar',
		'default'  => esc_attr__( 'Optional callout text', 'qalam' ),
		'priority' => 10,
		'active_callback'  => [
			[
				'setting'  => 'cb_left',
				'operator' => '===',
				'value'    => 'text',
			]
		]
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'select',
		'settings'    => 'cb_right',
		'label'       => esc_attr__( 'Top bar right section', 'qalam' ),
		'description' => esc_attr__( 'Choose what to show in right side of top bar.', 'qalam' ),
		'section'     => 'top_bar',
		'default'     => 'utility_nav',
		'priority'    => 10,
		'multiple'    => 1,
		'choices'     => array(
			'utility_nav' => esc_attr__( 'Utility links (Login, cart, etc.)', 'qalam' ),
			'text' => esc_attr__( 'Custom Text', 'qalam' )
		)
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'sortable',
		'settings'    => 'ut_links',
		'label'       => esc_attr__( 'Choose utility links', 'qalam' ),
		'section'     => 'top_bar',
		'default'     => array( 'welcome', 'login', 'cart' ),
		'priority'    => 10,
		'multiple'    => 999,
		'choices'     => array(
			'welcome' => esc_attr__( 'Welcome Username', 'qalam' ),
			'login' => esc_attr__( 'Login / Register', 'qalam' ),
			'cart' => esc_attr__( 'WooCommerce Cart', 'qalam' )
		),
		'active_callback'  => [
			[
				'setting'  => 'cb_right',
				'operator' => '===',
				'value'    => 'utility_nav',
			]
		]
	) );

	Kirki::add_field( 'qlm', array(
		'type'     => 'textarea',
		'settings' => 'cb_right_text',
		'label'    => esc_attr__( 'Custom text for right section', 'qalam' ),
		'section'  => 'top_bar',
		'default'  => esc_attr__( 'Hello World!', 'qalam' ),
		'priority' => 10,
		'active_callback'  => [
			[
				'setting'  => 'cb_right',
				'operator' => '===',
				'value'    => 'text',
			]
		]
	) );

// Main header
	Kirki::add_section( 'header', array(
		'title'          => esc_attr__( 'Main Header', 'qalam' ),
		'priority'       => 1,
		'panel' 		=> 'p_header'
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'radio-image',
		'settings'    => 'header_style',
		'label'       => esc_attr__( 'Main header style', 'qalam' ),
		'description' => esc_attr__( 'Choose a header style for the theme', 'qalam' ),
		'section'     => 'header',
		'default'     => '1',
		'priority'    => 1,
		'multiple'    => 0,
		'choices'     => array(
			'1' => get_template_directory_uri() . '/images/kirki/n1.png',
			'2' => get_template_directory_uri() . '/images/kirki/n2.png',
			'3' => get_template_directory_uri() . '/images/kirki/n3.png',
			'4' => get_template_directory_uri() . '/images/kirki/n4.png',
		)
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'toggle',
		'settings'    => 'sticky_check',
		'label'       => esc_attr__( 'Sticky navigation bar', 'qalam' ),
		'description' => esc_attr__( 'Enable sticky navbar upon scroll. The sticky feature will be applied for device width 768px and above.', 'qalam' ),
		'section'     => 'header',
		'default'     => 0
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'slider',
		'settings'    => 'header_height',
		'label'       => esc_attr__( 'Main header height', 'qalam' ),
		'description' => esc_attr__( 'Provide a height for main header containing the logo. Default: 64px, Minimum: 48px', 'qalam' ),
		'section'     => 'header',
		'default'     => 64,
		'choices'     => [
			'min'  => 48,
			'max'  => 999,
			'step' => 1,
		],
		'transport' => 'auto',
		'output'    => [
			[
				'element'  => '.hst-1 .nav-1 > .container',
				'property' => 'min-height',
				'units' => 'px'
			]
		],
		'active_callback' => [
			[
				'setting'  => 'header_style',
				'operator' => '==',
				'value'    => '1',
			]
		]
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'slider',
		'settings'    => 'header_height_t',
		'label'       => esc_attr__( 'Main header height (Tablet)', 'qalam' ),
		'description' => esc_attr__( 'Provide a height for main header containing the logo. Default: 64px, Minimum: 48px', 'qalam' ),
		'section'     => 'header',
		'default'     => 64,
		'choices'     => [
			'min'  => 48,
			'max'  => 999,
			'step' => 1,
		],
		'transport' => 'auto',
		'output'    => [
			[
				'element'  => '.hst-1 .nav-1 > .container',
				'media_query' => '@media (max-width: 768px)',
				'property' => 'min-height',
				'units' => 'px'
			]
		],
		'active_callback' => [
			[
				'setting'  => 'header_style',
				'operator' => '==',
				'value'    => '1',
			]
		]
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'slider',
		'settings'    => 'header_height_m',
		'label'       => esc_attr__( 'Main header height (Mobile)', 'qalam' ),
		'description' => esc_attr__( 'Provide a height for main header containing the logo. Default: 64px, Minimum: 48px', 'qalam' ),
		'section'     => 'header',
		'default'     => 64,
		'choices'     => [
			'min'  => 48,
			'max'  => 999,
			'step' => 1,
		],
		'transport' => 'auto',
		'output'    => [
			[
				'element'  => '.hst-1 .nav-1 > .container',
				'media_query' => '@media (max-width: 425px)',
				'property' => 'min-height',
				'units' => 'px'
			]
		],
		'active_callback' => [
			[
				'setting'  => 'header_style',
				'operator' => '==',
				'value'    => '1',
			]
		]
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'slider',
		'settings'    => 'header_height_2',
		'label'       => esc_attr__( 'Main header height (Desktop)', 'qalam' ),
		'description' => esc_attr__( 'Provide a height for main header containing the logo. Default: 64px, Minimum: 48px', 'qalam' ),
		'section'     => 'header',
		'default'     => 64,
		'choices'     => [
			'min'  => 48,
			'max'  => 999,
			'step' => 1,
		],
		'transport' => 'auto',
		'output'    => [
			[
				'element'  => '.site-header:not(.hst-1) .nav-1 > .container',
				'property' => 'min-height',
				'units' => 'px'
			]
		],
		'active_callback' => [
			[
				'setting'  => 'header_style',
				'operator' => '!==',
				'value'    => '1',
			]
		]
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'slider',
		'settings'    => 'header_height_t_2',
		'label'       => esc_attr__( 'Main header height (Tablet)', 'qalam' ),
		'description' => esc_attr__( 'Provide a height for main header containing the logo. Default: 64px, Minimum: 48px', 'qalam' ),
		'section'     => 'header',
		'default'     => 64,
		'choices'     => [
			'min'  => 48,
			'max'  => 999,
			'step' => 1,
		],
		'transport' => 'auto',
		'output'    => [
			[
				'element'  => '.site-header:not(.hst-1) .nav-1 > .container',
				'media_query' => '@media (max-width: 768px)',
				'property' => 'min-height',
				'units' => 'px'
			]
		],
		'active_callback' => [
			[
				'setting'  => 'header_style',
				'operator' => '!==',
				'value'    => '1',
			]
		]
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'slider',
		'settings'    => 'header_height_m_2',
		'label'       => esc_attr__( 'Main header height (Mobile)', 'qalam' ),
		'description' => esc_attr__( 'Provide a height for main header containing the logo. Default: 64px, Minimum: 48px', 'qalam' ),
		'section'     => 'header',
		'default'     => 64,
		'choices'     => [
			'min'  => 48,
			'max'  => 999,
			'step' => 1,
		],
		'transport' => 'auto',
		'output'    => [
			[
				'element'  => '.site-header:not(.hst-1) .nav-1 > .container',
				'media_query' => '@media (max-width: 425px)',
				'property' => 'min-height',
				'units' => 'px'
			]
		],
		'active_callback' => [
			[
				'setting'  => 'header_style',
				'operator' => '!==',
				'value'    => '1',
			]
		]
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'slider',
		'settings'    => 'nav_height',
		'label'       => esc_attr__( 'Navigation Bar Height', 'qalam' ),
		'description' => esc_attr__( 'Provide a height for navigation bar containing the main menu.', 'qalam' ),
		'section'     => 'header',
		'default'     => 48,
		'choices'     => [
			'min'  => 48,
			'max'  => 999,
			'step' => 1,
		],
		'transport' => 'auto',
		'output'    => [
			[
				'element'  => '.site-header:not(.hst-1) .nav-2 .flex',
//'media_query' => '@media (max-width: 425px)',
				'property' => 'min-height',
				'units' => 'px'
			],
			[
				'element'  => '.main-navigation > ul > li > a',
				'property' => 'padding',
				'value_pattern' => 'calc(($px - 20px) / 2) 1rem'
			],
		],
		'active_callback' => [
			[
				'setting'  => 'header_style',
				'operator' => '!==',
				'value'    => '1',
			]
		]
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'checkbox',
		'settings'    => 'search_panel',
		'label'       => esc_attr__( 'Show search panel in main header', 'qalam' ),
		'section'     => 'header',
		'default'     => true,
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'select',
		'settings'    => 'search_type',
		'label'       => esc_attr__( 'Search form type', 'qalam' ),
		'description' => esc_attr__( 'Choose type of search form display', 'qalam' ),
		'section'     => 'header',
		'default'     => 'inline',
		'priority'    => 10,
		'multiple'    => 1,
		'choices'     => array(
			'inline' => esc_attr__( 'Inline', 'qalam' ),
			'overlay' => esc_attr__( 'Full Screen Overlay', 'qalam' )
		),
		'active_callback'  => [
			[
				'setting'  => 'search_panel',
				'operator' => '===',
				'value'    => true,
			]
		]
	) );


	Kirki::add_field( 'qlm', array(
		'type'        => 'select',
		'settings'    => 'cart_pos',
		'label'       => esc_attr__( 'WooCommerce cart link', 'qalam' ),
		'description' => esc_attr__( 'Choose where to place the WooCommerce cart link.', 'qalam' ),
		'section'     => 'header',
		'default'     => 'none',
		'priority'    => 10,
		'multiple'    => 1,
		'choices'     => array(
			'none' => esc_attr__( 'None', 'qalam' ),
			'top' => esc_attr__( 'Top bar', 'qalam' ),
			'main' => esc_attr__( 'Main header', 'qalam' )
		)
	) );

// Social
	Kirki::add_section( 'social_links', array(
		'title'          => esc_attr__( 'Social Links', 'qalam' ),
		'priority'       => 3,
		'panel'     	=> 'p_header'
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'checkbox',
		'settings'    => 'social_check',
		'label'       => esc_attr__( 'Enable social links', 'qalam' ),
		'description' => esc_attr__( 'Check to enable social links in header', 'qalam' ),
		'section'     => 'social_links',
		'default'     => true
	) );

	Kirki::add_field( 'qlm', array(
		'type'     => 'text',
		'settings' => 'twitter',
		'label'    => esc_attr__( 'X (Twitter) URL', 'qalam' ),
		'section'  	=> 'social_links',
		'default'  => '',
		'priority' => 10,
		'active_callback'  => [
			[
				'setting'  => 'social_check',
				'operator' => '===',
				'value'    => true,
			]
		]
	) );

	Kirki::add_field( 'qlm', array(
		'type'     => 'text',
		'settings' => 'facebook',
		'label'    => esc_attr__( 'Facebook URL', 'qalam' ),
		'section'  	=> 'social_links',
		'default'  => '',
		'priority' => 10,
		'active_callback'  => [
			[
				'setting'  => 'social_check',
				'operator' => '===',
				'value'    => true,
			]
		]
	) );

	Kirki::add_field( 'qlm', array(
		'type'     => 'text',
		'settings' => 'linkedin',
		'label'    => esc_attr__( 'LinkedIn URL', 'qalam' ),
		'section'  	=> 'social_links',
		'default'  => '',
		'priority' => 10,
		'active_callback'  => [
			[
				'setting'  => 'social_check',
				'operator' => '===',
				'value'    => true,
			]
		]
	) );

	Kirki::add_field( 'qlm', array(
		'type'     => 'text',
		'settings' => 'instagram',
		'label'    => esc_attr__( 'Instagram URL', 'qalam' ),
		'section'  	=> 'social_links',
		'default'  => '',
		'priority' => 10,
		'active_callback'  => [
			[
				'setting'  => 'social_check',
				'operator' => '===',
				'value'    => true,
			]
		]
	) );

	Kirki::add_field( 'qlm', array(
		'type'     => 'text',
		'settings' => 'threads',
		'label'    => esc_attr__( 'Threads URL', 'qalam' ),
		'section'  	=> 'social_links',
		'default'  => '',
		'priority' => 10,
		'active_callback'  => [
			[
				'setting'  => 'social_check',
				'operator' => '===',
				'value'    => true,
			]
		]
	) );

	Kirki::add_field( 'qlm', array(
		'type'     => 'text',
		'settings' => 'youtube',
		'label'    => esc_attr__( 'Youtube URL', 'qalam' ),
		'section'  	=> 'social_links',
		'default'  => '',
		'priority' => 10,
		'active_callback'  => [
			[
				'setting'  => 'social_check',
				'operator' => '===',
				'value'    => true,
			]
		]
	) );


// Archive
	Kirki::add_section( 'archive', array(
		'title'          => esc_attr__( 'Archives', 'qalam' ),
		'priority'       => 3
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'select',
		'settings'    => 'archive_style',
		'label'       => esc_attr__( 'Global Archive Template', 'qalam' ),
		'section'     => 'archive',
		'default'     => 'list',
		'priority'    => 1,
		'multiple'    => 1,
		'choices'     => array(
			'list' => esc_attr__( 'List', 'qalam' ),
			'grid' => esc_attr__( 'Grid', 'qalam' ),
			'mix' => esc_attr__( 'Mix', 'qalam' ),
			'card' => esc_attr__( 'Card', 'qalam' ),
			'full' => esc_attr__( 'Full post', 'qalam' )
		)
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'select',
		'settings'    => 'grid_col',
		'label'       => esc_attr__( 'Number of grid columns', 'qalam' ),
		'section'     => 'archive',
		'default'     => '2',
		'priority'    => 1,
		'multiple'    => 1,
		'choices'     => array(
			'2' => esc_attr__( '2', 'qalam' ),
			'3' => esc_attr__( '3', 'qalam' ),
			'4' => esc_attr__( '4', 'qalam' )
		),
		'active_callback'  => [
			[
				'setting'  => 'archive_style',
				'operator' => 'in',
				'value'    => ['grid', 'card']
			]
		],
		'transport' => 'auto',
		'output'    => array(
			array(
				'element'  => '.grid-row > article',
				'media_query' => '@media (min-width: 769px)',
				'property' => 'flex-basis',
				'value_pattern' => 'calc(100%/$ - GutterArchivepx/2)',
				'pattern_replace' => array(
					'GutterArchive' => 'gutter_archive'
				)
			)
		)
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'slider',
		'settings'    => 'gutter_archive',
		'label'       => esc_attr__( 'Gutter width (in px)', 'qalam' ),
		'description' => esc_attr__( 'Set a gutter width between post modules in archives', 'qalam' ),
		'section'     => 'archive',
		'default'     => 24,
		'choices'     => array(
			'min'  => 0,
			'max'  => 200,
			'step' => 1,
		),
		'active_callback'  => [
			[
				'setting'  => 'archive_style',
				'operator' => '!=',
				'value'    => 'full'
			]
		],
		'transport' => 'auto',
		'output'    => array(
			array(
				'element'  => '.grid-row,.hero-section,.qlm-list',
				'media_query' => '@media (min-width: 769px)',
				'property' => 'margin-left',
				'value_pattern' => 'calc(-$px / 2)'
			),
			array(
				'element'  => '.grid-row,.hero-section,.qlm-list',
				'media_query' => '@media (min-width: 769px)',
				'property' => 'margin-right',
				'value_pattern' => 'calc(-$px / 2)'
			),
			array(
				'element'  => '.qlm-list .post-img,.qlm-list .entry-content,.hero-section .qlm-col,.grid-row > article',
				'media_query' => '@media (min-width: 769px)',
				'property' => 'padding-left',
				'value_pattern' => 'calc($px / 2)'
			),
			array(
				'element'  => '.qlm-list .post-img,.qlm-list .entry-content,.hero-section .qlm-col,.grid-row > article',
				'media_query' => '@media (min-width: 769px)',
				'property' => 'padding-right',
				'value_pattern' => 'calc($px / 2)'
			),

			array(
				'element'  => '.hero-section + .qlm-list:before',
				'media_query' => '@media (min-width: 769px)',
				'property' => 'margin-left',
				'value_pattern' => 'calc($px / 2)'
			),

			array(
				'element'  => '.hero-section + .qlm-list:before',
				'media_query' => '@media (min-width: 769px)',
				'property' => 'margin-right',
				'value_pattern' => 'calc($px / 2)'
			),

			array(
				'element'  => '.qlm-list .entry-content:after',
				'media_query' => '@media (min-width: 769px)',
				'property' => 'left',
				'value_pattern' => 'calc($px / 2)'
			),

			array(
				'element'  => '.qlm-list .entry-content:after',
				'media_query' => '@media (min-width: 769px)',
				'property' => 'right',
				'value_pattern' => 'calc($px / 2)'
			),

			array(
				'element'  => '.qlm-list .post-img,.qlm-list .entry-content',
				'media_query' => '@media (max-width: 768px)',
				'property' => 'margin-bottom',
				'value_pattern' => 'calc($px / 2)'
			),
			array(
				'element'  => '.qlm-list > article:after',
//'media_query' => '@media (min-width: 769px)',
				'property' => 'margin-left',
				'value_pattern' => 'calc(listImgRatio% + $px/2)',
				'pattern_replace' => array(
					'listImgRatio'    => 'list_img_ratio'
				)
			),
		)
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'slider',
		'settings'    => 'list_img_ratio',
		'label'       => esc_attr__( 'Image container width (in %)', 'qalam' ),
		'description' => esc_attr__( 'Set an image container width % for list style archives.', 'qalam' ),
		'section'     => 'archive',
		'default'     => 40,
		'choices'     => array(
			'min'  => 10,
			'max'  => 90,
			'step' => 1,
		),
		'active_callback'  => [
			[
				'setting'  => 'archive_style',
				'operator' => 'in',
				'value'    => ['list', 'mix']
			]
		],
		'transport' => 'auto',
		'output'    => array(
			array(
				'element'  => '.qlm-list .post-img',
				'media_query' => '@media (min-width: 769px)',
				'property' => 'flex',
				'value_pattern' => '0 0 $%'
			),
			array(
				'element'  => '.qlm-list > article:after',
				'media_query' => '@media (min-width: 769px)',
				'property' => 'margin-left',
				'value_pattern' => 'calc($% + archiveGutterpx/2)',
				'pattern_replace' => array(
					'archiveGutter'    => 'gutter_archive'
				)
			)
		)
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'toggle',
		'settings'    => 'show_excerpt',
		'label'       => esc_attr__( 'Excerpt in archives', 'qalam' ),
		'section'     => 'archive',
		'default'     => 1
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'slider',
		'settings'    => 'excerpt_length',
		'label'       => esc_attr__( 'Excerpt length (in words)', 'qalam' ),
		'description' => esc_attr__( 'Provide an excerpt length in words. Default: 20', 'qalam' ),
		'section'     => 'archive',
		'default'     => 20,
		'choices'     => array(
			'min'  => 1,
			'max'  => 999,
			'step' => 1,
		),
		'active_callback'  => [
			[
				'setting'  => 'show_excerpt',
				'operator' => '==',
				'value'    => 1
			]
		]
	) );


	Kirki::add_field( 'qlm', array(
		'type'        => 'toggle',
		'settings'    => 'archive_fw',
		'label'       => esc_attr__( 'Full width on archives', 'qalam' ),
		'description' => esc_attr__( 'Hides all sidebars on archives.', 'qalam' ),
		'section'     => 'archive',
		'default'     => 0
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'toggle',
		'settings'    => 'show_meta',
		'label'       => esc_attr__( 'Post meta in archives', 'qalam' ),
		'section'     => 'archive',
		'default'     => 1
	) );

	Kirki::add_field( 'qlm', array(
		'type'     => 'text',
		'settings' => 'archive_meta_format',
		'label'    => esc_attr__( 'Post Meta Format', 'qalam' ),
		'section'  	=> 'archive',
		'default'  => esc_attr__( '%1$s', 'qalam' ),
		'description' => esc_attr__( 'Use %1$s for date, %2$s for Author and %3$s for comment count respectively. E.g. Posted on %1$s by %2$s with %3$s comments.', 'qalam' ),
		'priority' => 10,
		'active_callback'  => [
			[
				'setting'  => 'show_meta',
				'operator' => '==',
				'value'    => 1
			]
		]
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'toggle',
		'settings'    => 'show_embed',
		'label'       => esc_attr__( 'Show Video Embeds', 'qalam' ),
		'description' => esc_attr__( 'If enabled, posts with video post format and a video content in it will show video embed in archives.', 'qalam'),
		'section'     => 'archive',
		'default'     => 0
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'toggle',
		'settings'    => 'show_sharing',
		'label'       => esc_attr__( 'Social sharing in archives', 'qalam' ),
		'section'     => 'archive',
		'default'     => '1'
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'sortable',
		'settings'    => 'archive_social',
		'label'       => esc_attr__( 'Social sharing links in archives', 'qalam' ),
		'description' => esc_attr__( 'Deselect all for hiding the links', 'qalam' ),
		'section'     => 'archive',
		'default'     => array( 'twitter', 'facebook-f', 'linkedin-in' ),
		'priority'    => 10,
		'multiple'    => 999,
		'choices'     => array(
			'twitter' => esc_attr__( 'X (Twitter)', 'qalam' ),
			'facebook-f' => esc_attr__( 'Facebook', 'qalam' ),
			'linkedin-in' => esc_attr__( 'LinkedIn', 'qalam' ),
			'pinterest' => esc_attr__( 'Pinterest', 'qalam' ),
			'vkontakte' => esc_attr__( 'VKOntakte', 'qalam' ),
			'line' => esc_attr__( 'LINE', 'qalam' ),
			'reddit' => esc_attr__( 'Reddit', 'qalam' ),
			'digg' => esc_attr__( 'Digg', 'qalam' ),
			'tumblr' => esc_attr__( 'Tumblr', 'qalam' ),
			'stumbleupon' => esc_attr__( 'StumbleUpon', 'qalam' ),
			'yahoo' => esc_attr__( 'Yahoo', 'qalam' ),
			'getpocket' => esc_attr__( 'GetPocket', 'qalam' ),
			'skype' => esc_attr__( 'Skype', 'qalam' ),
			'telegram' => esc_attr__( 'Telegram', 'qalam' ),
			'xing' => esc_attr__( 'Xing', 'qalam' ),
			'renren' => esc_attr__( 'RenRen', 'qalam' ),
			'whatsapp' => esc_attr__( 'WhatsApp', 'qalam' ),
			'threads' => esc_attr__( 'Threads', 'qalam' ),
			'email' => esc_attr__( 'Email', 'qalam' )
		),
		'active_callback'  => [
			[
				'setting'  => 'show_sharing',
				'operator' => '==',
				'value'    => 1
			]
		],
	) );


// Single
	Kirki::add_section( 'single', array(
		'title'          => esc_attr__( 'Single Posts', 'qalam' ),
		'priority'       => 4
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'radio-image',
		'settings'    => 'sb_pos_sng',
		'label'       => esc_attr__( 'Single post layout', 'qalam' ),
		'section'     => 'single',
		'default'     => 'ca',
		'priority'    => 10,
		'multiple'    => 0,
		'choices'     => array(
			'ca' => get_template_directory_uri() . '/images/kirki/ca.png',
			'ac' => get_template_directory_uri() . '/images/kirki/ac.png',
			'cba' => get_template_directory_uri() . '/images/kirki/cba.png',
			'acb' => get_template_directory_uri() . '/images/kirki/acb.png',
			'bca' => get_template_directory_uri() . '/images/kirki/bca.png',
			'abc' => get_template_directory_uri() . '/images/kirki/abc.png',
			'no-sb' => get_template_directory_uri() . '/images/kirki/nosb.png',
		)
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'slider',
		'settings'    => 'sng_layout_width',
		'label'       => esc_attr__( 'Global Layout width (in px)', 'qalam' ),
		'section'     => 'single',
		'default'     => 980,
		'choices'     => array(
			'min'  => 300,
			'max'  => 5000,
			'step' => 1,
		),
		'transport' => 'auto',
		'output'    => array(
			array(
				'element'  => '.single #main .container',
				'property' => 'max-width',
				'units' => 'px'
			),
			array(
				'element'  => '.is-boxed.single #main .container',
				'property' => 'max-width',
				'value_pattern' => 'calc($px - 48px)'
			)
		)
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'slider',
		'settings'    => 'sng_template_layout_width',
		'label'       => esc_attr__( 'Local Layout width (in px)', 'qalam' ),
		'description' => esc_attr__( 'This width applies to posts using the full width post template.', 'qalam' ),
		'section'     => 'single',
		'default'     => 760,
		'choices'     => array(
			'min'  => 300,
			'max'  => 5000,
			'step' => 1,
		),
		'transport' => 'auto',
		'output'    => array(
			array(
				'element'  => '.single.post-template-single-full-width #main .container',
				'property' => 'max-width',
				'units' => 'px'
			),
			array(
				'element'  => '.is-boxed.single.post-template-single-full-width #main .container',
				'property' => 'max-width',
				'value_pattern' => 'calc($px - 48px)'
			)
		)
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'select',
		'settings'    => 'sng_header',
		'label'       => esc_attr__( 'Title header style', 'qalam' ),
		'section'     => 'single',
		'default'     => 'inline',
		'priority'    => 10,
		'multiple'    => 1,
		'choices'     => array(
			'inline' => esc_attr__( 'Inline', 'qalam' ),
			'full' => esc_attr__( 'Full Width', 'qalam' )
		)
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'toggle',
		'settings'    => 'show_meta_single',
		'label'       => esc_attr__( 'Show post meta', 'qalam' ),
		'section'     => 'single',
		'default'     => 1
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'toggle',
		'settings'    => 'show_feat',
		'label'       => esc_attr__( 'Show featured image', 'qalam' ),
		'section'     => 'single',
		'default'     => '1'
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'toggle',
		'settings'    => 'show_author',
		'label'       => esc_attr__( 'Show author bio', 'qalam' ),
		'section'     => 'single',
		'default'     => '1'
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'toggle',
		'settings'    => 'posts_nav',
		'label'       => esc_attr__( 'Show posts navigation', 'qalam' ),
		'section'     => 'single',
		'default'     => '1'
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'toggle',
		'settings'    => 'show_related',
		'label'       => esc_attr__( 'Show related posts', 'qalam' ),
		'section'     => 'single',
		'default'     => '1'
	) );

	Kirki::add_field( 'qlm', array(
		'type'     => 'text',
		'settings' => 'related_title',
		'label'    => esc_attr__( 'Related posts heading', 'qalam' ),
		'section'  	=> 'single',
		'default'  => esc_attr__( 'Related Stories', 'qalam' ),
		'priority' => 10,
		'active_callback'  => [
			[
				'setting'  => 'show_related',
				'operator' => '==',
				'value'    => 1
			]
		],
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'slider',
		'settings'    => 'related_num',
		'label'       => esc_attr__( 'Number of related posts', 'qalam' ),
		'section'     => 'single',
		'default'     => 3,
		'choices'     => array(
			'min'  => 1,
			'max'  => 999,
			'step' => 1,
		),
		'active_callback'  => [
			[
				'setting'  => 'show_related',
				'operator' => '==',
				'value'    => 1
			]
		],
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'slider',
		'settings'    => 'related_col',
		'label'       => esc_attr__( 'Columns per row', 'qalam' ),
		'section'     => 'single',
		'default'     => 3,
		'choices'     => array(
			'min'  => 1,
			'max'  => 8,
			'step' => 1,
		),
		'active_callback'  => [
			[
				'setting'  => 'show_related',
				'operator' => '==',
				'value'    => 1
			]
		],
		'transport' => 'auto',
		'output'    => array(
			array(
				'element'  => '.related-posts > article',
				'media_query' => '@media (min-width: 769px)',
				'property' => 'width',
				'value_pattern' => 'calc(100% / $)'
			)
		)
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'slider',
		'settings'    => 'gutter_related',
		'label'       => esc_attr__( 'Gutter width (in px)', 'qalam' ),
		'description' => esc_attr__( 'Set a gutter width between post items in related posts', 'qalam' ),
		'section'     => 'single',
		'default'     => 20,
		'choices'     => array(
			'min'  => 0,
			'max'  => 100,
			'step' => 1,
		),
		'active_callback'  => [
			[
				'setting'  => 'show_related',
				'operator' => '==',
				'value'    => 1
			]
		],
		'transport' => 'auto',
		'output'    => array(
			array(
				'element'  => '.related-posts',
				'property' => 'margin-left',
				'value_pattern' => 'calc(-$px / 2)'
			),
			array(
				'element'  => '.related-posts',
				'property' => 'margin-right',
				'value_pattern' => 'calc(-$px / 2)'
			),

			array(
				'element'  => '.related-posts > article',
				'property' => 'padding',
				'value_pattern' => '0 calc($px / 2)'
			)
		)
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'sortable',
		'settings'    => 'single_social',
		'label'       => esc_attr__( 'Social sharing links', 'qalam' ),
		'section'     => 'single',
		'default'     => array( 'twitter', 'facebook-f', 'linkedin-in' ),
		'priority'    => 10,
		'multiple'    => 999,
		'choices'     => array(
			'twitter' => esc_attr__( 'X (Twitter)', 'qalam' ),
			'facebook-f' => esc_attr__( 'Facebook', 'qalam' ),
			'linkedin-in' => esc_attr__( 'LinkedIn', 'qalam' ),
			'pinterest' => esc_attr__( 'Pinterest', 'qalam' ),
			'vkontakte' => esc_attr__( 'VKOntakte', 'qalam' ),
			'line' => esc_attr__( 'LINE', 'qalam' ),
			'reddit' => esc_attr__( 'Reddit', 'qalam' ),
			'digg' => esc_attr__( 'Digg', 'qalam' ),
			'tumblr' => esc_attr__( 'Tumblr', 'qalam' ),
			'stumbleupon' => esc_attr__( 'StumbleUpon', 'qalam' ),
			'yahoo' => esc_attr__( 'Yahoo', 'qalam' ),
			'getpocket' => esc_attr__( 'GetPocket', 'qalam' ),
			'skype' => esc_attr__( 'Skype', 'qalam' ),
			'telegram' => esc_attr__( 'Telegram', 'qalam' ),
			'xing' => esc_attr__( 'Xing', 'qalam' ),
			'renren' => esc_attr__( 'RenRen', 'qalam' ),
			'threads' => esc_attr__( 'Threads', 'qalam' ),
			'whatsapp' => esc_attr__( 'WhatsApp', 'qalam' ),
			'email' => esc_attr__( 'Email', 'qalam' )
		)
	) );
// End Single

// Footer
	Kirki::add_section( 'footer', array(
		'title'          => esc_attr__( 'Footer', 'qalam' ),
		'priority'       => 5
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'slider',
		'settings'    => 'sec_cols',
		'label'       => esc_attr__( 'Secondary widget columns', 'qalam' ),
		'description' => esc_attr__( 'Number of columns in secondary widget area', 'qalam' ),
		'section'     => 'footer',
		'default'     => 5,
		'choices'     => array(
			'min'  => 1,
			'max'  => 8,
			'step' => 1,
		)
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'slider',
		'settings'    => 'footer_cols',
		'label'       => esc_attr__( 'Footer widget columns', 'qalam' ),
		'description' => esc_attr__( 'Number of columns in footer widget area', 'qalam' ),
		'section'     => 'footer',
		'default'     => 1,
		'choices'     => array(
			'min'  => 1,
			'max'  => 8,
			'step' => 1,
		)
	) );
// end Footer

// General
	Kirki::add_section( 'typography', array(
		'title'          => esc_attr__( 'Typography', 'qalam' ),
		'priority'       => 7
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'toggle',
		'settings'    => 'default_font',
		'label'       => esc_attr__( 'Use Theme\'s Default Font', 'qalam' ),
		'description' => esc_attr__( 'The theme uses Open Sans as default font.', 'qalam' ),
		'section'     => 'typography',
		'default'     => 1
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'typography',
		'settings'    => 'body_font',
		'label'       => esc_attr__( 'Body', 'qalam' ),
		'section'     => 'typography',
		'default'     => [
			'font-family'    => '',
			'font-weight'	 => '',
			'font-size'      => '',
			'line-height'    => '',
			'letter-spacing' => '',
			'color'          => '',
			'text-transform' => ''
		],
		'priority'    => 10,
		'transport'   => 'auto',
		'output'      => [
			[
				'element' => 'body',
			],
		]
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'typography',
		'settings'    => 'heading_font',
		'label'       => esc_attr__( 'General Headings', 'qalam' ),
		'section'     => 'typography',
		'default'     => [
			'font-family'    => '',
			'font-weight'	 => '',
			'letter-spacing' => '',
			'color'          => '',
			'text-transform' => ''
		],
		'priority'    => 10,
		'transport'   => 'auto',
		'output'      => [
			[
				'element' => 'h1,h2,h3,h4,h5,h6,.site-title',
			],
		]
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'typography',
		'settings'    => 'site_title_fnt',
		'label'       => esc_attr__( 'Site Title', 'qalam' ),
		'section'     => 'typography',
		'default'     => [
			'font-family'    => '',
			'font-weight'	 => '',
			'font-size'      => '',
			'line-height'    => '',
			'letter-spacing' => '',
			'color'          => '',
			'text-transform' => ''
		],
		'priority'    => 10,
		'transport'   => 'auto',
		'output'      => [
			[
				'element' => '.site-title',
			],
		]
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'typography',
		'settings'    => 'nav_a_fnt',
		'label'       => esc_attr__( 'Navigation Links', 'qalam' ),
		'section'     => 'typography',
		'default'     => [
			'font-family'    => '',
			'font-weight'	 => '',
			'font-size'      => '',
			'line-height'    => '',
			'letter-spacing' => '',
			'text-transform' => ''
		],
		'priority'    => 10,
		'transport'   => 'auto',
		'output'      => [
			[
				'element' => '.main-navigation > ul > li > a',
			],
		]
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'typography',
		'settings'    => 'site_tagline_fnt',
		'label'       => esc_attr__( 'Site Tagline', 'qalam' ),
		'section'     => 'typography',
		'default'     => [
			'font-family'    => '',
			'font-weight'	 => '',
			'font-size'      => '',
			'line-height'    => '',
			'letter-spacing' => '',
			'color'          => '',
			'text-transform' => ''
		],
		'priority'    => 10,
		'transport'   => 'auto',
		'output'      => [
			[
				'element' => '.site-description',
			],
		]
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'typography',
		'settings'    => 'sba_fnt',
		'label'       => esc_attr__( 'Widget Titles', 'qalam' ),
		'section'     => 'typography',
		'default'     => [
			'font-family'    => '',
			'variant'        => '',
			'font-size'      => '',
			'line-height'    => '',
			'letter-spacing' => '',
			'color'          => '',
			'text-transform' => ''
		],
		'priority'    => 10,
		'transport'   => 'auto',
		'output'      => [
			[
				'element' => '.widget-title, .comments-title, .comment-reply-title, .related-posts-title, .related.products h2:not(.woocommerce-loop-product__title)',
			],
		]
	) );

// Image Sizes
	Kirki::add_section( 'img_sizes', array(
		'title'          => esc_attr__( 'Image Sizes', 'qalam' ),
		'priority'       => 5
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'toggle',
		'settings'    => 'bfi_thumb',
		'label'       => esc_attr__( 'Enable BFI Thumb support', 'qalam' ),
		'description' => esc_attr__( 'If BFI Thumb script is enabled, images can be resized on-the-fly without regenerating thumbnails.', 'qalam' ),
		'section'     => 'img_sizes',
		'default'     => '0'
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'custom',
		'settings'    => 'grid_label',
		'label'       => esc_attr__( 'Archive images', 'qalam' ),
		'description' => esc_attr__( 'This setting applies on all archive images except full post archive style.', 'qalam' ),
		'section'     => 'img_sizes',
		'default'     => '',
		'priority'    => 10,
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'slider',
		'settings'    => 'arch_w',
		'label'       => esc_attr__( 'Width', 'qalam' ),
		'section'     => 'img_sizes',
		'default'     => 600,
		'choices'     => array(
			'min'  => 1,
			'max'  => 2000,
			'step' => 1,
		)
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'slider',
		'settings'    => 'arch_h',
		'label'       => esc_attr__( 'Height', 'qalam' ),
		'description' => esc_attr__( 'Use 0 for auto height.','qalam'),
		'section'     => 'img_sizes',
		'default'     => 0,
		'choices'     => array(
			'min'  => '',
			'max'  => 2000,
			'step' => 1,
		)
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'custom',
		'settings'    => 'single_label',
		'label'       => esc_attr__( 'Single post images', 'qalam' ),
		'description' => esc_attr__( 'This setting applies to single posts and full post style archives.', 'qalam' ),
		'section'     => 'img_sizes',
		'default'     => '',
		'priority'    => 10,
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'slider',
		'settings'    => 'single_w',
		'label'       => esc_attr__( 'Width', 'qalam' ),
		'section'     => 'img_sizes',
		'default'     => 800,
		'choices'     => array(
			'min'  => 1,
			'max'  => 2000,
			'step' => 1,
		)
	) );

	Kirki::add_field( 'qlm', array(
		'type'        => 'slider',
		'settings'    => 'single_h',
		'label'       => esc_attr__( 'Height', 'qalam' ),
		'section'     => 'img_sizes',
		'default'     => 0,
		'choices'     => array(
			'min'  => '',
			'max'  => 2000,
			'step' => 1,
		)
	) );
// end image sizes


	// Header
	Kirki::add_panel( 'p_colors', array(
		'priority'    => 2,
		'title'       => esc_attr__( 'Colors', 'qalam' ),
		'description' => esc_attr__( 'Color Settings', 'qalam' ),
	) );

	// Top Bar
	Kirki::add_section( 'colors_top_bar', array(
		'title'          => esc_attr__( 'Top Bar', 'qalam' ),
		'priority'       => 1,
		'panel'     => 'p_colors'
	) );

	Kirki::add_field( 'qlm', [
		'type'        => 'color',
		'settings'    => 'topbar_bg',
		'label'       => esc_attr__( 'Top Bar Background', 'qalam' ),
		'section'     => 'colors_top_bar',
		'default'     => '',
		'choices'     => [
			'alpha' => true,
		],
		'transport' => 'auto',
		'output'    => [
			[
				'element'  => '.top-bar',
				'property' => 'background-color'
			]
		]
	] );

	Kirki::add_field( 'qlm', [
		'type'        => 'color',
		'settings'    => 'topbar_clr',
		'label'       => esc_attr__( 'Top Bar Text Color', 'qalam' ),
		'section'     => 'colors_top_bar',
		'default'     => '',
		'choices'     => [
			'alpha' => true,
		],
		'transport' => 'auto',
		'output'    => [
			[
				'element'  => '.top-bar',
				'property' => 'color'
			]
		]
	] );

	Kirki::add_field( 'qlm', [
		'type'        => 'color',
		'settings'    => 'topbar_lnks_clr',
		'label'       => esc_attr__( 'Top Bar Links Color', 'qalam' ),
		'section'     => 'colors_top_bar',
		'default'     => '',
		'choices'     => [
			'alpha' => true,
		],
		'transport' => 'auto',
		'output'    => [
			[
				'element'  => '.top-bar .custom-text a, .top-bar .account-nav > li > a',
				'property' => 'color'
			]
		]
	] );

	Kirki::add_field( 'qlm', [
		'type'        => 'color',
		'settings'    => 'topbar_lnks_hvr_clr_',
		'label'       => esc_attr__( 'Top Bar Links Hover Color', 'qalam' ),
		'section'     => 'colors_top_bar',
		'default'     => '',
		'choices'     => [
			'alpha' => true,
		],
		'transport' => 'auto',
		'output'    => [
			[
				'element'  => '.top-bar .custom-text a:hover, .top-bar .account-nav > li:hover > a',
				'property' => 'color'
			]
		]
	] );

	// Top Bar
	Kirki::add_section( 'colors_header', array(
		'title'          => esc_attr__( 'Header', 'qalam' ),
		'priority'       => 1,
		'panel'     => 'p_colors'
	) );

	Kirki::add_field( 'qlm', [
		'type'        => 'color',
		'settings'    => 'header_bg',
		'label'       => esc_attr__( 'Header Background', 'qalam' ),
		'section'     => 'colors_header',
		'default'     => '',
		'choices'     => [
			'alpha' => true,
		],
		'transport' => 'auto',
		'output'    => [
			[
				'element'  => '.site-header',
				'property' => 'background-color'
			]
		]
	] );

	Kirki::add_field( 'qlm', [
		'type'        => 'color',
		'settings'    => 'site_title_clr',
		'label'       => esc_attr__( 'Site Title Color', 'qalam' ),
		'section'     => 'colors_header',
		'default'     => '',
		'choices'     => [
			'alpha' => true,
		],
		'transport' => 'auto',
		'output'    => [
			[
				'element'  => '.site-title > a',
				'property' => 'color'
			]
		]
	] );

	Kirki::add_field( 'qlm', [
		'type'        => 'color',
		'settings'    => 'site_title_hvr_clr',
		'label'       => esc_attr__( 'Site Title Hover Color', 'qalam' ),
		'section'     => 'colors_header',
		'default'     => '',
		'choices'     => [
			'alpha' => true,
		],
		'transport' => 'auto',
		'output'    => [
			[
				'element'  => '.site-title > a:hover',
				'property' => 'color'
			]
		]
	] );

	Kirki::add_field( 'qlm', [
		'type'        => 'color',
		'settings'    => 'site_tagline_clr',
		'label'       => esc_attr__( 'Site Tagline Color', 'qalam' ),
		'section'     => 'colors_header',
		'default'     => '',
		'choices'     => [
			'alpha' => true,
		],
		'transport' => 'auto',
		'output'    => [
			[
				'element'  => '.site-description',
				'property' => 'color'
			]
		]
	] );

	Kirki::add_field( 'qlm', [
		'type'        => 'color',
		'settings'    => 'header_lnks_clr',
		'label'       => esc_attr__( 'Header Links Color', 'qalam' ),
		'section'     => 'colors_header',
		'default'     => '',
		'choices'     => [
			'alpha' => true,
		],
		'transport' => 'auto',
		'output'    => [
			[
				'element'  => '.qlm-sharing-inline a, .site-header .cart-status .cart-contents, .utility-links .search-trigger',
				'property' => 'color'
			],
			[
				'element'  => '.toggle-icon, .toggle-icon:before, .toggle-icon:after',
				'property' => 'background-color'
			]
		]
	] );

	Kirki::add_field( 'qlm', [
		'type'        => 'color',
		'settings'    => 'header_lnks_hvr_clr',
		'label'       => esc_attr__( 'Header Links Hover Color', 'qalam' ),
		'section'     => 'colors_header',
		'default'     => '',
		'choices'     => [
			'alpha' => true,
		],
		'transport' => 'auto',
		'output'    => [
			[
				'element'  => '.qlm-sharing-inline a:hover, .site-header .cart-status:hover > .cart-contents, .utility-links .search-trigger:hover',
				'property' => 'color'
			],
			[
				'element'  => '.menu-trigger:hover .toggle-icon, .menu-trigger:hover .toggle-icon:before, .menu-trigger:hover .toggle-icon:after',
				'property' => 'background-color'
			]
		]
	] );

	Kirki::add_field( 'qlm', [
		'type'        => 'color',
		'settings'    => 'nav_bg',
		'label'       => esc_attr__( 'Navigation Background', 'qalam' ),
		'section'     => 'colors_header',
		'default'     => '',
		'choices'     => [
			'alpha' => true,
		],
		'transport' => 'auto',
		'output'    => [
			[
				'element'  => '.hst-1 .nav-1, .site-header:not(.hst-1) .nav-2',
				'property' => 'background-color'
			]
		]
	] );

	Kirki::add_field( 'qlm', [
		'type'        => 'color',
		'settings'    => 'nav_a_clr',
		'label'       => esc_attr__( 'Nav Links Color', 'qalam' ),
		'section'     => 'colors_header',
		'default'     => '',
		'choices'     => [
			'alpha' => true,
		],
		'transport' => 'auto',
		'output'    => [
			[
				'element'  => '.main-navigation > ul > li > a',
				'property' => 'color'
			]
		]
	] );

	Kirki::add_field( 'qlm', [
		'type'        => 'color',
		'settings'    => 'nav_a_clr_hvr',
		'label'       => esc_attr__( 'Nav Links Color (Hover)', 'qalam' ),
		'section'     => 'colors_header',
		'default'     => '',
		'choices'     => [
			'alpha' => true,
		],
		'transport' => 'auto',
		'output'    => [
			[
				'element'  => '.main-navigation > ul > li:hover > a',
				'property' => 'color'
			]
		]
	] );

	Kirki::add_field( 'qlm', [
		'type'        => 'color',
		'settings'    => 'nav_a_bg_hvr',
		'label'       => esc_attr__( 'Nav Links Background (Hover)', 'qalam' ),
		'section'     => 'colors_header',
		'default'     => '',
		'choices'     => [
			'alpha' => true,
		],
		'transport' => 'auto',
		'output'    => [
			[
				'element'  => '.main-navigation > ul > li:hover > a',
				'property' => 'background-color'
			]
		]
	] );

	Kirki::add_field( 'qlm', [
		'type'        => 'color',
		'settings'    => 'nav_a_curnt_clr',
		'label'       => esc_attr__( 'Current Link Color', 'qalam' ),
		'section'     => 'colors_header',
		'default'     => '',
		'choices'     => [
			'alpha' => true,
		],
		'transport' => 'auto',
		'output'    => [
			[
				'element'  => '.main-navigation .current-menu-item > a, .main-navigation .current-menu-parent > a, .main-navigation .current-menu-item:hover > a, .main-navigation .current-menu-parent:hover > a',
				'property' => 'color'
			]
		]
	] );

	Kirki::add_field( 'qlm', [
		'type'        => 'color',
		'settings'    => 'nav_a_curntbg_clr',
		'label'       => esc_attr__( 'Current Link Background Color', 'qalam' ),
		'section'     => 'colors_header',
		'default'     => '',
		'choices'     => [
			'alpha' => true,
		],
		'transport' => 'auto',
		'output'    => [
			[
				'element'  => '.main-navigation .current-menu-item > a, .main-navigation .current-menu-parent > a, .main-navigation .current-menu-item:hover > a, .main-navigation .current-menu-parent:hover > a',
				'property' => 'background-color'
			]
		]
	] );

	Kirki::add_field( 'qlm', [
		'type'        => 'color',
		'settings'    => 'submenu_bg',
		'label'       => esc_attr__( 'Submenu Background Color', 'qalam' ),
		'section'     => 'colors_header',
		'default'     => '',
		'choices'     => [
			'alpha' => true,
		],
		'transport' => 'auto',
		'output'    => [
			[
				'element'  => '.main-navigation ul ul',
				'property' => 'background-color'
			]
		]
	] );

	Kirki::add_field( 'qlm', [
		'type'        => 'color',
		'settings'    => 'submenu_a_clr',
		'label'       => esc_attr__( 'Submenu Link Color', 'qalam' ),
		'section'     => 'colors_header',
		'default'     => '',
		'choices'     => [
			'alpha' => true,
		],
		'transport' => 'auto',
		'output'    => [
			[
				'element'  => '.main-navigation ul ul a',
				'property' => 'color'
			]
		]
	] );

	Kirki::add_field( 'qlm', [
		'type'        => 'color',
		'settings'    => 'submenu_a_clr_hvr',
		'label'       => esc_attr__( 'Submenu Link Color (Hover)', 'qalam' ),
		'section'     => 'colors_header',
		'default'     => '',
		'choices'     => [
			'alpha' => true,
		],
		'transport' => 'auto',
		'output'    => [
			[
				'element'  => '.main-navigation ul ul li:hover a',
				'property' => 'color'
			]
		]
	] );

	Kirki::add_field( 'qlm', [
		'type'        => 'color',
		'settings'    => 'submenu_a_bg_hvr',
		'label'       => esc_attr__( 'Submenu Link Background (Hover)', 'qalam' ),
		'section'     => 'colors_header',
		'default'     => '',
		'choices'     => [
			'alpha' => true,
		],
		'transport' => 'auto',
		'output'    => [
			[
				'element'  => '.main-navigation ul ul li:hover a',
				'property' => 'background-color'
			]
		]
	] );

	// Secondary Widget Area
	Kirki::add_section( 'colors_sec', array(
		'title'          => esc_attr__( 'Secondary Widget Area', 'qalam' ),
		'priority'       => 1,
		'panel'     => 'p_colors'
	) );

	Kirki::add_field( 'qlm', [
		'type'        => 'color',
		'settings'    => 'sec_bg',
		'label'       => esc_attr__( 'Background', 'qalam' ),
		'section'     => 'colors_sec',
		'default'     => '',
		'choices'     => [
			'alpha' => true,
		],
		'transport' => 'auto',
		'output'    => [
			[
				'element'  => '#secondary',
				'property' => 'background-color'
			]
		]
	] );

	Kirki::add_field( 'qlm', [
		'type'        => 'color',
		'settings'    => 'sec_clr',
		'label'       => esc_attr__( 'Text Color', 'qalam' ),
		'section'     => 'colors_sec',
		'default'     => '',
		'choices'     => [
			'alpha' => true,
		],
		'transport' => 'auto',
		'output'    => [
			[
				'element'  => '#secondary',
				'property' => 'color'
			]
		]
	] );

	Kirki::add_field( 'qlm', [
		'type'        => 'color',
		'settings'    => 'links_clr',
		'label'       => esc_attr__( 'Links Color', 'qalam' ),
		'section'     => 'colors_sec',
		'default'     => '',
		'choices'     => [
			'alpha' => true,
		],
		'transport' => 'auto',
		'output'    => [
			[
				'element'  => '#secondary a',
				'property' => 'color'
			]
		]
	] );

	Kirki::add_field( 'qlm', [
		'type'        => 'color',
		'settings'    => 'links_hvr_clr',
		'label'       => esc_attr__( 'Links Hover Color', 'qalam' ),
		'section'     => 'colors_sec',
		'default'     => '',
		'choices'     => [
			'alpha' => true,
		],
		'transport' => 'auto',
		'output'    => [
			[
				'element'  => '#secondary a:hover',
				'property' => 'color'
			]
		]
	] );

	Kirki::add_field( 'qlm', [
		'type'        => 'color',
		'settings'    => 'widget_title_clr',
		'label'       => esc_attr__( 'Widget Title Color', 'qalam' ),
		'section'     => 'colors_sec',
		'default'     => '',
		'choices'     => [
			'alpha' => true,
		],
		'transport' => 'auto',
		'output'    => [
			[
				'element'  => '#secondary .widget-title',
				'property' => 'color'
			]
		]
	] );

	// Footer Widget Area
	Kirki::add_section( 'colors_footer', array(
		'title'          => esc_attr__( 'Footer Widget Area', 'qalam' ),
		'priority'       => 1,
		'panel'     => 'p_colors'
	) );

	Kirki::add_field( 'qlm', [
		'type'        => 'color',
		'settings'    => 'footer_bg',
		'label'       => esc_attr__( 'Background', 'qalam' ),
		'section'     => 'colors_footer',
		'default'     => '',
		'choices'     => [
			'alpha' => true,
		],
		'transport' => 'auto',
		'output'    => [
			[
				'element'  => '#footer',
				'property' => 'background-color'
			]
		]
	] );

	Kirki::add_field( 'qlm', [
		'type'        => 'color',
		'settings'    => 'footer_clr',
		'label'       => esc_attr__( 'Text Color', 'qalam' ),
		'section'     => 'colors_footer',
		'default'     => '',
		'choices'     => [
			'alpha' => true,
		],
		'transport' => 'auto',
		'output'    => [
			[
				'element'  => '#footer',
				'property' => 'color'
			]
		]
	] );

	Kirki::add_field( 'qlm', [
		'type'        => 'color',
		'settings'    => 'footer_links_clr',
		'label'       => esc_attr__( 'Links Color', 'qalam' ),
		'section'     => 'colors_footer',
		'default'     => '',
		'choices'     => [
			'alpha' => true,
		],
		'transport' => 'auto',
		'output'    => [
			[
				'element'  => '#footer a',
				'property' => 'color'
			]
		]
	] );

	Kirki::add_field( 'qlm', [
		'type'        => 'color',
		'settings'    => 'footer_links_hvr_clr',
		'label'       => esc_attr__( 'Links Hover Color', 'qalam' ),
		'section'     => 'colors_footer',
		'default'     => '',
		'choices'     => [
			'alpha' => true,
		],
		'transport' => 'auto',
		'output'    => [
			[
				'element'  => '#footer a:hover',
				'property' => 'color'
			]
		]
	] );

	Kirki::add_field( 'qlm', [
		'type'        => 'color',
		'settings'    => 'footer_widget_title_clr',
		'label'       => esc_attr__( 'Widget Title Color', 'qalam' ),
		'section'     => 'colors_footer',
		'default'     => '',
		'choices'     => [
			'alpha' => true,
		],
		'transport' => 'auto',
		'output'    => [
			[
				'element'  => '#footer .widget-title',
				'property' => 'color'
			]
		]
	] );

	}
