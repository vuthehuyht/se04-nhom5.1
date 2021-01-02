// the semi-colon before the function invocation is a safety
// net against concatenated scripts and/or other plugins
// that are not closed properly.
;(function ($, window, document, undefined) {
	'use strict';

	//
	// ATTS
	// -------------------------------------------------------------------------
	_.extend(vc.atts, {
		vc_cenote_exploded_textarea: {
			parse: function (param) {
				var $field = this.content().find('.wpb_vc_param_value[name=' + param.param_name + ']');

				return $field.val().replace(/\n/g, '~');
			}
		},
		vc_cenote_style_textarea: {
			parse: function (param) {
				var $field = this.content().find('.wpb_vc_param_value[name=' + param.param_name + ']');

				return $field.val().replace(/\n/g, '');
			}
		},
		vc_cenote_chosen: {
			parse: function (param) {
				var value = this.content().find('.wpb_vc_param_value[name=' + param.param_name + ']').val();

				return (value) ? value.join(',') : '';
			}
		},
	});

	// ======================================================
	// VISUAL COMPOSER IMAGE SELECT
	// ------------------------------------------------------
	$.fn.JSCOMPOSER_IMAGE_SELECT = function () {
		return this.each(function () {

			var _el = $(this),
				_elems = _el.find('li');

			_elems.each(function () {
				var _this = $(this),
					_data = _this.data('value');

				_this.click(function () {
					if (_this.is('.selected')) {
						_this.removeClass('selected');
						_el.next().val('').trigger('keyup');
					} else {
						_this.addClass('selected').siblings().removeClass('selected');
						_el.next().val(_data).trigger('keyup');
					}
				});
			});
		});
	};
	// ======================================================

	// ======================================================
	// VISUAL COMPOSER SWITCH
	// ------------------------------------------------------
	$.fn.JSCOMPOSER_SWITCH = function () {
		return this.each(function () {

			var _this = $(this),
				_input = _this.find('input');

			_this.click(function () {
				_this.toggleClass('switch-active');
				_input.val((_input.val() == 1) ? '' : 1).trigger('keyup');
			});
		});
	};

	// ======================================================
	// RELOAD FRAMEWORK PLUGINS
	// ------------------------------------------------------
	$.CENOTE_VC_RELOAD_PLUGINS = function () {
		$('.chosen').CENOTEFRAMEWORK_CHOSEN();
		$('.cenote-field-image-select').CENOTEFRAMEWORK_IMAGE_SELECTOR();
		$('.vc_image_select').JSCOMPOSER_IMAGE_SELECT();
		$('.vc_switch').JSCOMPOSER_SWITCH();
		$('.cenote-field-image').CENOTEFRAMEWORK_IMAGE_UPLOADER();
		$('.cenote-field-gallery').CENOTEFRAMEWORK_IMAGE_GALLERY();
		$('.cenote-field-sorter').CENOTEFRAMEWORK_SORTER();
		$('.cenote-field-upload').CENOTEFRAMEWORK_UPLOADER();
		$('.cenote-field-typography').CENOTEFRAMEWORK_TYPOGRAPHY();
		$('.cenote-field-color-picker').CENOTEFRAMEWORK_COLORPICKER();
		$('.cenote-help').CENOTEFRAMEWORK_TOOLTIP();
	};

})(jQuery, window, document);
