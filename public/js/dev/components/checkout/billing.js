/**
 * Object responsible for handling billing information.
 *
 * @type {{autoFillBillingAddress: Function, setDifferentBillingAddress: Function, clearBillingAddress: Function, init: Function}}
 */
var billingContainer = {

    /**
     * Fill the billing address with the shipping address.
     * First parameter is an array of all fields that only need basic validation (empty or not)
     * Second parameter is an input that requires more advanced verification (postcode)
     *
     *
     * @param fields
     * @param fieldWithRules
     */
    autoFillBillingAddress : function(fields, fieldWithRules) {
        if($(".billing-checkbox").is(":checked"))
        {
            //We assume here that fieldWithRules is the shipping postcode.
            $("#billing" + fieldWithRules[0].id.substring("shipping".length, fieldWithRules[0].id.length)).val(fieldWithRules[0].value);

            for(var i= 0, length = fields.length; i<length; i++) {
                //check if the id has the string "shipping".
                //if it does, delete the shipping prefix and replace it by billing.
                //Create a new jquery selector and fill it with the value of the shipping one.
                if (fields[i][0].id.indexOf("shipping") > -1) {
                    var genericInput = fields[i][0].id.substring("shipping".length, fields[i][0].id.length);
                    $("#billing" + genericInput).val(fields[i][0].value);
                }
            }
        }
    },

    /**
     * Get user's billing address. By default shipping address = billing address.
     * Set the width of select list at the same time.
     *
     */
    setDifferentBillingAddress : function (self) {
        $(".billing-checkbox").on("change", function() {
            $(".form-billing .chosen-container").width($("#customer_email").outerWidth()-20);

            if (!this.checked) {
                $(".form-billing").hide().removeClass("hidden").fadeIn();
                self.clearBillingAddress();
            }
            else {
                $(".form-billing").fadeOut(function() {
                    $(this).addClass("hidden");
                });
            }
        })
    },

    /**
     * Clear the billing form.
     *
     */
    clearBillingAddress : function() {
        if ($(".form-billing input").val() != "") {
            $(".form-billing input").val() == "";
        }
    },

    init: function() {
        var self = billingContainer;

        self.setDifferentBillingAddress(self);
    }
}