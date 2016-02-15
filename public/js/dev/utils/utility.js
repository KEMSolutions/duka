/**
 * Utility object containing various utility functions...
 * Self Explanatory duh.
 *
 * @type {{ getLocalizationAndEndpointUrl: Function,
 *          getAllProducts: Function,
 *          getNumberOfProductsInWishlist: Function,
 *          getNumberOfProducts: Function,
 *          getProductsPrice: Function,
 *          removeAllProducts: Function,
 *          getShippingFromForm: Function,
 *          buyButton_to_Json: Function,
 *          populateCountry: Function,
 *          validateEmptyFields: Function,
 *          validateEmail: Function,
 *          validatePostCode: Function,
 *          validateEmptyCart: Function,
 *          addErrorClassToFields: Function,
 *          addErrorClassToFieldsWithRules: Function,
 *          addFadeOutUpClass: Function,
 *          removeErrorClassFromFields: Function,
 *          getCheapestShippingMethod: Function,
 *          getTaxes: Function,
 *          getShipmentTaxes: Function,
 *          getCartTaxes: Function,
 *          getCartTotal: Function,
 *          urlGetParameters: Function,
 *          urlAddParameters: Function,
 *          urlRemoveParameters: Function,
 *          urlBuildQuery: Function
 *  }}
 */
var UtilityContainer = {

    /**
     * Utility function to get all Localization and Enpoint URLs.
     *
     */
    getLocalizationAndEndpointUrl: function () {
        return $.ajax({
            url: $("meta[name=duka-localizations-and-endpoints-url]").attr("content"),
            cache: true,
            async: false
        });
    },


    /**
     * Utility function for getting all the products in cookies.
     * Returns an array containing their id, their quantity and their price.
     *
     * @returns {Array}
     */
    getAllProducts : function() {
        var res = [];

        for (var item in Cookies.toObject()) {
            if (item.indexOf("_product_", 0) === 0) {
                var product = JSON.parse(Cookies.get(item)),
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

        for (var item in Cookies.toObject()) {
            if (item.indexOf("_wish_product_", 0) === 0) {
                total += JSON.parse(Cookies.get(item)).quantity;
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

        for (var item in Cookies.toObject()) {
            if (item.indexOf("_product_", 0) === 0) {
                total += JSON.parse(Cookies.get(item)).quantity;
            }
        }

        return total;
    },

    /**
     * Utility function to get the total price from all products present in cookies.
     *
     * @returns {number}
     */
    getProductsPrice : function() {
        var total = 0,
            products = UtilityContainer.getAllProducts();

        for(var i= 0, length = products.length; i<length; i++)
        {
            total += (products[i].price * products[i].quantity);
        }

        return total.toFixed(2);
    },

    /**
     * Utility function to delete all products from cookies.
     *
     */
    removeAllProducts : function() {
        for (var item in Cookies.toObject()) {
            if (item.indexOf("_product_", 0) === 0) {
                Cookies.remove(item);
            }
        }
    },

    /**
     * Utility function fo getting the country, the postal code and the province (if any) of the user.
     *
     * @returns {{country: (*|jQuery), postcode: (*|jQuery), province: (*|jQuery)}}
     */
    getShippingFromForm : function() {
        return {
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
            "product" : item.attr("data-product"),
            "name" : item.attr("data-name"),
            "price" : item.attr("data-price"),
            "thumbnail" : item.attr("data-thumbnail"),
            "thumbnail_lg" : item.attr("data-thumbnail_lg"),
            "quantity" : parseInt(item.attr("data-quantity")),
            "link" : item.attr("data-link"),
            "description" : item.attr("data-description") ? item.attr("data-description") : ""
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
        UtilityContainer.getProductsPrice() === 0 ?  empty = true : empty = false;

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
            subtotal = parseFloat(UtilityContainer.getProductsPrice()),
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

