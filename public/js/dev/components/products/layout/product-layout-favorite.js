var productLayoutFavoriteContainer = {


    fadeInFavoriteIcon: function() {
        $(".dense_product").hover(function() {
            $(this).children(".favorite-wrapper").fadeIn();
        }, function () {
            $(this).children(".favorite-wrapper").hide();
        });
    },


    /**
     * Update the value of #cart_badge when adding or deleting elements
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