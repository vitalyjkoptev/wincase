<?php global $redux_demo;
if( isset($redux_demo['header_layout']) ){
  get_header($redux_demo['header_layout']);;	
} else{
    get_header();
}
?>
<?php if ( isset($redux_demo['jp_category1']) && is_category($redux_demo['jp_category1'])) { 
		get_template_part($redux_demo['jp_cattemplate1']); 
	} elseif (isset($redux_demo['jp_category2']) && is_category($redux_demo['jp_category2'])) {
		get_template_part($redux_demo['jp_cattemplate2']); 		
	} elseif (isset($redux_demo['jp_category3']) && is_category($redux_demo['jp_category3'])) {
		get_template_part($redux_demo['jp_cattemplate3']); 
	} elseif (isset($redux_demo['jp_category4']) && is_category($redux_demo['jp_category4'])) {
		get_template_part($redux_demo['jp_cattemplate4']); 			
	} elseif (isset($redux_demo['jp_category5']) && is_category($redux_demo['jp_category5'])) {
		get_template_part($redux_demo['jp_cattemplate5']); 			
	} elseif (isset($redux_demo['jp_category6']) && is_category($redux_demo['jp_category6'])) {
		get_template_part($redux_demo['jp_cattemplate6']); 	
	} elseif (isset($redux_demo['jp_category7']) && is_category($redux_demo['jp_category7'])) {
		get_template_part($redux_demo['jp_cattemplate7']); 			
	} else { 
	  get_template_part( 'page-templates/category_default' );
	} ?>
<?php 
if( isset($redux_demo['footer_layout']) ){
  get_template_part($redux_demo['footer_layout']);
} else{
    get_footer();
}
?>