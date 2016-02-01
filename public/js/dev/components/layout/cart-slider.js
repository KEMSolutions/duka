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
            for(var i = 0, length = localStorage.length; i<length; i++)
            {
                if (localStorage.key(i).lastIndexOf("_product", 0) === 0)
                {
                    $("#empty-cart").addClass("hidden");
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
                    UtilityContainer.getNumberOfProducts() === 0 ? $("#empty-cart").removeClass("hidden") : null;
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