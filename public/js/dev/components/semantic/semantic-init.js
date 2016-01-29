/**
 * Component responsible for activating semantic ui features.
 *
 * @type {{module: {initDropdownModule: Function, initRatingModule: Function, initPopupModule: Function, initCheckboxModule: Function}, behaviors: {closeDimmer: Function}, init: Function}}
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
        },

        /**
         * Initialize accordion module.
         *
         */
        initAccordionModule: function() {
            $('.ui.accordion').accordion();
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

    /**
     * Specify custom form validation rules.
     *
     */
    rules: {
        postalCode: function() {
            $.fn.form.settings.rules.postalCode = function(value, fieldIdentifier) {
                if($('#checkboxSuccess').is("checked") && fieldIdentifier === "billingCountry") {
                    return true;
                } else {
                    if ($("#" + fieldIdentifier).val() === "CA")
                        return value.match(/^[ABCEGHJKLMNPRSTVXY]{1}\d{1}[A-Z]{1} ?\d{1}[A-Z]{1}\d{1}$/i) ? true : false;
                    else if ($("#" + fieldIdentifier).val() === "US")
                        return value.match(/^\d{5}(?:[-\s]\d{4})?$/) ? true : false;
                    else {
                        return true;
                    }
                }
            }
        }
    },


    init: function () {
        var self = semanticInitContainer,
            module = self.module,
            behaviors = self.behaviors,
            rules = self.rules;

        module.initDropdownModule();
        module.initRatingModule();
        module.initPopupModule();
        module.initCheckboxModule();
        module.initAccordionModule();

        behaviors.closeDimmer();

        rules.postalCode();
    }
}