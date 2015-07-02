var categoryContainer = {

    blurBackground: function () {
        $(".category-header").blurjs({
            source: ".category-header"
        });
    },

    init: function () {
        var self = categoryContainer;

        self.blurBackground();
    }

}