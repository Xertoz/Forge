// Hook our click listeners when we finished loading
$(document).ready(function() {
	$('#identity-permissions .accept').click(function() {
		$(this).hide().next().show();
		$(this).prev().attr('value', 0);
	});
	
	$('#identity-permissions .deny').click(function() {
		$(this).hide().prev().show();
		$(this).prev().prev().attr('value', 1);
	});
});