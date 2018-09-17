import $ from 'jquery';
import {get} from 'duo/AdminBundle/Resources/webpack/js/util/api';
import * as loader from 'duo/AdminBundle/Resources/webpack/js/util/loader';

export default ($ =>
{
	const NAME = 'media';
	const SELECTOR = `.${NAME}-widget`;

	const modals = {};

	const $window = $(window);

	/**
	 * Get jQuery
	 *
	 * @param {string|HTMLElement|jQuery} selector
	 *
	 * @returns {jQuery}
	 */
	const _$ = selector => (selector instanceof jQuery || 'jquery' in Object(selector)) ? selector : $(selector);

	const methods = {

		SELECTOR: SELECTOR,

		/**
		 * Initialize
		 *
		 * @param {string|HTMLElement|jQuery} selector
		 */
		init: selector =>
		{
			_$(selector).each(function()
			{
				const $this = $(this);

				if ($this.data(`init.${NAME}`))
				{
					return;
				}

				$this.data(`init.${NAME}`, true);

				const $media = $this.find('.media-input input[type=text]');
				const $caption = $this.find('.caption');
				const $preview = $this.find('.image-preview');

				const $btnClear = $this.find('button[data-action="clear"]');

				$this.on(`click.${NAME}`, 'button[data-action="select"]', async function(e)
				{
					e.preventDefault();

					const url = $(this).data('url');

					let $modal = modals[url];

					if (!$modal)
					{
						const html = await get(url);

						$modal = $(html);
						$modal.appendTo('body');

						modals[url] = $modal;
					}

					$modal.modal('show');

					$media.off('duo.event.iframe.selectItem').on('duo.event.iframe.selectItem', (e, data) =>
					{
						if (data.eventType !== 'media')
						{
							return;
						}

						// check whether or not mime-type is correct
						if ($this.data('mediaType') === 'image' && data.mimeType.indexOf('image/') !== 0 ||
							$this.data('mediaType') !== 'image' && data.mimeType.indexOf('image/') === 0)
						{
							console.error(`Illegal mime-type: '${data.mimeType}'`);

							return;
						}

						// show loader when (pre)loading takes to long
						loader.show();

						// dispatch clear event before updating fields
						$btnClear.trigger('click');

						/**
						 * Select item
						 */
						const selectItem = () =>
						{
							$caption.text(data.name);

							$media.val(data.id);
							$media.trigger('duo.event.media.selectItem');

							// show elements
							$this.removeClass('empty');

							// close modal after event
							$modal.find('[data-dismiss]').trigger('click');

							loader.hide();
						};

						// pre load image before selecting item
						if (data.src)
						{
							const $image = $(`<img src="${data.src}" srcset="${data.srcset}" sizes="(min-width: 576px) 66.66667vw, (min-width: 768px) 50vw, 100vw" alt="${data.name}" />`);

							$image.one('load', () =>
							{
								$preview.append($image);

								selectItem();
							});

							return;
						}

						selectItem();
					});
				});

				$this.on(`click.${NAME}`, 'button[data-action="clear"]', function(e)
				{
					e.preventDefault();

					$caption.text(null);

					$media.val(null);
					$media.trigger('duo.event.media.clearItem');

					// empty preview after event
					$preview.empty();

					// hide elements
					$this.addClass('empty');
				});
			});
		},

		/**
		 * Destroy
		 *
		 * @param {string|HTMLElement|jQuery} selector
		 */
		destroy: selector =>
		{
			const $selector = _$(selector);

			$selector.removeData(`init.${NAME}`).off(`click.${NAME}`);
			$selector.find('button[data-action="select"]').each(function()
			{
				const url = $(this).data('url');

				if (!modals[url])
				{
					return;
				}

				modals[url].remove();

				delete modals[url];
			});
		}
	};

	$window.on(`load.${NAME}`, () => methods.init(SELECTOR));

	return methods;
})($);