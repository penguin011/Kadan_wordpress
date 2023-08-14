(function($) {

  // Header
  var header = $('#js-header');
  if (header.hasClass('l-header--fixed'))  {
    $(window).scroll(function() {
      if ($(window).scrollTop() > 100) {
        header.addClass('is-active');
      } else {
        header.removeClass('is-active');
      }
    });
  }

  // Global navigation
  $('#js-menu-btn').click(function(e) {
    e.preventDefault();
    $(this).toggleClass('is-active');
    $('#js-global-nav').slideToggle();
  });
  $('.sub-menu-toggle').click(function() {
    $(this).toggleClass('is-active').parent('a').next('.sub-menu').slideToggle();
    return false;
  });


	function mediaQueryClass(width) {
		if (width > 991) { //PC
			$(".p-global-nav").css("display","block");
			$(".sub-menu").css("display","block");
		} else { //smart phone
			$(".p-global-nav").css("display","none");
			$(".sub-menu-toggle").removeClass("is-active");
			$(".sub-menu").css("display","none");
		};
	};
	function viewport() {
		var e = window, a = 'inner';
		if (!('innerWidth' in window )) {
		    a = 'client';
		    e = document.documentElement || document.body;
		}
		return { width : e[ a+'Width' ] , height : e[ a+'Height' ] };
	}
	var ww = viewport().width;
	mediaQueryClass(ww);
	$(window).bind("resize orientationchange", function() {
		var ww = viewport().width;
		mediaQueryClass(ww);
	})


  // Blog slider
  if ($('#js-footer-slider__inner').children('.p-footer-slider__item').length > 4) {
    $('#js-footer-slider__inner').slick({
      autoplay: true,
      infinite: true,
      slidesToShow: 4,
      responsive: [
        {
          breakpoint: 1280,
          settings: {
            arrows: false
          }
        },
        {
          breakpoint: 900,
          settings: {
            arrows: false,
            slidesToShow: 3
          }
        },
        {
          breakpoint: 768,
          settings: {
            arrows: false,
            slidesToShow: 2
          }
        }
      ]
    });
  }

  // Pagetop
  var pagetop = $('#js-pagetop');
  $(window).scroll(function() {
    if ($(window).scrollTop() > 100) {
      pagetop.addClass('is-active');
    } else {
      pagetop.removeClass('is-active');
    }
  });
  pagetop.click(function(e) {
    $('body, html').animate({
      scrollTop: 0
    }, 1000);
    e.preventDefault();
  });

  // Inview
  //$('.p-page-header__title, .p-section-header__title, .p-visual, .p-vertical--lg').one('inview', function() {
  $('.p-page-header__title, .p-section-header__title, .p-visual, .p-vertical--lg').one('inview', function() {
    $(this).addClass('is-inview');
  });

  // Widget: Archive list
  if ($('.p-dropdown').length) {
    $('.p-dropdown__title').click(function() {
      $(this).toggleClass('is-active');
      $('+ .p-dropdown__list:not(:animated)', this).slideToggle();
    });
  }

  // Widget: Recommended plan
  $('.js-post-list03').each(function() {
    $(this).slick({
      autoplay: true,
      autoplaySpeed: $(this).data('speed'),
      fade: true
    });
  });

  // Comment
  if ($('#js-comment__tab').length) {
    var commentTab = $('#js-comment__tab');
    commentTab.find('a').click(function(e) {
      e.preventDefault();
      if (!$(this).parent().hasClass('is-active')) {
        $($('.is-active a', commentTab).attr('href')).animate({opacity: 'hide'}, 0);
        $('.is-active', commentTab).removeClass('is-active');
        $(this).parent().addClass('is-active');
        $($(this).attr('href')).animate({opacity: 'show'}, 1000);
      }
    });
  }

  // Slider
  $('.js-block02__item--slider, .js-block03__slider-inner').slick({
    autoplay: true,
    infinite: true,
    slidesToShow: 1,
    prevArrow: '<button type="button" class="slick-arrow--square slick-prev"></button>',
    nextArrow: '<button type="button" class="slick-arrow--square slick-next"></button>'
  });

  // Smooth scrool
  $('.p-section-nav a').click(function(e) {
    $('body, html').animate({
      scrollTop: $($(this).attr('href')).offset().top
    }, 1000);
    e.preventDefault();
  });

  // Fix bugs in Edge and IE11
  var $verticalBlockInner = $('.p-vertical-block__inner');
  var verticalBlockFontSize = parseInt($verticalBlockInner.css('font-size'));
  $verticalBlockInner.each(function() {
    $(this).height(verticalBlockFontSize * $(this).text().length);
  });

})(jQuery);
