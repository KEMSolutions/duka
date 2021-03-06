/**
 * Component responsible for handling the payment overlay behaviour.
 * Entry point is in checkPendingOrders.
 *
 * @type {{cancelOrder: Function, displayUnpaidOverlay: Function, displayCongratulateOverlay: Function, renderAddress: Function, renderAdditionalDetails: Function, checkPendingOrders: Function, init: Function}}
 */
var paymentOverlayContainer = {

    /**
     * Cancel an order.
     * If the user clicks the cancel button, remove the cookie, flush the card, fadeOut the jumbotron then redirect to homepage.
     *
     */
    cancelOrder : function() {
        $("body").on("click", "#cancelOrder", function() {
            Cookies.remove("_current_order");

            $("#cancelledOrder").fadeOut();

            window.location.replace("/");

            UtilityContainer.removeAllProducts();
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

                            this.renderAdditionalDetails(order) +

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
     * Render the appropriate address' <td> tags according to the type of address.
     *
     * @param [object] address_details
     * @param [string] address_type_name
     * @returns {string}
     */
    renderAddress: function (address_details, address_type_name) {
        var line2 = address_details.line2 == null ? '' : address_details.line2 + '<br/>';

        return '<tr>' +
                    '<td>' + address_type_name + '</td>' +
                    '<td>' +
                        address_details.name +
                        '<br/>' +
                        address_details.line1 +
                        '<br/>' +
                        line2 +
                        address_details.city +
                        ', ' +
                        address_details.province +
                        ', ' +
                        address_details.postcode +
                        '<br/>' +
                        address_details.country +

                    '</td>' +
                '</tr>';
    },


    /**
     * Check if there are any additional details.
     * If there are, insert them in the summary table.
     *
     * @param order
     * @returns {string}
     */
    renderAdditionalDetails: function (order) {
        if (order.shipping_address != null) {
            return this.renderAddress(order.shipping_address, Localization.shipping_address) +
                    this.renderAddress(order.billing_address, Localization.billing_address) +
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
                    '</tr>';
        }
        else {
            return '';
        }
    },


    /**
     * Checks the status of the current order stored in _current_order cookie.
     *
     * If the order is paid and the call is made by the same user who passed the order,
     * we display a summary. Laravel takes care of the check, as this can raise security
     * concerns...
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
                success: function(order_details) {
                    if (order_details.status === 'pending') {
                        this.displayUnpaidOverlay();
                    }
                    else if (order_details.status === 'paid') {

                        // Display congratulation dimmer.
                        this.displayCongratulateOverlay(order_details);


                        // Register data for Google Analytics Ecommerce module (if GAE is available)
                        if (window.ga && ga.create) {
                            GAEAnalytics.register(order_details);
                        }


                        // Register data for piwik Ecommerce module (if available)
                        if (_paq) {
                            piwikAnalytics.trackEcommerceOrder(order_details);
                        }


                        // Remove products from cart
                        UtilityContainer.removeAllProducts();
                        UtilityContainer.setBadgeQuantity();


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
