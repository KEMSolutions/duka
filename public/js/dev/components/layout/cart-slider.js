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
            $("body").on("click", ".buybutton, .buybutton_impulse", function() {

                cartSliderContainer.behaviour.addItem(UtilityContainer.buyButton_to_Json($(this)));
                cartSliderContainer.behaviour.storeItem(UtilityContainer.buyButton_to_Json($(this)));

                // We remove the "Your cart is empty" message at the top every time we add an item.
                $("#empty-cart").addClass("hidden");
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
                '<div class="ui input small">' +
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
         * Store a product in Cookies.
         * Update badge quantity.
         * Create/update a quantity cookie.
         *
         * @param item JSON format converted from attributes on the .buybutton
         */
        storeItem : function(item) {
            if(Cookies.get("_product_" + item.product) != undefined)
            {
                // Update the Cookie value of an already existing product.
                var quantity_updated = JSON.parse(Cookies.get("_product_" + item.product)).quantity + 1;

                // Update the input value already displayed in the cart drawer.
                $("input[name='products[" + item.product + "][quantity]']").attr("value", quantity_updated);

                // Set the item.
                Cookies.set("_product_" + item.product,
                    {
                        product : item.product,
                        name : item.name,
                        price : item.price,
                        thumbnail : item.thumbnail,
                        thumbnail_lg : item.thumbnail_lg,
                        quantity : quantity_updated,
                        link : item.link,
                        description : item.description
                    }
                );
            }
            else {
                Cookies.set("_product_" + item.product, item);
            }
            UtilityContainer.setBadgeQuantity();
            cartSliderContainer.behaviour.setQuantityCookie();
            cartSliderContainer.view.setSubtotal();
        },


        /**
         * Load a list of items previously bought into the cart.
         * If there is no item in Cookies starting with the key "_product", then nothing is loaded.
         */
        loadItem : function() {
            var cookies = Cookies.toObject();

            for (var item in cookies) {
                if (item.indexOf("_product_", 0) === 0) {
                    $("#empty-cart").addClass("hidden");
                    cartSliderContainer.behaviour.addItem(JSON.parse(Cookies.get(item)));
                }
            }
        },


        /**
         * Delete an item from the cart drawer list.
         * Remove it from the DOM.
         * Delete the object on Cookies.
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
                    UtilityContainer.getNumberOfProducts() === 0 ? $("#empty-cart").removeClass("hidden") : null;
                });

                // To finally delete it from Cookies.
                Cookies.remove("_product_" + $(this).closest(".animated").data("product"));

                UtilityContainer.setBadgeQuantity();
                cartSliderContainer.view.setSubtotal();
                cartSliderContainer.behaviour.setQuantityCookie();

            });
        },


        /**
         * Modify the quantity of a product in the cart.
         * Update its price label accordingly.
         * Update the Cookies.
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
                var oldData = JSON.parse(Cookies.get("_product_" + $container.data("product")));
                oldData.quantity = parseInt($(this).val());
                Cookies.set("_product_" + $container.data("product"), oldData);

                UtilityContainer.setBadgeQuantity();
                cartSliderContainer.view.setSubtotal();
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
            $(".view-cart, .buybutton, .close-cart").on("click", function () {
                $(".cart-drawer").sidebar("toggle");
            });
        },


        /**
         * Update the value of .subtotal when adding or deleting elements.
         *
         */
        setSubtotal: function () {
            var subtotal = UtilityContainer.getProductsPrice(),
                subtotal_label = "CAD $" + subtotal.toFixed(2);

            if ($("meta[name='user-currency-code'], meta[name='user-currency-rate']").length > 0) {
                var currency_price = (subtotal * parseFloat($("meta[name='user-currency-rate']").attr("content"))).toFixed(2);

                subtotal_label += " (" + $("meta[name='user-currency-code']").attr("content") + " " + currency_price + ")" ;
            }
            $(".subtotal").text(subtotal_label);
        }
    },


    init : function() {
        var behaviour = cartSliderContainer.behaviour;
        var view = cartSliderContainer.view;


        view.setSubtotal();
        view.slideIn();
        UtilityContainer.setBadgeQuantity();


        behaviour.buyButtonClick();
        behaviour.loadItem();
        behaviour.deleteItem();
        behaviour.modifyQuantity();
        behaviour.setQuantityCookie();
    }

};