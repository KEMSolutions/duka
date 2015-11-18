/**
 * Component responsible for handling the payment overlay behaviour.
 *
 * @type {{cancelOrder: Function, checkPendingOrders: Function, showPaymentNotice: Function, init: Function}}
 */
var paymentOverlayContainer = {

    /**
     * Cancels an order.
     * If the user clicks the cancel button, remove the cookie, flush the card, fadeOut the jumbotron then redirect to homepage.
     *
     */
    cancelOrder : function() {
        $("body").on("click", "#cancelOrder", function() {
            var cookie_id = JSON.parse(Cookies.get("_unpaid_orders")).id;
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

        // Create dimmer notice.
        var cancelledOrder =
            '<div class="ui page active dimmer">' +
                '<div class="ui container color-one vertical-align" id="cancelledOrder">' +
                    '<h2 class="ui header">' + Localization.pending_order.replace(':command', order.id) + '</h2>' +
                    '<h4 class="ui header">'+ Localization.what_to_do +'</h4>'+
                    '<br/>' +
                    '<a href="'+ ApiEndpoints.orders.pay.replace(':id', order.id).replace(':verification', order.verification) +'">'+
                        '<button class="ui button green" id="payOrder">'+
                            Localization.pay_now +
                        '</button>'+
                    '</a>'+
                    '<button class="ui button red" id="cancelOrder">'+
                        Localization.cancel_order +
                    '</button>'+
                '</div>' +
            '</div>';

        // Display notice.
        $('body').prepend(cancelledOrder);
        $('#cancelledOrder').dimmer('closable', 'false');

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
