<?php
/*
 * Add a meta box of pagenation
 */

$blog_pagenation_type_options = array(
  'type1' => array( 'value' => 'type1', 'label' => __( 'Page numbers', 'tcd-w' ) ),
  'type2' => array( 'value' => 'type2', 'label' => __( 'Read more button', 'tcd-w' ) ),
  'type3' => array( 'value' => 'type3', 'label' => __( 'Use theme options settings', 'tcd-w' ) )
);

$blog_fields = array(
  array(
  	'id' => 'pagenation_type',
	  'title' => __( 'Pagenation settings', 'tcd-w' ),
  	'type' => 'select',
  	'description' => __( 'Please select the pagenation type.', 'tcd-w' ),
    'options' => $blog_pagenation_type_options,
		'before_field' => '<dl class="ml_custom_fields">',
		'after_field' => '</dd></dl>',
		'before_title' => '<dt class="label">',
		'after_title' => '</dt><dd class="content">',
    'default' => 'type3'
  )
);

$blog_args = array(
	'id' => 'blog_meta_box',
	'title' => __( 'Post settings', 'tcd-w' ),
	'screen' => array( 'post' ),
	'context' => 'normal',
	'fields' => $blog_fields
); 

$blog_meta_box = new TCD_Meta_Box( $blog_args );
