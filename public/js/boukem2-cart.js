$(document).ready(function() {
    /*
     Function to populate country list
     Activates the chosen plugin on the country select list.
     */
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

    /*
     Function to populate provinces and states
     Activates the chosen plugin on the province select list.
     */
    function populateProvincesAndStates(country, callback) {
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
    }

    /*
     Call the populateProvincesAndStates function with an array of countries.
     Callback to activate the chosen plugin.
     */
    populateProvincesAndStates(["CA", "US", "MX"], function() {
        $("#province").chosen();
    });


    /*
    Function to enable or disable fields according to the chosen country.
     */
    function updateChosenSelects(chosenCountry) {
        if (chosenCountry == 'CA' || chosenCountry == 'US' || chosenCountry == "MX"){
            $('#postcode').removeAttr('disabled');
            $('#province').removeAttr('disabled');
            $('#province').trigger('chosen:updated');
        } else {
            $('#province').attr('disabled','disabled');
        }

        $('#province optgroup').attr('disabled','disabled');

        if (chosenCountry == 'CA' || chosenCountry == 'US' || chosenCountry == 'MX'){
            $('#province [data-country="' + chosenCountry + '"]').removeAttr('disabled');
        }

        $('#province').trigger('chosen:updated');
    }

    $("#country").on("change", function() {
        updateChosenSelects($(this).val());
    });


    /**
     * Utility function for getting all the products in sessionStorage.
     * Returns an array containing their id and their quantity.
     *
     * @returns {Array}
     */
    function getProductsFromSessionStorage() {
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
    }

    /**
     * Utility function fo getting the country and the postal code of the user.
     * "Sanitize" user's postcode??
     *
     * @returns {{country: (*|jQuery), postcode: (*|jQuery)}}
     */
    function getCountriesFromForm() {
        return res = {
            "country" : $("#country").val(),
            "postcode" : $("#postcode").val()
        };

    }

    /**
     * Sets up the ajax token for all ajax requests
     */
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    /**
     * Event triggered when the "Continue" button is hit.
     * Makes an ajax POST call to boukem (/api/estimate) with the products present in the cart.
     */
    $("#estimateButton").on("click", function(e) {
        if (sanitizeEmail($("#customer_email").val()) && ($("#postcode").val()))
        {
            e.preventDefault();

            $.ajax({
                type: "POST",
                url: "/api/estimate",
                data: {
                    products: getProductsFromSessionStorage(),
                    shipping_address: getCountriesFromForm()
                },
                success: function(data) {
                    initEstimate(data);
                },
                error: function(e, status) {
                    if (e.status == 403){
                        window.location.replace(login_url);
                        return;
                    }
                    $('#estimate').html('<div class="alert alert-danger">Une erreur est survenue. Veuillez vérifier les informations fournies.</div>');
                }
            });

        }
        else
        {
            e.preventDefault();

            if (!sanitizeEmail($("#customer_email").val()))
            {
                $("#customer_email").parent().addClass("has-error");
                $('#customer_email').addClass('animated shake');
                $('#customer_email').bind('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
                    $(this).removeClass("animated");
                    $(this).removeClass("shake");
                    $(this).unbind();
                });

                $("#why_email").removeClass("hidden").addClass("animated bounceInRight");
            }
            if (!sanitizePostCode($("#postcode").val()))
            {
                $("#postcode").parent().addClass("has-error");
                $('#postcode').addClass('animated shake');
                $('#postcode').bind('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
                    $(this).removeClass("animated");
                    $(this).removeClass("shake");
                    $(this).unbind();
                });
            }
        }


    });

    /**
     * Utility function to check if the user has really entered an email address.
     * from http://stackoverflow.com/a/46181
     *
     * @param email
     * @returns {boolean}
     */
    function sanitizeEmail(email) {
        var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
        console.log(re.test(email));
        return re.test(email);
    }

    /**
     * Utility function to check if the user has entered a valid postcode.
     * Unfortunately there is no way on earth to know if the postcode is a valid one or not.
     * We only check if the postcode is not empty here.
     *
     * @param postcode
     * @returns {boolean}
     */
    function sanitizePostCode(postcode) {
        return postcode == "" ? false : true;
    }


    /**
     * TODO: Put the following in a Estimate Object.
     * TODO: validate customer's email and postal code
     */
    function initEstimate(data) {
        displayEstimatePanel();
        fetchEstimate(data);
    }

    function displayEstimatePanel() {
        $("#estimate").removeClass("hidden").addClass("animated fadeInDown");
    }

    /**
     * TODO: append only once!
     *
     * @param data
     */
    function fetchEstimate(data) {
        $(".has-error").removeClass("has-error");

        var email_value = $("#customer_email").val();
        var postcode_value = $("#postcode").val();
        var country_value = $("#country").val();
        var estimateButtonText = $('#estimateButton').text();

        $('#estimateButton').html('<i class="fa fa-spinner fa-spin"></i>');

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

        $("#estimateButton").removeClass("btn-three");
        $("#estimateButton").addClass("btn-one");
        $('#estimateButton').text(estimateButtonText);

    }



});