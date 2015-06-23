/**
 * Object responsible for building the select list populating countries, provinces and states.
 *
 * @type {{populateCountry: Function, populateProvincesAndStates: Function, updateChosenSelects: Function, callUpdateChosenSelects: Function, autoFillBillingAddress: Function, init: Function}}
 */
var locationContainer = {

    /**
     * Function to populate country list
     * Activates the chosen plugin on the country select list.
     *
     */
    populateCountry : function() {
        $.getJSON("/js/data/country-list.en.json", function(data) {
            var listItems = '',
                $country = $(".country");

            $.each(data, function(key, val) {
                if (key == "CA") {
                    listItems += "<option value='" + key + "' selected>" + val + "</option>";
                }
                else {
                    listItems += "<option value='" + key + "'>" + val + "</option>";
                }
            });
            $country.append(listItems);
        }).done(function() {
            $(".country").chosen();
        });
    },

    /**
     * Function to populate provinces and states
     * Activates the chosen plugin on the province select list.
     *
     * @param country
     * @param callback
     */
    populateProvincesAndStates : function (country, callback) {
        $.getJSON("/js/data/world-states.json", function(data) {
            for(var i= 0, length = country.length; i<length; i++) {
                var listItems = '',
                    $province = $(".province").find("[data-country='" + country[i] +"']");

                $.each(data, function(key, val) {
                    if (data[key].country === country[i] && data[key].short == "QC" ){
                        listItems += "<option value='" + data[key].short + "' selected>" + data[key].name + "</option>";
                    }
                    else if (data[key].country === country[i]){
                        listItems += "<option value='" + data[key].short + "'>" + data[key].name + "</option>";
                    }
                });
                $province.append(listItems);
            }
            callback();
        });
    },

    /**
     * Event function enabling or disabling postcode and province fields according to the chosen country and the provided input (shipping or billing)
     *
     * @param chosenCountry
     * @param input
     */
    updateChosenSelects: function(chosenCountry, input) {
        if (chosenCountry == 'CA' || chosenCountry == 'US' || chosenCountry == "MX"){
            $(input).removeAttr('disabled').trigger("chosen:updated");
        } else {
            $(input).attr('disabled','disabled');
        }

        $(input + ' optgroup').attr('disabled','disabled');

        if (chosenCountry == 'CA' || chosenCountry == 'US' || chosenCountry == 'MX'){
            $(input + ' [data-country="' + chosenCountry + '"]').removeAttr('disabled');

        }

        $(input).trigger('chosen:updated');
    },

    /**
     * Triggers updateChosenSelects($country, $input)
     * This function will be registered in init().
     *
     */
    callUpdateChosenSelects: function(self) {
        $("#billingCountry").on("change", function() {
            self.updateChosenSelects($(this).val(), "#billingProvince");
        });

        $("#shippingCountry").on("change", function() {
            self.updateChosenSelects($(this).val(), "#shippingProvince");
        });
    },

    /**
     * Registering functions to be called outside of this object.
     *
     */
    init : function() {
        var self = locationContainer;

        self.populateCountry();
        self.populateProvincesAndStates(["CA", "US", "MX"], function() {
            $(".province").chosen();
        });
        self.callUpdateChosenSelects(self);

    }
}




/**
 * Object responsible for handling billing information.
 *
 * @type {{autoFillBillingAddress: Function, setDifferentBillingAddress: Function, clearBillingAddress: Function, init: Function}}
 */
var billingContainer = {

    /**
     * Fill the billing address with the shipping address.
     * First parameter is an array of all fields that only need basic validation (empty or not)
     * Second parameter is an input that requires more advanced verification (postcode)
     *
     *
     * @param fields
     * @param fieldWithRules
     */
    autoFillBillingAddress : function(fields, fieldWithRules) {
        if($(".billing-checkbox").is(":checked"))
        {
            //We assume here that fieldWithRules is the shipping postcode.
            $("#billing" + fieldWithRules[0].id.substring("shipping".length, fieldWithRules[0].id.length)).val(fieldWithRules[0].value);

            for(var i= 0, length = fields.length; i<length; i++) {
                //check if the id has the string "shipping".
                //if it does, delete the shipping prefix and replace it by billing.
                //Create a new jquery selector and fill it with the value of the shipping one.
                if (fields[i][0].id.indexOf("shipping") > -1) {
                    var genericInput = fields[i][0].id.substring("shipping".length, fields[i][0].id.length);
                    $("#billing" + genericInput).val(fields[i][0].value);
                }
            }
        }
    },

    /**
     * Get user's billing address. By default shipping address = billing address.
     * Set the width of select list at the same time.
     *
     */
    setDifferentBillingAddress : function (self) {
        $(".billing-checkbox").on("change", function() {
            $(".form-billing .chosen-container").width($("#customer_email").outerWidth()-20);

            if (!this.checked) {
                $(".form-billing").hide().removeClass("hidden").fadeIn();
                self.clearBillingAddress();
            }
            else {
                $(".form-billing").fadeOut(function() {
                    $(this).addClass("hidden");
                });
            }
        })
    },

    /**
     * Clear the billing form.
     *
     */
    clearBillingAddress : function() {
          if ($(".form-billing input").val() != "") {
              $(".form-billing input").val() == "";
          }
    },

    init: function() {
        var self = billingContainer;

        self.setDifferentBillingAddress(self);
    }
}






