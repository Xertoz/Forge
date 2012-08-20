function rename(original) {
	var target = prompt("<?php echo _('Rename file to:'); ?>", original);
	if (!target)
		return;
	
	document.getElementById('renameSource').value = original;
	document.getElementById('renameTarget').value = <?php if (isset($_GET['path'])) echo $_GET['path'].'/'; ?>target;
	document.rename.submit();
}

function trash(file) {
	if (!confirm("<?php echo _('Are you sure you want to delete the file?'); ?>"))
		return;
	
	document.getElementById('trashFile').value = <?php if (isset($_GET['path'])) echo $_GET['path'].'/'; ?>file;
	document.trash.submit();
}