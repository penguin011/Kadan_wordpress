jQuery(document).ready(function($){

	if ($('.pb-modal-edit-widget.pb-widget-simple_table').size() == 0) return;

	// content_type変更
	$(document).on('change', '.pb-modal-edit-widget.pb-widget-simple_table .pb_repeater_content .content_type', function(){
		var $pb_repeater_content = $(this).closest('.pb_repeater_content');
		if ($(this).val() == 'type2') {
			$pb_repeater_content.find('.content_type-type1').hide();
			$pb_repeater_content.find('.content_type-type2').slideDown('fast');
		} else {
			$pb_repeater_content.find('.content_type-type2').hide();
			$pb_repeater_content.find('.content_type-type1').slideDown('fast');
		}
	});

});