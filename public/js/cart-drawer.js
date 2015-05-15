var cartDisplay = {
    $el : {
        $back : $("#back"),
        $proceed : $("#proceed"),
        $trigger : $(".view-cart"),
        $container : $("#cart-container"),
        $checkout : $("#checkout"),
        $body : $("body")
    },

    displayOn: function() {
        _width = cartDisplay.$el.$container.width();
        cartDisplay.$el.$container.css( {
            "margin-right" : -_width
            //"margin-right" : 0
        });

        cartDisplay.$el.$trigger.click(function() {
            cartDisplay.animateIn();
        });
    },

    displayOff : function() {
        _width = cartDisplay.$el.$container.width();
        cartDisplay.$el.$back.click(function() {
            cartDisplay.animateOut();
        });
        cartDisplay.$el.$checkout.click(function() {
            sessionStorage.isDisplayed = false;
        });
    },

    animateIn : function() {
        cartDisplay.$el.$container.show();
        cartDisplay.$el.$container.animate( {
            "margin-right" : 0
        }, 400);
        sessionStorage.isDisplayed = true;
    },

    animateOut: function() {
        _width = cartDisplay.$el.$container.width();
        cartDisplay.$el.$container.animate( {
            "margin-right" : -_width
        }, 400, function() {
            $(this).hide();
        });
        sessionStorage.isDisplayed = false;
    },


    init : function() {
        cartDisplay.displayOn();
        cartDisplay.displayOff();

        if (sessionStorage.isDisplayed == "true")
        {
            cartDisplay.$el.$container.css("margin-right", 0);
            cartDisplay.$el.$container.show();
        }

    }
};

var cartData = {
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
        var price = parseInt(item.quantity) * parseFloat(item.price);

        var sidebarElement = '<li class="w-box animated bounceInDown" data-product="' + item.product + '" data-quantity=1>' +
            '<div class="col-xs-3 text-center"><img src=' + item.thumbnail_lg + ' class="img-responsive"></div>' +
            '<div class="col-xs-9 no-padding-left">' +
            '<div class="row"><div class="col-xs-10"><h3 class="product-name">' + item.name + '</h3></div><div class="col-xs-2"><h4 class="text-right"><i class="fa fa-trash fa-1 close-button"><span class="sr-only">Remove Item</span></i></h4></div></div>' +
            '<div class="row"><div class="col-xs-8"><div class="input-group"><input type="number" class="quantity form-control input-sm" min="1" step="1" value="' + item.quantity + '">' +
            '<span class="input-group-addon update_quantity_indicator"><i class="fa" hidden><span class="sr-only">' + "Update quantity" + '</span></i></span></div></div>' +
            '<div class="col-xs-4 product-price text-right" data-price="' + item.price + '">$' + price  + '</div></div>' +
            '</div>' +
            '</li>';

        if (!$(".cart-items-list [data-product='" + item.product + "']").length){
            cartData.$el.$list.append(sidebarElement);
        }

    },

    /**
     * Store a product in sessionStorage
     * Update badge quantity
     * Create/update a quantity cookie
     *
     * @param item JSON format converted from attributes on the .buybutton
     */
    storeItem : function(item) {
        sessionStorage.setItem("_product " + item.product, JSON.stringify(item));
        cartData.setBadgeQuantity();
        cartData.setQuantityCookie();
    },

    /**
     * Load a list of items previously bought into the cart.
     * If there is no item in sessionStorage starting with the key "_product", then nothing is loaded.
     */
    loadItem : function() {
        for(var i = 0; i<sessionStorage.length; i++)
        {
            if (sessionStorage.key(i).lastIndexOf("_", 0) === 0)
            {
                cartData.addItem(JSON.parse(sessionStorage.getItem(sessionStorage.key(i))));
            }
        }
    },

    /**
     * Delete an item from the cart drawer list.
     * Remove it from the DOM.
     * Delete the object on sessionStorage.
     * Set Badge quantity accordingly.
     * Update Cookie quantity accordingly.
     */
    deleteItem: function() {
        $(document).on('click', ".close-button", function() {
            $parent = $(this).closest(".animated").addClass("animated bounceOutLeft");
            $parent.one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
                $(this).remove();
            });

            sessionStorage.removeItem("_product " + $(this).closest(".animated").data("product"));

            cartData.setBadgeQuantity();
            cartData.setQuantityCookie();

        });
    },

    /**
     * Modify the quantity of a product in the cart
     * Update its price label accordingly
     * Update the sessionStorage
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
            var oldData = JSON.parse(sessionStorage.getItem("_product " + $container.data("product")));
            oldData.quantity = parseInt($(this).val());
            sessionStorage.setItem("_product " + $container.data("product"), JSON.stringify(oldData));

            cartData.setBadgeQuantity();
            cartData.setQuantityCookie();

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
     * Utility function returning the number of products present in the cart.
     *
     * @returns {number}
     */
    getNumberOfProducts : function() {
        var total = 0;

        for(var i = 0; i<sessionStorage.length; i++)
        {
            if (sessionStorage.key(i).lastIndexOf("_", 0) === 0)
            {
                total += JSON.parse(sessionStorage.getItem(sessionStorage.key(i))).quantity;
            }
        }

        return total;
    },

    /**
     * Update the value of #cart_badge when adding or deleting elements
     */
    setBadgeQuantity : function() {
        var total = cartData.getNumberOfProducts();

        $("#cart_badge").text(total);
    },

    /**
     * Create or Update a cookie with the quantity present in the cart.
     * The value of the cookie is encoded in base64 (btoa)
     */
    setQuantityCookie : function () {
        var number = cartData.getNumberOfProducts();

        if (Cookies.get("quantityCart") == undefined || number === 0)
        {
            Cookies.set("quantityCart", btoa("0"));
        }
        else {
            Cookies.set("quantityCart", btoa(number));
        }
    },

    /**
     * parse the information form the button into a readable json format
     *
     * @param item
     * @returns {{product: *, name: *, price: *, thumbnail: *, thumbnail_lg: *, quantity: number}}
     */
    button_to_Json : function(item) {
        return {
            "product" : item.data("product"),
            "name" : item.data("name"),
            "price" : item.data("price"),
            "thumbnail" : item.data("thumbnail"),
            "thumbnail_lg" : item.data("thumbnail_lg"),
            "quantity" : parseInt(item.data("quantity"))
        }
    },

    init : function() {
        cartData.setBadgeQuantity();
        cartData.loadItem();
        cartData.deleteItem();
        cartData.modifyQuantity();
        cartData.modifyQuantityBeforeBuying();
        cartData.setQuantityCookie();
    }
};

$(document).ready(function() {
    cartDisplay.init();
    cartData.init();

    $(".buybutton").click(function() {
        cartDisplay.animateIn();
        cartData.addItem(cartData.button_to_Json($(this)));
        cartData.storeItem(cartData.button_to_Json($(this)));
    });

});