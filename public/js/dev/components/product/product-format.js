/**
 * Component responsible for handling different formats of the same product.
 *
 * @type {{productWithFormat: Function, productWithoutFormat: Function, updateBuybuttonAttributes: Function, updateProductInformation: Function, init: Function}}
 */
var productFormatContainer = {

    /**
     * Update price value for a product with format.
     *
     * @param option
     */
    productWithFormat: function(option) {
        if (option.find(":selected").data("reduced") != "undef") {
            var price = '<span id="product-format-name">' +
                option.find(":selected").data("format") +
                '</span>' +
                ' - ' +
                '<span class="text-strikethrough">' +
                ' CAD $ ' + option.find(":selected").data("price") +
                '</span>' +
                '<span id="product-price" class="strong text-danger">' +
                ' CAD $ ' + option.find(":selected").data("reduced") +
                '</span>';
        }
        else {
            var price = '<span id="product-format-name">' +
                option.find(":selected").data("format") +
                '</span>' +
                ' - ' +
                '<span id="product-price" class="strong">' +
                ' CAD $ ' + option.find(":selected").data("price") +
                '</span>';
        }


        $(".sub.header").html(price);
    },


    /**
     * Update price value for a format-less product.
     *
     * @param option
     */
    productWithoutFormat: function(option) {
        // Change description.
        $("#product-format-name").text(option.find(":selected").data("format"));
        $("#product-price").text("CAD $ " + option.find(":selected").data("price"));
    },


    /**
     * Update buybutton data attributes according to format: id/price/name/format.
     *
     * @param option
     */
    updateBuybuttonAttributes: function (option) {
        $(".buybutton").attr({
            'data-product': option.val(),
            'data-price': option.find(":selected").data("reduced") === "undef" ? option.find(":selected").data("price") : option.find(":selected").data("reduced"),
            'data-name': option.find(":selected").data("name"),
            'data-format': option.find(":selected").data("format")
        });
    },

    /**
     * Main function of this module.
     * Once the format selector is clicked, trigger the appropriate helpers then update buybutton.
     *
     */
    updateProductInformation: function() {
        var self = productFormatContainer;

        $("#product-format").on("change", function () {

            if ($(this).find(":selected").data("reduced") != "null") {
                // Add discounted price for a product with different formats.
                self.productWithFormat($(this));
            }
            else {
                // Add discounted price for a single format product.
                self.productWithoutFormat($(this));
            }


            // Update buybutton with right attributes.
            self.updateBuybuttonAttributes($(this));
        });

    },

    /**
     * Entry point of this module.
     *
     */
    init: function () {
        const self = productFormatContainer;

        self.updateProductInformation();

    }
}
