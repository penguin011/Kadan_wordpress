<?php
/**
 * Add a meta box of page template
 */

add_action( 'add_meta_boxes', 'register_template_meta_box', 11 );
add_action( 'save_post', 'save_template_meta_box' );

function register_template_meta_box() {
	add_meta_box(
    'template_meta_box',
    __( 'Page template setting', 'tcd-w' ),
    'render_template_meta_box',
    'page',
    'normal',
    'high'
  );
}

function save_template_meta_box( $post_id ) {

  $id = 'template_meta_box';

  $cf_keys = array( 
    'page_tcd_template_type', 
  );

	// Check if our nonce is set.
	if ( ! isset( $_POST[$id . '_nonce'] ) ) return;

	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST[$id . '_nonce'], 'save_' . $id  ) ) {
  	return $post_id;
  }

  // check autosave
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
  	return $post_id;
  }

  // check permissions
  if ( 'page' == $_POST['post_type'] ) {
  	if ( ! current_user_can( 'edit_page', $post_id ) ) {
    		return $post_id;
  	}
  } elseif ( !current_user_can( 'edit_post', $post_id ) ) {
    	return $post_id;
  }

  // save or delete
  if ( ! $fields = get_tcd_template_fields( 'all' ) ) {
    return $post_id;
  }

  foreach ( array_column( $fields, 'id' ) as $id ) {
    $cf_keys[] = $id;
  }

  foreach ( $cf_keys as $cf_key ) {

  	$old = get_post_meta( $post_id, $cf_key, true );
		$new = isset( $_POST[$cf_key] ) ? $_POST[$cf_key] : '';

  	if ( $new && $new != $old ) {
  		update_post_meta( $post_id, $cf_key, $new );
  	} elseif ( '' == $new && $old ) {
   		delete_post_meta( $post_id, $cf_key, $old );
  	}	
  }
  
}

// フォーム用 画像フィールド出力
function mlcf_media_form( $cf_key, $label ) {
	global $post;
	if ( empty( $cf_key ) ) return false;
	if ( empty( $label ) ) $label = $cf_key;
	$media_id = get_post_meta( $post->ID, $cf_key, true );
?>
	<div class="cf cf_media_field hide-if-no-js <?php echo esc_attr( $cf_key ); ?>">
    <input type="hidden" class="cf_media_id" name="<?php echo esc_attr( $cf_key ); ?>" id="<?php echo esc_attr( $cf_key ); ?>" value="<?php echo esc_attr( $media_id ); ?>">
    <div class="preview_field"><?php if ( $media_id ) echo wp_get_attachment_image( $media_id, 'medium' ); ?></div>
		<div class="buttton_area">
   	 		<input type="button" class="cfmf-select-img button" value="<?php _e( 'Select Image', 'tcd-w' ); ?>">
     		<input type="button" class="cfmf-delete-img button<?php if ( ! $media_id ) echo ' hidden'; ?>" value="<?php _e( 'Remove Image', 'tcd-w' ); ?>">
    	</div>
  	</div>
<?php
}

function render_template_meta_box( $post ) {

	$page_tcd_template_type = array(
		'name' => __( 'Page template type', 'tcd-w' ),
		'id' => 'page_tcd_template_type',
		'type' => 'radio',
		'default' => 'type1',
		'options' => array(
			array( 'name' => __( 'Normal', 'tcd-w' ), 'value' => 'type1' ),
			array( 'name' => __( 'No side', 'tcd-w' ), 'value' => 'type2' ),
			array( 'name' => __( 'Template1', 'tcd-w' ), 'value' => 'type3', 'img' => 'tmp-onsen.jpg' ),
			array( 'name' => __( 'Template2', 'tcd-w' ), 'value' => 'type4', 'img' => 'tmp-room.jpg' ),
			array( 'name' => __( 'Template3', 'tcd-w' ), 'value' => 'type5', 'img' => 'tmp-ryori.jpg' ),
		)
  );
	$page_tcd_template_type_meta = $post->page_tcd_template_type;

	wp_nonce_field( 'save_template_meta_box', 'template_meta_box_nonce' );

  // テンプレートの選択
	echo '<dl class="ml_custom_fields_select">';

	echo '<dt class="label">';
  echo '<label for="' . $page_tcd_template_type['id'] . '">' . $page_tcd_template_type['name'] . '</label>';
  echo '</dt>' . "\n";

  echo '<dd>';
  echo '<ul class="radio template cf">';

	foreach ( $page_tcd_template_type['options'] as $option ) {

    $checked = ( ( empty( $page_tcd_template_type_meta ) && $option['value'] == 'type1' ) || $page_tcd_template_type_meta == $option['value'] ) ? true : false;

    echo '<li>';

    if ( $checked ) {
      echo '<label class="active">';
		  echo '<input type="radio" name="' . $page_tcd_template_type['id'] . '" value="' .  $option['value'] . '" checked="checked">';
    } else {
      echo '<label>';
		  echo '<input type="radio" name="' . $page_tcd_template_type['id'] . '" value="' .  $option['value'] . '">';
    }

		echo $option['name'] . '</label>';

    if ( in_array( $option['value'], array( 'type3', 'type4', 'type5' ) ) ) {
      echo '<a href="' . get_template_directory_uri() . '/admin/assets/images/' . $option['img'] . '" target="_blank">' . __( 'View image', 'tcd-w' ) . '</a>';
    }

		echo '</li>';
	}

	echo '</ul></dd>' . "\n";
  echo '</dl>' . "\n";

  // Render common fields
  echo '<dl class="ml_custom_fields type2" id="page_tcd_template_type_common"';
	if ( in_array( $page_tcd_template_type_meta, array( 'type3', 'type4', 'type5' ) ) ) {
		echo ' style="display:block;"';
	} else {
		echo ' style="display:none;"';
	}
  echo  '>';
	render_tcd_template_fields_inputs( 'common' );
	echo '</dl>' . "\n";

  // Render type3/type4/type5 fields
	for ( $i = 3; $i <= 5; $i++ ) {
		echo '<dl class="ml_custom_fields type2" id="page_tcd_template_type_type' . $i . '"';
		if ( $page_tcd_template_type_meta == 'type' . $i ) {
			echo ' style="display:block;"';
		} else {
			echo ' style="display:none;"';
		}
		echo  '>';
		render_tcd_template_fields_inputs( 'type' . $i );
	  	echo '</dl>' . "\n";
	}
}

/**
 * Get template fields array
 */
