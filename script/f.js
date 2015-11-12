/**
 * f.js
 * Copyright 2012-2014 Mattias Lindholm
 *
 * This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
 * to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
 */

/**
 * Forge's JavaScript function/object
 * @type Function|f_L13.f
 */
var f = (function() {
	/**
	 * Make a function which runs a callback upon an event
	 * @param {Function} callback
	 * @returns {void}
	 */
	var call = function(callback) {
		return function(event) {
			callback.call(f(this), event);
		};
	};
	
	/**
	 * Void function which does... nothing
	 * @returns {void}
	 */
	var voidFunction = function() {};
	
	/**
	 * The local Forge object with targetted elements
	 * @param {Window|Element|String} selector
	 * @param {f_L14.o|undefined} context
	 * @returns {f_L14.f.o}
	 */
	var o = function(selector, context) {
		this.elements = [];

		// TODO: Improve array getter?
		if (selector instanceof Window || selector instanceof Element) {
			this.elements.push(selector);
			this[0] = this.elements[0];
		}
		else if (typeof(selector) === 'string') {
			var query = function(context) {
				var elements = context.querySelectorAll(selector);

				for (var i=0;i<elements.length;++i) {
					this.elements.push(elements[i]);
					this[i] = this.elements[i];
				}
			};

			if (context === undefined)
				query.call(this, document);
			else
				context.elements.forEach(function(element) {
					query.call(this, element);
				}, this);
		}
	};

	/**
	 * Add a CSS class to the elements
	 * @returns {void}
	 */
	o.prototype.addClass = function() {
		var classList = arguments;

		this.elements.forEach(function(element) {
			for (var i=0;i<classList.length;++i)
				element.classList.add(classList[i]);
		});
	};

	/**
	 * Append content to the elements
	 * @param {Element|String} content
	 * @returns {void}
	 */
	o.prototype.append = function(content) {
		this.elements.forEach(function(element) {
			if (content instanceof Element)
				element.appendChild(content);
			else if (typeof(content === 'string'))
				element.appendChild(document.createTextNode(content));
		});
	};
	
	/**
	 * Get (first element) or set (all elements) the value of an attribute
	 * @param {String} name
	 * @param {undefined|String} value
	 * @returns {String|undefined}
	 */
	o.prototype.attr = function(name, value) {
		if (typeof(value) === 'undefined')
			return this.elements[0].getAttribute(name);
		this.elements.forEach(function(element) {
			element.setAttribute(name, value);
		});
	};

	/**
	 * Insert an element before another
	 * @param {Element|f_L14.f.o} content
	 * @param {Element|f_L14.f.o} target
	 * @returns {void}
	 */
	o.prototype.before = function(content, target) {
		if (!(content instanceof Node))
			content = content[0];

		if (!(target instanceof Node))
			target = target[0];

		this.elements.forEach(function(element) {
			element.insertBefore(content, target);
		});
	};
	
	/**
	 * Listen to input changes
	 * @param {Function} callback
	 * @returns {void}
	 */
	o.prototype.change = function(callback) {
		this.elements.forEach(function(element) {
			element.addEventListener('change', call(callback), false);
		});
	};

	/**
	 * Listen to clicks on the elements
	 * @param {Function} callback
	 * @returns {void}
	 */
	o.prototype.click = function(callback) {
		this.elements.forEach(function(element) {
			element.addEventListener('click', call(callback), false);
		});
	};

	/**
	 * Set the style attribute of the elements
	 * @param {Object|String} css Key-Value pairs of CSS rules or CSS rule
	 * @param {String} value CSS value if first argument was a rule
	 * @returns {void}
	 */
	o.prototype.css = function(css, value) {
		var style = '';

		if (css instanceof Object)
			for (var rule in css)
				style += rule+':'+css[rule]+';';
		else
			style = css+':'+value+';';

		this.elements.forEach(function(element) {
			element.setAttribute('style', style);
		});
	};

	/**
	 * Loop each element
	 * @param {Function} callback
	 * @returns {void}
	 */
	o.prototype.each = function(callback) {
		this.elements.forEach(function(element) {
			callback.call(f(element));
		});
	};

	/**
	 * Return the offsetHeight of the element
	 * @returns {Number}
	 */
	o.prototype.height = function() {
		return this[0].offsetHeight;
	};

	/**
	 * Hide the elements
	 * @returns {void}
	 */
	o.prototype.hide = function() {
		this.elements.forEach(function(element) {
			element.style.display = 'none';
		});
	};
	
	/**
	 * Get (first element) or set (all elements) the inner HTML
	 * @param {undefined|String} html
	 * @returns {String}
	 */
	o.prototype.html = function(html) {
		if (typeof(html) === 'undefined')
			return this.elements[0].innerHTML;
		
		this.elements.forEach(function(element) {
			element.innerHTML = html;
		});
	};

	/**
	 * Listen to events on the elements
	 * @param {String} event
	 * @param {Function} callback
	 * @param {Bool} capture
	 * @returns {void}
	 */
	o.prototype.listen = function(event, callback, capture) {
		if (capture === undefined)
			capture = false;

		this.elements.forEach(function(element) {
			element.addEventListener(event, callback, capture);
		});
	};

	/**
	 * Get the next sibling of the element
	 * @returns {f_L14.f.o}
	 */
	o.prototype.next = function() {
		var element = this[0];

		do
			element = element.nextSibling;
		while (element && element.nodeType !== 1);

		return element instanceof Element ? f(element) : undefined;
	};

	/**
	 * Get the offset of the element
	 * @returns {f_L14.f.o.prototype.offset.fAnonym$0}
	 */
	o.prototype.offset = function() {
		var x = 0, y = 0, element = this[0];

		do {
			x += element.offsetLeft;
			y += element.offsetTop;
			element = element.offsetParent;
		} while (element instanceof Element);

		return {
			left: x,
			top: y
		};
	};

	/**
	 * Get the parent of an element
	 * @returns {f_L14.f.o}
	 */
	o.prototype.parent = function() {
		return f(this[0].parentNode);
	};

	/**
	 * Prepend content to the elements
	 * @param {Element|String} content
	 * @returns {void}
	 */
	o.prototype.prepend = function(content) {
		this.elements.forEach(function(element) {
			if (content instanceof Element)
				element.insertBefore(content, element.firstChild);
			else if (typeof(content === 'string'))
				element.insertBefore(document.createTextNode(content), element.firstChild);
		});
	};

	/**
	 * Get the previous sibling of the element
	 * @returns {f_L14.f.o}
	 */
	o.prototype.prev = function() {
		var element = this.elements[0];

		do
			element = element.previousSibling;
		while (element && element.nodeType !== 1);

		return element instanceof Element ? f(element) : undefined;
	};

	/**
	 * Remove a CSS class from the elements
	 * @param {String} cls
	 * @returns {void}
	 */
	o.prototype.removeClass = function(cls) {
		var classList = arguments;

		this.elements.forEach(function(element) {
			for (var i=0;i<classList.length;++i)
				element.classList.remove(classList[i]);
		});
	};

	/**
	 * Show the elements
	 * @returns {void}
	 */
	o.prototype.show = function() {
		this.elements.forEach(function(element) {
			element.style.display = 'initial';
		});
	};

	/**
	 * Make all children with draggable argument sortable
	 * @param {Object} args
	 * @returns {void}
	 */
	o.prototype.sortable = function(args) {
		f('[draggable=true]', this).each(function() {
			['drop', 'end', 'enter', 'leave', 'over', 'start'].forEach(function(event) {
				if (args[event] instanceof Function)
					this.listen('drag'+event, call(args[event]), false);
			}, this);
		});
	};
	
	o.prototype.text = function(text) {
		this.elements.forEach(function(element) {
			element.textContent = text;
		});
	};

	/**
	 * Get (first element) or set (all elements) the value attribute
	 * @param {String} value
	 * @returns {void}
	 */
	o.prototype.value = function(value) {
		return this.attr('value', value);
	};

	/**
	 * Return the offsetWidth of the element
	 * @returns {Number}
	 */
	o.prototype.width = function() {
		return this[0].offsetWidth;
	};
	
	/**
	 * The global Forge object
	 * @param {Window|Element|String} selector
	 * @param {f_L14.f.o|o|undefined} context
	 * @returns {f_L14.f.o|o}
	 */
	var f = function(selector, context) {
		// Return a new instance of the local object
		return new o(selector, context);
	};
	
	/**
	 * Retrieve a GET parameter
	 * @param {string} param Name of the parameter
	 * @returns {string|null}
	 */
	f.get = function(param) {
		var value = '';
		
		location.search.substr(1).split('&').forEach(function(pair) {
			var data = pair.split('=');
			
			if (data[0] === param)
				value = decodeURIComponent(data[1]);
		});
		
		return value;
	};
	
	/**
	 * Make a JSON call to Forge (or any other host)
	 * @param {Object} params
	 * @returns {void}
	 * @throws {Exception} Exceptions will be thrown on error
	 */
	f.json = function(params) {
		// We must have a parameter object
		if (typeof(params) !== 'object')
			throw 'No parameters given';
		
		// We must have either an addon to inquire or a host
		var url;
		if (typeof(params.addon) === 'string') {
			if (typeof(params.method) !== 'string')
				throw 'Addon given without method';
			
			url = '/json/'+params.addon+'/'+params.method;
		}
		else {
			if (typeof(params.url) === 'string')
				url = params.url;
			else
				throw 'No addon or URL given';
		}
		
		// Is there any Key-Value pairs to send as data?
		var data = typeof(params.data) === 'object' ? params.data : {};
		
		// Was a HTTP method supplied?
		var type = typeof(params.type) === 'string' ? params.type : 'GET';
		
		// Define a success callback if one exists
		var success = typeof(params.success) === 'function' ? params.success : voidFunction;
		
		// Define an error callback if one exists
		var error = typeof(params.error) === 'function' ? params.error : voidFunction;
		
		// Create the actual request
		var xhr = new XMLHttpRequest();
		xhr.addEventListener('loadend', function(event) {
			try {
				var json = JSON.parse(xhr.responseText);
			}
			catch (SyntaxError) {
				throw 'Did not recieve JSON data from server';
			}
			if (xhr.status === 200)
				success(json, xhr);
			else
				error(json.error, xhr);
		}, false);
		xhr.open(type, url);
		
		// Append the request URL if we use GET for the request
		if (type === 'GET') {
			var get = '';
			for (var key in data)
				get += encodeURIComponent(key)+'='+encodeURIComponent(data[key])+'&';
			
			url += get.length > 0 ? '?'+get.substr(0, get.length-1) : get;
			data = '';
		}
		// Set the message body if we use POST for the request
		else if (type === 'POST') {
			var post = '';
			for (var key in data)
				post += encodeURIComponent(key)+'='+encodeURIComponent(data[key])+'&';
			
			xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
			data = post.length > 0 ? post.substr(0, post.length-1) : post;
		}
		// If neither GET nor POST was requested, we don't know what to do
		else
			throw 'Neither GET nor POST was the given HTTP method';
		
		// Fire off the request
		xhr.send(data);
	};
	
	/**
	 * Fired when the window is ready
	 * @param {Function} callback
	 * @returns {void}
	 */
	f.ready = function(callback) {
		window.addEventListener('load', call(callback), false);
	};
	
	// Return the global object
	return f;
})();