<div class="l-secondary">
<?php
$sidebar = '';

if ( is_singular( 'post' ) ) {
	$sidebar = 'blog_widget';
} elseif ( is_singular( 'news' ) ) {
	$sidebar = 'news_widget';
} elseif ( is_page() ) {
  $sidebar = 'page_widget';
}

if ( is_mobile() ) {
  $sidebar .= '_sp';
}

if ( is_active_sidebar( $sidebar ) ) {
  dynamic_sidebar( $sidebar );
} elseif ( is_active_sidebar( 'common_widget' ) ) {
  dynamic_sidebar( 'common_widget' );
}
?>
</div><!-- /.l-secondary -->
