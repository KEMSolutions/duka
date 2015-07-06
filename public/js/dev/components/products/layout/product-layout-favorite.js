/**
 * Object responsible for the view component of the favorite feature.
 * Logic handled in dev/actions/products/layout-favorite-logic.js
 *
 * @type {{fadeInFavoriteIcon: Function, setWishlistBadgeQuantity: Function, init: Function}}
 */
var productLayoutFavoriteContainer = {
    /**
     * Fade in the favorite icon (heart icon) when hovering on a product tile.
     *
     */
    fadeInFavoriteIcon: function() {
        $(".dense_product").hover(function() {
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

    init: function () {
        var self = productLayoutFavoriteContainer;

        self.fadeInFavoriteIcon();
        self.setWishlistBadgeQuantity();
    }
}