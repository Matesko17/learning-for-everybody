/**
 * LIVE FORM VALIDATION
 * Must be initialized before document or else some options do not work (for example messageParentClass).
 */
//LiveForm.setOptions({
//    controlValidClass: 'label label-success',
//    messageErrorClass: 'label label-danger',
//    messageErrorPrefix: ''
//});

/**
 * Here are functions which will run after the document is loaded.
 */
$(document).ready(function () {
    $(".navbar-collapse").on("show.bs.collapse hide.bs.collapse", function () {
        $(".hamburger-toggle").toggleClass("is-active");
    });

    /*
        //
        // NETTE SPINNER
    */
    // $.nette.ext('spinner', {
    //     init: function () {
    //         this.spinner = this.createSpinner();
    //         this.spinner.appendTo('body');
    //     },
    //     start: function () {
    //         this.counter++;
    //         if (this.counter === 1) {
    //             this.spinner.show(this.speed);
    //         }
    //     },
    //     complete: function () {
    //         this.counter--;
    //         if (this.counter <= 0) {
    //             this.spinner.hide(this.speed);
    //         }
    //     }
    // }, {
    //     createSpinner: function () {
    //         return $('<div>', {
    //             id: 'ajax-spinner',
    //             css: {
    //                 display: 'none'
    //             }
    //         });
    //     },
    //     spinner: null,
    //     speed: undefined,
    //     counter: 0
    // });
    // $.nette.init();

    /*
        //
        // SLICK CAROUSEL
        //
    */
    // if ($(".slick-carousel")[0]) {
    //     $(".slick-carousel").slick({
    //         dots: true,
    //         infinite: true,
    //         speed: 200,
    //         slidesToShow: 4,
    //         responsive: [{
    //             breakpoint: 1200,
    //             settings: {
    //                 slidesToShow: 3
    //             }
    //         }, {
    //             breakpoint: 992,
    //             settings: {              
    //                 slidesToShow: 2
    //              }
    //         }, {
    //            breakpoint: 768,
    //            settings: {
    //                slidesToShow: 1
    //            }
    //         }]
    //     });
    // }

    /*
        //
        // LIGHT GALLERY
        //
    */
    // if ($(".light-gallery")[0]) {
    //     $(".light-gallery").lightGallery({

    //         // mode: 'lg-slide',

    //         // Ex : 'ease'
    //         // cssEasing: 'ease',

    //         //'for jquery animation'
    //         // easing: 'linear',
    //         // speed: 600,
    //         // height: '100%',
    //         // width: '100%',
    //         // addClass: '',
    //         // startClass: 'lg-start-zoom',
    //         // backdropDuration: 150,
    //         // hideBarsDelay: 6000,

    //         // useLeft: false,

    //         // closable: true,
    //         // loop: true,
    //         // escKey: true,
    //         // keyPress: true,
    //         // controls: true,
    //         // slideEndAnimatoin: true,
    //         // hideControlOnEnd: false,
    //         // mousewheel: true,

    //         // getCaptionFromTitleOrAlt: true,

    //         // .lg-item || '.lg-sub-html'
    //         // appendSubHtmlTo: '.lg-sub-html',

    //         // subHtmlSelectorRelative: false,

    //         /**
    //          * @desc number of preload slides
    //          * will exicute only after the current slide is fully loaded.
    //          *
    //          * @ex you clicked on 4th image and if preload = 1 then 3rd slide and 5th
    //          * slide will be loaded in the background after the 4th slide is fully loaded..
    //          * if preload is 2 then 2nd 3rd 5th 6th slides will be preloaded.. ... ...
    //          *
    //          */
    //         preload: 3,
    //         // showAfterLoad: true,
    //         selector: 'a',
    //         // selectWithin: '',
    //         // nextHtml: '',
    //         // prevHtml: '',

    //         // 0, 1
    //         // index: false,

    //         // iframeMaxWidth: '100%',

    //         // download: true,
    //         // counter: true,
    //         // appendCounterTo: '.lg-toolbar',

    //         // swipeThreshold: 50,
    //         // enableSwipe: true,
    //         // enableDrag: true,

    //         // dynamic: false,
    //         // dynamicEl: [],
    //         // galleryId: 1,

    //         /**
    //          * plugins
    //          */
    //          // autoplay: true,
    //          // pause: 5000,
    //          // autoplayControls: false,

    //          // fullScreen: false,

    //          // zoom: false,

    //         // thumbnail: true,
    //         // showThumbByDefault: false,
    //         // thumbMargin: 10,
    //         exThumbImage: 'data-exthumbimage',

    //     });
    // }

    /*
        //
        // ANIMATE CSS
        //
    */
    // $.fn.extend({
    //     animateCss: function(animationName, callback) {
    //     var animationEnd = (function(el) {
    //         var animations = {
    //         animation: 'animationend',
    //         OAnimation: 'oAnimationEnd',
    //         MozAnimation: 'mozAnimationEnd',
    //         WebkitAnimation: 'webkitAnimationEnd',
    //         };

    //         for (var t in animations) {
    //         if (el.style[t] !== undefined) {
    //             return animations[t];
    //         }
    //         }
    //     })(document.createElement('div'));

    //     this.addClass('animated ' + animationName).one(animationEnd, function() {
    //         $(this).removeClass('animated ' + animationName);

    //         if (typeof callback === 'function') callback();
    //     });

    //     return this;
    //     },
    // });


    // UNIVERSAL SCROLL EVENT
    $(document).on("click",'[data-scroll]', function(e) {
        e.preventDefault();

        var target = $(this).attr("data-scroll");

        $('html,body').animate({
            scrollTop: $("#"+target).offset().top - 15
         });

    });    

    //
    // PERMALINK SCROLLER
    var urlHash = location.hash;
 
    if(urlHash != undefined && urlHash != "") {
        $('html,body').animate({
            scrollTop: $(urlHash).offset().top - 15
        });  
    }

    //
    // LAZY LOADING
    /*
        Using of lazy loading:
            - img must have data-src attribute with image source
            - no src is defined
            - example: <img data-src="mySourceImage.png">
    */
    // imagesLazyLoad();
    // $(document).scroll(function(){ 
    //     imagesLazyLoad();
    // });

    //
    // AOS    
    //AOS.init();

    //
    // CUSTOM SCROLLBAR PLUGIN
    //
    //$(element).mCustomScrollbar();


    /*
        //
        // CUSTOM SCRIPTS
        //
    */

});

// $(window).load(function () {
// });
