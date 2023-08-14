<?php
/**
 * Add a meta box of custom CSS
 */
$custom_css_fields = array(
	array( 
		'id' => 'custom_css',
		'title' => '',
		'type' => 'textarea',
		'description' => __( 'You don\'t need to enter &lt;style&gt; tag.', 'tcd-w' ),
	)
);
$custom_css_args = array(
	'id' => 'custom_css_meta_box',
	'title' => __( 'Custom CSS for this post', 'tcd-w' ),
	'screen' => array( 'post', 'page', 'news', 'staff', 'style' ),
	'context' => 'normal',
	'fields' => $custom_css_fields
); 
$custom_css_meta_box = new TCD_Meta_Box( $custom_css_args );

function add_css_to_post_insert_custom_css() {

	if ( is_page() || is_single() ) {
		while ( have_posts() ) {
	  	the_post();
      echo get_post_meta( get_the_ID(), 'custom_css', true );
		}
		rewind_posts();
  }
}
add_action( 'tcd_head', 'add_css_to_post_insert_custom_css', 11 );
