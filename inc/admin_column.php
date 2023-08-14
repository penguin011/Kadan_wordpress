<?php
/**
 * Add custom columns (ID, thumbnails)
 */
function manage_columns( $columns ) {

	// Make new column array with ID column and featured image column
	$new_columns = array();

	foreach ( $columns as $column_name => $column_display_name ) {

		// Add post_id column before title column
		if ( isset( $columns['title'] ) && $column_name == 'title' ) {
			$new_columns['post_id'] = 'ID';
		}
		$new_columns[$column_name] = $column_display_name;
	}

	// Add featured image column
	$new_columns['new_post_thumb'] = __( 'Featured Image', 'tcd-w' );

	return $new_columns;
}
add_filter( 'manage_posts_columns', 'manage_columns', 5 ); // For post, news, plan and page

/**
 * Add a custom column (recommend post)
 */
function manage_post_posts_columns( $columns ) {
	$columns['recommend_post'] = __( 'Recommend post', 'tcd-w' );
	return $columns;
}
add_filter( 'manage_post_posts_columns', 'manage_post_posts_columns' ); // Only for post

/**
 * Add a custom column (recommended plan)
 */
function manage_plan_posts_columns( $columns ) {
	$columns['recommended_plan'] = __( 'Recommended plan', 'tcd-w' );
	return $columns;
}
add_filter( 'manage_plan_posts_columns', 'manage_plan_posts_columns' ); // Only for plan

/**
 * Output the content of custom columns (ID, featured image, recommend post and recommended plan)
 */
function add_column( $column_name, $post_id ) {

	switch ( $column_name ) {
		
		case 'post_id' : // ID
			echo $post_id;
			break;

		case 'new_post_thumb' : // Featured image
    	$post_thumbnail_id = get_post_thumbnail_id( $post_id );
      if ( $post_thumbnail_id ) {
        $post_thumbnail_img = wp_get_attachment_image_src( $post_thumbnail_id, 'thumbnail' );
        echo '<img width="70" src="' . esc_attr( $post_thumbnail_img[0] ) . '">';
      }
			break;

	 	case 'recommend_post' : // Recommend post
			if ( get_post_meta( $post_id, 'recommend_post1', true ) ) { _e( 'Recommend post1<br>', 'tcd-w' ); }
		  if ( get_post_meta( $post_id, 'recommend_post2', true ) ) { _e( 'Recommend post2<br>', 'tcd-w' ); }
		  if ( get_post_meta( $post_id, 'recommend_post3', true ) ) { _e( 'Recommend post3', 'tcd-w' ); }
      break;

	 	case 'recommended_plan' : // Recommended plan
			if ( get_post_meta( $post_id, 'recommended_plan', true ) ) { _e( 'Recommended plan', 'tcd-w' ); }
      break;
	}
}
add_action( 'manage_posts_custom_column', 'add_column', 10, 2 ); // For post, news and plan
add_action( 'manage_pages_custom_column', 'add_column', 10, 2 ); // For page

/**
 * Enable sorting of the ID column
 */
function custom_quick_edit_sortable_columns( $sortable_columns ) {
	$sortable_columns['post_id'] = 'ID';
	return $sortable_columns;
}
add_filter( 'manage_edit-post_sortable_columns', 'custom_quick_edit_sortable_columns' ); // For post
add_filter( 'manage_edit-news_sortable_columns', 'custom_quick_edit_sortable_columns' ); // For news
add_filter( 'manage_edit-plan_sortable_columns', 'custom_quick_edit_sortable_columns' ); // For plan
add_filter( 'manage_edit-page_sortable_columns', 'custom_quick_edit_sortable_columns' ); // For page
