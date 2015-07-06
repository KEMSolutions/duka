/**
 * Object responsible for the view component of the wish list page.
 * Logic handled in dev/actions/site/wishlist-logic.js
 *
 * @type {{setNumberOfProductsInHeader: Function, init: Function}}
 */
var wishlistContainer = {

    /**
     * Sets the number of products in the header (singular / plural).
     *
     */
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