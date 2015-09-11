/**
 * Object responsible for validating all the information entered by the user.
 * Will trigger the ajax call only when all the inputs entered match their validation rules.
 *
 * @type {{removeErrorClassFromEmail: Function, removeErrorClassFromPostcode: Function, init: Function}}
 */
var checkoutValidationContainer = {

    removeErrorClassFromEmail: function(email) {
        if (UtilityContainer.validateEmail(email.val()) && email.parent().hasClass("has-error"))
            email.parent().removeClass("has-error");
    },

    removeErrorClassFromPostcode: function(postcode, country) {
        if (UtilityContainer.validatePostCode(postcode.val(), country) && postcode.parent().hasClass("has-error"))
            postcode.parent().removeClass("has-error");
    },

    /**
     * If all validation pass, spin the button, clean the shipment table and trigger the ajax call.
     * If there are errors, warn the users about which inputs is faulty.
     *
     * @param fields
     * @param email
     * @param postcode
     * @param country
     */
    init : function(fields, email, shippingInformation, billingInformation) {
        var self = checkoutValidationContainer;

        if (UtilityContainer.validateEmptyFields(fields)
            && UtilityContainer.validateEmail(email.val())
            && UtilityContainer.validatePostCode(shippingInformation.postcode, shippingInformation.country)
            && UtilityContainer.validatePostCode(billingInformation.postcode, billingInformation.country))
        {
            $('#estimateButton').html('<i class="fa fa-spinner fa-spin"></i>');

            //delete previously uploaded shipping method (if any)
            if($("#estimate .table-striped").children().length > 0) {
                $("#estimate .table-striped tbody").empty();
            }

            estimateContainer.ajaxCall();
        }
        else
        {
            UtilityContainer.addErrorClassToFields(fields);

            if(!UtilityContainer.validatePostCode(shippingInformation.postcode, shippingInformation.country))
            {
                UtilityContainer.addErrorClassToFieldsWithRules(shippingInformation.postcodeInput);
            }

            if(!UtilityContainer.validatePostCode(billingInformation.postcode, billingInformation.country))
            {
                UtilityContainer.addErrorClassToFieldsWithRules(billingInformation.postcodeInput);
            }

            if(!UtilityContainer.validateEmail(email.val()))
            {
                UtilityContainer.addErrorClassToFieldsWithRules(email);
                $("#why_email").removeClass("hidden").addClass("animated bounceInRight").tooltip();
            }

        }

        UtilityContainer.removeErrorClassFromFields(fields);
        self.removeErrorClassFromEmail(email);
        self.removeErrorClassFromPostcode(shippingInformation.postcodeInput, shippingInformation.country);
        self.removeErrorClassFromPostcode(billingInformation.postcodeInput, billingInformation.country);
    }
}