function get_tcd_template_fields( $type = null ) {

	$common_fields = array();
	$type3_fields = array();
	$type4_fields = array();
	$type5_fields = array();

	$common_fields[] = array(
		'name' => __( 'Headline', 'tcd-w' ),
		'id' => 'page_headline',
		'type' => 'text'
	);
	$common_fields[] = array(
		'name' => __( 'Font size of headline', 'tcd-w' ),
		'id' => 'page_headline_font_size',
		'type' => 'font-size',
    'default' => 36
	);
	$common_fields[] = array(
		'name' => __( 'Font color of headline', 'tcd-w' ),
		'id' => 'page_headline_color',
		'type' => 'color',
    'default' => '#ffffff'
	);
	$common_fields[] = array(
		'name' => __( 'Background color of headline', 'tcd-w' ),
		'id' => 'page_headline_bg',
		'type' => 'color',
    'default' => '#660000'
	);
	$common_fields[] = array(
    'name' => __( 'Writing mode of headline', 'tcd-w' ),
		'id' => 'page_headline_writing_mode',
		'type' => 'radio',
    'default' => 'type1',
    'options' => array(
      'type1' => array( 'value' => 'type1', 'label' => __( 'Vertical writing', 'tcd-w' ) ),
      'type2' => array( 'value' => 'type2', 'label' => __( 'Horizontal writing', 'tcd-w' ) )
    )
	);
	$common_fields[] = array(
		'name' => 'A',
		'id' => 'page_desc',
		'type' => 'textarea'
	);
	$type3_fields[] = array(
    'name' => 'B',
		'id' => 'page_type3_b',
		'type' => 'repeater',
    'callback' => 'render_type3_block'
	);
	$type3_fields[] = array(
    'name' => __( 'Background image in C', 'tcd-w' ),
    'desc' => __( 'Recommended size: width 1450px, height 500px', 'tcd-w' ),
		'id' => 'page_type3_c_img',
		'type' => 'image'
	);
	$type3_fields[] = array(
    'name' => __( 'Catchphrase in C', 'tcd-w' ),
		'id' => 'page_type3_c_catch',
		'type' => 'textarea'
	);
	$type3_fields[] = array(
    'name' => __( 'Font color in C', 'tcd-w' ),
		'id' => 'page_type3_c_color',
		'type' => 'color',
    'default' => '#ffffff'
	);
	$type3_fields[] = array(
    'name' => __( 'Writing mode of catchphrase in C', 'tcd-w' ),
		'id' => 'page_type3_c_writing_mode',
		'type' => 'radio',
    'default' => 'type1',
    'options' => array(
      'type1' => array( 'value' => 'type1', 'label' => __( 'Vertical writing', 'tcd-w' ) ),
      'type2' => array( 'value' => 'type2', 'label' => __( 'Horizontal writing', 'tcd-w' ) )
    )
	);
	$type3_fields[] = array(
    'name' => 'D',
		'id' => 'page_type3_d',
		'type' => 'repeater',
    'callback' => 'render_type3_block'
	);
	$type3_fields[] = array(
    'name' => __( 'Catchphrase in E', 'tcd-w' ),
		'id' => 'page_type3_e_catch',
		'type' => 'textarea'
	);
	$type3_fields[] = array(
    'name' => __( 'Font size in E', 'tcd-w' ),
		'id' => 'page_type3_e_font_size',
		'type' => 'font-size',
    'defalt' => 36
	);
	$type3_fields[] = array(
    'name' => __( 'Background color of F', 'tcd-w' ),
		'id' => 'page_type3_f_bg',
		'type' => 'color',
    'default' => '#f4f1ed'
	);
	$type3_fields[] = array(
    'name' => __( 'F-1', 'tcd-w' ),
		'id' => 'page_type3_f_1',
		'type' => 'image'
	);
	$type3_fields[] = array(
    'name' => __( 'F-2', 'tcd-w' ),
		'id' => 'page_type3_f_2',
		'type' => 'textarea'
	);
	$type3_fields[] = array(
    'name' => __( 'F-3', 'tcd-w' ),
		'id' => 'page_type3_f_3',
		'type' => 'repeater',
    'callback' => 'render_repeater_table'
	);

	$type4_fields[] = array(
    'name' => 'C',
		'id' => 'page_type4_c',
		'type' => 'repeater',
    'callback' => 'render_type4_block'
	);
	$type4_fields[] = array(
    'name' => __( 'Background color of D', 'tcd-w' ),
		'id' => 'page_type4_d_bg',
		'type' => 'color',
    'default' => '#f4f1ed'
	);
	$type4_fields[] = array(
    'name' => __( 'D-1', 'tcd-w' ),
		'id' => 'page_type4_d_1',
		'type' => 'image',
    'desc' => __( 'Recommended size: width 700px, height 700px', 'tcd-w' ),
	);
	$type4_fields[] = array(
    'name' => __( 'D-2', 'tcd-w' ),
		'id' => 'page_type4_d_2',
		'type' => 'text'
	);
	$type4_fields[] = array(
    'name' => __( 'D-3', 'tcd-w' ),
		'id' => 'page_type4_d_3',
		'type' => 'repeater',
    'callback' => 'render_repeater_table'
	);
	$type4_fields[] = array(
    'name' => __( 'Label of D-4', 'tcd-w' ),
		'id' => 'page_type4_d_4_label',
		'type' => 'text'
	);
	$type4_fields[] = array(
    'name' => __( 'Link URL of D-4', 'tcd-w' ),
		'id' => 'page_type4_d_4_url',
		'type' => 'text'
	);
	$type4_fields[] = array(
    'name' => __( 'Open Link URL of D-4 with new tab', 'tcd-w' ),
		'id' => 'page_type4_d_4_target',
		'type' => 'checkbox',
    'default' => '',
    'options' => array(
      array( 'value' => 1, 'label' => __( 'Open with new tab', 'tcd-w' ) )
    )
	);
	$type4_fields[] = array(
    'name' => __( 'Font color of D-4', 'tcd-w' ),
		'id' => 'page_type4_d_4_color',
		'type' => 'color',
    'default' => '#ffffff'
	);
	$type4_fields[] = array(
    'name' => __( 'Background color of D-4', 'tcd-w' ),
		'id' => 'page_type4_d_4_bg',
		'type' => 'color',
    'default' => '#000000'
	);
	$type4_fields[] = array(
    'name' => __( 'Font color of D-4 on hover', 'tcd-w' ),
		'id' => 'page_type4_d_4_color_hover',
		'type' => 'color',
    'default' => '#ffffff'
	);
	$type4_fields[] = array(
    'name' => __( 'Background color of D-4 on hover', 'tcd-w' ),
		'id' => 'page_type4_d_4_bg_hover',
		'type' => 'color',
    'default' => '#660000'
	);

	$type5_fields[] = array(
    'name' => 'B',
		'id' => 'page_type5_b',
		'type' => 'repeater',
    'callback' => 'render_type5_block'
	);
	$type5_fields[] = array(
    'name' => __( 'Background image in C', 'tcd-w' ),
    'desc' => __( 'Recommended size: width 1450px, height 500px', 'tcd-w' ),
		'id' => 'page_type5_c_img',
		'type' => 'image'
	);
	$type5_fields[] = array(
    'name' => __( 'Catchphrase in C', 'tcd-w' ),
		'id' => 'page_type5_c_catch',
		'type' => 'textarea'
	);
	$type5_fields[] = array(
    'name' => __( 'Font color in C', 'tcd-w' ),
		'id' => 'page_type5_c_color',
		'type' => 'color',
    'default' => '#ffffff'
	);
	$type5_fields[] = array(
    'name' => __( 'Writing mode of catchphrase in C', 'tcd-w' ),
		'id' => 'page_type5_c_writing_mode',
		'type' => 'radio',
    'default' => 'type1',
    'options' => array(
      'type1' => array( 'value' => 'type1', 'label' => __( 'Vertical writing', 'tcd-w' ) ),
      'type2' => array( 'value' => 'type2', 'label' => __( 'Horizontal writing', 'tcd-w' ) )
    )
	);
	$type5_fields[] = array(
    'name' => 'D',
		'id' => 'page_type5_d',
		'type' => 'repeater',
    'callback' => 'render_type5_block'
	);
	$type5_fields[] = array(
    'name' => __( 'Background image in E', 'tcd-w' ),
    'desc' => __( 'Recommended size: width 1450px, height 500px', 'tcd-w' ),
		'id' => 'page_type5_e_img',
		'type' => 'image'
	);
	$type5_fields[] = array(
    'name' => __( 'Catchphrase in E', 'tcd-w' ),
		'id' => 'page_type5_e_catch',
		'type' => 'textarea'
	);
	$type5_fields[] = array(
    'name' => __( 'Font color in E', 'tcd-w' ),
		'id' => 'page_type5_e_color',
		'type' => 'color',
    'default' => '#ffffff'
	);
	$type5_fields[] = array(
    'name' => __( 'Writing mode of catchphrase in E', 'tcd-w' ),
		'id' => 'page_type5_e_writing_mode',
		'type' => 'radio',
    'default' => 'type1',
    'options' => array(
      'type1' => array( 'value' => 'type1', 'label' => __( 'Vertical writing', 'tcd-w' ) ),
      'type2' => array( 'value' => 'type2', 'label' => __( 'Horizontal writing', 'tcd-w' ) )
    )
	);
	$type5_fields[] = array(
    'name' => __( 'F-1', 'tcd-w' ),
		'id' => 'page_type5_f_1',
		'type' => 'textarea',
	);
	$type5_fields[] = array(
    'name' => __( 'F-2', 'tcd-w' ),
		'id' => 'page_type5_f_2',
		'type' => 'repeater',
    'callback' => 'render_type5_f'
	);
	$type5_fields[] = array(
    'name' => __( 'F-3', 'tcd-w' ),
		'id' => 'page_type5_f_3',
		'type' => 'textarea'
	);
	$type5_fields[] = array(
    'name' => __( 'F-4', 'tcd-w' ),
		'id' => 'page_type5_f_4',
		'type' => 'repeater',
    'callback' => 'render_type5_f'
	);

  if ( preg_match( '/^type\d$/', $type ) ) {
    return ${$type . '_fields'};
  } elseif ( 'common' === $type ) {
    return $common_fields;
  } else {
    return array_merge( $common_fields, $type3_fields, $type4_fields, $type5_fields );
  }
}

/* フィールド入力フォームを出力 */
function render_tcd_template_fields_inputs( $type ) {

	global $post;
  
  if ( ! $fields = get_tcd_template_fields( $type ) ) return false;
	$meta_values = get_post_meta( $post->ID );

	foreach( $fields as $field ) {

		if ( empty( $field['type'] ) ) $field['type'] = 'text';

		if ( isset( $meta_values[$field['id']][0] ) ) {
			$meta_value = $meta_values[$field['id']][0];
		} elseif ( ! empty( $field['default'] ) ) {
			$meta_value = $field['default'];
		} else {
			$meta_value = '';
		}

		echo '<dt class="label">';
    echo 'radio' === $field['type'] ? esc_html( $field['name'] ) : '<label for="' . esc_attr( $field['id'] ) . '">' . esc_html( $field['name'] ) . '</label>';
    echo '</dt>';
	  echo '<dd class="content">';

	  if ( ! empty( $field['desc'] ) ) {
	  	echo '<p class="desc">' . $field['desc'] . '</p>';
	  }

		switch ( $field['type'] ) {

      case 'checkbox' :
        foreach ( $field['options'] as $option ) {
          echo '<p><label><input type="checkbox" id="' . esc_attr( $field['id'] ) . '" name="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $option['value'] ) . '"';
          if ( strval( $option['value'] ) === $meta_value ) { 
            echo ' checked="checked"';
          }
          echo '>' . esc_html( $option['label'] ) . '</label></p>';
        }
        break;

      case 'color':
				echo '<input id="' . esc_attr( $field['id'] ) . '" class="c-color-picker" type="text" name="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $meta_value ) . '" data-default-color="' . esc_attr( $field['default'] ) . '">';
        break;

			case 'font-size' :
				echo '<input class="tiny-text" type="number" name="' . esc_attr( $field['id'] ) . '" id="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $meta_value ) . '" min="1" step="1"> px';
				break;

			case 'image' :
				mlcf_media_form( $field['id'], $field['name'] );
				break;

      case 'radio' :
        foreach ( $field['options'] as $option ) {
          echo '<p><label><input type="radio" id="' . esc_attr( $field['id'] ) . '" name="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $option['value'] ) . '"';
          if ( $option['value'] === $meta_value ) { 
            echo ' checked="checked"';
          }
          echo '>' . esc_html( $option['label'] ) . '</label></p>';
        }
        break;

			case 'repeater' :
        if ( function_exists( $field['callback'] ) ) {
          call_user_func( $field['callback'], $field, $meta_value );
        }
				break;

			case 'textarea' :
				$rows = 0;
				if ( ! empty( $field['rows'] ) ) {
					$rows = absint( $field['rows'] );
				}
				if ( $rows < 1 ) {
					$rows = 2;
				}
				echo '<textarea name="' . esc_attr( $field['id'] ). '" id="' . esc_attr( $field['id'] ) . '" cols="60" rows="' . $rows . '" class="widefat">' . esc_textarea( $meta_value ) . '</textarea>';
				break;

			default :
				echo '<input type="' . esc_attr( $field['type'] ) . '" name="' . esc_attr( $field['id'] ) . '" id="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $meta_value ) . '" class="regular-text">';
        break;

		}

	  echo '</dd>' . "\n";

	}
}

