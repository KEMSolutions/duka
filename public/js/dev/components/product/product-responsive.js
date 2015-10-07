/**
 * Component responsible for handling the responsiveness in product pages.
 *
 * @type {{invertPriceAndDescriptionColumn: Function, init: Function}}
 */
var productResponsiveContainer = {
    invertPriceAndDescriptionColumn: function () {
        $(window).on("load resize", function () {
            if($(this).width() < 769)
            {
                $("#product-description").before($("#product-info-box"));
            }
            else
            {
                $("#product-description").after($("#product-info-box"));
            }
        });
    },

    init: function () {
        var self = productResponsiveContainer;

        self.invertPriceAndDescriptionColumn();
    }
}