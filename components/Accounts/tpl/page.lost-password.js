<?php
	self::jQuery();
	self::addScript('<script src="/script/forge/forge.js" type="text/javascript"></script>');
	self::addStyle('<link href="/css/toast.css" rel="stylesheet"></script>');
?>

function recover() {
	$('#accountsRecover').attr('disabled', 'disabled');

	$.ajax({
		url: '/xml/Accounts/sendLostPasswordLink',
		type: 'post',
		data: {
			email: $('#lost_password_email').val()
		},
		error: function() {
			$('#accountsRecover').removeAttr('disabled');
			forge.toast('We could not find an account for the given email address!', 'bad');
		},
		success: function() {
			window.location = '/user/recover-password';
		}
	});

	return false;
}