function render_type3_block( $field, $meta_value ) {

  $key = 'addindex';
  ob_start();
  ?>
  <div id="topt_repeater-<?php echo $key; ?>" class="repeater-item">
	  <table class="block-contents-table">
	    <tr class="block-contents-table__row">
	  		<th class="block-contents-table__header"><?php _e( 'Layout', 'tcd-w' ); ?></th>
        <td class="u-center">
          <figure>
            <img src="<?php echo get_template_directory_uri(); ?>/admin/assets/images/tmp-onsen-type1.png" alt="">
          </figure>
          <label><input type="radio" name="<?php echo esc_attr( $field['id'] ); ?>[layout][<?php echo $key; ?>]" value="type1" checked="checked"><?php _e( 'Type1', 'tcd-w' ); ?></label>
        </td>
        <td class="u-center">
          <figure>
            <img src="<?php echo get_template_directory_uri(); ?>/admin/assets/images/tmp-onsen-type2.png" alt="">
          </figure>
          <label><input type="radio" name="<?php echo esc_attr( $field['id'] ); ?>[layout][<?php echo $key; ?>]" value="type2"><?php _e( 'Type2', 'tcd-w' ); ?></label>
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
	  		<th rowspan="4" class="block-contents-table__header"><?php _e( 'Color', 'tcd-w' ); ?></th>
        <td><?php _e( 'Font color of headline', 'tcd-w' ); ?></td>
        <td class="u-center">
          <input class="cf-color-picker" type="text" name="<?php echo esc_attr( $field['id'] ); ?>[headline_color][]" data-default-color="#ffffff" value="#ffffff">
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
        <td><?php _e( 'Background color of headline', 'tcd-w' ); ?></td>
        <td class="u-center">
          <input class="cf-color-picker" type="text" name="<?php echo esc_attr( $field['id'] ); ?>[headline_bg][]" data-default-color="#660000" value="#660000">
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
        <td><?php _e( 'Font color of description', 'tcd-w' ); ?></td>
        <td class="u-center">
          <input class="cf-color-picker" type="text" name="<?php echo esc_attr( $field['id'] ); ?>[color][]" data-default-color="#000000" value="#000000">
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
        <td><?php _e( 'Background color of description', 'tcd-w' ); ?></td>
        <td class="u-center">
          <input class="cf-color-picker" type="text" name="<?php echo esc_attr( $field['id'] ); ?>[bg][]" data-default-color="#f4f1ed" value="#f4f1ed">
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
	  		<th class="block-contents-table__header">1</th>
        <td><?php _e( 'Recommended size: width 900px, height 900px', 'tcd-w' ); ?></td>
        <td class="u-center">
	  			<div class="cf cf_media_field hide-if-no-js">
	  				<input type="hidden" value="" id="<?php echo esc_attr( $field['id'] ); ?>_img1_<?php echo $key; ?>" name="<?php echo esc_attr( $field['id'] ); ?>[img1][]" class="cf_media_id">
	  				<div class="preview_field"></div>
	  				<div class="button_area">
	  					<input type="button" value="<?php _e( 'Select Image', 'tcd-w' ); ?>" class="cfmf-select-img button">
	  					<input type="button" value="<?php _e( 'Remove Image', 'tcd-w' ); ?>" class="cfmf-delete-img button hidden">
	  				</div>
	  			</div>
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
	  		<th class="block-contents-table__header">2</th>
        <td><?php _e( 'Headline', 'tcd-w' ); ?></td>
        <td>
          <input class="large-text" type="text" name="<?php echo esc_attr( $field['id'] ); ?>[headline1][]" value="">
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
	  		<th class="block-contents-table__header">3</th>
        <td><?php _e( 'Description', 'tcd-w' ); ?></td>
        <td>
          <textarea rows="4" class="large-text" name="<?php echo esc_attr( $field['id'] ); ?>[desc1][]"></textarea>
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
	  		<th class="block-contents-table__header">4</th>
        <td><?php _e( 'Recommended size: width 900px, height 900px', 'tcd-w' ); ?></td>
        <td class="u-center">
	  			<div class="cf cf_media_field hide-if-no-js">
	  				<input type="hidden" value="" id="<?php echo esc_attr( $field['id'] ); ?>_img2_<?php echo $key; ?>" name="<?php echo esc_attr( $field['id'] ); ?>[img2][]" class="cf_media_id">
	  				<div class="preview_field"></div>
	  				<div class="button_area">
	  					<input type="button" value="<?php _e( 'Select Image', 'tcd-w' ); ?>" class="cfmf-select-img button">
	  					<input type="button" value="<?php _e( 'Remove Image', 'tcd-w' ); ?>" class="cfmf-delete-img button hidden">
	  				</div>
	  			</div>
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
	  		<th class="block-contents-table__header">5</th>
        <td><?php _e( 'Headline', 'tcd-w' ); ?></td>
        <td>
          <input class="large-text" type="text" name="<?php echo esc_attr( $field['id'] ); ?>[headline2][]" value="">
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
	  		<th class="block-contents-table__header">6</th>
        <td><?php _e( 'Description', 'tcd-w' ); ?></td>
        <td>
          <textarea rows="4" class="large-text" name="<?php echo esc_attr( $field['id'] ); ?>[desc2][]"></textarea>
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
	  		<th class="block-contents-table__header"><?php _e( 'Delete', 'tcd-w' ); ?></th>
        <td colspan="2" class="u-center">
          <p class="delete-row">
            <a href="#" class="button button-secondary button-delete-row"><?php _e( 'Delete item', 'tcd-w' ); ?></a>
          </p>
        </td>
	  	</tr>
	  </table>
  </div>
  <?php
	$clone = ob_get_clean();

	echo '<div class="repeater-wrapper" data-delete-confirm="' . __( 'Delete?', 'tcd-w' ) . '">' . "\n";
	echo '<div class="repeater ui-sortable">' . "\n";
  
  $meta_value = maybe_unserialize( $meta_value );

  if ( isset( $meta_value['headline1'][0] ) ) {

    foreach( array_keys( $meta_value['headline1'] ) as $repeater_index ) :

      $layout = isset( $meta_value['layout'][$repeater_index] ) ? $meta_value['layout'][$repeater_index] : 'type1';
      $headline_color = isset( $meta_value['headline_color'][$repeater_index] ) ? $meta_value['headline_color'][$repeater_index] : '#ffffff';
      $headline_bg = isset( $meta_value['headline_bg'][$repeater_index] ) ? $meta_value['headline_bg'][$repeater_index] : '#f4f1ed';
      $color = isset( $meta_value['color'][$repeater_index] ) ? $meta_value['color'][$repeater_index] : '#000000';
      $bg = isset( $meta_value['bg'][$repeater_index] ) ? $meta_value['bg'][$repeater_index] : '#f4f1ed';

      for ( $i = 1; $i <= 2; $i++ ) {
        ${'img' . $i} = isset( $meta_value['img' . $i][$repeater_index] ) ? $meta_value['img' . $i][$repeater_index] : '';
        ${'headline' . $i} = isset( $meta_value['headline' . $i][$repeater_index] ) ? $meta_value['headline' . $i][$repeater_index] : '';
        ${'desc' . $i} = isset( $meta_value['desc' . $i][$repeater_index] ) ? $meta_value['desc' . $i][$repeater_index] : '';
      }
  ?>
	<div class="repeater-item ui-sortable-handle">
	  <table class="block-contents-table">
	    <tr class="block-contents-table__row">
	  		<th class="block-contents-table__header"><?php _e( 'Layout', 'tcd-w' ); ?></th>
        <td class="u-center">
          <figure>
            <img src="<?php echo get_template_directory_uri(); ?>/admin/assets/images/tmp-onsen-type1.png" alt="">
          </figure>
          <label><input type="radio" name="<?php echo esc_attr( $field['id'] ); ?>[layout][<?php echo $repeater_index; ?>]" value="type1" <?php checked( 'type1', $layout ); ?>><?php _e( 'Type1', 'tcd-w' ); ?></label>
        </td>
        <td class="u-center">
          <figure>
            <img src="<?php echo get_template_directory_uri(); ?>/admin/assets/images/tmp-onsen-type2.png" alt="">
          </figure>
          <label><input type="radio" name="<?php echo esc_attr( $field['id'] ); ?>[layout][<?php echo $repeater_index; ?>]" value="type2" <?php checked( 'type2', $layout ); ?>><?php _e( 'Type2', 'tcd-w' ); ?></label>
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
	  		<th rowspan="4" class="block-contents-table__header"><?php _e( 'Color', 'tcd-w' ); ?></th>
        <td><?php _e( 'Font color of headline', 'tcd-w' ); ?></td>
        <td class="u-center">
          <input class="cf-color-picker" type="text" name="<?php echo esc_attr( $field['id'] ); ?>[headline_color][]" data-default-color="#ffffff" value="<?php echo $headline_color; ?>">
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
        <td><?php _e( 'Background color of headline', 'tcd-w' ); ?></td>
        <td class="u-center">
          <input class="cf-color-picker" type="text" name="<?php echo esc_attr( $field['id'] ); ?>[headline_bg][]" data-default-color="#660000" value="<?php echo $headline_bg; ?>">
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
        <td><?php _e( 'Font color of description', 'tcd-w' ); ?></td>
        <td class="u-center">
          <input class="cf-color-picker" type="text" name="<?php echo esc_attr( $field['id'] ); ?>[color][]" data-default-color="#000000" value="<?php echo $color; ?>">
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
        <td><?php _e( 'Background color of description', 'tcd-w' ); ?></td>
        <td class="u-center">
          <input class="cf-color-picker" type="text" name="<?php echo esc_attr( $field['id'] ); ?>[bg][]" data-default-color="#f4f1ed" value="<?php echo $bg; ?>">
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
	  		<th class="block-contents-table__header">1</th>
        <td><?php _e( 'Recommended size: width 900px, height 900px', 'tcd-w' ); ?></td>
        <td class="u-center">
	  			<div class="cf cf_media_field hide-if-no-js">
	  				<input class="cf_media_id" type="hidden" value="<?php echo esc_attr( $img1 ); ?>" id="<?php echo esc_attr( $field['id'] ); ?>_img1_<?php echo esc_attr( $repeater_index ); ?>" name="<?php echo esc_attr( $field['id'] ); ?>[img1][]" value="<?php echo esc_attr( $img1 ); ?>">
	  				<div class="preview_field">
              <?php if ( $img1 ) { echo wp_get_attachment_image( $img1, 'medium' ); } ?>
            </div>
	  				<div class="button_area">
	  					<input type="button" value="<?php _e( 'Select Image', 'tcd-w' ); ?>" class="cfmf-select-img button">
	  					<input type="button" value="<?php _e( 'Remove Image', 'tcd-w' ); ?>" class="cfmf-delete-img button <?php if ( ! $img1 ) { echo 'hidden'; } ?>">
	  				</div>
	  			</div>
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
	  		<th class="block-contents-table__header">2</th>
        <td><?php _e( 'Headline', 'tcd-w' ); ?></td>
        <td>
          <input class="large-text" type="text" name="<?php echo esc_attr( $field['id'] ); ?>[headline1][]" value="<?php echo esc_attr( $headline1 ); ?>">
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
	  		<th class="block-contents-table__header">3</th>
        <td><?php _e( 'Description', 'tcd-w' ); ?></td>
        <td>
          <textarea rows="4" class="large-text" name="<?php echo esc_attr( $field['id'] ); ?>[desc1][]"><?php echo esc_textarea( $desc1 ); ?></textarea>
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
	  		<th class="block-contents-table__header">4</th>
        <td><?php _e( 'Recommended size: width 900px, height 900px', 'tcd-w' ); ?></td>
        <td class="u-center">
	  			<div class="cf cf_media_field hide-if-no-js">
	  				<input class="cf_media_id" type="hidden" value="<?php echo esc_attr( $img2 ); ?>" id="<?php echo esc_attr( $field['id'] ); ?>_img2_<?php echo esc_attr( $repeater_index ); ?>" name="<?php echo esc_attr( $field['id'] ); ?>[img2][]" value="<?php echo esc_attr( $img2 ); ?>">
	  				<div class="preview_field">
              <?php if ( $img2 ) { echo wp_get_attachment_image( $img2, 'medium' ); } ?>
            </div>
	  				<div class="button_area">
	  					<input type="button" value="<?php _e( 'Select Image', 'tcd-w' ); ?>" class="cfmf-select-img button">
	  					<input type="button" value="<?php _e( 'Remove Image', 'tcd-w' ); ?>" class="cfmf-delete-img button <?php if ( ! $img2 ) { echo 'hidden'; } ?>">
	  				</div>
	  			</div>
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
	  		<th class="block-contents-table__header">5</th>
        <td><?php _e( 'Headline', 'tcd-w' ); ?></td>
        <td>
          <input class="large-text" type="text" name="<?php echo esc_attr( $field['id'] ); ?>[headline2][]" value="<?php echo esc_attr( $headline2 ); ?>">
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
	  		<th class="block-contents-table__header">6</th>
        <td><?php _e( 'Description', 'tcd-w' ); ?></td>
        <td>
          <textarea rows="4" class="large-text" name="<?php echo esc_attr( $field['id'] ); ?>[desc2][]"><?php echo esc_textarea( $desc2 ); ?></textarea>
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
	  		<th class="block-contents-table__header"><?php _e( 'Delete', 'tcd-w' ); ?></th>
        <td colspan="2" class="u-center">
          <p class="delete-row">
            <a href="#" class="button button-secondary button-delete-row"><?php _e( 'Delete item', 'tcd-w' ); ?></a>
          </p>
        </td>
	  	</tr>
	  </table>
  </div>
  <?php
    endforeach;
  } else {
		//echo $clone . "\n";
  }

  echo '</div>';
  echo ' <a href="#" class="button button-secondary button-add-row" data-clone="' . esc_attr( $clone ) . '">' . __( 'Add item', 'tcd-w' ) . '</a>';
  echo '</div>';

}

