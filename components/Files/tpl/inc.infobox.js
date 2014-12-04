/**
 * inc.infobox.js
 * Copyright 2014 Mattias Lindholm
 *
 * This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
 * to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
 */

f.ready(function() {
	var counter = f('#files-size');
	
	f.json({
		addon: 'Files',
		method: 'getTotalFileSize',
		success: function(response) {
			counter.text(response);
		},
		error: function(response) {
			counter.text('Error!');
		}
	});
});