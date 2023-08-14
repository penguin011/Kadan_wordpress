jQuery(document).ready(function($){

	// footer type 選択
	$(document).on('change', '.pb-widget-slider .form-field-footer_type :radio', function(){
		if (this.checked) {
			var $cl = $(this).closest('.pb-content');
			$cl.find('[class*="form-field-footer_type-type"]').hide();
			$cl.find('.form-field-footer_type-' + this.value).show();
		}
	});
	$('.pb-widget-slider .form-field-footer_type :radio:checked').trigger('change');

});
