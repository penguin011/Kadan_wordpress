<?php
/**
 * Manage top top
 */

// Add default values
add_filter( 'before_getting_design_plus_option', 'add_top_dp_default_options' );

//  Add label of top tab
add_action( 'tcd_tab_labels', 'add_top_tab_label' );

// Add HTML of top tab
add_action( 'tcd_tab_panel', 'add_top_tab_panel' );

// Register sanitize function
add_filter( 'theme_options_validate', 'add_top_theme_options_validate' );

global $hero_header_speed_options;
$hero_header_speed_options = array();
for ( $i = 5; $i <= 8; $i++ ) {
  $hero_header_speed_options[$i] = array( 'value' => $i, 'label' => sprintf( __( '%d seconds', 'tcd-w' ), $i ) );
}

global $hero_header_type_options;
$hero_header_type_options = array(
	'type1' => array( 'value' => 'type1', 'label' => __( 'Image', 'tcd-w' ) ),
 	'type2' => array( 'value' => 'type2', 'label' => __( 'Video', 'tcd-w' ) ),
 	'type3' => array( 'value' => 'type3', 'label' => __( 'YouTube', 'tcd-w' ) )
);

global $hero_header_writing_type_options;
$hero_header_writing_type_options = array(
  'type1' => array( 'value' => 'type1', 'label' => __( 'Vertical writing', 'tcd-w' ) ),
  'type2' => array( 'value' => 'type2', 'label' => __( 'Horizontal writing', 'tcd-w' ) )
);

global $hero_header_effect_type_options;
$hero_header_effect_type_options = array(
  'type1' => array( 'value' => 'type1', 'label' => __( 'Zoom-in', 'tcd-w' ) ),
  'type2' => array( 'value' => 'type2', 'label' => __( 'Zoom-out', 'tcd-w' ) ),
  'type3' => array( 'value' => 'type3', 'label' => __( 'No effect', 'tcd-w' ) )
);

global $writing_type_options;
$writing_type_options = array(
  'type1' => array( 'value' => 'type1', 'label' => __( 'Vertical writing', 'tcd-w' ) ),
  'type2' => array( 'value' => 'type2', 'label' => __( 'Horizontal writing', 'tcd-w' ) )
);

global $cb_news_topic_type_options;
$cb_news_topic_type_options = array(
  'post' => array( 'value' => 'post', 'label' => __( 'Post', 'tcd-w' ) ),
  'news' => array( 'value' => 'news', 'label' => __( 'News', 'tcd-w' ) ),
  'plan' => array( 'value' => 'plan', 'label' => __( 'Plan', 'tcd-w' ) )
);

global $cb_news_topic_speed_options;
$cb_news_topic_speed_options = array();
for ( $i = 5; $i <= 10; $i++ ) {
  $cb_news_topic_speed_options[$i] = array( 'value' => $i, 'label' => sprintf( __( '%d seconds', 'tcd-w' ), $i ) );
}

global $cb_section_type_options;
$cb_section_type_options = array(
  'type1' => array( 'value' => 'type1', 'label' => __( 'Type1', 'tcd-w' ) ),
  'type2' => array( 'value' => 'type2', 'label' => __( 'Type2', 'tcd-w' ) ),
  'type3' => array( 'value' => 'type3', 'label' => __( 'Type3', 'tcd-w' ) )
);

global $cb_section_layout_options;
$cb_section_layout_options = array(
  'type1' => array( 'value' => 'type1', 'label' => __( 'Left: headline and description, Right: block contents', 'tcd-w' ) ),
  'type2' => array( 'value' => 'type2', 'label' => __( 'Left: block contents, Right: headline and description', 'tcd-w' ) )
);

global $cb_blog_type_options;
$cb_blog_type_options = array(
  'recent_post' => array( 'value' => 'recent_post', 'label' => __( 'Recent post', 'tcd-w' ) ),
  'recommend_post1' => array( 'value' => 'recommend_post1', 'label' => __( 'Recommended post1', 'tcd-w' ) ),
  'recommend_post2' => array( 'value' => 'recommend_post2', 'label' => __( 'Recommended post2', 'tcd-w' ) ),
  'recommend_post3' => array( 'value' => 'recommend_post3', 'label' => __( 'Recommended post3', 'tcd-w' ) )
);

function add_top_dp_default_options( $dp_default_options ) {

  // Hero header
  $dp_default_options['hero_header_speed'] = 7;
	for ( $i = 1; $i <= 3; $i++ ) {
		$dp_default_options['hero_header_type' . $i] = 'type1';
		$dp_default_options['hero_header_catch_font_size' . $i] = 40;
		$dp_default_options['hero_header_desc' . $i] = __( 'Here is a slider1 description.', 'tcd-w' ) . "\n" . __( 'Here is a slider1 description.', 'tcd-w' );
		$dp_default_options['hero_header_desc_font_size' . $i] = 16;
		$dp_default_options['hero_header_color' . $i] = '#ffffff';
		$dp_default_options['hero_header_writing_type' . $i] = 'type1';
		$dp_default_options['hero_header_img_sp' . $i] = '';
		$dp_default_options['hero_header_img' . $i] = '';
		$dp_default_options['hero_header_effect_type' . $i] = 'type3';
		$dp_default_options['hero_header_video' . $i] = '';
		$dp_default_options['hero_header_youtube' . $i] = '';
	}
	$dp_default_options['hero_header_catch1'] = __( 'Slider1', 'tcd-w' );
	$dp_default_options['hero_header_catch2'] = __( 'Slider2', 'tcd-w' );
	$dp_default_options['hero_header_catch3'] = __( 'Slider3', 'tcd-w' );
  $dp_default_options['hero_header_arrow_color'] = '#ffffff';
  $dp_default_options['hero_header_btn_font_size'] = 18;
  for ( $i = 1; $i <= 3; $i++ ) {
    $dp_default_options['hero_header_btn_label' . $i] = __( 'Button', 'tcd-w' );
    $dp_default_options['hero_header_btn_url' . $i] = '#';
    $dp_default_options['hero_header_btn_target' . $i] = '';
    $dp_default_options['hero_header_btn_color' . $i] = '#ffffff';
    $dp_default_options['hero_header_btn_color_hover' . $i] = '#ffffff';
  }
  $dp_default_options['hero_header_btn_bg1'] = '#660000';
  $dp_default_options['hero_header_btn_bg_hover1'] = '#520000';
  $dp_default_options['hero_header_btn_bg2'] = '#204000';
  $dp_default_options['hero_header_btn_bg_hover2'] = '#1a3300';
  $dp_default_options['hero_header_btn_bg3'] = '#402000';
  $dp_default_options['hero_header_btn_bg_hover3'] = '#331a00';

  // Contents builder
	$dp_default_options['contents_builder'] = array(
    array( 
      'column' => 'one_column',
      'cb_content_select' => 'news',
      'cb_news_display' => 1,
      'cb_news_topic_headline' => __( 'Topics', 'tcd-w' ),
      'cb_news_topic_type' => 'post',
      'cb_news_topic_speed' => '5',
      'cb_news_display_date' => 1,
      'cb_news_news_headline' => __( 'News', 'tcd-w' ),
      'cb_news_btn_label' => __( 'News archive', 'tcd-w' )
    ),
    array(
      'column' => 'one_column',
      'cb_content_select' => 'section',
      'cb_section_display' => 1,
      'cb_section_type' => 'type1',
      'cb_section_header_img' => '',
      'cb_section_header_title' => __( 'Section1', 'tcd-w' ),
      'cb_section_header_font_size' => 40,
      'cb_section_header_color' => '#ffffff',
      'cb_section_header_writing_type' => 'type1',
      'cb_section_headline' => __( 'Headline', 'tcd-w' ),
      'cb_section_headline_font_size' => 36,
      'cb_section_headline_color' => '#ffffff',
      'cb_section_headline_bg' => '#660000',
      'cb_section_headline_writing_type' => 'type1',
      'cb_section_headline_layout' => 'type1',
      'cb_section_desc' => __( 'Here is a description.', 'tcd-w' ) . "\n" . __( 'Here is a description.', 'tcd-w' ) . "\n" . __( 'Here is a description.', 'tcd-w' ) . "\n",
      'cb_section_type2_layout' => 'type1',
      'cb_section_type3_layout' => 'type2',
      'cb_section_type1_block_layout' => 'type1',
      'cb_section_type1_block_img1' => '',
      'cb_section_type1_block_text2' => __( 'Sample text.', 'tcd-w' ) . __( 'Sample text.', 'tcd-w' ) . __( 'Sample text.', 'tcd-w' ) . __( 'Sample text.', 'tcd-w' ) . __( 'Sample text.', 'tcd-w' ) . __( 'Sample text.', 'tcd-w' ),
      'cb_section_type1_block_btn_label2' => '',
      'cb_section_type1_block_btn_url2' => '',
      'cb_section_type1_block_img3' => '',
      'cb_section_type1_block_text4' => __( 'Sample text.', 'tcd-w' ) . __( 'Sample text.', 'tcd-w' ) . __( 'Sample text.', 'tcd-w' ) . __( 'Sample text.', 'tcd-w' ) . __( 'Sample text.', 'tcd-w' ) . __( 'Sample text.', 'tcd-w' ),
      'cb_section_type1_block_btn_label4' => '',
      'cb_section_type1_block_btn_url4' => '',
      'cb_section_type1_block_img5' => '',
      'cb_section_type1_block_text6' => __( 'Sample text.', 'tcd-w' ) . __( 'Sample text.', 'tcd-w' ) . __( 'Sample text.', 'tcd-w' ) . __( 'Sample text.', 'tcd-w' ) . __( 'Sample text.', 'tcd-w' ) . __( 'Sample text.', 'tcd-w' ),
      'cb_section_type1_block_btn_label6' => __( 'Sample button', 'tcd-w' ),
      'cb_section_type1_block_btn_url6' => '#',
      'cb_section_type1_block_color' => '#ffffff',
      'cb_section_type1_block_bg' => '#000000',
      'cb_section_type1_block_btn_color' => '#ffffff',
      'cb_section_type1_block_btn_bg' => '#333333',
      'cb_section_type1_block_btn_color_hover' => '#ffffff',
      'cb_section_type1_block_btn_bg_hover' => '#660000',
      'cb_section_type2_block_layout' => 'type1',
      'cb_section_type2_block_img1' => '',
      'cb_section_type2_block_img2' => '',
      'cb_section_type2_block_img3' => '',
      'cb_section_type2_block_img4' => '',
      'cb_section_type2_block_title' => '',
      'cb_section_type2_block_desc' => '',
      'cb_section_type2_block_color' => '#000000',
      'cb_section_type2_block_bg' => '#f4f1ed',
      'cb_section_type3_block_layout' => 'type1',
      'cb_section_type3_block_img1' => '',
      'cb_section_type3_block_img2' => '',
      'cb_section_type3_block_img3' => '',
      'cb_section_type3_block_title' => '',
      'cb_section_type3_block_desc' => '',
      'cb_section_type3_block_color' => '#000000',
      'cb_section_type3_block_bg' => '#f4f1ed',
      'cb_section_btn_label' => __( 'Sample button', 'tcd-w' ),
      'cb_section_btn_url' => '#'
    ),
    array(
			'column' => 'one_column',
		  'cb_content_select' => 'recommended_plan',	
      'cb_recommended_plan_display' => 1,
      'cb_recommended_plan_img' => '',
      'cb_recommended_plan_header_title' => __( 'Recommended plan', 'tcd-w' ),
      'cb_recommended_plan_header_color' => '#ffffff',
      'cb_recommended_plan_header_font_size' => 40,
      'cb_recommended_plan_header_writing_type' => 'type1',
      'cb_recommended_plan_btn_label' => __( 'Plan archive', 'tcd-w' )
    ),
    array(
			'column' => 'one_column',
		  'cb_content_select' => 'blog',
      'cb_blog_display' => 1,
      'cb_blog_headline' => __( 'Blog', 'tcd-w' ),
      'cb_blog_type' => 'recent_post',
      'cb_blog_btn_label' => __( 'Blog archive', 'tcd-w' )
    ),
    array(
      'column' => 'one_column',
      'cb_content_select' => 'section',
      'cb_section_display' => 1,
      'cb_section_type' => 'type2',
      'cb_section_header_img' => '',
      'cb_section_header_title' => __( 'Section2', 'tcd-w' ),
      'cb_section_header_font_size' => 40,
      'cb_section_header_color' => '#ffffff',
      'cb_section_header_writing_type' => 'type1',
      'cb_section_headline' => __( 'Headline', 'tcd-w' ),
      'cb_section_headline_font_size' => 36,
      'cb_section_headline_color' => '#ffffff',
      'cb_section_headline_bg' => '#660000',
      'cb_section_headline_writing_type' => 'type1',
      'cb_section_headline_layout' => 'type1',
      'cb_section_desc' => __( 'Here is a description.', 'tcd-w' ) . "\n" . __( 'Here is a description.', 'tcd-w' ) . "\n" . __( 'Here is a description.', 'tcd-w' ) . "\n",
      'cb_section_type2_layout' => 'type1',
      'cb_section_type3_layout' => 'type2',
      'cb_section_type1_block_layout' => 'type1',
      'cb_section_type1_block_img1' => '',
      'cb_section_type1_block_text2' => '',
      'cb_section_type1_block_text2' => '',
      'cb_section_type1_block_btn_label2' => '',
      'cb_section_type1_block_btn_url2' => '',
      'cb_section_type1_block_img3' => '',
      'cb_section_type1_block_text4' => '',
      'cb_section_type1_block_btn_label4' => '',
      'cb_section_type1_block_btn_url4' => '',
      'cb_section_type1_block_img5' => '',
      'cb_section_type1_block_text6' => '',
      'cb_section_type1_block_btn_label6' => '',
      'cb_section_type1_block_btn_url6' => '',
      'cb_section_type1_block_color' => '#ffffff',
      'cb_section_type1_block_bg' => '#000000',
      'cb_section_type1_block_btn_color' => '#ffffff',
      'cb_section_type1_block_btn_bg' => '#333333',
      'cb_section_type1_block_btn_color_hover' => '#ffffff',
      'cb_section_type1_block_btn_bg_hover' => '#660000',
      'cb_section_type2_block_layout' => 'type1',
      'cb_section_type2_block_img1' => '',
      'cb_section_type2_block_img2' => '',
      'cb_section_type2_block_img3' => '',
      'cb_section_type2_block_img4' => '',
      'cb_section_type2_block_title' => __( 'Sample title', 'tcd-w' ),
      'cb_section_type2_block_desc' => __( 'Sample text.', 'tcd-w' ) . __( 'Sample text.', 'tcd-w' ) . __( 'Sample text.', 'tcd-w' ) . __( 'Sample text.', 'tcd-w' ) . __( 'Sample text.', 'tcd-w' ) . __( 'Sample text.', 'tcd-w' ),
      'cb_section_type2_block_color' => '#000000',
      'cb_section_type2_block_bg' => '#f4f1ed',
      'cb_section_type3_block_layout' => 'type1',
      'cb_section_type3_block_img1' => '',
      'cb_section_type3_block_img2' => '',
      'cb_section_type3_block_img3' => '',
      'cb_section_type3_block_title' => '',
      'cb_section_type3_block_desc' => '',
      'cb_section_type3_block_color' => '#000000',
      'cb_section_type3_block_bg' => '#f4f1ed',
      'cb_section_btn_label' => __( 'Sample button', 'tcd-w' ),
      'cb_section_btn_url' => '#'
    ),
    array(
      'column' => 'one_column',
      'cb_content_select' => 'section',
      'cb_section_display' => 1,
      'cb_section_type' => 'type3',
      'cb_section_header_img' => '',
      'cb_section_header_title' => __( 'Section3', 'tcd-w' ),
      'cb_section_header_font_size' => 40,
      'cb_section_header_color' => '#ffffff',
      'cb_section_header_writing_type' => 'type1',
      'cb_section_headline' => __( 'Headline', 'tcd-w' ),
      'cb_section_headline_font_size' => 36,
      'cb_section_headline_color' => '#ffffff',
      'cb_section_headline_bg' => '#660000',
      'cb_section_headline_writing_type' => 'type1',
      'cb_section_headline_layout' => 'type1',
      'cb_section_desc' => __( 'Here is a description.', 'tcd-w' ) . "\n" . __( 'Here is a description.', 'tcd-w' ) . "\n" . __( 'Here is a description.', 'tcd-w' ) . "\n",
      'cb_section_type2_layout' => 'type1',
      'cb_section_type3_layout' => 'type2',
      'cb_section_type1_block_layout' => 'type1',
      'cb_section_type1_block_img1' => '',
      'cb_section_type1_block_text2' => '',
      'cb_section_type1_block_text2' => '',
      'cb_section_type1_block_btn_label2' => '',
      'cb_section_type1_block_btn_url2' => '',
      'cb_section_type1_block_img3' => '',
      'cb_section_type1_block_text4' => '',
      'cb_section_type1_block_btn_label4' => '',
      'cb_section_type1_block_btn_url4' => '',
      'cb_section_type1_block_img5' => '',
      'cb_section_type1_block_text6' => '',
      'cb_section_type1_block_btn_label6' => '',
      'cb_section_type1_block_btn_url6' => '',
      'cb_section_type1_block_color' => '#ffffff',
      'cb_section_type1_block_bg' => '#000000',
      'cb_section_type1_block_btn_color' => '#ffffff',
      'cb_section_type1_block_btn_bg' => '#333333',
      'cb_section_type1_block_btn_color_hover' => '#ffffff',
      'cb_section_type1_block_btn_bg_hover' => '#660000',
      'cb_section_type2_block_layout' => 'type1',
      'cb_section_type2_block_img1' => '',
      'cb_section_type2_block_img2' => '',
      'cb_section_type2_block_img3' => '',
      'cb_section_type2_block_img4' => '',
      'cb_section_type2_block_title' => '',
      'cb_section_type2_block_desc' => '',
      'cb_section_type2_block_color' => '#000000',
      'cb_section_type2_block_bg' => '#f4f1ed',
      'cb_section_type3_block_layout' => 'type1',
      'cb_section_type3_block_img1' => '',
      'cb_section_type3_block_img2' => '',
      'cb_section_type3_block_img3' => '',
      'cb_section_type3_block_title' => __( 'Sample title', 'tcd-w' ),
      'cb_section_type3_block_desc' => __( 'Sample text.', 'tcd-w' ) . __( 'Sample text.', 'tcd-w' ) . __( 'Sample text.', 'tcd-w' ) . __( 'Sample text.', 'tcd-w' ) . __( 'Sample text.', 'tcd-w' ) . __( 'Sample text.', 'tcd-w' ),
      'cb_section_type3_block_color' => '#000000',
      'cb_section_type3_block_bg' => '#f4f1ed',
      'cb_section_btn_label' => __( 'Sample button', 'tcd-w' ),
      'cb_section_btn_url' => '#'
    )
  );

	return $dp_default_options;
}

