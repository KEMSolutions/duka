function Entity(price, quantity) {
    this.price = price;
    this.quantity = quantity;
}

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
}

var cartData_old = {
    $el : {
        $back : $("#back"),
        $proceed : $("#proceed"),
        $trigger : $(".view-cart"),
        $checkout: $("#checkout"),
        $container : $("#cart-container"),
        $body : $("body"),
        $buybutton : $(".buybutton"),
        $close : $(".close-button")
    },
    //
    //$links : {
    //    update_url : '/' + page_lang + '/cart/update',
    //    remove_url : '/' + page_lang + '/cart/remove',
    //    estimate_url : '/' + page_lang + '/cart/estimate',
    //    login_url : '/' + page_lang + '/site/login'
    //},

    deleteItem : function() {
        $(document).on('click', ".close-button", function(e) {
            $this = $(this);


            var product_id = $this.closest("li").data("product");
            e.stopPropagation();
            $.post( cartData.$links.remove_url, { product: product_id })
                .done(function( data ) {
                    updateCartOverview(false);
                });
            var cart_item = $this.closest("li");

            // When we are on the cart layout, make the products disapear by the left as they are presented on the left side of the screen
            if (typeof cartCheckoutFetchEstimateProgramatically == 'function') {
                cart_item.addClass('animated bounceOutLeft');
            } else {
                cart_item.addClass('animated bounceOutRight');
            }

            cart_item.one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
                cart_item.remove();

                if (typeof cartCheckoutFetchEstimateProgramatically == 'function') {
                    cartCheckoutFetchEstimateProgramatically();
                }
                cart_item.unbind();
                cart_item.remove();
            });

        });
    },

    modifiyQuantity: function() {
        $("#cart-items").on("change", ".quantity", function() {
            $this = $(this);

            var container = $this.closest("li");
            var product_id = container.data("product");
            var quantity_group = $this.closest("div");
            var quantityField = container.find(".quantity");
            var quantity = quantityField.val();
            var editIcon = quantity_group.find(".fa");
            var priceLabel = container.find(".product-price");

            if (quantity <=0){
                quantity_group.addClass("has-error");
                quantity_group.addClass('animated shake');
                quantity_group.bind('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
                    quantity_field.removeClass("animated");
                    quantity_field.removeClass("shake");
                    quantity_field.unbind();
                });
                return;
            }

            editIcon.addClass("fa-spinner fa-spin");
            quantityField.prop("disabled", true);

            $.post( cartData.$links.update_url, { product: product_id, quantity: quantity })
                .done(function( data ) {
                    updateCartOverview(false);
                    priceLabel.text( "$" + (priceLabel.data("price") * quantity).toFixed(2) );
                    quantity_group.removeClass("has-error");
                    editIcon.removeClass("fa-spinner fa-spin");
                    editIcon.addClass('animated tada fa-check-circle-o text-success');
                    editIcon.bind('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
                        editIcon.removeClass("animated tada text-success fa-check-circle-o");
                        editIcon.unbind();
                        quantityField.prop("disabled", false);
                    });

                    // Reload the total if we are currently on the cart's page
                    if (typeof cartCheckoutFetchEstimateProgramatically == 'function') {
                        cartCheckoutFetchEstimateProgramatically();
                    }

                });

        })
    },

    init : function() {
        cartData.deleteItem();
        cartData.modifiyQuantity();
    }
}

