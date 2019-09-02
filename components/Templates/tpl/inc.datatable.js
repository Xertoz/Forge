/*
 * inc.datatable.js
 * Copyright 2019 Mattias Lindholm
 *
 * This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
 * to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
 */

require(['jquery', 'datatables.bootstrap', 'datatables.rowReorder'], function($, datatables) {
	$('table.table').each(function(i, table) {
		let dt = $(table).DataTable({
			ordering: table.hasAttribute('data-sortable'),
			paging: table.hasAttribute('data-paging'),
			rowReorder: table.hasAttribute('data-draggable'),
			searching: table.hasAttribute('data-searchable')
		});

		dt.on('row-reorder', function(e, details, edit) {
			if (table.hasAttribute('data-onrowreorder')) {
				let fn = window[table.getAttribute('data-onrowreorder')];

				if (typeof(fn) === 'function')
					fn.apply(null, [e, details, edit]);
			}
		});
	});
});