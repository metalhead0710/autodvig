(function ($, Drupal) {
  Drupal.behaviors.sellingPriceCalculator = {
    attach: function (context) {
      var self = this;
      var $totalWrapper = $('#buying-price-total');
      if (!$totalWrapper.length) {
        $('#buying-price').append('<div id="buying-price-total"></div>');
      }
      var $europeBuyingPriceField = $(context).find('#edit-field-europe-price-0-value');
      var $deliveryPriceField = $(context).find('#edit-field-delivery-0-value');
      var $customsClearancePriceField = $(context).find('#edit-field-customs-clearence-0-value');
      this.calculatePrice($europeBuyingPriceField, $deliveryPriceField, $customsClearancePriceField);
      $europeBuyingPriceField.add($deliveryPriceField).add($customsClearancePriceField).on('input', function () {
        self.calculatePrice($europeBuyingPriceField, $deliveryPriceField, $customsClearancePriceField);
      })

    },
    calculatePrice: function ($europeBuyingPriceField, $deliveryPriceField, $customsClearancePriceField) {
      var total = 0;
      var $europeBuyingPrice = parseFloat($europeBuyingPriceField.val());
      if (!Number.isNaN($europeBuyingPrice)) {
        total = total + $europeBuyingPrice;
      }
      var $deliveryPrice = parseFloat($deliveryPriceField.val());
      if (!Number.isNaN($deliveryPrice)) {
        total = total + $deliveryPrice;
      }
      var $customsClearancePrice = parseFloat($customsClearancePriceField.val());
      if (!Number.isNaN($customsClearancePrice)) {
        total = total + $customsClearancePrice;
      }
      if (total !== 0) {
        $('#buying-price-total').html('<strong>Разом: </strong>' + total + '€');
      }
    }
  };
})(jQuery, Drupal);
