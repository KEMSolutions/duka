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
                listItems += "<option value='" + key + "'>" + val + "</option>";
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
                    if (data[key].country === country[i]){
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
     *
     */
    callUpdateChosenSelects: function() {
        $("#country").on("change", function() {
            LocationContainer.updateChosenSelects($(this).val());
        });
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
 * @type {{passedVerification: Function, emailVerificationFailed: Function, postcodeVerificationFailed: Function, ajaxCall: Function, displayEstimatePanel: Function, fetchEstimate: Function, init: Function}}
 */
var estimateContainer = {
    /**
     * The email entered is not a valid one. Triggers appropriate class and animation (.has-error / .animated / .shake)
     *
     */
    emailVerificationFailed : function() {
        $("#customer_email").parent().addClass("has-error");
        $('#customer_email').addClass('animated shake').bind('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
            $(this).removeClass("animated");
            $(this).removeClass("shake");
            $(this).unbind();
        });

        $("#why_email").removeClass("hidden").addClass("animated bounceInRight").tooltip();
    },

    /**
     * The postcode entered is not a valid one. Triggers appropriate class and animation (.has-error / .animated / .shake)
     *
     */
    postcodeVerificationFailed : function() {
        $("#postcode").parent().addClass("has-error");
        $('#postcode').addClass('animated shake').bind('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
            $(this).removeClass("animated");
            $(this).removeClass("shake");
            $(this).unbind();
        });
    },

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

        for(var i = 0; i<data.services.length; i++)
        {
            var serviceDOM = "<tr data-service='" + data.services[i].service_code + "'>" +
                "<td>" + data.services[i].service_name + "</td>" +
                "<td>" + data.services[i].service_standard_expected_transit_time + "</td>" +
                "<td>" + data.services[i].service_standard_expected_delivery + "</td>" +
                "<td>" + data.services[i].price_due + "</td>" +
                "<td><input type='radio' name='shipment' class='shipping_method' data-cost='" + data.services[i].price_due + "' value='" + data.services[i].service_code + "' checked=''></td>";

            $("#estimate .table-striped").append(serviceDOM);
        }

        $("#estimateButton").removeClass("btn-three").addClass("btn-one").text(localizationContainer.estimateButton.val);

        UtilityContainer.scrollTopToEstimate();
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
 * @type {{getProductsFromSessionStorage: Function, getCountriesFromForm: Function, sanitizeEmail: Function, sanitizePostCode: Function, scrollTopToEstimate: Function}}
 */
var UtilityContainer = {
    /**
     * Utility function for getting all the products in sessionStorage.
     * Returns an array containing their id and their quantity.
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
                    productQuantity = product.quantity;

                res.push({
                    id: productId,
                    quantity: productQuantity
                });
            }
        }

        return res;
    },

    /**
     * Utility function fo getting the country and the postal code of the user.
     *
     * @returns {{country: (*|jQuery), postcode: (*|jQuery)}}
     */
    getCountriesFromForm : function() {
        return res = {
            "country" : $("#country").val(),
            "postcode" : $("#postcode").val()
        };
    },

    /**
     * Utility function to check if the user has really entered an email address.
     * From http://stackoverflow.com/a/46181
     *
     * @param email
     * @returns {boolean}
     */
    sanitizeEmail : function(email) {
        var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
        return re.test(email);
    },

    /**
     * Utility function to check if the user has entered a valid postcode.
     * Unfortunately there is no way on earth to know if the postcode is a valid one or not.
     * We only check if the postcode is not empty here.
     *
     * @param postcode
     * @returns {boolean}
     */
    sanitizePostCode : function(postcode) {
        return postcode == "" ? false : true;
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
     * If the email and the postcode entered are appropriate, make the ajax call to "/api/estimate".
     * If they are not, display the relevant error message(s)
     *
     */
    $("#estimateButton").on("click", function(e) {
        var email = $("#customer_email").val(),
            postcode = $("#postcode").val();

        e.preventDefault();

        if (UtilityContainer.sanitizeEmail(email) && (UtilityContainer.sanitizePostCode(postcode)))
        {
            $('#estimateButton').html('<i class="fa fa-spinner fa-spin"></i>');

            if($("#estimate .table-striped").children().length > 0) {
                $("#estimate .table-striped tbody").empty();
            }
            estimateContainer.ajaxCall();
        }
        else
        {
            if (!UtilityContainer.sanitizeEmail(email))
            {
                estimateContainer.emailVerificationFailed();
            }
            if (!UtilityContainer.sanitizePostCode(postcode))
            {
                estimateContainer.postcodeVerificationFailed();
            }
            if (UtilityContainer.sanitizeEmail(email) && $("#customer_email").parent().hasClass("has-error"))
            {
                $("#customer_email").parent().removeClass("has-error");
            }
            if (UtilityContainer.sanitizePostCode(postcode) && $("#postcode").parent().hasClass("has-error"))
            {
                $("#postcode").parent().removeClass("has-error");
            }
        }
    });
});