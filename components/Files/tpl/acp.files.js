function rename(original) {
	var target = prompt('Rename file to:', original);
	if (!target)
		return;
	
	document.getElementById('renameSource').value = f.get('path')+'/'+original;
	document.getElementById('renameTarget').value = target;
	document.rename.submit();
}

function trash(file) {
	if (!confirm('Are you sure you want to delete the file?'))
		return;
	
	document.getElementById('trashFile').value = f.get('path')+'/'+file;
	document.trash.submit();
}