// YouTube IFrame Player API
var tag = document.createElement('script');
tag.src = 'https://www.youtube.com/iframe_api';
var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

var onReadyFlag = false;
var youtube = document.getElementsByClassName('js-index-slider__item-youtube');

// make a object to store YT.Player objects
var players = {};

function onYouTubeIframeAPIReady() {

  for (var i = 0, len = youtube.length; i < len; i++) {
    var playerId = youtube[i].id;
    players[playerId] = new YT.Player(playerId, {
      playerVars: { 'controls': 0 },
      events: {
        'onReady': onPlayerReady,
        'onStateChange': function(event) {
          if (1 === event.data) { // Start
            jQuery('#js-index-slider').slick('slickPause');
          }
          if (0 === event.data) { // End
            jQuery('#js-index-slider').slick('slickNext');
            jQuery('#js-index-slider').slick('slickPlay');
          }
        }
      }
    });
  }

}

function onPlayerReady(event) {
  onReadyFlag = true;
  event.target.mute();
  if ('js-index-slider__item-youtube1' === event.target.a.id) {
    event.target.playVideo();
  }
}

(function($, players) {

  // Slider
  var $indexSlider = $('#js-index-slider');
  var $indexSliderItem = $('#js-index-slider').find('.p-index-slider__item');
  var speed = $indexSlider.data('speed');

  // Initialize the first item
  $indexSliderItem.first().addClass('is-active');

  if ($indexSliderItem.length > 1) {

    $indexSlider.slick({
      arrows: false,
      autoplay: true,
      autoplaySpeed: $indexSlider.data('speed'),
      fade: true,
      speed: 1000,
      slide: '.p-index-slider__item'
    });

    if ($indexSliderItem.first().find('.p-index-slider__item-video').length) {

      var video = $indexSliderItem.first().find('.p-index-slider__item-video').get(0);
      $indexSlider.slick('slickPause');
      video.play();
      video.onended = function() {
        $indexSlider.slick('slickNext');
        $indexSlider.slick('slickPlay');
      };
    }

    $indexSlider.on('beforeChange', function(event, slick, currentSlide, nextSlide) {

      $indexSliderItem.eq(nextSlide).addClass('is-active');

      if ($indexSliderItem.eq(currentSlide).find('.p-index-slider__item-video').length) {
        $indexSliderItem.eq(currentSlide).find('.p-index-slider__item-video').get(0).pause();
      }

      if ($indexSliderItem.eq(nextSlide).find('.p-index-slider__item-video').length) {

        var video = $indexSliderItem.eq(nextSlide).find('.p-index-slider__item-video').get(0);
        video.currentTime = 0;
        video.play();

      } else if ($indexSliderItem.eq(nextSlide).find('.p-index-slider__item-youtube').length) {

        var playerId = $indexSliderItem.eq(nextSlide).find('.p-index-slider__item-youtube').attr('id');
        players[playerId].playVideo();

      }
    });

    $indexSlider.on('afterChange', function(event, slick, currentSlide, nextSlide) {

      // Remove class .is-active from the first element
      $indexSliderItem.not(':eq(' + currentSlide + ')').removeClass('is-active');

      if ($indexSliderItem.eq(currentSlide).find('.p-index-slider__item-video').length) {

        var video = $indexSliderItem.eq(currentSlide).find('.p-index-slider__item-video').get(0);

        // Pause slick
        $indexSlider.slick('slickPause');

        // Restart slick
        video.onended = function() {
          $indexSlider.slick('slickNext');
          $indexSlider.slick('slickPlay');
        };

      } else if ($indexSliderItem.eq(currentSlide).find('.p-index-slider__item-img').length) {

        $indexSlider.slick('slickPause');
        window.setTimeout(function() {
          $indexSlider.slick('slickNext');
          $indexSlider.slick('slickPlay');
        }, speed);
      }
    });
  } else if (1 === $indexSliderItem.length) {

    $indexSliderItem.first().addClass('slick-active');

    if ($indexSliderItem.first().find('.p-index-slider__item-video').length) {

      var video = $indexSliderItem.first().find('.p-index-slider__item-video').get(0);
      video.autoplay = true;
      video.loop = true;
    }

  }

  $('#js-index-slider__arrow, #js-index-slider__nav a').click(function(e) {
    if ($($(this).attr('href')).length) {
      $('body, html').animate({
        scrollTop: $($(this).attr('href')).offset().top
      }, 1000);
      e.preventDefault();
    }
  });

  // News
  function switchTopic($topic, $topicInner, index) {
    $topic.find('.is-active').removeClass('is-active');
    $topic.find('.p-index-news__topic-date-inner').eq(index).addClass('is-active');
    $topic.find('.p-index-news__topic-pager-item').eq(index).addClass('is-active');
    $topic.find('.p-index-news__topic-inner').slick('slickGoTo', index);
  }

  $('.js-index-news__topic').each(function() {

    var $topic = $(this);
    var $topicInner = $topic.find('.p-index-news__topic-inner');
    var len = $topicInner.children().length;
    var index = 0;
    var speed = $topicInner.data('speed');

    if (len <= 1) return;

    $topicInner.slick({
      arrows: false,
      draggable: false,
      speed: 500,
      swipe: false
    });

    var intervalID = window.setInterval(function() {
      index = (index + 1) % len;
      switchTopic($topic, $topicInner, index);
    }, parseInt(speed));

    $topic.find('.p-index-news__topic-pager a').click(function(e) {

      e.preventDefault();

      index = $(this).parent().index();
      switchTopic($topic, $topicInner, index);

      clearInterval(intervalID);
      intervalID = window.setInterval(function() {
        index = (index + 1) % len;
        switchTopic($topic, $topicInner, index);
      }, parseInt(speed));

    });
  });

  // Blog
  $('.js-index-blog__slider').each(function() {
    if ($(this).children('.p-index-blog__slider-item').length > 4) {
      $(this).slick({
        autoplay: true,
        slidesToShow: 4,
        responsive: [
          {
            breakpoint: 1200,
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
  });

  // Fix bugs in Edge and IE11
  var $sliderNavItemLink = $('.p-index-slider__nav-item a');
  var sliderNavItemLinkFontSize = parseInt($sliderNavItemLink.css('font-size'));
  $sliderNavItemLink.each(function() {
    $(this).height(sliderNavItemLinkFontSize * $(this).text().length);
  });

})(jQuery, players);
