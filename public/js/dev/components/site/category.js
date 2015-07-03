var categoryContainer = {

    blurBackground: function () {
        $(".category-header").blurjs({
            source: ".category-header",
            overlay: "rgba(0,0,0,0.5)"
        });
    },

    init: function () {
        var self = categoryContainer;

        console.log("eh");
        self.blurBackground();
    }

}