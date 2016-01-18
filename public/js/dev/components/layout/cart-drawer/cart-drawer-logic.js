/**
 * Component responsible for the overall logic (CRUD) of the cart drawer.
 * Layout handled in dev/components/layout/cart-drawer/cart-drawer-display.js
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
        '<i class="remove icon large pull-right close-button"></i>' +
        '</div>' +
        '<div class="content cart-content">' +
        '<span style="padding: 0 1.7531% 0 0">'+ Localization.quantity + '</span>' +
        '<div class="ui input one-quarter">' +
        '<input type="number" class="quantity" min="1" step="1" value="' + item.quantity + '" name="products[' + item.product + '][quantity]">' +
        '<input type="hidden" name="products[' + item.product + '][id]" value="' + item.product + '"/> ' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>';

        if (!$(".cart-items-list [data-product='" + item.product + "']").length){
            $(".cart-items-list").append(sidebarElement);
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

            // We have two cart-items-list now ! (cart drawer and dimmer)
            // So when deleting on one list, we should also delete the item on the other.
            var $parent = $(this).closest(".animated").addClass("animated bounceOutLeft"),
                $otherList = $(this).closest(".cart-items-list").hasClass("dimmered") ?
                    $(".cart-items-list").not(".dimmered") :
                    $(".cart-items-list.dimmered");

            $parent.one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
                $(this).remove();
                $otherList.find('[data-product="' + $parent.data("product") + '"]').remove();
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
        $(".cart-items-list").on("change", ".quantity", function() {
            var $container = $(this).closest(".item"),
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

                // Check if GAE is defined, and if does, register the event: Estimate
                if (window.ga && ga.create) {
                    GAEAnalytics.events.estimate(UtilityContainer.getCartTotal(UtilityContainer.getCheapestShippingMethod(data), data));
                }
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
            $(".getEstimate").html(Localization.update);
            $(".price-estimate-update").fadeOut();
            $(".price-estimate").fadeIn();

        });

        //TODO: Refactor the arbitrary xxxxms to an actual end of ajax call.

        $(".price-estimate-update .getEstimate").click(function() {
            if(!UtilityContainer.validateEmptyCart()) {
                setTimeout(function() {
                    $(".price-estimate-update .getEstimate").parent().fadeOut(300);
                    $(".price-estimate-update .getEstimate").html(Localization.update);
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
