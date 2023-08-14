jQuery(document).ready(function($) {

	var pbmf_image_frame;
	var pbmf_image_current;

	if (typeof pbmf_text != 'object') {
		pbmf_text = {};
	}
	if (typeof pbmf_text.image_title != 'string') {
		pbmf_text.image_title = 'Select an Image';
	}
	if (typeof pbmf_text.image_button != 'string') {
		pbmf_text.image_button = 'Use Image';
	}

	// select image click event
	$(document).on('click', '.pb_media_field-image .pbmf-select-img', function(e){
		e.preventDefault();
		if (typeof pbmf_image_frame != 'undefined') {
			pbmf_image_frame.close();
		}

		// create and open new file frame
		pbmf_image_frame = wp.media({
			title: pbmf_text.image_title,
			library: {
				type: 'image'
			},
			button: {
				text: pbmf_text.image_button
			},
			multiple: false,
		});

		pbmf_image_frame.on('open',function(){
			var selection = pbmf_image_frame.state().get('selection');
			var selected_media_id = pbmf_image_current.find('.pb_media_id').val();
			if (selected_media_id) {
				selection.add(wp.media.attachment(selected_media_id));
			}
		});

		// callback for selected image
		pbmf_image_frame.on('select', function(){
			var selection = pbmf_image_frame.state().get('selection').first();
			pbmf_image_current.find('.pb_media_id').val(selection.attributes.id);
			if (selection.attributes.url) {
				pbmf_image_current.find('.preview_field').html('<img src="'+selection.attributes.url+'" />');
			} else {
				pbmf_image_current.find('.preview_field').html('');
			}
			pbmf_image_current.find('.pbmf-delete-img').show();
			pbmf_image_current = null;
		});

		// form element
		pbmf_image_current = $(this).closest('.pb_media_field-image');

		// open
		pbmf_image_frame.open();
	});

	// delete image
	$(document).on('click', '.pb_media_field-image .pbmf-delete-img', function(e) {
		var c = $(this).closest('.pb_media_field-image');
		c.find('.pb_media_id').val('');
		c.find('.preview_field').html('');
		$(this).hide();
	});

	// video
	var pbmf_video_frame;
	var pbmf_video_current;
	var pbmf_video_target_id;

	if (typeof pbmf_text.video_title != 'string') {
		pbmf_text.video_title = 'Select a video';
	}
	if (typeof pbmf_text.video_button != 'string') {
		pbmf_text.video_button = 'Use Video';
	}

	// select video click event
	$(document).on('click', '.pb_media_field-video .pbmf-select-video', function(e){
		e.preventDefault();
		if (typeof pbmf_video_frame != 'undefined') {
			pbmf_video_frame.close();
		}

		// create and open new file frame
		pbmf_video_frame = wp.media({
			title: pbmf_text.video_title,
			library: {
				type: 'video'
			},
			button: {
				text: pbmf_text.video_button
			},
			multiple: false,
		});

		pbmf_video_frame.on('open',function(){
			var selection = pbmf_video_frame.state().get('selection');
			var selected_media_id = pbmf_video_current.find('.pb_media_id').val();
			if (selected_media_id) {
				selection.add(wp.media.attachment(selected_media_id));
			}
		});

		// callback for selected video
		pbmf_video_frame.on('select', function(){
			var selection = pbmf_video_frame.state().get('selection').first();
			pbmf_video_current.find('.pb_media_id').val(selection.attributes.id);
			if (selection.attributes.url) {
				pbmf_video_current.find('.preview_field').html('<p class="media_url">'+selection.attributes.url+'</p>');
			} else {
				pbmf_video_current.find('.preview_field').html('');
			}
			pbmf_video_current.find('.pbmf-delete-video').show();
			pbmf_video_current = null;
		});

		// form element
		pbmf_video_current = $(this).closest('.pb_media_field-video');

		// open
		pbmf_video_frame.open();
	});

	// delete video
	$(document).on('click', '.pb_media_field-video .pbmf-delete-video', function(e) {
		var c = $(this).closest('.pb_media_field-video');
		c.find('.pb_media_id').val('');
		c.find('.preview_field').html('');
		$(this).hide();
	});

});
