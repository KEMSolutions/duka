$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    cartDisplayContainer.init();
    cartLogicContainer.init();
    cartDisplayContainer.setCartItemsHeight();

    $(".buybutton").click(function() {
        cartDisplayContainer.animateIn();
        cartLogicContainer.addItem(cartLogicContainer.button_to_Json($(this)));
        cartLogicContainer.storeItem(cartLogicContainer.button_to_Json($(this)));

        //We remove the "Your cart is empty" message at the top every time we add an item.
        //TODO : Maybe improve it?
        $("#cart-items .empty-cart").addClass("hidden");
    });


    $(".getEstimate").on("click", function() {
        //Fields validation + Empty cart validation.
        if(UtilityContainer.validatePostCode($("#postcode").val(), $(".price-estimate #country").val())
            && UtilityContainer.validateEmptyFields([$("#postcode")])
            && !UtilityContainer.validateEmptyCart()) {

            $(this).html('<i class="fa fa-spinner fa-spin"></i>');

            cartLogicContainer.ajaxCall();

        }
        else if (UtilityContainer.validateEmptyCart()) {
            $("#cart-items .empty-cart").removeClass("hidden");
        }
        else {
            UtilityContainer.addErrorClassToFieldsWithRules($("#postcode"));
        }
    });

});