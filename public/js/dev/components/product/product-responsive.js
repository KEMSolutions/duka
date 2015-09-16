var productResponsive = {
    invertPriceAndDescriptionColumn: function () {
        $(window).on("load resize", function () {
            if($(this).width() < 768)
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
        var self = productResponsive;

        self.invertPriceAndDescriptionColumn();
    }
}