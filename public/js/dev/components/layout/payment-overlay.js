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
            Cookies.remove("_current_order");

            $("#cancelledOrder").fadeOut();

            window.location.replace("/");

            UtilityContainer.removeAllProductsFromLocalStorage();
        });
    },

    /**
     * Display the unpaid overlay using semantic-ui modal module.
     *
     */
    displayUnpaidOverlay: function () {
        var order = JSON.parse(Cookies.get('_current_order'));

        var unpaidOverlay =
            '<div class="ui small modal text-center unpaid-modal">' +
                '<i class="close icon"></i>' +
                '<div class="header">' +
                    Localization.pending_order.replace(':command', order.id) +
                '</div>' +
                '<div class="content">' +
                    '<div class="description">' +
                        '<div class="ui header">'  +
                            Localization.what_to_do +
                        '</div>' +
                        '<a href="' + order.payment_url + '">' +
                            '<button class="ui button green" id="payOrder">'+ Localization.pay_now +'</button>'+
                        '</a>' +
                        '<button class="ui button red" id="cancelOrder">'+
                            Localization.cancel_order +
                        '</button>'+
                    '</div>' +
                '</div>' +
            '</div>';

        $("body").prepend(unpaidOverlay);
        $(".small.unpaid-modal").modal("show");

    },


    /**
     * Display the congratulate overlay using semantic-ui modal module.
     *
     * @param order
     */
    displayCongratulateOverlay: function (order) {
        var overlay =
            '<div class="ui modal congratulate-modal payment_successful">' +
                '<div class="header">' +
                    Localization.payment_successful +
                '</div>' +
                '<div class="content">' +
                    '<div class="description">' +
                        '<div class="ui header">' +
                            Localization.summary_below +
                        '</div>' +
                        '<p>' + Localization.summary_copy + '</p>' +
                    '</div>' +
                    '<br/>' +
                    '<table class="ui striped table" style="margin: 0 auto">' +
                        '<tbody class="center aligned">' +
                            '<tr>' +
                                '<td>' + Localization.order + '</td>' +
                                '<td>' + "#" + order.id + '</td>' +
                            '</tr>' +

                            '<tr>' +
                                '<td>' + Localization.shipping_address + '</td>' +
                                '<td>' +
                                    order.shipping_address.line1 +
                                    '<br/>' +
                                    '<br/>' +
                                    order.shipping_address.postcode +
                                    '<br/>' +
                                    order.shipping_address.city +
                                    ', ' +
                                    order.shipping_address.province +
                                    ', ' +
                                    order.shipping_address.country +
                                    '<br/>' +
                                    order.shipping_address.name +
                                '</td>' +
                            '</tr>'+

                            '<tr>' +
                                '<td>' + Localization.billing_address + '</td>' +
                                '<td>' +
                                    order.billing_address.line1 +
                                    '<br/>' +
                                    '<br/>' +
                                    order.billing_address.postcode +
                                    '<br/>' +
                                    order.billing_address.city +
                                    ', ' +
                                    order.billing_address.province +
                                    ', ' +
                                    order.billing_address.country +
                                    '<br/>' +
                                    order.billing_address.name +
                            '   </td>' +
                            '</tr>'+

                            '<tr>' +
                                '<td>' + Localization.subtotal + '</td>' +
                                '<td>' + "$" + parseFloat(order.payment_details.subtotal).toFixed(2) + '</td>' +
                            '</tr>' +

                            '<tr>' +
                                '<td>' + Localization.taxes + '</td>' +
                                '<td>' + "$" + parseFloat(order.payment_details.taxes).toFixed(2) + '</td>' +
                            '</tr>' +

                            '<tr>' +
                                '<td>' + Localization.total + '</td>' +
                                '<td>' + "$" + parseFloat(order.payment_details.total).toFixed(2) + '</td>' +
                            '</tr>' +
                        '</tbody>' +
                    '</table>' +
                '</div>' +
                '<div class="actions">' +
                    '<div class="ui black deny button">' +
                        Localization.close +
                    '</div>' +
                '</div>' +
            '</div>';

        $("body").prepend(overlay);

        $(".congratulate-modal").modal("show");
    },

    /**
     * Second Ajax call after the order has been paid.
     * We make a call to the API to get more details about it.
     * Laravel takes care of the security issue that this implementation can raise.
     *
     * @param order
     */
    getOrderInformation: function (order) {
        $.ajax({
            type: 'GET',
            url: ApiEndpoints.orders.view.replace(':id', order.id).replace(':verification', order.verification),
            success: function (order_details) {
                this.displayCongratulateOverlay(order_details);
                console.log(order_details);
            }.bind(this),
            error: function (xhr, error, code) {
                console.log(error);
            }
        });
    },

    /**
     * Checks the status of the current order stored in _current_order cookie.
     *
     */
    checkPendingOrders : function() {

        if (Cookies.get('_current_order')) {

            // Retrieve order details.
            var order = JSON.parse(Cookies.get('_current_order'));

            // Check whether current order has been paid.
            $.ajax({
                type: 'GET',
                url: ApiEndpoints.orders.view.replace(':id', order.id).replace(':verification', order.verification),
                success: function(data) {
                    if (data.status === 'pending') {
                        this.displayUnpaidOverlay();
                    }
                    else if (data.status === 'paid') {
                        // Display congratulation dimmer.
                        this.getOrderInformation(order);

                        // Remove products from cart
                        UtilityContainer.removeAllProductsFromLocalStorage();

                        // Delete the unpaid orders cookie (if any).
                        Cookies.remove('_current_order');
                    }
                    else {
                        Cookies.remove('_current_order');
                    }
                }.bind(this)
            });
        }

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
};
