<?php

/**
 * テーマオプション取得関数のスペルミス・揺らぎ対策
 */
if ( ! function_exists( 'get_design_plus_option' ) ) {
	function get_design_plus_option() {
		if ( function_exists( 'get_design_plus_options' ) ) {
			return get_design_plus_options();
		}

		if ( function_exists( 'get_desing_plus_options' ) ) {
			return get_desing_plus_options();
		}

		if ( function_exists( 'get_desing_plus_option' ) ) {
			return get_desing_plus_option();
		}

		global $dp_default_options;

		$dp_options = get_option( 'dp_options', array() );

		if ( $dp_options && is_array( $dp_options ) ) {
			if ( $dp_default_options && is_array( $dp_default_options ) ) {
				return array_merge( $dp_default_options, $dp_options );
			} else {
				return $dp_options;
			}
		}

		return false;
	}
}

/**
 * テーマオプション メインカラー取得
 */
function page_builder_get_primary_color($default = null) {
	if ($default) {
		$color = $default;
	} else {
		$color = '#000000';
	}

	//テーマオプション カラー反映
	if (function_exists('get_design_plus_option')) {
		$dp_options = get_design_plus_option();

		$primary_color_theme_option_keys = apply_filters('page_builder_primary_color_theme_option_keys', array('primary_color', 'main_color', 'pickedcolor1'));
		if ($primary_color_theme_option_keys) {
			foreach((array) $primary_color_theme_option_keys as $theme_option_key) {
				if (is_string($theme_option_key) && !empty($dp_options[$theme_option_key])) {
					$color = $dp_options[$theme_option_key];
					// 旧カラーピッカー対策
					if (preg_match('/^[0-9a-f]{6}$/i', $color)) {
						$color = '#'.strtolower($color);
					}
					break;
				}
			}
		}
	}

	return apply_filters('page_builder_get_primary_color', $color);
}

/**
 * テーマオプション サブカラー取得
 */
function page_builder_get_secondary_color($default = null) {
	if ($default) {
		$color = $default;
	} else {
		$color = '#000000';
	}

	//テーマオプション カラー反映
	if (function_exists('get_design_plus_option')) {
		$dp_options = get_design_plus_option();

		$primary_color_theme_option_keys = apply_filters('page_builder_secondary_color_theme_option_keys', array('secondary_color', 'pickedcolor2'));
		if ($primary_color_theme_option_keys) {
			foreach((array) $primary_color_theme_option_keys as $theme_option_key) {
				if (is_string($theme_option_key) && !empty($dp_options[$theme_option_key])) {
					$color = $dp_options[$theme_option_key];
					// 旧カラーピッカー対策
					if (preg_match('/^[0-9a-f]{6}$/i', $color)) {
						$color = '#'.strtolower($color);
					}
					break;
				}
			}
		}
	}

	return apply_filters('page_builder_get_secondary_color', $color);
}

