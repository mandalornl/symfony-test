import $ from 'jquery';

import autoComplete from 'Duo/AdminBundle/Resources/webpack/js/lib/autocomplete';
import wysiwyg from 'Duo/AdminBundle/Resources/webpack/js/lib/wysiwyg';
import doNotLeave from 'Duo/AdminBundle/Resources/webpack/js/util/donotleave';

import 'Duo/AdminBundle/Resources/webpack/js/modal/new-draft';

import 'Duo/AdminBundle/Resources/webpack/js/widget/collection';
import 'Duo/AdminBundle/Resources/webpack/js/widget/file';
import 'Duo/PartBundle/Resources/webpack/js/widget/parts';
import media from 'Duo/MediaBundle/Resources/webpack/js/widget/media';
import imageCrop from 'Duo/MediaBundle/Resources/webpack/js/widget/image-crop';

$(() =>
{
	const $listing = $('.listing-create');

	if (!$listing.length)
	{
		return;
	}

	const $form = $listing.find('.listing-form');

	// show warning when user closes or reloads page
	$form.on('change.donotleave', 'select:not(.locale-selector)', function()
	{
		doNotLeave.enable();

		$form.off('.donotleave');
	});

	$form.on({
		'keydown.donotleave': function()
		{
			$(this).data('donotleave.keydown', this.value);
		},

		'keyup.donotleave': function()
		{
			const $this = $(this);

			if ($this.data('donotleave.keydown') !== this.value)
			{
				doNotLeave.enable();

				$form.off('.donotleave').removeData('donotleave.keydown');
			}
		}
	}, 'input, textarea');

	// select proper translation tab
	$form.on('change', '.translation-list .tab-list > .locale-selector', function()
	{
		$(this.value).tab('show');
	});

	/**
	 * On add item
	 */
	const onAddItem = function()
	{
		const $item = $(this);

		[
			autoComplete,
			wysiwyg,
			media,
			imageCrop // be sure to init image crop after media widget
		].forEach(plugin =>
		{
			plugin.init($item.find(plugin.SELECTOR));
		});

		doNotLeave.enable();
	};

	/**
	 * On remove item
	 */
	const onRemoveItem = function()
	{
		const $item = $(this);

		[
			autoComplete,
			wysiwyg,
			imageCrop, // be sure to destroy image crop before media widget
			media
		].forEach(plugin =>
		{
			plugin.destroy($item.find(plugin.SELECTOR));
		});

		doNotLeave.enable();
	};

	// init widgets inside part widget
	$form.on('duo.event.part.addItem', '.widget-parts .sortable-item', onAddItem);

	// destroy widgets inside part widget
	$form.on('duo.event.part.removeItem', '.widget-parts .sortable-item', onRemoveItem);

	// init widgets inside collection widget
	$form.on('duo.event.collection.addItem', '.widget-collection .collection-item', onAddItem);

	// destroy widgets inside collection widget
	$form.on('duo.event.collection.removeItem', '.widget-collection .collection-item', onRemoveItem);

	// prevent window unload after sortable list update
	$listing.find('.widget-parts .sortable-list').on('sortupdate', () =>
	{
		doNotLeave.enable();
	});

	// handle form submit
	$listing.on('click', 'button[data-action="save"]', function()
	{
		const $this = $(this);
		$this.prop('disabled', true);

		doNotLeave.disable();

		$form.submit();
	});

	const $invalid = $form.find('.is-invalid');

	if ($invalid.length)
	{
		// open any tabs with first invalid field
		let $tabPane = $invalid.first().closest('.tab-pane:not(:visible)');

		do
		{
			const $anchor = $(`a[href="#${$tabPane.attr('id')}"]`);
			$anchor.tab('show');

			const $option = $(`option[value="#${$anchor.attr('id')}"]`);
			$option.closest('select').find('option').not($option).prop('selected', false);
			$option.prop('selected', true);

			$tabPane = $tabPane.closest('.tab-content').closest('.tab-pane');
		} while ($tabPane.length);

		// focus first invalid field
		window.setTimeout(() => $invalid.first().focus(), 250);

		// add badge to tab
		$form.find('.tab-pane').each(function()
		{
			const $this = $(this);

			const $invalid = $this.find('.is-invalid');

			if (!$invalid.length)
			{
				return;
			}

			const $anchor = $(`a[href="#${$this.attr('id')}"]`);
			$anchor.append(`<span class="badge badge-danger">${$invalid.length}</span>`);
		});
	}
});
