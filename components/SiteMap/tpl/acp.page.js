function display(id) {
	var list = document.getElementsByClassName('plugin-form');
	
	for (var i=0;i<list.length;++i)
		list[i].style.display = 'none';
	
	document.getElementById(id).style.display = 'block';
}

function mkuri() {
	document.getElementById('page-url').value = document.getElementById('page-title').value.replace(/\W/g, '-').toLowerCase();
}