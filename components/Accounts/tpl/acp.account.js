//<?php self::forgeJS('dom'); ?>

function grant(elem) {
	var other = forge.dom.prev(elem);
	var input = forge.dom.prev(other);
	input.setAttribute('value', '1');
	elem.style.display = 'none';
	other.style.display = 'block';
}

function revoke(elem) {
	var other = forge.dom.next(elem);
	var input = forge.dom.prev(elem);
	input.setAttribute('value', '0');
	elem.style.display = 'none';
	other.style.display = 'block';
}