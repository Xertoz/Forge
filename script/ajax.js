/**
 * ajax.js
 * Copyright 2012 Mattias Lindholm
 *
 * This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
 * to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
 */

var forge = typeof(forge) != 'object' ? {} : forge;

/**
 * Perform an AJAX request to the Forge XML API
 * @param params An object with various parameters set
 * @return Boolean
 */
forge.ajax = function(params) {
	// The paramers are required to be passed by an object
	if (typeof(params) != 'object')
		return false;

	// Require an addon and method for the request
	if (typeof(params.addon) != 'string' || typeof(params.method) != 'string')
		return false;

	// There should be an object with data to send
	params.data = typeof(params.data) != 'object' ? {} : params.data;

	// There should be a request method
	params.type = typeof(params.type) != 'string' ? 'GET' : params.type;

	// Any callback functions?
	params.error = typeof(params.error) != 'function' ? function() {} : params.error;
	params.success = typeof(params.success) != 'function' ? function() {} : params.success;

	// Fabricate the URL & data object the request is aimed at
	var url = '/xml/'+params.addon+'/'+params.method;
	var data = null;

	// Create a request object
	var xhr = XMLHttpRequest();
	xhr.addEventListener('loadend', function(evt) {
		if (xhr.status == 200)
			params.success(xhr);
		else {
			if (xhr.status == 400) {
				var message = xhr.responseXML.getElementsByTagName('message')[0].childNodes[0].nodeValue;
				params.error(message, xhr);
			}
			else
				params.error('An internal server error occured. Please contact the administrator!', xhr);
		}
	}, false);
	xhr.open(params.type, url);

	// Append the request URL if we use GET for the request
	if (params.type == 'GET') {
		var get = '';
		for (var key in params.data)
			get += encodeURIComponent(key)+'='+encodeURIComponent(params.data[key])+'&';
		var append = get.length > 0 ? '?'+get.substr(0, get.length-1) : get;
		url += append;
	}
	// Set the message body if we use POST for the request
	else if (params.type == 'POST') {
		var post = '';
		for (var key in params.data)
			post += encodeURIComponent(key)+'='+encodeURIComponent(params.data[key])+'&';
		data = post.length > 0 ? post.substr(0, post.length-1) : post;
		xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
	}
	// If neither GET nor POST was requested, it's an illegal operation
	else
		return false;

	// Send the request
	xhr.send(data);

	return true;
};