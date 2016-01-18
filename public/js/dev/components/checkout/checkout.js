/**
 * Component responsible for handling the checkout process.
 * @type {{validation: {validateFormFields: Function}, view: {autofillBillingInformation: Function, clearFields: Function, dispatchButtonsActions: Function, displayContactInformation: Function, displayShipmentMethodsAndPriceInformation: Function, fadeInBillingInformation: Function, fetchEstimate: Function, fetchPayment: Function, setInternationalFields: Function, updatePayment: Function}, actions: {createOrdersCookie: Function, getShipmentTaxes: Function, getTaxes: Function, placeOrderAjaxCall: Function, shipmentMethodsAjaxCall: Function}, bootstrap: {selectDefaultShipmentMethod: Function}, init: Function}}
 */
var checkoutContainer = {

    /**
     * Responsible for validating the form.
     *
     */
    validation: {

        /**
         * Validate the form by following a set of rules defined in validationRules.
         *
         */
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

                billingProvince: {
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
            };


            $(".form-checkout").form({
                fields: validationRules,
                inline: true,
                on    : 'blur',

                onSuccess: function (e) {
                    // We prevent default here, so that the form is not submitted when clicked on "next" (which is a submit button)
                    e.preventDefault();

                    // We are calling a function responsible for attributing each button's behaviour.
                    self.view.dispatchButtonsActions();
                }
            });

        }
    },

    /**
     * Responsible for handling the view aspect of checkout.
     *
     */
    view: {
        /**
         * Auto fill the billing information if the checkbox is ticked.
         *
         */
        autofillBillingInformation: function () {
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


        /**
         * Small utility function used to clear a field.
         *
         * @param node
         * @param fields
         */
        clearFields: function (node, fields) {
            node.find(fields).val("");
        },


        /**
         *  Defines a specific behaviour depending on which button is clicked after a form validation passes.
         *
         */
        dispatchButtonsActions: function () {
            var self = checkoutContainer;

            // Default actions triggered right after all validation passes and the next button is clicked.
            self.view.displayShipmentMethodsAndPriceInformation();
            self.actions.shipmentMethodsAjaxCall();

            // When clicked on the back button, display the contact information.
            $(".back-contact-info").on("click", function (e) {

                // Once again, we prevent default here since, oddly, every button inside a semantic-ui validated form
                // triggers a form submit.
                e.preventDefault();

                self.view.displayContactInformation(e);
            });

            // When clicked on the next button, we process the payment.
            $(".next-payment-process").on("click", function (e) {
                e.preventDefault();

                // Creates a redirecting dimmer.
                var dimmer = '<div class="ui page dimmer redirect-dimmer">' +
                    '<div class="content">' +
                    '<div class="center"><div class="ui text loader"><h3 class="ui header white">' + Localization.payment_redirect +'</h3></div></div>' +
                    '</div>' +
                    '</div>';

                $(dimmer).appendTo("body");
                $(".redirect-dimmer").dimmer("show");

                // Makes the ajax call.
                self.actions.placeOrderAjaxCall();

            });
        },


        /**
         * Displays the contact information.
         *
         * @param e
         */
        displayContactInformation: function (e) {
            $(".priceInformation").fadeOut(300);
            $(".shippingMethod").fadeOut(300, function() {
                $(".contactInformation").fadeIn();
            });

            // We need to stop event bubbling from the back button.
            // TBH, I didn't really look into it but one of these two should be enough...
            e.stopPropagation();
            e.stopImmediatePropagation();
        },


        /**
         * Fades out the contact information segments then fades in the shipping methods and price information segment.
         *
         */
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


        /**
         * Fades in the billing information segment.
         *
         */
        fadeInBillingInformation: function () {
            var self = checkoutContainer;

            $(".billing-checkbox").checkbox({
                onUnchecked: function () {
                    $(".billingInformation").hide().removeClass("hidden").fadeIn(400);
                    self.view.clearFields($(".billingInformation"), "input:text");
                },

                onChecked: function () {
                    $(".billingInformation").fadeOut(300, function () {
                        $(this).delay(300).addClass("hidden");
                    })
                }
            })
        },


        /**
         * Creates a table of available shipments populated with data from the api call.
         *
         * @param data
         */
        fetchEstimate: function (data) {
            var self = checkoutContainer;

            $("#shippingMethod-table-tbody").empty();

            for(var i = 0, shippingLength = data.shipping.services.length; i<shippingLength; i++)
            {
                var delivery = data.shipping.services[i].delivery != null ? data.shipping.services[i].delivery : " - ";

                var serviceDOM = "<tr data-service='" + data.shipping.services[i].method + "'>" +
                    "<td>" + data.shipping.services[i].name + "</td>" +
                    "<td>" + delivery  + "</td>" +
                    "<td>" + "$" + data.shipping.services[i].price.toFixed(2) + "</td>" +
                    "<td>" +
                    "<input " +
                    "type='radio' " +
                    "name='shipping' " +
                    "class='shipping_method' " +
                    "data-taxes='" + self.actions.getShipmentTaxes(data.shipping.services[i].method, data) + "' " +
                    "data-cost='" + data.shipping.services[i].price.toFixed(2) + "' " +
                    "data-value='" + data.shipping.services[i].method + "' " +
                    "value='" + btoa(JSON.stringify(data.shipping.services[i])) + "' >" +
                    "</td>";

                $("#shippingMethod-table-tbody").append(serviceDOM);

            }

            // After all shipments are appended, remove the loading sign on the appropriate segment.
            $(".shippingMethod .segment").removeClass("loading");

            // Select the default shipment method.
            self.bootstrap.selectDefaultShipmentMethod();
        },


        /**
         * Displays the various prices according to the chosen shipment method option.
         *
         * @param data
         */
        fetchPayment: function (data) {
            var subtotal = UtilityContainer.getProductsPriceFromLocalStorage().toFixed(2),
                priceTransport = $("input:radio.shipping_method:checked").data("cost"),
                taxes = checkoutContainer.actions.getTaxes(data) + parseFloat($("input:radio.shipping_method:checked").data("taxes")),
                total = parseFloat(subtotal) + parseFloat(priceTransport) + parseFloat(taxes);

            $("#price_subtotal").text("$" + subtotal);
            $("#price_transport").text("$" + priceTransport);
            $("#price_taxes").text("$" + taxes.toFixed(2));
            $("#price_total").text("$" + total.toFixed(2));

            $(".priceInformation .segment").removeClass("loading");
        },


        /**
         * Sets the province/state/region dropdown state according to the country entered.
         *
         * @param fields
         */
        setInternationalFields: function (fields) {
            fields.map(function(field) {
                field.on("change", function () {
                    if($(this).val() != "CA") {

                        // We assume the structure is not changing and stays like so:
                        // Country list is a sibling of province state region, both of them wrapped
                        // in a parent container.
                        $(this).parent().next().addClass("disabled");
                        $(this).parent().next().find("select").attr("disabled", true);
                    }
                    else {
                        $(this).parent().next().removeClass("disabled");
                        $(this).parent().next().find("select").attr("disabled", false);
                    }
                });
            });
        },


        /**
         * Update the payment panel with right values (shipment method)
         *
         * @param data
         */
        updatePayment : function(data) {
            var subtotal = parseFloat(UtilityContainer.getProductsPriceFromLocalStorage()).toFixed(2),
                priceTransport, taxes, total;

            $(".shipping_method").on("change", function() {
                priceTransport = $(this).data("cost");
                taxes = checkoutContainer.actions.getTaxes(data) + parseFloat($(this).data("taxes"));
                total = parseFloat(subtotal) + parseFloat(priceTransport) + parseFloat(taxes);

                $("#price_subtotal").text("$" + subtotal);
                $("#price_transport").text("$" + priceTransport);
                $("#price_taxes").text("$" + taxes.toFixed(2));
                $("#price_total").text("$" + total.toFixed(2));
            });
        }
    },

    /**
     * Responsible for the overall checkout behaviour.
     *
     */
    actions: {
        /**
         * Create a localStorage object containing the id, verification code and
         * redirection link of the order.
         *
         * @param data
         */
        createOrdersCookie: function(data) {
            var paymentId = data.id,
                paymentVerification = data.verification,
                payment_url = data.payment_details.payment_url;

            Cookies.set("_current_order", JSON.stringify( {
                id : paymentId,
                verification : paymentVerification,
                payment_url : payment_url
            }));
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

                    self.actions.createOrdersCookie(data);

                    //redirect the user to the checkout page if he backs from the payment page
                    history.pushState({data: data}, "Checkout ","/cart");

                    //Redirect to success url
                    window.location.replace(data.payment_details.payment_url);
                },
                error: function(xhr, e) {
                    console.log(xhr);
                    console.log(e);
                }
            });
        },


        /**
         * Makes an ajax call to api/estimate with the contact information.
         *
         * @param self
         */
        shipmentMethodsAjaxCall: function () {
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
                    checkoutContainer.view.fetchEstimate(data);
                    checkoutContainer.view.fetchPayment(data);

                    checkoutContainer.view.updatePayment(data);
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
        }
    },

    /**
     * Functions meant to be called for default behaviour.
     *
     */
    bootstrap: {
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
        }
    },

    /**
     * Register outside calling methods.
     *
     */
    init: function () {
        var self = checkoutContainer;
        self.validation.validateFormFields();
        self.view.fadeInBillingInformation();
        self.view.setInternationalFields([$("#shippingCountry"), $("#billingCountry")]);

        // This is where it all begins...
        // This automatically calls the form.onSuccess method upon validating all fields from the contact information
        // segment.
        $(".shipment-trigger").on("click", function (e) {
            if ($(".billing-checkbox").checkbox("is checked")) {
                self.view.autofillBillingInformation();
            }

            // We prevent default here, to avoid a double form submission.
            e.preventDefault();
        });
    }

}