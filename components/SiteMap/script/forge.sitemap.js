/*
 * forge.sitemap.js
 * Copyright 2019 Mattias Lindholm
 *
 * This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
 * to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
 */

define(['jquery', 'forge'], function($, forge) {
	return {
		delete: function(id, complete, error) {
			forge.json('SiteMap', 'Delete', {
				complete: complete,
				data: {'page[id]': id},
				error: error
			});
		},
		order: function(ids, complete, error) {
			forge.json('SiteMap', 'Organize', {
				complete: complete,
				data: {menu:ids},
				error: error});
		}
	};
});