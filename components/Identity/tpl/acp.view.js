// Hook our click listeners when we finished loading
f.ready(function() {
	// When clicking an accepted permission, deny it!
	f('#identity-permissions .accept').click(function() {
		var deny = this.next();
		var input = this.prev();
		input.value(0);
		this.hide();
		deny.show();
	});
	
	// When clicking a denied permission, accept it!
	f('#identity-permissions .deny').click(function() {
		var accept = this.prev();
		var input = accept.prev();
		input.value(1);
		accept.show();
		this.hide();
	});
});