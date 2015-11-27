/**
 * Component responsible for activating semantic ui features.
 *
 * @type {{module: {initDropdownModule: Function, initRatingModule: Function, initPopupModule: Function, initCheckboxModule: Function}, behaviors: {}, init: Function}}
 */
var semanticInitContainer = {

    /**
     * Initialize modules
     *
     */
    module: {
        /**
         * Initialize dropdown module.
         *
         */
        initDropdownModule: function() {
            //Enable selection on clicked items
            $(".ui.dropdown-select").dropdown();

            //Prevent selection on clicked items
            $(".ui.dropdown-no-select").dropdown({
                    action: "select"
                }
            );
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