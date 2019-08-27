function display(id) {
	$('.plugin-form.visible').removeClass('visible');
	$('#'+id).addClass('visible');
}

function mkuri() {
	document.getElementById('page-url').value = document.getElementById('page-title').value.replace(/\W/g, '-').toLowerCase();
}