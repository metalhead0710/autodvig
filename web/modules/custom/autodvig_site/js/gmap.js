(function ($, Drupal, drupalSettings) {
  Drupal.behaviors.gmap = {
    attach: function (context, settings) {
      if (!('lattitude' in drupalSettings) || !('longtitude' in drupalSettings)) {
        return;
      }

      $.getScript('https://maps.googleapis.com/maps/api/js?key='+settings.api_key+'&region=UK&language=uk',function () {

        var myCenter = new google.maps.LatLng(settings.lattitude, settings.longtitude);
        var mapCanvas = document.getElementById("map");
        var mapOptions = {
          center: myCenter,
          zoom: 15,
        };

        var map = new google.maps.Map(mapCanvas, mapOptions);

        var marker = new google.maps.Marker({
          position: new google.maps.LatLng(settings.lattitude, settings.longtitude)
        });
        marker.setMap(map);
      });
    },
  };
})(jQuery, Drupal, drupalSettings);
