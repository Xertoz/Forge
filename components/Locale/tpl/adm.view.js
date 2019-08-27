function translate(event) {
    var message = event.target.parentNode.children[0].innerHTML;
    var translation = prompt(message);

    if (!translation)
        return;

    forge.ajax({
        addon: 'XML',
        method: 'Controller',
        type: 'POST',
        error: function() {
            event.target.parentNode.children[1].innerHTML = '<span style="color:red;">Error</span>';
        },
        success: function() {
            event.target.parentNode.children[1].innerHTML = translation;
        },
        data: {
            'forge[controller]': 'Locale\\Message',
            locale: '<?php echo $locale; ?>',
            message: message,
            translation: translation
        }
    });
}

window.addEventListener('load', function() {
    var children = document.getElementById('messages').children;
    for (var i=0;i<children.length;++i)
        children[i].addEventListener('click', translate, false);
}, false);