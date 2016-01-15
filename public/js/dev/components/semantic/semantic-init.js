/**
 * Component responsible for activating semantic ui features.
 *
 * @type {{module: {initDropdownModule: Function, initRatingModule: Function, initPopupModule: Function, initCheckboxModule: Function}, behaviors: {closeDimmer: Function}, init: Function}}
 */
var semanticInitContainer = {

    /**
     * Initialize modules

     */
    module: {
        /**
         * Initialize dropdown module.
         *
         */
        initDropdownModule: function() {
            $(".ui.dropdown").dropdown();

            $(".ui.dropdown").on("click", function () {
                var action = $(this).data("action") || "activate";

                $(this).dropdown({
                    action: action
                });
            });
        },

        /**
         * Initialize rating module.
         *
         */
        initRatingModule: function () {
            $(".ui.rating").rating();
        },

        /**
         * Initialize popup module.
         *
         */
        initPopupModule: function () {
            $(".popup").popup();
        },

        /**
         * Initialize checkbox module.
         *
         */
        initCheckboxModule: function () {
            $('.ui.checkbox')
                .checkbox()
            ;
        }
    },

    /**
     * Specify semantic custom behavior.
     *
     */
    behaviors: {
        closeDimmer: function () {
            $(".close-dimmer").on("click", function() {
                $(".dimmer").dimmer("hide");
            });
        }
    },



    init: function () {
        var self = semanticInitContainer,
            module = self.module,
            behaviors = self.behaviors;

        module.initDropdownModule();
        module.initRatingModule();
        module.initPopupModule();
        module.initCheckboxModule();

        behaviors.closeDimmer();
    }
}