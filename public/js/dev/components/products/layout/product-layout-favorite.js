var productLayoutFavorite = {


    fadeInFavoriteIcon: function() {
        $(".dense_product").hover(function() {
            $(this).children(".favorite-wrapper").fadeIn();
        }, function () {
            $(this).children(".favorite-wrapper").fadeOut();
        });
    },



    //TODO: LOGIC = TO BE MOVED TO DEV/ACTIONS/PRODUCTS/LAYOUT/PRODUCT-LAYOUT-FAVORITE-LOGIC.JS
    addToFavorite: function() {
        var self = productLayoutFavorite,
            item;

        $(".favorite-wrapper").on("click", function() {
            item = self.button_to_Json($(this).parent().find(".buybutton"));
            localStorage.setItem("_wish_product " + item.product, JSON.stringify(item));

            $(this).addClass("favorited");

        });
    },

    /**
     * parse the information from the button into a readable json format
     *
     * @param item
     * @returns {{product: *, name: *, price: *, thumbnail: *, thumbnail_lg: *, quantity: number}}
     */
    button_to_Json : function(item) {
        return {
            "product" : item.data("product"),
            "name" : item.data("name"),
            "price" : item.data("price"),
            "thumbnail" : item.data("thumbnail"),
            "thumbnail_lg" : item.data("thumbnail_lg")
        }
    },

    init: function () {
        var self = productLayoutFavorite;

        self.fadeInFavoriteIcon();
        self.addToFavorite();
    }
}