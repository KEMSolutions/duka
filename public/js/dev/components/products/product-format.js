/**
 * Object responsible for handling different formats of the same product.
 *
 * @type {{displaySyncedProductInformation: Function, setInventoryCount: Function, setPriceTag: Function, init: Function}}
 */
var productFormatContainer = {

    /**
     * Sets the right price, inventory count and format text according to the format of the hovered product.
     *
     */
    displaySyncedProductInformation: function() {

        const self = productFormatContainer,
            $formatSelection = $(".format-selection");

        $formatSelection.on("click", function () {
            self.setPriceTag($(this).data("price"));
            self.setInventoryCount($(this).data("inventory-count"));
            $("#product-format").text($(this).data("format"));

            self.setBuybuttonInformation($(this));
        });

    },

    /**
     * Sets the inventory text and value according to the inventory count of the product.
     *
     * @param count
     */
    setInventoryCount: function (count) {
        const $inventoryCount = $("#inventory-count"),
            countryCode = $inventoryCount.data("country-code"),
            expressShipping = Localization.express_shipping,
            stockLeft = Localization.stock_left.replace(":quantity", count),
            shippingTime = Localization.shipping_time,
            shippingMethod = (countryCode === "US" || countryCode === "CA") ? "fa-truck" : "fa-plane";

        var inventoryDescription = '';

       if (count > 5) {
            inventoryDescription =
                '<link itemprop="availability" href="http://schema.org/InStock">' +
                    '<li class="text-success">' +
                    '<i class="fa ' + shippingMethod + ' fa-fw"></i> ' +
                    expressShipping;
       }
       else if (count > 0) {
           inventoryDescription =
               '<link itemprop="availability" href="http://schema.org/LimitedAvailability" >' +
               '<li class="text-warning">' +
                   '<i class="fa ' + shippingMethod + ' fa-fw"></i> ' +
                   stockLeft;
       }
        else {
           inventoryDescription =
               '<link itemprop="availability" href="http://schema.org/LimitedAvailability" >' +
           '<li class="text-warning">' +
           '<i class="fa ' + shippingMethod + ' fa-fw"></i> ' +
           shippingTime;
       }

        $inventoryCount.html(inventoryDescription);

    },

    /**
     * Sets the price tag according to the format.
     *
     * @param price
     */
    setPriceTag: function (price) {
        $(".price-tag").text("$ " + price);
    },

    setBuybuttonInformation: function(format) {
        var $buybuttonWrapper = $(".buybutton-format-selection-wrapper"),
            buybutton =
                '<button class="btn btn-three buybutton horizontal-align"' +
                    'data-product="' + format.data("product") +'"' +
                'data-price="' + format.data("price") +'"' +
                'data-thumbnail="' + format.data("thumbnail") +'"' +
                'data-thumbnail_lg="' + format.data("thumbnail_lg") +'"' +
                'data-name="' + format.data("name") +'"' +
                'data-format="' + format.data("format") +'"' +
                'data-inventory-count="' + format.data("inventory-count") +'"' +
                'data-quantity="' + format.data("quantity") +'"' +
                'data-link="' + format.data("link") +'"' +
                    '>' +
                '<div class="add-cart">' +
                    '<i class="fa fa-check-circle"></i> ' +
                    Localization.add_cart +
                    '</div> </button>';

        $buybuttonWrapper.empty();

        $buybuttonWrapper.append(buybutton);
    },

    init: function () {
        const self = productFormatContainer;

        self.displaySyncedProductInformation();
    }
}