/*
 * forge.js
 * Copyright 2019 Mattias Lindholm
 *
 * This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
 * to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
 */

define(['jquery'], function($) {
	return {
		json: function(addon, controller, obj) {
			function def(key, val) {
				if (typeof(obj[key]) === 'undefined')
					obj[key] = val;
			}

			def('data', {});
			def('dataType', 'json');
			def('method', 'POST');

			obj.data.forge = {
				controller: addon+'\\'+controller
			};

			$.ajax(window.location.href, obj);
		}
	};
});