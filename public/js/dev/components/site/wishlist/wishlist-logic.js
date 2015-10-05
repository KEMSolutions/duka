/**
 * Component responsible for handling the logic of the wish list page.
 * Layout handled in dev/components/site/wishlist.js
 *
 * @type {{createWishlistElement: Function, renderWishlist: Function, localizeWishlistButton: Function, removeWishlistElement: Function, init: Function}}
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
        var self = wishlistLogicContainer,
            element =
        '<div class="item list-layout-element">' +
        '<div class="ui tiny image">' +
        '<img src=' + item.thumbnail_lg + '>' +
        '</div>' +
        '<div class="middle aligned content">' +
        '<div class="header">' +
        '<a href=' + item.link + '>' + item.name + '</a>' +
        '</div>' +
        '<div class="description">' +
        '<p>' + item.description + '</p>' +
            '<h5> $ ' + parseFloat(Math.round(item.price * 100) / 100).toFixed(2) + '</h5>'+
        '</div>' +
        '<div class="extra">' +
        '<button class="ui right floated button green buybutton"' +
        'data-product="' + item.product + '"' +
        'data-price="' + item.price + '"' +
        'data-thumbnail="' + item.thumbnail + '"' +
        'data-thumbnail_lg="' + item.thumbnail_lg + '"' +
        'data-name="' + item.name + '"' +
        'data-description="' + item.description + '"' +
        'data-quantity="' + item.quantity  + '"' + ">" +
        'Add to cart </button>' +
        '</button>' +
        '<button class="ui right floated button inverted red removeFavoriteButton" data-product="' + item.product + '">' +
        'Remove from wishlist' +
        '</button>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '<hr/>';


        //Localize button (default in english)
        self.localizeWishlistButton();

        //Append elements
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

    localizeWishlistButton: function() {
        $(".list-layout-element .buybutton").text(Localization.add_cart);
        $(".list-layout-element .removeFavoriteButton").text(Localization.wishlist_remove);
    },

    /**
     * Remove the element from the wishlist after a subtle animation.
     *
     */
    removeWishlistElement: function () {
        $(".list-layout-element-container").on("click", ".removeFavoriteButton", function() {
            //Animate the element.
            UtilityContainer.addFadeOutUpClass($(this).closest(".list-layout-element"));
            UtilityContainer.addFadeOutUpClass($(this).closest(".list-layout-element").next());

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