jQuery(document).ready(function($){

	/**
	 * スマホ用固定フッターバー
	 */
	var footerBar = $('#js-footer-bar');
	var pageTop = $('#js-pagetop');
	var activeClass = 'is-active';

	// モーダルの処理
	if ($('.c-footer-bar__share').length) {
		$('.c-footer-bar__item--share, #js-modal-overlay').on('click', function() {
			$('#js-modal-overlay, #js-modal-content').toggleClass('u-hidden');
			return false;
		});	
		$('#js-modal-overlay, #js-modal-content').on('touchmove', function(e) {
			e.preventDefault();
		});
	}

	// フッターバーの表示、非表示
	if ( footerBar.length ) {

		$(window).scroll(function () {
			if ($(this).scrollTop() > 100) {
				footerBar.addClass(activeClass);
        var footerBarHeight = footerBar.height();
				$('body').css('paddingBottom', footerBarHeight);
				pageTop.css('bottom', footerBarHeight);
			} else {
				footerBar.removeClass(activeClass);
			}
		});

    $(window).bind('resize orientationchange', function() {
      if (footerBar.hasClass(activeClass)) {
        var footerBarHeight = footerBar.height();
				$('body').css('paddingBottom', footerBarHeight);
				pageTop.css('bottom', footerBarHeight);
      };
    });

	}

});
