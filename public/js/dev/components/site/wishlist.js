var wishlistContainer = {
    setNumberOfProductsInHeader: function() {
        var quantity = "";
        UtilityContainer.getNumberOfProductsInWishlist() == 0 ? quantity+="0 item" : quantity += (UtilityContainer.getNumberOfProductsInWishlist() + "  items ");
        $("#quantity-wishlist").text(quantity);
    },

    init: function() {
        var self = wishlistContainer;

        self.setNumberOfProductsInHeader();
    }
}