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
                    eventAction: 'Add to cart',
                    eventLabel: $(this).data("product"),
                    eventValue: $(this).data("price")
                });
            });
        },

        estimate: function (price) {
            ga('send', {
                hitType: 'event',
                eventCategory: 'Checkout',
                eventAction: 'Estimate',
                eventValue: price
            });
        },

        checkout_page: function () {
            $("#checkout").on("click", function() {
                ga('send', {
                    hitType: 'event',
                    eventCategory: 'Checkout',
                    eventAction: 'Checkout main page'
                });
            });
        },

        checkout_success: function (id, price) {
            if ($(".payment_successful").length > 0)
            {
                ga('send', {
                    hitType: 'event',
                    eventCategory: 'Checkout',
                    eventAction: 'Checkout success'
                });
            }
        },

        checkout_failure: function () {
            $("body").on("click", "#cancelOrder", function() {
                var cookie_id = JSON.parse(Cookies.get("_unpaid_orders")).id;

                ga('send', {
                    hitType: 'event',
                    eventCategory: 'Checkout',
                    eventAction: 'Checkout failure',
                    eventLabel: 'Order #' + cookie_id
                });
            });
        }
    },

    init: function () {
        GAEAnalytics.events.addToCart();
        GAEAnalytics.events.checkout_page();
        GAEAnalytics.events.checkout_failure();
    }
}