function render_repeater_table( $field, $meta_value ) {

  $key = 'addindex';
  ob_start();
  ?>
        <tr class="a-table__row repeater-item">
          <td>
            <input type="text" name="<?php echo esc_attr( $field['id'] ); ?>[header][]" value="" class="widefat">
          </td>
          <td>
            <input type="text" name="<?php echo esc_attr( $field['id'] ); ?>[data][]" value="" class="widefat">
          </td>
          <td class="col-delete">
            <a href="#" class="button button-secondary button-delete-row"><?php _e( 'Delete', 'tcd-w' ); ?></a>
          </td>
        </tr>
  <?php
	$clone = ob_get_clean();

	echo '<div class="repeater-wrapper" data-delete-confirm="' . __( 'Delete?', 'tcd-w' ) . '">' . "\n";
  echo '<table class="a-table a-table--border repeater">' . "\n";
  echo '<thead>' . "\n";
  echo '<tr class="a-table__row repeater-item">';
  echo '<th class="a-table__h a-table__col">' . __( 'Headline', 'tcd-w' ) . '</th>';
  echo '<th class="a-table__h a-table__col">' . __( 'Data', 'tcd-w' ) . '</th>';
  echo '<th class="a-table__h a-table__col"></th>';
  echo '</tr>' . "\n";
  echo '</thead>' . "\n";
  echo '<tbody>' . "\n";
    
  $meta_value = maybe_unserialize( $meta_value );

  if ( isset( $meta_value['header'][0] ) ) {

    foreach( array_keys( $meta_value['header'] ) as $repeater_index ) {
      $header = isset( $meta_value['header'][$repeater_index] ) ? $meta_value['header'][$repeater_index] : '';
      $data = isset( $meta_value['data'][$repeater_index] ) ? $meta_value['data'][$repeater_index] : '';
  ?>
        <tr class="a-table__row repeater-item">
          <td>
            <input type="text" name="<?php echo esc_attr( $field['id'] ); ?>[header][]" value="<?php echo esc_attr( $header ); ?>" class="widefat">
          </td>
          <td>
            <input type="text" name="<?php echo esc_attr( $field['id'] ); ?>[data][]" value="<?php echo esc_attr( $data ); ?>" class="widefat">
          </td>
          <td class="col-delete">
            <a href="#" class="button button-secondary button-delete-row"><?php _e( 'Delete', 'tcd-w' ); ?></a>
          </td>
        </tr>
  <?php
    }
  } else {
		//echo $clone . "\n";
  }

  echo '</tbody>';
  echo '</table>';
  echo ' <a href="#" class="button button-secondary button-add-row" data-clone="' . esc_attr( $clone ) . '">' . __( 'Add item', 'tcd-w' ) . '</a>';
  echo '</div>';

}

