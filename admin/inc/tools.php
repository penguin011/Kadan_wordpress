<?php
/**
 * Manage tool tab
 */

// Add label of tool tab
add_action( 'tcd_tab_labels', 'add_tool_tab_label' );

// Add HTML of tool tab
add_action( 'tcd_tab_panel', 'add_tool_tab_panel' );

function add_tool_tab_label( $tab_labels ) {
	$tab_labels['tool'] = __( 'TCD theme options tools', 'tcd-w' );
	return $tab_labels;
}

function add_tool_tab_panel( $options ) {
?>
<div class="theme_option_field cf">
	<h3 class="theme_option_headline"><?php _e( 'TCD theme options tools', 'tcd-w' ); ?></h3>
	<h4 class="theme_option_headline2"><?php _e( 'Export', 'tcd-w' ); ?></h4>
	<div class="theme_option_message">
		<p><?php _e( 'Export TCD theme option.', 'tcd-w' ); ?></p>
	</div>
	<p><input class="button-ml" type="submit" name="tcd-tools-export" value="<?php _e( 'Export', 'tcd-w' ); ?>"></p>
	<h4 class="theme_option_headline2"><?php _e( 'Import', 'tcd-w' ); ?></h4>
	<div class="theme_option_message">
		<p><?php _e( 'Import the TCD theme option.Please specify JSON file (. Json) exported from TCD theme.', 'tcd-w' ); ?></p>
	</div>
	<p class="cf">
		<input type="file" name="tcd-tools-import-file" value="">
		<input class="button-ml" name="tcd-tools-import" type="submit" value="<?php _e( 'Import', 'tcd-w' ); ?>">
	</p>
	<h4 class="theme_option_headline2"><?php _e( 'Reset', 'tcd-w' ); ?></h4>
	<div class="theme_option_message">
		<p><?php _e( 'Initialize the TCD theme option.Please note that all current settings will be deleted.', 'tcd-w' ); ?></p>
	</div>
	<p><input class="button-ml" name="tcd-tools-reset" type="submit" value="<?php _e( 'Reset', 'tcd-w' ); ?>"></p>
	<ul>
		<li><label style="vertical-align: baseline;"><input name="tcd-tools-reset-sample-posts" type="checkbox" value="1"><?php _e( 'Add sample posts', 'tcd-w' ); ?></label> <small class="description"><?php _e( 'Note: sample posts will not be added if they already exist.', 'tcd-w' ); ?></small></li>
		<li><label style="vertical-align: baseline;"><input name="tcd-tools-reset-sample-menus" type="checkbox" value="1"><?php _e( 'Add an sample global menu', 'tcd-w' ); ?></label> <small class="description"><?php _e( 'Note: an sample global menu will not be added if the global menu or "Sample menu" already exist.', 'tcd-w' ); ?></small></li>
		<li><label style="vertical-align: baseline;"><input name="tcd-tools-reset-sample-widgets" type="checkbox" value="1"><?php _e( 'Initialize the common widget area', 'tcd-w' ); ?></label> <small class="description"><?php _e( 'Note: current widgets in the common widget area will be initialized.', 'tcd-w' ); ?></small></li>
	</ul>
</div>
<script>
(function($){
	// インポート
	$(':submit[name="tcd-tools-import"]').click(function(){
		if (!$(':file[name="tcd-tools-import-file"]').val()) return false;
	});
	// リセット
	$(':submit[name="tcd-tools-reset"]').click(function(){
		return confirm('<?php echo __( 'Are you sure you want to reset?', 'tcd-w' ); ?>');
	});
	// _wp_http_refererにインポートメッセージ用クエリー文字列が残る対策
	var $_wp_http_referer = $(':submit[name="tcd-tools-import"]').closest('form').find('input[name="_wp_http_referer"]');
	$_wp_http_referer.val($_wp_http_referer.val().replace(/&(amp;)?tcd-tools-result=.*&?/, ''));
})(jQuery);
</script>
<?php
}
