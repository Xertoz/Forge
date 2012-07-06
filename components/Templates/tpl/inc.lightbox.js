$(document).ready(function() {
    $('a[rel^="lightbox"]').lightBox({
        imageBtnClose: '/images/lightbox/btn-close.gif',
        imageBtnNext: '/images/lightbox/btn-next.gif',
        imageBtnPrev: '/images/lightbox/btn-prev.gif',
        imageLoading: '/images/lightbox/ico-loading.gif'
    });
});