jQuery(document).ready(function($){

	// Saturation
	$(document).on('change', '.pb-widget-googlemap .range', function() {
		$(this).prev('.range-output').find('span').text($(this).val());
	});

	// マーカータイプ
	$(document).on('change', '.pb-widget-googlemap .form-field-marker_type :radio', function(){
		if (this.checked) {
			var $cl = $(this).closest('.pb-content');
			if (this.value == 'type3') {
				$cl.find('.form-field-marker_type-type3').show();
			} else {
				$cl.find('.form-field-marker_type-type3').hide();
			}
		}
	});
	$('.pb-widget-googlemap .form-field-marker_type :radio:checked').trigger('change');

	// カスタムマーカータイプ
	$(document).on('change', '.pb-widget-googlemap .form-field-custom_marker_type :radio', function(){
		if (this.checked) {
			var $cl = $(this).closest('.pb-content');
			if (this.value == 'type1') {
				$cl.find('.form-field-marker_text').show();
				$cl.find('.form-field-marker_img').hide();
			} else {
				$cl.find('.form-field-marker_text').hide();
				$cl.find('.form-field-marker_img').show();
			}
		}
	});
	$('.pb-widget-googlemap .form-field-custom_marker_type :radio:checked').trigger('change');

	// Google Map オーバーレイ
	$(document).on('change', '.pb-widget-googlemap .form-field-show_overlay :checkbox', function(){
		if (this.checked) {
			$(this).closest('.pb-content').find('.form-field-overlay').show();
		} else {
			$(this).closest('.pb-content').find('.form-field-overlay').hide();
		}
	});
	$('.pb-widget-googlemap .form-field-show_overlay :checkbox').trigger('change');

});
