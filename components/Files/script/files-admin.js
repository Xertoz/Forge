define(['jquery'], function($) {
	return {
		runMaintenance: function() {
			let tasks = [
				function() {}
			];
			
			let status = $('#mnt-total .status');
			let total = $('#mnt-total .bar');
			let current = $('#mnt-current .bar');
			
			for (let i=0,n=tasks.length;i<n;++i) {
				total.width(Math.round(i/n*100)+'%');
				tasks[i]();
			}
			
			total.width('100%');
		}
	};
});