/**
 * Utility object containing various utility functions...
 * Self Explanatory duh.
 *
 * @type {{getProductsFromLocalStorage: Function, getProductsPriceFromLocalStorage: Function, getCountriesFromForm: Function, scrollTopToEstimate: Function}}
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

        for(var i =0; i<localStorage.length; i++)
        {
            if (localStorage.key(i).lastIndexOf("_", 0) === 0)
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
     * Utility function returning the number of products present in the cart.
     *
     * @returns {number}
     */
    getNumberOfProducts : function() {
        var total = 0;

        for(var i = 0; i<localStorage.length; i++)
        {
            if (localStorage.key(i).lastIndexOf("_", 0) === 0)
            {
                total += JSON.parse(localStorage.getItem(localStorage.key(i))).quantity;
            }
        }

        return total;
    },

    /**
     * Utility function to get the total price from all products present in localStorage
     *
     * @returns {number}
     */
    getProductsPriceFromLocalStorage : function() {
        var total = 0,
            products = UtilityContainer.getProductsFromLocalStorage();

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
    getShippingFromForm : function() {
        return res = {
            "country" : $(".country").val(),
            "postcode" : $("#postcode").val(),
            "province" : $("#province").val(),
            "line1" : $("#shippingAddress1").val(),
            "line2" : $("#shippingAddress2").val(),
            "name" : $("#shippingFirstname").val() + " " + $("#shippingLastname").val(),
            "city" : $("#shippingCity").val(),
            "phone" : $("#shippingTel").val()
        };
    },

    /**
     * Utility function to populate a select list (#country) with a list of country (json formatted)
     *
     */
    populateCountry : function () {
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
        for(var i=0; i<emptyFields.length; i++) {
            if (emptyFields[i].val() == "")
            {
                passed = false;
                break;
            }
        }
        return passed;
    },

    /**
     * Validate the email address passed as the argument
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
     * Strip HTML tags from a string.
     * @param string html   The string to be stripped.
     * @return string       The stripped result.
     */
    stripTags: function(html) {
        var tmp = document.createElement("DIV");
        tmp.innerHTML = html;
        return tmp.textContent || tmp.innerText || "";
    },

    /**
     * Add .has-error to parent class + animate the relevant fields.
     *
     * @param fields
     */
    addErrorClassToFields: function(fields) {
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
     * Remove .has-error from fields
     *
     * @param fields
     */
    removeErrorClassFromFields: function(fields) {
        for(var i=0; i<fields.length; i++)
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

        for(var i=0; i<availableShipment.length; i++)
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
        var taxes = 0;

        if (data.taxes.length != 0)
        {
            for(var i=0; i<data.taxes.length; i++)
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
        var taxes = parseFloat(UtilityContainer.getShipmentTaxes(serviceCode, data)) + parseFloat(UtilityContainer.getCartTaxes(serviceCode, data)),
            shipping = parseFloat(UtilityContainer.getCheapestShippingMethod(data).fare),
            subtotal = parseFloat(UtilityContainer.getProductsPriceFromLocalStorage()),
            total = (taxes + shipping + subtotal).toFixed(2);

        return total;
    }
}