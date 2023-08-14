<?php
/**
 * Add a meta box of topic
 */
$topic_fields = array(
	array( 
		'id' => 'topic',
		'title' => '',
		'type' => 'checkbox',
    'before_field' => '<p>',
    'after_field' => '</p>',
		'options' => array( 
			array(
				'value' => 'on',
				'label' => __( 'Display this post as topic.', 'tcd-w' )
			)
		)
	)
);
$topic_args = array(
	'id' => 'topic_meta_box',
	'title' => __( 'Topic', 'tcd-w' ),
	'context' => 'side',
  'screen' => array( 'post', 'news', 'plan' ),
	'fields' => $topic_fields,
	'description' => __( 'Check if you want to display this post as topic.', 'tcd-w' )
); 
$topic_meta_box = new TCD_Meta_Box( $topic_args );
