<?php global $redux_demo;
if( isset($redux_demo['header_layout']) ){
  get_header($redux_demo['header_layout']);
} else{
    get_header();
}
?>
<div class="single2-wrapper">
  <div class="error-wrapper">
    <div id="content" role="main">
      <div class="error-image">
        <?php global $redux_demo; if ( isset($redux_demo['jp_404image']['url']) ){ ?>
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>"> <img alt="<?php echo get_bloginfo('name'); ?>" src="
<?php if( isset($redux_demo['jp_404image']) ){ ?>
<?php echo esc_url($redux_demo['jp_404image']['url']); ?>
<?php } ?>"> </a>
        <?php } 
else if ( isset($redux_demo['jp_404image']) ){ ?>
        <h1> <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
          <?php if( isset($redux_demo['opt_header_text']) ){ ?>
          <?php echo esc_html($redux_demo['opt_header_text']); ?>
          <?php } ?>
          </a> </h1>
        <?php } 
			  
else { ?>
        <img src="<?php echo esc_url( get_template_directory_uri() . '/images/404.png' ); ?>">
        <?php } 
			  ?>
      </div>
      <header class="entry-header">
        <h1 class="entry-title">
          <?php if( isset($redux_demo['jp_404text1']) ){ ?>
          <?php echo esc_html($redux_demo['jp_404text1']); ?>
          <?php } ?>
        </h1>
        <?php if( isset($redux_demo['jp_404text2']) ){ ?>
        <?php echo esc_html($redux_demo['jp_404text2']); ?>
        <?php } ?>
      </header>
      <div class="error-cat">
        <div class="category1-topinside">
          <div class="d-flex align-items-center">
            <div class="col-md-6 cattitle">
              <?php  wp_dropdown_categories('show_option_none=Select category'); ?>
              <script type="text/javascript"><!--
				var dropdown = document.getElementById("cat");
				function onCatChange() {
				if ( dropdown.options[dropdown.selectedIndex].value > 0 ) {
					
				location.href = "<?php  echo esc_url( home_url( '/' ) ); ?>/?cat="+dropdown.options[dropdown.selectedIndex].value;
				}
				}
				dropdown.onchange = onCatChange;
//-->
				</script>
            </div>
            <div class="col-md-6">
              <select name="page-dropdown"
					onchange='document.location.href=this.options[this.selectedIndex].value;'>
                <option value="">
                <?php esc_html_e("Select page", "javapaper"); ?>
                </option>
                <?php $pages = get_pages();
					foreach ( $pages as $pagg ) {
					$option = '<option value="' . esc_attr(get_page_link( $pagg->ID )) . '">';
					$option .= $pagg->post_title;
					$option .= '</option>';
					echo ent2ncr($option);
					}
				?>
              </select>
            </div>
          </div>
        </div>
      </div>
      <p>
        <?php _e( 'Kindly search your topic below or browse the recent posts.', 'javapaper' ); ?>
      </p>
      <?php get_search_form(); ?>
    </div>
  </div>
</div>
<?php 
if( isset($redux_demo['footer_layout']) ){
  get_template_part($redux_demo['footer_layout']);
} else{
    get_footer();
}
?>