function add_top_tab_label( $tab_labels ) {
	$tab_labels['top'] = __( 'Front page', 'tcd-w' );
	return $tab_labels;
}

function add_top_tab_panel( $dp_options ) {
	global $dp_default_options, $hero_header_speed_options, $hero_header_type_options, $hero_header_writing_type_options, $hero_header_effect_type_options;
?>
<div id="tab-content3">
  <?php // Hero header ?>
	<div class="theme_option_field cf">
  	<h3 class="theme_option_headline"><?php _e( 'Hero header settings', 'tcd-w' ); ?></h3>
    <h4 class="theme_option_headline2"><?php _e( 'Slider settings', 'tcd-w' ); ?></h4>
    <p>
      <label for="hero_header_speed"><?php _e( 'Autoplay speed', 'tcd-w' ); ?></label>
      <select id="hero_header_speed" name="dp_options[hero_header_speed]">
        <?php foreach ( $hero_header_speed_options as $option ) : ?>
        <option value="<?php echo esc_attr( $option['value'] ); ?>" <?php selected( $option['value'], $dp_options['hero_header_speed'] ); ?>><?php echo esc_html( $option['label'] ); ?></option>
        <?php endforeach; ?>
      </select>
    </p>
		<?php for ( $i = 1; $i <= 3; $i++ ) : ?>
		<div class="sub_box cf"> 
    	<h3 class="theme_option_subbox_headline"><?php _e( 'Content', 'tcd-w' ); ?><?php echo $i; ?></h3>
    	<div class="sub_box_content">
    		<h4 class="theme_option_headline2"><?php _e( 'Content type', 'tcd-w' ); ?></h4>
				<p><?php _e( 'On tablets and mobiles images are displayed regardless of the type of content', 'tcd-w' ); ?></p>
				<?php foreach ( $hero_header_type_options as $option ) : ?>
				<p><label><input type="radio" name="dp_options[hero_header_type<?php echo $i; ?>]" value="<?php echo esc_attr( $option['value'] ); ?>" <?php checked( $dp_options['hero_header_type' . $i], $option['value'] ); ?>><?php esc_html_e( $option['label'], 'tcd-w' ); ?></label></p>
				<?php endforeach; ?>
    		<h4 class="theme_option_headline2"><?php _e( 'Catchphrase', 'tcd-w' ); ?></h4>
			  <textarea class="regular-text" name="dp_options[hero_header_catch<?php echo $i; ?>]"><?php echo esc_textarea( $dp_options['hero_header_catch' . $i] ); ?></textarea>
				<p><label><?php _e( 'Font size', 'tcd-w' ); ?> <input type="number" min="1" class="tiny-text" name="dp_options[hero_header_catch_font_size<?php echo $i; ?>]" value="<?php echo esc_attr( $dp_options['hero_header_catch_font_size' . $i] ); ?>"> px</label></p>
    		<h4 class="theme_option_headline2"><?php _e( 'Description', 'tcd-w' ); ?></h4>
				<textarea class="large-text" type="text" name="dp_options[hero_header_desc<?php echo $i; ?>]"><?php echo esc_textarea( $dp_options['hero_header_desc' . $i] ); ?></textarea>
				<p><label><?php _e( 'Font size', 'tcd-w' ); ?> <input type="number" min="1" class="tiny-text" name="dp_options[hero_header_desc_font_size<?php echo $i; ?>]" value="<?php echo esc_attr( $dp_options['hero_header_desc_font_size' . $i] ); ?>"> px</label></p>
    		<h4 class="theme_option_headline2"><?php _e( 'Font color of catchphrase and description', 'tcd-w' ); ?></h4>
        <input type="text" class="c-color-picker" name="dp_options[hero_header_color<?php echo $i; ?>]" data-default-color="<?php echo esc_attr( $dp_default_options['hero_header_color' . $i] ); ?>" value="<?php echo esc_attr( $dp_options['hero_header_color' . $i] ); ?>">
    		<h4 class="theme_option_headline2"><?php _e( 'Writing mode of catchphrase and description', 'tcd-w' ); ?></h4>
        <?php foreach ( $hero_header_writing_type_options as $option ) : ?>
        <p><label><input type="radio" name="dp_options[hero_header_writing_type<?php echo $i; ?>]" value="<?php echo esc_attr( $option['value'] ); ?>" <?php checked( $option['value'], $dp_options['hero_header_writing_type' . $i] ); ?>> <?php echo esc_html_e( $option['label'] ); ?></label></p>
        <?php endforeach; ?>
    		<h4 class="theme_option_headline2"><?php _e( 'Background image for tablet and mobile', 'tcd-w' ); ?></h4>
				<p><?php _e( 'Recommended size: width 1450px, height 1200px', 'tcd-w' ); ?></p>
        <p><?php _e( 'Notice: this image is cropped for tablet and mobile devices.', 'tcd-w' ); ?></p>
  		  <div class="image_box cf">
  		  	<div class="cf cf_media_field hide-if-no-js">
  		  		<input type="hidden" value="<?php echo esc_attr( $dp_options['hero_header_img_sp' . $i] ); ?>" id="hero_header_img_sp<?php echo $i; ?>" name="dp_options[hero_header_img_sp<?php echo $i; ?>]" class="cf_media_id">
  		  		<div class="preview_field"><?php if ( $dp_options['hero_header_img_sp' . $i] ) { echo wp_get_attachment_image( $dp_options['hero_header_img_sp' . $i], 'medium' ); } ?></div>
  		  		<div class="button_area">
  		   			<input type="button" value="<?php _e( 'Select Image', 'tcd-w' ); ?>" class="cfmf-select-img button">
  		   			<input type="button" value="<?php _e( 'Remove Image', 'tcd-w' ); ?>" class="cfmf-delete-img button <?php if ( ! $dp_options['hero_header_img_sp' . $i] ) { echo 'hidden'; } ?>">
  		  		</div>
  		  	</div>
				</div>
				<div id="hero_header_type<?php echo $i; ?>_type1"<?php if ( 'type1' !== $dp_options['hero_header_type' . $i] ) { echo ' style="display: none;"'; } ?>>
    			<h4 class="theme_option_headline2"><?php _e( 'Background image for PC', 'tcd-w' ); ?></h4>
					<p><?php _e( 'Recommended size: width 1450px, height 1200px', 'tcd-w' ); ?></p>
  		  	<div class="image_box cf">
  		   		<div class="cf cf_media_field hide-if-no-js">
  		    		<input type="hidden" value="<?php echo esc_attr( $dp_options['hero_header_img' . $i] ); ?>" id="hero_header_img<?php echo $i; ?>" name="dp_options[hero_header_img<?php echo $i; ?>]" class="cf_media_id">
  		    		<div class="preview_field"><?php if ( $dp_options['hero_header_img' . $i] ) { echo wp_get_attachment_image( $dp_options['hero_header_img' . $i], 'medium' ); } ?></div>
  		    		<div class="button_area">
                <input type="button" value="<?php _e( 'Select Image', 'tcd-w' ); ?>" class="cfmf-select-img button">
                <input type="button" value="<?php _e( 'Remove Image', 'tcd-w' ); ?>" class="cfmf-delete-img button <?php if ( ! $dp_options['hero_header_img' . $i] ) { echo 'hidden'; } ?>">
  		    		</div>
  		   		</div>
  		  	</div>
    		  <h4 class="theme_option_headline2"><?php _e( 'Effect type of the background image', 'tcd-w' ); ?></h4>
          <?php foreach ( $hero_header_effect_type_options as $option ) : ?>
          <p><label><input type="radio" name="dp_options[hero_header_effect_type<?php echo $i; ?>]" value="<?php echo esc_attr( $option['value'] ); ?>" <?php checked( $option['value'], $dp_options['hero_header_effect_type' . $i] ); ?>> <?php echo esc_html( $option['label'] ); ?></label></p>
          <?php endforeach; ?>
  		  </div>
				<div id="hero_header_type<?php echo $i; ?>_type2"<?php if ( 'type2' !== $dp_options['hero_header_type' . $i] ) { echo ' style="display: none;"'; } ?>>
					<h4 class="theme_option_headline2"><?php _e( 'Video file', 'tcd-w' ); ?></h4>
					<p><?php _e( 'Please upload MP4 format file.', 'tcd-w' ); ?></p>
					<div class="image_box cf">
						<div class="cf cf_media_field hide-if-no-js video">
							<input type="hidden" value="<?php echo esc_attr( $dp_options['hero_header_video' . $i] ); ?>" id="hero_header_video<?php echo $i; ?>" name="dp_options[hero_header_video<?php echo $i; ?>]" class="cf_media_id">
							<div class="preview_field preview_field_video">
								<?php if ( $dp_options['hero_header_video' . $i] ) : ?>
								<h5><?php _e( 'Uploaded MP4 file', 'tcd-w' ); ?></h5>
          			<p><?php echo esc_html( wp_get_attachment_url( $dp_options['hero_header_video' . $i] ) ); ?></p>
								<?php endif; ?>
         			</div>
         			<div class="button_area">
          			<input type="button" value="<?php _e( 'Select MP4 file', 'tcd-w' ); ?>" class="cfmf-select-video button">
          			<input type="button" value="<?php _e( 'Remove MP4 file', 'tcd-w' ); ?>" class="cfmf-delete-video button <?php if ( ! $dp_options['hero_header_video' . $i] ) { echo 'hidden'; }; ?>">
         			</div>
        		</div>
       		</div>
				</div>
				<div id="hero_header_type<?php echo $i; ?>_type3"<?php if ( 'type3' !== $dp_options['hero_header_type' . $i] ) { echo ' style="display: none;"'; } ?>>
					<h4 class="theme_option_headline2"><?php _e( 'YouTube video ID', 'tcd-w' ); ?></h4>
					<p><?php _e( 'Please enter video ID of YouTube.', 'tcd-w' ); ?></p>
					<p><input type="text" class="large-text" name="dp_options[hero_header_youtube<?php echo $i; ?>]" value="<?php echo esc_attr( $dp_options['hero_header_youtube' . $i] ); ?>"></p>
				</div>
        <input type="submit" class="button-ml ajax_button" value="<?php echo __( 'Save Changes', 'tcd-w' ); ?>">
     	</div>
    </div>
		<?php endfor; ?>
    <h4 class="theme_option_headline2"><?php _e( 'Arrow settings', 'tcd-w' ); ?></h4>
    <p><?php _e( 'This arrow is displayed at the bottom of the hero header.', 'tcd-w' ); ?></p>
    <p><?php _e( 'Font color', 'tcd-w' ); ?> <input type="text" class="c-color-picker" name="dp_options[hero_header_arrow_color]" data-default-color="<?php echo esc_attr( $dp_default_options['hero_header_arrow_color'] ); ?>" value="<?php echo esc_attr( $dp_options['hero_header_arrow_color'] ); ?>"></p>
    <h4 class="theme_option_headline2"><?php _e( 'Button settings', 'tcd-w' ); ?></h4>
    <p><?php _e( 'These buttons are displayed at the right side of the hero header.', 'tcd-w' ); ?></p>
    <p><label><?php _e( 'Font size', 'tcd-w' ); ?> <input type="number" min="1" step="1" name="dp_options[hero_header_btn_font_size]" value="<?php echo esc_html( $dp_options['hero_header_btn_font_size'] ); ?>" class="tiny-text"> px</label></p>
    <?php for ( $i = 1; $i <= 3; $i++ ) : ?>
		<div class="sub_box cf"> 
      <h3 class="theme_option_subbox_headline"><?php _e( 'Button', 'tcd-w' ); ?><?php echo $i; ?></h3>
    	<div class="sub_box_content">
    		<h4 class="theme_option_headline2"><?php _e( 'Label', 'tcd-w' ); ?></h4>
        <input type="text" class="regular-text" name="dp_options[hero_header_btn_label<?php echo $i; ?>]" value="<?php echo esc_attr( $dp_options['hero_header_btn_label' . $i] ); ?>">
    		<h4 class="theme_option_headline2"><?php _e( 'Link URL', 'tcd-w' ); ?></h4>
        <input type="text" class="regular-text" name="dp_options[hero_header_btn_url<?php echo $i; ?>]" value="<?php echo esc_attr( $dp_options['hero_header_btn_url' . $i] ); ?>">
        <p><label><input type="checkbox" name="dp_options[hero_header_btn_target<?php echo $i; ?>]" value="1" <?php checked( 1, $dp_options['hero_header_btn_target' . $i] ); ?>> <?php _e( 'Open with new window', 'tcd-w' ); ?></label></p>
    		<h4 class="theme_option_headline2"><?php _e( 'Color settings', 'tcd-w' ); ?></h4>
        <p><?php _e( 'Font color', 'tcd-w' ); ?> <input type="text" class="c-color-picker" name="dp_options[hero_header_btn_color<?php echo $i; ?>]" value="<?php echo esc_attr( $dp_options['hero_header_btn_color' . $i] ); ?>" data-default-color="<?php echo esc_attr( $dp_default_options['hero_header_btn_color' . $i] ); ?>"></p>
        <p><?php _e( 'Background color', 'tcd-w' ); ?> <input type="text" class="c-color-picker" name="dp_options[hero_header_btn_bg<?php echo $i; ?>]" value="<?php echo esc_attr( $dp_options['hero_header_btn_bg' . $i] ); ?>" data-default-color="<?php echo esc_attr( $dp_default_options['hero_header_btn_bg' . $i] ); ?>"></p>
        <p><?php _e( 'Font color on hover', 'tcd-w' ); ?> <input type="text" class="c-color-picker" name="dp_options[hero_header_btn_color_hover<?php echo $i; ?>]" value="<?php echo esc_attr( $dp_options['hero_header_btn_color_hover' . $i] ); ?>" data-default-color="<?php echo esc_attr( $dp_default_options['hero_header_btn_color_hover' . $i] ); ?>"></p>
        <p><?php _e( 'Background color on hover', 'tcd-w' ); ?> <input type="text" class="c-color-picker" name="dp_options[hero_header_btn_bg_hover<?php echo $i; ?>]" value="<?php echo esc_attr( $dp_options['hero_header_btn_bg_hover' . $i] ); ?>" data-default-color="<?php echo esc_attr( $dp_default_options['hero_header_btn_bg_hover' . $i] ); ?>"></p>
        <input type="submit" class="button-ml ajax_button" value="<?php echo __( 'Save Changes', 'tcd-w' ); ?>">
      </div>
		</div><!-- .sub_box END -->
    <?php endfor; ?>
    <input type="submit" class="button-ml ajax_button" value="<?php echo __( 'Save Changes', 'tcd-w' ); ?>">
	</div>
	<?php // Contents builder ?>
  <div class="theme_option_field cf index_cb_data">
  	<h3 class="theme_option_headline"><?php _e( 'Contents Builder', 'tcd-w' ); ?></h3>
    <div class="theme_option_message"><?php echo __( '<p>You can build contents freely with this function.</p><p>FIRST STEP: Click Add content button.<br />SECOND STEP: Select content from dropdown menu to show on each column.</p><p>You can change row by dragging MOVE button and you can delete row by clicking DELETE button.</p>', 'tcd-w' ); ?></div>
    <div id="contents_builder_wrap">
    	<div id="contents_builder" data-delete-confirm="<?php _e( 'Are you sure you want to delete this content?', 'tcd-w' ); ?>">
      <?php
      if ( ! empty( $dp_options['contents_builder'] ) ) :
      	foreach( $dp_options['contents_builder'] as $key => $content ) :
        	$cb_index = 'cb_' . $key;
      ?>
      <div class="cb_row one_column">
      	<ul class="cb_button cf">
        	<li><span class="cb_move"><?php echo __( 'Move', 'tcd-w' ); ?></span></li>
        	<li><span class="cb_delete"><?php echo __( 'Delete', 'tcd-w' ); ?></span></li>
       	</ul>
       	<div class="cb_column_area cf">
        	<div class="cb_column">
         		<input type="hidden" class="cb_index" value="<?php echo $cb_index; ?>">
         		<input type="hidden" name="dp_options[contents_builder][<?php echo $cb_index; ?>][column]" value="one_column">
         		<?php the_cb_content_select( $cb_index, $content['cb_content_select'] ); ?>
         		<?php if ( ! empty( $content['cb_content_select'] ) ) the_cb_content_setting( $cb_index, $content['cb_content_select'], $content ); ?>
        </div>
       </div><!-- END .cb_column_area -->
      </div><!-- END .cb_row -->
      <?php
      	endforeach;
      endif;
      ?>
     </div><!-- END #contents_builder -->
     <div id="cb_add_row_buttton_area">
      <input type="button" value="<?php echo __( 'Add content', 'tcd-w' ); ?>" class="button-secondary add_row-one_column">
     </div>
     <p><input type="submit" class="button-ml ajax_button" value="<?php echo __( 'Save Changes', 'tcd-w' ); ?>" /></p>
    </div><!-- END #contents_builder_wrap -->
   </div><!-- END .theme_option_field -->
   <?php // コンテンツビルダー追加用 非表示 ?>
   <div id="contents_builder-clone" class="hidden">
    <div class="cb_row one_column">
     <ul class="cb_button cf">
      <li><span class="cb_move"><?php echo __( 'Move', 'tcd-w' ); ?></span></li>
      <li><span class="cb_delete"><?php echo __( 'Delete', 'tcd-w' ); ?></span></li>
     </ul>
     <div class="cb_column_area cf">
      <div class="cb_column">
       <input type="hidden" class="cb_index" value="cb_cloneindex">
       <input type="hidden" name="dp_options[contents_builder][cb_cloneindex][column]" value="one_column">
       <?php the_cb_content_select( 'cb_cloneindex' ); ?>
      </div>
     </div><!-- END .cb_column_area -->
    </div><!-- END .cb_row -->
    <?php
    // News content
    the_cb_content_setting( 'cb_cloneindex', 'news' );

    // Section content
    the_cb_content_setting( 'cb_cloneindex', 'section' );

    // Recommended plan content
    the_cb_content_setting( 'cb_cloneindex', 'recommended_plan' );

    // Blog content
    the_cb_content_setting( 'cb_cloneindex', 'blog' );

    // Wysiwyg
    the_cb_content_setting( 'cb_cloneindex', 'wysiwyg' );
    ?>
	</div><!-- END #contents_builder-clone.hidden -->
  <?php // コンテンツビルダーここまで ---------------------------------------------------------------- ?>	
</div><!-- END #tab-content3 -->
<?php
}

