/*
 * acp.page.js
 * Copyright 2019 Mattias Lindholm
 *
 * This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
 * to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
 */

require(['jquery', 'forge.sitemap', 'domReady!'], function($, sitemap) {
	$('#page-type').change(function(event) {
		$('.plugin-form').addClass('hidden');
		$('#'+event.target.value.replace(/\\/g, '_')).removeClass('hidden');
	});

	$('#page-title, #page-parent').keyup(function() {
		$('#page-url').val($('#page-title').val().replace(/\W/g, '-').toLowerCase());
	});

	$('#btn-delete').click(function(event) {
		if (confirm(event.target.getAttribute('data-locale')))
			sitemap.delete(event.target.getAttribute('data-id'), function() {
				window.location = event.target.getAttribute('data-success');
			}, function() {
				alert(event.target.getAttribute('data-error'));
			});
	});
});