function render_type4_block( $field, $meta_value ) {

  $key = 'addindex';
  ob_start();
  ?>
  <div id="topt_repeater-<?php echo $key; ?>" class="repeater-item">
	  <table class="block-contents-table">
	    <tr class="block-contents-table__row">
	  		<th rowspan="2" class="block-contents-table__header">1</th>
        <td><?php _e( 'For desktop', 'tcd-w' ); ?><br><?php _e( 'Recommended size: width 1180px, height 530px', 'tcd-w' ); ?></td>
        <td class="u-center">
	  			<div class="cf cf_media_field hide-if-no-js">
	  				<input type="hidden" value="" id="<?php echo esc_attr( $field['id'] ); ?>_header_img_<?php echo $key; ?>" name="<?php echo esc_attr( $field['id'] ); ?>[header_img][]" class="cf_media_id">
	  				<div class="preview_field"></div>
	  				<div class="button_area">
	  					<input type="button" value="<?php _e( 'Select Image', 'tcd-w' ); ?>" class="cfmf-select-img button">
	  					<input type="button" value="<?php _e( 'Remove Image', 'tcd-w' ); ?>" class="cfmf-delete-img button hidden">
	  				</div>
	  			</div>
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
        <td><?php _e( 'For mobile', 'tcd-w' ); ?><br><?php _e( 'Recommended size: width 900px, height 480px', 'tcd-w' ); ?></td>
        <td class="u-center">
	  			<div class="cf cf_media_field hide-if-no-js">
	  				<input type="hidden" value="" id="<?php echo esc_attr( $field['id'] ); ?>_header_img_sp_<?php echo $key; ?>" name="<?php echo esc_attr( $field['id'] ); ?>[header_img_sp][]" class="cf_media_id">
	  				<div class="preview_field"></div>
	  				<div class="button_area">
	  					<input type="button" value="<?php _e( 'Select Image', 'tcd-w' ); ?>" class="cfmf-select-img button">
	  					<input type="button" value="<?php _e( 'Remove Image', 'tcd-w' ); ?>" class="cfmf-delete-img button hidden">
	  				</div>
	  			</div>
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
	  		<th rowspan="3" class="block-contents-table__header">2</th>
        <td><?php _e( 'Headline', 'tcd-w' ); ?></td>
        <td>
          <input class="large-text" type="text" name="<?php echo esc_attr( $field['id'] ); ?>[header_headline][]" value="">
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
        <td><?php _e( 'Font color', 'tcd-w' ); ?></td>
        <td class="u-center">
          <input class="cf-color-picker" type="text" name="<?php echo esc_attr( $field['id'] ); ?>[header_headline_color][]" value="#ffffff" data-default-color="#ffffff">
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
        <td><?php _e( 'Background color', 'tcd-w' ); ?></td>
        <td class="u-center">
          <input class="cf-color-picker" type="text" name="<?php echo esc_attr( $field['id'] ); ?>[header_headline_bg][]" value="#660000" data-default-color="#660000">
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
	  		<th rowspan="3" class="block-contents-table__header">3</th>
        <td><?php _e( 'Headline', 'tcd-w' ); ?></td>
        <td>
          <input class="large-text" type="text" name="<?php echo esc_attr( $field['id'] ); ?>[slider_headline][]" value="">
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
        <td><?php _e( 'Font color', 'tcd-w' ); ?></td>
        <td class="u-center">
          <input class="cf-color-picker" type="text" name="<?php echo esc_attr( $field['id'] ); ?>[slider_headline_color][]" value="#ffffff" data-default-color="#ffffff">
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
        <td><?php _e( 'Background color', 'tcd-w' ); ?></td>
        <td class="u-center">
          <input class="cf-color-picker" type="text" name="<?php echo esc_attr( $field['id'] ); ?>[slider_headline_bg][]" value="#000000" data-default-color="#000000">
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
	  		<th rowspan="3" class="block-contents-table__header">4</th>
        <td><?php _e( 'Recommended size: width 900px, height 480px', 'tcd-w' ); ?></td>
        <td class="u-center">
	  			<div class="cf cf_media_field hide-if-no-js">
	  				<input type="hidden" value="" id="<?php echo esc_attr( $field['id'] ); ?>_slider_img1_<?php echo $key; ?>" name="<?php echo esc_attr( $field['id'] ); ?>[slider_img1][]" class="cf_media_id">
	  				<div class="preview_field"></div>
	  				<div class="button_area">
	  					<input type="button" value="<?php _e( 'Select Image', 'tcd-w' ); ?>" class="cfmf-select-img button">
	  					<input type="button" value="<?php _e( 'Remove Image', 'tcd-w' ); ?>" class="cfmf-delete-img button hidden">
	  				</div>
	  			</div>
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
        <td><?php _e( 'Recommended size: width 900px, height 480px', 'tcd-w' ); ?></td>
        <td class="u-center">
	  			<div class="cf cf_media_field hide-if-no-js">
	  				<input type="hidden" value="" id="<?php echo esc_attr( $field['id'] ); ?>_slider_img2_<?php echo $key; ?>" name="<?php echo esc_attr( $field['id'] ); ?>[slider_img2][]" class="cf_media_id">
	  				<div class="preview_field"></div>
	  				<div class="button_area">
	  					<input type="button" value="<?php _e( 'Select Image', 'tcd-w' ); ?>" class="cfmf-select-img button">
	  					<input type="button" value="<?php _e( 'Remove Image', 'tcd-w' ); ?>" class="cfmf-delete-img button hidden">
	  				</div>
	  			</div>
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
        <td><?php _e( 'Recommended size: width 900px, height 480px', 'tcd-w' ); ?></td>
        <td class="u-center">
	  			<div class="cf cf_media_field hide-if-no-js">
	  				<input type="hidden" value="" id="<?php echo esc_attr( $field['id'] ); ?>_slider_img3_<?php echo $key; ?>" name="<?php echo esc_attr( $field['id'] ); ?>[slider_img3][]" class="cf_media_id">
	  				<div class="preview_field"></div>
	  				<div class="button_area">
	  					<input type="button" value="<?php _e( 'Select Image', 'tcd-w' ); ?>" class="cfmf-select-img button">
	  					<input type="button" value="<?php _e( 'Remove Image', 'tcd-w' ); ?>" class="cfmf-delete-img button hidden">
	  				</div>
	  			</div>
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
	  		<th class="block-contents-table__header">5</th>
        <td><?php _e( 'Description', 'tcd-w' ); ?></td>
        <td>
          <textarea rows="4" class="large-text" name="<?php echo esc_attr( $field['id'] ); ?>[desc][]"></textarea>
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
	  		<th class="block-contents-table__header">6</th>
        <td><?php _e( 'Recommended size: width 850px, height 850px', 'tcd-w' ); ?></td>
        <td class="u-center">
	  			<div class="cf cf_media_field hide-if-no-js">
	  				<input type="hidden" value="" id="<?php echo esc_attr( $field['id'] ); ?>_img_<?php echo $key; ?>" name="<?php echo esc_attr( $field['id'] ); ?>[img][]" class="cf_media_id">
	  				<div class="preview_field"></div>
	  				<div class="button_area">
	  					<input type="button" value="<?php _e( 'Select Image', 'tcd-w' ); ?>" class="cfmf-select-img button">
	  					<input type="button" value="<?php _e( 'Remove Image', 'tcd-w' ); ?>" class="cfmf-delete-img button hidden">
	  				</div>
	  			</div>
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
	  		<th rowspan="2" class="block-contents-table__header"><?php _e( 'Settings for B', 'tcd-w' ); ?></th>
        <td><?php _e( 'Recommended size: width 300px, height 300px', 'tcd-w' ); ?></td>
        <td class="u-center">
	  			<div class="cf cf_media_field hide-if-no-js">
	  				<input type="hidden" value="" id="<?php echo esc_attr( $field['id'] ); ?>_b_img_<?php echo $key; ?>" name="<?php echo esc_attr( $field['id'] ); ?>[b_img][]" class="cf_media_id">
	  				<div class="preview_field"></div>
	  				<div class="button_area">
	  					<input type="button" value="<?php _e( 'Select Image', 'tcd-w' ); ?>" class="cfmf-select-img button">
	  					<input type="button" value="<?php _e( 'Remove Image', 'tcd-w' ); ?>" class="cfmf-delete-img button hidden">
	  				</div>
	  			</div>
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
        <td><?php _e( 'Title', 'tcd-w' ); ?></td>
        <td>
          <textarea class="large-text" name="<?php echo esc_attr( $field['id'] ); ?>[b_title][]"></textarea>
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
	  		<th class="block-contents-table__header"><?php _e( 'Delete', 'tcd-w' ); ?></th>
        <td colspan="2" class="u-center">
          <p class="delete-row">
            <a href="#" class="button button-secondary button-delete-row"><?php _e( 'Delete item', 'tcd-w' ); ?></a>
          </p>
        </td>
	  	</tr>
	  </table>
  </div>
  <?php
	$clone = ob_get_clean();

	echo '<div class="repeater-wrapper" data-delete-confirm="' . __( 'Delete?', 'tcd-w' ) . '">' . "\n";
	echo '<div class="repeater ui-sortable">' . "\n";
  
  $meta_value = maybe_unserialize( $meta_value );

  if ( isset( $meta_value['header_headline'][0] ) ) {

    foreach( array_keys( $meta_value['header_headline'] ) as $repeater_index ) :

      $header_headline = $meta_value['header_headline'][$repeater_index];
      $header_img = isset( $meta_value['header_img'][$repeater_index] ) ? $meta_value['header_img'][$repeater_index] : '';
      $header_img_sp = isset( $meta_value['header_img_sp'][$repeater_index] ) ? $meta_value['header_img_sp'][$repeater_index] : '';
      $header_headline_color = isset( $meta_value['header_headline_color'][$repeater_index] ) ? $meta_value['header_headline_color'][$repeater_index] : '#ffffff';
      $header_headline_bg = isset( $meta_value['header_headline_bg'][$repeater_index] ) ? $meta_value['header_headline_bg'][$repeater_index] : '#660000';
      $slider_headline = isset( $meta_value['slider_headline'][$repeater_index] ) ? $meta_value['slider_headline'][$repeater_index] : '';
      $slider_headline_color = isset( $meta_value['slider_headline_color'][$repeater_index] ) ? $meta_value['slider_headline_color'][$repeater_index] : '#ffffff';
      $slider_headline_bg = isset( $meta_value['slider_headline_bg'][$repeater_index] ) ? $meta_value['slider_headline_bg'][$repeater_index] : '#000000';
      $desc = isset( $meta_value['desc'][$repeater_index] ) ? $meta_value['desc'][$repeater_index] : '';
      $img = isset( $meta_value['img'][$repeater_index] ) ? $meta_value['img'][$repeater_index] : '';
      for ( $i = 1; $i <= 3; $i++ ) {
        ${'slider_img' . $i} = isset( $meta_value['slider_img' . $i][$repeater_index] ) ? $meta_value['slider_img' . $i][$repeater_index] : '';
      }
      $b_img = isset( $meta_value['b_img'][$repeater_index] ) ? $meta_value['b_img'][$repeater_index] : '';
      $b_title = isset( $meta_value['b_title'][$repeater_index] ) ? $meta_value['b_title'][$repeater_index] : '';
  ?>
	<div class="repeater-item ui-sortable-handle">
	  <table class="block-contents-table">
	    <tr class="block-contents-table__row">
	  		<th rowspan="2" class="block-contents-table__header">1</th>
        <td><?php _e( 'For desktop', 'tcd-w' ); ?><br><?php _e( 'Recommended size: width 1180px, height 530px', 'tcd-w' ); ?></td>
        <td class="u-center">
	  			<div class="cf cf_media_field hide-if-no-js">
	  				<input type="hidden" value="<?php echo esc_attr( $header_img ); ?>" id="<?php echo esc_attr( $field['id'] ); ?>_header_img_<?php echo $repeater_index; ?>" name="<?php echo esc_attr( $field['id'] ); ?>[header_img][]" class="cf_media_id">
	  				<div class="preview_field">
              <?php if ( $header_img ) { echo wp_get_attachment_image( $header_img, 'medium' ); } ?>
            </div>
	  				<div class="button_area">
	  					<input type="button" value="<?php _e( 'Select Image', 'tcd-w' ); ?>" class="cfmf-select-img button">
	  					<input type="button" value="<?php _e( 'Remove Image', 'tcd-w' ); ?>" class="cfmf-delete-img button <?php if ( ! $header_img ) { echo ' hidden'; } ?>">
	  				</div>
	  			</div>
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
        <td><?php _e( 'For mobile', 'tcd-w' ); ?><br><?php _e( 'Recommended size: width 900px, height 480px', 'tcd-w' ); ?></td>
        <td class="u-center">
	  			<div class="cf cf_media_field hide-if-no-js">
	  				<input type="hidden" value="<?php echo esc_attr( $header_img_sp ); ?>" id="<?php echo esc_attr( $field['id'] ); ?>_header_img_sp_<?php echo $repeater_index; ?>" name="<?php echo esc_attr( $field['id'] ); ?>[header_img_sp][]" class="cf_media_id">
	  				<div class="preview_field">
              <?php if ( $header_img_sp ) { echo wp_get_attachment_image( $header_img_sp, 'medium' ); } ?>
            </div>
	  				<div class="button_area">
	  					<input type="button" value="<?php _e( 'Select Image', 'tcd-w' ); ?>" class="cfmf-select-img button">
	  					<input type="button" value="<?php _e( 'Remove Image', 'tcd-w' ); ?>" class="cfmf-delete-img button <?php if ( ! $header_img_sp ) { echo ' hidden'; } ?>">
	  				</div>
	  			</div>
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
	  		<th rowspan="3" class="block-contents-table__header">2</th>
        <td><?php _e( 'Headline', 'tcd-w' ); ?></td>
        <td>
          <input class="large-text" type="text" name="<?php echo esc_attr( $field['id'] ); ?>[header_headline][]" value="<?php echo esc_attr( $header_headline ); ?>">
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
        <td><?php _e( 'Font color', 'tcd-w' ); ?></td>
        <td class="u-center">
          <input class="cf-color-picker" type="text" name="<?php echo esc_attr( $field['id'] ); ?>[header_headline_color][]" value="<?php echo esc_attr( $header_headline_color ); ?>" data-default-color="#ffffff">
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
        <td><?php _e( 'Background color', 'tcd-w' ); ?></td>
        <td class="u-center">
          <input class="cf-color-picker" type="text" name="<?php echo esc_attr( $field['id'] ); ?>[header_headline_bg][]" value="<?php echo esc_attr( $header_headline_bg ); ?>" data-default-color="#660000">
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
	  		<th rowspan="3" class="block-contents-table__header">3</th>
        <td><?php _e( 'Headline', 'tcd-w' ); ?></td>
        <td>
          <input class="large-text" type="text" name="<?php echo esc_attr( $field['id'] ); ?>[slider_headline][]" value="<?php echo esc_attr( $slider_headline ); ?>">
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
        <td><?php _e( 'Font color', 'tcd-w' ); ?></td>
        <td class="u-center">
          <input class="cf-color-picker" type="text" name="<?php echo esc_attr( $field['id'] ); ?>[slider_headline_color][]" value="<?php echo esc_attr( $slider_headline_color ); ?>" data-default-color="#ffffff">
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
        <td><?php _e( 'Background color', 'tcd-w' ); ?></td>
        <td class="u-center">
          <input class="cf-color-picker" type="text" name="<?php echo esc_attr( $field['id'] ); ?>[slider_headline_bg][]" value="<?php echo esc_attr( $slider_headline_bg ); ?>" data-default-color="#000000">
        </td>
	  	</tr>
      <?php for ( $i = 1; $i <= 3; $i++ ) : ?>
	    <tr class="block-contents-table__row">
        <?php if ( 1 === $i ) : ?>
	  		<th rowspan="3" class="block-contents-table__header">4</th>
        <?php endif; ?>
        <td><?php _e( 'Recommended size: width 900px, height 480px', 'tcd-w' ); ?></td>
        <td class="u-center">
	  			<div class="cf cf_media_field hide-if-no-js">
	  				<input type="hidden" value="<?php echo esc_attr( ${'slider_img' . $i} ); ?>" id="<?php echo esc_attr( $field['id'] ); ?>_slider_img<?php echo $i; ?>_<?php echo $repeater_index; ?>" name="<?php echo esc_attr( $field['id'] ); ?>[slider_img<?php echo $i; ?>][]" class="cf_media_id">
	  				<div class="preview_field">
              <?php if ( ${'slider_img' . $i} ) { echo wp_get_attachment_image( ${'slider_img' . $i}, 'medium' ); } ?>
            </div>
	  				<div class="button_area">
	  					<input type="button" value="<?php _e( 'Select Image', 'tcd-w' ); ?>" class="cfmf-select-img button">
	  					<input type="button" value="<?php _e( 'Remove Image', 'tcd-w' ); ?>" class="cfmf-delete-img button<?php if ( ! ${'slider_img' . $i} ) { echo ' hidden'; } ?>">
	  				</div>
	  			</div>
        </td>
	  	</tr>
      <?php endfor; ?>
	    <tr class="block-contents-table__row">
	  		<th class="block-contents-table__header">5</th>
        <td><?php _e( 'Description', 'tcd-w' ); ?></td>
        <td>
          <textarea rows="4" class="large-text" name="<?php echo esc_attr( $field['id'] ); ?>[desc][]"><?php echo $desc; // No escape ?></textarea>
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
	  		<th class="block-contents-table__header">6</th>
        <td><?php _e( 'Recommended size: width 850px, height 850px', 'tcd-w' ); ?></td>
        <td class="u-center">
	  			<div class="cf cf_media_field hide-if-no-js">
	  				<input type="hidden" value="<?php echo esc_attr( $img ); ?>" id="<?php echo esc_attr( $field['id'] ); ?>_img_<?php echo $repeater_index; ?>" name="<?php echo esc_attr( $field['id'] ); ?>[img][]" class="cf_media_id">
	  				<div class="preview_field">
              <?php if ( $img ) { echo wp_get_attachment_image( $img, 'medium' ); } ?>
            </div>
	  				<div class="button_area">
	  					<input type="button" value="<?php _e( 'Select Image', 'tcd-w' ); ?>" class="cfmf-select-img button">
	  					<input type="button" value="<?php _e( 'Remove Image', 'tcd-w' ); ?>" class="cfmf-delete-img button<?php if ( ! $img ) { echo ' hidden'; } ?>">
	  				</div>
	  			</div>
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
	  		<th rowspan="2" class="block-contents-table__header"><?php _e( 'Settings for B', 'tcd-w' ); ?></th>
        <td><?php _e( 'Recommended size: width 300px, height 300px', 'tcd-w' ); ?></td>
        <td class="u-center">
	  			<div class="cf cf_media_field hide-if-no-js">
	  				<input type="hidden" value="<?php echo esc_attr( $b_img ); ?>" id="<?php echo esc_attr( $field['id'] ); ?>_b_img_<?php echo $repeater_index; ?>" name="<?php echo esc_attr( $field['id'] ); ?>[b_img][]" class="cf_media_id">
	  				<div class="preview_field">
              <?php if ( $b_img ) { echo wp_get_attachment_image( $b_img, 'medium' ); } ?>
            </div>
	  				<div class="button_area">
	  					<input type="button" value="<?php _e( 'Select Image', 'tcd-w' ); ?>" class="cfmf-select-img button">
	  					<input type="button" value="<?php _e( 'Remove Image', 'tcd-w' ); ?>" class="cfmf-delete-img button<?php if ( ! $b_img ) { echo ' hidden'; } ?>">
	  				</div>
	  			</div>
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
        <td><?php _e( 'Title', 'tcd-w' ); ?></td>
        <td>
          <textarea class="large-text" name="<?php echo esc_attr( $field['id'] ); ?>[b_title][]"><?php echo esc_textarea( $b_title ); ?></textarea>
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
	  		<th class="block-contents-table__header"><?php _e( 'Delete', 'tcd-w' ); ?></th>
        <td colspan="2" class="u-center">
          <p class="delete-row">
            <a href="#" class="button button-secondary button-delete-row"><?php _e( 'Delete item', 'tcd-w' ); ?></a>
          </p>
        </td>
	  	</tr>
	  </table>
  </div>
  <?php
    endforeach;
  } else {
		//echo $clone . "\n";
  }

  echo '</div>';
  echo ' <a href="#" class="button button-secondary button-add-row" data-clone="' . esc_attr( $clone ) . '">' . __( 'Add item', 'tcd-w' ) . '</a>';
  echo '</div>';

}

