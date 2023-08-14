<?php
/**
 * Adds a meta box.
 */
class TCD_Meta_Box {

	/**
	 * Meta box ID
	 *
	 * @var string
	 */
	protected $id = '';

	/**
	 * Meta box title
	 *
	 * @var string
	 */
	protected $title = '';
		
	/**
	 * Meta box screen
	 *
	 * @var array
	 */
	protected $screen = array();
	
	/**
	 * Meta box context
	 *
	 * @var string
	 */
	protected $context = '';

	/**
	 * Post ID
	 * 
	 * @var int
	 */
	protected $post_id = '';

	/**
	 * Meta box fields
	 *
	 * @var array
	 */
	protected $fields = array();

	/**
	 * HTML before rendering
	 *
	 * @var string
	 */
	//protected $before_render = '';

	/**
	 * HTML after rendering
	 *
	 * @var string
	 */
	//protected $after_render = '';

	/**
	 * Custom fields id arrays used in save action
	 *
	 * @var array
	 */
	protected $cf_keys = array();

	/**
	 * @param array $args {
	 *     An array of arguments.
	 *
	 *     @type string $id          ID of the meta box.
	 *     @type string $title       Optional. Title of the meta box.
	 *     @type array  $screen      Optional. Screens on which to show the box.
	 *     @type string $context     Optional. The context within the screen where the boxes should display. 
	 *     @type string $description Optional. Description of the meta box.
	 *     @type array  $fields  {
	 *         Optional. An array of meta box fields.
	 *
	 *         @type string       $id           ID of the field.
	 *         @type string       $title        Title of the field.
	 *         @type string       $description  Descripton of the field.
	 *         @type string       $type         Type of the field (checkbox, color, image, number, select, textarea, text).
	 *         @type string|array $default      Default value of the field.
	 *         @type array        $options      Options of checkbox, select box.
	 *         @type string       $step         You can add step attribute to the number field.
	 *         @type string       $unit         You can add unit to the number field (e.g., 'px').
	 *      }
	 * }
	 */
	public function __construct( $args ) {

		$defaults = array(
			'title' => '',
			'screen' => array( 'post' ),
			'context' => 'normal',
			'fields' => array(),
			'description' => '',
			'cf_keys' => array()
		);
		$args = wp_parse_args( $args, $defaults );

		$this->id = $args['id'];
		$this->title = $args['title'];
		$this->screen = $args['screen'];
		$this->context = $args['context'];
		$this->fields = $args['fields'];
		$this->description = $args['description'];
		$this->cf_keys = $args['cf_keys'];
		//$this->before_render = $args['before_render'];
		//$this->after_render = $args['after_render'];

		add_action( 'add_meta_boxes', array( $this, 'register' ) );
		add_action( 'save_post', array( $this, 'save' ) );

	}

	public function register() {

		add_meta_box( 
			$this->id, 
			$this->title, 
			array( $this, 'render' ), 
			$this->screen, 
			$this->context 
		);

	}

	public function render( $post ) {

		$this->post_id = $post->ID;

		$defaults = array(
			'description' => '',
			'before_field' => '',
			'after_field' => '',
			'before_title' => '',
			'after_title' => ''
		);
	
		//echo $this->before_render; 

		wp_nonce_field( 'save_' . $this->id, $this->id . '_nonce' );

		if ( $this->description ) {
			echo '<p>' . nl2br( esc_html( $this->description ) ) . '</p>';
		}

		foreach ( $this->fields as $field ) {

			$field = wp_parse_args( $field, $defaults );

			// assign $value if $field['type'] is not 'sub_box'
			if ( 'sub_box' !== $field['type'] ) {
				$value = get_post_meta( $this->post_id, $field['id'], true );
				$value = ( '' === $value && isset( $field['default'] ) ) ? $field['default'] : $value;
			}

			switch ( $field['type'] ) {
				case 'checkbox' :
					$this->render_checkbox( $field, $value );
					break;
				case 'color' :
					$this->render_color( $field, $value );
					break;
				case 'image' :
					$this->render_image( $field, $value );
					break;
				case 'number' :
					$this->render_number( $field, $value );
					break;
				case 'radio' :	
					$this->render_radio( $field, $value );
					break;
				case 'select' :
					$this->render_select( $field, $value );
					break;
				case 'textarea' :
					$this->render_textarea( $field, $value );
					break;
				case 'text' :
					$this->render_text( $field, $value );
					break;
				case 'repeater_table' :
					$this->render_repeater_table( $field, $value );
					break;
				case 'sub_box' :
					$this->render_subbox( $field );
					break;
			}
		}
		//echo $this->after_render;

	}

