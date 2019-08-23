/**
 * inc.table.js
 * Copyright 2014 Mattias Lindholm
 *
 * This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
 * to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
 */
/*
f.ready(function() {
	var source = null;
	
	f('table.list.sortable').sortable({
		drop: function(event) {
			var offset = (window.pageYOffset+event.clientY)-this.offset().top;
			var target = offset/this.height() < 0.5 ? this : this.next();
			
			if (target !== undefined)
				this.parent().before(source, target);
			else
				this.parent().append(source);
			
			this.removeClass('forge-dragsort-top', 'forge-dragsort-bottom');
		},
		end: function() {
			this.css('opacity', 1);
		},
		enter: function() {
			this.addClass('forge-dragsort-over');
		},
		leave: function() {
			this.removeClass('forge-dragsort-over', 'forge-dragsort-top', 'forge-dragsort-bottom');
		},
		over: function(event) {
			event.preventDefault();

			var offset = (window.pageYOffset+event.clientY)-this.offset().top;
			var cls = offset/this.height() < 0.5 ? 'forge-dragsort-top' : 'forge-dragsort-bottom';
			this.removeClass('forge-dragsort-top', 'forge-dragsort-bottom');
			this.addClass(cls);

			return false;
		},
		start: function(event) {
			source = event.target;
			event.dataTransfer.setData('text/html', this[0].innerHTML);
			this.css('opacity', .25);
		}
	});
});*/