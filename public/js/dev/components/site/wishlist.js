var wishlistContainer = {
    setNumberOfProductsInHeader: function() {
        var quantity = "";
        UtilityContainer.getNumberOfProductsInWishlist() == 0 || UtilityContainer.getNumberOfProductsInWishlist() == 1 ? quantity+= (UtilityContainer.getNumberOfProductsInWishlist() + "  item ") : quantity += (UtilityContainer.getNumberOfProductsInWishlist() + "  items ");
        $("#quantity-wishlist").text(quantity);
    },


    init: function() {
        var self = wishlistContainer;

        self.setNumberOfProductsInHeader();
    }
}