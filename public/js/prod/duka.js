/**
 * Utility object containing various utility functions...
 * Self Explanatory duh.
 *
 * @type {{ getLocalizationAndEndpointUrl: Function,
 *          getProductsFromLocalStorage: Function,
 *          getNumberOfProductsInWishlist: Function,
 *          getNumberOfProducts: Function,
 *          getProductsPriceFromLocalStorage: Function,
 *          removeAllProductsFromLocalStorage: Function,
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


var cartSliderContainer = {

    /**
     * Responsible for the logic.
     * CRUD.
     *
     */
    behaviour: {
        /**
         * Event triggered when a buy button is clicked.
         *
         */
        buyButtonClick : function () {
            $("body").on("click", ".buybutton", function() {

                cartSliderContainer.behaviour.addItem(UtilityContainer.buyButton_to_Json($(this)));
                cartSliderContainer.behaviour.storeItem(UtilityContainer.buyButton_to_Json($(this)));

                // We remove the "Your cart is empty" message at the top every time we add an item.
                $("#cart-items").addClass("hidden");
            });
        },


        /**
         * Add an item in the list.
         *
         * @param item JSON format converted from attributes on the .buybutton
         */
        addItem : function(item) {
            var price = (parseInt(item.quantity) * parseFloat(item.price)).toFixed(2);

            var productItem =
                '<div class="very padded item animated fadeInUp" style="margin: 1rem auto;" data-product="' + item.product + '"data-quantity=1>' +
                '<div class="ui tiny left floated image">' +
                '<img src="' + item.thumbnail_lg + '"/>' +
                '</div>' +
                '<div class="middle aligned content">' +
                '<h4 class="ui header">' + item.name + '</h4>' +
                '<div class="meta">' +
                '<span class="price" data-price="' + item.price + '">$' + price  + '</span>' +
                '<i class="trash icon large pull-right close-button"></i>' +
                '</div>' +
                '<div class="content cart-content">' +
                '<span>'+ Localization.quantity + '</span>' +
                '<div class="ui input one-quarter">' +
                '<input type="number" class="quantity" min="1" step="1" value="' + item.quantity + '" name="products[' + item.product + '][quantity]">' +
                '<input type="hidden" name="products[' + item.product + '][id]" value="' + item.product + '"/> ' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>';

            if (!$(".cart-items-list [data-product='" + item.product + "']").length){
                $(".cart-items-list").append(productItem);
            }

        },


        /**
         * Store a product in localStorage.
         * Update badge quantity.
         * Create/update a quantity cookie.
         *
         * @param item JSON format converted from attributes on the .buybutton
         */
        storeItem : function(item) {
            if(localStorage.getItem("_product " + item.product) != null)
            {
                // Update the value on localStorage of an already existing product.
                var quantity_updated = JSON.parse(localStorage.getItem("_product " + item.product)).quantity + 1;

                // Update the input value already displayed in the cart drawer.
                $("input[name='products[" + item.product + "][quantity]']").attr("value", quantity_updated);

                // Set the item.
                localStorage.setItem("_product " + item.product, JSON.stringify(
                    {
                        "product" : item.product,
                        "name" : item.name,
                        "price" : item.price,
                        "thumbnail" : item.thumbnail,
                        "thumbnail_lg" : item.thumbnail_lg,
                        "quantity" : quantity_updated,
                        "link" : item.link,
                        "description" : item.description
                    }
                ));
            }
            else {
                localStorage.setItem("_product " + item.product, JSON.stringify(item));
            }
            cartSliderContainer.view.setBadgeQuantity();
            cartSliderContainer.behaviour.setQuantityCookie();
        },


        /**
         * Load a list of items previously bought into the cart.
         * If there is no item in localStorage starting with the key "_product", then nothing is loaded.
         */
        loadItem : function() {
            $("#cart-items").addClass("hidden");

            for(var i = 0, length = localStorage.length; i<length; i++)
            {
                if (localStorage.key(i).lastIndexOf("_product", 0) === 0)
                {
                    cartSliderContainer.behaviour.addItem(JSON.parse(localStorage.getItem(localStorage.key(i))));
                }
            }
        },


        /**
         * Delete an item from the cart drawer list.
         * Remove it from the DOM.
         * Delete the object on localStorage.
         * Set Badge quantity accordingly.
         * Update Cookie quantity accordingly.
         *
         */
        deleteItem: function() {
            $(document).on('click', ".close-button", function() {

                // We fade out the item...
                var $item = $(this).closest(".animated").addClass("animated fadeOutUp");

                // Then we remove it from the dom...
                $item.one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
                    $(this).remove();

                    // Display a message if the cart has no more item in it.
                    UtilityContainer.getNumberOfProducts() === 0 ? $("#cart-items").removeClass("hidden") : null;
                });

                // To finally delete it from localstorage.
                localStorage.removeItem("_product " + $(this).closest(".animated").data("product"));

                cartSliderContainer.view.setBadgeQuantity();
                cartSliderContainer.behaviour.setQuantityCookie();

            });
        },


        /**
         * Modify the quantity of a product in the cart.
         * Update its price label accordingly.
         * Update the localStorage.
         * Set badge quantity.
         * Update Cookie quantity.
         *
         */
        modifyQuantity : function() {
            $(".cart-items-list").on("change", ".quantity", function() {
                var $container = $(this).closest(".item"),
                    $product_price = $container.find(".price");

                //update the total value
                $product_price.text("$" + ($product_price.data("price") * $(this).val()).toFixed(2));

                //retrieve old data from old object then update the quantity and finally update the object
                var oldData = JSON.parse(localStorage.getItem("_product " + $container.data("product")));
                oldData.quantity = parseInt($(this).val());
                localStorage.setItem("_product " + $container.data("product"), JSON.stringify(oldData));

                cartSliderContainer.view.setBadgeQuantity();
                cartSliderContainer.behaviour.setQuantityCookie();

            });
        },


        /**
         * Create or Update a cookie with the quantity present in the cart.
         * The value of the cookie is encoded in base64 (btoa)
         *
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
        }
    },



    /**
     * Responsible for the view aspect.
     *
     */
    view: {
        /**
         * Slide in the cart-drawer (slider?) when adding items or clicking on the .view-cart trigger.
         *
         */
        slideIn: function () {
            $(".view-cart, .buybutton").on("click", function () {
                $(".cart-drawer").sidebar("toggle");
            });
        },


        /**
         * Update the value of #cart_badge when adding or deleting elements.
         *
         */
        setBadgeQuantity : function() {
            var total = UtilityContainer.getNumberOfProducts();

            $(".cart_badge").text(total);
        }
    },


    init : function() {
        var behaviour = cartSliderContainer.behaviour;
        var view = cartSliderContainer.view;


        view.setBadgeQuantity();
        view.slideIn();


        behaviour.buyButtonClick();
        behaviour.loadItem();
        behaviour.deleteItem();
        behaviour.modifyQuantity();
        behaviour.setQuantityCookie();
    }

};
/**
 * Component responsible for handling the payment overlay behaviour.
 * Entry point is in checkPendingOrders.
 *
 * @type {{cancelOrder: Function, displayUnpaidOverlay: Function, displayCongratulateOverlay: Function, renderAddress: Function, renderAdditionalDetails: Function, checkPendingOrders: Function, init: Function}}
 */
