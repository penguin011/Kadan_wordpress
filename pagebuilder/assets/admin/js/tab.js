jQuery(document).ready(function($){

	// tab type 選択
	$(document).on('change', '.pb-widget-tab .form-field-tab_type :radio', function(){
		if (this.checked) {
			var $cl = $(this).closest('.pb-content');
			$cl.find('[class*="form-field-tab_type-type"]').hide();
			$cl.find('.form-field-tab_type-' + this.value).show();
		}
	});
	$('.pb-widget-tab .form-field-tab_type :radio:checked').trigger('change');

	// content type 選択
	$(document).on('change', '.pb-widget-tab .form-field-content_type :radio', function(){
		if (this.checked) {
			var $cl = $(this).closest('.pb_repeater_content');
			$cl.find('[class*="form-field-content_type-type"]').hide();
			$cl.find('.form-field-content_type-' + this.value).show();
		}
	});
	$('.pb-widget-tab .form-field-content_type :radio:checked').trigger('change');

	// video type 選択
	$(document).on('change', '.pb-widget-tab .form-field-video_type :radio', function(){
		if (this.checked) {
			var $cl = $(this).closest('.pb_repeater_content');
			$cl.find('[class*="form-field-video_type-type"]').hide();
			$cl.find('.form-field-video_type-' + this.value).show();
		}
	});
	$('.pb-widget-tab .form-field-video_type :radio:checked').trigger('change');

});
