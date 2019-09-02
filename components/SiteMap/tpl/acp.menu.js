function onRowReorder() {
	let overlay = $('#forge-sitemap-menu_wrapper').parent().next();
	overlay.removeClass('hidden');

	let order = [];
	$('#forge-sitemap-menu > tbody > tr').each(function(i, tr) {
		order.push(parseInt(tr.getAttribute('data-forge-id')));
	});

	require('forge.sitemap').order(order, function() {
		overlay.addClass('hidden');
	}, function(xhr, text, error) {
		let warn = $('#forge-sitemap-menu_wrapper').prev();
		warn.removeClass('hidden');
		if (typeof(xhr.responseJSON) === 'object')
			warn.append('<p>'+xhr.responseJSON.message+'</p>');
		else
			warn.append('<p>'+text+'</p><p>'+error+'</p>');
		overlay.addClass('hidden');
	});
}

requirejs(['forge.sitemap']);