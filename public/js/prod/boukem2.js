/**
 * Utility object containing various utility functions...
 * Self Explanatory duh.
 *
 * @type {{getProductsFromLocalStorage: Function, getNumberOfProductsInWishlist: Function, getNumberOfProducts: Function, getProductsPriceFromLocalStorage: Function, removeAllProductsFromLocalStorage: Function, getShippingFromForm: Function, buyButton_to_Json: Function, populateCountry: Function, validateEmptyFields: Function, validateEmail: Function, validatePostCode: Function, validateEmptyCart: Function, addErrorClassToFields: Function, addErrorClassToFieldsWithRules: Function, addFadeOutUpClass: Function, removeErrorClassFromFields: Function, getCheapestShippingMethod: Function, getTaxes: Function, getShipmentTaxes: Function, getCartTaxes: Function, getCartTotal: Function}}
 */
var UtilityContainer = {
    /**
     * Utility function for getting all the products in localStorage.
     * Returns an array containing their id, their quantity and their price.
     *
     * @returns {Array}
     */
    getProductsFromLocalStorage : function() {
        var res = [];

        for(var i = 0, length = localStorage.length; i<length; i++)
        {
            if (localStorage.key(i).lastIndexOf("_product", 0) === 0)
            {
                var product = JSON.parse(localStorage.getItem(localStorage.key(i))),
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
     * Utility function returning the number of products present in the wish list.
     *
     * @returns {number}
     */
    getNumberOfProductsInWishlist : function() {
        var total = 0;

        for(var i = 0, length = localStorage.length; i<length; i++)
        {
            if (localStorage.key(i).lastIndexOf("_wish_product", 0) === 0)
            {
                total += JSON.parse(localStorage.getItem(localStorage.key(i))).quantity;
            }
        }

        return total;
    },

    /**
     * Utility function returning the number of products present in the cart.
     *
     * @returns {number}
     */
    getNumberOfProducts : function() {
        var total = 0;

        for(var i = 0, length = localStorage.length; i<length; i++)
        {
            if (localStorage.key(i).lastIndexOf("_product", 0) === 0)
            {
                total += JSON.parse(localStorage.getItem(localStorage.key(i))).quantity;
            }
        }

        return total;
    },

    /**
     * Utility function to get the total price from all products present in localStorage.
     *
     * @returns {number}
     */
    getProductsPriceFromLocalStorage : function() {
        var total = 0,
            products = UtilityContainer.getProductsFromLocalStorage();

        for(var i= 0, length = products.length; i<length; i++)
        {
            total += (products[i].price * products[i].quantity);
        }

        return total;
    },

    /**
     * Utility function to delete all products from localStorage.
     *
     */
    removeAllProductsFromLocalStorage : function() {
        for(var i= 0, length = localStorage.length; i<length; i++) {
            if (localStorage.key(i).lastIndexOf("_product", 0) === 0)
            {
                localStorage.removeItem(localStorage.key(i));
            }
        }
    },

    /**
     * Utility function fo getting the country, the postal code and the province (if any) of the user.
     *
     * @returns {{country: (*|jQuery), postcode: (*|jQuery), province: (*|jQuery)}}
     */
    getShippingFromForm : function() {
        return res = {
            "country" : $("#shippingCountry").val(),
            "postcode" : $("#shippingPostcode").val(),
            "province" : $("#shippingProvince").val(),
            "line1" : $("#shippingAddress1").val(),
            "line2" : $("#shippingAddress2").val(),
            "name" : $("#shippingFirstname").val() + " " + $("#shippingLastname").val(),
            "city" : $("#shippingCity").val(),
            "phone" : $("#shippingTel").val()
        };
    },

    /**
     * parse the information from a buy button into a readable json format
     *
     * @param item
     * @returns {{product: *, name: *, price: *, thumbnail: *, thumbnail_lg: *, quantity: number}}
     */
    buyButton_to_Json : function(item) {
        return {
            "product" : item.data("product"),
            "name" : item.data("name"),
            "price" : item.data("price"),
            "thumbnail" : item.data("thumbnail"),
            "thumbnail_lg" : item.data("thumbnail_lg"),
            "quantity" : parseInt(item.data("quantity")),
            "link" : item.data("link"),
            "description" : item.data("description") ? item.data("description") : ""
        }
    },

    /**
     * Utility object used to populate a select list (#country) with a list of country (json formatted) in the appropriate language.
     *
     */
    populateCountry : function (lang) {
        var file = "/js/data/country-list." + lang + ".json",
            listItems = '',
            $country = $("#country");

        $.getJSON(file, function(data) {
            $.each(data, function(key, val) {
                if (key == "CA") {
                    listItems += "<option value='" + key + "' selected>" + val + "</option>";
                }
                else {
                    listItems += "<option value='" + key + "'>" + val + "</option>";
                }
            });
            $country.append(listItems);
        });
    },

    /**
     * Check if the fields passed in the argument are empty or not.
     *
     * @param emptyFields
     * @returns {boolean}
     */
    validateEmptyFields: function(emptyFields) {
        var passed = true;
        for(var i= 0, length = emptyFields.length; i<length; i++) {
            if (emptyFields[i].val() == "")
            {
                passed = false;
                break;
            }
        }
        return passed;
    },

    /**
     * Validate the email address passed as the argument.
     *
     * @param email
     * @returns {boolean}
     */
    validateEmail: function(email) {
        var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
        return re.test(email);
    },

    /**
     * Validate a CA or US postal code.
     *
     * @param postcode
     * @param country
     * @returns {boolean}
     */
    validatePostCode: function(postcode, country) {
        if (country == "CA")
            return postcode.match(/^[ABCEGHJKLMNPRSTVXY]{1}\d{1}[A-Z]{1} ?\d{1}[A-Z]{1}\d{1}$/i) ? true : false;
        else if (country == "US")
            return postcode.match(/^\d{5}(?:[-\s]\d{4})?$/) ? true : false;
        else
            return true;
    },

    /**
     * Returns true if the cart is empty, false otherwise.
     *
     * @returns {*}
     */
    validateEmptyCart : function () {
        var empty;
        UtilityContainer.getProductsPriceFromLocalStorage() === 0 ?  empty = true : empty = false;

        return empty;
    },

    /**
     * Add .has-error to parent class + animate the relevant fields.
     *
     * @param fields
     */
    addErrorClassToFields: function(fields) {
        for(var i= 0, length = fields.length; i<length; i++)
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
    },

    /**
     * Same as addErrorClassToFields but accept a single input (ie. specific rules have to be applied: email / postal code / ...
     *
     * @param input
     */
    addErrorClassToFieldsWithRules: function(input) {
        input.parent().addClass("has-error");
        input.addClass('animated shake').bind('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
            $(this).removeClass("animated");
            $(this).removeClass("shake");
            $(this).unbind();
        });
    },

    /**
     * Adds a fadeOutUp class then hide the element passed as an argument.
     *
     * @param $element
     */
    addFadeOutUpClass: function ($element) {
        $element.addClass("animated fadeOutUp").delay(1000).queue(function() {
            $(this).addClass("hidden").clearQueue();
        });
    },

    /**
     * Remove .has-error from fields
     *
     * @param fields
     */
    removeErrorClassFromFields: function(fields) {
        for(var i= 0, length = fields.length; i<length; i++)
        {
            if (fields[i].val() != "" && fields[i].parent().hasClass("has-error"))
            {
                fields[i].parent().removeClass("has-error");
            }
        }
    },

    /**
     * Returns the method and the price of the cheapest shipping services.
     *
     * @param data
     * @returns {{fare: *, method: (*|string)}}
     */
    getCheapestShippingMethod : function(data) {
        var availableShipment = data.shipping.services,
            sortedShipmentByPrice = [];

        for(var i= 0, length = availableShipment.length; i<length; i++)
        {
            sortedShipmentByPrice.push(availableShipment[i]);
        }

        sortedShipmentByPrice.sort(function(a,b) {
            return a.price - b.price
        });

        return {
            fare: sortedShipmentByPrice[0].price,
            method: sortedShipmentByPrice[0].method
        }
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

        return taxes.toFixed(2);
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
        console.log(data);

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
     * Returns appropriate taxes according to the shipping method.
     *
     * @param serviceCode
     * @param data
     * @returns {number}
     */
    getCartTaxes : function(serviceCode, data) {
        var taxes = parseFloat(UtilityContainer.getTaxes(data)),
            shippingTaxes = parseFloat(UtilityContainer.getShipmentTaxes(serviceCode, data)),
            totalTaxes = taxes + shippingTaxes;

        return totalTaxes;
    },

    /**
     * Returns total price (subtotal + taxes + shipping taxes)
     * Saves total in sessionStorage (for live update)
     *
     * @param data
     * @returns {string}
     */
    getCartTotal : function(serviceCode, data) {
        var taxes = parseFloat(UtilityContainer.getCartTaxes(serviceCode.method, data)),
            shipping = parseFloat(UtilityContainer.getCheapestShippingMethod(data).fare),
            subtotal = parseFloat(UtilityContainer.getProductsPriceFromLocalStorage()),
            total = (taxes + shipping + subtotal).toFixed(2);

        return total;
    },

    /**
     * Retrieves the query parameters from the URL.
     * Courtesy of http://stackoverflow.com/a/1917916
     *
     * @returns object
     */
    urlGetParameters : function() {

        // Performance check.
        var query = document.location.search.substr(1);
        if (query.length < 1) {
            return {};
        }

        // Loop through query elements.
        var kvp = query.split('&'), index, pair, key, value, pairs = {};
        for (index in kvp)
        {
            // Skip parameters without any values.
            if (kvp[index].indexOf('=') < 1) {
                continue;
            }

            // Save query value.
            pair = kvp[index].split('=');
            key = decodeURIComponent(pair[0]), value = decodeURIComponent(pair[1]);
            pairs[key] = value;

            // Split up queries with a ";" in the value.
            if (value.indexOf(';') > -1) {
                pairs[key] = value.split(';');
            }
        }

        return pairs;
    },

    /**
     * Adds one or more query parameters to the URL and reloads the page.
     * Courtesy of http://stackoverflow.com/a/1917916
     *
     * @param mixed key     Either a query key, or an object representing all the key-pair values to be added.
     * @param mixed value   Query value, or null if key is an object.
     * @constructor
     */
    urlAddParameters : function(key, value) {

        // We either accept a key-value pair, or a query object.
        var params = {};
        if (typeof key == "object") {
            params = key;
        } else if (typeof key == "string" && typeof value != "undefined") {
            params[key] = value;
        } else {
            return console.log("Invalid query parameters.");
        }

        // Add query parameters to existing ones.
        var query = this.urlGetParameters(), index;
        for (index in params) {
            query[index] = params[index];
        }

        // Build query string and reload the page.
        document.location.search = this.urlBuildQuery(query);
    },

    urlRemoveParameters : function(key) {

        key = typeof key == "string" ? [key] : key;

        // Try to remove one or more query parameters.
        var query = this.urlGetParameters();
        key.forEach(function(param, index, keys)
        {
            if (typeof query[param] != "undefined") {
                delete query[param];
            }
        });

        // Update the URL query.
        document.location.search = this.urlBuildQuery(query);
    },

    urlBuildQuery : function(query) {

        // Build query string.
        // We use encodeURIComponent() instead of the deprecated escape() function.
        var newQuery = [];
        for (var index in query) {
            if (typeof query[index] != "undefined" && query[index] != null)
            {
                // Concatenate arrays.
                if (typeof query[index] == 'object') {
                    query[index] = query[index].join(';');
                }

                newQuery.push(encodeURIComponent(index) +'='+ encodeURIComponent(query[index]));
            }
        }

        return "?"+ (newQuery.length > 1 ? newQuery.join('&') : newQuery[0]);
    }
};


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
 * Container responsible for initializing the checkout page.
 * Overall logic is handled in js/dev/actions/checkout/*.js
 * View component is handled in js/dev/components/checkout/*.js
 *
 * @type {{estimateButtonClick: Function, init: Function}}
 */
var checkoutInitContainer = {

    /**
     * Event triggered when the "Continue" button is hit.
     * If the input fields entered are appropriate, make the ajax call to "/api/estimate".
     * If they are not, display the relevant error message(s)
     *
     */
    estimateButtonClick : function() {
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
            checkoutValidationContainer.init(fields, email, shippingInformation, billingInformation);
        });
    },

    init: function () {
        /**
         * Populate select lists and set up billing address container behaviour.
         * Set the form focus on first name field
         *
         */
        locationContainer.init();
        billingContainer.init();
        $("#shippingFirstname").focus();

        var self = checkoutInitContainer;
        self.estimateButtonClick();
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
        $(".quantity, #shippingPostcode, #shippingCity").on("change", function () {
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
        $("#estimateButton")
            .removeClass("btn-one animated rubberBand")
            .addClass("animated rubberBand btn-three")
            .text(Localization.update);
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
/**
 * Object responsible for validating all the information entered by the user.
 * Will trigger the ajax call only when all the inputs entered match their validation rules.
 *
 * @type {{removeErrorClassFromEmail: Function, removeErrorClassFromPostcode: Function, init: Function}}
 */
var checkoutValidationContainer = {

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
        var self = checkoutValidationContainer;

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
 * Object responsible for building the select list populating countries, provinces and states on checkout page.
 *
 * @type {{populateCountry: Function, populateProvincesAndStates: Function, updateChosenSelects: Function, callUpdateChosenSelects: Function, init: Function}}
 */
var locationContainer = {

    /**
     * Function to populate country list
     * Activates the chosen plugin on the country select list.
     *
     */
    populateCountry : function (lang) {
        var file = "/js/data/country-list." + lang + ".json",
            listItems = '',
            $country = $(".country");

        $.getJSON(file, function(data) {
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

                $.each(data, function(key)
                {
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

        self.populateCountry($("html").attr("lang"));
        self.populateProvincesAndStates(["CA", "US", "MX"], function() {
            $(".province").chosen();
        });
        self.callUpdateChosenSelects(self);

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
 * Object responsible for handling the payment overlay behaviour.
 *
 * @type {{cancelOrder: Function, init: Function}}
 */
var paymentOverlayContainer = {

    /**
     * Cancels an order.
     * If the user clicks the cancel button, remove the cookie, flush the card, fadeOut the jumbotron then redirect to homepage.
     *
     */
    cancelOrder : function() {
        $("body").on("click", "#cancelOrder", function() {
            Cookies.remove("_unpaid_orders");

            $("#cancelledOrder .jumbotron").fadeOut();

            window.location.replace("/");

            UtilityContainer.removeAllProductsFromLocalStorage();

        });
    },

    /**
     * Checks whether the user has any unpaid orders, and displays a message if that's the case.
     *
     */
    checkPendingOrders : function() {

        if (Cookies.get('_unpaid_orders')) {

            // Retrieve order details.
            var order = JSON.parse(Cookies.get('_unpaid_orders'));

            // Check whether current order has been paid.
            $.ajax({
                type: 'GET',
                url: ApiEndpoints.orders.view.replace(':id', order.id).replace(':verification', order.verification),
                success: function(data) {
                    if (data.status == 'pending')
                        paymentOverlayContainer.showPaymentNotice();
                    else
                        Cookies.remove('_unpaid_orders');
                }
            });
        }

    },

    /**
     * Shows payment notice.
     *
     */
    showPaymentNotice : function() {

        // Retrieve order details.
        var order = JSON.parse(Cookies.get('_unpaid_orders'));

        // Display notice.
        $('body').prepend(
            '<div class="container overlay fullScreen" id="cancelledOrder">'+
            '<div class="jumbotron vertical-align color-one">'+
            '<div class="text-center">'+
            '<h2>'+
            Localization.pending_order.replace(':command', order.id) +
            '</h2>'+
            '<h4>'+ Localization.what_to_do +'</h4>'+
            '<br />'+
            '<ul class="list-inline">' +
            '<li>' +
            '<a href="'+
            ApiEndpoints.orders.pay.replace(':id', order.id)
                .replace(':verification', order.verification) +'">'+
            '<button class="btn btn-success" id="payOrder">'+ Localization.pay_now +'</button>'+
            '</a>'+
            '</li>' +
            '<li>' +
            '<button class="btn btn-danger" id="cancelOrder">'+
            Localization.cancel_order +
            '</button>'+
            '</li>'+
            '</ul>'+
            '</div>'+
            '</div>'+
            '</div>'
        );
    },

    /**
     * Register functions to be called outside paymentOverlayContainer.
     *
     */
    init : function() {
        var self = paymentOverlayContainer;

        self.cancelOrder();
        self.checkPendingOrders();
    }
}

/**
 * Object responsible for handling different formats of the same product.
 *
 * @type {{displaySyncedProductInformation: Function, setInventoryCount: Function, setPriceTag: Function, init: Function}}
 */
var productFormatContainer = {

    /**
     * Sets the right price, inventory count and format text according to the format of the hovered product.
     *
     */
    displaySyncedProductInformation: function() {

        const self = productFormatContainer,
            $formatSelection = $(".format-selection");

        $formatSelection.on("click", function () {
            // Set the right format in product title
            $("#product-format").text($(this).data("format"));

            // Set the right price and the right inventory count
            self.setPriceTag($(this).data("price"));
            self.setInventoryCount($(this).data("inventory-count"));

            // Toggle active class on right format
            self.toggleActiveClass($(this));

            // Creates an appropriate buybutton according to the info.
            self.setBuybuttonInformation($(this));
        });

    },

    /**
     * Sets the inventory text and value according to the inventory count of the product.
     *
     * @param count
     */
    setInventoryCount: function (count) {
        const $inventoryCount = $("#inventory-count"),
            countryCode = $inventoryCount.data("country-code"),
            expressShipping = Localization.express_shipping,
            stockLeft = Localization.stock_left.replace(":quantity", count),
            shippingTime = Localization.shipping_time,
            shippingMethod = (countryCode === "US" || countryCode === "CA") ? "fa-truck" : "fa-plane";

        var inventoryDescription = '';

       if (count > 5) {
            inventoryDescription =
                '<link itemprop="availability" href="http://schema.org/InStock">' +
                    '<li class="text-success">' +
                    '<i class="fa ' + shippingMethod + ' fa-fw"></i> ' +
                    expressShipping;
       }
       else if (count > 0) {
           inventoryDescription =
               '<link itemprop="availability" href="http://schema.org/LimitedAvailability" >' +
               '<li class="text-warning">' +
                   '<i class="fa ' + shippingMethod + ' fa-fw"></i> ' +
                   stockLeft;
       }
        else {
           inventoryDescription =
               '<link itemprop="availability" href="http://schema.org/LimitedAvailability" >' +
           '<li class="text-warning">' +
           '<i class="fa ' + shippingMethod + ' fa-fw"></i> ' +
           shippingTime;
       }

        $inventoryCount.html(inventoryDescription);

    },

    /**
     * Sets the price tag according to the format.
     *
     * @param price
     */
    setPriceTag: function (price) {
        $(".price-tag").text("$ " + price);
    },

    /**
     * Recreates a buybutton with relevant information every time we switch format.
     *
     * @param format (html5 data in format buttons)
     */
    setBuybuttonInformation: function(format) {
        var $buybuttonWrapper = $(".buybutton-format-selection-wrapper"),
            buybutton =
                '<button class="btn btn-three buybutton horizontal-align"' +
                    'data-product="' + format.data("product") +'"' +
                'data-price="' + format.data("price") +'"' +
                'data-thumbnail="' + format.data("thumbnail") +'"' +
                'data-thumbnail_lg="' + format.data("thumbnail_lg") +'"' +
                'data-name="' + format.data("name") +'"' +
                'data-format="' + format.data("format") +'"' +
                'data-inventory-count="' + format.data("inventory-count") +'"' +
                'data-quantity="' + format.data("quantity") + '"' +
                'data-link="' + format.data("link") +'"' +
                    '>' +
                '<div class="add-cart">' +
                    '<i class="fa fa-check-circle"></i> ' +
                    Localization.add_cart +
                    '</div> </button>';

        $buybuttonWrapper.empty();

        $buybuttonWrapper.append(buybutton);
    },

    /**
     * Toggles the .active class when clicked on a format.
     *
     * @param format
     */
    toggleActiveClass: function (format) {
        $(".format-selection.active").removeClass("active");
        format.addClass("active");
    },

    init: function () {
        const self = productFormatContainer;

        self.displaySyncedProductInformation();

    }
}
/**
 * Object responsible for adding products to a user's wishlist.
 *
 * @type {{fadeInFavoriteIcon: Function, setWishlistBadgeQuantity: Function, createWishlistElement: Function, renderWishlist: Function, localizeWishlistButton: Function, removeWishlistElement: Function, init: Function}}
 */
var productLayoutFavoriteContainer = {
    /**
     * Fade in the favorite icon (heart icon) when hovering on a product tile.
     *
     */
    fadeInFavoriteIcon: function() {
        $(".dense-product").hover(function() {
            $(this).children(".favorite-wrapper").fadeIn();
        }, function () {
            $(this).children(".favorite-wrapper").hide();
        });
    },

    /**
     * Update the value of .wishlist_badge when adding or deleting elements.
     *
     */
    setWishlistBadgeQuantity : function() {
        var total = UtilityContainer.getNumberOfProductsInWishlist();

        $(".wishlist_badge").text(total);
    },

    /**
     * Add the clicked product to the wish list.
     *
     */
    addToFavorite: function() {
        var self = productLayoutFavoriteContainer,
            item;

        $(".favorite-wrapper").on("click", function() {
            //No favorited class.
            if (!$(this).hasClass("favorited")) {
                item = UtilityContainer.buyButton_to_Json($(this).parent().find(".buybutton"));
                localStorage.setItem("_wish_product " + item.product, JSON.stringify(item));

                $(this).addClass("favorited");

                self.setWishlistBadgeQuantity();
            }
            else
            //Has a favorited class. We remove it, then delete the element from local Storage.
            {
                self.removeFromFavorite($(this), self);
            }
        });
    },

    /**
     * Persist the heart icon next to products already marked as wished.
     *
     */
    persistFavorite: function() {
        for(var i = 0, length = localStorage.length; i<length; i++)
        {
            if (localStorage.key(i).lastIndexOf("_wish_product", 0) === 0) {
                for(var j = 0; j<$(".favorite-wrapper").length; j++)
                {
                    if(JSON.parse(localStorage.getItem(localStorage.key(i))).product === $(".favorite-wrapper")[j].dataset.product)
                    {
                        $(".favorite-wrapper")[j].className += " favorited";
                    }
                }
            }
        };
    },

    /**
     * Delete the clicked element from the wish list.
     *
     * @param context
     */
    removeFromFavorite: function (element, context) {
        element.removeClass("favorited");
        localStorage.removeItem("_wish_product " + element.data("product"));
        context.setWishlistBadgeQuantity();
    },

    init: function () {
        var self = productLayoutFavoriteContainer;


        self.addToFavorite();
        self.persistFavorite();
        self.fadeInFavoriteIcon();
        self.setWishlistBadgeQuantity();
    }
}
var productResponsive = {
    invertPriceAndDescriptionColumn: function () {
        $(window).on("load resize", function () {
            if($(this).width() < 768)
            {
                $("#product-description").before($("#product-info-box"));
            }
            else
            {
                $("#product-description").after($("#product-info-box"));
            }
        });
    },

    init: function () {
        var self = productResponsive;

        self.invertPriceAndDescriptionColumn();
    }
}
/**
 * Object responsible for activating semantic ui features.
 *
 * @type {{module: {initDropdownModule: Function, initRatingModule: Function}, behaviors: {}, init: Function}}
 */
var semanticInitContainer = {

    /**
     * Initialize modules
     *
     */
    module: {
        /**
         * Initialize dropdown module.
         *
         */
        initDropdownModule: function() {
            //Enable selection on clicked items
            $(".ui.dropdown-select").dropdown();

            //Prevent selection on clicked items
            $(".ui.dropdown-no-select").dropdown({
                    action: "select"
                }
            );
        },

        /**
         * Initialize rating module.
         *
         */
        initRatingModule: function () {
            $(".ui.rating").rating();
        }
    },

    /**
     * Specify semantic custom behavior.
     *
     */
    behaviors: {

    },



    init: function () {
        var self = semanticInitContainer,
            module = self.module;

        module.initDropdownModule();
        module.initRatingModule();
    }
}
/**
 * Object responsible for the view component of each category page.
 *
 * @type {{blurBackground: Function, init: Function}}
 */
var categoryContainer = {

    /**
     * Contains the updated URL parameters,
     *
     */
    searchParameters: {
        page: 1,
        per_page: 8,
        order: 'relevance',
        min_price: null,
        max_price: null,
        brands: [],
        categories: []
    },

    /**
     * Blurs the background of each category's page header.
     *
     */
    blurBackground: function () {
        $(".category-header").blurjs({
            source: ".category-header"
        });
    },


    /**
     * Sets a number of items per page and set the value to the appropriate input.
     *
     */
    itemsPerPage: function () {
        $(".items-per-page .item").on("click", function() {
            UtilityContainer.urlAddParameters("per_page", $(this).data("sort"));
        });

        // Set the selected option.
        $('#items-per-page-box').dropdown('set selected', this.searchParameters.per_page);
    },


    /**
     * Sets the sort by filter and set the value to the appropriate input.
     *
     */
    sortBy: function () {
        $(".sort-by .item").on("click", function() {
            UtilityContainer.urlAddParameters("order", $(this).data("sort"));
        });

        // Set the selected option.
        $('#sort-by-box').dropdown('set selected', this.searchParameters.order);
    },

    /**
     * Adds the price filter to the search query.
     *
     */
    priceUpdate: function() {

        $("#price-update").on("click", function()
        {
            UtilityContainer.urlAddParameters({
                min_price : $("#min-price").val(),
                max_price : $("#max-price").val()
            });
        });

        // Set the specified price range.
        if (this.searchParameters.min_price) {
            $('#min-price').val(this.searchParameters.min_price);
        }

        if (this.searchParameters.max_price) {
            $('#max-price').val(this.searchParameters.max_price);
        }
    },

    /**
     * Adds the category filter to the search query.
     *
     */
    categoriesUpdate: function() {
        this.filterListUpdate($("#refine-by-category"), "categories");
    },

    /**
     * Adds the brands filter to the search query.
     *
     */
    brandsUpdate: function() {
        this.filterListUpdate($("#refine-by-brand"), "brands");
    },

    /**
     * Shortcut to handle filter lists such as brands and categories.
     *
     * @param el
     * @param type
     */
    filterListUpdate : function(el, type)
    {
        // Performance check.
        if (!el) {
            return;
        }

        // Add the event listeners to each child element.
        el.find(".item").on("change",
            {
                filter : type || "brands"
            },

            function(event)
            {
                var ID = $(this).data("filter"),
                    filterList = categoryContainer.searchParameters[event.data.filter],
                    filter = $(this);

                // Add brand to filter.
                if ($(this).prop("checked")) {
                    filterList.push(ID);

                }

                // Or remove it.
                else
                {
                    var newList = [];

                    if (filterList.length > 1) {
                        for (var index in filterList) {
                            if (filterList[index] != ID) {
                                newList.push(filterList[index]);
                            }
                        }
                    }

                    filterList = newList;
                }

                // Reorder filter list.
                filterList.sort(function(a, b) {
                    return a - b;
                });

                // Update page.
                if (filterList.length > 0) {
                    var filter = filterList.length > 1 ? filterList.join(';') : filterList[0];
                    UtilityContainer.urlAddParameters(event.data.filter, filter);
                } else {
                    UtilityContainer.urlRemoveParameters(event.data.filter);
                }
        });

        // Update selected checkboxes. IDs are stored as strings in "categoryContainer.searchParameters".
        el.find(".item").each(function() {
            $(this).prop("checked", categoryContainer.searchParameters[type].indexOf(""+ $(this).data("filter")) > -1);
        });


    },

    /**
     * Create a new tag to be appended to the tags list.
     *
     * @param filter (filter being the checkbox DOM node)
     */
    addFilterToTagList: function (filter) {
        var item =
        '<div class="item">' +
        '<a class="ui grey tag label" data-id="' + filter.data("filter") + '">' + filter.data("name") +
        '<i class="icon remove right floated"></i>' +
        '</a>' +
        '</div>';

        $(".tags-list").append(item);
    },


    /**
     * Switch between grid or list layout.
     *
     */
    toggleLayout: function () {
        var self= categoryContainer,
            $container = $(".layout-toggle-container"),
            $product = $(".dense-product"),
            $product_img = $(".product-image"),
            $product_buybutton = $(".dense-product .buybutton"),
            $product_shortDescription = $(".dense-product .short-description"),
            $product_name = $(".dense-product .name a");

        $("#category-layout-switcher").on("click", function () {

            if($container.hasClass("grid-layout"))
            {
                // List layout
                $container.removeClass("grid-layout").addClass("list-layout");

                $product.removeClass("four wide column text-center no-border")
                    .addClass("sixteen wide column border-bottom-clear");

                $product_shortDescription.removeClass("hidden");

                $product_name.addClass("ui medium header");

                $product_img.removeClass("center-block").addClass("pull-left").css("margin-right", "5%");

                $product_buybutton.css("margin-top", "2rem");

                self.localizeSwitcher($(this), "grid");
            }
            else if ($container.hasClass("list-layout"))
            {
                // Grid layout
                $container.removeClass("list-layout").addClass("grid-layout");

                $product.removeClass("sixteen wide column border-bottom-clear").
                    addClass("four wide column text-center no-border");

                $product_img.addClass("center-block").removeClass("pull-left").css("margin-right", "0");

                $product_shortDescription.addClass("hidden");

                $product_name.removeClass("medium").addClass("tiny");

                $product_buybutton.css("margin-top", "0");

                self.localizeSwitcher($(this), "list");
            }
        })
    },

    /**
     * Utility function to localize the layout switch button in the appropriate locale.
     *
     * @param element
     * @param layout
     */
    localizeSwitcher: function(element, layout) {
        layout === "list" ?
            element.html("<i class='list layout icon'></i>" + Localization.list) :
            element.html("<i class='grid layout icon'></i>" + Localization.grid);
    },

    /**
     * Retrieves the query parameters from the URL and stores them locally.
     *
     */
    retrieveSearchParameters: function() {

        var query = UtilityContainer.urlGetParameters();

        for (var key in query)
        {
            this.searchParameters[key] = query[key];

            // For brands and categories, the value should be an array.
            if (["brands", "categories"].indexOf(key) > -1 && typeof query[key] != 'object') {
                this.searchParameters[key] = [query[key]];
            }
        }
    },

    init: function () {
        var self = categoryContainer;

        self.retrieveSearchParameters();
        self.blurBackground();
        self.itemsPerPage();
        self.sortBy();
        self.priceUpdate();
        self.categoriesUpdate();
        self.brandsUpdate();
        self.toggleLayout();
    }
};


/**
 * Object responsible for specific behaviours of homepage sections.
 *
 * @type {{mixed: {toggleSixteenWideColumn: Function}, init: Function}}
 */
var homepageContainer = {

    /**
     * Mixed section
     *
     */
    mixed: {
        toggleSixteenWideColumn: function () {
                var $productColumn = $(".mixed-section .eleven"),
                $widgetColumn = $(".mixed-section .four");

            $(window).on("load resize", function() {
                if(!$widgetColumn.is(":visible")) {
                    $productColumn.removeClass().addClass("sixteen wide column");
                }
                else {
                    $productColumn.removeClass().addClass("eleven wide column");
                }
            });

        }
    },

    init: function () {
        var self = homepageContainer,
            mixed = self.mixed;

        mixed.toggleSixteenWideColumn();
    }
}
/**
 * Container responsible for initializing the cart drawer feature.
 *
 * @type {{buyButtonClick: Function, getEstimateClick: Function, init: Function}}
 */
var cartDrawerInitContainer = {

    /**
     * Event triggered when a buy button is clicked.
     *
     */
    buyButtonClick : function () {
        $("body").on("click", ".buybutton", function() {
            cartDisplayContainer.animateIn();
            cartLogicContainer.addItem(UtilityContainer.buyButton_to_Json($(this)));
            cartLogicContainer.storeItem(UtilityContainer.buyButton_to_Json($(this)));

            //We remove the "Your cart is empty" message at the top every time we add an item.
            //TODO : Maybe improve it?
            $("#cart-items .empty-cart").addClass("hidden");
        });
    },

    /**
     * Event triggered when the Calculate button (to get a price estimate) is clicked.
     *
     */
    getEstimateClick: function () {
        $(".getEstimate").on("click", function() {
            //Fields validation + Empty cart validation.
            if(UtilityContainer.validatePostCode($("#postcode").val(), $(".price-estimate #country").val())
                && UtilityContainer.validateEmptyFields([$("#postcode")])
                && !UtilityContainer.validateEmptyCart()) {

                $(this).html('<i class="fa fa-spinner fa-spin"></i>');

                cartLogicContainer.ajaxCall();

            }
            else if (UtilityContainer.validateEmptyCart()) {
                $("#cart-items .empty-cart").removeClass("hidden");
            }
            else {
                UtilityContainer.addErrorClassToFieldsWithRules($("#postcode"));
            }
        });
    },

    init: function () {
        cartDisplayContainer.init();
        cartLogicContainer.init();
        cartDisplayContainer.setCartItemsHeight();

        var self = cartDrawerInitContainer;
        self.buyButtonClick();
        self.getEstimateClick();
    }

}

/**
 * Object responsible for the overall logic (CRUD) of the cart drawer.
 * Layout handled in dev/components/layout/cart-drawer.js
 *
 * @type {{$el: {$list: (*|jQuery|HTMLElement)}, addItem: Function, storeItem: Function, loadItem: Function, deleteItem: Function, modifyQuantity: Function, modifyQuantityBeforeBuying: Function, setBadgeQuantity: Function, setQuantityCookie: Function, setCartSubtotal: Function, setCartShipping: Function, setCartTaxes: Function, setCartTotal: Function, ajaxCall: Function, updateAjaxCall: Function, init: Function}}
 */
var cartLogicContainer = {
    /**
     * Cache a set of elements commonly used (to be updated)
     */
    $el : {
        $list : $(".cart-items-list")
    },

    /**
     * Add an item in the list.
     *
     * @param item JSON format converted from attributes on the .buybutton
     */
    addItem : function(item) {
        var price = (parseInt(item.quantity) * parseFloat(item.price)).toFixed(2);

        var sidebarElement =
        '<div class="item horizontally-padded animated bounceInDown" data-product="' + item.product + '"data-quantity=1>' +
        '<div class="ui tiny images">' +
        '<img src="' + item.thumbnail_lg + '"/>' +
        '</div>' +
        '<div class="middle aligned content" style="padding-left: 1.7531%">' +
        '<h4 class="ui header">' + item.name + '</h4>' +
        '<div class="meta">' +
        '<span class="price" data-price="' + item.price + '">$' + price  + '</span>' +
        '<i class="trash icon pull-right close-button"></i>' +
        '</div>' +
        '<div class="content" style="margin-top: 2.958rem">' +
        '<div class="ui input">' +
        '<input type="number" class="quantity" min="1" step="1" value="' + item.quantity + '" name="products[' + item.product + '][quantity]">' +
        '<input type="hidden" name="products[' + item.product + '][id]" value="' + item.product + '"/> ' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>';


        //var sidebarElement = '<li class="w-box animated bounceInDown" data-product="' + item.product + '" data-quantity=1>' +
        //    '<div class="col-xs-3 text-center"><img src=' + item.thumbnail_lg + ' class="img-responsive"></div>' +
        //    '<div class="col-xs-9 no-padding-left">' +
        //    '<div class="row"><div class="col-xs-10"><h3 class="product-name">' + item.name + '</h3></div><div class="col-xs-2"><h4 class="text-right"><i class="fa fa-trash fa-1 close-button"><span class="sr-only">Remove Item</span></i></h4></div></div>' +
        //    '<div class="row"><div class="col-xs-8">' +
        //    '<div class="input-group"><label for="products[' + item.product + '][quantity]" class="sr-only">'+ item.name + ":" + item.price +'</label>' +
        //    '<input type="number" class="quantity form-control input-sm" min="1" step="1" value="' + item.quantity + '" name="products[' + item.product + '][quantity]">' +
        //    '<span class="input-group-addon update_quantity_indicator"><i class="fa" hidden><span class="sr-only">' + "Update quantity" + '</span></i></span></div></div>' +
        //    '<div class="col-xs-4 product-price text-right" data-price="' + item.price + '">$' + price  + '<span class="sr-only">' + $ + item.price + '</span></div></div>' +
        //    '<input type="hidden" name="products[' + item.product + '][id]" value="' + item.product + '"/> ' +
        //    '</div>' +
        //    '</li>';

        if (!$(".cart-items-list [data-product='" + item.product + "']").length){
            cartLogicContainer.$el.$list.append(sidebarElement);
        }

    },

    /**
     * Store a product in localStorage
     * Update badge quantity
     * Create/update a quantity cookie
     *
     * @param item JSON format converted from attributes on the .buybutton
     */
    storeItem : function(item) {
        localStorage.setItem("_product " + item.product, JSON.stringify(item));
        cartLogicContainer.setBadgeQuantity();
        cartLogicContainer.setQuantityCookie();
        cartLogicContainer.setCartSubtotal();
        cartLogicContainer.setCartTotal();
        cartLogicContainer.updateAjaxCall();
    },

    /**
     * Load a list of items previously bought into the cart.
     * If there is no item in localStorage starting with the key "_product", then nothing is loaded.
     */
    loadItem : function() {
        for(var i = 0, length = localStorage.length; i<length; i++)
        {
            if (localStorage.key(i).lastIndexOf("_product", 0) === 0)
            {
                cartLogicContainer.addItem(JSON.parse(localStorage.getItem(localStorage.key(i))));
            }
        }
    },

    /**
     * Delete an item from the cart drawer list.
     * Remove it from the DOM.
     * Delete the object on localStorage.
     * Set Badge quantity accordingly.
     * Update Cookie quantity accordingly.
     */
    deleteItem: function() {
        $(document).on('click', ".close-button", function() {
            $parent = $(this).closest(".animated").addClass("animated bounceOutLeft");
            $parent.one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
                $(this).remove();
            });

            localStorage.removeItem("_product " + $(this).closest(".animated").data("product"));

            cartLogicContainer.setBadgeQuantity();
            cartLogicContainer.setQuantityCookie();
            cartLogicContainer.setCartSubtotal();
            cartLogicContainer.setCartTotal();
            cartLogicContainer.updateAjaxCall();

        });
    },

    /**
     * Modify the quantity of a product in the cart
     * Update its price label accordingly
     * Update the localStorage
     * Set badge quantity
     * Update Cookie quantity
     */
    modifyQuantity : function() {
        $("#cart-items").on("change", ".quantity", function() {
            $container = $(this).closest(".item");
            $product_price = $container.find(".price");

            //update the total value
            $product_price.text("$" + ($product_price.data("price") * $(this).val()).toFixed(2));

            //retrieve old data from old object then update the quantity and finally update the object
            var oldData = JSON.parse(localStorage.getItem("_product " + $container.data("product")));
            oldData.quantity = parseInt($(this).val());
            localStorage.setItem("_product " + $container.data("product"), JSON.stringify(oldData));

            cartLogicContainer.setBadgeQuantity();
            cartLogicContainer.setQuantityCookie();
            cartLogicContainer.setCartSubtotal();
            cartLogicContainer.setCartTotal();
            cartLogicContainer.updateAjaxCall();

        });
    },

    /**
     * Modify the quantity in a product page before buying
     * Only used in a product page. (It is hackish, I'll admit it)
     * Assuming the DOM has (and will keep) this structure:
     *      .form-group
     *          #item-quantity
     *          .ui.buttons.huge
     *          <br>
     *          <br>
     *      .buybutton
     */
    modifyQuantityBeforeBuying : function() {
        $("#item_quantity").on("change", function() {

            // Cache buybutton and format selection buttons.
            var $buybutton = $(this).closest(".input-qty-detail").find(".buybutton"),
                $formatSelection = $(this).closest(".input-qty-detail").find(".format-selection"),
                self = $(this);

            // Set quantity in html5 data attributes for each format selection button.
            $formatSelection.each(function() {
                this.dataset.quantity = parseInt(self.val());
            })

            // Set quantity in html5 data attribute for buybutton.
            $buybutton.data("quantity", parseInt(self.val()));

        });
    },

    /**
     * Update the value of #cart_badge when adding or deleting elements
     */
    setBadgeQuantity : function() {
        var total = UtilityContainer.getNumberOfProducts();

        $(".cart_badge").text(total);
    },

    /**
     * Create or Update a cookie with the quantity present in the cart.
     * The value of the cookie is encoded in base64 (btoa)
     */
    setQuantityCookie : function () {
        var number = UtilityContainer.getNumberOfProducts();

        if (Cookies.get("quantityCart") == undefined || number === 0)
        {
            Cookies.set("quantityCart", btoa("0"));
        }
        else {
            Cookies.set("quantityCart", btoa(number));
        }
    },

    /**
     * Update subtotal when users put something in or out of their cart.
     *
     */
    setCartSubtotal : function () {
        $("dd#subtotal").text("$" + UtilityContainer.getProductsPriceFromLocalStorage().toFixed(2));
    },

    /**
     * Set shipping field
     *
     * @param data
     */
    setCartShipping : function(data) {
        $("dd#shipping").text("$" + (UtilityContainer.getCheapestShippingMethod(data).fare));
    },


    /**
     * Set taxes field
     *
     * @param taxes
     */
    setCartTaxes : function(taxes) {
        $("#taxes").text("$" + taxes.toFixed(2));
    },

    /**
     * Set total field
     *
     * @param total
     */
    setCartTotal : function (total) {
        $(".cart-total dl").show();
        $(".calculation.total dd").text("$ " + total);
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
                products: UtilityContainer.getProductsFromLocalStorage(),
                shipping_address: {
                    "postcode": $("#postcode").val(),
                    "country": $(".price-estimate #country").val(),
                    "province" : "QC"
                }
            },
            success: function(data) {
                cartLogicContainer.setCartShipping(data);
                cartLogicContainer.setCartTaxes(UtilityContainer.getCartTaxes(UtilityContainer.getCheapestShippingMethod(data).method, data));
                cartLogicContainer.setCartTotal(UtilityContainer.getCartTotal(UtilityContainer.getCheapestShippingMethod(data), data));
            },
            error: function(e) {
                console.log(e);
            },
            complete : function() {
                $(".price-estimate").fadeOut(300, function() {
                    $(".calculation.hidden").fadeIn().removeClass("hidden");
                    $(".cart-total.hidden").fadeIn().removeClass("hidden");
                });
            }
        });
    },

    /**
     * Display an update panel when changes are made to the cart drawer.
     *
     */
    updateAjaxCall : function() {
        //If the total is displayed, it means that there's already been an ajax call: we have to display an update!
        if(!$(".total").parent().hasClass("hidden")) {
            $(".cart-total dl").hide();
            $(".price-estimate-update").fadeIn('fast');
        }

        $(".changeLocation").click(function() {
            $("dl.calculation").addClass("hidden");
            $(".getEstimate").html(Localization.calculate);
            $(".price-estimate-update").fadeOut();
            $(".price-estimate").fadeIn();

        });

        //TODO: Refactor the arbitrary xxxxms to an actual end of ajax call.

        $(".price-estimate-update .getEstimate").click(function() {
            if(!UtilityContainer.validateEmptyCart()) {
                setTimeout(function() {
                    $(".price-estimate-update .getEstimate").parent().fadeOut(300);
                    $(".price-estimate-update .getEstimate").html(Localization.calculate);
                }, 2250);
            }
        });

    },

    init : function() {
        cartLogicContainer.setBadgeQuantity();
        cartLogicContainer.loadItem();
        cartLogicContainer.deleteItem();
        cartLogicContainer.modifyQuantity();
        cartLogicContainer.modifyQuantityBeforeBuying();
        cartLogicContainer.setQuantityCookie();
        cartLogicContainer.setCartSubtotal();
    }
};

/**
 * Object responsible for displaying the cart drawer.
 * Logic handled in dev/actions/layout/cart-drawer-logic.js
 *
 * @type {{$el: {$back: (*|jQuery|HTMLElement), $proceed: (*|jQuery|HTMLElement), $trigger: (*|jQuery|HTMLElement), $container: (*|jQuery|HTMLElement), $checkout: (*|jQuery|HTMLElement), $body: (*|jQuery|HTMLElement)}, displayOn: Function, displayOff: Function, animateIn: Function, animateOut: Function, setCartItemsHeight: Function, computeCartItemsHeight: Function, init: Function}}
 */
var cartDisplayContainer = {
    $el : {
        $back : $("#back"),
        $proceed : $("#proceed"),
        $trigger : $(".view-cart"),
        $container : $("#cart-container"),
        $checkout : $("#checkout"),
        $body : $("body")
    },

    displayOn: function() {
        var _width = cartDisplayContainer.$el.$container.width();
        cartDisplayContainer.$el.$container.css( {
            "margin-right" : -_width
        });

        cartDisplayContainer.$el.$trigger.click(function() {
            cartDisplayContainer.animateIn();
        });
    },

    displayOff : function() {
        cartDisplayContainer.$el.$back.click(function() {
            cartDisplayContainer.animateOut();
        });
        cartDisplayContainer.$el.$checkout.click(function() {
            sessionStorage.isDisplayed = false;
        });
    },

    animateIn : function() {
        cartDisplayContainer.$el.$container.show();
        cartDisplayContainer.$el.$container.animate( {
            "margin-right" : 0
        }, 400);
        sessionStorage.isDisplayed = true;
    },

    animateOut: function() {
        var _width = cartDisplayContainer.$el.$container.width();
        cartDisplayContainer.$el.$container.animate( {
            "margin-right" : -_width
        }, 400, function() {
            $(this).hide();
        });
        sessionStorage.isDisplayed = false;
    },

    setCartItemsHeight : function() {
        cartDisplayContainer.computeCartItemsHeight();

        $(window).on("resize", function() {
            cartDisplayContainer.computeCartItemsHeight();
        });

        cartDisplayContainer.$el.$trigger.on("click", function() {
            cartDisplayContainer.computeCartItemsHeight();
        })
    },

    computeCartItemsHeight : function() {
        var cartItemsHeight = $("#cart-container").height() - ($(".cart-header").height() + $(".cart-footer").height());

        $("#cart-items").css("height", cartItemsHeight);
    },

    init : function() {
        cartDisplayContainer.displayOn();
        cartDisplayContainer.displayOff();
        UtilityContainer.populateCountry($("html").attr("lang"));

        if (sessionStorage.isDisplayed == "true")
        {
            cartDisplayContainer.$el.$container.css("margin-right", 0);
            cartDisplayContainer.$el.$container.show();
        }

    }
};
/**
 * Container responsible for handling the logic of the wish list page.
 * Layout handled in dev/components/site/wishlist.js
 *
 * @type {{createWishlistElement: Function, renderWishlist: Function, removeWishlistElement: Function, init: Function}}
 */
var wishlistLogicContainer = {

    /**
     * Create a list layout element from the information passed as an argument.
     *
     * Rounding to 2 decimals, courtesy of http://stackoverflow.com/a/6134070.
     *
     * @param item
     */
    createWishlistElement: function(item) {
        var self = wishlistLogicContainer,
            element =
        '<div class="item list-layout-element">' +
        '<div class="ui tiny image">' +
        '<img src=' + item.thumbnail_lg + '>' +
        '</div>' +
        '<div class="middle aligned content">' +
        '<div class="header">' +
        '<a href=' + item.link + '>' + item.name + '</a>' +
        '</div>' +
        '<div class="description">' +
        '<p>' + item.description + '</p>' +
            '<h5> $ ' + parseFloat(Math.round(item.price * 100) / 100).toFixed(2) + '</h5>'+
        '</div>' +
        '<div class="extra">' +
        '<button class="ui right floated button green buybutton"' +
        'data-product="' + item.product + '"' +
        'data-price="' + item.price + '"' +
        'data-thumbnail="' + item.thumbnail + '"' +
        'data-thumbnail_lg="' + item.thumbnail_lg + '"' +
        'data-name="' + item.name + '"' +
        'data-description="' + item.description + '"' +
        'data-quantity="' + item.quantity  + '"' + ">" +
        'Add to cart </button>' +
        '</button>' +
        '<button class="ui right floated button inverted red removeFavoriteButton" data-product="' + item.product + '">' +
        'Remove from wishlist' +
        '</button>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '<hr/>';


        //Localize button (default in english)
        self.localizeWishlistButton();

        //Append elements
        $(".list-layout-element-container").append(element);
    },

    /**
     * Populate the wishlist page with elements created on the fly from localStorage that has their key starting with "_wish_prod {id}".
     * The creation is handled in createWishlistElement function.
     *
     */
    renderWishlist: function() {
        var self = wishlistLogicContainer;

        for(var i = 0, length = localStorage.length; i<length; i++)
        {
            if (localStorage.key(i).lastIndexOf("_wish_product", 0) === 0)
            {
                self.createWishlistElement(JSON.parse(localStorage.getItem(localStorage.key(i))));
            }
        }
    },

    localizeWishlistButton: function() {
        $(".list-layout-element .buybutton").text(Localization.add_cart);
        $(".list-layout-element .removeFavoriteButton").text(Localization.wishlist_remove);
    },

    /**
     * Remove the element from the wishlist after a subtle animation.
     *
     */
    removeWishlistElement: function () {
        $(".list-layout-element-container").on("click", ".removeFavoriteButton", function() {
            //Animate the element.
            UtilityContainer.addFadeOutUpClass($(this).closest(".list-layout-element"));
            UtilityContainer.addFadeOutUpClass($(this).closest(".list-layout-element").next());

            //Delete the element from localStorage.
            localStorage.removeItem("_wish_product " + $(this).data("product"));

            //Set wishlist header quantity.
            wishlistContainer.setNumberOfProductsInHeader();

            //Set wishlist badge
            productLayoutFavoriteContainer.setWishlistBadgeQuantity();
        });
    },

    init: function () {
        var self = wishlistLogicContainer;

        //Calls the layout container (wishlistContainer).
        wishlistContainer.init();

        //Initialize the logic.
        self.renderWishlist();
        self.removeWishlistElement();
    }

}
/**
 * Object responsible for the view component of the wish list page.
 * Logic handled in dev/actions/site/wishlist-logic.js
 *
 * @type {{setNumberOfProductsInHeader: Function, init: Function}}
 */
var wishlistContainer = {

    /**
     * Sets the number of products in the header (singular / plural).
     *
     */
    setNumberOfProductsInHeader: function() {
        var quantity = "";
        UtilityContainer.getNumberOfProductsInWishlist() == 0 || UtilityContainer.getNumberOfProductsInWishlist() == 1 ? quantity+= (UtilityContainer.getNumberOfProductsInWishlist() + "  item ") : quantity += (UtilityContainer.getNumberOfProductsInWishlist() + "  items ");
        $("#quantity-wishlist").text(quantity);
    },


    init: function() {
        var self = wishlistContainer;

        self.setNumberOfProductsInHeader();
    }
}
/**
 * Entry point of script.
 *
 */
$(document).ready(function () {

    /**
     * Sets up the ajax token for all ajax requests
     *
     */
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'locale': $('html').attr('lang')
        }
    });

    /**
     * Initialize semantic UI modules
     *
     */
    semanticInitContainer.init();

    /**
     * Initialize checkout logic.
     *
     */
    checkoutInitContainer.init();

    /**
     * Initialize cart drawer logic.
     *
     */
    cartDrawerInitContainer.init();

    /**
     * Initialize category container
     *
     */
    categoryContainer.init();

    /**
     * Initialize overlay plugin.
     *
     */
    paymentOverlayContainer.init();

    /**
     * Initialize homepage sections.
     *
     */
    homepageContainer.init();

    /**
     * Initialize favorite products feature.
     *
     */
    productLayoutFavoriteContainer.init();

    /**
     * Initialize product formats feature.
     *
     */
    productFormatContainer.init();

    /**
     * Initialize column responsiveness in product pages.
     *
     */
    productResponsive.init();

    /**
     * Initialize wishlist page.
     *
     */
    wishlistLogicContainer.init();

    /**
     * Global initialization of elements.
     *
     */
    //fancy plugin for product page (quantity input)
    $(".input-qty").TouchSpin({
        initval: 1
    });

});