var wishlistLogicContainer = {

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
            '<h4 style="margin-top: 5px">' + item.name + '</h4>' +
            '<h5> $ ' + item.price + '</h5>'+
            '</div>' +
            '</div>';

        $(".list-layout-element-container").append(element);
    },

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

    removeWishlistElement: function () {
        $(".list-layout-element-container").on("click", ".removeFavoriteButton", function() {
            UtilityContainer.addFadeOutUpClass($(this).closest(".list-layout-element"));

            localStorage.removeItem("_wish_product " + $(this).data("product"));

            wishlistContainer.setNumberOfProductsInHeader();
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