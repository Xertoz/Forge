/**
 * sys.design.js
 * Copyright 2019 Mattias Lindholm
 *
 * This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
 * to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
 */
//require(['jquery', 'cookie'], function($, cookie) {
 	//require(['/templates/forge-admin/script/bootstrap.min.js'], function() {
 		//require(['/templates/forge-admin/script/adminlte.min.js']);
 	//});
 //});
require(['jquery', 'cookie'], function($, cookie) {
	$('.sidebar-toggle').click(function(event) {
		cookie.set('admin_menu', $('body').hasClass('sidebar-collapse'));
	});
});