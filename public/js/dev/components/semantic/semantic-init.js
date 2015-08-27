/**
 * Object responsible for handling the initialization of all semantic ui modules.
 *
 * @type {{initDropdownModule: Function, init: Function}}
 */
var semanticInitContainer = {

    /**
     * Initialize dropdown module.
     *
     */
    initDropdownModule : function() {
        //Enable selection on clicked items
        $(".ui.dropdown-select").dropdown();

        //Prevent selection on clicked items
        $(".ui.dropdown-no-select").dropdown({
                action: "select"
            }
        );
    },

    init: function () {
        var self = semanticInitContainer;

        self.initDropdownModule();
    }
}