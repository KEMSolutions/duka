/**
 * Component responsible for specific behaviours of homepage sections.
 *
 * @type {{mixed: {toggleSixteenWideColumn: Function}, init: Function}}
 */
var homepageContainer = {

    /**
     * Mixed section
     *
     */
    mixed: {
        toggleSixteenWideColumn: function () {
                var $productColumn = $(".mixed-section .eleven"),
                $widgetColumn = $(".mixed-section .four");

            $(window).on("load resize", function() {
                if(!$widgetColumn.is(":visible")) {
                    $productColumn.removeClass().addClass("sixteen wide column");
                }
                else {
                    $productColumn.removeClass().addClass("eleven wide column");
                }
            });

        }
    },

    init: function () {
        var self = homepageContainer,
            mixed = self.mixed;

        mixed.toggleSixteenWideColumn();
    }
}