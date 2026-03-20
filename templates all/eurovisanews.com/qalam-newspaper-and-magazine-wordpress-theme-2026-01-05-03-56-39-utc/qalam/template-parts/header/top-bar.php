<?php

?>
<div class="top-bar">
	<div class="container clearfix">
    	<div class="flex w-100 flex-center">
        	<div class="flex w-50 h-100 flex-center">
	            <?php
				if ( 'text' == get_theme_mod( 'cb_left', 'wp_menu' ) ) {
					echo '<div class="custom-text">' . do_shortcode( get_theme_mod( 'cb_left_text', esc_html__( 'Optional callout text', 'qalam' ) ) ) . '</div>';
				}
				else {
					wp_nav_menu( array( 'theme_location' => 'top', 'menu_class' => 'top-menu', 'container' => false, 'fallback_cb' => false ) );
				}
				?>
            </div>
            <div class="flex w-50 h-100 flex-center justify-end">
	            <?php
				if ( 'utility_nav' == get_theme_mod( 'cb_right', 'utility_nav' ) ) {
					echo '<ul class="account-nav">';
					foreach( get_theme_mod( 'ut_links', array( 'welcome', 'login', 'cart' ) ) as $links ) {

						switch( $links ) {
							case 'welcome' :
								if ( ! is_user_logged_in() ) {

									printf( esc_html__( '%s%sWelcome, Guest%s', 'qalam' ),
										'<li class="welcome">',
										'<i class="fas fa-user-tie"></i>',
										'</li>'
									);

								} else {

									$current_user = wp_get_current_user();
									if ( ! ( $current_user instanceof WP_User ) )
										return;
									printf( esc_html__( '%s%sWelcome, %s%s', 'qalam' ),
										'<li class="welcome">',
										'<i class="fas fa-user"></i>',
										$current_user->user_login,
										'</li>'
									);
								}

							break;

							case 'login' :

								if ( ! is_user_logged_in() ) {
									printf( '%s<a id="qlm-login" href="%s" title="%s">%s%s</a><div class="login-form">%s</div>%s',
											'<li class="login">',
											esc_url( wp_login_url( get_permalink() ) ),
											esc_attr__( 'Sign in to your account', 'qalam' ),
											'<i class="fas fa-sign-in-alt"></i>',
											esc_html__( 'Sign in', 'qalam' ),
											wp_login_form( array( 'echo' => false ) ),
											'</li>'
									);
									printf( '%s<a href="%s" title="%s">%s%s</a>%s',
											'<li class="register">',
											class_exists( 'woocommerce' ) ? esc_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) ) : esc_url( wp_registration_url() ),
											esc_attr__( 'Register for a new account', 'qalam' ),
											'<i class="fas fa-user-plus"></i>',
											esc_html__( 'Register', 'qalam' ),
											'</li>'
									);
								}
								else {
									printf( '%s<a href="%s" title="%s">%s%s</a>%s',
											'<li class="logout">',
											esc_url( wp_logout_url( get_permalink() ) ),
											esc_attr__( 'Sign out from your account', 'qalam' ),
											'<i class="fas fa-sign-out-alt"></i>',
											esc_html__( 'Sign out', 'qalam' ),
											'</li>'
									);
								}

							break;

							case 'cart' :

								if ( class_exists( 'woocommerce' ) && 'top' == get_theme_mod( 'cart_pos', 'none' ) ) {
									printf( '<li class="cart-status"><a class="cart-contents" href="%s" title="%s"><i class="fas fa-shopping-basket"></i>%s</a><div class="cart-submenu">
<div class="widget_shopping_cart_content"></div><!-- /.widget_shopping_cart_content -->
</div><!-- /.cart-submenu --></li>',
										wc_get_cart_url(),
										esc_attr__( 'View your shopping cart', 'qalam' ),
										'<sup class="amount">' . WC()->cart->get_cart_contents_count() . '</sup>'
									);
			                    }
		                    break;
		                } // end switch
		            } // end foreach
					echo '</ul>';
				}
				elseif ( 'text' == get_theme_mod( 'cb_right', 'utility_nav' ) ) {
					echo '<div class="custom-text">' . do_shortcode( get_theme_mod( 'cb_right_text', esc_html__( 'Hello World!', 'qalam' ) ) ) . '</div>';
				}
				?>
            </div>
        </div><!-- /.flex -->
    </div><!-- /.container -->
</div><!-- /.top-bar -->