	public function save( $post_id ) {

		// Check if our nonce is set.
		if ( ! isset( $_POST[$this->id . '_nonce'] ) ) return;

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST[$this->id . '_nonce'], 'save_' . $this->id  ) ) {
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
		} else {
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return $post_id; 
			}
		}

  	// save or delete
		// get only ids from fields array
		// TODO sub_box のidの処理
  	$cf_keys = array_merge( array_column( $this->fields, 'id' ), $this->cf_keys );

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

	/**
	 * Render a checkbox field
	 */
	public function render_checkbox( $field, $value ) {

		echo $field['before_field']; 
		echo $field['before_title'] . esc_html( $field['title'] ) . $field['after_title'];
		if ( $field['description'] ) {
			echo '<p>' . nl2br( esc_html( $field['description'] ) ) . '</p>';
		}
		?>
		<label><input type="checkbox" name="<?php echo esc_attr( $field['id'] ); ?>" value="<?php echo esc_attr( $field['options'][0]['value'] ); ?>" <?php checked( $field['options'][0]['value'], $value ); ?>><?php echo esc_html( $field['options'][0]['label'] ); ?></label>
		<?php
		echo $field['after_field'];
	}

	/**
	 * Render a color field
	 */
	public function render_color( $field, $value ) {

		echo $field['before_field']; 
		echo $field['before_title'] . esc_html( $field['title'] ) . $field['after_title'];
		if ( $field['description'] ) { 
			echo '<p>' . nl2br( esc_html( $field['description'] ) ) . '</p>';
		}
		?>
		<input type="text" class="c-color-picker" data-default-color="<?php echo esc_attr( $field['default'] ); ?>" name="<?php echo esc_attr( $field['id'] ); ?>" value="<?php echo esc_attr( $value ); ?>">
		<?php
		echo $field['after_field'];
	}

	/**
	 * Render a image field
	 */
	public function render_image( $field, $value ) {

		echo $field['before_field'];
		echo $field['before_title'] . esc_html( $field['title'] ) . $field['after_title'];
		if ( $field['description'] ) {
			echo '<p>' . nl2br( esc_html( $field['description'] ) ) . '</p>';
		}
		?>
		<div class="cf cf_media_field hide-if-no-js">
			<input type="hidden" class="cf_media_id" id="<?php echo esc_attr( $field['id'] ); ?>" name="<?php echo esc_attr( $field['id'] ); ?>" value="<?php echo esc_attr( $value ); ?>">
			<div class="preview_field">
				<?php if ( $value ) { echo wp_get_attachment_image( $value, 'medium' ); } ?>
			</div>
			<div class="button_area">
				<input type="button" class="cfmf-select-img button" value="<?php _e( 'Select Image', 'tcd-w' ); ?>">
				<input type="button" class="cfmf-delete-img button<?php if ( ! $value ) { echo ' hidden'; } ?>" value="<?php _e( 'Remove Image', 'tcd-w' ); ?>">
			</div>
		</div>
	<?php
		echo $field['after_field'];
	}

	/**
	 * Render a number field
	 */
	// TODO
	// min, max属性に対応
	public function render_number( $field, $value ) {

		echo $field['before_field']; 
		echo $field['before_title'] . esc_html( $field['title'] ) . $field['after_title'];
		if ( $field['description'] ) {
			echo '<p>' . nl2br( esc_html( $field['description'] ) ) . '</p>';
		}
		?>
		<input type="number" class="tiny-text" id="<?php echo esc_attr( $field['id'] ); ?>" name="<?php echo esc_attr( $field['id'] ); ?>" value="<?php echo esc_attr( $value ); ?>"<?php if ( isset( $field['step'] ) ) { echo ' step="' . esc_attr( $field['step'] ) . '"'; } ?>> <?php if ( isset( $field['unit'] ) ) { echo esc_html( $field['unit'] ); } ?>
		<?php
		echo $field['after_field'];
	}

	/**
	 * Render radio fields
	 */
	public function render_radio( $field, $value ) {

		echo $field['before_field']; 
		echo $field['before_title'] . esc_html( $field['title'] ) . $field['after_title'];
		if ( $field['description'] ) {
			echo '<p>' . nl2br( esc_html( $field['description'] ) ) . '</p>';
		}
		?>
		<?php foreach ( $field['options'] as $option ) : ?>
		<p><label class="<?php echo esc_attr( $field['id'] ); ?>"><input type="radio" name="<?php echo esc_attr( $field['id'] ); ?>" value="<?php echo esc_attr( $option['value'] ); ?>" <?php checked( $option['value'], $value ); ?>> <?php echo esc_html( $option['label'] ); ?></label></p>
		<?php endforeach; ?>
		<?php
		echo $field['after_field'];
	}
	
	/**
	 * Render a select field
	 */
	public function render_select( $field, $value ) {

		echo $field['before_field']; 
		echo $field['before_title'] . esc_html( $field['title'] ) . $field['after_title'];
		if ( $field['description'] ) {
			echo '<p>' . nl2br( esc_html( $field['description'] ) ) . '</p>';
		}
		?>
		<select name="<?php echo esc_attr( $field['id'] ); ?>">
      <option value=""><?php _e( '— Select —', 'tcd-w' ); ?></option>
			<?php foreach( $field['options'] as $option ) : ?>
			<option value="<?php echo esc_attr( $option['value'] ); ?>" <?php selected( $option['value'], $value ); ?>><?php echo esc_html( $option['label'] ); ?></option>
			<?php endforeach; ?>
		</select>
		<?php
		echo $field['after_field'];
	}

	/**
	 * Render a textarea field
	 */
	public function render_textarea( $field, $value ) {

		echo $field['before_field']; 
		echo $field['before_title'] . esc_html( $field['title'] ) . $field['after_title'];
		if ( $field['description'] ) {
			echo '<p>' . nl2br( esc_html( $field['description'] ) ) . '</p>';
		}
		?>
		<textarea class="large-text" id="<?php echo esc_attr( $field['id'] ); ?>" name="<?php echo esc_attr( $field['id'] ); ?>"><?php echo esc_textarea( $value ); ?></textarea>
		<?php
		echo $field['after_field'];
	}

	/**
	 * Render a text field
	 */
	public function render_text( $field, $value ) {

		echo $field['before_field']; 
		echo $field['before_title'] . esc_html( $field['title'] ) . $field['after_title'];

		if ( $field['description'] ) {
			echo '<p>' . nl2br( esc_html( $field['description'] ) ) . '</p>';
		}
		?>
		<input type="text" class="regular-text" id="<?php echo esc_attr( $field['id'] ); ?>" name="<?php echo esc_attr( $field['id'] ); ?>" value="<?php echo esc_attr( $value ); ?>">
		<?php
		echo $field['after_field'];
	}

	/**
	 * Render a repeater table
	 */
	public function render_repeater_table( $field, $value ) { 

    // Create a template
    $clone = '<tr class="repeater-item">';

    foreach ( array_keys( $field['header'] ) as $key ) {
      $clone .= '<td>';
      $clone .= '<input type="text" name=' . esc_attr( $field['id'] ) . '[' . esc_attr( $key ) . '][]" value="" class="widefat">';
      $clone .= '</td>';
    }
    $clone .= '<td class="col-delete">';
    $clone .= '<a href="#" class="button button-secondary button-delete-row">' . __( 'Delete', 'tcd-w' ) . '</a>';
    $clone .= '</td>';
    $clone .= '</tr>' . "\n";

		echo $field['before_field']; 
		echo $field['before_title'] . esc_html( $field['title'] ) . $field['after_title'];
		if ( $field['description'] ) {
			echo '<p>' . nl2br( esc_html( $field['description'] ) ) . '</p>';
		}

    echo '<div class="repeater-wrapper" data-delete-confirm="' . __( 'Delete?', 'tcd-w' ) . '">' . "\n";
    echo '<table class="a-table a-table--border repeater">' . "\n";

    echo '<thead>' . "\n";
    echo '<tr class="a-table__row repeater-item">';
    foreach ( $field['header'] as $label ) {
      echo '<th class="a-table__h a-table__col">' . esc_html( $label ) . '</th>';
      //echo '<th class="a-table__h a-table__col">' . __( 'Data', 'tcd-w' ) . '</th>';
    }
    echo '<th class="a-table__h a-table__col"></th>';    
    echo '</tr>' . "\n";
    echo '</thead>' . "\n";

    echo '<tbody>' . "\n";

    if ( isset( $value['header'][0] ) ) {
		  foreach( array_keys( $value['header'] ) as $repeater_index ) {
			  //$row_header = isset( $value['header'][$repeater_index] ) ? $value['header'][$repeater_index] : '';
			  //$row_data = isset( $value['data'][$repeater_index] ) ? $value['data'][$repeater_index] : '';

			  echo '<tr class="a-table__row repeater-item">';

			  //echo '<td><input type="text" name="' . esc_attr( $field['id'] ) . '[header][]" value="' . esc_attr( $row_header ) . '" class="widefat"></td>';
			  //echo '<td><input type="text" name="' . esc_attr( $field['id'] ).'[data][]" value="' . esc_attr( $row_data ) . '" class="widefat"></td>';

        foreach ( array_keys( $field['header'] ) as $key ) {
          $data = isset( $value[$key][$repeater_index] ) ? $value[$key][$repeater_index] : '';
          echo '<td>';
          echo '<input type="text" name="' . esc_attr( $field['id'] ) . '[' . esc_attr( $key ) . '][]" value="' . esc_attr( $data ) . '" class="widefat">';
          echo '</td>';
        }
			  echo '<td class="col-delete"><a href="#" class="button button-secondary button-delete-row">' . __( 'Delete', 'tcd-w' ) . '</a></td>';
			  echo '</tr>' . "\n";
		  }
	  } else {
		  echo $clone . "\n";
	  }

    echo '</tbody>'."\n";
    echo '</table>'."\n";
    echo '<a href="#" class="button button-secondary button-add-row" data-clone="' . esc_attr( $clone ) . '">' . __( 'Add row', 'tcd-w' ) . '</a>';
    echo '</div>' . "\n";
		echo $field['after_field'];
	}

	/**
	 * Render sub_box fields
	 */
	public function render_subbox( $field ) { 

		echo $field['before_field']; 
		echo $field['before_title'] . esc_html( $field['title'] ) . $field['after_title'];
		if ( $field['description'] ) {
			echo '<p>' . nl2br( esc_html( $field['description'] ) ) . '</p>';
		}

		for ( $i = 0; $i < count( $field['labels'] ); $i++ ) {
			echo '<div class="sub_box cf">' . "\n";
			echo '<h3 class="theme_option_subbox_headline">' . esc_html( $field['labels'][$i] ) . '</h3>' . "\n";
			echo '<div class="sub_box_content">' . "\n";

			// render fields in sub_box
			// You can access sub fields by $field['fields'][$i]
			foreach ( $field['fields'][$i] as $sub_field ) {

				// TODO whether we use $defaults as class property or not
				$defaults = array(
					'description' => '',
					'before_field' => '',
					'after_field' => '',
					'before_title' => '',
					'after_title' => ''
				);

				$sub_field = wp_parse_args( $sub_field, $defaults );
				$sub_value = get_post_meta( $this->post_id, $sub_field['id'], true );
				$sub_value = ( '' === $sub_value && isset( $sub_field['default'] ) ) ? $sub_field['default'] : $sub_value;
				switch ( $sub_field['type'] ) {
					case 'checkbox' :
						$this->render_checkbox( $sub_field, $sub_value );
						break;
					case 'color' :
						$this->render_color( $sub_field, $sub_value );
						break;
					case 'image' :
						$this->render_image( $sub_field, $sub_value );
						break;
					case 'number' :
						$this->render_number( $sub_field, $sub_value );
						break;
					case 'radio' :	
						$this->render_radio( $sub_field, $sub_value );
						break;
					case 'select' :
						$this->render_select( $sub_field, $sub_value );
						break;
					case 'textarea' :
						$this->render_textarea( $sub_field, $sub_value );
						break;
					case 'text' :
						$this->render_text( $sub_field, $sub_value );
						break;
				}
			}	
			echo '</div>'. "\n";
			echo '</div>'. "\n";
		}
		?>
		
		<?php
		echo $field['after_field'];
	}

} 
