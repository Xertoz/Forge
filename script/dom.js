/**
 * dom.js
 * Copyright 2012 Mattias Lindholm
 *
 * This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
 * to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
 */

var forge = typeof(forge) != 'object' ? {} : forge;

/**
 * DOM helper API
 */
forge.dom = {};

/**
 * Get the next sibling of an element
 * @param params Element
 * @return Element
 */
forge.dom.next = function(elem) {
    do {
        elem = elem.nextSibling;
    }
    while (elem && elem.nodeType != 1);

    return elem;
};

/**
 * Get the previous sibling of an element
 * @param params Element
 * @return Element
 */
forge.dom.prev = function(elem) {
    do {
        elem = elem.previousSibling;
    }
    while (elem && elem.nodeType != 1);

    return elem;
};