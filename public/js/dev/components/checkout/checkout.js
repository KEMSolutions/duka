var checkoutContainer = {

    validateFormFields: function () {
        var self = checkoutContainer;

        var validationRules =
        {
            shippingFirstname: {
                identifier: 'shippingFirstname',
                rules: [
                    {
                        type   : 'empty',
                        prompt : Localization.validation_name
                    }
                ]
            },

            shippingLastname: {
                identifier: 'shippingLastname',
                rules: [
                    {
                        type   : 'empty',
                        prompt : Localization.validation_name
                    }
                ]
            },

            shippingAddress1: {
                identifier: 'shippingAddress1',
                rules: [
                    {
                        type   : 'empty',
                        prompt : Localization.validation_address_shipping
                    }
                ]
            },


            shippingCountry: {
                identifier: 'shippingCountry',
                rules: [
                    {
                        type   : 'empty',
                        prompt : Localization.validation_address_shipping
                    }
                ]
            },

            shippingProvince: {
                identifier: 'shippingProvince',
                rules: [
                    {
                        type   : 'empty',
                        prompt : Localization.validation_address_shipping
                    }
                ]
            },

            shippingCity: {
                identifier: 'shippingCity',
                rules: [
                    {
                        type   : 'empty',
                        prompt : Localization.validation_city_shipping
                    }
                ]
            },

            shippingPostcode: {
                identifier: 'shippingPostcode',
                rules: [
                    {
                        type   : 'empty',
                        prompt : Localization.validation_post_shipping
                    }
                ]
            },

            customer_email: {
                identifier: 'customer_email',
                rules: [
                    {
                        type   : 'empty',
                        prompt : Localization.validation_email
                    },
                    {
                        type   : 'email',
                        prompt : Localization.validation_valid_email
                    }
                ]
            },

            customer_phone: {
                identifier: 'customer_phone',
                rules: [
                    {
                        type   : 'empty',
                        prompt : Localization.validation_phone
                    }
                ]
            },

            billingFirstname: {
                identifier: 'billingFirstname',
                rules: [
                    {
                        type   : 'empty',
                        prompt : Localization.validation_name
                    }
                ]
            },

            billingLastname: {
                identifier: 'billingLastname',
                rules: [
                    {
                        type   : 'empty',
                        prompt : Localization.validation_name
                    }
                ]
            },

            billingAddress1: {
                identifier: 'billingAddress1',
                rules: [
                    {
                        type   : 'empty',
                        prompt : Localization.validation_address_billing
                    }
                ]
            },

            billingCountry: {
                identifier: 'billingCountry',
                rules: [
                    {
                        type   : 'empty',
                        prompt : Localization.validation_address_shipping
                    }
                ]
            },

            billingCountry: {
                identifier: 'billingProvince',
                rules: [
                    {
                        type   : 'empty',
                        prompt : Localization.validation_address_shipping
                    }
                ]
            },

            billingCity: {
                identifier: 'billingCity',
                rules: [
                    {
                        type   : 'empty',
                        prompt : Localization.validation_city_billing
                    }
                ]
            },

            billingPostcode: {
                identifier: 'billingPostcode',
                rules: [
                    {
                        type   : 'empty',
                        prompt : Localization.validation_post_billing
                    }
                ]
            }
        }


        $(".form-checkout").form({
            fields: validationRules,
            inline: true,
            on    : 'blur',

            onSuccess: function (e) {
                e.preventDefault();

                self.dispatchButtonsActions();
            }
        });

    },

    dispatchButtonsActions: function () {
        var self = checkoutContainer;

        self.displayShipmentMethodsAndPriceInformation();
        self.ajaxCall();

        $(".back-contact-info").on("click", function (e) {
            e.preventDefault();

            self.displayContactInformation(e);
        });

        $(".next-payment-process").on("click", function (e) {
            e.preventDefault();

            var dimmer = '<div class="ui page dimmer redirect-dimmer">' +
                '<div class="content">' +
                '<div class="center"><h3 class="ui header">' + Localization.payment_redirect +'</h3></div>' +
                '</div>' +
                '</div>';

            $(dimmer).appendTo("body");
            $(".redirect-dimmer").dimmer("show");

            self.placeOrderAjaxCall();
        });
    },

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
    placeOrderAjaxCall: function() {
        $.ajax({
            method: "POST",
            url: ApiEndpoints.placeOrder,
            data: $("#cart_form").serialize(),
            cache: false,
            success: function(data) {
                var self = checkoutContainer;

                //self.createOrdersCookie(data);

                //redirect the user to the checkout page if he backs from the payment page
                history.pushState({data: data}, "Checkout ","/cart");

                //Redirect to success url
                window.location.replace(data.payment_details.payment_url);
            },
            error: function(xhr, e) {
                console.log(xhr);
                console.log(e);
            }
        })

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

    displayShipmentMethodsAndPriceInformation: function () {

        var $contactInformation = $(".contactInformation"),
            $shippingMethod = $(".shippingMethod"),
            $priceInformation = $(".priceInformation");

        $contactInformation.fadeOut(300, function() {
            $(".shippingMethod .loadable-segment, .priceInformation .loadable-segment").addClass("loading");

            //Fade the shipping methods and price info from the left.
            $shippingMethod.show(0, function() {
                $(this).removeClass("hidden animated fadeInLeft").addClass("animated fadeInLeft");
            });


            $priceInformation.show(0, function() {
                $(this).removeClass("hidden animated fadeInLeft").addClass("animated fadeInLeft");
            });
        });


    },

    displayContactInformation: function (e) {
        var $contactInformation = $(".contactInformation"),
            $shippingMethod = $(".shippingMethod"),
            $priceInformation = $(".priceInformation");

        $priceInformation.fadeOut(300);
        $shippingMethod.fadeOut(300, function() {
            $contactInformation.fadeIn();
        });

        e.stopPropagation();
        e.stopImmediatePropagation();
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
                checkoutContainer.fetchPayment(data);

                checkoutContainer.updatePayment(data);
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
        var taxes = 0,
            self = checkoutContainer;

        for(var i=0; i<data.shipping.services.length; i++)
        {
            if(data.shipping.services[i].method == serviceCode)
            {
                if (data.shipping.services[i].taxes.length != 0)
                {
                    for(var j=0; j<data.shipping.services[i].taxes.length; j++)
                    {
                        taxes += data.shipping.services[i].taxes[j].amount;
                    }
                }
            }
        }

        return taxes.toFixed(2);
    },

    /**
     * Get the total taxes (TPS/TVQ or TVH or TPS or null) + shipping method taxes.
     *
     * @param data
     * @returns {number}
     */
    getTaxes : function(data) {
        var taxes = 0,
            dataTaxesLength = data.taxes.length;

        if (dataTaxesLength != 0)
        {
            for(var i=0; i<dataTaxesLength; i++)
            {
                taxes += data.taxes[i].amount;
            }
        }
        return parseFloat(taxes);
    },

    fetchEstimate: function (data) {
        var self = checkoutContainer;

        $("#shippingMethod-table-tbody").empty();

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

        $(".shippingMethod .segment").removeClass("loading");
        self.selectDefaultShipmentMethod();

    },

    fetchPayment: function (data) {
        var subtotal = UtilityContainer.getProductsPriceFromLocalStorage().toFixed(2),
            priceTransport = $("input:radio.shipping_method:checked").data("cost"),
            taxes = checkoutContainer.getTaxes(data) + parseFloat($("input:radio.shipping_method:checked").data("taxes")),
            total = parseFloat(subtotal) + parseFloat(priceTransport) + parseFloat(taxes);

        $("#price_subtotal").text("$" + subtotal);
        $("#price_transport").text("$" + priceTransport);
        $("#price_taxes").text("$" + taxes.toFixed(2));
        $("#price_total").text("$" + total.toFixed(2));

        $(".priceInformation .segment").removeClass("loading");
    },

    /**
     * Update the payment panel with right values (shipping method)
     *
     * @param data
     */
    updatePayment : function(data) {
        var subtotal = parseFloat(UtilityContainer.getProductsPriceFromLocalStorage()).toFixed(2),
            priceTransport, taxes, total;

        $(".shipping_method").on("change", function() {
            priceTransport = $(this).data("cost");
            taxes = checkoutContainer.getTaxes(data) + parseFloat($(this).data("taxes"));
            total = parseFloat(subtotal) + parseFloat(priceTransport) + parseFloat(taxes);

            $("#price_subtotal").text("$" + subtotal);
            $("#price_transport").text("$" + priceTransport);
            $("#price_taxes").text("$" + taxes.toFixed(2));
            $("#price_total").text("$" + total.toFixed(2));
        });
    },

    /**
     * Select the default shipment method from a predefined list.
     *
     */
    selectDefaultShipmentMethod : function() {
        var defaultShipment = ["DOM.EP", "USA.TP", "INT.TP"],
            availableShipment = $("input[name=shipping]");

        for(var i= 0, length = availableShipment.length; i<length; i++)
        {
            if (defaultShipment.indexOf(availableShipment[i].dataset.value) != -1)
            {
                availableShipment[i].checked = true;
            }
        }
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