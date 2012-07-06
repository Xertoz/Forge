/**
 * forge.js
 * Copyright 2012 Mattias Lindholm
 *
 * This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
 * to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
 */

var forge = typeof(forge) != 'object' ? {} : forge;

forge.ANIM_MESSAGE_SLIDE = 500;

forge.MESSAGE_BAD = 'bad';
forge.MESSAGE_GOOD = 'good';

forge.displayMessage = function(message,type) {
    var div = $('#forge-message').length ? $('#forge-message') : $('<div></div>',{'id':'forge-message'}).prependTo('body');
    div.html(message);
    div.attr('class',type);
    div.attr('onclick','$(this).slideUp(forge.ANIM_MESSAGE_SLIDE);');
    div.slideDown(forge.ANIM_MESSAGE_SLIDE);
};

/**
    List of what has already been included
**/
forge.includedList = new Array();

/**
* Create a safe id from the string
*/
forge.id = function(str) {
    return str.replace(' ','_').replace(/\\/g,'_');
};

/**
    Include other forge javascript files dynamically
    @return void
**/
forge.include = function(file) {
    // Make sure it's not already included
    for (var i=0;i<this.includedList.length;i++)
        if (this.includedList[i] == file)
            return;
    
    // Create the script element
    script = document.createElement('script');
    script.src = '/script/forge/'+file;
    script.type = 'text/javascript';
    script.defer = true;
    
    // Save this to the log
    this.includedList.push(script);
    
    // Insert it
    document.getElementsByTagName('head').item(0).appendChild(script);
};

forge.toast = function(message, cls) {
    var extra = typeof(cls) == 'undefined' ? '' : ' '+cls;

    var toast = document.createElement('div');
    toast.setAttribute('class', 'forge-toast');
    var text = document.createElement('p');
    text.setAttribute('class', extra);
    text.innerHTML = message;
    text.onclick = function(evt) {evt.target.classList.add('clicked');};
    toast.appendChild(text);
    document.body.appendChild(toast);
};