var mixpanelAnalytics = {
    events: {
        addToCart: function () {
            mixpanel.track("Added 1 item to cart.");
        },

        checkoutPage: function () {
            mixpanel.track("Checkout main page.");
        },

        checkoutShipping: function () {
            mixpanel.track("Successfully loaded shipping methods");
        },

        checkoutPayment: function () {
            mixpanel.track("Redirected to payment page.");
        },

        orderCancelled: function (id) {
            mixpanel.track("Order cancelled", {
                "Order ID": id
            });
        },

        orderSuccess: function (id) {
            mixpanel.track("Order success", {
                "Order ID": id
            });
        }

    }
}
