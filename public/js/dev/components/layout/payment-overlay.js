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
            Cookies.remove("_unpaid_orders");

            $("#cancelledOrder").fadeOut();

            window.location.replace("/");

            UtilityContainer.removeAllProductsFromLocalStorage();
        });
    },

    displayUnpaidOverlay: function () {
        var order = JSON.parse(Cookies.get('_unpaid_orders'));

        var unpaidOverlay =
            '<div class="ui page active dimmer">' +
                '<div class="ui container color-one vertical-align" id="cancelledOrder">' +
                    '<h2 class="ui header">' + Localization.pending_order.replace(':command', order.id) + '</h2>' +
                    '<h4 class="ui header">' + Localization.what_to_do + '</h4>' +
                    '<br/>' +
                    '<a href="'+
                        ApiEndpoints.orders.pay.replace(':id', order.id)
                            .replace(':verification', order.verification) +'">'+
                        '<button class="ui button green" id="payOrder">'+ Localization.pay_now +'</button>'+
                    '</a>' +
                    '<button class="ui button red" id="cancelOrder">'+
                    Localization.cancel_order +
                    '</button>'+
                '</div>' +
            '</div>';

        $("body").prepend(unpaidOverlay);

    },

    displayCongratulateOverlay: function () {
        var order = JSON.parse(Cookies.get('_unpaid_orders'));

        $.ajax({
            type: 'GET',
            url: ApiEndpoints.orders.view.replace(':id', order.id).replace(':verification', order.verification),
            success: function (data) {
                this.renderCongratulateOverlay(data);
            }.bind(this)
        });
    },

    renderCongratulateOverlay: function (order) {
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

    userAuthenticated: function (request) {
        return '<p> User auth not yet implemented </p>';
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
                    if (data.status === 'pending') {
                        $('#cancelledOrder').dimmer({
                            closable: false
                        });
                    }
                    else if (data.status === 'paid') {
                        // Display congratulation dimmer.
                        $('.congratulate-dimmer').dimmer({
                            opacity: 1
                        });

                        // Remove products from cart
                        UtilityContainer.removeAllProductsFromLocalStorage();

                        // Delete the unpaid orders cookie (if any).
                        Cookies.remove('_unpaid_orders');
                    }
                    else {
                        Cookies.remove('_unpaid_orders');
                    }
                }
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

        // This is temporary.
        //self.renderCongratulateOverlay({});

    }
};
