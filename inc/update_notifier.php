<?php
/**
 * Provides a notification everytime the theme is updated
 * Original code courtesy of Joao Araujo of Unisphere Design - http://themeforest.net/user/unisphere
 */
function update_notifier_menu() {  

	// This tells the function to cache the remote call for 21600 seconds (6 hours)
	$xml = get_latest_theme_version( 21600 );
	
	// Get theme data from style.css (current version is what we want)
	$theme_data = function_exists( 'wp_get_theme' ) ? wp_get_theme() : get_theme_data( TEMPLATEPATH . '/style.css' ); 
	
	if ( version_compare( $theme_data['Version'], $xml->latest ) == -1 ) {
		add_dashboard_page( $theme_data['Name'] . ' Theme Updates', __( 'Theme Update', 'tcd-w' ) . '<span class="update-plugins count-1"><span class="update-count">1</span></span>', 'administrator', 'design-plus-updates', 'update_notifier' );
	}
}  
add_action( 'admin_menu', 'update_notifier_menu' );

function update_notifier() { 

	// This tells the function to cache the remote call for 21600 seconds (6 hours)
	$xml = get_latest_theme_version( 21600 );

	// Get theme data from style.css (current version is what we want)
	$theme_data = function_exists( 'wp_get_theme' ) ? wp_get_theme() : get_theme_data( TEMPLATEPATH . '/style.css' ); 
?>
	<style>
	.update-nag { display: none; }
	#mono_info { float:left; width:400px; margin:0 20px 20px 0; border:1px solid #ccc; -moz-border-radius:5px; -khtml-border-radius:5px; -webkit-border-radius:5px; border-radius:5px; }
	#mono_theme_thumbnail { float:left; display:block; border:1px solid #ccc; }
    #mono_info h3 { margin:0 0 15px 0; font-size:14px; background:#f2f2f2; padding:15px 15px 12px; -moz-border-radius:5px 5px 0 0; -khtml-border-radius:5px 5px 0 0; -webkit-border-radius:5px 5px 0 0; border-radius:5px 5px 0 0; border-bottom:1px solid #ccc; background: -webkit-gradient(linear, left top, left bottom, from(#fff), to(#eee)); background: -moz-linear-gradient(top, #fff, #eee); }
    #mono_info dt { font-weight:bold; margin:0 0 2px 0; font-size:12px; }
    #mono_info dd { margin:0 0 15px 0; font-size:12px; }
    #mono_info dl { margin:0 15px 5px 15px; font-size:12px; }
	</style>
    <div class="wrap">
   		<div id="icon-tools" class="icon32"></div>
   		<h2><?php echo $theme_data['Name']; ?> <?php _e( 'Theme Update Information', 'tcd-w' ); ?></h2>
   		<div id="message" class="updated below-h2">
   			<p><strong><?php printf( __( 'The latest version of %s is released.', 'tcd-w' ), esc_html( $theme_data['Name'] ) ); ?></strong> <?php printf( __( 'Current version is %s. You can update to the latest version, %s.', 'tcd-w' ), esc_html( $theme_data['Version'] ), esc_html( $xml->latest ) ); ?></p>
     	</div>
     	<div id="mono_instructions">
				<h3><?php _e( 'Note: Please be sure to backup your theme before you update to the latest version.', 'tcd-w' ); ?></h3>
          <p style="font-size:16px;">【 <a href="http://tcdwp.info/dl056/kadan/kadan_tcd056.zip"><?php _e( 'Download the latest version', 'tcd-w' ); ?></a> 】</p>
     		<div id="mono_info">
   				<h3><?php _e( 'Changelog', 'tcd-w' ); ?></h3>
   				<?php echo $xml->changelog; ?>
      		</div>
          	<img id="mono_theme_thumbnail" src="<?php echo get_bloginfo( 'template_url' ) . '/screenshot.png'; ?>" alt="">
         </div>
	</div>
<?php 
} 

// This function retrieves a remote xml file on my server to see if there's a new update 
// For performance reasons this function caches the xml content in the database for XX seconds ($interval variable)
function get_latest_theme_version( $interval ) {

	// remote xml file location
    $notifier_file_url = 'http://design-plus1.com/notifier_kadan.xml';	
    $db_cache_field = 'kadan-contempo-notifier-cache';
    $db_cache_field_last_updated = 'kadan-contempo-notifier-last-updated';
	$last = get_option( $db_cache_field_last_updated );
	$now = time();
	
	// check the cache
	if ( ! $last || ( ( $now - $last ) > $interval ) ) {
		// cache doesn't exist, or is old, so refresh it
		if ( function_exists( 'curl_init' ) ) { // if cURL is available, use it...
			$ch = curl_init( $notifier_file_url );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch, CURLOPT_HEADER, 0 );
			curl_setopt( $ch, CURLOPT_TIMEOUT, 10 );
			$cache = curl_exec( $ch );
			curl_close( $ch );
		} else {
			$cache = file_get_contents( $notifier_file_url ); // ...if not, use the common file_get_contents()
		}
		
		if ( $cache ) {			
			// we got good results
			update_option( $db_cache_field, $cache );
			update_option( $db_cache_field_last_updated, time() );			
		}
		// read from the cache file
		$notifier_data = get_option( $db_cache_field );
	}
	else {
		// cache file is fresh enough, so read from it
		$notifier_data = get_option( $db_cache_field );
	}
	$xml = simplexml_load_string( $notifier_data ); 
	return $xml;
}
