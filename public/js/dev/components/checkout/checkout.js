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
            customer_phone   : ['empty', 'number'],
            billingFirstname: 'empty',
            billingLastname : 'empty',
            billingAddress1 : 'empty',
            billingCity     : 'empty',
            billingPostcode : 'empty'
        };

        $(".form-checkout").form({
            fields: validationRules,
            inline: true,
            on    : 'blur',

            onSuccess: function (e) {
                e.preventDefault();

                self.displayShipmentMethod();
                console.log("success");
            }
        });

    },

    autofillBillingAddress: function () {
        var shippingFirstname = $("#shippingFirstname").val(),
            shippingLastname = $("#shippingLastname").val(),
            shippingAddress1 = $("#shippingAddress1").val(),
            shippingCity = $("#shippingCity").val(),
            shippingPostcode = $("#shippingPostcode").val();

        $(".form-checkout").form('set values', {
            billingFirstname: shippingFirstname,
            billingLastname : shippingLastname,
            billingAddress1 : shippingAddress1,
            billingCity     : shippingCity,
            billingPostcode : shippingPostcode
        });

    },

    clearFields: function (node, fields) {
        node.find(fields).val("");
    },

    fadeInBillingInformation: function () {
        var self = checkoutContainer;

        $(".billing-checkbox").checkbox({
            onUnchecked: function () {
                $(".billingInformation").hide().removeClass("hidden").fadeIn(400);
                self.clearFields($(".billingInformation"), "input:text");
            },

            onChecked: function () {
                $(".billingInformation").fadeOut(300, function () {
                    $(this).delay(300).addClass("hidden");
                })
            }
        })
    },


    displayShipmentMethod: function () {
        var self = checkoutContainer;

        console.log("display shipment called");
    },



    init: function () {
        var self = checkoutContainer;
        self.validateFormFields();
        self.fadeInBillingInformation();

        $(".shipment-trigger").on("click", function () {
            if ($(".billing-checkbox").checkbox("is checked")) {
                self.autofillBillingAddress();
            }
        });





    }

}