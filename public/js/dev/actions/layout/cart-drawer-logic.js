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
            '<div class="row"><div class="col-xs-8"><div class="input-group"><input type="number" class="quantity form-control input-sm" min="1" step="1" value="' + item.quantity + '" name="products[' + item.product + '][quantity]">' +
            '<span class="input-group-addon update_quantity_indicator"><i class="fa" hidden><span class="sr-only">' + "Update quantity" + '</span></i></span></div></div>' +
            '<div class="col-xs-4 product-price text-right" data-price="' + item.price + '">$' + price  + '</div></div>' +
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