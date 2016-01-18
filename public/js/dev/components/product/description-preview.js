/**
 * Component responsible for displaying a product description (with a nice fade  out effect)
 *
 * @type {{toggleDescription: Function, init: Function}}
 */
var productDescriptionPreviewContainer = {

    /**
     * Toggle the description between its open state and its close state.
     *
     */
    toggleDescription: function () {
        var open = false;

        $(".preview-trigger").on("click", function () {
            open = !open;

            if (open) {
                $(this).text(Localization.show_less);
                $(".preview-text").fadeOut();
            }
            else {
                $(this).text(Localization.show_more);
                setTimeout(function() {
                    $(".preview-text").fadeIn()
                }, 200);
            }
        });
    },

    init: function () {
        var self = productDescriptionPreviewContainer;

        self.toggleDescription();
    }
};