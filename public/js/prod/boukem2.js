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
        _width = cartDisplayContainer.$el.$container.width();
        cartDisplayContainer.$el.$container.css( {
            "margin-right" : -_width
        });

        cartDisplayContainer.$el.$trigger.click(function() {
            cartDisplayContainer.animateIn();
        });
    },

    displayOff : function() {
        _width = cartDisplayContainer.$el.$container.width();
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
        _width = cartDisplayContainer.$el.$container.width();
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
        UtilityContainer.populateCountry();

        if (sessionStorage.isDisplayed == "true")
        {
            cartDisplayContainer.$el.$container.css("margin-right", 0);
            cartDisplayContainer.$el.$container.show();
        }

    }
};
/**
 * Object responsible for displaying the navigation header.
 *
 * @type {{md: {removeCartDescription: Function}, sm: {btnTransform_sm: Function}, init: Function}}
 */
var headerContainer = {
    /**
     * Desktop size
     *
     */
    md: {
        removeCartDescription : function() {
            if ($(window).width() <= 1195) {
                $("#nav-right #cart-description").text("");
                $("#nav-right").css("padding-bottom", "18px");
            }
        }
    },

    /**
     * Tablet size
     *
     */
    sm : {
        btnTransform_sm : function() {
            if ($(window).width() <= 934 && ($(window).width() >= 769)) {
                $(".row:first .btn").addClass("btn-sm");
                $("#searchBar").addClass("input-sm");
                $("#view-cart-wrapper").addClass("btn-xs btn-xs-btn-sm-height");
            }
        }
    },

    /**
     * Changes text from dropdown button within the parent node passed in the argument
     *
     * @param $elem
     */
    changeTextFromDropdown : function($elem) {
        $($elem + " .dropdown-menu li a").click(function(){

            $($elem + " .btn:first-child").html($(this).text() + '<span class=\"caret\"></span>');
            $($elem + " .btn:first-child").val($(this).text());

        });
    },

    /**
     * Object responsible for handling all semantic ui modules (to be refactored eventually into its own object).
     *
     */
    semanticUI: {

        /**
         * Initialize dropdown module.
         *
         */
        initDropdownModule : function() {
            $(".ui.dropdown").dropdown({
                    action: "select"
                }
            );
        }
    },

    /**
     * Register functions in event handler (onload, onresize) to be called outside of this object.
     *
     */
    init: function () {
        var self = headerContainer;

        $(window).on("load resize", function() {
            self.md.removeCartDescription();
            self.sm.btnTransform_sm();
        });

        self.changeTextFromDropdown(".search-filter");

        //Initialize Semantic UI component
        self.semanticUI.initDropdownModule();
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
            '<a href="'+
            ApiEndpoints.orders.pay.replace(':id', order.id)
                .replace(':verification', order.verification) +'">'+
            '<button class="btn btn-success" id="payOrder">'+ Localization.pay_now +'</button>'+
            '</a>'+
            '<button class="btn btn-danger" id="cancelOrder">'+
            Localization.cancel_order +
            '</button>'+
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
 * Object responsible for the view component of the favorite feature.
 * Logic handled in dev/actions/products/layout-favorite-logic.js
 *
 * @type {{fadeInFavoriteIcon: Function, setWishlistBadgeQuantity: Function, init: Function}}
 */
var productLayoutFavoriteContainer = {
    /**
     * Fade in the favorite icon (heart icon) when hovering on a product tile.
     *
     */
    fadeInFavoriteIcon: function() {
        $(".dense_product").hover(function() {
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

    init: function () {
        var self = productLayoutFavoriteContainer;

        self.fadeInFavoriteIcon();
        self.setWishlistBadgeQuantity();
    }
}
/**
 * Object responsible for the view component of each category page.
 *
 * @type {{blurBackground: Function, init: Function}}
 */
var categoryContainer = {

    /**
     * Blurs the background of each category's page header.
     *
     */
    blurBackground: function () {
        $(".category-header").blurjs({
            source: ".category-header"
        });
    },

    init: function () {
        var self = categoryContainer;

        self.blurBackground();
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
            "link" : item.data("link")
        }
    },

    /**
     * Utility function to populate a select list (#country) with a list of country (json formatted).
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
 * Object responsible for the overall logic (CRUD) of the cart drawer.
 * Layout handled in dev/components/layout/cart-drawer.js
 *
 * @type {{$el: {$list: (*|jQuery|HTMLElement)}, addItem: Function, storeItem: Function, loadItem: Function, deleteItem: Function, modifyQuantity: Function, modifyQuantityBeforeBuying: Function, setBadgeQuantity: Function, setQuantityCookie: Function, setCartSubtotal: Function, setCartShipping: Function, setCartTaxes: Function, setCartTotal: Function, button_to_Json: Function, ajaxCall: Function, updateAjaxCall: Function, init: Function}}
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

        var sidebarElement = '<li class="w-box animated bounceInDown" data-product="' + item.product + '" data-quantity=1>' +
            '<div class="col-xs-3 text-center"><img src=' + item.thumbnail_lg + ' class="img-responsive"></div>' +
            '<div class="col-xs-9 no-padding-left">' +
            '<div class="row"><div class="col-xs-10"><h3 class="product-name">' + item.name + '</h3></div><div class="col-xs-2"><h4 class="text-right"><i class="fa fa-trash fa-1 close-button"><span class="sr-only">Remove Item</span></i></h4></div></div>' +
            '<div class="row"><div class="col-xs-8">' +
            '<div class="input-group"><label for="products[' + item.product + '][quantity]" class="sr-only">'+ item.name + ":" + item.price +'</label>' +
            '<input type="number" class="quantity form-control input-sm" min="1" step="1" value="' + item.quantity + '" name="products[' + item.product + '][quantity]">' +
            '<span class="input-group-addon update_quantity_indicator"><i class="fa" hidden><span class="sr-only">' + "Update quantity" + '</span></i></span></div></div>' +
            '<div class="col-xs-4 product-price text-right" data-price="' + item.price + '">$' + price  + '<span class="sr-only">' + $ + item.price + '</span></div></div>' +
            '<input type="hidden" name="products[' + item.product + '][id]" value="' + item.product + '"/> ' +
            '</div>' +
            '</li>';

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
            $container = $(this).closest("li");
            $product_price = $container.find(".product-price");

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
     * Only used in a product page.
     * Assuming the DOM has (and will keep) this structure:
     *      .form-group
     *          #item-quantity
     *      .buybutton
     */
    modifyQuantityBeforeBuying : function() {
        $("#item_quantity").on("change", function() {
            $(this).closest(".form-group").next().data("quantity", parseInt($(this).val()));
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
            error: function(e, status) {
                console.log(e);
            },
            complete : function(data) {
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
 * Container responsible for handling the logic of adding products to a user's wishlist.
 * Layout handled in dev/components/products/layout/product-layout-favorite.js
 *
 * @type {{addToFavorite: Function, persistFavorite: Function, removeFromFavorite: Function, init: Function}}
 */
var productLayoutFavoriteLogicContainer = {

    /**
     * Add the clicked product to the wish list.
     *
     */
    addToFavorite: function() {
        var self = productLayoutFavoriteLogicContainer,
            selfLayout = productLayoutFavoriteContainer,
            item;

        $(".favorite-wrapper").on("click", function() {
            //No favorited class.
            if (!$(this).hasClass("favorited")) {
                item = UtilityContainer.buyButton_to_Json($(this).parent().find(".buybutton"));
                localStorage.setItem("_wish_product " + item.product, JSON.stringify(item));

                $(this).addClass("favorited");

                selfLayout.setWishlistBadgeQuantity();
            }
            else
            //Has a favorited class. We remove it, then delete the element from local Storage.
            {
                self.removeFromFavorite($(this), selfLayout);
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
                    if(JSON.parse(localStorage.getItem(localStorage.key(i))).product === parseInt($(".favorite-wrapper")[j].dataset.product))
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
        var self = productLayoutFavoriteLogicContainer;

        //Calls the layout container (productLayoutFavoriteContainer).
        productLayoutFavoriteContainer.init();

        //Initialize the logic.
        self.addToFavorite();
        self.persistFavorite();
    }
}
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
            '<div class="col-md-12 list-layout-element">' +
            '<div class="col-md-2">' +
            '<img src=' + item.thumbnail_lg + '>' +
            '</div>' +
            '<div class="col-md-10">' +
            '<button class="btn btn-outline btn-danger-outline pull-right btn-lg inline-block padding-side-lg removeFavoriteButton" data-product="' + item.product + '">Remove from wishlist </button>' +
            '<button class="btn btn-success buybutton pull-right btn-lg inline-block padding-side-lg"' +
            'data-product="' + item.product + '"' +
            'data-price="' + item.price + '"' +
            'data-thumbnail="' + item.thumbnail + '"' +
            'data-thumbnail_lg="' + item.thumbnail_lg + '"' +
            'data-name="' + item.name + '"' +
            'data-quantity="' + item.quantity  + '"' + ">" +
            'Add to cart </button>' +
            '<a href=' + item.link + '><h4 style="margin-top: 5px">' + item.name + '</h4></a>' +
            '<h5> $ ' + parseFloat(Math.round(item.price * 100) / 100).toFixed(2) + '</h5>'+
            '</div>' +
            '</div>';

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
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

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
     * Initialize navigation header.
     *
     */
    headerContainer.init();

    /**
     * Initialize favorite products feature.
     *
     */
    productLayoutFavoriteLogicContainer.init();

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
//# sourceMappingURL=boukem2.js.map