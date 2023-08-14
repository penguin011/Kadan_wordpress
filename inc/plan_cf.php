<?php
/*
 * Add meta boxes of recommended plan, plan slider and plan information
 */

// Recommended plan
$recommended_plan_fields = array(
  array(
		'id' => 'recommended_plan',
		'title' => '',
		'type' => 'checkbox',
		'options' => array( 
			array(
				'value' => 'on',
				'label' => __( 'Show this post for recommended plan.', 'tcd-w' )
			)
		),
  )
);

$recommended_plan_args = array(
	'id' => 'recommended_plan_meta_box',
	'title' => __( 'Recommended plan', 'tcd-w' ),
	'context' => 'side',
	'fields' => $recommended_plan_fields,
	'screen' => array( 'plan' ),
	'description' => __( 'Check if you want to show this post for recommended plan.', 'tcd-w' )
); 

$recommended_plan_meta_box = new TCD_Meta_Box( $recommended_plan_args );

// Plan slider
$plan_slider_sub_box_fields = array();
$plan_slider_sub_box_labels = array();

for ( $i = 1; $i <= 6; $i++ ) {
  
  $plan_slider_sub_box_fields[] = array(
    array(
      'id' => 'plan_slider_img' . $i,
			'title' => __( 'Image', 'tcd-w' ),
      'description' => __( 'Recommended image size. Width: 1000px, Height: 680px', 'tcd-w' ),
			'type' => 'image',
			'before_title' => '<h4 class="theme_option_headline2">',
			'after_title' => '</h4>'
		),
    array(
      'id' => 'plan_slider_cap' . $i,
			'title' => __( 'Caption', 'tcd-w' ),
			'type' => 'text',
			'before_title' => '<h4 class="theme_option_headline2">',
			'after_title' => '</h4>'
		),
	);
	$cf_keys[] = 'plan_slider_img' . $i;
	$cf_keys[] = 'plan_slider_cap' . $i;

  $plan_slider_sub_box_labels[] = __( 'Item', 'tcd-w' ) . $i;
}

$plan_slider_fields = array(
	array(
		'title' => __( 'Item settings', 'tcd-w' ),
		'type' => 'sub_box',
		'before_field' => '<dl class="ml_custom_fields">',
		'after_field' => '</dd></dl>',
		'before_title' => '<dt class="label">',
		'after_title' => '</dt><dd class="content">',
		'labels' => $plan_slider_sub_box_labels, // 各 sub_box の見出し
		'fields' => $plan_slider_sub_box_fields
	)
);

$plan_slider_args = array(
	'id' => 'plan_slider_meta_box',
	'title' => __( 'Slider settings', 'tcd-w' ),
	'screen' => array( 'plan' ),
	'context' => 'normal',
	'fields' => $plan_slider_fields,
  'cf_keys' => $cf_keys
);

$plan_slider_meta_box = new TCD_Meta_Box( $plan_slider_args );

// Plan information
$plan_info_fields = array(
  array(
		'id' => 'plan_display_thumbnail',
		'title' => '',
		'type' => 'checkbox',
		'before_field' => '<p>',
		'after_field' => '</p>',
		'options' => array( 
			array(
				'value' => 1,
				'label' => __( 'Display featured image', 'tcd-w' )
			)
		)
  ),
  array(
		'id' => 'plan_data',
		'title' => __( 'Plan data', 'tcd-w' ),
		'type' => 'repeater_table',
		'before_field' => '<dl class="ml_custom_fields">',
		'after_field' => '</dd></dl>',
		'before_title' => '<dt class="label">',
		'after_title' => '</dt><dd class="content">',
    'default' => array( 'header' => array( '' ), 'data' => array( '' ) ),
    'header' => array( 'header' => __( 'Headline', 'tcd-w' ), 'data' => __( 'Data', 'tcd-w' ) ),
  ),
  array(
		'id' => 'plan_btn_label',
		'title' => __( 'Button label', 'tcd-w' ),
		'type' => 'text',
		'before_field' => '<dl class="ml_custom_fields">',
		'after_field' => '</dd></dl>',
		'before_title' => '<dt class="label">',
		'after_title' => '</dt><dd class="content">'
  ),
  array(
		'id' => 'plan_btn_url',
		'title' => __( 'Button url', 'tcd-w' ),
		'type' => 'text',
		'before_field' => '<dl class="ml_custom_fields">',
		'after_field' => '</dd></dl>',
		'before_title' => '<dt class="label">',
		'after_title' => '</dt><dd class="content">'
  ),
  array(
		'id' => 'plan_btn_target',
		'title' => '',
		'type' => 'checkbox',
		'options' => array( 
			array(
				'value' => 1,
				'label' => __( 'Open with new window', 'tcd-w' )
			)
		)
  )
);

$plan_info_args = array(
	'id' => 'plan_info_meta_box',
	'title' => __( 'Plan information settings', 'tcd-w' ),
	'context' => 'normal',
	'fields' => $plan_info_fields,
	'screen' => array( 'plan' )
); 

$plan_info_meta_box = new TCD_Meta_Box( $plan_info_args );

/**
 * Add a recommended image size
 */
function add_recommended_feature_image_size( $content, $post_id ) {

  $post_type = get_post_type( $post_id );

  if ( 'plan' === $post_type ) {
    $content .= '<p>' . __( 'Recommend image size: width 560px or more, height 560px or more', 'tcd-w' ) . '</p>' . "\n";
  }

  return $content;
}
add_filter( 'admin_post_thumbnail_html', 'add_recommended_feature_image_size', 10, 2 );
