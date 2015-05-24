/**
 * Object responsible for building the select list populating countries, provinces and states.
 *
 * @type {{populateCountry: Function, populateProvincesAndStates: Function, updateChosenSelects: Function, callUpdateChosenSelects: Function, init: Function}}
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
                $country = $("#country");

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
            $("#country").chosen();
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
                    $provinces = $("#province").find("[data-country='" + country[i] +"']");

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
            $('#province').removeAttr('disabled').trigger("chosen:updated");
        } else {
            $('#province').attr('disabled','disabled');
        }

        $('#province optgroup').attr('disabled','disabled');

        if (chosenCountry == 'CA' || chosenCountry == 'US' || chosenCountry == 'MX'){
            $('#province [data-country="' + chosenCountry + '"]').removeAttr('disabled');
        }

        $('#province').trigger('chosen:updated');
    },

    /**
     * Triggers updateChosenSelects($country)
     * This function will be registered in init().
     * TODO: Display appropriate provinces at the beginning of the process
     *
     */
    callUpdateChosenSelects: function() {
        $("#country").on("change", function() {
            LocationContainer.updateChosenSelects($(this).val());
        });

        //$("#country").trigger("change");
    },

    /**
     * Registering functions to be called outside of this object.
     *
     */
    init : function() {
        LocationContainer.populateCountry();
        LocationContainer.populateProvincesAndStates(["CA", "US", "MX"], function() {
            $("#province").chosen();
        });
        LocationContainer.callUpdateChosenSelects();
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
                products: UtilityContainer.getProductsFromSessionStorage(),
                shipping_address: UtilityContainer.getCountriesFromForm()
            },
            success: function(data) {
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
            if(data.shipping.services[i].service_code == serviceCode)
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
     * Populate the shipping methods table with the data received after the api call.
     *
     * @param data
     */
    fetchEstimate : function(data) {
        $(".has-error").removeClass("has-error");

        var email_value = $("#customer_email").val();
        var postcode_value = $("#postcode").val();
        var country_value = $("#country").val();

        for(var i = 0; i<data.shipping.services.length; i++)
        {
            var serviceDOM = "<tr data-service='" + data.shipping.services[i].service_code + "'>" +
                "<td>" + data.shipping.services[i].service_name + "</td>" +
                "<td>" + data.shipping.services[i].service_standard_expected_transit_time + "</td>" +
                "<td>" + data.shipping.services[i].price_due + "</td>" +
                "<td><input type='radio' name='shipment' class='shipping_method' data-taxes='" + estimateContainer.getShipmentTaxes(data.shipping.services[i].service_code, data) + "' data-cost='" + data.shipping.services[i].price_due + "' value='" + data.shipping.services[i].service_code + "' checked></td>";

            $("#estimate .table-striped").append(serviceDOM);
        }

        $("#estimateButton").removeClass("btn-three").addClass("btn-one").text(localizationContainer.estimateButton.val);

        UtilityContainer.scrollTopToEstimate();

        paymentContainer.init(data);
    },

    /**
     * Registers functions to be called outside of this object.
     *
     * @param data
     */
    init : function(data) {
        estimateContainer.displayEstimatePanel();
        estimateContainer.fetchEstimate(data);
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
        var subtotal = parseFloat(UtilityContainer.getProductsPriceFromSessionStorage()).toFixed(2),
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
        var subtotal = parseFloat(UtilityContainer.getProductsPriceFromSessionStorage()).toFixed(2),
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


/**
 * Utility container storing relevant locales to be manipulated in javascript.
 *
 * @type {{estimateButton: {val: (*|jQuery)}}}
 */
var localizationContainer = {
    estimateButton : {
        val : $("#estimateButton").text()
    }
}

/**
 * Utility object containing various utility functions...
 * Self Explanatory duh.
 *
 * @type {{getProductsFromSessionStorage: Function, getProductsPriceFromSessionStorage: Function, getCountriesFromForm: Function, scrollTopToEstimate: Function}}
 */
var UtilityContainer = {
    /**
     * Utility function for getting all the products in sessionStorage.
     * Returns an array containing their id, their quantity and their price.
     *
     * @returns {Array}
     */
    getProductsFromSessionStorage : function() {
        var res = [];

        for(var i =0; i<sessionStorage.length; i++)
        {
            if (sessionStorage.key(i).lastIndexOf("_", 0) === 0)
            {
                var product = JSON.parse(sessionStorage.getItem(sessionStorage.key(i))),
                    productId = product.product,
                    productQuantity = product.quantity,
                    productPrice = product.price;

                res.push({
                    id: productId,
                    quantity: productQuantity,
                    price : productPrice
                });
            }
        }

        return res;
    },

    /**
     * Utility function to get the total price from all products present in sessionStorage
     *
     * @returns {number}
     */
    getProductsPriceFromSessionStorage : function() {
        var total = 0,
            products = UtilityContainer.getProductsFromSessionStorage();

        for(var i=0; i<products.length; i++)
        {
            total += (products[i].price * products[i].quantity);
        }

        return total;
    },

    /**
     * Utility function fo getting the country, the postal code and the province (if any) of the user.
     *
     * @returns {{country: (*|jQuery), postcode: (*|jQuery), province: (*|jQuery)}}
     */
    getCountriesFromForm : function() {
        return res = {
            "country" : $("#country").val(),
            "postcode" : $("#postcode").val(),
            "province" : $("#province").val()
        };
    },


    /**
     * Utility function to scroll the body to the estimate table
     *
     */
    scrollTopToEstimate : function() {
        $('html, body').animate({
            scrollTop: $("#estimate").offset().top
        }, 1000);
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
            postcode = $("#postcode"),
            firstName = $("#shippingFirstname"),
            lastName = $("#shippingLastname"),
            address = $("#shippingAddress1"),
            city = $("#shippingCity"),
            phone = $("#shippingTel"),
            country = $("#country").val(),
            fields = [firstName, lastName, address, city, phone ];

        e.preventDefault();

        if (validateEmptyFields(fields) && validateEmail(email.val()) && validatePostCode(postcode.val(), country))
        {
            alert("hi");
        }
        else
        {
            addErrorClassToFields(fields);

            if(!validatePostCode(postcode.val(), country))
            {
                addErrorClassToFieldsWithRules(postcode);
            }

            if(!validateEmail(email.val()))
            {
                addErrorClassToFieldsWithRules(email);
                $("#why_email").removeClass("hidden").addClass("animated bounceInRight").tooltip();
            }

        }

        removeErrorClassFromFields(fields);
        removeErrorClassFromEmail(email);
        removeErrorClassFromPostcode(postcode, country);

    });



    function validateEmptyFields(emptyFields) {
        var passed = true;
        for(var i=0; i<emptyFields.length; i++) {
            if (emptyFields[i].val() == "")
            {
                passed = false;
                break;
            }
        }
        return passed;
    }

    function validateEmail(email) {
        var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
        return re.test(email);
    }

    function validatePostCode(postcode, country) {
        if (country == "CA")
            return postcode.match(/^[ABCEGHJKLMNPRSTVXY]{1}\d{1}[A-Z]{1} ?\d{1}[A-Z]{1}\d{1}$/i) ? true : false;
        else if (country == "US")
            return postcode.match(/^\d{5}(?:[-\s]\d{4})?$/) ? true : false;
        else
            return true;
    }

    function addErrorClassToFields(fields) {
        for(var i=0; i<fields.length; i++)
        {
            if (fields[i].val() == "")
            {
                fields[i].parent().addClass("has-error");
                fields[i].addClass('animated shake').bind('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
                    $(this).removeClass("animated");
                    $(this).removeClass("shake");
                    $(this).unbind();
                });
            }
        }
    }

        //AKA email and postcode
    function addErrorClassToFieldsWithRules(input) {
        input.parent().addClass("has-error");
        input.addClass('animated shake').bind('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
            $(this).removeClass("animated");
            $(this).removeClass("shake");
            $(this).unbind();
        });
    }

    function removeErrorClassFromFields(fields) {
        for(var i=0; i<fields.length; i++)
        {
            if (fields[i].val() != "" && fields[i].parent().hasClass("has-error"))
            {
                fields[i].parent().removeClass("has-error");
            }
        }
    }

    function removeErrorClassFromEmail(email) {
        if (validateEmail(email.val()) && email.parent().hasClass("has-error"))
            email.parent().removeClass("has-error");
    }

    function removeErrorClassFromPostcode(postcode, country) {
        if (validatePostCode(postcode.val(), country) && postcode.parent().hasClass("has-error"))
            postcode.parent().removeClass("has-error");
    }


});
