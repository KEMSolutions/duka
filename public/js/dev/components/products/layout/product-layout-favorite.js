/**
 * Object responsible for adding products to a user's wishlist.
 *
 * @type {{fadeInFavoriteIcon: Function, setWishlistBadgeQuantity: Function, createWishlistElement: Function, renderWishlist: Function, localizeWishlistButton: Function, removeWishlistElement: Function, init: Function}}
 */
var productLayoutFavoriteContainer = {
    /**
     * Fade in the favorite icon (heart icon) when hovering on a product tile.
     *
     */
    fadeInFavoriteIcon: function() {
        $(".dense-product").hover(function() {
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

    /**
     * Add the clicked product to the wish list.
     *
     */
    addToFavorite: function() {
        var self = productLayoutFavoriteContainer,
            item;

        $(".favorite-wrapper").on("click", function() {
            //No favorited class.
            if (!$(this).hasClass("favorited")) {
                item = UtilityContainer.buyButton_to_Json($(this).parent().find(".buybutton"));
                localStorage.setItem("_wish_product " + item.product, JSON.stringify(item));

                $(this).addClass("favorited");

                self.setWishlistBadgeQuantity();
            }
            else
            //Has a favorited class. We remove it, then delete the element from local Storage.
            {
                self.removeFromFavorite($(this), self);
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
                    if(JSON.parse(localStorage.getItem(localStorage.key(i))).product === $(".favorite-wrapper")[j].dataset.product)
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
        var self = productLayoutFavoriteContainer;


        self.addToFavorite();
        self.persistFavorite();
        self.fadeInFavoriteIcon();
        self.setWishlistBadgeQuantity();
    }
}