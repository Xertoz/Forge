function RoxyFileBrowser(field_name, url, type, win) {
	var roxyFileman = '/script/fileman/index.html';
	if (roxyFileman.indexOf("?") < 0)
		roxyFileman += "?type=" + type;
	else
		roxyFileman += "&type=" + type;
	roxyFileman += '&input=' + field_name + '&value=' + document.getElementById(field_name).value;
	tinyMCE.activeEditor.windowManager.open(
		{
			file: roxyFileman,
			title: 'Roxy Fileman',
			width: 850,
			height: 650,
			resizable: "yes",
			plugins: "media",
			inline: "yes",
			close_previous: "no"
		}, {
			window: win,
			input: field_name
		}
	);
	return false;
}

tinymce.init({
	file_browser_callback: RoxyFileBrowser,
	plugins: 'advlist anchor autolink autosave charmap code colorpicker \
		directionality emoticons fullscreen hr image insertdatetime link \
		lists media paste searchreplace spellchecker table textcolor \
		visualblocks visualchars wordcount',
	selector: 'textarea.editable'
});