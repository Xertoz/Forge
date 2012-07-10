<?php
	self::jQuery();
	self::addScript('<script src="/script/forge/forge.js" type="text/javascript"></script>');
	self::addStyle('<link href="/css/toast.css" rel="stylesheet"></script>');
?>

function recover() {
	$('#accountsRecover').attr('disabled', 'disabled');

	$.ajax({
		url: '/xml/Accounts/recoverPassword',
		type: 'post',
		data: {
			key: '<?php echo $key; ?>',
			passwd1: $('#recover_passwd1').val(),
			passwd2: $('#recover_passwd2').val()
		},
		error: function() {
			$('#accountsRecover').removeAttr('disabled');
			forge.toast('We could not set the new password! Are you sure both input fields are equal?', 'bad');
		},
		success: function() {
			window.location = '/user/login';
		}
	});

	return false;
}