var cartData = {
    $el : {
        $back : $("#back"),
        $proceed : $("#proceed"),
        $trigger : $(".view-cart"),
        $checkout: $("#checkout"),
        $container : $("#cart-container"),
        $body : $("body"),
        $buybutton : $(".buybutton"),
        $close : $(".close-button"),

        $list : $(".cart-items-list")
    },

    addItem : function(item) {
        var sidebarElement = '<li class="w-box animated bounceInDown" data-product="' + item.data("product") + '" data-quantity=1>' +
            '<div class="col-xs-3 text-center"><img src=' + item.data("thumbnail_lg") + ' class="img-responsive"></div>' +
            '<div class="col-xs-9 no-padding-left">' +
            '<div class="row"><div class="col-xs-10"><h3 class="product-name">' + item.data("name") + '</h3></div><div class="col-xs-2"><h4 class="text-right"><i class="fa fa-trash fa-1 close-button"><span class="sr-only">Remove Item</span></i></h4></div></div>' +
            '<div class="row"><div class="col-xs-8"><div class="input-group"><input type="number" value="1" class="quantity form-control input-sm" min="1" step="1">' +
            '<span class="input-group-addon update_quantity_indicator"><i class="fa" hidden><span class="sr-only">' + "Update quantity" + '</span></i></span></div></div>' +
            '<div class="col-xs-4 product-price text-right" data-price="' + item.data("price") + '">$' + item.data("price")  + '</div></div>' +
            '</div>' +
            '</li>';

        if (!$(".cart-items-list [data-product='" + item.data("product") + "']").length){
            cartData.$el.$list.append(sidebarElement);
        }

        item.attr("disabled", "disabled");
    },

    storeItem : function(item) {
        var current_product = cartData.button_to_Json(item);

        sessionStorage.setItem("_product " + current_product.product, JSON.stringify(current_product));
    },

    loadItem : function() {
        for(var i = 0; i<sessionStorage.length; i++)
        {
            console.log(sessionStorage.key(i))
        }
    },


    deleteItem: function() {
        $(document).on('click', ".close-button", function(e) {
            $parent = $(this).closest(".animated").addClass("animated bounceOutLeft");
            $parent.one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
                $(this).remove();
            });

            sessionStorage.removeItem("_product " + $(this).closest(".animated").data("product"));
        });
    },

    modifyQuantity : function() {
        $("#cart-items").on("change", ".quantity", function() {
            $container = $(this).closest("li");
            $product_price = $container.find(".product-price");

            //update the total value
            $product_price.text("$" + ($product_price.data("price") * $(this).val()).toFixed(2));

            //retrieve old data from old object then update the quantity
            var oldData = JSON.parse(sessionStorage.getItem("_product " + $container.data("product")));
            oldData.quantity = parseInt($(this).val());
            sessionStorage.setItem("_product " + $container.data("product"), JSON.stringify(oldData));

        });
    },

    //get information the clicked button.
    button_to_Json : function(item) {
        return {
            "product" : item.data("product"),
            "name" : item.data("name"),
            "price" : item.data("price"),
            "thumbnail" : item.data("thumbnail"),
            "thumbnail_lg" : item.data("thumbnail_lg"),
            "quantity" : 1
        }
    },

    Json_to_button : function(data) {
        return sidebarElement = '<li class="w-box animated bounceInDown" data-product="' + item.data("product") + '" data-quantity=1>' +
            '<div class="col-xs-3 text-center"><img src=' + item.data("thumbnail_lg") + ' class="img-responsive"></div>' +
            '<div class="col-xs-9 no-padding-left">' +
            '<div class="row"><div class="col-xs-10"><h3 class="product-name">' + item.data("name") + '</h3></div><div class="col-xs-2"><h4 class="text-right"><i class="fa fa-trash fa-1 close-button"><span class="sr-only">Remove Item</span></i></h4></div></div>' +
            '<div class="row"><div class="col-xs-8"><div class="input-group"><input type="number" value="1" class="quantity form-control input-sm" min="1" step="1" >' +
            '<span class="input-group-addon update_quantity_indicator"><i class="fa" hidden><span class="sr-only">' + "Update quantity" + '</span></i></span></div></div>' +
            '<div class="col-xs-4 product-price text-right" data-price="' + item.data("price") + '">$' + (item.data("price") * $(".quantity").val()).toFixed(2) + '</div></div>' +
            '</div>' +
            '</li>';
    }


}

$(document).ready(function() {
    cartDisplay.init();
    cartData.deleteItem();
    cartData.loadItem();
    cartData.modifyQuantity();

    $(".buybutton").click(function() {
        cartDisplay.animateIn();
        cartData.addItem($(this));
        cartData.storeItem($(this));
    })

    //This is the JSON boukem receives every time we add something to the cart
    /**
     * [
     *  {
     *      "quantity":"3",
     *      "price_paid":"36.95",
     *      "product_id":"577",
     *      "name":"Probiotic Plus - 120 capsules",
     *      "slug":"fr-probiotic-plus-120-capsules",
     *      "thumbnail":"\/\/static.boutiquekem.com\/productimg-50-50-83.jpg",
     *      "thumbnail_lg":"\/\/static.boutiquekem.com\/productimg-120-160-83.jpg",
     *      "link":"\/fr\/prod\/fr-probiotic-plus-120-capsules.html"
     *   },
     *   {
     *      "quantity":"1",
     *      "price_paid":"39.00",
     *      "product_id":"907",
     *      "name":"Wobenzym N - 100 comprim\u00e9s ",
     *      "slug":"fr-wobenzym-n-100-comprimes",
     *      "thumbnail":"\/\/static.boutiquekem.com\/productimg-50-50-413.jpg",
     *      "thumbnail_lg":"\/\/static.boutiquekem.com\/productimg-120-160-413.jpg",
     *      "link":"\/fr\/prod\/fr-wobenzym-n-100-comprimes.html"
     *   }
     * ]
     */
    //cartData.init();
})