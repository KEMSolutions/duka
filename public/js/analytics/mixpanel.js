var mixpanelAnalytics = {
    events: {
        addToCart: function () {
            $("body").on("click", ".buybutton", function() {
                mixpanel.track("Added 1 item to cart.");
            });
        },

        checkoutPage: function () {
            $("#checkout").on("click", function() {
                mixpanel.track("Checkout main page.");
            });
        },

        checkoutPayment: function () {
            $(".next-payment-process, #payOrder").on("click", function (e) {
                mixpanel.track("Redirected to payment page.");
            });
        },

        orderCancelled: function () {
            $("body").on("click", "#cancelOrder", function() {
                var cookie_id = JSON.parse(Cookies.get("_unpaid_orders")).id;

                mixpanel.track("Order cancelled", {
                    "Order ID": cookie_id
                });
            });
        },

        orderSuccess: function () {
            if ($(".payment_successful").length > 0)
            {
                mixpanel.track("Order success");
            }
        }

    },

    init: function () {
        var self = mixpanelAnalytics;

        self.events.addToCart();
        self.events.checkoutPage();
        self.events.checkoutPayment();
        self.events.orderCancelled();
        self.events.orderSuccess();
    }
}