/**
 * Object responsible for handling the estimation of user's purchase.
 *
 * @type {{ajaxCall: Function, getShipmentTaxes: Function, displayEstimatePanel: Function, fetchEstimate: Function, init: Function}}
 */
var estimateContainer = {

    /**
     * Ajax call to /api/estimate after all verifications have passed.
     *
     */
    ajaxCall : function() {
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
                console.log(data);
                estimateContainer.init(data);
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
     * Display the estimate panel
     *
     */
    displayEstimatePanel : function() {
        $("#estimate").removeClass("hidden fadeOutUp").addClass("animated fadeInDown");
    },
    
    /**
     * Utility function to scroll the body to the estimate table
     *
     */
    scrollTopToEstimate : function() {
        $('html, body').animate({
            scrollTop: $("#estimate").offset().top
        }, 1000);
    },

    /**
     * Populate the shipping methods table with the data received after the api call.
     *
     * @param data
     */
    fetchEstimate : function(data, self) {
        $(".has-error").removeClass("has-error");

        var email_value = $("#customer_email").val();
        var postcode_value = $("#shippingPostcode").val();
        var country_value = $(".country").val();

        for(var i = 0, shippingLength = data.shipping.services.length; i<shippingLength; i++)
        {
            var serviceDOM = "<tr data-service='" + data.shipping.services[i].method + "'>" +
                "<td>" + data.shipping.services[i].name + "</td>" +
            "<td>" + data.shipping.services[i].transit + "</td>" +
            "<td>" + data.shipping.services[i].delivery + "</td>" +
            "<td>" + data.shipping.services[i].price + "</td>" +
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

            $("#estimate .table-striped").append(serviceDOM);
        }

        $("#estimateButton").removeClass("btn-three").addClass("btn-one").text(Localization.continue);
        self.selectDefaultShipmentMethod();

        self.scrollTopToEstimate();

        paymentContainer.init(data);
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

    /**
     * Registers functions to be called outside of this object.
     *
     * @param data
     */
    init : function(data) {
        var self = estimateContainer;

        if (UtilityContainer.getProductsFromLocalStorage().length == 0)
        {
            location.reload();
        }
        else
        {
            self.displayEstimatePanel();
            self.fetchEstimate(data, self);
        }
    }

}





/**
 * Object responsible for handling the payment panel.
 *
 * @type {{displayPaymentPanel: Function, initPaymentPanel: Function, updatePaymentPanel: Function, getTaxes: Function, init: Function}}
 */
var paymentContainer = {
    /**
     * Displays the Payment panel.
     *
     */
    displayPaymentPanel : function() {
        $("#payment").removeClass("hidden fadeOutUp").addClass("animated fadeInDown");
        $("#checkoutButton").addClass("animated rubberBand");
    },

    /**
     * Populate the payment panel with default values.
     *
     * @param data
     */
    initPaymentPanel : function(data) {
        var subtotal = parseFloat(UtilityContainer.getProductsPriceFromLocalStorage()).toFixed(2),
            priceTransport = $("input:radio.shipping_method:checked").data("cost"),
            taxes = paymentContainer.getTaxes(data) + parseFloat($("input:radio.shipping_method:checked").data("taxes")),
            total = parseFloat(subtotal) + parseFloat(priceTransport) + parseFloat(taxes);

        $("#price_subtotal").text(subtotal);
        $("#price_transport").text(priceTransport);
        $("#price_taxes").text(taxes.toFixed(2));
        $("#price_total").text(total.toFixed(2));
    },

    /**
     * Update the payment panel with right values (shipping method)
     *
     * @param data
     */
    updatePaymentPanel : function(data) {
        var subtotal = parseFloat(UtilityContainer.getProductsPriceFromLocalStorage()).toFixed(2),
            priceTransport, taxes;

        $(".shipping_method").on("change", function() {
            priceTransport = $(this).data("cost");
            taxes = paymentContainer.getTaxes(data) + parseFloat($(this).data("taxes"));
            total = parseFloat(subtotal) + parseFloat(priceTransport) + parseFloat(taxes);

            $("#price_subtotal").text(subtotal);
            $("#price_transport").text(priceTransport);
            $("#price_taxes").text(taxes.toFixed(2));
            $("#price_total").text(total.toFixed(2));
        });
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
        return taxes;
    },

    /**
     * Register methods for outside calling.
     *
     * @param data
     */
    init : function(data) {
        paymentContainer.displayPaymentPanel();
        paymentContainer.initPaymentPanel(data);
        paymentContainer.updatePaymentPanel(data);

        checkoutLogicContainer.init();
    }
}

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
        // if estimate is not displayed, it means we do not need to mark this as an update.
        if ($("#estimate").css("display") != "none") {
            $("#estimateButton").removeClass("btn-one animated").addClass("animated rubberBand btn-three").text(Localization.update);
        }
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



var validationContainer = {

    removeErrorClassFromEmail: function(email) {
        if (UtilityContainer.validateEmail(email.val()) && email.parent().hasClass("has-error"))
            email.parent().removeClass("has-error");
    },

    removeErrorClassFromPostcode: function(postcode, country) {
        if (UtilityContainer.validatePostCode(postcode.val(), country) && postcode.parent().hasClass("has-error"))
            postcode.parent().removeClass("has-error");
    },

    /**
     * If all validation pass, spin the button, clean the shipment table and trigger the ajax call.
     * If there are errors, warn the users about which inputs is faulty.
     *
     * @param fields
     * @param email
     * @param postcode
     * @param country
     */
    init : function(fields, email, shippingInformation, billingInformation) {
        var self = validationContainer;

        if (UtilityContainer.validateEmptyFields(fields)
            && UtilityContainer.validateEmail(email.val())
            && UtilityContainer.validatePostCode(shippingInformation.postcode, shippingInformation.country)
            && UtilityContainer.validatePostCode(billingInformation.postcode, billingInformation.country))
        {
            $('#estimateButton').html('<i class="fa fa-spinner fa-spin"></i>');

            //delete previously uploaded shipping method (if any)
            if($("#estimate .table-striped").children().length > 0) {
                $("#estimate .table-striped tbody").empty();
            }

            estimateContainer.ajaxCall();
        }
        else
        {
            UtilityContainer.addErrorClassToFields(fields);

            if(!UtilityContainer.validatePostCode(shippingInformation.postcode, shippingInformation.country))
            {
                UtilityContainer.addErrorClassToFieldsWithRules(shippingInformation.postcodeInput);
            }

            if(!UtilityContainer.validatePostCode(billingInformation.postcode, billingInformation.country))
            {
                UtilityContainer.addErrorClassToFieldsWithRules(billingInformation.postcodeInput);
            }

            if(!UtilityContainer.validateEmail(email.val()))
            {
                UtilityContainer.addErrorClassToFieldsWithRules(email);
                $("#why_email").removeClass("hidden").addClass("animated bounceInRight").tooltip();
            }

        }

        UtilityContainer.removeErrorClassFromFields(fields);
        self.removeErrorClassFromEmail(email);
        self.removeErrorClassFromPostcode(shippingInformation.postcodeInput, shippingInformation.country);
        self.removeErrorClassFromPostcode(billingInformation.postcodeInput, billingInformation.country);
    }
}





$(document).ready(function() {
    /**
     * Sets up the ajax token for all ajax requests
     *
     */
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    /**
     * Populate select lists and set up billing address container behaviour.
     * Set the form focus on first name field
     *
     */
    locationContainer.init();
    billingContainer.init();
    $("#shippingFirstname").focus();

    /**
     * Event triggered when the "Continue" button is hit.
     * If the input fields entered are appropriate, make the ajax call to "/api/estimate".
     * If they are not, display the relevant error message(s)
     *
     */
    $("#estimateButton").on("click", function(e) {
        var email = $("#customer_email"),
            phone = $("#customer_phone"),
            shippingFirstName = $("#shippingFirstname"),
            shippingLastName = $("#shippingLastname"),
            shippingAddress1 = $("#shippingAddress1"),
            shippingCity = $("#shippingCity"),
            shippingCountry = $("#shippingCountry").val(),
            shippingPostcode = $("#shippingPostcode"),
            billingFirstName = $("#billingFirstname"),
            billingLastName = $("#billingLastname"),
            billingAddress1 = $("#billingAddress1"),
            billingCity = $("#billingCity"),
            billingCountry = $("#billingCountry").val(),
            billingPostcode = ("#billingPostcode"),
            shippingInformation = {
                "country" : shippingCountry,
                "postcode" : $("#shippingPostcode").val(),
                "postcodeInput" : $("#shippingPostcode")
            },
            fields = [
                shippingFirstName,
                shippingLastName,
                shippingAddress1,
                shippingCity,
                billingFirstName,
                billingLastName,
                billingAddress1,
                billingCity,
                email,
                phone
            ];

        e.preventDefault();

        //Auto fill billing address if checkbox is checked.
        billingContainer.autoFillBillingAddress(fields, shippingInformation.postcodeInput);

        //Build the billing information object (from auto fill or entered by hand)
        var billingInformation = {
                "country" : billingCountry,
                "postcode" : $("#billingPostcode").val(),
                "postcodeInput" : $("#billingPostcode")
            };

        //Validate all fields and make the ajax call!
        validationContainer.init(fields, email, shippingInformation, billingInformation);
    });
});

