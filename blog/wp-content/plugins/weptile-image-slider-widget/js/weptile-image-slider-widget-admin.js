var timeout;
jQuery(function () {
	jQuery(document).on('keydown', '.weptile-image-slider-widget-number-only-input', function (event) {
		// Allow: backspace, delete, tab, escape, and enter
		if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 ||
			// Allow: Ctrl+A
			(event.keyCode == 65 && event.ctrlKey === true) ||
			// Allow: home, end, left, right
			(event.keyCode >= 35 && event.keyCode <= 39)) {
			// let it happen, don't do anything
			return true;
		}
		else {
			// Ensure that it is a number and stop the keypress
			if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
				event.preventDefault();
			}
		}
	});
	var img_url_text_container_id = '';
	var submit_button_id = '';
	jQuery(document).on('click', '[id$="slider-images-upload-button"]', function () {
		window.weptile_backup_send_to_editor = window.send_to_editor;
		window.send_to_editor = window.weptile_send_to_editor;
		submit_button_id = jQuery(this).attr('id');
		img_url_text_container_id = jQuery(this).prev().attr('id');
		tb_show('', 'media-upload.php?TB_iframe=true&');

		var iframe = jQuery('#TB_iframeContent');
		iframe.css('display', 'none');
		iframe.load(function () {
			var iframeDoc = iframe[0].contentWindow.document;
			var iframeJQuery = iframe[0].contentWindow.jQuery;
			if (iframeJQuery != undefined)
				iframeJQuery('#tab-type_url').remove();

			iframe.css('display', '');
			apply_insert_button_filter(iframeJQuery);
		});

		return false;
	});


	window.weptile_send_to_editor = function (html) {
		var imgurl = jQuery('img', html).attr('src');
		if (imgurl == undefined)
			imgurl = jQuery(html).attr('src');

		jQuery('#' + img_url_text_container_id).val(imgurl).prop('disabled', false);
		clearTimeout(timeout);
		tb_remove();
		jQuery('#' + submit_button_id).parent().parent().find('input[name=savewidget]').click();
		window.send_to_editor = window.weptile_backup_send_to_editor;
	};

	jQuery(document).on('click', '.weptile-image-slider-images-delete-button', function () {
		var parent_li = jQuery(this).parent();

		parent_li.toggleClass('weptile-image-slider-images-will-be-deleted');
		if (parent_li.hasClass('weptile-image-slider-images-will-be-deleted')) {
			parent_li.find('input').prop('disabled', true);
		} else {
			parent_li.find('input').prop('disabled', false);
		}

        jQuery(this).parent().parent().parent().parent().find('input[name=savewidget]').click();
	});

	jQuery(document).on('click', '.weptile-image-slider-images-details-button', function () {
		var that = jQuery(this);

		if (that.parent().find('.weptile-image-slider-images-details-table').is(':visible')) {
			that.parent().find('.weptile-image-slider-images-details-table').fadeOut(300);
			that.parent().animate({height: 30, maxHeight: 30  }, 360);
		} else {
			that.parent().animate({height: 210, maxHeight: 210}, 360, function () {
				that.parent().find('.weptile-image-slider-images-details-table').fadeIn(300);
			});
		}
	});

	jQuery(document).on('change', '.weptile-image-slider-image-link-target-select, .weptile-image-slider-image-link-rel-select, .weptile-image-slider-theme-select', function () {
		var that = jQuery(this), text_input, button;

		if (that.val() === 'weptile_custom_frame_name' || that.val() === 'weptile_custom_rel' || that.val() === 'weptile_custom_theme') {
			if (that.hasClass('weptile-image-slider-image-link-target-select')) {
				text_input = jQuery('<input type="text" class="weptile-image-slider-image-link-target-input widefat" id="' + that.attr('id') + '" name="' + that.attr('name') + '" />');
				button = jQuery(' <button type="button" title="' + window.weptile_link_target_input_title + '" class="button weptile-image-slider-image-link-target-cancel-button" >X</button>');
			}

			if(that.hasClass('weptile-image-slider-image-link-rel-select')){
				text_input = jQuery('<input type="text" class="weptile-image-slider-image-link-rel-input widefat" id="' + that.attr('id') + '" name="' + that.attr('name') + '" />');
				button = jQuery(' <button type="button" title="' + window.weptile_link_rel_input_title + '" class="button weptile-image-slider-image-link-rel-cancel-button" >X</button>');
			}

			if(that.hasClass('weptile-image-slider-theme-select')){
				text_input = jQuery('<input type="text" class="weptile-image-slider-custom-theme-input widefat" id="' + that.attr('id') + '" name="' + that.attr('name') + '" />');
				button = jQuery(' <button type="button" title="' + window.weptile_custom_theme_button_title + '" class="button weptile-image-slider-custom-theme-cancel-button" >X</button>');
			}

			if (button != undefined)
				button.insertAfter(that);

			that.replaceWith(text_input);
			text_input.focus();
		}
	});

	jQuery(document).on('click', '.weptile-image-slider-image-link-target-cancel-button, .weptile-image-slider-image-link-rel-cancel-button, .weptile-image-slider-custom-theme-cancel-button', function () {
		var that = jQuery(this), text_input = that.prev(), select_input;

		if (that.hasClass('weptile-image-slider-image-link-target-cancel-button')){
			select_input = jQuery('<select class="weptile-image-slider-image-link-target-select widefat" id="' + text_input.attr('id') + '" name="' + text_input.attr('name') + '">' + window.weptile_link_target_select_options + '</select>');
		}

		if(that.hasClass('weptile-image-slider-image-link-rel-cancel-button')){
			select_input = jQuery('<select class="weptile-image-slider-image-link-rel-select widefat" id="' + text_input.attr('id') + '" name="' + text_input.attr('name') + '">' + window.weptile_link_rel_select_options + '</select>');
		}

		if(that.hasClass('weptile-image-slider-custom-theme-cancel-button')){
			select_input = jQuery('<select class="weptile-image-slider-theme-select widefat" id="' + text_input.attr('id') + '" name="' + text_input.attr('name') + '">' + window.weptile_slider_theme_select_options + '</select>');
		}

		text_input.replaceWith(select_input);
		that.remove();
	});

});

