var productCardFormatContainer = {

    /**
     * Temporary function to handle the format change on product card.
     * Until we use Vue.js to fix all this mess...
     *
     */
    syncFormat: function () {

        // Change the buybutton data attributes.
        $(".product-format").on("change", function () {
            $(this).parent().next().attr({
                'data-product': $(this).val(),
                'data-price': $(this).find(":selected").data("price"),
                'data-name': $(this).find(":selected").data("name"),
                'data-format': $(this).find(":selected").data("format")
            });

            // Change buybutton text.
            $(this).parent().next().find(".format-name").text($(this).find(":selected").data('format'));

            // Change buybutton price
            $(this).parent().next().find(".format-price").text($(this).find(":selected").data('price'));
        });
    },

    init: function () {
        productCardFormatContainer.syncFormat();
    }
};