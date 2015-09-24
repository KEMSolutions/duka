var checkoutContainer = {

    validateFormFields: function () {
        $(".form-checkout").form({
            fields: {
                shippingFirstname: 'empty',
                shippingLastname : 'empty',
                shippingAddress1 : 'empty',
                shippingCity     : 'empty',
                shippingPostcode : 'empty',
                customer_email   : ['empty', 'email'],
                customer_phone   : ['empty', 'number']
            }
        });

        console.log("validated");
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


    init: function () {
        var self = checkoutContainer;
        self.validateFormFields();
        self.fadeInBillingInformation();


        $(".address-next").on("click", function (e) {
            //e.preventDefault();


            console.log("clicked");
        })


    }

}