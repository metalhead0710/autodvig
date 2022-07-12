(function ($) {
  "use strict";
  $(".owl-carousel").owlCarousel({
    items:1,
    margin:10,
    autoHeight:true,
    dots: true,
    nav: true,
    center: true,
  });
  var count = $(".owl-stage > div").length - 1;
  $(".owl-carousel").trigger('remove.owl.carousel', [count]).trigger('refresh.owl.carousel');
})(jQuery);