function render_type5_block( $field, $meta_value ) {

  $key = 'addindex';
  ob_start();
  ?>
  <div id="topt_repeater-<?php echo $key; ?>" class="repeater-item">
	  <table class="block-contents-table">
	    <tr class="block-contents-table__row">
	  		<th class="block-contents-table__header"><?php _e( 'Layout', 'tcd-w' ); ?></th>
        <td class="u-center">
          <figure>
            <img src="<?php echo get_template_directory_uri(); ?>/admin/assets/images/tmp-ryori-type1.png" alt="">
          </figure>
          <label><input type="radio" name="<?php echo esc_attr( $field['id'] ); ?>[layout][<?php echo $key; ?>]" value="type1" checked="checked"><?php _e( 'Type1', 'tcd-w' ); ?></label>
        </td>
        <td class="u-center">
          <figure>
            <img src="<?php echo get_template_directory_uri(); ?>/admin/assets/images/tmp-ryori-type2.png" alt="">
          </figure>
          <label><input type="radio" name="<?php echo esc_attr( $field['id'] ); ?>[layout][<?php echo $key; ?>]" value="type2"><?php _e( 'Type2', 'tcd-w' ); ?></label>
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
	  		<th rowspan="2" class="block-contents-table__header"><?php _e( 'Color', 'tcd-w' ); ?></th>
        <td><?php _e( 'Font color', 'tcd-w' ); ?></td>
        <td class="u-center">
          <input class="cf-color-picker" type="text" name="<?php echo esc_attr( $field['id'] ); ?>[color][]" data-default-color="#000000" value="#000000">
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
        <td><?php _e( 'Background color', 'tcd-w' ); ?></td>
        <td class="u-center">
          <input class="cf-color-picker" type="text" name="<?php echo esc_attr( $field['id'] ); ?>[bg][]" data-default-color="#f4f1ed" value="#f4f1ed">
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
	  		<th rowspan="3" class="block-contents-table__header">1</th>
        <td><?php _e( 'Recommended size: width 900px, height 900px', 'tcd-w' ); ?></td>
        <td class="u-center">
	  			<div class="cf cf_media_field hide-if-no-js">
	  				<input type="hidden" value="" id="<?php echo esc_attr( $field['id'] ); ?>_slider_img1_<?php echo $key; ?>" name="<?php echo esc_attr( $field['id'] ); ?>[slider_img1][]" class="cf_media_id">
	  				<div class="preview_field"></div>
	  				<div class="button_area">
	  					<input type="button" value="<?php _e( 'Select Image', 'tcd-w' ); ?>" class="cfmf-select-img button">
	  					<input type="button" value="<?php _e( 'Remove Image', 'tcd-w' ); ?>" class="cfmf-delete-img button hidden">
	  				</div>
	  			</div>
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
        <td><?php _e( 'Recommended size: width 900px, height 900px', 'tcd-w' ); ?></td>
        <td class="u-center">
	  			<div class="cf cf_media_field hide-if-no-js">
	  				<input type="hidden" value="" id="<?php echo esc_attr( $field['id'] ); ?>_slider_img2_<?php echo $key; ?>" name="<?php echo esc_attr( $field['id'] ); ?>[slider_img2][]" class="cf_media_id">
	  				<div class="preview_field"></div>
	  				<div class="button_area">
	  					<input type="button" value="<?php _e( 'Select Image', 'tcd-w' ); ?>" class="cfmf-select-img button">
	  					<input type="button" value="<?php _e( 'Remove Image', 'tcd-w' ); ?>" class="cfmf-delete-img button hidden">
	  				</div>
	  			</div>
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
        <td><?php _e( 'Recommended size: width 900px, height 900px', 'tcd-w' ); ?></td>
        <td class="u-center">
	  			<div class="cf cf_media_field hide-if-no-js">
	  				<input type="hidden" value="" id="<?php echo esc_attr( $field['id'] ); ?>_slider_img3_<?php echo $key; ?>" name="<?php echo esc_attr( $field['id'] ); ?>[slider_img3][]" class="cf_media_id">
	  				<div class="preview_field"></div>
	  				<div class="button_area">
	  					<input type="button" value="<?php _e( 'Select Image', 'tcd-w' ); ?>" class="cfmf-select-img button">
	  					<input type="button" value="<?php _e( 'Remove Image', 'tcd-w' ); ?>" class="cfmf-delete-img button hidden">
	  				</div>
	  			</div>
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
	  		<th rowspan="3" class="block-contents-table__header">2</th>
        <td><?php _e( 'Headline', 'tcd-w' ); ?></td>
        <td>
          <input class="large-text" type="text" name="<?php echo esc_attr( $field['id'] ); ?>[headline][]" value="">
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
        <td><?php _e( 'Font color', 'tcd-w' ); ?></td>
        <td class="u-center">
          <input class="cf-color-picker" type="text" name="<?php echo esc_attr( $field['id'] ); ?>[headline_color][]" value="#ffffff" data-default-color="#ffffff">
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
        <td><?php _e( 'Background color', 'tcd-w' ); ?></td>
        <td class="u-center">
          <input class="cf-color-picker" type="text" name="<?php echo esc_attr( $field['id'] ); ?>[headline_bg][]" value="#204000" data-default-color="#204000">
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
	  		<th class="block-contents-table__header">3</th>
        <td><?php _e( 'Description', 'tcd-w' ); ?></td>
        <td>
          <textarea rows="4" class="large-text" name="<?php echo esc_attr( $field['id'] ); ?>[desc][]"></textarea>
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
	  		<th rowspan="2" class="block-contents-table__header">4</th>
        <td><?php _e( 'Recommended size: width 420px, height 420px', 'tcd-w' ); ?></td>
        <td class="u-center">
	  			<div class="cf cf_media_field hide-if-no-js">
	  				<input type="hidden" value="" id="<?php echo esc_attr( $field['id'] ); ?>_img1_<?php echo $key; ?>" name="<?php echo esc_attr( $field['id'] ); ?>[img1][]" class="cf_media_id">
	  				<div class="preview_field"></div>
	  				<div class="button_area">
	  					<input type="button" value="<?php _e( 'Select Image', 'tcd-w' ); ?>" class="cfmf-select-img button">
	  					<input type="button" value="<?php _e( 'Remove Image', 'tcd-w' ); ?>" class="cfmf-delete-img button hidden">
	  				</div>
	  			</div>
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
        <td><?php _e( 'Recommended size: width 420px, height 420px', 'tcd-w' ); ?></td>
        <td class="u-center">
	  			<div class="cf cf_media_field hide-if-no-js">
	  				<input type="hidden" value="" id="<?php echo esc_attr( $field['id'] ); ?>_img2_<?php echo $key; ?>" name="<?php echo esc_attr( $field['id'] ); ?>[img2][]" class="cf_media_id">
	  				<div class="preview_field"></div>
	  				<div class="button_area">
	  					<input type="button" value="<?php _e( 'Select Image', 'tcd-w' ); ?>" class="cfmf-select-img button">
	  					<input type="button" value="<?php _e( 'Remove Image', 'tcd-w' ); ?>" class="cfmf-delete-img button hidden">
	  				</div>
	  			</div>
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
	  		<th class="block-contents-table__header"><?php _e( 'Delete', 'tcd-w' ); ?></th>
        <td colspan="2" class="u-center">
          <p class="delete-row">
            <a href="#" class="button button-secondary button-delete-row"><?php _e( 'Delete item', 'tcd-w' ); ?></a>
          </p>
        </td>
	  	</tr>
	  </table>
  </div>
  <?php
	$clone = ob_get_clean();

	echo '<div class="repeater-wrapper" data-delete-confirm="' . __( 'Delete?', 'tcd-w' ) . '">' . "\n";
	echo '<div class="repeater ui-sortable">' . "\n";

  $meta_value = maybe_unserialize( $meta_value );

  if ( isset( $meta_value['headline'][0] ) ) {

    foreach( array_keys( $meta_value['headline'] ) as $repeater_index ) :

      $layout = isset( $meta_value['layout'][$repeater_index] ) ? $meta_value['layout'][$repeater_index] : 'type1';
      $color = isset( $meta_value['color'][$repeater_index] ) ? $meta_value['color'][$repeater_index] : '#000000';
      $bg = isset( $meta_value['bg'][$repeater_index] ) ? $meta_value['bg'][$repeater_index] : '#f4f1ed';
      for ( $i = 1; $i <= 3; $i++ ) {
        ${'slider_img' . $i} = isset( $meta_value['slider_img' . $i][$repeater_index] ) ? $meta_value['slider_img' . $i][$repeater_index] : '';
      }
      $headline = $meta_value['headline'][$repeater_index];
      $headline_color = isset( $meta_value['headline_color'][$repeater_index] ) ? $meta_value['headline_color'][$repeater_index] : '#ffffff';
      $headline_bg = isset( $meta_value['headline_bg'][$repeater_index] ) ? $meta_value['headline_bg'][$repeater_index] : '#204000';
      $desc = isset( $meta_value['desc'][$repeater_index] ) ? $meta_value['desc'][$repeater_index] : '';

      $img1 = isset( $meta_value['img1'][$repeater_index] ) ? $meta_value['img1'][$repeater_index] : '';
      $img2 = isset( $meta_value['img2'][$repeater_index] ) ? $meta_value['img2'][$repeater_index] : '';

  ?>
	<div class="repeater-item ui-sortable-handle">
	  <table class="block-contents-table">
	    <tr class="block-contents-table__row">
	  		<th class="block-contents-table__header"><?php _e( 'Layout', 'tcd-w' ); ?></th>
        <td class="u-center">
          <figure>
            <img src="<?php echo get_template_directory_uri(); ?>/admin/assets/images/tmp-ryori-type1.png" alt="">
          </figure>
          <label><input type="radio" name="<?php echo esc_attr( $field['id'] ); ?>[layout][<?php echo $repeater_index; ?>]" value="type1" <?php checked( 'type1', $layout ); ?>><?php _e( 'Type1', 'tcd-w' ); ?></label>
        </td>
        <td class="u-center">
          <figure>
            <img src="<?php echo get_template_directory_uri(); ?>/admin/assets/images/tmp-ryori-type2.png" alt="">
          </figure>
          <label><input type="radio" name="<?php echo esc_attr( $field['id'] ); ?>[layout][<?php echo $repeater_index; ?>]" value="type2" <?php checked( 'type2', $layout ); ?>><?php _e( 'Type2', 'tcd-w' ); ?></label>
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
	  		<th rowspan="2" class="block-contents-table__header"><?php _e( 'Color', 'tcd-w' ); ?></th>
        <td><?php _e( 'Font color', 'tcd-w' ); ?></td>
        <td class="u-center">
          <input class="cf-color-picker" type="text" name="<?php echo esc_attr( $field['id'] ); ?>[color][]" data-default-color="#000000" value="<?php echo esc_attr( $color ); ?>">
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
        <td><?php _e( 'Background color', 'tcd-w' ); ?></td>
        <td class="u-center">
          <input class="cf-color-picker" type="text" name="<?php echo esc_attr( $field['id'] ); ?>[bg][]" data-default-color="#f4f1ed" value="<?php echo esc_attr( $bg ); ?>">
        </td>
	  	</tr>
      <?php for ( $i = 1; $i <= 3; $i++ ) : ?>
	    <tr class="block-contents-table__row">
        <?php if ( 1 === $i ) : ?>
	  		<th rowspan="3" class="block-contents-table__header">1</th>
        <?php endif; ?>
        <td><?php _e( 'Recommended size: width 900px, height 900px', 'tcd-w' ); ?></td>
        <td class="u-center">
	  			<div class="cf cf_media_field hide-if-no-js">
	  				<input type="hidden" value="<?php echo esc_attr( ${'slider_img' . $i} ); ?>" id="<?php echo esc_attr( $field['id'] ); ?>_slider_img<?php echo $i; ?>_<?php echo $repeater_index; ?>" name="<?php echo esc_attr( $field['id'] ); ?>[slider_img<?php echo $i; ?>][]" class="cf_media_id">
	  				<div class="preview_field">
              <?php if ( ${'slider_img' . $i} ) { echo wp_get_attachment_image( ${'slider_img' . $i}, 'medium' ); } ?>
            </div>
	  				<div class="button_area">
	  					<input type="button" value="<?php _e( 'Select Image', 'tcd-w' ); ?>" class="cfmf-select-img button">
	  					<input type="button" value="<?php _e( 'Remove Image', 'tcd-w' ); ?>" class="cfmf-delete-img button<?php if ( ! ${'slider_img' . $i} ) { echo ' hidden'; } ?>">
	  				</div>
	  			</div>
        </td>
	  	</tr>
      <?php endfor; ?>
	    <tr class="block-contents-table__row">
	  		<th rowspan="3" class="block-contents-table__header">2</th>
        <td><?php _e( 'Headline', 'tcd-w' ); ?></td>
        <td>
          <input class="large-text" type="text" name="<?php echo esc_attr( $field['id'] ); ?>[headline][]" value="<?php echo esc_attr( $headline ); ?>">
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
        <td><?php _e( 'Font color', 'tcd-w' ); ?></td>
        <td class="u-center">
          <input class="cf-color-picker" type="text" name="<?php echo esc_attr( $field['id'] ); ?>[headline_color][]" value="<?php echo esc_attr( $headline_color ); ?>" data-default-color="#ffffff">
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
        <td><?php _e( 'Background color', 'tcd-w' ); ?></td>
        <td class="u-center">
          <input class="cf-color-picker" type="text" name="<?php echo esc_attr( $field['id'] ); ?>[headline_bg][]" value="<?php echo esc_attr( $headline_bg ); ?>" data-default-color="#204000">
        </td>
	  	</tr>
	    <tr class="block-contents-table__row">
	  		<th class="block-contents-table__header">3</th>
        <td><?php _e( 'Description', 'tcd-w' ); ?></td>
        <td>
          <textarea rows="4" class="large-text" name="<?php echo esc_attr( $field['id'] ); ?>[desc][]"><?php echo esc_textarea( $desc ); ?></textarea>
        </td>
	  	</tr>
      <?php for ( $i = 1; $i <= 2; $i++ ) : ?>
	    <tr class="block-contents-table__row">
        <?php if ( 1 === $i ) : ?>
	  		<th rowspan="2" class="block-contents-table__header">4</th>
        <?php endif; ?>
        <td><?php _e( 'Recommended size: width 420px, height 420px', 'tcd-w' ); ?></td>
        <td class="u-center">
	  			<div class="cf cf_media_field hide-if-no-js">
	  				<input type="hidden" value="<?php echo esc_attr( ${'img' . $i} ); ?>" id="<?php echo esc_attr( $field['id'] ); ?>_img<?php echo $i; ?>_<?php echo $repeater_index; ?>" name="<?php echo esc_attr( $field['id'] ); ?>[img<?php echo $i; ?>][]" class="cf_media_id">
	  				<div class="preview_field">
              <?php if ( ${'img' . $i} ) { echo wp_get_attachment_image( ${'img' . $i}, 'medium' ); } ?>
            </div>
	  				<div class="button_area">
	  					<input type="button" value="<?php _e( 'Select Image', 'tcd-w' ); ?>" class="cfmf-select-img button">
	  					<input type="button" value="<?php _e( 'Remove Image', 'tcd-w' ); ?>" class="cfmf-delete-img button<?php if ( ! ${'img' . $i} ) { echo ' hidden'; } ?>">
	  				</div>
	  			</div>
        </td>
	  	</tr>
      <?php endfor; ?>
	    <tr class="block-contents-table__row">
	  		<th class="block-contents-table__header"><?php _e( 'Delete', 'tcd-w' ); ?></th>
        <td colspan="2" class="u-center">
          <p class="delete-row">
            <a href="#" class="button button-secondary button-delete-row"><?php _e( 'Delete item', 'tcd-w' ); ?></a>
          </p>
        </td>
	  	</tr>
	  </table>
  </div>
  <?php
    endforeach;
  } else {
		//echo $clone . "\n";
  }

  echo '</div>';
  echo ' <a href="#" class="button button-secondary button-add-row" data-clone="' . esc_attr( $clone ) . '">' . __( 'Add item', 'tcd-w' ) . '</a>';
  echo '</div>';

}

