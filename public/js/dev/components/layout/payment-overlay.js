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
                        '<a href="'+
                            ApiEndpoints.orders.pay.replace(':id', order.id)
                                .replace(':verification', order.verification) +'">'+
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
            '<div class="ui modal congratulate-modal">' +
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
                                '<td>' + "#{{ $latest_order_details->id }}" + '</td>' +
                            '</tr>' +

                            '<tr>' +
                                '<td>' + Localization.shipping_address + '</td>' +
                                '<td>' +
                                    "{{ $latest_order_details->shipping_address->line1 }}" +
                                    " Shipping address 2 if not null." +
                                    '<br/>' +
                                    "{{ $latest_order_details->shipping_address->postcode }}"+
                                    '<br/>' +
                                    "{{ $latest_order_details->shipping_address->city }}," +
                                    "{{ $latest_order_details->shipping_address->province }}, " +
                                    "{{ $latest_order_details->shipping_address->country }}" +
                                    '<br/>' +
                                    "{{ $latest_order_details->shipping_address->name }}" +
                                '</td>' +
                            '</tr>'+

                            '<tr>' +
                                '<td>' + Localization.billing_address + '</td>' +
                                '<td>' +
                                "{{ $latest_order_details->billing_address->line1 }}" +
                                " Billing address 2 if not null." +
                                '<br/>' +
                                "{{ $latest_order_details->billing_address->postcode }}"+
                                '<br/>' +
                                "{{ $latest_order_details->billing_address->city }}," +
                                "{{ $latest_order_details->billing_address->province }}, " +
                                "{{ $latest_order_details->billing_address->country }}" +
                                '<br/>' +
                                "{{ $latest_order_details->billing_address->name }}" +
                            '   </td>' +
                            '</tr>'+

                            '<tr>' +
                                '<td>' + Localization.subtotal + '</td>' +
                                '<td>' + "$20.00" + '</td>' +
                            '</tr>' +

                            '<tr>' +
                                '<td>' + Localization.taxes + '</td>' +
                                '<td>' + "$1.75" + '</td>' +
                            '</tr>' +

                            '<tr>' +
                                '<td>' + Localization.total + '</td>' +
                                '<td>' + "$21.75" + '</td>' +
                            '</tr>' +
                        '</tbody>' +
                    '</table>' +

                    paymentOverlayContainer.userAuthenticated(order) +

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
     * Checks if the user that made the order already has a password.
     * If not, we prompt him with an incentive message.
     *
     * @param request
     * @returns {string}
     */
    userAuthenticated: function (request) {
        return '<p> User auth not yet implemented </p>';
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
            success: function (data) {
                return {}
            }.bind(this)
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
                        this.displayCongratulateOverlay();

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
