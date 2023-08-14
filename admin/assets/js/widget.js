jQuery(document).ready(function($) {

  if ($('body').hasClass('widgets-php')) {

    var current_item, target_id;

    $(document).on('click', '.a-widget-box__headline', function(){
      $(this).toggleClass('is-active');
    });

    $(document).on('click', 'input.select-img', function(evt){
      window.ml_ad_original_send_to_editor = window.send_to_editor;
      window.send_to_editor = function(html) {
        if(current_item && target_id) {
          var imgurl = $(html).attr('src') || $('img',html).attr('src');
          current_item.siblings('.img').val(imgurl);
          $('#preview_'+target_id).html('<img src="'+imgurl+'" />');
          current_item = null;
          target_id = null;
        }
        window.send_to_editor = window.ml_ad_original_send_to_editor;
        tb_remove();
      }

      current_item = $(this);
      target_id = current_item.prev('input').attr('id');
      tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
      return false;
    });

    $(document).on('click', '.delete-img', function(e) {
      $(this).prev('input').val(0);
      $(this).prev().prev('.preview_field').hide();
      $(this).closest('form').find('[name=savewidget]').trigger('click');
    });

  	function initColorPicker(widget) {
    	widget.find('.w-color-picker').wpColorPicker( {
    		change: _.throttle(function() {
    	  	$(this).trigger('change');
    	 	}, 3000)
    	});
  	}
  
  	$(document).on( 'widget-added widget-updated', function(event, widget) {
  		initColorPicker(widget);
		});
  
  	$(document).ready(function() {
    	$('#widgets-right .widget:has(.w-color-picker)').each(function() {
      	initColorPicker($(this));
    	});
  	});
  }
});
