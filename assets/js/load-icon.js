(function($){
  
  if ($('#site_loader_overlay').length) {

		var loadTime = 3000;
		var bodyHeight = $('body').height();
		$('#site_wrap').css('display', 'none');
		$('body').height(bodyHeight);

		// After the document loading process
		$(window).load(function() {
			$('#site_wrap').css('display', 'block');
		  if ($('.slick-slider').length) { $('.slick-slider').slick('setPosition'); }
			$('body').height('');
			$('#site_loader_animation').delay(600).fadeOut(400);
		  $('#site_loader_overlay').delay(900).fadeOut(800);
		});

		// Display #site_wrap even if the document loading process is not over
		$(function() {
			setTimeout(function(){
				$('#site_loader_animation').delay(600).fadeOut(400);
				$('#site_loader_overlay').delay(900).fadeOut(800);
				$('#site_wrap').css('display', 'block');
		  }, loadTime);
		});
	}

})(jQuery);	