function render_type5_f( $field, $meta_value ) {

  $key = 'addindex';
  ob_start();
  ?>
        <tr class="block-contents-table__row repeater-item">
          <td class="u-center">
	  			  <div class="cf cf_media_field hide-if-no-js">
	  			  	<input type="hidden" value="" id="<?php echo esc_attr( $field['id'] ); ?>_img_<?php echo $key; ?>" name="<?php echo esc_attr( $field['id'] ); ?>[img][]" class="cf_media_id">
	  			  	<div class="preview_field"></div>
	  			  	<div class="button_area">
	  			  		<input type="button" value="<?php _e( 'Select Image', 'tcd-w' ); ?>" class="cfmf-select-img button">
	  			  		<input type="button" value="<?php _e( 'Remove Image', 'tcd-w' ); ?>" class="cfmf-delete-img button hidden">
	  			  	</div>
	  			  </div>
          </td>
          <td>
            <textarea rows="4" class="widefat" name="<?php echo esc_attr( $field['id'] ); ?>[desc][]"></textarea>
          </td>
          <td class="col-delete u-center">
            <a href="#" class="button button-secondary button-delete-row"><?php _e( 'Delete', 'tcd-w' ); ?></a>
          </td>
        </tr>
  <?php
	$clone = ob_get_clean();

	echo '<div class="repeater-wrapper" data-delete-confirm="' . __( 'Delete?', 'tcd-w' ) . '">' . "\n";
  echo '<table class="block-contents-table repeater">' . "\n";
  echo '<thead>' . "\n";
  echo '<tr class="block-contents-table__row repeater-item">';
  echo '<th class="block-contents-table__header block-contents-table__header--auto">' . __( 'Image', 'tcd-w' ) . __( '(Recommended size: width 420px, height 420px)', 'tcd-w' ) . '</th>';
  echo '<th class="block-contents-table__header block-contents-table__header--auto">' . __( 'Description', 'tcd-w' ) . '</th>';
  echo '<th class="block-contents-table__header"></th>';
  echo '</tr>' . "\n";
  echo '</thead>' . "\n";
  echo '<tbody>' . "\n";
    
  $meta_value = maybe_unserialize( $meta_value );

  if ( isset( $meta_value['desc'][0] ) ) {

    foreach( array_keys( $meta_value['desc'] ) as $repeater_index ) {
      $desc = $meta_value['desc'][$repeater_index];
      $img = isset( $meta_value['img'][$repeater_index] ) ? $meta_value['img'][$repeater_index] : '';
  ?>
        <tr class="block-contents-table__row repeater-item">
          <td class="u-center">
	  			  <div class="cf cf_media_field hide-if-no-js">
	  			  	<input type="hidden" value="<?php echo esc_attr( $img ); ?>" id="<?php echo esc_attr( $field['id'] ); ?>_img_<?php echo $repeater_index; ?>" name="<?php echo esc_attr( $field['id'] ); ?>[img][]" class="cf_media_id">
	  			  	<div class="preview_field">
                <?php if ( $img ) { echo wp_get_attachment_image( $img, 'medium' ); } ?>
              </div>
	  			  	<div class="button_area">
	  			  		<input type="button" value="<?php _e( 'Select Image', 'tcd-w' ); ?>" class="cfmf-select-img button">
	  			  		<input type="button" value="<?php _e( 'Remove Image', 'tcd-w' ); ?>" class="cfmf-delete-img button<?php if ( ! $img ) { echo ' hidden'; } ?>">
	  			  	</div>
	  			  </div>
          </td>
          <td>
            <textarea rows="4" class="large-text" name="<?php echo esc_attr( $field['id'] ); ?>[desc][]"><?php echo esc_textarea( $desc ); ?></textarea>
          </td>
          <td class="col-delete u-center">
            <a href="#" class="button button-secondary button-delete-row"><?php _e( 'Delete', 'tcd-w' ); ?></a>
          </td>
        </tr>
  <?php
    }
  } else {
		//echo $clone . "\n";
  }

  echo '</tbody>';
  echo '</table>';
  echo ' <a href="#" class="button button-secondary button-add-row" data-clone="' . esc_attr( $clone ) . '">' . __( 'Add item', 'tcd-w' ) . '</a>';
  echo '</div>';

}
