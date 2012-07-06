/**
    Supply string additions for Forge
**/

var forge = typeof(forge) != 'object' ? {} : forge;

forge.string = {
    /**
        Pad a string
        @param string Input string to pad
        @param string Pad with this character
        @param string Output string length
        @param bool Left padding (default true)
        @return string
    **/
    pad: function(input,padchar,length,adjust_left) {
        // Default adjust_left to true
        if (typeof(adjust_left) == 'undefined')
            adjust_left = true;
        
        // Make sure this is a string
        if (typeof(input) != 'string')
            input = input.toString();
        
        // Initiate the padded string
        padded = '';
        
        if (adjust_left) {
            // Start result with padding
            for (i=0;i<length-input.length;i++)
                padded += padchar;
            
            // Add the input onto the padding
            padded += input;
        }
        else {
            // Start result with the input
            padded += input;
            
            // Add the padding onto the result
            for (var i=0;i<length-input.length;i++)
                padded += padchar;
        }
        
        // Return.
        return padded;
    }
}