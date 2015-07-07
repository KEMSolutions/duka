/**
 * Container responsible for handling the logic of adding products to a user's wishlist.
 * Layout handled in dev/components/products/layout/product-layout-favorite.js
 *
 * @type {{addToFavorite: Function, persistFavorite: Function, removeFromFavorite: Function, init: Function}}
 */
var productLayoutFavoriteLogicContainer = {

    /**
     * Add the clicked product to the wish list.
     *
     */
    addToFavorite: function() {
        var self = productLayoutFavoriteLogicContainer,
            selfLayout = productLayoutFavoriteContainer,
            item;

        $(".favorite-wrapper").on("click", function() {
            //No favorited class.
            if (!$(this).hasClass("favorited")) {
                item = UtilityContainer.buyButton_to_Json($(this).parent().find(".buybutton"));
                localStorage.setItem("_wish_product " + item.product, JSON.stringify(item));

                $(this).addClass("favorited");

                selfLayout.setWishlistBadgeQuantity();
            }
            else
            //Has a favorited class. We remove it, then delete the element from local Storage.
            {
                self.removeFromFavorite($(this));
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
                    if(JSON.parse(localStorage.getItem(localStorage.key(i))).product === parseInt($(".favorite-wrapper")[j].dataset.product))
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
    removeFromFavorite: function (context) {
        context.removeClass("favorited");
        localStorage.removeItem("_wish_product " + context.data("product"));
    },

    init: function () {
        var self = productLayoutFavoriteLogicContainer;

        //Calls the layout container (productLayoutFavoriteContainer).
        productLayoutFavoriteContainer.init();

        //Initialize the logic.
        self.addToFavorite();
        self.persistFavorite();
    }
}