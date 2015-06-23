/**
 * Object responsible for handling the payment overlay behaviour.
 * Will be present on all the pages, thus written here.
 *
 * @type {{cancelOrder: Function, init: Function}}
 */
var paymentOverlayContainer = {

    /**
     * Cancels an order.
     * If the user clicks the cancel button, remove the cookie, flush the card, fadeOut the jumbotron then redirect to homepage.
     *
     */
    cancelOrder : function() {
        $("body").on("click", "#cancelOrder", function() {
            Cookies.remove("_unpaid_orders");

            $("#cancelledOrder .jumbotron").fadeOut();

            window.location.replace("/");

            UtilityContainer.removeAllProductsFromLocalStorage();

        });
    },

    /**
     * Checks whether the user has any unpaid orders, and displays a message if that's the case.
     *
     */
    checkPendingOrders : function() {

        if (Cookies.get('_unpaid_orders')) {

            // Retrieve order details.
            var order = JSON.parse(Cookies.get('_unpaid_orders'));

            // Check whether current order has been paid.
            $.ajax({
                type: 'GET',
                url: ApiEndpoints.orders.view.replace(':id', order.id).replace(':verification', order.verification),
                success: function(data) {
                    if (data.status == 'pending')
                        paymentOverlayContainer.showPaymentNotice();
                    else
                        Cookies.remove('_unpaid_orders');
                }
            });
        }

    },

    /**
     * Shows payment notice.
     *
     */
    showPaymentNotice : function() {

        // Retrieve order details.
        var order = JSON.parse(Cookies.get('_unpaid_orders'));

        // Display notice.
        $('body').prepend(
            '<div class="container overlay fullScreen" id="cancelledOrder">'+
                '<div class="jumbotron vertical-align color-one">'+
                    '<div class="text-center">'+
                        '<h2>'+
                            Localization.pending_order.replace(':command', order.id) +
                        '</h2>'+
                        '<h4>'+ Localization.what_to_do +'</h4>'+
                        '<br />'+
                        '<a href="'+
                            ApiEndpoints.orders.pay.replace(':id', order.id)
                                .replace(':verification', order.verification) +'">'+
                            '<button class="btn btn-success" id="payOrder">'+ Localization.pay_now +'</button>'+
                        '</a>'+
                        '<button class="btn btn-danger" id="cancelOrder">'+
                            Localization.cancel_order +
                        '</button>'+
                    '</div>'+
                '</div>'+
            '</div>'
        );
    },

    /**
     * Register functions to be called outside paymentOverlayContainer.
     *
     */
    init : function() {
        var self = paymentOverlayContainer;

        self.cancelOrder();
        self.checkPendingOrders();
    }
}

$(document).ready(function () {
    //fancy plugin for product page (quantity input)
    $(".input-qty").TouchSpin({
        initval: 1
    });

    //Initialize overlay plugin.
    paymentOverlayContainer.init();
});