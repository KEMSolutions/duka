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
        $("#cancelOrder").on("click", function() {
            Cookies.remove("_unpaid_orders");

            $("#cancelledOrder .jumbotron").fadeOut();

            UtilityContainer.removeAllProductsFromLocalStorage();

            window.location.replace("/");
        });
    },

    init : function() {
        var self = paymentOverlayContainer;

        self.cancelOrder();
    }
}

$(document).ready(function () {
    //fancy plugin for product page (quantity input)
    $(".input-qty").TouchSpin({
        initval: 1
    });

    paymentOverlayContainer.init();

});