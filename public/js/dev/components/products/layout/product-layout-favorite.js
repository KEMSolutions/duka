var productLayoutFavorite = {


    fadeInFavoriteIcon: function() {
        $(".dense_product").hover(function() {
            $(this).children(".favorite-wrapper").fadeIn();
        }, function () {
            $(this).children(".favorite-wrapper").hide();
        });
    },



    //TODO: LOGIC = TO BE MOVED TO DEV/ACTIONS/PRODUCTS/LAYOUT/PRODUCT-LAYOUT-FAVORITE-LOGIC.JS
    addToFavorite: function() {
        var self = productLayoutFavorite,
            item;

        $(".favorite-wrapper").on("click", function() {
            //No favorited class.
            if (!$(this).hasClass("favorited")) {
                item = self.button_to_Json($(this).parent().find(".buybutton"));
                localStorage.setItem("_wish_product " + item.product, JSON.stringify(item));

                $(this).addClass("favorited");

                self.setWishlistBadgeQuantity();
            }
            else
            //Has a favorited class. We remove it, then delete the element from local Storage.
            {
                self.removeFromFavorite($(this));
            }
        });
    },


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

    removeFromFavorite: function (context) {
        context.removeClass("favorited");
        localStorage.removeItem("_wish_product " + context.data("product"));
    },

    /**
     * Update the value of #cart_badge when adding or deleting elements
     */
    setWishlistBadgeQuantity : function() {
        var total = UtilityContainer.getNumberOfProductsInWishlist();

        $(".wishlist_badge").text(total);
    },

    /**
     * parse the information from the button into a readable json format
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
            "quantity" : 1
        }
    },

    init: function () {
        var self = productLayoutFavorite;

        self.fadeInFavoriteIcon();
        self.addToFavorite();
        self.setWishlistBadgeQuantity();
        self.persistFavorite();
    }
}