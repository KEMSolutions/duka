/**
 * Component responsible for adding products to a user's wishlist.
 *
 * @type {{fadeInFavoriteIcon: Function, setPopupText: Function, setWishlistBadgeQuantity: Function, addToFavorite: Function, persistFavorite: Function, removeFromFavorite: Function, init: Function}}
 */
var productLayoutFavoriteContainer = {
    /**
     * Fade in the favorite icon (heart icon) when hovering on a product tile.
     *
     */
    fadeInFavoriteIcon: function() {
        self = productLayoutFavoriteContainer;

        $(".dense-product").hover(function() {

            $(this).children(".favorite-wrapper").fadeIn();
            self.setPopupText($(this).children(".favorite-wrapper"));

        }, function () {
            $(this).children(".favorite-wrapper").hide();
        });
    },

    /**
     * Set popup text according to current state of the wrapper.
     *
     * @param wrapper
     */
    setPopupText: function (wrapper) {
        if($(wrapper).hasClass("favorited")){
            $(wrapper).attr("title", Localization.wishlist_remove);
        }
        else {
            $(wrapper).attr("title", Localization.wishlist_add);
        }
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

                //Set the favorite icon to be displayed
                $(this).addClass("favorited");

                //Set wishlist badge quantity
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
        }
    },

    /**
     * Delete the clicked element from the wish list.
     *
     * @param element
     * @param context
     */
    removeFromFavorite: function (element, context) {
        element.removeClass("favorited");
        localStorage.removeItem("_wish_product " + element.data("product"));
        context.setWishlistBadgeQuantity();
    },

    init: function () {
        var self = productLayoutFavoriteContainer;

        self.setPopupText();
        self.addToFavorite();
        self.persistFavorite();
        self.fadeInFavoriteIcon();
        self.setWishlistBadgeQuantity();
    }
}