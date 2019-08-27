/*function rename(original) {
	var target = prompt('Rename file to:', original);
	if (!target)
		return;
	
	document.getElementById('renameSource').value = f.get('path')+'/'+original;
	document.getElementById('renameTarget').value = target;
	document.rename.submit();
}*/

function trash(file) {
	if (!confirm('Are you sure you want to delete the file?'))
		return;
	
	document.getElementById('trashFile').value = file;
	document.trash.submit();
}
/*
$(document).ready(function() {
	let busy = false;
	
	function send(files) {
		if (busy)
			return;
		
		let data = new FormData($('#upload-form')[0]);
		
		if (files) {
			data.delete('files[]');
			$.each(files, function(i, file) {
				data.append('files[]', file);
			});
			$('#upload-progress span').text(files.length);
		}
		else
			$('#upload-progress span').text('1');
		
		busy = true;
		$('#upload, #upload-fail').hide();
		$('#upload-progress').show();
		let bar = $('#upload-progress .bar');
		bar.css('width', '0%');
		$.ajax({
			url: '/json/Files/upload',
			type: 'POST',
			data: data,
			processData: false,
			contentType: false,
			xhr: function() {
				let xhr = $.ajaxSettings.xhr();
				
				xhr.upload.addEventListener('progress', function(event) {
					bar.css('width', Math.round(event.loaded/event.total*100)+'%');
				});
				
				return xhr;
			}
		}).done(function() {
			$('#upload-progress').hide();
			$('#upload-success').show();
			window.location.reload();
		}).fail(function() {
			busy = false;
			$('#upload-progress').hide();
			$('#upload-fail').show();
		});
	}
	
	let upload = $('#upload, #upload-fail');
	upload.on('click', function() {$('#upload-form input[type="file"]').click();});
	upload.on('dragenter', function() {upload.addClass('hover');});
	upload.on('dragleave drop', function() {upload.removeClass('hover');});
	upload.on('dragover', function(event) {event.preventDefault();});
	upload.on('drop', function(event) {
		event.preventDefault();
		send(event.originalEvent.dataTransfer.files);
	});
	$('#upload-form input[type="file"]').change(function(event) {
		send();
	});
});*/