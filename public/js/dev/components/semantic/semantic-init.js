/**
 * Object responsible for activating semantic ui features.
 *
 * @type {{module: {initDropdownModule: Function, initRatingModule: Function}, behaviors: {}, init: Function}}
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
        }
    },

    /**
     * Specify semantic custom behavior.
     *
     */
    behaviors: {

    },



    init: function () {
        var self = semanticInitContainer,
            module = self.module;

        module.initDropdownModule();
        module.initRatingModule();
    }
}