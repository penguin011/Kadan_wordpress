<?php
/*
 * Add a meta box of page header
 */
$ph_fields = array(
	array(
		'id' => 'ph_img',
		'title' => __( 'Background image', 'tcd-w' ),
    'description' => __( 'Recommended image size. Width: 1450px, Height: 1100px', 'tcd-w' ),
		'type' => 'image',
		'before_field' => '<dl class="ml_custom_fields">',
		'after_field' => '</dd></dl>',
		'before_title' => '<dt class="label">',
		'after_title' => '</dt><dd class="content">'
	),
	array(
		'id' => 'ph_title',
		'title' => __( 'Title', 'tcd-w' ),
		'type' => 'textarea',
		'before_field' => '<dl class="ml_custom_fields">',
		'after_field' => '</dd></dl>',
		'before_title' => '<dt class="label">',
		'after_title' => '</dt><dd class="content">'
	),
	array(
		'id' => 'ph_font_size',
		'title' => __( 'Font size of title', 'tcd-w' ),
		'type' => 'number',
    'unit' => 'px',
    'default' => 40,
		'before_field' => '<dl class="ml_custom_fields">',
		'after_field' => '</dd></dl>',
		'before_title' => '<dt class="label">',
		'after_title' => '</dt><dd class="content">'
	),
	array(
		'id' => 'ph_color',
		'title' => __( 'Font color of title', 'tcd-w' ),
		'type' => 'color',
    'default' => '#ffffff',
		'before_field' => '<dl class="ml_custom_fields">',
		'after_field' => '</dd></dl>',
		'before_title' => '<dt class="label">',
		'after_title' => '</dt><dd class="content">'
	),
	array(
		'id' => 'ph_writing_type',
		'title' => __( 'Writing mode of title', 'tcd-w' ),
		'type' => 'radio',
    'default' => 'type1',
		'before_field' => '<dl class="ml_custom_fields">',
		'after_field' => '</dd></dl>',
		'before_title' => '<dt class="label">',
		'after_title' => '</dt><dd class="content">',
    'options' => array(
      'type1' => array( 'value' => 'type1', 'label' => __( 'Vertical writing', 'tcd-w' ) ),
      'type2' => array( 'value' => 'type2', 'label' => __( 'Horizontal writing', 'tcd-w' ) )
    )
	),
);
$ph_args = array(
	'id' => 'ph_meta_box',
	'title' => __( 'Page header settings', 'tcd-w' ),
	'screen' => array( 'page' ),
	'context' => 'normal',
	'fields' => $ph_fields
);
$ph_meta_box = new TCD_Meta_Box( $ph_args );
