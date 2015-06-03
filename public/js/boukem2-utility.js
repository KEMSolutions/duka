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
            "country" : $("#country").val(),
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
     * Utility function to scroll the body to the estimate table
     *
     */
    scrollTopToEstimate : function() {
        $('html, body').animate({
            scrollTop: $("#estimate").offset().top
        }, 1000);
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
     * Strip HTML tags from a string.
     * @param string html   The string to be stripped.
     * @return string       The stripped result.
     */
    stripTags: function(html) {
        var tmp = document.createElement("DIV");
        tmp.innerHTML = html;
        return tmp.textContent || tmp.innerText || "";
    }
}