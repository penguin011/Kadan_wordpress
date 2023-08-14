(function($) {

	// Initialize wordpress color picker API
	$('.c-color-picker').wpColorPicker();
  $('.cf-color-picker').wpColorPicker();

  // Sortable
	$('.sortable').sortable({
  	placeholder: 'sortable-placeholder',
  	helper: 'clone',
  	forceHelperSize: true,
  	forcePlaceholderSize: true
	});

  // Toggle .sub_box in custom fields
  $('.ml_custom_fields .theme_option_subbox_headline').click(function(){
    $(this).parent('.sub_box').toggleClass('active');
  });

	// Theme options
	if ($('#my_theme_option').length) {

		// CookieTab
  	$('#my_theme_option').cookieTab({
  		tabMenuElm: '#theme_tab',
   		tabPanelElm: '#tab-panel'
  	});

	  $('.theme_option_field')

      // Toggle .sub_box
      .on('click', '.theme_option_subbox_headline', function(e){
  	    $(this).parent('.sub_box').toggleClass('active');
      })

      // Change .sub_box headline
      .on('change keyup', '.change_subbox_headline', function(e){
    	  $(this).closest('.sub_box').find('.theme_option_subbox_headline').text($(this).val());
      })

      // Toggle HTML to display on clicking radio button
		  .on('click', 'input[type="radio"]', function() {

        // Get name attribute value inside bracket
        var name = $(this).attr('name').match(/dp_options\[(\w+)\]/)[1];

        // Get value of the radio button
        var value = $(this).val();

        if ('contents_builder' !== name && $('[id^="' + name + '"]').length) {

          // Hide all HTML related to the radio buttons
          $('[id^="' + name + '"]').hide();

          // Display HTML related to checked radio button
          $('[id="' + name + '_' + value + '"]').show();
        }
		  });

    // Footer bar
    $('.repeater-wrapper').on('change', '.footer-bar-type select', function() {
			var subBox = $(this).parents('.sub_box');
			var target = subBox.find('.footer-bar-target');
			var url = subBox.find('.footer-bar-url');
			var number = subBox.find('.footer-bar-number');
			switch ($(this).val()) {
			  case 'type1' :
				  target.show();
				  url.show();
				  number.hide();
				  break;
			  case 'type2' :
				  target.hide();
				  url.hide();
				  number.hide();
				  break;
			  case 'type3' :
				  target.hide();
				  url.hide();
				  number.show();
				  break;
			}
		});
    
	  // Submit by AJAX
    //$('#myOptionsForm').submit(function(event) {
    $('#tab-panel').on( 'click', '.ajax_button', function(event) {

	  	//event.preventDefault();

      var $button = $('.button-ml');
      $('#saving_data').show();
      tinyMCE.triggerSave(); // tinymceを利用しているフィールドのデータを保存
      $('#myOptionsForm').ajaxSubmit({
        beforeSend: function() {
          $button.attr('disabled', true); // ボタンを無効化し、二重送信を防止
        },
        complete: function() {
          $button.attr('disabled', false); // ボタンを有効化し、送信を許可
        },
        success: function(){
          $('#saving_data').hide();
          $('#saved_data').html('<div id="saveMessage" class="successModal"></div>');
          $('#saveMessage').append('<p>' + error_messages.success + '</p>').show();
        },
        error: function() {
          $('#saving_data').hide();
          alert(error_messages.error);
        },
        timeout: 10000
      }); 
      setTimeout(function() { 
	  		$('#saveMessage').hide();
	  	}, 3000);

      return false;
    });
	}

  // Add and delete repeater fields
  $('.repeater-wrapper')


    .each(function() {

      var nextIndex = $(this).find('.repeater-item').last().index();
      $(this).find('.button-add-row').click(function(e) {
        e.preventDefault();
        var clone = $(this).attr('data-clone');
        var $parent = $(this).closest('.repeater-wrapper');
        if (clone && $parent.length) {
          nextIndex++;
          $parent.find('.repeater').append(clone.replace(/addindex/g, nextIndex));
        }
        $('.cf-color-picker').wpColorPicker();
      });
    })
    
    .on('click', '.button-delete-row', function(e) {
      e.preventDefault();
      var del = true;
      var confirmMessage = $(this).closest('.repeater-wrapper').attr('data-delete-confirm');
      if (confirmMessage.length) {
        del = confirm(confirmMessage);
      }
      if (del) {
        $(this).closest('.repeater-item').remove();
      }
	  });

  // Page template
  $('[name="page_tcd_template_type"]').click(function() {

    var $label = $(this).parent();

    if (!$label.hasClass('active')) {
      $label.parents('ul').find('.active').removeClass('active');
      $label.addClass('active');
      $('[id^="page_tcd_template_type_type"]').hide();
      $('[id="page_tcd_template_type_' + $(this).val() + '"]').show();
      if ('type1' === $(this).val() || 'type2' === $(this).val()) {
        $('#page_tcd_template_type_common').hide();
      } else {
        $('#page_tcd_template_type_common').show();
      }
    }
  });

})(jQuery);