function add_top_theme_options_validate( $input ) {

	global $hero_header_speed_options, $hero_header_writing_type_options, $hero_header_type_options, $hero_header_effect_type_options, $cb_news_topic_type_options, $cb_news_topic_speed_options, $cb_section_type_options, $cb_section_layout_options, $writing_type_options, $cb_blog_type_options;

  if ( ! isset( $input['hero_header_speed'] ) ) $input['hero_header_speed'] = null;
  if ( ! array_key_exists( $input['hero_header_speed'], $hero_header_speed_options ) ) $input['hero_header_speed'] = null;
	for ( $i = 1; $i <= 3; $i++ ) {
 		if ( ! isset( $input['hero_header_type' . $i] ) ) $input['hero_header_type' . $i] = null;
 		if ( ! array_key_exists( $input['hero_header_type' . $i], $hero_header_type_options ) ) $input['hero_header_type' . $i] = null;
		$input['hero_header_catch' . $i] = sanitize_textarea_field( $input['hero_header_catch' . $i] );
		$input['hero_header_catch_font_size' . $i] = absint( $input['hero_header_catch_font_size' . $i] );
		$input['hero_header_desc' . $i] = sanitize_textarea_field( $input['hero_header_desc' . $i] );
		$input['hero_header_desc_font_size' . $i] = absint( $input['hero_header_desc_font_size' . $i] );
		$input['hero_header_color' . $i] = sanitize_hex_color( $input['hero_header_color' . $i] );
 		if ( ! isset( $input['hero_header_writing_type' . $i] ) ) $input['hero_header_writing_type' . $i] = 'type1';
 		if ( ! array_key_exists( $input['hero_header_writing_type' . $i], $hero_header_writing_type_options ) ) $input['hero_header_writing_type' . $i] = 'type1';
		$input['hero_header_img_sp' . $i] = absint( $input['hero_header_img_sp' . $i] );
 		if ( ! isset( $input['hero_header_effect_type' . $i] ) ) $input['hero_header_effect_type' . $i] = null;
 		if ( ! array_key_exists( $input['hero_header_effect_type' . $i], $hero_header_effect_type_options ) ) $input['hero_header_effect_type' . $i] = null;
		$input['hero_header_img' . $i] = absint( $input['hero_header_img' . $i] );
		$input['hero_header_video' . $i] = absint( $input['hero_header_video' . $i] );
		$input['hero_header_youtube' . $i] = sanitize_text_field( $input['hero_header_youtube' . $i] );
	}
	$input['hero_header_arrow_color'] = sanitize_hex_color( $input['hero_header_arrow_color'] );
	$input['hero_header_btn_font_size'] = absint( $input['hero_header_btn_font_size'] );
  for ( $i = 1; $i <= 3; $i++ ) {
	  $input['hero_header_btn_label' . $i] = sanitize_text_field( $input['hero_header_btn_label' . $i] );
	  $input['hero_header_btn_url' . $i] = esc_url_raw( $input['hero_header_btn_url' . $i] );
 	  if ( ! isset( $input['hero_header_btn_target' . $i] ) ) $input['hero_header_btn_target' . $i] = null;
    $input['hero_header_btn_target' . $i] = ( $input['hero_header_btn_target' . $i] == 1 ? 1 : 0 );
	  $input['hero_header_btn_color' . $i] = sanitize_hex_color( $input['hero_header_btn_color' . $i] );
	  $input['hero_header_btn_bg' . $i] = sanitize_hex_color( $input['hero_header_btn_bg' . $i] );
	  $input['hero_header_btn_color_hover' . $i] = sanitize_hex_color( $input['hero_header_btn_color_hover' . $i] );
	  $input['hero_header_btn_bg_hover' . $i] = sanitize_hex_color( $input['hero_header_btn_bg_hover' . $i] );
  }

  // Contents builder
 	if ( ! empty( $input['contents_builder'] ) ) {
  	$input_cb = $input['contents_builder'];
  	$input['contents_builder'] = array();

  	foreach( $input_cb as $key => $value ) {

   		// クローン用はスルー
      // テーマオプション管理機能用に0が渡されても通るように true 指定
			if ( in_array( $key, array( 'cb_cloneindex', 'cb_cloneindex2' ), true ) ) continue;

      // News
      if ( 'news' === $value['cb_content_select'] ) {

 				if ( ! isset( $value['cb_news_display'] ) ) $value['cb_news_display'] = null;
  			$value['cb_news_display'] = ( $value['cb_news_display'] == 1 ? 1 : 0 );
     		$value['cb_news_topic_headline'] = sanitize_text_field( $value['cb_news_topic_headline'] );
				if ( ! isset( $value['cb_news_topic_type'] ) ) $value['cb_news_topic_type'] = null;
     		if ( ! array_key_exists( $value['cb_news_topic_type'], $cb_news_topic_type_options ) ) $value['cb_news_topic_type'] = null;
				if ( ! isset( $value['cb_news_topic_speed'] ) ) $value['cb_news_topic_speed'] = null;
     		if ( ! array_key_exists( $value['cb_news_topic_speed'], $cb_news_topic_speed_options ) ) $value['cb_news_topic_speed'] = null;
 				if ( ! isset( $value['cb_news_display_date'] ) ) $value['cb_news_display_date'] = null;
  			$value['cb_news_display_date'] = ( $value['cb_news_display_date'] == 1 ? 1 : 0 );
     		$value['cb_news_news_headline'] = sanitize_text_field( $value['cb_news_news_headline'] );
     		$value['cb_news_btn_label'] = sanitize_text_field( $value['cb_news_btn_label'] );

      // Section
   		} elseif ( 'section' === $value['cb_content_select'] ) {

 				if ( ! isset( $value['cb_section_display'] ) ) $value['cb_section_display'] = null;
  			$value['cb_section_display'] = ( $value['cb_section_display'] == 1 ? 1 : 0 );
				if ( ! isset( $value['cb_section_type'] ) ) $value['cb_section_type'] = null;
     		if ( ! array_key_exists( $value['cb_section_type'], $cb_section_type_options ) ) $value['cb_section_type'] = null;
     		$value['cb_section_header_img'] = absint( $value['cb_section_header_img'] );
     		$value['cb_section_header_title'] = sanitize_textarea_field( $value['cb_section_header_title'] );
     		$value['cb_section_header_font_size'] = absint( $value['cb_section_header_font_size'] );
     		$value['cb_section_header_color'] = sanitize_hex_color( $value['cb_section_header_color'] );
				if ( ! isset( $value['cb_section_header_writing_type'] ) ) $value['cb_section_header_writing_type'] = null;
     		if ( ! array_key_exists( $value['cb_section_header_writing_type'], $writing_type_options ) ) $value['cb_section_header_writing_type'] = null;
     		$value['cb_section_headline'] = sanitize_text_field( $value['cb_section_headline'] );
     		$value['cb_section_headline_font_size'] = absint( $value['cb_section_headline_font_size'] );
     		$value['cb_section_headline_color'] = sanitize_hex_color( $value['cb_section_headline_color'] );
     		$value['cb_section_headline_bg'] = sanitize_hex_color( $value['cb_section_headline_bg'] );
				if ( ! isset( $value['cb_section_headline_writing_type'] ) ) $value['cb_section_headline_writing_type'] = null;
     		if ( ! array_key_exists( $value['cb_section_headline_writing_type'], $writing_type_options ) ) $value['cb_section_headline_writing_type'] = null;
				if ( ! isset( $value['cb_section_headline_layout'] ) ) $value['cb_section_headline_layout'] = null;
     		if ( ! in_array( $value['cb_section_headline_layout'], array( 'type1', 'type2' ) ) ) $value['cb_section_headline_layout'] = null;
     		$value['cb_section_desc'] = sanitize_textarea_field( $value['cb_section_desc'] );
        for ( $i = 2; $i <= 3; $i++ ) {
				  if ( ! isset( $value["cb_section_type{$i}_layout"] ) ) $value["cb_section_type{$i}_layout"] = null;
     		  if ( ! array_key_exists( $value["cb_section_type{$i}_layout"], $cb_section_layout_options ) ) $value["cb_section_type{$i}_layout"] = null;
        }
				if ( ! isset( $value['cb_section_type1_block_layout'] ) ) $value['cb_section_type1_block_layout'] = null;
     		if ( ! in_array( $value['cb_section_type1_block_layout'], array( 'type1', 'type2' ) ) ) $value['cb_section_type1_block_layout'] = null;
        for ( $i = 1; $i <= 6; $i++ ) {
          if ( 0 === $i % 2 ) { // Text
		        $value['cb_section_type1_block_text' . $i] = sanitize_textarea_field( $value['cb_section_type1_block_text' . $i] );
		        $value['cb_section_type1_block_btn_label' . $i] = sanitize_text_field( $value['cb_section_type1_block_btn_label' . $i] );
		        $value['cb_section_type1_block_btn_url' . $i] = esc_url_raw( $value['cb_section_type1_block_btn_url' . $i] );
          } else { // Image
		        $value['cb_section_type1_block_img' . $i] = absint( $value['cb_section_type1_block_img' . $i] );
          }
        }
     		$value['cb_section_type1_block_color'] = sanitize_hex_color( $value['cb_section_type1_block_color'] );
     		$value['cb_section_type1_block_bg'] = sanitize_hex_color( $value['cb_section_type1_block_bg'] );
     		$value['cb_section_type1_block_btn_color'] = sanitize_hex_color( $value['cb_section_type1_block_btn_color'] );
     		$value['cb_section_type1_block_btn_bg'] = sanitize_hex_color( $value['cb_section_type1_block_btn_bg'] );
     		$value['cb_section_type1_block_btn_color_hover'] = sanitize_hex_color( $value['cb_section_type1_block_btn_color_hover'] );
     		$value['cb_section_type1_block_btn_bg_hover'] = sanitize_hex_color( $value['cb_section_type1_block_btn_bg_hover'] );
				if ( ! isset( $value['cb_section_type2_block_layout'] ) ) $value['cb_section_type2_block_layout'] = null;
     		if ( ! in_array( $value['cb_section_type2_block_layout'], array( 'type1', 'type2' ) ) ) $value['cb_section_type2_block_layout'] = null;
        for ( $i = 1; $i <= 4; $i++ ) {
		      $value['cb_section_type2_block_img' . $i] = absint( $value['cb_section_type2_block_img' . $i] );
        }
     		$value['cb_section_type2_block_title'] = sanitize_textarea_field( $value['cb_section_type2_block_title'] );
     		$value['cb_section_type2_block_desc'] = sanitize_textarea_field( $value['cb_section_type2_block_desc'] );
     		$value['cb_section_type2_block_color'] = sanitize_hex_color( $value['cb_section_type2_block_color'] );
     		$value['cb_section_type2_block_bg'] = sanitize_hex_color( $value['cb_section_type2_block_bg'] );
				if ( ! isset( $value['cb_section_type3_block_layout'] ) ) $value['cb_section_type3_block_layout'] = null;
     		if ( ! in_array( $value['cb_section_type3_block_layout'], array( 'type1', 'type2' ) ) ) $value['cb_section_type3_block_layout'] = null;
        for ( $i = 1; $i <= 3; $i++ ) {
		      $value['cb_section_type3_block_img' . $i] = absint( $value['cb_section_type3_block_img' . $i] );
        }
     		$value['cb_section_type3_block_title'] = sanitize_textarea_field( $value['cb_section_type3_block_title'] );
     		$value['cb_section_type3_block_desc'] = sanitize_textarea_field( $value['cb_section_type3_block_desc'] );
     		$value['cb_section_type3_block_color'] = sanitize_hex_color( $value['cb_section_type3_block_color'] );
     		$value['cb_section_type3_block_bg'] = sanitize_hex_color( $value['cb_section_type3_block_bg'] );
     		$value['cb_section_btn_label'] = sanitize_textarea_field( $value['cb_section_btn_label'] );
     		$value['cb_section_btn_url'] = esc_url_raw( $value['cb_section_btn_url'] );

      // Recommended plan
   		} elseif ( 'recommended_plan' === $value['cb_content_select'] ) {

 				if ( ! isset( $value['cb_recommended_plan_display'] ) ) $value['cb_recommended_plan_display'] = null;
  			$value['cb_recommended_plan_display'] = ( $value['cb_recommended_plan_display'] == 1 ? 1 : 0 );
     		$value['cb_recommended_plan_img'] = absint( $value['cb_recommended_plan_img'] );
     		$value['cb_recommended_plan_header_title'] = sanitize_text_field( $value['cb_recommended_plan_header_title'] );
     		$value['cb_recommended_plan_header_color'] = sanitize_hex_color( $value['cb_recommended_plan_header_color'] );
     		$value['cb_recommended_plan_header_font_size'] = absint( $value['cb_recommended_plan_header_font_size'] );
				if ( ! isset( $value['cb_recommended_plan_header_writing_type'] ) ) $value['cb_recommended_plan_header_writing_type'] = null;
     		if ( ! array_key_exists( $value['cb_recommended_plan_header_writing_type'], $writing_type_options ) ) $value['cb_recommended_plan_header_writing_type'] = null;
     		$value['cb_recommended_plan_btn_label'] = sanitize_text_field( $value['cb_recommended_plan_btn_label'] );

      // Blog
   		} elseif ( 'blog' === $value['cb_content_select'] ) {

 				if ( ! isset( $value['cb_blog_display'] ) ) $value['cb_blog_display'] = null;
  			$value['cb_blog_display'] = ( $value['cb_blog_display'] == 1 ? 1 : 0 );
     		$value['cb_blog_headline'] = sanitize_text_field( $value['cb_blog_headline'] );
				if ( ! isset( $value['cb_blog_type'] ) ) $value['cb_blog_type'] = null;
     		if ( ! array_key_exists( $value['cb_blog_type'], $cb_blog_type_options ) ) $value['cb_blog_type'] = null;
     		$value['cb_blog_btn_label'] = sanitize_text_field( $value['cb_blog_btn_label'] );

      // Wysiwyg
   		} elseif ( 'wysiwyg' == $value['cb_content_select'] ) {

 				if ( ! isset( $value['cb_wysiwyg_display'] ) ) $value['cb_wysiwyg_display'] = null;
  			$value['cb_wysiwyg_display'] = ( $value['cb_wysiwyg_display'] == 1 ? 1 : 0 );

   		}

   		$input['contents_builder'][] = $value;

  		}
      
	 } // コンテンツビルダーループここまで

	return $input;

}

