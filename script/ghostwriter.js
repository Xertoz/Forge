var forge = typeof(forge) != 'object' ? {} : forge;

/**
* A text suggestion API written for Forge
* Copyright 2011 Mattias Lindholm
*/
forge.ghostwriter = {
    /**
    * How long animation?
    */
    ANIMATION_TIME: 250,
    
    /**
    * The DIV element we use for the suggestions
    */
    box: null,
    
    /**
    * When the input is blurred, we should close
    */
    blur: function() {
        $('#ghostwriter').fadeOut(forge.ghostwriter.ANIMATION_TIME);
    },
    
    /**
    * When the input is focused, we should open
    */
    focus: function() {
        // Save the input element
        var input = $(this);
        forge.ghostwriter.input = this;
        
        // Set some input dependent attributes
        forge.ghostwriter.box.css('left',input.offset().left);
        forge.ghostwriter.box.css('top',input.offset().top+input.outerHeight());
        forge.ghostwriter.box.css('min-width',input.outerWidth());
        
        // Populate the list
        forge.ghostwriter.populate();
        
        // Show it
        forge.ghostwriter.box.fadeIn(forge.ghostwriter.ANIMATION_TIME);
    },
    
    /**
    * Init the API
    */
    init: function() {
        // Create the elements in proper ascension
        forge.ghostwriter.box = $(document.createElement('div'));
        forge.ghostwriter.table = $(document.createElement('table'));
        forge.ghostwriter.box.append(forge.ghostwriter.table);
        
        // First off, set CSS attributes
        forge.ghostwriter.box.attr('id','ghostwriter');
        forge.ghostwriter.box.css('position','absolute');
        forge.ghostwriter.table.css('width','100%');
        
        // Make sure we listen for focus changes (& disable native autocomplete)
        $('input[type="text"].ghostwriter').attr('autocomplete','off');
        $('input[type="text"].ghostwriter').focus(forge.ghostwriter.focus);
        $('input[type="text"].ghostwriter').blur(forge.ghostwriter.blur);
        
        // Display it hiddenly to begin with
        $('body').append(forge.ghostwriter.box.hide());
    },
    
    /**
    * The current input being treated
    */
    input: null,
    
    /**
    * Populate the table
    */
    populate: function() {
        if (forge.ghostwriter.input == null || typeof(forge.ghostwriter.input.ghostwriter) != 'object' || typeof(forge.ghostwriter.input.ghostwriter.loading) != 'undefined' && forge.ghostwriter.input.ghostwriter.loading)
            forge.ghostwriter.table.html('<tr><td>Loading...</td>');
        else {
            forge.ghostwriter.table.html(null);
            
            for (var i=0;i<forge.ghostwriter.input.ghostwriter.values.length;i++) (function(i) {
                var row = $('<tr></tr>');
                row.click(function() { forge.ghostwriter.select(i); });
                row.append($('<td>'+forge.ghostwriter.input.ghostwriter.values[i]+'</td>'));
                forge.ghostwriter.table.append(row);
            })(i);
        }
        
        console.log(forge.ghostwriter.input.ghostwriter);
    },
    
    /**
    * What happens when we select a suggestion?
    */
    select: function(i) {
        $(forge.ghostwriter.input).attr('value',forge.ghostwriter.input.ghostwriter.values[i]);
    },
    
    /**
    * The table to be used for the population
    */
    table: null
};

// When we have finished loading the document, we should initiate
$(document).ready(forge.ghostwriter.init);