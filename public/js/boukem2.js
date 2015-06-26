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

/**
 * Object responsible for displaying the navigation header.
 * Will be present on all the pages, thus written here.
 *
 * @type {{md: {removeCartDescription: Function}, sm: {btnTransform_sm: Function}, init: Function}}
 */
var headerContainer = {
    /**
     * Desktop size
     *
     */
    md: {
        removeCartDescription : function() {
            if ($(window).width() <= 1195) {
                $("#nav-right #cart-description").text("");
                $("#nav-right").css("padding-bottom", "18px");
            }
        }
    },

    /**
     * Tablet size
     *
     */
    sm : {
        btnTransform_sm : function() {
            if ($(window).width() <= 934 && ($(window).width() >= 769)) {
                $(".row:first .btn").addClass("btn-sm");
                $("#searchBar").addClass("input-sm");
                $("#view-cart-wrapper").addClass("btn-xs btn-xs-btn-sm-height");
            }
        }
    },

    changeTextFromDropdown : function() {
        $(".dropdown-menu li a").click(function(){

            $(".btn:first-child").text($(this).text());
            $(".btn:first-child").val($(this).text());

        });
    },

    /**
     * Register functions in event handler (onload, onresize) to be called outside of this object.
     *
     */
    init: function () {
        var self = headerContainer;

        $(window).on("load resize", function() {
            self.md.removeCartDescription();
            self.sm.btnTransform_sm();
        });

        //self.changeTextFromDropdown();
    }
}

$(document).ready(function () {
    //fancy plugin for product page (quantity input)
    $(".input-qty").TouchSpin({
        initval: 1
    });

    //Initialize overlay plugin.
    paymentOverlayContainer.init();

    //Initialize navigation header.
    headerContainer.init();
});