/**
 * コンテンツビルダー用 コンテンツ選択プルダウン
 */
function the_cb_content_select( $cb_index = 'cb_cloneindex', $selected = null ) {
	$cb_content_select = array(
    'news' => __( 'News content', 'tcd-w' ),
    'section' => __( 'Section content', 'tcd-w' ),
    'recommended_plan' => __( 'Recommended plan content', 'tcd-w' ),
    'blog' => __( 'Blog content', 'tcd-w' ),
		'wysiwyg' => __( 'WYSIWYG Editor', 'tcd-w' )
	);

	if ( $selected && isset( $cb_content_select[$selected] ) ) {
		$add_class = ' hidden';
	} else {
		$add_class = '';
	}

	$out = '<select name="dp_options[contents_builder][' . esc_attr( $cb_index ) . '][cb_content_select]" class="cb_content_select' . $add_class . '">';
	$out .= '<option value="" style="padding-right: 10px;">' . __( 'Choose the content', 'tcd-w' ) . '</option>';

	foreach( $cb_content_select as $key => $value ) {

    $attr = $key === $selected ? ' selected="selected"' : '';
		$out .= '<option value="' . esc_attr( $key ) . '"' . $attr . ' style="padding-right: 10px;">' . esc_html( $value ) . '</option>';

	}

	$out .= '</select>';

	echo $out; 
}

