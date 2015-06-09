/**
 * Object responsible for building the select list populating countries, provinces and states.
 *
 * @type {{populateCountry: Function, populateProvincesAndStates: Function, updateChosenSelects: Function, callUpdateChosenSelects: Function, autoFillBillingAddress: Function, init: Function}}
 */
var LocationContainer = {

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
            for(var i=0; i<country.length; i++) {
                var listItems = '',
                    $provinces = $(".province").find("[data-country='" + country[i] +"']");

                $.each(data, function(key, val) {
                    if (data[key].country === country[i] && data[key].short == "QC" ){
                        listItems += "<option value='" + data[key].short + "' selected>" + data[key].name + "</option>";
                    }
                    else if (data[key].country === country[i]){
                        listItems += "<option value='" + data[key].short + "'>" + data[key].name + "</option>";
                    }
                });
                $provinces.append(listItems);
            }
            callback();
        });
    },

    /**
     * Event function enabling or disabling postcode and province fields according to the chosen country
     *
     * @param chosenCountry
     */
    updateChosenSelects: function(chosenCountry) {
        if (chosenCountry == 'CA' || chosenCountry == 'US' || chosenCountry == "MX"){
            $('.province').removeAttr('disabled').trigger("chosen:updated");
        } else {
            $('.province').attr('disabled','disabled');
        }

        $('.province optgroup').attr('disabled','disabled');

        if (chosenCountry == 'CA' || chosenCountry == 'US' || chosenCountry == 'MX'){
            $('.province [data-country="' + chosenCountry + '"]').removeAttr('disabled');
        }

        $('.province').trigger('chosen:updated');
    },

    /**
     * Triggers updateChosenSelects($country)
     * This function will be registered in init().
     * TODO: Display appropriate provinces at the beginning of the process
     *
     */
    callUpdateChosenSelects: function() {
        $(".country").on("change", function() {
            LocationContainer.updateChosenSelects($(this).val());
        });
    },

    /**
     * Get user's billing address. By default shipping address = billing address.
     * Set the width of select list at the same time.
     *
     */
    getBillingAddress : function () {
        $(".billing-checkbox").on("change", function() {
            $(".form-billing .chosen-container").width($("#customer_email").outerWidth()-20);

            if (!this.checked) {
                $(".form-billing").hide().removeClass("hidden").fadeIn();
            }
            else {
                $(".form-billing").fadeOut(function() {
                    $(this).addClass("hidden");
                });
            }
        })
    },

    /**
     * Registering functions to be called outside of this object.
     *
     */
    init : function() {
        LocationContainer.populateCountry();
        LocationContainer.populateProvincesAndStates(["CA", "US", "MX"], function() {
            $(".province").chosen();
        });
        LocationContainer.callUpdateChosenSelects();
        LocationContainer.getBillingAddress();
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
            url: "/api/estimate",
            data: {
                success_url: "example.com",
                failure_url: "example.com",
                cancel_url: "example.com",
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
        $("#estimate").removeClass("hidden").addClass("animated fadeInDown");
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
    fetchEstimate : function(data) {
        $(".has-error").removeClass("has-error");

        var email_value = $("#customer_email").val();
        var postcode_value = $("#shippingPostcode").val();
        var country_value = $(".country").val();

        for(var i = 0; i<data.shipping.services.length; i++)
        {
            var serviceDOM = "<tr data-service='" + data.shipping.services[i].method + "'>" +
                "<td>" + data.shipping.services[i].name + "</td>" +
            "<td>" + data.shipping.services[i].transit + "</td>" +
            "<td>" + data.shipping.services[i].delivery + "</td>" +
            "<td>" + data.shipping.services[i].price + "</td>" +
            "<td><input type='radio' name='shipment' class='shipping_method' data-taxes='" + estimateContainer.getShipmentTaxes(data.shipping.services[i].method, data) + "' data-cost='" + data.shipping.services[i].price + "' value='" + data.shipping.services[i].method + "'></td>";

            $("#estimate .table-striped").append(serviceDOM);
        }

        $("#estimateButton").removeClass("btn-three").addClass("btn-one").text(localizationContainer.estimateButton.val);
        estimateContainer.selectDefaultShipmentMethod();

        estimateContainer.scrollTopToEstimate();

        paymentContainer.init(data);
    },

    selectDefaultShipmentMethod : function() {
        var defaultShipment = ["DOM.EP", "USA.TP", "INT.TP"],
            availableShipment = $("input[name=shipment]");

        for(var i=0; i<availableShipment.length; i++)
        {
            if (defaultShipment.indexOf(availableShipment[i].value) != -1)
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
        if (UtilityContainer.getProductsFromLocalStorage().length == 0)
        {
            location.reload();
        }
        else
        {
            estimateContainer.displayEstimatePanel();
            estimateContainer.fetchEstimate(data);
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
        $("#payment").removeClass("hidden").addClass("animated fadeInDown");
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
            priceTransport = parseFloat($(this).data("cost").toFixed(2));
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
        var taxes = 0;

        if (data.taxes.length != 0)
        {
           for(var i=0; i<data.taxes.length; i++)
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
    init : function(fields, email, postcode, country) {
        if (UtilityContainer.validateEmptyFields(fields) && UtilityContainer.validateEmail(email.val()) && UtilityContainer.validatePostCode(postcode.val(), country))
        {
            $('#estimateButton').html('<i class="fa fa-spinner fa-spin"></i>');

            if($("#estimate .table-striped").children().length > 0) {
                $("#estimate .table-striped tbody").empty();
            }

            estimateContainer.ajaxCall();
        }
        else
        {
            UtilityContainer.addErrorClassToFields(fields);

            if(!UtilityContainer.validatePostCode(postcode.val(), country))
            {
                UtilityContainer.addErrorClassToFieldsWithRules(postcode);
            }

            if(!UtilityContainer.validateEmail(email.val()))
            {
                UtilityContainer.addErrorClassToFieldsWithRules(email);
                $("#why_email").removeClass("hidden").addClass("animated bounceInRight").tooltip();
            }

        }

        UtilityContainer.removeErrorClassFromFields(fields);
        validationContainer.removeErrorClassFromEmail(email);
        validationContainer.removeErrorClassFromPostcode(postcode, country);
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
     * Populate select lists.
     *
     */
    LocationContainer.init();

    /**
     * Event triggered when the "Continue" button is hit.
     * If the input fields entered are appropriate, make the ajax call to "/api/estimate".
     * If they are not, display the relevant error message(s)
     *
     */
    $("#estimateButton").on("click", function(e) {
        var email = $("#customer_email"),
            postcode = $("#shippingPostcode"),
            firstName = $("#shippingFirstname"),
            lastName = $("#shippingLastname"),
            address1 = $("#shippingAddress1"),
            city = $("#shippingCity"),
            phone = $("#shippingTel"),
            country = $("#shippingCountry").val(),
            fields = [firstName, lastName, address1, city, phone ];

        e.preventDefault();

        validationContainer.init(fields, email, postcode, country);
    });
});

