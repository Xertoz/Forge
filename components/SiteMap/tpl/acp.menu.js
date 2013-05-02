function organize() {
	var table = document.getElementById('forge-sitemap-menu').childNodes[1];
	var form = document.sitemap_menu;
	form.innerHTML = '';
	var ctrl = document.createElement('input');
	ctrl.type = 'hidden';
	ctrl.name = 'forge[controller]';
	ctrl.value = 'SiteMap\\Organize';
	form.appendChild(ctrl);
	var parent = document.createElement('input');
	parent.type = 'hidden';
	parent.name = 'parent';
	parent.value = '<?php echo !empty($_GET['parent']) ? (int)$_GET['parent'] : 0; ?>';
	form.appendChild(parent);
	
	var inputs = document.getElementsByClassName('forge-sitemap-menu-row');
	for (var i=0;i<inputs.length;++i) {
		var input = document.createElement('input');
		input.type = 'hidden';
		input.name = 'menu[]';
		input.value = inputs[i].value;
		form.appendChild(input);
	}
	
	form.submit();
}