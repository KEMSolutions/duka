var GAEAnalytics = {
    /**
     * Categories
     *      Action ([Label], [Value])
     *
     * Products
     *      add_to_cart (product-id, price)
     *
     * Checkout
     *      estimate (price)
     *      checkout_page (total_price)
     *      checkout_success (id, price)
     *      checkout_failure (id, price)
     *
     */
    events: {
        addToCart: function () {
            $("body").on("click", ".buybutton", function() {
                ga('send', {
                    hitType: 'event',
                    eventCategory: 'Products',
                    eventAction: 'add_to_cart',
                    eventLabel: $(this).data("product"),
                    eventValue: $(this).data("price")
                });
            });
        },

        estimate: function (price) {
            ga('send', {
                hitType: 'event',
                eventCategory: 'Checkout',
                eventAction: 'estimate',
                eventValue: price
            });
        }
    },

    init: function () {
        GAEAnalytics.events.addToCart();
    }
}