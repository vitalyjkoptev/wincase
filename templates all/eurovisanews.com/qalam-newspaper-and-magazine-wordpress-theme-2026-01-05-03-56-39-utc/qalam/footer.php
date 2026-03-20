<?php
/**
 * The template for displaying footer.
 *
 * @package Qalam
 * @since 1.0.0
 * @version 2.4.0
 */
?>
            </div><!-- .row -->
        </div><!-- #main .container -->
    </div><!-- #main -->
    <?php
    /**
	 * Hook: qalam_after_main
	 *
	 * @hooked qlm_widget_area_after_content - 10
	 * @hooked qlm_secondary_widget_area - 15
	 * @hooked qlm_footer_widget_area - 20
	 * @hooked qlm_fixed_widget_area_left - 25
	 * @hooked qlm_fixed_widget_area_right - 30
	 */
    do_action( 'qalam_after_main' );
    ?>
</div> <!-- #page -->

<?php wp_footer(); ?>
</body>
</html>