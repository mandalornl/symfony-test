import $ from 'jquery';
import 'bootstrap/js/dist/util';
import 'bootstrap/js/dist/collapse';
import 'bootstrap/js/dist/tab';

import * as datePicker from './assets/datepicker';
import * as select2 from './assets/select2';
import * as autoComplete from './assets/autocomplete';
import * as wysiwyg from './assets/wysiwyg';
import * as parts from './assets/parts';
import * as doNotLeave from './util/donotleave';

$(() =>
{
	const $form = $('.form-edit');
	if (!$form.length)
	{
		return;
	}

	$form.on('change.donotleave', 'select', function()
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

	$form.on('change', '.translation-list .tab-list > select', function()
	{
		$(this.value).tab('show');
	});

	$('.listing-edit').on('click', 'button[data-action="save"]', function()
	{
		const $this = $(this);
		$this.prop('disabled', true);

		doNotLeave.disable();

		$form.submit();
	});

	datePicker.init();
	select2.init();
	autoComplete.init();
	wysiwyg.init();
	parts.init();
});