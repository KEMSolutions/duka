/**
 * Object responsible for handling the overall logic of the checkout process.
 * After clicking on "Proceed to checkout", create a cookie and make an ajax call to get all the data before redirecting the user to the payment page.
 *
 * When a user changes the quantity or deletes an item, fadeOut the shipping estimate and payment panel. Replace the Continue button with "Update".
 *
 * @type {{createOrdersCookie: Function, placeOrderAjaxCall: Function, init: Function}}
 */
var checkoutLogicContainer = {

    /**
     * Create a localStorage object containing the id and the verification code.
     *
     * @param data
     */
    createOrdersCookie: function(data) {
        var paymentId = data.id,
            paymentVerification = data.verification;

        Cookies.set("_unpaid_orders", JSON.stringify( {
            id : paymentId,
            verification : paymentVerification
        }));
    },

    /**
     * Makes an ajax call to api/orders with the values from the form
     *
     * @param self
     */
    placeOrderAjaxCall: function(self) {
        $.ajax({
            method: "POST",
            url: ApiEndpoints.placeOrder,
            data: $("#cart_form").serialize(),
            cache: false,
            success: function(data) {
                console.log(data);

                self.createOrdersCookie(data);

                //redirect the user to the checkout page if he backs from the payment page
                history.pushState({data: data}, "Checkout ","/dev/cart");

                //Redirect to success url
                window.location.replace(data.payment_details.payment_url);
            },
            error: function(xhr, e) {
                console.log(xhr);
                console.log(e);
            }
        })

    },

    /**
     * Hide the panels by fading them up then adding a hidden class.
     *
     * @param self
     */
    hidePanels: function (self) {
        $(".quantity").on("change", function () {
            UtilityContainer.addFadeOutUpClass($("#estimate"));
            UtilityContainer.addFadeOutUpClass($("#payment"));

            self.updateEstimateButtonValue();
        });

        $(".close-button").on("click", function() {
            UtilityContainer.addFadeOutUpClass($("#estimate"));
            UtilityContainer.addFadeOutUpClass($("#payment"));

            self.updateEstimateButtonValue();
        });
    },

    /**
     * Updates the estimate button with "Update" while making it bouncy ;)
     *
     */
    updateEstimateButtonValue: function() {
        $("#estimateButton")
            .removeClass("btn-one animated rubberBand")
            .addClass("animated rubberBand btn-three")
            .text(Localization.update);
    },

    /**
     * Register methods for outside calling.
     *
     */
    init: function() {
        var self = checkoutLogicContainer;

        $("#checkoutButton").on("click", function (e) {
            e.preventDefault();

            $('#checkoutButton').html('<i class="fa fa-spinner fa-spin"></i>');

            self.placeOrderAjaxCall(self);

        });

        self.hidePanels(self);
    }
}