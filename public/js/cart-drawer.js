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

        if (sessionStorage.isDisplayed == "true")
        {
            cartDisplayContainer.$el.$container.css("margin-right", 0);
            cartDisplayContainer.$el.$container.show();
        }

    }
};

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
            '<div class="row"><div class="col-xs-8"><div class="input-group"><input type="number" class="quantity form-control input-sm" min="1" step="1" value="' + item.quantity + '">' +
            '<span class="input-group-addon update_quantity_indicator"><i class="fa" hidden><span class="sr-only">' + "Update quantity" + '</span></i></span></div></div>' +
            '<div class="col-xs-4 product-price text-right" data-price="' + item.price + '">$' + price  + '</div></div>' +
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
    },

    /**
     * Load a list of items previously bought into the cart.
     * If there is no item in localStorage starting with the key "_product", then nothing is loaded.
     */
    loadItem : function() {
        for(var i = 0; i<localStorage.length; i++)
        {
            if (localStorage.key(i).lastIndexOf("_", 0) === 0)
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

        $("#cart_badge").text(total);
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

    setCartSubtotal : function () {
        $("dd#subtotal").text("$" + UtilityContainer.getProductsPriceFromLocalStorage().toFixed(2));
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
        cartLogicContainer.setBadgeQuantity();
        cartLogicContainer.loadItem();
        cartLogicContainer.deleteItem();
        cartLogicContainer.modifyQuantity();
        cartLogicContainer.modifyQuantityBeforeBuying();
        cartLogicContainer.setQuantityCookie();
        cartLogicContainer.setCartSubtotal();
    }
};

/*
    TODO: 1. Flusher le contenu du cart mais conserver l’ID de la commande qui nous est passée quand client cliques sur checkout et on POST sur /Orders
    TODO: 2. Placer l’ID quelque part, genre session php ou même la passer au browser de l'utilisateur pour le conserver, session ou cookie (avantage de théoriquement pouvoir syncer avec son téléphone ou son autre ordinateur)
    TODO: 3. Rediriger l’utilisateur vers la page de paiement et espérer qu’il paie.
    TODO: 3.a. S’il paie, supprimer l’ID de la commande de sa session/cookie et afficher un mot de félicitation
    TODO: 3.b s’Il ne paie pas, et tant que l’ID est présent sur la commande, l'embêter avec un bandeau bien visible en haut de chaque page qui lui présente un lien de paiement pour sa commande précédente.
 */

$(document).ready(function() {
    cartDisplayContainer.init();
    cartLogicContainer.init();
    cartDisplayContainer.setCartItemsHeight();

    $(".buybutton").click(function() {
        cartDisplayContainer.animateIn();
        cartLogicContainer.addItem(cartLogicContainer.button_to_Json($(this)));
        cartLogicContainer.storeItem(cartLogicContainer.button_to_Json($(this)));
    });

});