/**
 *
 * -----------------------------------------------------------
 *
 * Cenote Framework
 *
 * -----------------------------------------------------------
 *
 */
;(function ($, window, document, undefined) {
	'use strict';

	$.CENOTEFRAMEWORK = $.CENOTEFRAMEWORK || {};

	// caching selector
	var $cenote_body = $('body');

	// caching variables
	var cenote_is_rtl = $cenote_body.hasClass('rtl');

	// ======================================================
	// CENOTEFRAMEWORK TAB NAVIGATION
	// ------------------------------------------------------
	$.fn.CENOTEFRAMEWORK_TAB_NAVIGATION = function () {
		return this.each(function () {

			var $this = $(this),
				$nav = $this.find('.cenote-nav'),
				$reset = $this.find('.cenote-reset'),
				$expand = $this.find('.cenote-expand-all');

			$nav.find('ul:first a').on('click', function (e) {

				e.preventDefault();

				var $el = $(this),
					$next = $el.next(),
					$target = $el.data('section');

				if ($next.is('ul')) {

					$next.slideToggle('fast');
					$el.closest('li').toggleClass('cenote-tab-active');

				} else {

					$('#cenote-tab-' + $target).show().siblings().hide();
					$nav.find('a').removeClass('cenote-section-active');
					$el.addClass('cenote-section-active');
					$reset.val($target);

				}

			});

			$expand.on('click', function (e) {
				e.preventDefault();
				$this.find('.cenote-body').toggleClass('cenote-show-all');
				$(this).find('.fa').toggleClass('fa-eye-slash').toggleClass('fa-eye');
			});

		});
	};
	// ======================================================

	// ======================================================
	// CENOTEFRAMEWORK DEPENDENCY
	// ------------------------------------------------------
	$.CENOTEFRAMEWORK.DEPENDENCY = function (el, param) {

		// Access to jQuery and DOM versions of element
		var base = this;
		base.$el = $(el);
		base.el = el;

		base.init = function () {

			base.ruleset = $.deps.createRuleset();

			// required for shortcode attrs
			var cfg = {
				show: function (el) {
					el.removeClass('hidden');
				},
				hide: function (el) {
					el.addClass('hidden');
				},
				log: false,
				checkTargets: false
			};

			if (param !== undefined) {
				base.depSub();
			} else {
				base.depRoot();
			}

			$.deps.enable(base.$el, base.ruleset, cfg);

		};

		base.depRoot = function () {

			base.$el.each(function () {

				$(this).find('[data-controller]').each(function () {

					var $this = $(this),
						_controller = $this.data('controller').split('|'),
						_condition = $this.data('condition').split('|'),
						_value = $this.data('value').toString().split('|'),
						_rules = base.ruleset;

					$.each(_controller, function (index, element) {

						var value = _value[index] || '',
							condition = _condition[index] || _condition[0];

						_rules = _rules.createRule('[data-depend-id="' + element + '"]', condition, value);
						_rules.include($this);

					});

				});

			});

		};

		base.depSub = function () {

			base.$el.each(function () {

				$(this).find('[data-sub-controller]').each(function () {

					var $this = $(this),
						_controller = $this.data('sub-controller').split('|'),
						_condition = $this.data('sub-condition').split('|'),
						_value = $this.data('sub-value').toString().split('|'),
						_rules = base.ruleset;

					$.each(_controller, function (index, element) {

						var value = _value[index] || '',
							condition = _condition[index] || _condition[0];

						_rules = _rules.createRule('[data-sub-depend-id="' + element + '"]', condition, value);
						_rules.include($this);

					});

				});

			});

		};


		base.init();
	};

	$.fn.CENOTEFRAMEWORK_DEPENDENCY = function (param) {
		return this.each(function () {
			new $.CENOTEFRAMEWORK.DEPENDENCY(this, param);
		});
	};
	// ======================================================

	// ======================================================
	// CENOTEFRAMEWORK CHOSEN
	// ------------------------------------------------------
	$.fn.CENOTEFRAMEWORK_CHOSEN = function () {
		return this.each(function () {
			$(this).chosen({
				allow_single_deselect: true,
				disable_search_threshold: 15,
				width: parseFloat($(this).actual('width') + 25) + 'px'
			});
		});
	};
	// ======================================================

	// ======================================================
	// CENOTEFRAMEWORK IMAGE SELECTOR
	// ------------------------------------------------------
	$.fn.CENOTEFRAMEWORK_IMAGE_SELECTOR = function () {
		return this.each(function () {

			$(this).find('label').on('click', function () {
				$(this).siblings().find('input').prop('checked', false);
			});

		});
	};
	// ======================================================

	// ======================================================
	// CENOTEFRAMEWORK SORTER
	// ------------------------------------------------------
	$.fn.CENOTEFRAMEWORK_SORTER = function () {
		return this.each(function () {

			var $this = $(this),
				$enabled = $this.find('.cenote-enabled'),
				$disabled = $this.find('.cenote-disabled');

			$enabled.sortable({
				connectWith: $disabled,
				placeholder: 'ui-sortable-placeholder',
				update: function (event, ui) {

					var $el = ui.item.find('input');

					if (ui.item.parent().hasClass('cenote-enabled')) {
						$el.attr('name', $el.attr('name').replace('disabled', 'enabled'));
					} else {
						$el.attr('name', $el.attr('name').replace('enabled', 'disabled'));
					}

				}
			});

			// avoid conflict
			$disabled.sortable({
				connectWith: $enabled,
				placeholder: 'ui-sortable-placeholder'
			});

		});
	};
	// ======================================================

	// ======================================================
	// CENOTEFRAMEWORK MEDIA UPLOADER / UPLOAD
	// ------------------------------------------------------
	$.fn.CENOTEFRAMEWORK_UPLOADER = function () {
		return this.each(function () {

			var $this = $(this),
				$add = $this.find('.cenote-add'),
				$input = $this.find('input'),
				wp_media_frame;

			$add.on('click', function (e) {

				e.preventDefault();

				// Check if the `wp.media.gallery` API exists.
				if (typeof wp === 'undefined' || !wp.media || !wp.media.gallery) {
					return;
				}

				// If the media frame already exists, reopen it.
				if (wp_media_frame) {
					wp_media_frame.open();
					return;
				}

				// Create the media frame.
				wp_media_frame = wp.media({

					// Set the title of the modal.
					title: $add.data('frame-title'),

					// Tell the modal to show only images.
					library: {
						type: $add.data('upload-type')
					},

					// Customize the submit button.
					button: {
						// Set the text of the button.
						text: $add.data('insert-title'),
					}

				});

				// When an image is selected, run a callback.
				wp_media_frame.on('select', function () {

					// Grab the selected attachment.
					var attachment = wp_media_frame.state().get('selection').first();
					$input.val(attachment.attributes.url).trigger('change');

				});

				// Finally, open the modal.
				wp_media_frame.open();

			});

		});

	};
	// ======================================================

	// ======================================================
	// CENOTEFRAMEWORK IMAGE UPLOADER
	// ------------------------------------------------------
	$.fn.CENOTEFRAMEWORK_IMAGE_UPLOADER = function () {
		return this.each(function () {

			var $this = $(this),
				$add = $this.find('.cenote-add'),
				$preview = $this.find('.cenote-image-preview'),
				$remove = $this.find('.cenote-remove'),
				$input = $this.find('input'),
				$img = $this.find('img'),
				wp_media_frame;

			$add.on('click', function (e) {
				e.preventDefault();

				// Check if the `wp.media.gallery` API exists.
				if (typeof wp === 'undefined' || !wp.media || !wp.media.gallery) {
					return;
				}

				// If the media frame already exists, reopen it.
				if (wp_media_frame) {
					wp_media_frame.open();
					return;
				}

				// Create the media frame.
				wp_media_frame = wp.media({
					library: {
						type: 'image'
					}
				});

				// When an image is selected, run a callback.
				wp_media_frame.on('select', function () {

					var attachment = wp_media_frame.state().get('selection').first().attributes;
					var thumbnail = (typeof attachment.sizes !== 'undefined' && typeof attachment.sizes.thumbnail !== 'undefined') ? attachment.sizes.thumbnail.url : attachment.url;

					$preview.removeClass('hidden');
					$img.attr('src', thumbnail);
					$input.val(attachment.id).trigger('change');

				});

				// Finally, open the modal.
				wp_media_frame.open();

			});

			// Remove image
			$remove.on('click', function (e) {
				e.preventDefault();
				$input.val('').trigger('change');
				$preview.addClass('hidden');
			});

		});

	};
	// ======================================================

	// ======================================================
	// CENOTEFRAMEWORK IMAGE GALLERY
	// ------------------------------------------------------
	$.fn.CENOTEFRAMEWORK_IMAGE_GALLERY = function () {
		return this.each(function () {

			var $this = $(this),
				$edit = $this.find('.cenote-edit'),
				$remove = $this.find('.cenote-remove'),
				$list = $this.find('ul'),
				$input = $this.find('input'),
				$img = $this.find('img'),
				wp_media_frame,
				wp_media_click;

			$this.on('click', '.cenote-add, .cenote-edit', function (e) {

				var $el = $(this),
					what = ($el.hasClass('cenote-edit')) ? 'edit' : 'add',
					state = (what === 'edit') ? 'gallery-edit' : 'gallery-library';

				e.preventDefault();

				// Check if the `wp.media.gallery` API exists.
				if (typeof wp === 'undefined' || !wp.media || !wp.media.gallery) {
					return;
				}

				// If the media frame already exists, reopen it.
				if (wp_media_frame) {
					wp_media_frame.open();
					wp_media_frame.setState(state);
					return;
				}

				// Create the media frame.
				wp_media_frame = wp.media({
					library: {
						type: 'image'
					},
					frame: 'post',
					state: 'gallery',
					multiple: true
				});

				// Open the media frame.
				wp_media_frame.on('open', function () {

					var ids = $input.val();

					if (ids) {

						var get_array = ids.split(',');
						var library = wp_media_frame.state('gallery-edit').get('library');

						wp_media_frame.setState(state);

						get_array.forEach(function (id) {
							var attachment = wp.media.attachment(id);
							library.add(attachment ? [attachment] : []);
						});

					}
				});

				// When an image is selected, run a callback.
				wp_media_frame.on('update', function () {

					var inner = '';
					var ids = [];
					var images = wp_media_frame.state().get('library');

					images.each(function (attachment) {

						var attributes = attachment.attributes;
						var thumbnail = (typeof attributes.sizes.thumbnail !== 'undefined') ? attributes.sizes.thumbnail.url : attributes.url;

						inner += '<li><img src="' + thumbnail + '"></li>';
						ids.push(attributes.id);

					});

					$input.val(ids).trigger('change');
					$list.html('').append(inner);
					$remove.removeClass('hidden');
					$edit.removeClass('hidden');

				});

				// Finally, open the modal.
				wp_media_frame.open();
				wp_media_click = what;

			});

			// Remove image
			$remove.on('click', function (e) {
				e.preventDefault();
				$list.html('');
				$input.val('').trigger('change');
				$remove.addClass('hidden');
				$edit.addClass('hidden');
			});

		});

	};
	// ======================================================

	// ======================================================
	// CENOTEFRAMEWORK TYPOGRAPHY
	// ------------------------------------------------------
	$.fn.CENOTEFRAMEWORK_TYPOGRAPHY = function () {
		return this.each(function () {

			var typography = $(this),
				family_select = typography.find('.cenote-typo-family'),
				variants_select = typography.find('.cenote-typo-variant'),
				$unique = typography.parent('div').data('unique-id'),
				$option = typography.parent('div').data('option-id');


			family_select.on('change', function () {

				var _this = $(this),
					_variants = _this.find(':selected').data('variants'),
					_family_val	= _this.find(':selected').text();



				if (variants_select.length) {

					variants_select.find('option').remove();

					$.each(_variants.split('|'), function (key, text) {
						variants_select.append('<option value="' + text + '">' + text + '</option>');
					});

					variants_select.find('option[value="regular"]').attr('selected', 'selected').trigger('chosen:updated');

					variants_select.on('change', function() {
						var box = {};
						var boxes =  [];

						box = {
							family: _family_val,
							variant: $(this).find(':selected').text()
						};

						boxes.push(box);

						wp.customize.control($option).setting.set(box);
					});

				}

				var obj 	= typography.find(':input').serializeObjectCENOTE();
				var data 	= !$.isEmptyObject(obj) ? obj[$unique][$option] : '';

				wp.customize.control($option).setting.set(data);
			});
		});
	};
	// ======================================================

	// ======================================================
	// CENOTEFRAMEWORK GROUP
	// ------------------------------------------------------
	$.fn.CENOTEFRAMEWORK_GROUP = function () {
		return this.each(function () {

			var _this = $(this),
				field_groups	= _this.find('> .cenote-groups, > .cenote-fieldset > .cenote-groups'),
				accordion_group	= _this.find('> .cenote-accordion, > .cenote-fieldset > .cenote-accordion'),
				clone_group		= _this.find('> .cenote-group:first, > .cenote-fieldset > .cenote-group:first').clone();

			if (accordion_group.length) {
				accordion_group.accordion({
					header: '.cenote-group-title',
					collapsible: true,
					active: false,
					animate: 250,
					heightStyle: 'content',
					icons: {
						'header': 'dashicons dashicons-arrow-right',
						'activeHeader': 'dashicons dashicons-arrow-down'
					},
					beforeActivate: function (event, ui) {
						$(ui.newPanel).CENOTEFRAMEWORK_DEPENDENCY('sub');
					}
				});
			}

			field_groups.sortable({
				axis: 'y',
				handle: '.cenote-group-title',
				helper: 'original',
				cursor: 'move',
				placeholder: 'widget-placeholder',
				start: function (event, ui) {
					var inside = ui.item.children('.cenote-group-content');
					if (inside.css('display') === 'block') {
						inside.hide();
						field_groups.sortable('refreshPositions');
					}
				},
				stop: function (event, ui) {
					ui.item.children('.cenote-group-title').triggerHandler('focusout');
					accordion_group.accordion({active: false});
					field_groups.CENOTE_CUSTOMIZER_REFRESH();
				}
			});

			var i = 0;
			$('.cenote-add-group', _this).unbind('click').on('click', function (e) {
				e.preventDefault();

				clone_group.find('input, select, textarea').each(function () {
					var level	= _this.parents('.cenote-groups').length + 1;
					var nth		= 0;

					level		= _this.parents('.widget-content').length ? level + 1 : level;

					this.name	= this.name.replace(/\[(\d+)\]/g, function (string, id) {
						nth++;

						if (level <= 0 && nth || level == nth) {
							return '[' + (parseInt(id, 10) + 1) + ']';
						}

						return string;
					});
				});

				var cloned = clone_group.clone().removeClass('hidden');
				field_groups.append(cloned);

				if (accordion_group.length) {
					field_groups.accordion('refresh');
					field_groups.accordion({active: cloned.index()});
				}

				field_groups.find('input, select, textarea').each(function () {
					this.name = this.name.replace('[_nonce]', '');
				});

				// run all field plugins
				cloned.CENOTEFRAMEWORK_DEPENDENCY('sub');
				cloned.CENOTEFRAMEWORK_RELOAD_PLUGINS();
				field_groups.CENOTE_CUSTOMIZER_REFRESH();

				i++;

			});

			field_groups.on('click', '.cenote-remove-group', function (e) {
				e.preventDefault();
				$(this).closest('.cenote-group').remove();
				field_groups.CENOTE_CUSTOMIZER_REFRESH();
			});

		});
	};
	// ======================================================

	// ======================================================
	// CENOTEFRAMEWORK RESET CONFIRM
	// ------------------------------------------------------
	$.fn.CENOTEFRAMEWORK_CONFIRM = function () {
		return this.each(function () {
			$(this).on('click', function (e) {
				if (!confirm('Are you sure?')) {
					e.preventDefault();
				}
			});
		});
	};
	// ======================================================

	// ======================================================
	// CENOTEFRAMEWORK SAVE OPTIONS
	// ------------------------------------------------------
	$.fn.CENOTEFRAMEWORK_SAVE = function () {
		return this.each(function () {

			var $this = $(this),
				$text = $this.data('save'),
				$value = $this.val(),
				$ajax = $('#cenote-save-ajax');

			$(document).on('keydown', function (event) {
				if (event.ctrlKey || event.metaKey) {
					if (String.fromCharCode(event.which).toLowerCase() === 's') {
						event.preventDefault();
						$this.trigger('click');
					}
				}
			});

			$this.on('click', function (e) {

				if ($ajax.length) {

					if (typeof tinyMCE === 'object') {
						tinyMCE.triggerSave();
					}

					$this.prop('disabled', true).attr('value', $text);

					var serializedOptions = $('#cenoteframework_form').serialize();

					$.post('options.php', serializedOptions).error(function () {
						alert('Error, Please try again.');
					}).success(function () {
						$this.prop('disabled', false).attr('value', $value);
						$ajax.hide().fadeIn().delay(250).fadeOut();
					});

					e.preventDefault();

				} else {

					$this.addClass('disabled').attr('value', $text);

				}

			});

		});
	};
	// ======================================================

	// ======================================================
	// CENOTEFRAMEWORK SAVE TAXONOMY CLEAR FORM ELEMENTS
	// ------------------------------------------------------
	$.fn.CENOTEFRAMEWORK_TAXONOMY = function () {
		return this.each(function () {

			var $this = $(this),
				$parent = $this.parent();

			// Only works in add-tag form
			if ($parent.attr('id') === 'addtag') {

				var $submit = $parent.find('#submit'),
					$name = $parent.find('#tag-name'),
					$wrap = $parent.find('.cenote-framework'),
					$clone = $wrap.find('.cenote-element').clone(),
					$list = $('#the-list'),
					flooding = false;

				$submit.on('click', function () {

					if (!flooding) {

						$list.on('DOMNodeInserted', function () {

							if (flooding) {

								$wrap.empty();
								$wrap.html($clone);
								$clone = $clone.clone();

								$wrap.CENOTEFRAMEWORK_RELOAD_PLUGINS();
								$wrap.CENOTEFRAMEWORK_DEPENDENCY();

								flooding = false;

							}

						});

					}

					flooding = true;

				});

			}

		});
	};
	// ======================================================

	// ======================================================
	// CENOTEFRAMEWORK UI DIALOG OVERLAY HELPER
	// ------------------------------------------------------
	if (typeof $.widget !== 'undefined' && typeof $.ui !== 'undefined' && typeof $.ui.dialog !== 'undefined') {
		$.widget('ui.dialog', $.ui.dialog, {
				_createOverlay: function () {
					this._super();
					if (!this.options.modal) {
						return;
					}
					this._on(this.overlay, {click: 'close'});
				}
			}
		);
	}

	// ======================================================
	// CENOTEFRAMEWORK ICONS MANAGER
	// ------------------------------------------------------
	$.CENOTEFRAMEWORK.ICONS_MANAGER = function () {

		var base = this,
			onload = true,
			$parent;

		base.init = function () {

			$cenote_body.on('click', '.cenote-icon-add', function (e) {

				e.preventDefault();

				var $this = $(this),
					$dialog = $('#cenote-icon-dialog'),
					$load = $dialog.find('.cenote-dialog-load'),
					$select = $dialog.find('.cenote-dialog-select'),
					$insert = $dialog.find('.cenote-dialog-insert'),
					$search = $dialog.find('.cenote-icon-search');

				// set parent
				$parent = $this.closest('.cenote-icon-select');

				// open dialog
				$dialog.dialog({
					width: 850,
					height: 700,
					modal: true,
					resizable: false,
					closeOnEscape: true,
					position: {my: 'center', at: 'center', of: window},
					open: function () {

						// fix scrolling
						$cenote_body.addClass('cenote-icon-scrolling');

						// fix button for VC
						$('.ui-dialog-titlebar-close').addClass('ui-button');

						// set viewpoint
						$(window).on('resize', function () {

							var height = $(window).height(),
								load_height = Math.floor(height - 237),
								set_height = Math.floor(height - 125);

							$dialog.dialog('option', 'height', set_height).parent().css('max-height', set_height);
							$dialog.css('overflow', 'auto');
							$load.css('height', load_height);

						}).resize();

					},
					close: function () {
						$cenote_body.removeClass('cenote-icon-scrolling');
					}
				});

				// load icons
				if (onload) {

					$.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {
							action: 'cenote-get-icons'
						},
						success: function (content) {

							$load.html(content);
							onload = false;

							$load.on('click', 'a', function (e) {

								e.preventDefault();

								var icon = $(this).data('cenote-icon');

								$parent.find('i').removeAttr('class').addClass(icon);
								$parent.find('input').val(icon).trigger('change');
								$parent.find('.cenote-icon-preview').removeClass('hidden');
								$parent.find('.cenote-icon-remove').removeClass('hidden');
								$dialog.dialog('close');

							});

							$search.keyup(function () {

								var value = $(this).val(),
									$icons = $load.find('a');

								$icons.each(function () {

									var $ico = $(this);

									if ($ico.data('cenote-icon').search(new RegExp(value, 'i')) < 0) {
										$ico.hide();
									} else {
										$ico.show();
									}

								});

							});

							$load.find('.cenote-icon-tooltip').cenotetooltip({
								html: true,
								placement: 'top',
								container: 'body'
							});

						}
					});

				}

			});

			$cenote_body.on('click', '.cenote-icon-remove', function (e) {

				e.preventDefault();

				var $this = $(this),
					$parent = $this.closest('.cenote-icon-select');

				$parent.find('.cenote-icon-preview').addClass('hidden');
				$parent.find('input').val('').trigger('change');
				$this.addClass('hidden');

			});

		};

		// run initializer
		base.init();
	};
	// ======================================================

	// ======================================================
	// CENOTEFRAMEWORK SHORTCODE MANAGER
	// ------------------------------------------------------
	$.CENOTEFRAMEWORK.SHORTCODE_MANAGER = function () {

		var base = this, deploy_atts;

		base.init = function () {

			var $dialog = $('#cenote-shortcode-dialog'),
				$insert = $dialog.find('.cenote-dialog-insert'),
				$shortcodeload = $dialog.find('.cenote-dialog-load'),
				$selector = $dialog.find('.cenote-dialog-select'),
				shortcode_target = false,
				shortcode_name,
				shortcode_view,
				shortcode_clone,
				$shortcode_button,
				editor_id;

			$cenote_body.on('click', '.cenote-shortcode', function (e) {

				e.preventDefault();

				// init chosen
				$selector.CENOTEFRAMEWORK_CHOSEN();

				$shortcode_button = $(this);
				shortcode_target = $shortcode_button.hasClass('cenote-shortcode-textarea');
				editor_id = $shortcode_button.data('editor-id');

				$dialog.dialog({
					width: 850,
					height: 700,
					modal: true,
					resizable: false,
					closeOnEscape: true,
					position: {my: 'center', at: 'center', of: window},
					open: function () {

						// fix scrolling
						$cenote_body.addClass('cenote-shortcode-scrolling');

						// fix button for VC
						$('.ui-dialog-titlebar-close').addClass('ui-button');

						// set viewpoint
						$(window).on('resize', function () {

							var height = $(window).height(),
								load_height = Math.floor(height - 281),
								set_height = Math.floor(height - 125);

							$dialog.dialog('option', 'height', set_height).parent().css('max-height', set_height);
							$dialog.css('overflow', 'auto');
							$shortcodeload.css('height', load_height);

						}).resize();

					},
					close: function () {
						shortcode_target = false;
						$cenote_body.removeClass('cenote-shortcode-scrolling');
					}
				});

			});

			$selector.on('change', function () {

				var $elem_this = $(this);
				shortcode_name = $elem_this.val();
				shortcode_view = $elem_this.find(':selected').data('view');

				// check val
				if (shortcode_name.length) {

					$.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {
							action: 'cenote-get-shortcode',
							shortcode: shortcode_name
						},
						success: function (content) {

							$shortcodeload.html(content);
							$insert.parent().removeClass('hidden');

							shortcode_clone = $('.cenote-shortcode-clone', $dialog).clone();

							$shortcodeload.CENOTEFRAMEWORK_DEPENDENCY();
							$shortcodeload.CENOTEFRAMEWORK_DEPENDENCY('sub');
							$shortcodeload.CENOTEFRAMEWORK_RELOAD_PLUGINS();

						}
					});

				} else {

					$insert.parent().addClass('hidden');
					$shortcodeload.html('');

				}

			});

			$insert.on('click', function (e) {

				e.preventDefault();

				var send_to_shortcode = '',
					ruleAttr = 'data-atts',
					cloneAttr = 'data-clone-atts',
					cloneID = 'data-clone-id';

				switch (shortcode_view) {

					case 'contents':

						$('[' + ruleAttr + ']', '.cenote-dialog-load').each(function () {
							var _this = $(this), _atts = _this.data('atts');
							send_to_shortcode += '[' + _atts + ']';
							send_to_shortcode += _this.val();
							send_to_shortcode += '[/' + _atts + ']';
						});

						break;

					case 'clone':

						send_to_shortcode += '[' + shortcode_name; // begin: main-shortcode

						// main-shortcode attributes
						$('[' + ruleAttr + ']', '.cenote-dialog-load .cenote-element:not(.hidden)').each(function () {
							var _this_main = $(this), _this_main_atts = _this_main.data('atts');
							send_to_shortcode += base.validate_atts(_this_main_atts, _this_main);  // validate empty atts
						});

						send_to_shortcode += ']'; // end: main-shortcode attributes

						// multiple-shortcode each
						$('[' + cloneID + ']', '.cenote-dialog-load').each(function () {

							var _this_clone = $(this),
								_clone_id = _this_clone.data('clone-id');

							send_to_shortcode += '[' + _clone_id; // begin: multiple-shortcode

							// multiple-shortcode attributes
							$('[' + cloneAttr + ']', _this_clone.find('.cenote-element').not('.hidden')).each(function () {

								var _this_multiple = $(this), _atts_multiple = _this_multiple.data('clone-atts');

								// is not attr content, add shortcode attribute else write content and close shortcode tag
								if (_atts_multiple !== 'content') {
									send_to_shortcode += base.validate_atts(_atts_multiple, _this_multiple); // validate empty atts
								} else if (_atts_multiple === 'content') {
									send_to_shortcode += ']';
									send_to_shortcode += _this_multiple.val();
									send_to_shortcode += '[/' + _clone_id + '';
								}
							});

							send_to_shortcode += ']'; // end: multiple-shortcode

						});

						send_to_shortcode += '[/' + shortcode_name + ']'; // end: main-shortcode

						break;

					case 'clone_duplicate':

						// multiple-shortcode each
						$('[' + cloneID + ']', '.cenote-dialog-load').each(function () {

							var _this_clone = $(this),
								_clone_id = _this_clone.data('clone-id');

							send_to_shortcode += '[' + _clone_id; // begin: multiple-shortcode

							// multiple-shortcode attributes
							$('[' + cloneAttr + ']', _this_clone.find('.cenote-element').not('.hidden')).each(function () {

								var _this_multiple = $(this),
									_atts_multiple = _this_multiple.data('clone-atts');


								// is not attr content, add shortcode attribute else write content and close shortcode tag
								if (_atts_multiple !== 'content') {
									send_to_shortcode += base.validate_atts(_atts_multiple, _this_multiple); // validate empty atts
								} else if (_atts_multiple === 'content') {
									send_to_shortcode += ']';
									send_to_shortcode += _this_multiple.val();
									send_to_shortcode += '[/' + _clone_id + '';
								}
							});

							send_to_shortcode += ']'; // end: multiple-shortcode

						});

						break;

					default:

						send_to_shortcode += '[' + shortcode_name;

						$('[' + ruleAttr + ']', '.cenote-dialog-load .cenote-element:not(.hidden)').each(function () {

							var _this = $(this), _atts = _this.data('atts');

							// is not attr content, add shortcode attribute else write content and close shortcode tag
							if (_atts !== 'content') {
								send_to_shortcode += base.validate_atts(_atts, _this); // validate empty atts
							} else if (_atts === 'content') {
								send_to_shortcode += ']';
								send_to_shortcode += _this.val();
								send_to_shortcode += '[/' + shortcode_name + '';
							}

						});

						send_to_shortcode += ']';

						break;

				}

				if (shortcode_target) {
					var $textarea = $shortcode_button.next();
					$textarea.val(base.insertAtChars($textarea, send_to_shortcode)).trigger('change');
				} else {
					base.send_to_editor(send_to_shortcode, editor_id);
				}

				deploy_atts = null;

				$dialog.dialog('close');

			});

			// cloner button
			var cloned = 0;
			$dialog.on('click', '#shortcode-clone-button', function (e) {

				e.preventDefault();

				// clone from cache
				var cloned_el = shortcode_clone.clone().hide();

				cloned_el.find('input:radio').attr('name', '_nonce_' + cloned);

				$('.cenote-shortcode-clone:last').after(cloned_el);

				// add - remove effects
				cloned_el.slideDown(100);

				cloned_el.find('.cenote-remove-clone').show().on('click', function (e) {

					cloned_el.slideUp(100, function () {
						cloned_el.remove();
					});
					e.preventDefault();

				});

				// reloadPlugins
				cloned_el.CENOTEFRAMEWORK_DEPENDENCY('sub');
				cloned_el.CENOTEFRAMEWORK_RELOAD_PLUGINS();
				cloned++;

			});

		};

		base.validate_atts = function (_atts, _this) {

			var el_value;

			if (_this.data('check') !== undefined && deploy_atts === _atts) {
				return '';
			}

			deploy_atts = _atts;

			if (_this.closest('.pseudo-field').hasClass('hidden') === true) {
				return '';
			}
			if (_this.hasClass('pseudo') === true) {
				return '';
			}

			if (_this.is(':checkbox') || _this.is(':radio')) {
				el_value = _this.is(':checked') ? _this.val() : '';
			} else {
				el_value = _this.val();
			}

			if (_this.data('check') !== undefined) {
				el_value = _this.closest('.cenote-element').find('input:checked').map(function () {
					return $(this).val();
				}).get();
			}

			if (el_value !== null && el_value !== undefined && el_value !== '' && el_value.length !== 0) {
				return ' ' + _atts + '="' + el_value + '"';
			}

			return '';

		};

		base.insertAtChars = function (_this, currentValue) {

			var obj = (typeof _this[0].name !== 'undefined') ? _this[0] : _this;

			if (obj.value.length && typeof obj.selectionStart !== 'undefined') {
				obj.focus();
				return obj.value.substring(0, obj.selectionStart) + currentValue + obj.value.substring(obj.selectionEnd, obj.value.length);
			} else {
				obj.focus();
				return currentValue;
			}

		};

		base.send_to_editor = function (html, editor_id) {

			var tinymce_editor;

			if (typeof tinymce !== 'undefined') {
				tinymce_editor = tinymce.get(editor_id);
			}

			if (tinymce_editor && !tinymce_editor.isHidden()) {
				tinymce_editor.execCommand('mceInsertContent', false, html);
			} else {
				var $editor = $('#' + editor_id);
				$editor.val(base.insertAtChars($editor, html)).trigger('change');
			}

		};

		// run initializer
		base.init();
	};
	// ======================================================

	// ======================================================
	// CENOTEFRAMEWORK COLORPICKER
	// ------------------------------------------------------
	if (typeof Color === 'function') {

		// adding alpha support for Automattic Color.js toString function.
		Color.fn.toString = function () {

			// check for alpha
			if (this._alpha < 1) {
				return this.toCSS('rgba', this._alpha).replace(/\s+/g, '');
			}

			var hex = parseInt(this._color, 10).toString(16);

			if (this.error) {
				return '';
			}

			// maybe left pad it
			if (hex.length < 6) {
				for (var i = 6 - hex.length - 1; i >= 0; i--) {
					hex = '0' + hex;
				}
			}

			return '#' + hex;

		};

	}

	$.CENOTEFRAMEWORK.PARSE_COLOR_VALUE = function (val) {

		var value = val.replace(/\s+/g, ''),
			alpha = (value.indexOf('rgba') !== -1) ? parseFloat(value.replace(/^.*,(.+)\)/, '$1') * 100) : 100,
			rgba = (alpha < 100) ? true : false;

		return {value: value, alpha: alpha, rgba: rgba};

	};

	$.fn.CENOTEFRAMEWORK_SLIDER = function () {
		return this.each(function () {

			var $this = $(this),
				$input = $this.find('input'),
				$slider = $this.find('.cenote-slider-ui'),
				data = $input.data(),
				value = $input.val() || 0,
				$reset = $this.find('.reset-to-default');

			if ($slider.hasClass('ui-slider')) {
				$slider.empty();
			}

			$slider.slider({
				range: 'min',
				value: value,
				min: data.min,
				max: data.max,
				step: data.step,
				slide: function (e, o) {
					$input.val(o.value).trigger('change');
				}
			});

			$input.keyup(function () {
				$slider.slider('value', $input.val());
			});

			$reset.on('click', function (el) {
				el.preventDefault();
				$slider.slider('value', $reset.attr('data-default'));
				$input.val($reset.attr('data-default'));
			});
		});
	};

	$.fn.CENOTEFRAMEWORK_COLORPICKER = function () {

		return this.each(function () {

			var $this = $(this);

			// check for rgba enabled/disable
			if ($this.data('rgba') !== false) {

				// parse value
				var picker = $.CENOTEFRAMEWORK.PARSE_COLOR_VALUE($this.val());

				// wpColorPicker core
				$this.wpColorPicker({

					// wpColorPicker: clear
					clear: function () {
						$this.trigger('keyup');
					},

					// wpColorPicker: change
					change: function (event, ui) {

						var ui_color_value = ui.color.toString();

						// update checkerboard background color
						$this.closest('.wp-picker-container').find('.cenote-alpha-slider-offset').css('background-color', ui_color_value);
						$this.val(ui_color_value).trigger('change');

					},

					// wpColorPicker: create
					create: function () {

						// set variables for alpha slider
						var a8cIris = $this.data('a8cIris'),
							$container = $this.closest('.wp-picker-container'),

							// appending alpha wrapper
							$alpha_wrap = $('<div class="cenote-alpha-wrap">' +
								'<div class="cenote-alpha-slider"></div>' +
								'<div class="cenote-alpha-slider-offset"></div>' +
								'<div class="cenote-alpha-text"></div>' +
								'</div>').appendTo($container.find('.wp-picker-holder')),

							$alpha_slider = $alpha_wrap.find('.cenote-alpha-slider'),
							$alpha_text = $alpha_wrap.find('.cenote-alpha-text'),
							$alpha_offset = $alpha_wrap.find('.cenote-alpha-slider-offset');

						// alpha slider
						$alpha_slider.slider({

							// slider: slide
							slide: function (event, ui) {

								var slide_value = parseFloat(ui.value / 100);

								// update iris data alpha && wpColorPicker color option && alpha text
								a8cIris._color._alpha = slide_value;
								$this.wpColorPicker('color', a8cIris._color.toString());
								$alpha_text.text((slide_value < 1 ? slide_value : ''));

							},

							// slider: create
							create: function () {

								var slide_value = parseFloat(picker.alpha / 100),
									alpha_text_value = slide_value < 1 ? slide_value : '';

								// update alpha text && checkerboard background color
								$alpha_text.text(alpha_text_value);
								$alpha_offset.css('background-color', picker.value);

								// wpColorPicker clear for update iris data alpha && alpha text && slider color option
								$container.on('click', '.wp-picker-clear', function () {

									a8cIris._color._alpha = 1;
									$alpha_text.text('').trigger('change');
									$alpha_slider.slider('option', 'value', 100).trigger('slide');

								});

								// wpColorPicker default button for update iris data alpha && alpha text && slider color option
								$container.on('click', '.wp-picker-default', function () {

									var default_picker = $.CENOTEFRAMEWORK.PARSE_COLOR_VALUE($this.data('default-color')),
										default_value = parseFloat(default_picker.alpha / 100),
										default_text = default_value < 1 ? default_value : '';

									a8cIris._color._alpha = default_value;
									$alpha_text.text(default_text);
									$alpha_slider.slider('option', 'value', default_picker.alpha).trigger('slide');

								});

								// show alpha wrapper on click color picker button
								$container.on('click', '.wp-color-result', function () {
									$alpha_wrap.toggle();
								});

								// hide alpha wrapper on click body
								$cenote_body.on('click.wpcolorpicker', function () {
									$alpha_wrap.hide();
								});

							},

							// slider: options
							value: picker.alpha,
							step: 1,
							min: 1,
							max: 100

						});
					}

				});

			} else {

				// wpColorPicker default picker
				$this.wpColorPicker({
					clear: function () {
						$this.trigger('keyup');
					},
					change: function (event, ui) {
						$this.val(ui.color.toString()).trigger('change');
					}
				});

			}

		});

	};
	// ======================================================

	// ======================================================
	// ON WIDGET-ADDED RELOAD FRAMEWORK PLUGINS
	// ------------------------------------------------------
	$.CENOTEFRAMEWORK.WIDGET_RELOAD_PLUGINS = function () {
		$(document).on('widget-added widget-updated', function (event, $widget) {
			$widget.CENOTEFRAMEWORK_RELOAD_PLUGINS();
			$widget.CENOTEFRAMEWORK_DEPENDENCY();
		});
	};

	// ======================================================
	// DATETIME PICKER
	// ------------------------------------------------------
	$.fn.CENOTEFRAMEWORK_DATEPICKER = function() {
		return this.each(function () {
			$(this).datetimepicker();
		});
	};

	// ======================================================
	// TOOLTIP HELPER
	// ------------------------------------------------------
	$.fn.CENOTEFRAMEWORK_TOOLTIP = function () {
		return this.each(function () {
			var placement = (cenote_is_rtl) ? 'right' : 'left';
			$(this).cenotetooltip({html: true, placement: placement, container: 'body'});
		});
	};

	$.fn.CENOTEFRAMEWORK_SWICHER = function () {
		return this.each(function () {
			var $this = $(this),
				$input = $this.find('input[type="checkbox"]'),
				$label = $input.parents('label');

			if ($input.is(':checked')) {
				$label.addClass('has-check');
			} else {
				$label.removeClass('has-check');
			}

			$input.on('change', function () {
				if ($(this).is(':checked')) {
					$label.addClass('has-check');
				} else {
					$label.removeClass('has-check');
				}
			})
		})
	};

	//
	// Customize Refresh
	//
	$.fn.CENOTEFRAMEWORK_CUSTOMIZER_REFRESH = function () {
		return this.each(function () {
			var $this = $(this),
				$complex = $this.closest('.test-trung');

			$(document).trigger('CENOTEFRAMEWORK_CUSTOMIZER_REFRESH', $this);

			if (wp.customize === undefined || $complex.length === 0) {
				return;
			}

			var $input = $complex.find(':input'),
				$unique = $complex.data('unique-id'),
				$option = $complex.data('option-id'),
				obj = $input,
				data = !$.isEmptyObject(obj) ? obj[$unique][$option] : '';

			console.log('trung');
			wp.customize.control($unique + '[' + $option + ']').setting.set(data);
		});
	};

	//
	// Customize Refresh
	//
	$.fn.CENOTE_CUSTOMIZER_REFRESH = function () {
		return this.each(function () {
			var $this = $(this),
				$complex = $this.closest('.cenote-customize-complex');

			$(document).trigger('cenote-customizer-refresh', $this);

			if (wp.customize === undefined || $complex.length === 0) {
				return;
			}

			var $input = $complex.find(':input'),
				$unique = $complex.data('unique-id'),
				$option = $complex.data('option-id'),
				obj = $input.serializeObjectCENOTE(),
				data = !$.isEmptyObject(obj) ? obj[$unique][$option] : '';
				data = data.filter(Boolean);
			console.log(data);

			wp.customize.control($option).setting.set(data);
		});
	};

	//
	// Customize Listen Form Elements
	//
	$.fn.CENOTEFRAMEWORK_CUSTOMIZER_LISTEN = function (has_closest) {
		return this.each(function () {
			if (wp.customize === undefined) {
				return;
			}

			var $this = has_closest ? $(this).closest('.cenote-customize-complex') : $(this),
				$input = $this.find(':input'),
				$unique = $this.data('unique-id'),
				$option = $this.data('option-id');

			if ($unique === undefined) {
				return;
			}

			$input.on('change keyup', function () {
				var obj = $this.find(':input').serializeObjectCENOTE();

				var data = !$.isEmptyObject(obj) ? obj[$unique][$option] : '';

				wp.customize.control($option).setting.set(data);
			});
		});
	};

	$.fn.CENOTEFRAMEWORK_SPECTRUM = function () {
		return this.each(function () {
			var $this = $(this);

			$this.spectrum({
				showInput: true,
				showInitial: true,
				allowEmpty: true,
				showAlpha: true,
				clickoutFiresChange: true,
				preferredFormat: "rgb",
				show: function () {
				},
				move: function (e) {
					$this.siblings('.color_hex').text('');
					if (e) {
						$this.siblings('.color_hex').text(e.getAlpha() == 1 ? e.toHexString() : e.toRgbString())
					}
				},
				change: function (e) {
					$this.siblings('.color_hex').text('');
					if (e) {
						$this.siblings('.color_hex').text(e.getAlpha() == 1 ? e.toHexString() : e.toRgbString())
					}
				},
				hide: function (e) {
					if (!e) {
						$this.siblings('.color_hex').text('');
					} else {
						var f = e.getAlpha() == 1 ? e.toHexString() : e.toRgbString();
						$this.siblings('.color_hex').text(f);
					}
				}
			});
		});
	};

	$.fn.CENOTEFRAMEWORK_NUMBER = function() {
		return this.each(function () {
			var $this = $(this),
				$t		= $this.find('span.t'),
				$b		= $this.find('span.b'),
				$input	= $this.find('input[type="number"]');

			$t.on('click', function() {
				var $val	= $input.val(),
					$n_val	= ++$val;

				$input.val($n_val);
			});

			$b.on('click', function() {
				var $val	= $input.val(),
					$n_val	= --$val;

				if ($val >= 0) {
					$input.val($n_val);
				}
			});
		});

	};

	// ======================================================

	// ======================================================
	// CUSTOM SIDEBARS
	// ------------------------------------------------------
	$.CENOTEFRAMEWORK.CUSTOM_SIDEBARS	= function() {
		var base	= this;

		base.custom_sidebars = (function() {
			function Custom_Sidebars() {
				this.widget_wrap	= $('.widget-liquid-right');
				this.widget_area	= $('#widgets-right');
				this.widget_add		= $('#tmpl-cenote-add-widget');

				this.create_form();
				this.add_elements();
				this.events();
			}

			Custom_Sidebars.prototype.create_form = function () {
				this.widget_wrap.append(this.widget_add.html());

				this.widget_name	= this.widget_wrap.find('input[name="cenote-add-widget"]');
				this.nonce			= this.widget_wrap.find('input[name="cenote-delete-nonce"]').val();
			};

			Custom_Sidebars.prototype.add_elements = function () {
				this.widget_area.find('.sidebar-cenote-custom-widget').append('<span class="cenote-area-delete"><span class="dashicons dashicons-no"></span></span>');

				this.widget_area.find('.sidebar-cenote-custom-widget').each(function () {
					var where_to_add	= $(this).find('.widgets-sortables');
					var id				= where_to_add.attr('id').replace('sidebar-', '');

				});
			};

			Custom_Sidebars.prototype.events = function () {
				this.widget_wrap.on('click', '.cenote-area-delete', $.proxy(this.delete_sidebar, this));
			};

			Custom_Sidebars.prototype.delete_sidebar = function (e) {
				var widget		= $(e.currentTarget).parents('.widgets-holder-wrap:eq(0)');
				var title		= widget.find('.sidebar-name h2');
				var spinner		= widget.find('.spinner');
				var widget_name	= widget.children().first().attr('id');
				var obj			= this;

				if (confirm(acsL10n.delete_sidebar_area)) {
					$.ajax({
						type:	'POST',
						url:	window.ajaxurl,
						data: {
							action: 'cenote_ajax_delete_custom_sidebar',
							name: widget_name,
							_wpnonce: obj.nonce
						},

						beforeSend: function () {
							spinner.addClass('activate');
						},

						success: function (response) {
							if (response === "sidebar-deleted") {
								widget.slideUp(200, function () {
									$('.widget-control-remove', widget).trigger('click');
									widget.remove();
									wpWidgets.saveOrder();
								});
							}
						}
					});
				}
			};

			return Custom_Sidebars;
		})();

		new base.custom_sidebars();
	};

	$.fn.CENOTEFRAMEWORK_IMAGE_DROPDOWN = function () {
		return this.each(function () {
			var $this = $(this);

			$this.find('.show-option').on('click', function(e) {
				console.log('1');
    			$(this).siblings('.list-options').slideToggle(200);
    		});

			$this.find('.list-options li').on('click', function() {
				var $li 	= $(this),
					$img 	= $li.find('span').html();

				$this.find('.list-options li').removeClass('active');
				$this.find('.show-option span').html($img);
				$li.addClass('active');

				$this.find('.list-options').slideUp(100);
			});
		});
	};

	// ======================================================
	// DEMO IMPORTER
	// ------------------------------------------------------
	$.CENOTEFRAMEWORK.DEMO_IMPORTER	= function() {
		var base	= this;

		var progress_bar	= {
			progress_bar_wrapper_element:	'',
			progress_bar_element:			'',
			current_value:					0,
			goto_value:						0,
			timer:							'',
			last_goto_value: 				0,

			show: function show() {
				progress_bar.progress_bar_wrapper_element.addClass('cenote-demo-progress-bar-visible');
			},

			hide: function hide() {
				progress_bar.progress_bar_wrapper_element.removeClass('cenote-demo-progress-bar-visible');
			},

			reset: function reset() {
				clearInterval(progress_bar.timer);

				progress_bar.current_value		= 0;
				progress_bar.goto_value			= 0;
				progress_bar.timer				= '';
				progress_bar.last_goto_value	= 0;

				progress_bar.change(0);
			},


			change: function change(new_progress) {
				progress_bar.progress_bar_element.css('width', new_progress + '%');

				progress_bar.last_goto_value	= new_progress;

				if (new_progress === 100) {
					clearInterval(progress_bar.timer);
				}
			},

			timer_change: function timer_change(new_progress) {
				clearInterval(progress_bar.timer);

				progress_bar._ui_change(progress_bar.last_goto_value);

				progress_bar.current_value	= progress_bar.last_goto_value;

				clearInterval(progress_bar.timer);

				progress_bar.timer	= setInterval(function () {
					if (Math.floor((Math.random() * 5) + 1) === 1) {
						var tmp_value	= Math.floor((Math.random() * 5) + 1) + progress_bar.current_value;

						if (tmp_value <= new_progress) {
							progress_bar._ui_change(progress_bar.current_value);

							progress_bar.current_value	= tmp_value;
						} else {
							progress_bar._ui_change(new_progress);
							clearInterval(progress_bar.timer);
						}
					}
				}, 1000);
				progress_bar.last_goto_value = new_progress;
			},

			_ui_change: function change(new_progress) {
				progress_bar.progress_bar_element.css('width', new_progress + '%');
			}
		};

		base.init	= function() {
			$('.cenote-button-install-demo').click(function(e) {
				e.preventDefault();

				var $demo	= $('.cenote-demo');

				if ($demo.hasClass('cenote-demo-installed') || $demo.hasClass('cenote-demo-installing') || $demo.hasClass('cenote-demo-disabled') || $(this).hasClass('button-disabled')) {
					return;
				}

				var c	= confirm(adiL10n.install_demo_confirm);

				if (c) {
					base.install($(this).data('demo-id'));
				}
			});

			$('.cenote-button-uninstall-demo').click(function(e) {
				e.preventDefault();

				var c = confirm(adiL10n.uninstall_demo_confirm);

				if (c) {
					base.uninstall($(this).data('demo-id'));
				}
			});

			$('.cenote-button-install-demo-no-content').click(function(e) {
				e.preventDefault();

				var $demo	= $('.cenote-demo');

				if ($demo.hasClass('cenote-demo-installed') || $demo.hasClass('cenote-demo-installing') || $demo.hasClass('cenote-demo-disabled') || $(this).hasClass('button-disabled')) {
					return;
				}

				var c	= confirm(adiL10n.install_demo_confirm);

				if (c) {
					base.installnocontent($(this).data('demo-id'));
				}
			});
		};

		base.install	= function(id, data) {
			var $wrapper	= $('.cenote-demo-' + id);

			$wrapper.addClass('cenote-demo-installing');
			$wrapper.find('.cenote-button-install-demo').addClass('button-disabled');
			$wrapper.find('.cenote-button-install-demo-no-content').addClass('button-disabled');
			$('.cenote-demo').not($wrapper).addClass('cenote-demo-disabled');

			progress_bar.progress_bar_wrapper_element	= $wrapper.find('.cenote-demo-progress-bar-wrapper');
			progress_bar.progress_bar_element			= $wrapper.find('.cenote-demo-progress-bar');
			progress_bar.show();
			progress_bar.change(0);

			base.install_step(id, {cenote_demo_importer_action: 'install'});
		};

		base.installnocontent	= function(id, data) {
			var $wrapper	= $('.cenote-demo-' + id);

			$wrapper.addClass('cenote-demo-installing');
			$wrapper.find('.cenote-button-install-demo').addClass('button-disabled');
			$wrapper.find('.cenote-button-install-demo-no-content').addClass('button-disabled');
			$('.cenote-demo').not($wrapper).addClass('cenote-demo-disabled');

			progress_bar.progress_bar_wrapper_element	= $wrapper.find('.cenote-demo-progress-bar-wrapper');
			progress_bar.progress_bar_element			= $wrapper.find('.cenote-demo-progress-bar');
			progress_bar.show();
			progress_bar.change(0);

			base.install_step(id, {cenote_demo_importer_action: 'install'});
		};

		base.install_finish	= function(id, error) {
			var $wrapper	= $('.cenote-demo-' + id);

			$wrapper.removeClass('cenote-demo-installing');

			if (!error) {
				// finish
				progress_bar.change(100);

				setTimeout(function() {
					progress_bar.hide();
					progress_bar.reset();

					$wrapper.removeClass('cenote-demo-installing').addClass('cenote-demo-installed');
					$wrapper.find('.cenote-button-install-demo').removeClass('button-disabled');
					$wrapper.find('.cenote-button-install-demo-no-content').removeClass('button-disabled');
				}, 500);
			} else {
				progress_bar.hide();
				progress_bar.reset();
				$wrapper.find('.cenote-button-install-demo').removeClass('button-disabled');
				$wrapper.find('.cenote-button-install-demo-no-content').removeClass('button-disabled');
			}
		};

		base.install_step	= function(id, data) {
			var $wrapper	= $('.cenote-demo-' + id);

			data	= data || {};

			if (!data.action) {
				data.action		= 'cenote_demo_importer_action';
			}

			if (!data.demo_id) {
				data.demo_id	= id;
			}

			$.ajax({
				type:		'POST',
				url:		ajaxurl,
				cache:		false,
				dataType:	'text',
				data:		data,
				success:	function(content) {
					if (!content || content == '0') {
						base.install_finish(id, true);
						alert(adiL10n.install_demo_error);
					} else if (content == '1') {
						base.install_finish(id);
					} else {
						var response	= JSON.parse(content);

						progress_bar.change(response.progress);

						var request		= {
							cenote_demo_importer_action:	response.next_action
						};

						if (response.next_action == 'post' && response.pni) {
							request.pni	= response.pni;
						}

						base.install_step(id, request);
					}
				},
				error:		function() {
					base.install_finish(id, true);
					alert(adiL10n.install_demo_error);
				}
			});
		};

		base.uninstall	= function(id) {
			var $wrapper	= $('.cenote-demo-' + id);

			$wrapper.addClass('cenote-demo-uninstalling').removeClass('cenote-demo-installed');

			progress_bar.progress_bar_wrapper_element	= $wrapper.find('.cenote-demo-progress-bar-wrapper');
			progress_bar.progress_bar_element			= $wrapper.find('.cenote-demo-progress-bar');
			progress_bar.show();
			progress_bar.change(2);
			progress_bar.timer_change(98);

			$.ajax({
				type:		'POST',
				url:		ajaxurl,
				cache:		false,
				dataType:	'text',
				data:		{
					action:						'cenote_demo_importer_action',
					cenote_demo_importer_action:	'uninstall',
					demo_id:					id
				},
				success:	function(content) {
					progress_bar.change(100);

					setTimeout(function() {
						progress_bar.hide();
						progress_bar.reset();

						$wrapper.removeClass('cenote-demo-uninstalling');
						$('.cenote-demo').removeClass('cenote-demo-disabled');
					})
				},
				error:	function() {
					$wrapper.removeClass('cenote-demo-uninstalling');
					alert(adiL10n.uninstall_demo_error);
				}
			});
		};

		base.init();
	};

	// ======================================================
	// RELOAD FRAMEWORK PLUGINS
	// ------------------------------------------------------
	$.fn.CENOTEFRAMEWORK_RELOAD_PLUGINS = function () {
		return this.each(function () {
			$('.chosen', this).CENOTEFRAMEWORK_CHOSEN();
			$('.cenote-field-image-select', this).CENOTEFRAMEWORK_IMAGE_SELECTOR();
			$('.cenote-field-image', this).CENOTEFRAMEWORK_IMAGE_UPLOADER();
			$('.cenote-field-gallery', this).CENOTEFRAMEWORK_IMAGE_GALLERY();
			$('.cenote-field-sorter', this).CENOTEFRAMEWORK_SORTER();
			$('.cenote-field-upload', this).CENOTEFRAMEWORK_UPLOADER();
			$('.cenote-field-typography', this).CENOTEFRAMEWORK_TYPOGRAPHY();
			$('.cenote-field-typography_advance', this).CENOTEFRAMEWORK_TYPOGRAPHY();
			$('.cenote-field-typekit', this).CENOTEFRAMEWORK_TYPOGRAPHY();
			//$('.cenote-field-color-picker', this).CENOTEFRAMEWORK_COLORPICKER();
			$('.cenote-field-color-picker', this).CENOTEFRAMEWORK_SPECTRUM();
			$('.cenote-help', this).CENOTEFRAMEWORK_TOOLTIP();
			$('.cenote-field-slider', this).CENOTEFRAMEWORK_SLIDER();
			$('.cenote-field-switcher', this).CENOTEFRAMEWORK_SWICHER();
			$('.cenote-customize-complex').CENOTEFRAMEWORK_CUSTOMIZER_LISTEN(false);
			$('.cenote-field-number').CENOTEFRAMEWORK_NUMBER();
			$('.cenote-datepicker').CENOTEFRAMEWORK_DATEPICKER();
			$('.cenote-field-image-dropdown').CENOTEFRAMEWORK_IMAGE_DROPDOWN();
		});
	};

	// ======================================================
	// JQUERY DOCUMENT READY
	// ------------------------------------------------------
	$(document).ready(function () {
		$('.cenote-framework').CENOTEFRAMEWORK_TAB_NAVIGATION();
		$('.cenote-reset-confirm, .cenote-import-backup').CENOTEFRAMEWORK_CONFIRM();
		$('.cenote-content, .wp-customizer, .widget-content, .cenote-taxonomy, #menu-to-edit').CENOTEFRAMEWORK_DEPENDENCY();
		$('.cenote-field-group').CENOTEFRAMEWORK_GROUP();
		$('.cenote-save').CENOTEFRAMEWORK_SAVE();
		$('.cenote-taxonomy').CENOTEFRAMEWORK_TAXONOMY();
		$('.cenote-framework, #widgets-right').CENOTEFRAMEWORK_RELOAD_PLUGINS();
		$('.cenote-field-image', this).CENOTEFRAMEWORK_IMAGE_UPLOADER();
		$.CENOTEFRAMEWORK.ICONS_MANAGER();
		$.CENOTEFRAMEWORK.SHORTCODE_MANAGER();
		$.CENOTEFRAMEWORK.WIDGET_RELOAD_PLUGINS();
		$.CENOTEFRAMEWORK.CUSTOM_SIDEBARS();
		$.CENOTEFRAMEWORK.DEMO_IMPORTER();
	});
})(jQuery, window, document);
