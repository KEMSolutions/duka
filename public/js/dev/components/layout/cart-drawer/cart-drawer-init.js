/**
 * Component responsible for initializing the cart drawer feature.
 *
 * @type {{buyButtonClick: Function, getEstimateClick: Function, init: Function}}
 */
var cartDrawerInitContainer = {

    /**
     * Event triggered when a buy button is clicked.
     *
     */
    buyButtonClick : function () {
        $("body").on("click", ".buybutton", function() {

            // Call animateIn only if window width is greater than 768px.
            $(window).width() > 768 ? cartDisplayContainer.animateIn() : cartDisplayContainer.fadeInDimmer();

            cartLogicContainer.addItem(UtilityContainer.buyButton_to_Json($(this)));
            cartLogicContainer.storeItem(UtilityContainer.buyButton_to_Json($(this)));

            // We remove the "Your cart is empty" message at the top every time we add an item.
            $("#cart-items .empty-cart").addClass("invisible");
        });
    },

    /**
     * Event triggered when the Calculate button (to get a price estimate) is clicked.
     *
     */
    getEstimateClick: function () {
        $(".getEstimate").on("click", function() {
            //Fields validation + Empty cart validation.
            if(UtilityContainer.validatePostCode($("#postcode").val(), $(".price-estimate #country").val())
                && UtilityContainer.validateEmptyFields([$("#postcode")])
                && !UtilityContainer.validateEmptyCart()) {

                $(this).html('<i class="fa fa-spinner fa-spin"></i>');

                cartLogicContainer.ajaxCall();

            }
            else if (UtilityContainer.validateEmptyCart()) {
                $("#cart-items .empty-cart").removeClass("invisible");
            }
            else {
                UtilityContainer.addErrorClassToFieldsWithRules($("#postcode"));
            }
        });
    },

    init: function () {
        cartDisplayContainer.init();
        cartLogicContainer.init();
        cartDisplayContainer.setCartItemsHeight();

        var self = cartDrawerInitContainer;
        self.buyButtonClick();
        self.getEstimateClick();
    }

}
