(function ($, Drupal, drupalSettings) {
  Drupal.behaviors.sellingPriceCalculator = {
    attach: function (context, settings) {
      if (!('selling_price_calculator' in drupalSettings)) {
        return;
      }

      var percent = parseFloat(drupalSettings.selling_price_calculator.percent);
      if (isNaN(percent)) return;

      var buyingPriceField = $(context).find('#edit-field-bying-price-0-value');
      buyingPriceField.on('input', function () {
        var byingPrice = parseFloat(buyingPriceField.val());
        var margin = byingPrice * (percent / 100);
        var sellingPrice = byingPrice + margin;
        $('#edit-field-selling-price-eur-0-value').val(sellingPrice);
      })

    },
  };
})(jQuery, Drupal, drupalSettings);
