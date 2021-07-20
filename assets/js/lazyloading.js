/* IS ELEMENT IN VIEWPORT */
$.fn.isInViewport = function() {
    var elementTop = $(this).offset().top;
    var elementBottom = elementTop + $(this).outerHeight();
  
    var viewportTop = $(window).scrollTop();
    var viewportBottom = ( viewportTop + $(window).height() ) + 100 ;
  
    return elementBottom > viewportTop && elementTop < viewportBottom;
};

function imagesLazyLoad() {
    $("img").each(function() {
       if($(this).isInViewport()) {

        var imgSource  = $(this).attr("data-src");

        $(this).attr("src",imgSource);
        $(this).removeAttr("data-src");

       }
    });
}