var paymentOverlayContainer = {

    /**
     * Cancel an order.
     * If the user clicks the cancel button, remove the cookie, flush the card, fadeOut the jumbotron then redirect to homepage.
     *
     */
    cancelOrder : function() {
        $("body").on("click", "#cancelOrder", function() {
            Cookies.remove("_current_order");

            $("#cancelledOrder").fadeOut();

            window.location.replace("/");

            UtilityContainer.removeAllProductsFromLocalStorage();
        });
    },


    /**
     * Display the unpaid overlay using semantic-ui modal module.
     *
     */
    displayUnpaidOverlay: function () {
        var order = JSON.parse(Cookies.get('_current_order'));

        var unpaidOverlay =
            '<div class="ui small modal text-center unpaid-modal">' +
                '<i class="close icon"></i>' +
                '<div class="header">' +
                    Localization.pending_order.replace(':command', order.id) +
                '</div>' +
                '<div class="content">' +
                    '<div class="description">' +
                        '<div class="ui header">'  +
                            Localization.what_to_do +
                        '</div>' +
                        '<a href="' + order.payment_url + '">' +
                            '<button class="ui button green" id="payOrder">'+ Localization.pay_now +'</button>'+
                        '</a>' +
                        '<button class="ui button red" id="cancelOrder">'+
                            Localization.cancel_order +
                        '</button>'+
                    '</div>' +
                '</div>' +
            '</div>';

        $("body").prepend(unpaidOverlay);
        $(".small.unpaid-modal").modal("show");

    },


    /**
     * Display the congratulate overlay using semantic-ui modal module.
     *
     * @param order
     */
    displayCongratulateOverlay: function (order) {
        var overlay =
            '<div class="ui modal congratulate-modal payment_successful">' +
                '<div class="header">' +
                    Localization.payment_successful +
                '</div>' +
                '<div class="content">' +
                    '<div class="description">' +
                        '<div class="ui header">' +
                            Localization.summary_below +
                        '</div>' +
                        '<p>' + Localization.summary_copy + '</p>' +
                    '</div>' +
                    '<br/>' +
                    '<table class="ui striped table" style="margin: 0 auto">' +
                        '<tbody class="center aligned">' +
                            '<tr>' +
                                '<td>' + Localization.order + '</td>' +
                                '<td>' + "#" + order.id + '</td>' +
                            '</tr>' +

                            this.renderAdditionalDetails(order) +

                        '</tbody>' +
                    '</table>' +
                '</div>' +
                '<div class="actions">' +
                    '<div class="ui black deny button">' +
                        Localization.close +
                    '</div>' +
                '</div>' +
            '</div>';

        $("body").prepend(overlay);

        $(".congratulate-modal").modal("show");
    },

    /**
     * Render the appropriate address' <td> tags according to the type of address.
     *
     * @param [object] address_details
     * @param [string] address_type_name
     * @returns {string}
     */
    renderAddress: function (address_details, address_type_name) {
        var line2 = address_details.line2 == null ? '' : address_details.line2 + '<br/>';

        return '<tr>' +
                    '<td>' + address_type_name + '</td>' +
                    '<td>' +
                        address_details.name +
                        '<br/>' +
                        address_details.line1 +
                        '<br/>' +
                        line2 +
                        address_details.city +
                        ', ' +
                        address_details.province +
                        ', ' +
                        address_details.postcode +
                        '<br/>' +
                        address_details.country +

                    '</td>' +
                '</tr>';
    },


    /**
     * Check if there are any additional details.
     * If there are, insert them in the summary table.
     *
     * @param order
     * @returns {string}
     */
    renderAdditionalDetails: function (order) {
        if (order.shipping_address != null) {
            return this.renderAddress(order.shipping_address, Localization.shipping_address) +
                    this.renderAddress(order.billing_address, Localization.billing_address) +
                '<tr>' +
                        '<td>' + Localization.subtotal + '</td>' +
                        '<td>' + "$" + parseFloat(order.payment_details.subtotal).toFixed(2) + '</td>' +
                    '</tr>' +

                    '<tr>' +
                        '<td>' + Localization.taxes + '</td>' +
                        '<td>' + "$" + parseFloat(order.payment_details.taxes).toFixed(2) + '</td>' +
                    '</tr>' +

                    '<tr>' +
                        '<td>' + Localization.total + '</td>' +
                        '<td>' + "$" + parseFloat(order.payment_details.total).toFixed(2) + '</td>' +
                    '</tr>';
        }
        else {
            return '';
        }
    },


    /**
     * Checks the status of the current order stored in _current_order cookie.
     *
     * If the order is paid and the call is made by the same user who passed the order,
     * we display a summary. Laravel takes care of the check, as this can raise security
     * concerns...
     *
     */
    checkPendingOrders : function() {

        if (Cookies.get('_current_order')) {

            // Retrieve order details.
            var order = JSON.parse(Cookies.get('_current_order'));

            // Check whether current order has been paid.
            $.ajax({
                type: 'GET',
                url: ApiEndpoints.orders.view.replace(':id', order.id).replace(':verification', order.verification),
                success: function(order_details) {
                    if (order_details.status === 'pending') {
                        this.displayUnpaidOverlay();
                    }
                    else if (order_details.status === 'paid') {
                        // Display congratulation dimmer.
                        this.displayCongratulateOverlay(order_details);

                        // Remove products from cart
                        UtilityContainer.removeAllProductsFromLocalStorage();

                        // Delete the unpaid orders cookie (if any).
                        Cookies.remove('_current_order');
                    }
                    else {
                        Cookies.remove('_current_order');
                    }
                }.bind(this)
            });
        }

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
};

/**
 * Responsible for handling the switch between one, two and four columns per row depending on screen width.
 *
 * @type {{tablet: {setClasses: Function}, mobile: {setClasses: Function}, desktop: {setClasses: Function}, init: Function}}
 */
var responsiveContainer = {
    // Everything between 400px and 768px is considered tablet size.
    tablet : {
        setClasses: function () {
            // Take the stackable off the grid-layout.
            $(".grid-layout").removeClass("stackable");
            // Set two products per row.
            $(".dense-product").removeClass("four wide column").addClass("eight wide column");
        }
    },

    // Everything less than 400px is considered mobile size.
    mobile : {
        setClasses: function () {
            $(".grid-layout").addClass("stackable");
        }
    },

    // Everything more than 768px is considered desktop size.
    desktop: {
        setClasses: function () {
            $(".grid-layout").removeClass("stackable");
            // Set four products per row.
            $(".dense-product").removeClass("eight four wide column").addClass("four wide column");
        }
    },

    init: function () {
        var self = responsiveContainer;

        $(window).on("load resize", function () {
            if ($(this).width() < 768 && $(this).width() > 400) {
                self.tablet.setClasses();
            }
            else if ($(this).width() <= 400) {
                self.mobile.setClasses();
            }
            else if ($(this).width() >= 768) {
                self.desktop.setClasses();
            }
        });
    }
}
/**
 * Component responsible for the view component of each category page.
 *
 * @type {{searchParameters: {page: number, per_page: number, order: string, min_price: null, max_price: null, brands: Array, categories: Array}, blurBackground: Function, itemsPerPage: Function, sortBy: Function, price: Function, categories: Function, brands: Function, updateFilterList: Function, addTag: Function, tags: Function, addFilter: Function, removeFilter: Function, updateFilters: Function, toggleLayout: Function, localizeSwitcher: Function, retrieveSearchParameters: Function, toggleTagsList: Function, localizeDimmer: Function, addDimmer: Function, init: Function}}
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
            categoryContainer.addDimmer();
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
            categoryContainer.addDimmer();
            UtilityContainer.urlAddParameters("order", $(this).data("sort"));
        });

        // Find the text for the selected option.
        $(".sort-by .item").each(function(index, element) {
            if ($(element).data('sort') == categoryContainer.searchParameters.order) {
                $("#sort-by-box").dropdown("set selected", $(element).data('sort'));
                return false;
            }
        });
    },

    /**
     * Adds the price filter to the search query and updates the filter on the page.
     *
     */
    price: function() {

        $("#price-update").on("click", function()
        {
            categoryContainer.addDimmer();

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
     * Adds the category filter to the search query and updates the filter on the page.
     *
     */
    categories: function() {
        this.updateFilterList($("#refine-by-category"), "categories");
    },

    /**
     * Adds the brands filter to the search query and updates the filter on the page.
     *
     */
    brands: function() {
        this.updateFilterList($("#refine-by-brand"), "brands");
    },

    /**
     * Shortcut to handle filter lists such as brands and categories.
     *
     * @param element
     * @param filterType
     */
    updateFilterList : function(element, filterType)
    {
        // Add the event listeners to each child element.
        element.find(".item").on("change",
            {
                filter : filterType || "brands"
            },

            function(event)
            {
                var id = $(this).data("filter"),
                    filterList = categoryContainer.searchParameters[event.data.filter],
                    filter = $(this);

                // If the checkbox is checked, add the filter to the list.
                if (filter.prop("checked")) {
                    categoryContainer.addFilter(event.data.filter, id);
                }

                // If not, then remove it from the list.
                else {
                    categoryContainer.removeFilter(event.data.filter, id);
                }
            }
        );

        // Update selected checkboxes. IDs are stored as strings in "categoryContainer.searchParameters".
        element.find(".item").each(function() {

            $(this).prop("checked", categoryContainer.searchParameters[filterType].indexOf(""+ $(this).data("filter")) > -1);

            // And add the filter as a tag.
            if ($(this).prop("checked")) {
                categoryContainer.addTag($(this));
            }
        });
    },

    /**
     * Create a new tag to be appended to the tags list.
     *
     * @param filter (filter being the checkbox DOM node)
     */
    addTag: function (filter) {
        var item =
        '<div class="item">' +
        '<a class="ui grey tag label">' + filter.data("name") +
        '<i class="icon remove right floated" data-id="' + filter.data("filter") + '" data-type="' + filter.data('type') + '"></i>' +
        '</a>' +
        '</div>';

        $(".tags-list").append(item);
    },

    /**
     * Attaches the remove event to the tags.
     *
     */
    tags: function() {
        $(".tags-list .item .remove").on("click", function() {
            categoryContainer.removeFilter($(this).data('type'), $(this).data('id'));
        });
    },

    /**
     * Adds a filter and refreshes the page.
     *
     * @param filterType    Either "brands" or "categories".
     * @param id            ID of brand or category.
     */
    addFilter: function(filterType, id) {
        this.searchParameters[filterType].push(id);
        this.updateFilters(filterType);
    },

    /**
     * Removes a filter and refreshes the page.
     *
     * @param filterType    Either "brands" or "categories".
     * @param id            ID of brand or category.
     */
    removeFilter: function(filterType, id) {

        // Retrieve filter list.
        var filterList = this.searchParameters[filterType], newList = [];

        // Rebuild a new list, without the filter we want removed.
        if (filterList.length > 1) {
            for (var index in filterList) {
                if (filterList[index] != id) {
                    newList.push(filterList[index]);
                }
            }
        }

        this.searchParameters[filterType] = newList;
        this.updateFilters(filterType);
    },

    updateFilters: function(filterType) {

        // Reorder filter list (this will help with caching on Laravel's end).
        var filterList = this.searchParameters[filterType];
        filterList.sort(function(a, b) {
            return a - b;
        });

        // If we have filters, update the query string and refresh the page.
        if (filterList.length > 0) {
            var filter = filterList.length > 1 ? filterList.join(';') : filterList[0];
            categoryContainer.addDimmer();
            UtilityContainer.urlAddParameters(filterType, filter);
        }

        // If we don't have any filters left, refresh the page without the filter parameter.
        else {
            UtilityContainer.urlRemoveParameters(filterType);
        }
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

    toggleTagsList: function () {
        $(".tags-list").children().size() > 0 ? $(".tags-list").parent().removeClass("hidden") : "";
    },

    /**
     * Localize the dimmer text with the appropriate message.
     *
     */
    localizeDimmer: function () {
        $(".loading-text").text(Localization.loading);
    },

    /**
     * Add a dimmer to the body when adding / removing a new filter.
     *
     */
    addDimmer: function () {
        var dimmer =
        '<div class="ui page dimmer loading-dimmer">' +
        '<div class="content">' +
        '<div class="center">' +
        '<div class="ui text loader">' +
        '<h1 class="ui header loading-text white"></h1></div>' +
        '</div>' +
        '</div>';

        $("body").append(dimmer);

        categoryContainer.localizeDimmer();

        $('.ui.dimmer.loading-dimmer')
            .dimmer('show')
        ;
    },

    init: function () {
        var self = categoryContainer;

        self.retrieveSearchParameters();
        self.blurBackground();
        self.itemsPerPage();
        self.sortBy();
        self.price();
        self.categories();
        self.brands();
        self.tags();
        self.toggleLayout();
        self.toggleTagsList();
    }
};

/**
 * Component responsible for specific behaviours of homepage sections.
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

    bootstrap: function () {
        $(".indicator-down:first").hide();
        $(".section-title:first").hide();
    },

    init: function () {
        var self = homepageContainer,
            mixed = self.mixed;

        mixed.toggleSixteenWideColumn();
        self.bootstrap();
    }
}
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
                            type   : 'postalCode[shippingCountry]',
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
                        },
                        {
                            type   : 'postalCode[billingCountry]',
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
/**
 * Component responsible for handling different formats of the same product.
 *
 * @type {{productWithFormat: Function, productWithoutFormat: Function, updateBuybuttonAttributes: Function, updateProductInformation: Function, init: Function}}
 */
var productFormatContainer = {

    /**
     * Update price value for a product with format.
     *
     * @param option
     */
    productWithFormat: function(option) {
        var price = '<span class="text-strikethrough">' +
            'CAD $ ' + option.find(":selected").data("price") +
            '</span>' +
            '<span id="product-price" class="strong text-danger">' +
            'CAD $ ' + option.find(":selected").data("reduced") +
            '</span>';

        $(".sub.header").text(price);
    },


    /**
     * Update price value for a format-less product.
     *
     * @param option
     */
    productWithoutFormat: function(option) {
        // Change description.
        $("#product-format-name").text(option.find(":selected").data("format"));
        $("#product-price").text("CAD $ " + option.find(":selected").data("price"));
    },


    /**
     * Update buybutton data attributes according to format: id/price/name/format.
     *
     * @param option
     */
    updateBuybuttonAttributes: function (option) {
        $(".buybutton").attr({
            'data-product': option.val(),
            'data-price': option.find(":selected").data("price"),
            'data-name': option.find(":selected").data("name"),
            'data-format': option.find(":selected").data("format")
        });
    },

    /**
     * Main function of this module.
     * Once the format selector is clicked, trigger the appropriate helpers then update buybutton.
     *
     */
    updateProductInformation: function() {
        var self = productFormatContainer;

        $("#product-format").on("change", function () {

            if ($(this).find(":selected").data("reduced")) {
                // Add discounted price for a product with different formats.
                self.productWithFormat($(this));
            }
            else {
                // Add discounted price for a single format product.
                self.productWithoutFormat($(this));
            }


            // Update buybutton with right attributes.
            self.updateBuybuttonAttributes($(this));
        });

    },

    /**
     * Entry point of this module.
     *
     */
    init: function () {
        const self = productFormatContainer;

        self.updateProductInformation();

    }
}
/**
 * Component responsible for adding products to a user's wishlist.
 *
 * @type {{fadeInFavoriteIcon: Function, setPopupText: Function, setWishlistBadgeQuantity: Function, addToFavorite: Function, persistFavorite: Function, removeFromFavorite: Function, init: Function}}
 */
var productLayoutFavoriteContainer = {
    /**
     * Fade in the favorite icon (heart icon) when hovering on a product tile.
     *
     */
    fadeInFavoriteIcon: function() {
        self = productLayoutFavoriteContainer;

        $(".dense-product").hover(function() {

            $(this).children(".favorite-wrapper").fadeIn();
            self.setPopupText($(this).children(".favorite-wrapper"));

        }, function () {
            $(this).children(".favorite-wrapper").hide();
        });
    },

    /**
     * Set popup text according to current state of the wrapper.
     *
     * @param wrapper
     */
    setPopupText: function (wrapper) {
        if($(wrapper).hasClass("favorited")){
            $(wrapper).attr("title", Localization.wishlist_remove);
        }
        else {
            $(wrapper).attr("title", Localization.wishlist_add);
        }
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

                //Set the favorite icon to be displayed
                $(this).addClass("favorited");

                //Set wishlist badge quantity
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
        }
    },

    /**
     * Delete the clicked element from the wish list.
     *
     * @param element
     * @param context
     */
    removeFromFavorite: function (element, context) {
        element.removeClass("favorited");
        localStorage.removeItem("_wish_product " + element.data("product"));
        context.setWishlistBadgeQuantity();
    },

    init: function () {
        var self = productLayoutFavoriteContainer;

        self.setPopupText();
        self.addToFavorite();
        self.persistFavorite();
        self.fadeInFavoriteIcon();
        self.setWishlistBadgeQuantity();
    }
}
/**
 * Component responsible for changing quantity on the product page view.
 *
 * @type {{addQuantity: Function, removeQuantity: Function, updateBuyButton: Function, init: Function}}
 */
var productQuantityContainer = {
    addQuantity : function(input, callback) {
        $(".qty-selector[data-action='add']").on("click", function() {
            input.val(parseInt(input.val()) + 1);

            callback();
        });
    },

    removeQuantity: function(input, callback) {
        $(".qty-selector[data-action='remove']").on("click", function() {
            var actual = parseInt(input.val());

            if (actual > 1) {
                input.val(parseInt(input.val()) - 1);
            }

            callback();
        });
    },

    updateBuyButton: function() {
        $(".buybutton").attr("data-quantity", $(".qty-selector-input").val());
    },

    init: function () {
        var self = productQuantityContainer;
        self.addQuantity($(".qty-selector-input"), self.updateBuyButton);
        self.removeQuantity($(".qty-selector-input"), self.updateBuyButton);

    }
};
/**
 * Component responsible for activating semantic ui features.
 *
 * @type {{module: {initDropdownModule: Function, initRatingModule: Function, initPopupModule: Function, initCheckboxModule: Function}, behaviors: {closeDimmer: Function}, init: Function}}
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
            $(".ui.dropdown").dropdown();

            $(".ui.dropdown").on("click", function () {
                var action = $(this).data("action") || "activate";

                $(this).dropdown({
                    action: action
                });
            });
        },

        /**
         * Initialize rating module.
         *
         */
        initRatingModule: function () {
            $(".ui.rating").rating();
        },

        /**
         * Initialize popup module.
         *
         */
        initPopupModule: function () {
            $(".popup").popup();
        },

        /**
         * Initialize checkbox module.
         *
         */
        initCheckboxModule: function () {
            $('.ui.checkbox')
                .checkbox()
            ;
        },

        /**
         * Initialize accordion module.
         *
         */
        initAccordionModule: function() {
            $('.ui.accordion').accordion();
        }
    },

    /**
     * Specify semantic custom behavior.
     *
     */
    behaviors: {
        closeDimmer: function () {
            $(".close-dimmer").on("click", function() {
                $(".dimmer").dimmer("hide");
            });
        }
    },

    /**
     * Specify custom form validation rules.
     *
     */
    rules: {
        postalCode: function() {
            $.fn.form.settings.rules.postalCode = function(value, fieldIdentifier) {
                if($('#checkboxSuccess').is("checked") && fieldIdentifier === "billingCountry") {
                    return true;
                } else {
                    if ($("#" + fieldIdentifier).val() === "CA")
                        return value.match(/^[ABCEGHJKLMNPRSTVXY]{1}\d{1}[A-Z]{1} ?\d{1}[A-Z]{1}\d{1}$/i) ? true : false;
                    else if ($("#" + fieldIdentifier).val() === "US")
                        return value.match(/^\d{5}(?:[-\s]\d{4})?$/) ? true : false;
                    else {
                        return true;
                    }
                }
            }
        }
    },


    init: function () {
        var self = semanticInitContainer,
            module = self.module,
            behaviors = self.behaviors,
            rules = self.rules;

        module.initDropdownModule();
        module.initRatingModule();
        module.initPopupModule();
        module.initCheckboxModule();
        module.initAccordionModule();

        behaviors.closeDimmer();

        rules.postalCode();
    }
}
/**
 * Component responsible for handling the logic of the wish list page.
 * Layout handled in dev/components/site/wishlist.js
 *
 * @type {{createWishlistElement: Function, renderWishlist: Function, localizeWishlistButton: Function, removeWishlistElement: Function, init: Function}}
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
 * Component responsible for the view component of the wish list page.
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
; (function(window, document, $) {
    $(document).ready(function () {

        /**
         * Sets up the ajax token for all ajax requests.
         *
         */
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'locale': $('html').attr('lang')
            }
        });

        /**
         * Sets up Localization and ApiEndpoints variables.
         *
         */
        var env = UtilityContainer.getLocalizationAndEndpointUrl().responseJSON;
        Localization = env.Localization;
        ApiEndpoints = env.ApiEndpoints;

        /**
         * Initialize semantic UI modules.
         *
         */
        semanticInitContainer.init();

        /**
         * Initialize responsiveness feature.
         *
         */
        responsiveContainer.init();

        /**
         * Initialize checkout logic.
         *
         */
        checkoutContainer.init();

        /**
         * Initialize cart slider logic.
         *
         */
        cartSliderContainer.init();

        /**
         * Initialize category container.
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
         * Initialize product quantity change.
         *
         */
        productQuantityContainer.init();

        /**
         * Initialize wishlist page.
         *
         */
        wishlistLogicContainer.init();

    });

})(window, window.document, jQuery, undefined)
