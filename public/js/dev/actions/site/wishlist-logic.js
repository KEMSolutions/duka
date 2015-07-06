/**
 * Container responsible for handling the logic of the wish list page.
 * Layout handled in dev/components/site/wishlist.js
 *
 * @type {{createWishlistElement: Function, renderWishlist: Function, removeWishlistElement: Function, init: Function}}
 */
var wishlistLogicContainer = {

    /**
     * Create a list layout element from the information passed as an argument.
     *
     * Rounding to 2 decimals, courtesy of http://stackoverflow.com/a/6134070.
     *
     * @param item
     */
    createWishlistElement: function(item) {
        var element =
            '<div class="col-md-12 list-layout-element">' +
            '<div class="col-md-2">' +
            '<img src=' + item.thumbnail_lg + '>' +
            '</div>' +
            '<div class="col-md-10">' +
            '<button class="btn btn-outline btn-danger-outline pull-right btn-lg inline-block padding-side-lg removeFavoriteButton" data-product="' + item.product + '">Remove from wishlist </button>' +
            '<button class="btn btn-success buybutton pull-right btn-lg inline-block padding-side-lg"' +
            'data-product="' + item.product + '"' +
            'data-price="' + item.price + '"' +
            'data-thumbnail="' + item.thumbnail + '"' +
            'data-thumbnail_lg="' + item.thumbnail_lg + '"' +
            'data-name="' + item.name + '"' +
            'data-quantity="' + item.quantity  + '"' + ">" +
            'Add to cart </button>' +
            '<a href=' + item.link + '><h4 style="margin-top: 5px">' + item.name + '</h4></a>' +
            '<h5> $ ' + parseFloat(Math.round(item.price * 100) / 100).toFixed(2) + '</h5>'+
            '</div>' +
            '</div>';

        $(".list-layout-element-container").append(element);
    },

    /**
     * Populate the wishlist page with elements created on the fly from localStorage that has their key starting with "_wish_prod {id}".
     * The creation is handled in createWishlistElement function.
     *
     */
    renderWishlist: function() {
        var self = wishlistLogicContainer;

        for(var i = 0, length = localStorage.length; i<length; i++)
        {
            if (localStorage.key(i).lastIndexOf("_wish_product", 0) === 0)
            {
                self.createWishlistElement(JSON.parse(localStorage.getItem(localStorage.key(i))));
            }
        }
    },

    /**
     * Remove the element from the wishlist after a subtle animation.
     *
     */
    removeWishlistElement: function () {
        $(".list-layout-element-container").on("click", ".removeFavoriteButton", function() {
            //Animate the element.
            UtilityContainer.addFadeOutUpClass($(this).closest(".list-layout-element"));

            //Delete the element from localStorage.
            localStorage.removeItem("_wish_product " + $(this).data("product"));

            //Set wishlist header quantity.
            wishlistContainer.setNumberOfProductsInHeader();

            //Set wishlist badge
            productLayoutFavoriteContainer.setWishlistBadgeQuantity();
        });
    },

    init: function () {
        var self = wishlistLogicContainer;

        //Calls the layout container (wishlistContainer).
        wishlistContainer.init();

        //Initialize the logic.
        self.renderWishlist();
        self.removeWishlistElement();
    }

}