<?php
/**
 * Add a meta box of recommend post
 */
$recommend_fields = array(
	array( 
		'id' => 'recommend_post1',
		'title' => '',
		'type' => 'checkbox',
		'after_field' => '<br>',
		'options' => array( 
			array(
				'value' => 'on',
				'label' => __( 'Display this post as recommended post1.', 'tcd-w' )
			)
		),
	),
	array( 
		'id' => 'recommend_post2',
		'title' => '',
		'type' => 'checkbox',
		'after_field' => '<br>',
		'options' => array( 
			array(
				'value' => 'on',
				'label' => __( 'Display this post as recommended post2.', 'tcd-w' )
			)
		),
	),
	array( 
		'id' => 'recommend_post3',
		'title' => '',
		'type' => 'checkbox',
		'options' => array( 
			array(
				'value' => 'on',
				'label' => __( 'Display this post as recommended post3.', 'tcd-w' )
			)
		)
	)
);
$recommend_args = array(
	'id' => 'recommend_meta_box',
	'title' => __( 'Recommend post', 'tcd-w' ),
	'context' => 'side',
	'fields' => $recommend_fields,
	'description' => __( 'Check if you want to show this post for recommend post.', 'tcd-w' )
); 
$recommend_meta_box = new TCD_Meta_Box( $recommend_args );
