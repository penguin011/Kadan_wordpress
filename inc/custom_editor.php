<?php
$dp_optiong = get_design_plus_option();

if ( $dp_optiong['use_quicktags'] ) {
	add_action( 'admin_head', 'tcd_add_mce_button' );
  add_action( 'admin_print_footer_scripts', 'tcd_add_quicktags' );
}

// Hooks your functions into the correct filters
function tcd_add_mce_button() {
	// check user permissions
	if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) ) {
		return;
	}
	// check if WYSIWYG is enabled
	if ( 'true' == get_user_option( 'rich_editing' ) ) {
		add_filter( 'mce_external_plugins', 'tcd_add_tinymce_plugin' );
		add_filter( 'mce_buttons', 'tcd_register_mce_button' );
	}
}

// Declare script for new button
function tcd_add_tinymce_plugin( $plugin_array ) {
	$plugin_array['tcd_mce_button'] = get_template_directory_uri() .'/admin/assets/js/mce-button.js?ver=2.0.0';
	return $plugin_array;
}

// Register new button in the editor
function tcd_register_mce_button( $buttons ) {
	array_push( $buttons, 'tcd_mce_button' );
	return $buttons;
}

function tcd_add_quicktags() {

  $tcdQuicktagsL10n = array(
    'pulldown_title' => array(
		  'display' => __( 'quicktags', 'tcd-w' ),
		),
		'ytube' => array(
		  'display' => __( 'Youtube', 'tcd-w' ),
      'tag' => __( '<div class="ytube">Youtube code here</div>', 'tcd-w' )
		),
		'relatedcardlink' => array(
      'display' => __( 'Cardlink', 'tcd-w' ),
      'tag' => __( '[clink url="Post URL to display"]', 'tcd-w' )
		),
		'post_col-2' => array(
      'display' => __( '2 column', 'tcd-w' ),
      'tag' => __( '<div class="post_row"><div class="post_col post_col-2">Text and image tags to display in the left column</div><div class="post_col post_col-2">Text and image tags to display in the right column</div></div>', 'tcd-w' )
		),
		'post_col-3' => array(
      'display' => __( '3 column', 'tcd-w' ),
      'tag' => __( '<div class="post_row"><div class="post_col post_col-3">Text and image tags to display in the left column</div><div class="post_col post_col-3">Text and image tags to display in the center column</div><div class="post_col post_col-3">Text and image tags to display in the right column</div></div>', 'tcd-w' )
		),
		'style3a' => array(
      'display' => __( 'H3 styleA', 'tcd-w' ),
      'tag' => __( '<h3 class="style3a">Heading 3 styleA</h3>', 'tcd-w' )
		),
		'style3b' => array(
      'display' => __( 'H3 styleB', 'tcd-w' ),
      'tag' => __( '<h3 class="style3b">Heading 3 styleB</h3>', 'tcd-w' )
		),
		'style4a' => array(
      'display' => __( 'H4 styleA', 'tcd-w' ),
      'tag' => __( '<h4 class="style4a">Heading 4 styleA</h4>', 'tcd-w' )
		),
		'style4b' => array(
			'display' => __( 'H4 styleB', 'tcd-w' ),
      'tag' => __( '<h4 class="style4b">Heading 4 styleB</h4>', 'tcd-w' )
		),
		'style5a' => array(
			'display' => __( 'H5 styleA', 'tcd-w' ),
      'tag' => __( '<h5 class="style5a">Heading 5 styleA</h5>', 'tcd-w' )
		),
		'style5b' => array(
			'display' => __( 'H5 styleB', 'tcd-w' ),
			'tag' => __( '<h5 class="style5b">Heading 5 styleB</h5>', 'tcd-w' )
		),
		'well' => array(
      'display' => __( 'Frame styleA', 'tcd-w' ),
			'tag' => __( '<p class="well">Frame styleA</p>', 'tcd-w' )
		),
		'well2' => array(
      'display' => __( 'Frame styleB', 'tcd-w' ),
			'tag' => __( '<p class="well2">Frame styleB</p>', 'tcd-w' )
		),
		'well3' => array(
      'display' => __( 'Frame styleC', 'tcd-w' ),
			'tag' => __( '<p class="well3">Frame styleC</p>', 'tcd-w' )
		),
		'q_button' => array(
      'display' => __( 'Flat btn', 'tcd-w' ),
			'tag' => __( '<a href="#" class="q_button">Flat button</a>', 'tcd-w' )
		),
		'q_button_l' => array(
      'display' => __( 'Flat btn-L', 'tcd-w' ),
      'tag' => __( '<a href="#" class="q_button sz_l">Flat button sizeL</a>', 'tcd-w' )
		),
		'q_button_s' => array(
      'display' => __( 'Flat btn-S', 'tcd-w' ),
      'tag' => __( '<a href="#" class="q_button sz_s">Flat button sizeS</a>', 'tcd-w' )
		),
		'q_button_blue' => array(
      'display' => __( 'Flat btn-blue', 'tcd-w' ),
      'tag' => __( '<a href="#" class="q_button bt_blue">Flat button blue</a>', 'tcd-w' )
		),
		'q_button_green' => array(
      'display' => __( 'Flat btn-green', 'tcd-w' ),
      'tag' => __( '<a href="#" class="q_button bt_green">Flat button green</a>', 'tcd-w' )
		),
		'q_button_red' => array(
      'display' => __( 'Flat btn-red', 'tcd-w' ),
      'tag' => __( '<a href="#" class="q_button bt_red">Flat button red</a>', 'tcd-w' )
		),
		'q_button_yellow' => array(
      'display' => __( 'Flat btn-yellow', 'tcd-w' ),
      'tag' => __( '<a href="#" class="q_button bt_yellow">Flat button yellow</a>', 'tcd-w' )
		),
		'q_button_rounded' => array(
      'display' => __( 'Rounded btn', 'tcd-w' ),
      'tag' => __( '<a href="#" class="q_button rounded">Rounded button</a>', 'tcd-w' )
		),
		'q_button_rounded_l' => array(
      'display' => __( 'Rounded btn-L', 'tcd-w' ),
      'tag' => __( '<a href="#" class="q_button rounded sz_l">Rounded button sizeL</a>', 'tcd-w' )
		),
		'q_button_rounded_s' => array(
      'display' => __( 'Rounded btn-S', 'tcd-w' ),
      'tag' => __( '<a href="#" class="q_button rounded sz_s">Rounded button sizeS</a>', 'tcd-w' )
		),
		'q_button_pill' => array(
      'display' => __( 'oval btn', 'tcd-w' ),
      'tag' => __( '<a href="#" class="q_button pill">Oval button</a>', 'tcd-w' )
		),
		'q_button_pill_l' => array(
      'display' => __( 'oval btn-L', 'tcd-w' ),
      'tag' => __( '<a href="#" class="q_button pill sz_l">Oval button sizeL</a>', 'tcd-w' )
		),
		'q_button_pill_s' => array(
      'display' => __( 'oval btn-S', 'tcd-w' ),
      'tag' => __( '<a href="#" class="q_button pill sz_s">Oval button sizeS</a>', 'tcd-w' )
		),
		'single_banner' => array(
      'display' => __( 'advertisement', 'tcd-w' ),
			'tag' => __( '[s_ad]', 'tcd-w' )
		),
		'page_break' => array(
			'display' => __( 'Page break' ),
			'tag' => '<!--nextpage-->'
		),
		'vertical_writing' => array(
			'display' => __( 'Vertical writing', 'tcd-w' ),
			'tag' => __( '<div class="p-vertical"><p>Vertical writing</p></div>', 'tcd-w' )
		)
	);

  //echo '<script>';
	echo '<script type="text/javascript">'; 

  if ( 'true' == get_user_option( 'rich_editing' ) ) { 
    echo "var tcdQuicktagsL10n = " . json_encode( $tcdQuicktagsL10n ) . ";\n";
  }
	
	if ( wp_script_is( 'quicktags' ) ) {

		foreach( $tcdQuicktagsL10n as $key => $value ) {
			if ( is_numeric( $key ) || empty( $value['display'] ) ) continue;
			if ( empty( $value['tag'] ) && empty( $value['tagStart'] ) ) continue;

			if ( isset( $value['tag'] ) || ! isset( $value['tagStart'] ) ) {
				$value['tagStart'] = $value['tag'] . "\n\n";
			}
			if ( ! isset( $value['tagEnd'] ) ) {
				$value['tagEnd'] = '';
			}

			$key = json_encode( $key );
			$display = json_encode( $value['display'] );
			$tagStart = json_encode( $value['tagStart'] );
			$tagEnd = json_encode( $value['tagEnd'] );
			//echo "QTags.addButton($key, $display, $tagStart, $tagEnd);\n";
			echo "QTags.addButton($key, $display, $tagStart, $tagEnd);";
		}
	}
  echo "</script>\n";
}
