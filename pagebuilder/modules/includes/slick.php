<?php

/**
 * slick用js
 */
function page_builder_slick_enqueue_script() {
	if (!wp_script_is('slick-script', 'registered') && !wp_script_is('slick-script', 'enqueued')) {
		if (apply_filters('page_builder_slick_enqueue_script', true)) {
			$pb_slick_script_paths = array(
				'assets/js/slick.min.js',
				'assets/js/slick.js',
				'js/slick.min.js',
				'js/slick.js'
			);
			$pb_slick_script_paths = apply_filters('page_builder_slick_script_locate_names', $pb_slick_script_paths);
			$pb_slick_script_path = locate_template($pb_slick_script_paths, false, false);
			$pb_slick_script_path = apply_filters('page_builder_slick_script_located', $pb_slick_script_path);
			if ($pb_slick_script_path) {
				$pb_slick_script_url = str_replace(ABSPATH, site_url('/'), $pb_slick_script_path);
				$pb_slick_script_url = apply_filters('page_builder_slick_script_url', $pb_slick_script_url);
				if ($pb_slick_script_url) {
					wp_enqueue_script('slick-script', $pb_slick_script_url, array('jquery'), PAGE_BUILDER_VERSION, true);
				}
			}
		}
	}
}

/**
 * slick用css
 */
function page_builder_slick_enqueue_style() {
	if (!wp_style_is('slick-style', 'registered') && !wp_style_is('slick-style', 'enqueued')) {
		if (apply_filters('page_builder_slick_enqueue_style', true)) {
			$pb_slick_style_paths = array(
				'assets/css/slick.min.css',
				'assets/css/slick.css',
				'js/slick.min.css',
				'js/slick.css',
				'css/slick.min.css',
				'css/slick.css'
			);
			$pb_slick_style_paths = apply_filters('page_builder_slick_style_locate_names', $pb_slick_style_paths);
			$pb_slick_style_path = locate_template($pb_slick_style_paths, false, false);
			$pb_slick_style_path = apply_filters('page_builder_slick_style_located', $pb_slick_style_path);
			if ($pb_slick_style_path) {
				$pb_slick_style_url = str_replace(ABSPATH, site_url('/'), $pb_slick_style_path);
				$pb_slick_style_url = apply_filters('page_builder_slick_style_url', $pb_slick_style_url);
				if ($pb_slick_style_url) {
					wp_enqueue_style('slick-style', $pb_slick_style_url, false, PAGE_BUILDER_VERSION);
				}
			}
		}
	}
}