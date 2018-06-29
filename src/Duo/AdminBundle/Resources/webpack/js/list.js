import $ from 'jquery';
import 'bootstrap/js/dist/util';
import 'bootstrap/js/dist/collapse';
import 'bootstrap/js/dist/dropdown';
import 'bootstrap/js/dist/alert';

import confirm from './util/confirm';
import datePicker from './lib/datepicker';
import select2 from './lib/select2';

$(() =>
{
	const $form = $('.listing-form');

	if (!$form.length)
	{
		return;
	}

	const $list = $form.find('.listing-table');

	$list.on('click', 'tbody tr[data-url]', function(e)
	{
		if ($(e.target).closest(':input, a, .custom-control').length)
		{
			return;
		}

		location.href = $(this).closest('tr').data('url');
	});

	$list.on('click', '[data-modal="delete"]', async function(e)
	{
		e.preventDefault();

		const $this = $(this);

		await confirm({
			selector: '#modal_confirm_delete',
			title: $this.data('title'),
			body: $this.data('body')
		});

		location.href = $this.attr('href');
	});

	$('.listing-index').on('click', '[data-modal="multi-delete"]', async function(e)
	{
		e.preventDefault();

		const $this = $(this);

		await confirm({
			title: $this.data('title'),
			body: $this.data('body')
		});

		$form.attr('action', $this.attr('href')).submit();
	});

	datePicker();
	select2();
});