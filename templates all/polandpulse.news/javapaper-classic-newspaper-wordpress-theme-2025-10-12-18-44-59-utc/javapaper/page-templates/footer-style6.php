<?php
/**
 * Footer6 for Javapaper
 */
global $redux_demo; 
?>
</div>
<!-- #main .wrapper -->
<div class="wrapper-footer one <?php if(isset($redux_demo['jp_footerscheme']) ){ ?><?php echo esc_html($redux_demo['jp_footerscheme']); ?><?php } ?>">
<?php if (is_active_sidebar('javapaper-footer1') || is_active_sidebar('javapaper-footer2') || is_active_sidebar('javapaper-footer3')  || is_active_sidebar('javapaper-footer4') || is_active_sidebar('javapaper-footer5') ) :?>
 <div class="footer-wrapinside">
    <div class="footer-topinside">
      <div class="footer7-topinside">
        <footer id="colophon" role="contentinfo">
          <div class="j_maintitle2">
            <div class="footer7-subwrapper">
              <div class="footer7-subtitle2">
                <h2>
                  <?php bloginfo( 'name' ); ?>
                </h2>
                <span>
                <?php bloginfo('description');?>
                </span> </div>
            </div>
          </div>
        <div class="footer-line">
            <div class="col-md-6">
              <?php dynamic_sidebar( 'javapaper-footer1' ); ?>
            </div>
            <div class="col-md-6">
              <?php dynamic_sidebar( 'javapaper-footer2' ); ?>
            </div>
          </div>
        <div class="footer-line">
            <div class="col-md-3">
              <?php dynamic_sidebar( 'javapaper-footer3' ); ?>
            </div>
            <div class="col-md-6">
              <?php dynamic_sidebar( 'javapaper-footer4' ); ?>
            </div>
            <div class="col-md-3">
              <?php dynamic_sidebar( 'javapaper-footer5' ); ?>
            </div>
          </div>
          <div id="back-top"> <a href="#top"><span><i class="fa fa-angle-up fa-2x"></i></span></a> </div>
        </footer>
        <!-- #colophon -->
      </div>
    </div>
  </div>
 <?php endif; ?>   
  <div class="footer-bottom-wrapper">
    <div class="footer-topinside">
        <div class="col-md-8 widget-area">
      <div class="footer-nav">		
        <nav id="site-footernavigation" class="javapaper-nav"> <a class="assistive-text" href="#main" title="<?php esc_attr_e( 'Skip to content', 'javapaper' ); ?>">
          <?php _e( 'Skip to content', 'javapaper' ); ?>
          </a>
          <?php wp_nav_menu( array( 'theme_location' => 'footer_menu', 'menu_id' => 'menu-footer', 'menu_class' => 'nav-menu', 'container' => 'ul','depth' => 1 ) ); ?>
        </nav>
        <!-- #site-navigation -->
      </div>
	  </div>
        <div class="col-md-4 widget-area">  
      <div class="site-wordpress"> 			
<?php global $redux_demo; if ( isset($redux_demo['footer_text']) ){ ?>
<?php if( isset($redux_demo['footer_text']) ){ ?>
<?php echo esc_html($redux_demo['footer_text']); ?>
<?php } ?>
    <?php } ?>	  
	  </div>
	  </div>
      <!-- .site-info -->
    </div>
  </div>
</div>
<div class="clear"></div>
<?php wp_footer(); ?>
</body></html>