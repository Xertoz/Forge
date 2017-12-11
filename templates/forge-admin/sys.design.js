/**
 * f.js
 * Copyright 2016 Mattias Lindholm
 *
 * This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
 * to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
 */

var menu = {
	T_ANIM: 250,
	
	is: f.cookie('admin_menu'),
	title: null,
	
	animate: function(show) {
		f('header > h1, body > nav').animate({'width': show ? '230px' : '50px'}, menu.T_ANIM);
		f('#admin-content').animate({'padding-left': show ? '246px' : '66px'}, menu.T_ANIM);
	},
	
	hide: function() {
        var o = f('header > h1 > a');
        menu.title = o.text();
        o.html('<b>'+menu.title.substr(0, 1)+'</b>'+menu.title.split(' ')[1].substr(0, 1));
		
		menu.animate(false);
	},
	
	init: function() {
		if ((/^false$/).test(menu.is)) {
			var mTimer = menu.T_ANIM;
			menu.T_ANIM = 0;
			menu.hide();
			menu.T_ANIM = mTimer;
		}
	},
	
	show: function() {
        var s = menu.title.split(' ');
        f('header > h1 > a').html('<b>'+s[0]+'</b> '+s[1]);
		
		menu.animate(true);
	},
	
	toggle: function() {
		menu.is = !(/^true$/).test(menu.is);
		f.cookie('admin_menu', menu.is);
		
		menu.is ? menu.show() : menu.hide();
	}
};