/**
 * コンテンツビルダー用 コンテンツ設定 ■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■
 */
function the_cb_content_setting( $cb_index = 'cb_cloneindex', $cb_content_select = null, $value = array() ) {
	global $writing_type_options, $cb_news_topic_type_options, $cb_news_topic_speed_options, $cb_section_type_options, $cb_section_layout_options, $cb_blog_type_options;
  ?>
	<div class="cb_content_wrap cf <?php echo esc_attr( $cb_content_select ); ?>">
	<?php
  // News
	if ( 'news' ==  $cb_content_select ) {

		if ( ! isset( $value['cb_news_display'] ) ) {
			$value['cb_news_display'] = null;
		}
		if ( ! isset( $value['cb_news_display_date'] ) ) {
			$value['cb_news_display_date'] = null;
		}
		if ( ! isset( $value['cb_news_topic_headline'] ) ) {
			$value['cb_news_topic_headline'] = '';
		}
		if ( ! isset( $value['cb_news_topic_type'] ) ) {
			$value['cb_news_topic_type'] = 'news';
		}
		if ( ! isset( $value['cb_news_topic_speed'] ) ) {
			$value['cb_news_topic_speed'] = 5;
		}
		if ( ! isset( $value['cb_news_news_headline'] ) ) {
			$value['cb_news_news_headline'] = '';
		}
		if ( ! isset( $value['cb_news_btn_label'] ) ) {
			$value['cb_news_btn_label'] = '';
		}
  ?>
    <h3 class="cb_content_headline"><span><?php _e( 'News content', 'tcd-w' ); ?></span><a href="#"><?php _e( 'Open', 'tcd-w' ); ?></a></h3>
    <div class="cb_content">
			<p><label><input name="dp_options[contents_builder][<?php echo $cb_index; ?>][cb_news_display]" type="checkbox" value="1" <?php checked( 1, $value['cb_news_display'] ); ?>><?php _e( 'Display this content', 'tcd-w' ); ?></label></p>
      <?php if ( preg_match( '/^cb_\d+$/', $cb_index ) ) : ?>
      <div class="theme_option_message">
        <p><?php _e( 'To make it a link to jump to this content, set URL of hero header buttons to the following.', 'tcd-w' ); ?></p>
        <p><?php _e( 'ID:', 'tcd-w' ); ?> <input type="text" readonly="readonly" value="#<?php echo $cb_index; ?>"></p>
      </div>
      <?php endif; ?>
  		<h4 class="theme_option_headline2"><?php _e( 'Topics settings', 'tcd-w' ); ?></h4>
      <p><?php _e( 'Display topics slider which display posts registerd as topic.', 'tcd-w' ); ?></p>
      <p><?php _e( 'How to set a post as topics: please check in checkbox says "Display this post as topic." on the right side of the post edit screen.', 'tcd-w' ); ?></p>
      <p>
      <p><label><?php _e( 'Headline', 'tcd-w' ); ?> <input type="text" name="dp_options[contents_builder][<?php echo $cb_index; ?>][cb_news_topic_headline]" class="regular-text" value="<?php echo esc_attr( $value['cb_news_topic_headline'] ); ?>"></label></p>
      <p>
        <?php _e( 'Post type', 'tcd-w' ); ?>
        <select name="dp_options[contents_builder][<?php echo $cb_index; ?>][cb_news_topic_type]">
        <?php foreach ( $cb_news_topic_type_options as $option ) : ?>
        <option value="<?php echo esc_attr( $option['value'] ); ?>" <?php selected( $option['value'], $value['cb_news_topic_type'] ); ?>><?php echo esc_html( $option['label'] ); ?></option>
        <?php endforeach; ?>
        </select>
      </p>
      <p>
        <?php _e( 'Autoplay speed', 'tcd-w' ); ?>
        <select name="dp_options[contents_builder][<?php echo $cb_index; ?>][cb_news_topic_speed]">
        <?php foreach ( $cb_news_topic_speed_options as $option ) : ?>
        <option value="<?php echo esc_attr( $option['value'] ); ?>" <?php selected( $option['value'], $value['cb_news_topic_speed'] ); ?>><?php echo esc_html( $option['label'] ); ?></option>
        <?php endforeach; ?>
        </select>
      </p>
      <p><label><input type="checkbox" name="dp_options[contents_builder][<?php echo $cb_index; ?>][cb_news_display_date]" value="1" <?php checked( 1, $value['cb_news_display_date'] ); ?>> <?php _e( 'Display date', 'tcd-w' ); ?></label></p> 
  		<h4 class="theme_option_headline2"><?php _e( 'News settings', 'tcd-w' ); ?></h4>
      <p><?php _e( 'Display news list.', 'tcd-w' ); ?></p>
      <p><label><?php _e( 'Headline', 'tcd-w' ); ?> <input type="text" name="dp_options[contents_builder][<?php echo $cb_index; ?>][cb_news_news_headline]" class="regular-text" value="<?php echo esc_attr( $value['cb_news_news_headline'] ); ?>"></label></p>
  		<h4 class="theme_option_headline2"><?php _e( 'Label of archive link', 'tcd-w' ); ?></h4>
  		<input type="text" class="regular-text" name="dp_options[contents_builder][<?php echo $cb_index; ?>][cb_news_btn_label]" value="<?php echo esc_attr( $value['cb_news_btn_label'] ); ?>">

  <?php
  // Section
  } elseif ( 'section' === $cb_content_select ) {

		if ( ! isset( $value['cb_section_display'] ) ) {
			$value['cb_section_display'] = null;
		}
		if ( ! isset( $value['cb_section_type'] ) ) {
			$value['cb_section_type'] = 'type1';
		}
		if ( ! isset( $value['cb_section_header_img'] ) ) {
			$value['cb_section_header_img'] = '';
		}
		if ( ! isset( $value['cb_section_header_title'] ) ) {
			$value['cb_section_header_title'] = '';
		}
		if ( ! isset( $value['cb_section_header_font_size'] ) ) {
			$value['cb_section_header_font_size'] = 40;
		}
		if ( ! isset( $value['cb_section_header_color'] ) ) {
			$value['cb_section_header_color'] = '#ffffff';
		}
		if ( ! isset( $value['cb_section_header_writing_type'] ) ) {
			$value['cb_section_header_writing_type'] = 'type1';
		}
		if ( ! isset( $value['cb_section_headline'] ) ) {
			$value['cb_section_headline'] = '';
		}
		if ( ! isset( $value['cb_section_headline_font_size'] ) ) {
			$value['cb_section_headline_font_size'] = 36;
		}
		if ( ! isset( $value['cb_section_headline_color'] ) ) {
			$value['cb_section_headline_color'] = '#ffffff';
		}
		if ( ! isset( $value['cb_section_headline_bg'] ) ) {
			$value['cb_section_headline_bg'] = '#660000';
		}
		if ( ! isset( $value['cb_section_headline_writing_type'] ) ) {
			$value['cb_section_headline_writing_type'] = 'type1';
		}
		if ( ! isset( $value['cb_section_headline_layout'] ) ) {
			$value['cb_section_headline_layout'] = 'type1';
		}
		if ( ! isset( $value['cb_section_desc'] ) ) {
			$value['cb_section_desc'] = '';
		}
		if ( ! isset( $value['cb_section_type2_layout'] ) ) {
			$value['cb_section_type2_layout'] = 'type1';
		}
		if ( ! isset( $value['cb_section_type3_layout'] ) ) {
			$value['cb_section_type3_layout'] = 'type2';
		}
		if ( ! isset( $value['cb_section_type1_block_layout'] ) ) {
			$value['cb_section_type1_block_layout'] = 'type1';
		}
    for ( $i = 1; $i <= 6; $i++ ) {
      if ( 0 === $i % 2 ) { // Text
		    if ( ! isset( $value['cb_section_type1_block_text' . $i] ) ) {
		    	$value['cb_section_type1_block_text' . $i] = '';
		    }
		    if ( ! isset( $value['cb_section_type1_block_btn_label' . $i] ) ) {
		    	$value['cb_section_type1_block_btn_label' . $i] = '';
		    }
		    if ( ! isset( $value['cb_section_type1_block_btn_url' . $i] ) ) {
		    	$value['cb_section_type1_block_btn_url' . $i] = '';
		    }
      } else { // Image
		    if ( ! isset( $value['cb_section_type1_block_img' . $i] ) ) {
		    	$value['cb_section_type1_block_img' . $i] = '';
		    }
      }
    }
		if ( ! isset( $value['cb_section_type1_block_color'] ) ) {
			$value['cb_section_type1_block_color'] = '#ffffff';
		}
		if ( ! isset( $value['cb_section_type1_block_bg'] ) ) {
			$value['cb_section_type1_block_bg'] = '#000000';
		}
		if ( ! isset( $value['cb_section_type1_block_btn_color'] ) ) {
			$value['cb_section_type1_block_btn_color'] = '#ffffff';
		}
		if ( ! isset( $value['cb_section_type1_block_btn_bg'] ) ) {
			$value['cb_section_type1_block_btn_bg'] = '#333';
		}
		if ( ! isset( $value['cb_section_type1_block_btn_color_hover'] ) ) {
			$value['cb_section_type1_block_btn_color_hover'] = '#ffffff';
		}
		if ( ! isset( $value['cb_section_type1_block_btn_bg_hover'] ) ) {
			$value['cb_section_type1_block_btn_bg_hover'] = '#660000';
		}

		if ( ! isset( $value['cb_section_type2_block_layout'] ) ) {
			$value['cb_section_type2_block_layout'] = 'type1';
		}
    for ( $i = 1; $i <= 4; $i++ ) {
		  if ( ! isset( $value['cb_section_type2_block_img' . $i] ) ) {
		  	$value['cb_section_type2_block_img' . $i] = '';
		  }
    }
		if ( ! isset( $value['cb_section_type2_block_title'] ) ) {
			$value['cb_section_type2_block_title'] = '';
		}
		if ( ! isset( $value['cb_section_type2_block_desc'] ) ) {
			$value['cb_section_type2_block_desc'] = '';
		}
		if ( ! isset( $value['cb_section_type2_block_color'] ) ) {
			$value['cb_section_type2_block_color'] = '#000000';
		}
		if ( ! isset( $value['cb_section_type2_block_bg'] ) ) {
			$value['cb_section_type2_block_bg'] = '#f4f1ed';
		}

		if ( ! isset( $value['cb_section_type3_block_layout'] ) ) {
			$value['cb_section_type3_block_layout'] = 'type1';
		}
    for ( $i = 1; $i <= 3; $i++ ) {
		  if ( ! isset( $value['cb_section_type3_block_img' . $i] ) ) {
		  	$value['cb_section_type3_block_img' . $i] = '';
		  }
    }
		if ( ! isset( $value['cb_section_type3_block_title'] ) ) {
			$value['cb_section_type3_block_title'] = '';
		}
		if ( ! isset( $value['cb_section_type3_block_desc'] ) ) {
			$value['cb_section_type3_block_desc'] = '';
		}
		if ( ! isset( $value['cb_section_type3_block_color'] ) ) {
			$value['cb_section_type3_block_color'] = '#000000';
		}
		if ( ! isset( $value['cb_section_type3_block_bg'] ) ) {
			$value['cb_section_type3_block_bg'] = '#f4f1ed';
		}

		if ( ! isset( $value['cb_section_btn_label'] ) ) {
			$value['cb_section_btn_label'] = '';
		}
		if ( ! isset( $value['cb_section_btn_url'] ) ) {
			$value['cb_section_btn_url'] = '';
		}
  ?>
    <h3 class="cb_content_headline"><span><?php _e( 'Section content', 'tcd-w' ); ?></span><a href="#"><?php _e( 'Open', 'tcd-w' ); ?></a></h3>
    <div class="cb_content">
			<p><label><input name="dp_options[contents_builder][<?php echo $cb_index; ?>][cb_section_display]" type="checkbox" value="1" <?php checked( 1, $value['cb_section_display'] ); ?>><?php _e( 'Display this content', 'tcd-w' ); ?></label></p>
      <?php if ( preg_match( '/^cb_\d+$/', $cb_index ) ) : ?>
      <div class="theme_option_message">
        <p><?php _e( 'To make it a link to jump to this content, set URL of hero header buttons to the following.', 'tcd-w' ); ?></p>
        <p><?php _e( 'ID:', 'tcd-w' ); ?> <input type="text" readonly="readonly" value="#<?php echo $cb_index; ?>"></p>
      </div>
      <?php endif; ?>
  		<h4 class="theme_option_headline2"><?php _e( 'Content type', 'tcd-w' ); ?></h4>
      <ul class="section-type-list">
        <?php foreach ( $cb_section_type_options as $key => $option ) : ?>
        <li>
          <figure>
            <img src="<?php echo get_template_directory_uri() . '/admin/assets/images/cb-section-' . esc_attr( $key ) . '.jpg'; ?>" alt="">
          </figure>
          <label><input type="radio" name="dp_options[contents_builder][<?php echo $cb_index; ?>][cb_section_type]" value="<?php echo esc_attr( $option['value'] ); ?>" <?php checked( $option['value'], $value['cb_section_type'] ); ?>> <?php echo esc_html( $option['label'] ); ?></label>
        </li>
        <?php endforeach; ?>
      </ul>
  		<h4 class="theme_option_headline2"><?php _e( 'Background image of the header', 'tcd-w' ); ?></h4>
      <p><?php _e( 'Recommended size: width 1450px, height 500px', 'tcd-w' ); ?></p>
    	<div class="image_box cf">
    		<div class="cf cf_media_field hide-if-no-js">
    			<input type="hidden" value="<?php echo esc_attr( $value['cb_section_header_img'] ); ?>" id="cb_section_header_img-<?php echo $cb_index; ?>" name="dp_options[contents_builder][<?php echo $cb_index; ?>][cb_section_header_img]" class="cf_media_id">
    			<div class="preview_field"><?php if ( $value['cb_section_header_img'] ) { echo wp_get_attachment_image( $value['cb_section_header_img'], 'medium' ); } ?></div>
    			<div class="button_area">
    	 			<input type="button" value="<?php _e( 'Select Image', 'tcd-w' ); ?>" class="cfmf-select-img button">
    	 			<input type="button" value="<?php _e( 'Remove Image', 'tcd-w' ); ?>" class="cfmf-delete-img button <?php if ( ! $value['cb_section_header_img'] ) { echo 'hidden'; } ?>">
    			</div>
    		</div>
    	</div>
  		<h4 class="theme_option_headline2"><?php _e( 'Title of the header', 'tcd-w' ); ?></h4>
      <textarea class="large-text" name="dp_options[contents_builder][<?php echo $cb_index; ?>][cb_section_header_title]"><?php echo esc_textarea( $value['cb_section_header_title'] ); ?></textarea>
      <p><label><?php _e( 'Font size', 'tcd-w' ); ?> <input type="number" min="1" step="1" class="tiny-text" name="dp_options[contents_builder][<?php echo $cb_index; ?>][cb_section_header_font_size]" value="<?php echo esc_attr( $value['cb_section_header_font_size'] ); ?>"> px</label></p>
      <p><?php _e( 'Font color', 'tcd-w' ); ?> <input type="text" name="dp_options[contents_builder][<?php echo $cb_index; ?>][cb_section_header_color]" value="<?php echo esc_attr( $value['cb_section_header_color'] ); ?>" data-default-color="#ffffff" class="<?php echo preg_match( '/^cb_\d+$/', $cb_index ) ? 'c-color-picker' : 'cb-color-picker'; ?>"></p>
      <?php foreach ( $writing_type_options as $option ) : ?>
      <p><label><input type="radio" name="dp_options[contents_builder][<?php echo $cb_index; ?>][cb_section_header_writing_type]" value="<?php echo esc_attr( $option['value'] ); ?>" <?php checked( $option['value'], $value['cb_section_header_writing_type'] ); ?>> <?php echo esc_html( $option['label'] ); ?></label></p>
      <?php endforeach; ?>

  		<h4 class="theme_option_headline2"><?php _e( 'Headline', 'tcd-w' ); ?></h4>
      <input type="text" class="regular-text" name="dp_options[contents_builder][<?php echo $cb_index; ?>][cb_section_headline]" value="<?php echo esc_attr( $value['cb_section_headline'] ); ?>">
      <p><label><?php _e( 'Font size', 'tcd-w' ); ?> <input type="number" min="1" step="1" class="tiny-text" name="dp_options[contents_builder][<?php echo $cb_index; ?>][cb_section_headline_font_size]" value="<?php echo esc_attr( $value['cb_section_headline_font_size'] ); ?>"> px</label></p>
      <p><?php _e( 'Font color', 'tcd-w' ); ?> <input type="text" name="dp_options[contents_builder][<?php echo $cb_index; ?>][cb_section_headline_color]" value="<?php echo esc_attr( $value['cb_section_headline_color'] ); ?>" data-default-color="#ffffff" class="<?php echo preg_match( '/^cb_\d+$/', $cb_index ) ? 'c-color-picker' : 'cb-color-picker'; ?>"></p>
      <p><?php _e( 'Background color', 'tcd-w' ); ?> <input type="text" name="dp_options[contents_builder][<?php echo $cb_index; ?>][cb_section_headline_bg]" value="<?php echo esc_attr( $value['cb_section_headline_bg'] ); ?>" data-default-color="#660000" class="<?php echo preg_match( '/^cb_\d+$/', $cb_index ) ? 'c-color-picker' : 'cb-color-picker'; ?>"></p>
      <?php foreach ( $writing_type_options as $option ) : ?>
      <p><label><input type="radio" name="dp_options[contents_builder][<?php echo $cb_index; ?>][cb_section_headline_writing_type]" value="<?php echo esc_attr( $option['value'] ); ?>" <?php checked( $option['value'], $value['cb_section_headline_writing_type'] ); ?>> <?php echo esc_html( $option['label'] ); ?></label></p>
      <?php endforeach; ?>
      <div class="cb_section_type_type1"<?php if ( 'type1' !== $value['cb_section_type'] ) { echo ' style="display: none;"'; } ?>>
  		  <h4 class="theme_option_headline2"><?php _e( 'Layout of the headline', 'tcd-w' ); ?></h4>
        <p><label><input type="radio" name="dp_options[contents_builder][<?php echo $cb_index; ?>][cb_section_headline_layout]"value="type1" <?php checked( 'type1', $value['cb_section_headline_layout'] ); ?>> <?php _e( 'Left', 'tcd-w' ); ?></label></p>
        <p><label><input type="radio" name="dp_options[contents_builder][<?php echo $cb_index; ?>][cb_section_headline_layout]"value="type2" <?php checked( 'type2', $value['cb_section_headline_layout'] ); ?>> <?php _e( 'Right', 'tcd-w' ); ?></label></p>
      </div>
  		<h4 class="theme_option_headline2"><?php _e( 'Description', 'tcd-w' ); ?></h4>
      <textarea class="large-text" rows="4" name="dp_options[contents_builder][<?php echo $cb_index; ?>][cb_section_desc]"><?php echo esc_textarea( $value['cb_section_desc'] ); ?></textarea>
      <div class="cb_section_type_type2" <?php if ( 'type2' !== $value['cb_section_type'] ) { echo ' style="display: none;"'; } ?>>
  		  <h4 class="theme_option_headline2"><?php _e( 'Layout', 'tcd-w' ); ?></h4>
        <?php foreach ( $cb_section_layout_options as $option ) : ?>
        <p><label><input type="radio" name="dp_options[contents_builder][<?php echo $cb_index; ?>][cb_section_type2_layout]" value="<?php echo esc_attr( $option['value'] ); ?>" <?php checked( $option['value'], $value['cb_section_type2_layout'] ); ?>> <?php echo esc_html( $option['label'] ); ?></label></p>
        <?php endforeach; ?>
      </div>
      <div class="cb_section_type_type3" <?php if ( 'type3' !== $value['cb_section_type'] ) { echo ' style="display: none;"'; } ?>>
  		  <h4 class="theme_option_headline2"><?php _e( 'Layout', 'tcd-w' ); ?></h4>
        <?php foreach ( $cb_section_layout_options as $option ) : ?>
        <p><label><input type="radio" name="dp_options[contents_builder][<?php echo $cb_index; ?>][cb_section_type3_layout]" value="<?php echo esc_attr( $option['value'] ); ?>" <?php checked( $option['value'], $value['cb_section_type3_layout'] ); ?>> <?php echo esc_html( $option['label'] ); ?></label></p>
        <?php endforeach; ?>
      </div>
  		<h4 class="theme_option_headline2"><?php _e( 'Block contents settings', 'tcd-w' ); ?></h4>
      <div class="cb_section_type_type1"<?php if ( 'type1' !== $value['cb_section_type'] ) { echo ' style="display: none;"'; } ?>>
        <table class="block-contents-table">
          <tr class="block-contents-table__row">
            <th class="block-contents-table__header"><?php _e( 'Layout', 'tcd-w' ); ?></th>
            <td class="u-center">
              <figure>
                <img src="<?php echo get_template_directory_uri(); ?>/admin/assets/images/cb-section-type1-A.png" alt="">
              </figure>
              <p><label><input type="radio" name="dp_options[contents_builder][<?php echo $cb_index; ?>][cb_section_type1_block_layout]" value="type1" <?php checked( 'type1', $value['cb_section_type1_block_layout'] ); ?>> <?php _e( 'Type1', 'tcd-w' ); ?></label></p>
            </td>
            <td class="u-center">
              <figure>
                <img src="<?php echo get_template_directory_uri(); ?>/admin/assets/images/cb-section-type1-B.png" alt="">
              </figure>
              <p><label><input type="radio" name="dp_options[contents_builder][<?php echo $cb_index; ?>][cb_section_type1_block_layout]" value="type2" <?php checked( 'type2', $value['cb_section_type1_block_layout'] ); ?>> <?php _e( 'Type2', 'tcd-w' ); ?></label></p>
            </td>
          </tr>
          <?php
          for ( $i = 1; $i <= 6; $i++ ) : 
            if ( 0 === $i % 2 ) : // Text and button 
          ?>
          <tr class="block-contents-table__row">
            <th rowspan="3" class="block-contents-table__header"><?php echo $i; ?></td>
            <td><?php _e( 'Text', 'tcd-w' ); ?></td>
            <td><textarea rows="4" class="large-text" name="dp_options[contents_builder][<?php echo $cb_index; ?>][cb_section_type1_block_text<?php echo $i; ?>]"><?php echo esc_textarea( $value['cb_section_type1_block_text' . $i] ); ?></textarea></td>
          </tr>
          <tr class="block-contents-table__row">
            <td><?php _e( 'Button label', 'tcd-w' ); ?></td>
            <td><input type="text" class="large-text" name="dp_options[contents_builder][<?php echo $cb_index; ?>][cb_section_type1_block_btn_label<?php echo $i; ?>]" value="<?php echo esc_attr( $value['cb_section_type1_block_btn_label' . $i] ); ?>"></label></p></td>
          </tr>
          <tr class="block-contents-table__row">
            <td><?php _e( 'Button URL', 'tcd-w' ); ?></td>
            <td><input type="text" class="large-text" name="dp_options[contents_builder][<?php echo $cb_index; ?>][cb_section_type1_block_btn_url<?php echo $i; ?>]" value="<?php echo esc_attr( $value['cb_section_type1_block_btn_url' . $i] ); ?>"></label></p></td>
          </tr>
          <?php else : // Image ?>
          <tr class="block-contents-table__row">
            <th class="block-contents-table__header"><?php echo $i; ?></td>
            <td>
              <p><?php _e( 'Recommended size: width: 600px, height: 600px', 'tcd-w' ); ?></p>
            </td>
            <td>
    	        <div class="image_box cf">
    	        	<div class="cf cf_media_field hide-if-no-js">
    	        		<input type="hidden" value="<?php echo esc_attr( $value['cb_section_type1_block_img' . $i] ); ?>" id="cb_section_type1_block_img<?php echo $i; ?>_<?php echo $cb_index; ?>" name="dp_options[contents_builder][<?php echo $cb_index; ?>][cb_section_type1_block_img<?php echo $i; ?>]" class="cf_media_id">
    	        		<div class="preview_field"><?php if ( $value['cb_section_type1_block_img' . $i] ) { echo wp_get_attachment_image( $value['cb_section_type1_block_img' . $i], 'medium' ); } ?></div>
    	        		<div class="button_area u-center">
    	         			<input type="button" value="<?php _e( 'Select Image', 'tcd-w' ); ?>" class="cfmf-select-img button">
    	         			<input type="button" value="<?php _e( 'Remove Image', 'tcd-w' ); ?>" class="cfmf-delete-img button <?php if ( ! $value['cb_section_type1_block_img' . $i] ) { echo 'hidden'; } ?>">
    	        		</div>
    	        	</div>
    	        </div>
            </td>
          </tr>
          <?php
            endif; 
          endfor;
          ?>
        </table>
        <p><?php _e( 'Font color', 'tcd-w' ); ?> <input type="text" name="dp_options[contents_builder][<?php echo $cb_index; ?>][cb_section_type1_block_color]" value="<?php echo esc_attr( $value['cb_section_type1_block_color'] ); ?>" data-default-color="#ffffff" class="<?php echo preg_match( '/^cb_\d+$/', $cb_index ) ? 'c-color-picker' : 'cb-color-picker'; ?>"></p>
        <p><?php _e( 'Background color', 'tcd-w' ); ?> <input type="text" name="dp_options[contents_builder][<?php echo $cb_index; ?>][cb_section_type1_block_bg]" value="<?php echo esc_attr( $value['cb_section_type1_block_bg'] ); ?>" data-default-color="#000000" class="<?php echo preg_match( '/^cb_\d+$/', $cb_index ) ? 'c-color-picker' : 'cb-color-picker'; ?>"></p>
        <p><?php _e( 'Font color of the button', 'tcd-w' ); ?> <input type="text" name="dp_options[contents_builder][<?php echo $cb_index; ?>][cb_section_type1_block_btn_color]" value="<?php echo esc_attr( $value['cb_section_type1_block_btn_color'] ); ?>" data-default-color="#ffffff" class="<?php echo preg_match( '/^cb_\d+$/', $cb_index ) ? 'c-color-picker' : 'cb-color-picker'; ?>"></p>
        <p><?php _e( 'Background color of the button', 'tcd-w' ); ?> <input type="text" name="dp_options[contents_builder][<?php echo $cb_index; ?>][cb_section_type1_block_btn_bg]" value="<?php echo esc_attr( $value['cb_section_type1_block_btn_bg'] ); ?>" data-default-color="#660000" class="<?php echo preg_match( '/^cb_\d+$/', $cb_index ) ? 'c-color-picker' : 'cb-color-picker'; ?>"></p>
        <p><?php _e( 'Font color of the button on hover', 'tcd-w' ); ?> <input type="text" name="dp_options[contents_builder][<?php echo $cb_index; ?>][cb_section_type1_block_btn_color_hover]" value="<?php echo esc_attr( $value['cb_section_type1_block_btn_color_hover'] ); ?>" data-default-color="#ffffff" class="<?php echo preg_match( '/^cb_\d+$/', $cb_index ) ? 'c-color-picker' : 'cb-color-picker'; ?>"></p>
        <p><?php _e( 'Background color of the button on hover', 'tcd-w' ); ?> <input type="text" name="dp_options[contents_builder][<?php echo $cb_index; ?>][cb_section_type1_block_btn_bg_hover]" value="<?php echo esc_attr( $value['cb_section_type1_block_btn_bg_hover'] ); ?>" data-default-color="#660000" class="<?php echo preg_match( '/^cb_\d+$/', $cb_index ) ? 'c-color-picker' : 'cb-color-picker'; ?>"></p>
      </div>
      <div class="cb_section_type_type2"<?php if ( 'type2' !== $value['cb_section_type'] ) { echo ' style="display: none;"'; } ?>>
        <table class="block-contents-table">
          <tr class="block-contents-table__row">
            <th class="block-contents-table__header"><?php _e( 'Layout', 'tcd-w' ); ?></th>
            <td class="u-center">
              <img src="<?php echo get_template_directory_uri(); ?>/admin/assets/images/cb-section-type2-A.png" alt="" width="300" height="auto">
              <p><label><input type="radio" name="dp_options[contents_builder][<?php echo $cb_index; ?>][cb_section_type2_block_layout]" value="type1" <?php checked( 'type1', $value['cb_section_type2_block_layout'] ); ?>> <?php _e( 'Type1', 'tcd-w' ); ?></label></p>
            </td>
            <td class="u-center">
              <img src="<?php echo get_template_directory_uri(); ?>/admin/assets/images/cb-section-type2-B.png" alt="" width="300" height="auto">
              <p><label><input type="radio" name="dp_options[contents_builder][<?php echo $cb_index; ?>][cb_section_type2_block_layout]" value="type2" <?php checked( 'type2', $value['cb_section_type2_block_layout'] ); ?>> <?php _e( 'Type2', 'tcd-w' ); ?></label></p>
            </td>
          </tr>
          <?php
          for ( $i = 1; $i <= 5; $i++ ) : 
            if ( 5 === $i ) : // Text
          ?>
          <tr class="block-contents-table__row">
            <th rowspan="4" class="block-contents-table__header"><?php echo $i; ?></td>
            <td><?php _e( 'Title', 'tcd-w' ); ?></td>
            <td><textarea class="large-text" name="dp_options[contents_builder][<?php echo $cb_index; ?>][cb_section_type2_block_title]"><?php echo esc_textarea( $value['cb_section_type2_block_title'] ); ?></textarea></td>
          </tr>
          <tr class="block-contents-table__row">
            <td><?php _e( 'Description', 'tcd-w' ); ?></td>
            <td><textarea rows="4" class="large-text" name="dp_options[contents_builder][<?php echo $cb_index; ?>][cb_section_type2_block_desc]"><?php echo esc_textarea( $value['cb_section_type2_block_desc'] ); ?></textarea></td>
          </tr>
          <tr class="block-contents-table__row">
            <td><?php _e( 'Font color', 'tcd-w' ); ?></td>
            <td class="u-center"><input type="text" name="dp_options[contents_builder][<?php echo $cb_index; ?>][cb_section_type2_block_color]" value="<?php echo esc_attr( $value['cb_section_type2_block_color'] ); ?>" data-default-color="#000000" class="<?php echo preg_match( '/^cb_\d+$/', $cb_index ) ? 'c-color-picker' : 'cb-color-picker'; ?>"></td>
          </tr>
          <tr class="block-contents-table__row">
            <td><?php _e( 'Background color', 'tcd-w' ); ?></td>
            <td class="u-center"><input type="text" name="dp_options[contents_builder][<?php echo $cb_index; ?>][cb_section_type2_block_bg]" value="<?php echo esc_attr( $value['cb_section_type2_block_bg'] ); ?>" data-default-color="#f4f1ed" class="<?php echo preg_match( '/^cb_\d+$/', $cb_index ) ? 'c-color-picker' : 'cb-color-picker'; ?>"></td>
          </tr>
          <?php else : // Image ?>
          <tr class="block-contents-table__row">
            <th class="block-contents-table__header"><?php echo $i; ?></td>
            <td>
              <p><?php echo 4 === $i ? __( 'Recommended size: width: 750px, height: 750px', 'tcd-w' ) : __( 'Recommended size: width: 500px, height: 500px', 'tcd-w' ); ?></p>
            </td>
            <td>
    	        <div class="image_box cf">
    	        	<div class="cf cf_media_field hide-if-no-js">
    	        		<input type="hidden" value="<?php echo esc_attr( $value['cb_section_type2_block_img' . $i] ); ?>" id="cb_section_type2_block_img<?php echo $i; ?>_<?php echo $cb_index; ?>" name="dp_options[contents_builder][<?php echo $cb_index; ?>][cb_section_type2_block_img<?php echo $i; ?>]" class="cf_media_id">
    	        		<div class="preview_field"><?php if ( $value['cb_section_type2_block_img' . $i] ) { echo wp_get_attachment_image( $value['cb_section_type2_block_img' . $i], 'medium' ); } ?></div>
    	        		<div class="button_area u-center">
    	         			<input type="button" value="<?php _e( 'Select Image', 'tcd-w' ); ?>" class="cfmf-select-img button">
    	         			<input type="button" value="<?php _e( 'Remove Image', 'tcd-w' ); ?>" class="cfmf-delete-img button <?php if ( ! $value['cb_section_type2_block_img' . $i] ) { echo 'hidden'; } ?>">
    	        		</div>
    	        	</div>
    	        </div>
            </td>
          </tr>
          <?php
            endif; 
          endfor;
          ?>
        </table>
      </div>
      <div class="cb_section_type_type3"<?php if ( 'type3' !== $value['cb_section_type'] ) { echo ' style="display: none;"'; } ?>>
        <table class="block-contents-table">
          <tr class="block-contents-table__row">
            <th class="block-contents-table__header"><?php _e( 'Layout', 'tcd-w' ); ?></th>
            <td class="u-center">
              <img src="<?php echo get_template_directory_uri(); ?>/admin/assets/images/cb-section-type3-A.png" alt="" width="300" height="auto">
              <p><label><input type="radio" name="dp_options[contents_builder][<?php echo $cb_index; ?>][cb_section_type3_block_layout]" value="type1" <?php checked( 'type1', $value['cb_section_type3_block_layout'] ); ?>> <?php _e( 'Type1', 'tcd-w' ); ?></label></p>
            </td>
            <td class="u-center">
              <img src="<?php echo get_template_directory_uri(); ?>/admin/assets/images/cb-section-type3-B.png" alt="" width="300" height="auto">
              <p><label><input type="radio" name="dp_options[contents_builder][<?php echo $cb_index; ?>][cb_section_type3_block_layout]" value="type2" <?php checked( 'type2', $value['cb_section_type3_block_layout'] ); ?>> <?php _e( 'Type2', 'tcd-w' ); ?></label></p>
            </td>
          </tr>
          <?php
          for ( $i = 1; $i <= 4; $i++ ) : 
            if ( 4 === $i ) : // Text
          ?>
          <tr class="block-contents-table__row">
            <th rowspan="4" class="block-contents-table__header"><?php echo $i; ?></td>
            <td><?php _e( 'Title', 'tcd-w' ); ?></td>
            <td><textarea class="large-text" name="dp_options[contents_builder][<?php echo $cb_index; ?>][cb_section_type3_block_title]"><?php echo esc_textarea( $value['cb_section_type3_block_title'] ); ?></textarea></td>
          </tr>
          <tr class="block-contents-table__row">
            <td><?php _e( 'Description', 'tcd-w' ); ?></td>
            <td><textarea rows="4" class="large-text" name="dp_options[contents_builder][<?php echo $cb_index; ?>][cb_section_type3_block_desc]"><?php echo esc_textarea( $value['cb_section_type3_block_desc'] ); ?></textarea></td>
          </tr>
          <tr class="block-contents-table__row">
            <td><?php _e( 'Font color', 'tcd-w' ); ?></td>
            <td class="u-center"><input type="text" name="dp_options[contents_builder][<?php echo $cb_index; ?>][cb_section_type3_block_color]" value="<?php echo esc_attr( $value['cb_section_type3_block_color'] ); ?>" data-default-color="#000000" class="<?php echo preg_match( '/^cb_\d+$/', $cb_index ) ? 'c-color-picker' : 'cb-color-picker'; ?>"></td>
          </tr>
          <tr class="block-contents-table__row">
            <td><?php _e( 'Background color', 'tcd-w' ); ?></td>
            <td class="u-center"><input type="text" name="dp_options[contents_builder][<?php echo $cb_index; ?>][cb_section_type3_block_bg]" value="<?php echo esc_attr( $value['cb_section_type3_block_bg'] ); ?>" data-default-color="#f4f1ed" class="<?php echo preg_match( '/^cb_\d+$/', $cb_index ) ? 'c-color-picker' : 'cb-color-picker'; ?>"></td>
          </tr>
          <?php else : // Image ?>
          <tr class="block-contents-table__row">
            <th class="block-contents-table__header"><?php echo $i; ?></td>
            <td><p><?php echo _e( 'Recommended size: width: 750px, height: 750px', 'tcd-w' ); ?></p></td>
            <td>
    	        <div class="image_box cf">
    	        	<div class="cf cf_media_field hide-if-no-js">
    	        		<input type="hidden" value="<?php echo esc_attr( $value['cb_section_type3_block_img' . $i] ); ?>" id="cb_section_type3_block_img<?php echo $i; ?>_<?php echo $cb_index; ?>" name="dp_options[contents_builder][<?php echo $cb_index; ?>][cb_section_type3_block_img<?php echo $i; ?>]" class="cf_media_id">
    	        		<div class="preview_field"><?php if ( $value['cb_section_type3_block_img' . $i] ) { echo wp_get_attachment_image( $value['cb_section_type3_block_img' . $i], 'medium' ); } ?></div>
    	        		<div class="button_area u-center">
    	         			<input type="button" value="<?php _e( 'Select Image', 'tcd-w' ); ?>" class="cfmf-select-img button">
    	         			<input type="button" value="<?php _e( 'Remove Image', 'tcd-w' ); ?>" class="cfmf-delete-img button <?php if ( ! $value['cb_section_type3_block_img' . $i] ) { echo 'hidden'; } ?>">
    	        		</div>
    	        	</div>
    	        </div>
            </td>
          </tr>
          <?php
            endif; 
          endfor;
          ?>
        </table>
      </div>
  		<h4 class="theme_option_headline2"><?php _e( 'Button settings', 'tcd-w' ); ?></h4>
			<p><?php _e( 'The button is displayed after the block content', 'tcd-w' ); ?></p>
  		<p><label><?php _e( 'Label', 'tcd-w' ); ?> <input type="text" class="regular-text" name="dp_options[contents_builder][<?php echo $cb_index; ?>][cb_section_btn_label]" value="<?php echo esc_attr( $value['cb_section_btn_label'] ); ?>"></label></p>
  		<p><label><?php _e( 'Link URL', 'tcd-w' ); ?> <input type="text" class="regular-text" name="dp_options[contents_builder][<?php echo $cb_index; ?>][cb_section_btn_url]" value="<?php echo esc_attr( $value['cb_section_btn_url'] ); ?>"></label></p>
  <?php
  // Recommended plan
  } elseif ( 'recommended_plan' === $cb_content_select ) {

		if ( ! isset( $value['cb_recommended_plan_display'] ) ) {
			$value['cb_recommended_plan_display'] = null;
		}
		if ( ! isset( $value['cb_recommended_plan_img'] ) ) {
			$value['cb_recommended_plan_img'] = '';
		}
		if ( ! isset( $value['cb_recommended_plan_header_title'] ) ) {
			$value['cb_recommended_plan_header_title'] = '';
		}
		if ( ! isset( $value['cb_recommended_plan_header_font_size'] ) ) {
			$value['cb_recommended_plan_header_font_size'] = 40;
		}
		if ( ! isset( $value['cb_recommended_plan_header_color'] ) ) {
			$value['cb_recommended_plan_header_color'] = '#ffffff';
		}
		if ( ! isset( $value['cb_recommended_plan_header_writing_type'] ) ) {
			$value['cb_recommended_plan_header_writing_type'] = 'type1';
		}
		if ( ! isset( $value['cb_recommended_plan_btn_label'] ) ) {
			$value['cb_recommended_plan_btn_label'] = '';
		}
  ?>
    <h3 class="cb_content_headline"><span><?php _e( 'Recommended plan content', 'tcd-w' ); ?></span><a href="#"><?php _e( 'Open', 'tcd-w' ); ?></a></h3>
    <div class="cb_content">
			<p><?php _e( 'The recommended plan list is displayed.', 'tcd-w' ); ?><br><?php _e( 'How to set a plan as recommended plan: please check in checkbox which says "Show this post for recommended plan." on the right side of the post edit screen.', 'tcd-w' ); ?></p>
			<p><label><input name="dp_options[contents_builder][<?php echo $cb_index; ?>][cb_recommended_plan_display]" type="checkbox" value="1" <?php checked( 1, $value['cb_recommended_plan_display'] ); ?>><?php _e( 'Display this content', 'tcd-w' ); ?></label></p>
      <?php if ( preg_match( '/^cb_\d+$/', $cb_index ) ) : ?>
      <div class="theme_option_message">
        <p><?php _e( 'To make it a link to jump to this content, set URL of hero header buttons to the following.', 'tcd-w' ); ?></p>
        <p><?php _e( 'ID:', 'tcd-w' ); ?> <input type="text" readonly="readonly" value="#<?php echo $cb_index; ?>"></p>
      </div>
      <?php endif; ?>
  		<h4 class="theme_option_headline2"><?php _e( 'Background image of the header', 'tcd-w' ); ?></h4>
      <p><?php _e( 'Recommended size: width 1450px, height 500px', 'tcd-w' ); ?></p>
    	<div class="image_box cf">
    		<div class="cf cf_media_field hide-if-no-js">
    			<input type="hidden" value="<?php echo esc_attr( $value['cb_recommended_plan_img'] ); ?>" id="cb_recommended_plan_img-<?php echo $cb_index; ?>" name="dp_options[contents_builder][<?php echo $cb_index; ?>][cb_recommended_plan_img]" class="cf_media_id">
    			<div class="preview_field"><?php if ( $value['cb_recommended_plan_img'] ) { echo wp_get_attachment_image( $value['cb_recommended_plan_img'], 'medium' ); } ?></div>
    			<div class="button_area">
    	 			<input type="button" value="<?php _e( 'Select Image', 'tcd-w' ); ?>" class="cfmf-select-img button">
    	 			<input type="button" value="<?php _e( 'Remove Image', 'tcd-w' ); ?>" class="cfmf-delete-img button <?php if ( ! $value['cb_recommended_plan_img'] ) { echo 'hidden'; } ?>">
    			</div>
    		</div>
    	</div>
  		<h4 class="theme_option_headline2"><?php _e( 'Title of the header', 'tcd-w' ); ?></h4>
      <textarea class="large-text" name="dp_options[contents_builder][<?php echo $cb_index; ?>][cb_recommended_plan_header_title]"><?php echo esc_textarea( $value['cb_recommended_plan_header_title'] ); ?></textarea>
      <p><?php _e( 'Font color', 'tcd-w' ); ?> <input type="text" name="dp_options[contents_builder][<?php echo $cb_index; ?>][cb_recommended_plan_header_color]" value="<?php echo esc_attr( $value['cb_recommended_plan_header_color'] ); ?>" data-default-color="#ffffff" class="<?php echo preg_match( '/^cb_\d+$/', $cb_index ) ? 'c-color-picker' : 'cb-color-picker'; ?>"></p>
      <p><label><?php _e( 'Font size', 'tcd-w' ); ?> <input type="number" min="1" step="1" class="tiny-text" name="dp_options[contents_builder][<?php echo $cb_index; ?>][cb_recommended_plan_header_font_size]" value="<?php echo esc_attr( $value['cb_recommended_plan_header_font_size'] ); ?>"> px</label></p>
      <?php foreach ( $writing_type_options as $option ) : ?>
      <p><label><input type="radio" name="dp_options[contents_builder][<?php echo $cb_index; ?>][cb_recommended_plan_header_writing_type]" value="<?php echo esc_attr( $option['value'] ); ?>" <?php checked( $option['value'], $value['cb_recommended_plan_header_writing_type'] ); ?>> <?php echo esc_html( $option['label'] ); ?></label></p>
      <?php endforeach; ?>
  		<h4 class="theme_option_headline2"><?php _e( 'Label of archive link', 'tcd-w' ); ?></h4>
  		<input type="text" class="regular-text" name="dp_options[contents_builder][<?php echo $cb_index; ?>][cb_recommended_plan_btn_label]" value="<?php echo esc_attr( $value['cb_recommended_plan_btn_label'] ); ?>">
  <?php
  // Blog 
	} elseif ( 'blog' ===  $cb_content_select ) {

		if ( ! isset( $value['cb_blog_display'] ) ) {
			$value['cb_blog_display'] = null;
		}
		if ( ! isset( $value['cb_blog_headline'] ) ) {
			$value['cb_blog_headline'] = '';
		}
		if ( ! isset( $value['cb_blog_type'] ) ) {
			$value['cb_blog_type'] = 'recent_post';
		}
		if ( ! isset( $value['cb_blog_btn_label'] ) ) {
			$value['cb_blog_btn_label'] = '';
		}
    ?>
    <h3 class="cb_content_headline"><span><?php _e( 'Blog content', 'tcd-w' ); ?></span><a href="#"><?php _e( 'Open', 'tcd-w' ); ?></a></h3>
    <div class="cb_content">
			<p><label><input name="dp_options[contents_builder][<?php echo $cb_index; ?>][cb_blog_display]" type="checkbox" value="1" <?php checked( 1, $value['cb_blog_display'] ); ?>><?php _e( 'Display this content', 'tcd-w' ); ?></label></p>
      <?php if ( preg_match( '/^cb_\d+$/', $cb_index ) ) : ?>
      <div class="theme_option_message">
        <p><?php _e( 'To make it a link to jump to this content, set URL of hero header buttons to the following.', 'tcd-w' ); ?></p>
        <p><?php _e( 'ID:', 'tcd-w' ); ?> <input type="text" readonly="readonly" value="#<?php echo $cb_index; ?>"></p>
      </div>
      <?php endif; ?>
  		<h4 class="theme_option_headline2"><?php _e( 'Headline', 'tcd-w' ); ?></h4>
  		<input type="text" class="regular-text" name="dp_options[contents_builder][<?php echo $cb_index; ?>][cb_blog_headline]" value="<?php echo esc_attr( $value['cb_blog_headline'] ); ?>">
  		<h4 class="theme_option_headline2"><?php _e( 'Post type', 'tcd-w' ); ?></h4>
      <select name="dp_options[contents_builder][<?php echo $cb_index; ?>][cb_blog_type]">
        <?php foreach ( $cb_blog_type_options as $option ) : ?>
        <option value="<?php echo esc_attr( $option['value'] ); ?>" <?php selected( $option['value'], $value['cb_blog_type'] ); ?>><?php echo esc_html( $option['label'] ); ?></option>
        <?php endforeach; ?>
      </select>
  		<h4 class="theme_option_headline2"><?php _e( 'Label of archive link', 'tcd-w' ); ?></h4>
  		<input type="text" class="regular-text" name="dp_options[contents_builder][<?php echo $cb_index; ?>][cb_blog_btn_label]" value="<?php echo esc_attr( $value['cb_blog_btn_label'] ); ?>">
  <?php
	// フリーススペース ----------------------------------------------------------------------------------------
	} elseif ( 'wysiwyg' == $cb_content_select ) {

		if ( ! isset( $value['cb_wysiwyg_display'] ) ) {
			$value['cb_wysiwyg_display'] = null;
		}
		if ( ! isset( $value['cb_wysiwyg_editor'] ) ) {
			$value['cb_wysiwyg_editor'] = '';
		}
?>
    <h3 class="cb_content_headline"><span><?php _e( 'WYSIWYG editor', 'tcd-w' ); ?></span><a href="#"><?php _e( 'Open', 'tcd-w' ); ?></a></h3>
    <div class="cb_content">
			<p><label><input name="dp_options[contents_builder][<?php echo $cb_index; ?>][cb_wysiwyg_display]" type="checkbox" value="1" <?php checked( 1, $value['cb_wysiwyg_display'] ); ?>><?php _e( 'Display this content', 'tcd-w' ); ?></label></p>
      <?php if ( preg_match( '/^cb_\d+$/', $cb_index ) ) : ?>
      <div class="theme_option_message">
        <p><?php _e( 'To make it a link to jump to this content, set URL of hero header buttons to the following.', 'tcd-w' ); ?></p>
        <p><?php _e( 'ID:', 'tcd-w' ); ?> <input type="text" readonly="readonly" value="#<?php echo $cb_index; ?>"></p>
      </div>
      <?php endif; ?>
			<?php
      wp_editor( 
        $value['cb_wysiwyg_editor'], 
        'cb_wysiwyg_editor-' . $cb_index, 
        array( 
          'textarea_name' => 'dp_options[contents_builder][' . $cb_index . '][cb_wysiwyg_editor]', 
          'textarea_rows' => 10,
          'editor_class' => 'change_content_headline' 
        ) 
      ); 
      ?>
<?php
	} else {
?>
    <h3 class="cb_content_headline"><?php echo esc_html( $cb_content_select ); ?><a href="#"><?php _e( 'Open', 'tcd-w' ); ?></a></h3>
    <div class="cb_content">
<?php
      }
?>
     <ul class="cb_content_button cf">
      <li><input type="submit" class="button-ml ajax_button" value="<?php echo __( 'Save Changes', 'tcd-w' ); ?>"></li>
      <li><a href="#" class="button-ml close-content"><?php echo __( 'Close', 'tcd-w' ); ?></a></li>
     </ul>

    </div><!-- END .cb_content -->
   </div><!-- END .cb_content_wrap -->
<?php
}
