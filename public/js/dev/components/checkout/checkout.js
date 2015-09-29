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

                self.displayShipmentMethods();
                self.ajaxCall();
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

    displayShipmentMethods: function () {

        var $contactInformation = $(".contactInformation");

        $contactInformation.addClass("animated fadeOutRight");

        $contactInformation.one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
            $(this).css("display", "none");

            // Fade the shipping method from the left.
            $(".shippingMethod").addClass("animated").removeClass("hidden").addClass("fadeInLeft");

            // Add a dimmer just in case shipment methods are not fetched yet.
            $(".shippingMethod-table").dimmer("show");
        });
    },


    ajaxCall: function () {
        $.ajax({
            type: "POST",
            url: ApiEndpoints.estimate,
            data: {
                email: $("#customer_email").val(),
                shipping: {},
                products: UtilityContainer.getProductsFromLocalStorage(),
                shipping_address: UtilityContainer.getShippingFromForm()
            },
            success: function(data) {
                checkoutContainer.fetchEstimate(data);
                console.log(data);
            },
            error: function(e, status) {
                if (e.status == 403){
                    // TODO: replace with an actual link
                    window.location.replace("/auth/login");
                    return;
                }
                $('#estimate').html('<div class="alert alert-danger">Une erreur est survenue. Veuillez vérifier les informations fournies.</div>');
            }
        });
    },

    /**
     * Get the relevant taxes according to the chosen shipping method.
     *
     * @param serviceCode
     * @param data
     * @returns {string}
     */
    getShipmentTaxes : function(serviceCode, data) {
        var taxes = 0;

        data.shipping.services.map(function(item) {
           if (item.method == serviceCode) {
               if (item.taxes.length != 0) {
                   item.taxes.map(function(taxes) {
                       taxes += taxes.amount;
                   });
               }
           }
        });

        return taxes.toFixed(2);
    },

    fetchEstimate: function (data) {
        var self = checkoutContainer;

        for(var i = 0, shippingLength = data.shipping.services.length; i<shippingLength; i++)
        {
            var serviceDOM = "<tr data-service='" + data.shipping.services[i].method + "'>" +
                "<td>" + data.shipping.services[i].name + "</td>" +
                "<td>" + data.shipping.services[i].delivery + "</td>" +
                "<td>" + "$" + data.shipping.services[i].price + "</td>" +
                "<td>" +
                "<input " +
                "type='radio' " +
                "name='shipping' " +
                "class='shipping_method' " +
                "data-taxes='" + self.getShipmentTaxes(data.shipping.services[i].method, data) + "' " +
                "data-cost='" + data.shipping.services[i].price + "' " +
                "data-value='" + data.shipping.services[i].method + "' " +
                "value='" + btoa(JSON.stringify(data.shipping.services[i])) + "' >" +
                "</td>";

            $("#shippingMethod-table-tbody").append(serviceDOM);

        }

        //Hide the dimmer on #shippinMethod segment
        $(".shippingMethod-table").dimmer("hide");
    },

    init: function () {
        var self = checkoutContainer;
        self.validateFormFields();
        self.fadeInBillingInformation();

        $(".shipment-trigger").on("click", function (e) {
            if ($(".billing-checkbox").checkbox("is checked")) {
                self.autofillBillingAddress();
            }

            e.preventDefault();
        });





    }

}