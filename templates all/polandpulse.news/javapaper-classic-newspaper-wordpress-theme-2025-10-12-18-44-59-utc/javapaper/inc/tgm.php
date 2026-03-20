<?php
/**
 * This file represents an example of the code that themes would use to register
 * the required plugins.
 *
 * It is expected that theme authors would copy and paste this code into their
 * functions.php file, and amend to suit.
 *
 * @see http://tgmpluginactivation.com/configuration/ for detailed documentation.
 *
 * @package    TGM-Plugin-Activation
 * @subpackage Example
 * @version    2.6.1 for parent theme javapaper for publication on ThemeForest
 * @author     Thomas Griffin, Gary Jones, Juliette Reinders Folmer
 * @copyright  Copyright (c) 2011, Thomas Griffin
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       https://github.com/TGMPA/TGM-Plugin-Activation
 */

/**
 * Include the TGM_Plugin_Activation class.
 *
 * Depending on your implementation, you may want to change the include call:
 *
 * Parent Theme:
 * require_once get_template_directory() . '/path/to/class-tgm-plugin-activation.php';
 *
 * Child Theme:
 * require_once get_stylesheet_directory() . '/path/to/class-tgm-plugin-activation.php';
 */
require_once get_template_directory() . '/inc/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'javapaper_register_required_plugins' );

/**
 * Register the required plugins for this theme.
 */
function javapaper_register_required_plugins() {
	/*
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(
		array(
		'name' => 'Elementor', 
		'slug' => 'elementor',
		'required' => true,
		'force_activation' 		=> false,
		'force_deactivation' 	=> true, 	
		),	
		array(
		'name' => 'Redux Framework', 
		'slug' => 'redux-framework',
		'required' => true,
		'force_activation' 		=> false,
		'force_deactivation' 	=> true, 	
		),		
		array(
		'name' => 'One Click Demo Import', 
		'slug' => 'one-click-demo-import',
		'required' => true,
		'force_activation' 		=> false,
		'force_deactivation' 	=> true, 	
		),
		array(
		'name' => 'javapaper Custom Function', 
		'slug' => 'javapaper-customfunction',
        'source' => 'https://wpbromo.com/files/incjavapaper5/javapaper-customfunction.zip', 
		'required' => true,
		'force_activation' 		=> false,
		'force_deactivation' 	=> true, 	
		),		
		array(
		'name' => 'Javapaper Custom Post Type', 
		'slug' => 'javapaper-cpt',
        'source' => 'https://wpbromo.com/files/incjavapaper5/javapaper-cpt.zip', 
		'required' => true,
		'force_activation' 		=> false,
		'force_deactivation' 	=> true, 	
		),
		array(
		'name' => 'Javapaper For Elementor', 
		'slug' => 'javapaper-for-elementor',
		'source' => 'https://wpbromo.com/files/incjavapaper5/javapaper-for-elementor.zip', 
		'required' => true,
		'force_activation' 		=> false,
		'force_deactivation' 	=> true, 	
		),	
		array(
		'name' => 'Mega Main Menu', 
		'slug' => 'mega_main_menu',
		'source' => 'https://wpbromo.com/files/incjavapaper5/mega-main-menu.zip', 
		'required' => true,
		'force_activation' 		=> false,
		'force_deactivation' 	=> true, 	
		),		
		array(
		'name' => 'Javapaper Widget', 
		'slug' => 'javapaper-widget',
		'source' => 'https://wpbromo.com/files/incjavapaper5/javapaper-widget.zip', 
		'required' => true,
		'force_activation' 		=> false,
		'force_deactivation' 	=> true, 	
		),	
		array(
		'name' => 'Contact Form 7', 
		'slug' => 'contact-form-7',
		'required' => true,
		'force_activation' 		=> false,
		'force_deactivation' 	=> true, 	
		),
		
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
		'id'           => 'javapaper',                 // Unique ID for hashing notices for multiple instances of TGMPA.
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