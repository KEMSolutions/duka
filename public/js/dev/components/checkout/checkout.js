var checkoutContainer = {

    validateFormFields: function () {
        var self = checkoutContainer;

        var validationRules =
        {
            shippingFirstname: 'empty',
            shippingLastname : 'empty',
            shippingAddress1 : 'empty',
            shippingCity     : 'empty',
            shippingPostcode : 'empty',
            customer_email   : ['empty', 'email'],
            customer_phone   : ['empty', 'number']
        };

        $(".form-checkout").form({
            fields: validationRules,
            inline: true,
            on    : 'submit'
        });

        self.onFormSuccess(self.displayShipmentMethod());
    },

    fadeInBillingInformation: function () {
        $(".billing-checkbox").checkbox({
            onUnchecked: function () {
                $(".billingInformation").hide().removeClass("hidden").fadeIn(400);
            },

            onChecked: function () {
                $(".billingInformation").fadeOut(300, function () {
                    $(this).delay(300).addClass("hidden");
                })
            }
        })
    },


    displayShipmentMethod: function () {
        console.log("display shipment called");
    },

    onFormSuccess: function (callback) {
        $(".form-checkout").form({
            onSuccess: callback
        });
    },

    preventDefault: function () {
        $(".address-next").on("click", function (e) {
            e.preventDefault();
        });
    },


    init: function () {
        var self = checkoutContainer;
        self.validateFormFields();
        self.fadeInBillingInformation();
        self.preventDefault();




    }

}