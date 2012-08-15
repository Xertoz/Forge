/**
 * dragsort.js
 * Copyright 2012 Mattias Lindholm
 *
 * This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
 * to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
 */

var forge = typeof(forge) != 'object' ? {} : forge;

forge.dragsort = {};

forge.dragsort.drop = function(event) {
	var target = forge.dom.offsetY(event)/event.target.offsetHeight < 0.5
		? event.target.parentNode : forge.dom.next(event.target.parentNode);
	
	var row = this.getRow(event);
	row.classList.remove('forge-dragsort-top');
	row.classList.remove('forge-dragsort-bottom');
	
	event.target.parentNode.parentNode.insertBefore(forge.dragsort.source, target);
}

forge.dragsort.end = function(event) {
	forge.dragsort.source.style.opacity = 1;
}

forge.dragsort.enter = function(event) {
	this.getRow(event).classList.add('forge-dragsort-over');
}

forge.dragsort.getRow = function(event) {
	var element = event.target;
	
	while (element && element.tagName != 'TR') {
		element = element.parentNode;
	}
	
	return element;
}

forge.dragsort.leave = function(event) {
	var row = this.getRow(event);
	row.classList.remove('forge-dragsort-over');
	row.classList.remove('forge-dragsort-top');
	row.classList.remove('forge-dragsort-bottom');
}

forge.dragsort.over = function(event) {
	event.preventDefault();
	
	var row = this.getRow(event);
	row.classList.remove('forge-dragsort-top');
	row.classList.remove('forge-dragsort-bottom');
	
	var cls = forge.dom.offsetY(event)/event.target.offsetHeight < 0.5
		? 'forge-dragsort-top' : 'forge-dragsort-bottom';
	row.classList.add(cls);
	
	return false;
}

forge.dragsort.source = null;

forge.dragsort.start = function(event) {
	forge.dragsort.source = event.target;
	event.target.style.opacity = .25;
	event.dataTransfer.setData('text/html', this.innerHTML);
}