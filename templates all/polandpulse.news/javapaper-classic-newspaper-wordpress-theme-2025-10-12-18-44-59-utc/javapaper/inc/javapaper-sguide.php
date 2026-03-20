<?php
/** 
 * Adding options page under Appearance menu 
 */
function javapaper_one_options_theme_menu() {  
  
add_theme_page( 'Javapaper Theme', 'Javapaper start guide', 'edit_theme_options', 'javapaper_after_instalation', 'javapaper_one_options_display');  
  
} 
add_action( 'admin_menu', 'javapaper_one_options_theme_menu' ); 

/** 
 * Adding customizer link under Appearance menu
 */ 


/** 
 * Show Javapaper Options 
 */ 
function javapaper_one_options_display() { 
?> 

    <!-- Create a header in the default WordPress 'wrap' container --> 
    <div class="wrap"style='background:#FFFFFF;border:1px solid #e1e1e1; padding:20px;min-width:750px; max-width:960px;'> 
  

<div style="clear:both;">
<h1><?php _e( 'JAVAPAPER START GUIDE', 'javapaper' ); ?></h1> 
<h3>
<?php _e( 'Quick Steps to Build Your Web', 'javapaper' ); ?>
</h3> 

<h2>
<?php _e( 'I. Make sure all the required plugins are installed and activated.', 'javapaper' ); ?>
</h2>
<ol>
<li><b>
<?php _e( 'Step 1 :', 'javapaper' ); ?>
</b> 
<?php _e( 'After installing the theme, you will see the notification that appears at the top.', 'javapaper' ); ?>
</li>
<br>
<img src="<?php echo esc_url( get_template_directory_uri() . '/images/intallplugin.jpg' ); ?>">
<br .../><br .../>
<li><b>
<?php _e( 'Step 2 :', 'javapaper' ); ?>
</b> 
<?php _e( 'Click Begin Install Plugins.', 'javapaper' ); ?>
</li>
<br .../>
<?php _e( 'Wait until the process is complete. Activate the plugins after the installation process is complete.', 'javapaper' ); ?>
</ol>
<br .../>
<div style="clear:both;">
<h2>
<?php _e( 'II. Import Demo Data', 'javapaper' ); ?>
</h2>
<ol>
<li><b>
<?php _e( 'Step 1 :', 'javapaper' ); ?>
</b> 
<?php _e( 'Go to Dashboard> Appearance > Import Demo Data.', 'javapaper' ); ?>
</li>
<li><b>
<?php _e( 'Step 2 :', 'javapaper' ); ?>
</b> 
<?php _e( 'On One Click Demo Imports page, choose the layout and click Import button.', 'javapaper' ); ?>
</li>
<li><b>
<?php _e( 'Step 3 :', 'javapaper' ); ?>
</b>
<?php _e( ' Wait until the process is complete.', 'javapaper' ); ?>
</li>
</ol>
<br .../>
<h2>
<?php _e( 'III. Setup Menu', 'javapaper' ); ?>
</h2> 
<ol>
<li><b>
<?php _e( 'Step 1 :', 'javapaper' ); ?>
</b>
<?php _e( ' Go to Dashboard> Appearance > Menus.', 'javapaper' ); ?>
</li>
<li><b>
<?php _e( 'Step 2 :', 'javapaper' ); ?>
</b> 
<?php _e( 'On Menus page, setting up the menu placement.', 'javapaper' ); ?>
</li>
<li><b>
<?php _e( 'Step 3 :', 'javapaper' ); ?>
</b>  
<?php _e( 'Go to Dashboard> Mega Main Menu. Click Specific options tab. Scrolling down till you see Download backup file with current settings.', 'javapaper' ); ?>
</li>
<li><b>
<?php _e( 'Step 4 :', 'javapaper' ); ?>
</b>  
<?php _e( 'Search the file name Mega-main-menu.txt on your theme on folder name inc>installation. ', 'javapaper' ); ?>
</li>
</ol>
<br .../>
<h2>
<?php _e( 'IV. Theme Option and Customization', 'javapaper' ); ?>
</h2>
<?php _e( 'You can rearrange the appearance of the web layout through the Japaper theme option. In this option, you can make changes to the header, footer, sidebar, typography, and
other elements', 'javapaper' ); ?>
. 


</div><!-- /.wrap --> 
<?php 
} // end javapaper_one_options_display 
