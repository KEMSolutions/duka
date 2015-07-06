/**
 * Container responsible for initializing the checkout page.
 * Overall logic is handled in js/dev/actions/checkout/*.js
 * View component is handled in js/dev/components/checkout/*.js
 *
 * @type {{estimateButtonClick: Function, init: Function}}
 */
var checkoutInitContainer = {

    /**
     * Event triggered when the "Continue" button is hit.
     * If the input fields entered are appropriate, make the ajax call to "/api/estimate".
     * If they are not, display the relevant error message(s)
     *
     */
    estimateButtonClick : function() {
        $("#estimateButton").on("click", function(e) {
            var email = $("#customer_email"),
                phone = $("#customer_phone"),
                shippingFirstName = $("#shippingFirstname"),
                shippingLastName = $("#shippingLastname"),
                shippingAddress1 = $("#shippingAddress1"),
                shippingCity = $("#shippingCity"),
                shippingCountry = $("#shippingCountry").val(),
                shippingPostcode = $("#shippingPostcode"),
                billingFirstName = $("#billingFirstname"),
                billingLastName = $("#billingLastname"),
                billingAddress1 = $("#billingAddress1"),
                billingCity = $("#billingCity"),
                billingCountry = $("#billingCountry").val(),
                billingPostcode = ("#billingPostcode"),
                shippingInformation = {
                    "country" : shippingCountry,
                    "postcode" : $("#shippingPostcode").val(),
                    "postcodeInput" : $("#shippingPostcode")
                },
                fields = [
                    shippingFirstName,
                    shippingLastName,
                    shippingAddress1,
                    shippingCity,
                    billingFirstName,
                    billingLastName,
                    billingAddress1,
                    billingCity,
                    email,
                    phone
                ];

            e.preventDefault();

            //Auto fill billing address if checkbox is checked.
            billingContainer.autoFillBillingAddress(fields, shippingInformation.postcodeInput);

            //Build the billing information object (from auto fill or entered by hand)
            var billingInformation = {
                "country" : billingCountry,
                "postcode" : $("#billingPostcode").val(),
                "postcodeInput" : $("#billingPostcode")
            };

            //Validate all fields and make the ajax call!
            checkoutValidationContainer.init(fields, email, shippingInformation, billingInformation);
        });
    },

    init: function () {
        /**
         * Populate select lists and set up billing address container behaviour.
         * Set the form focus on first name field
         *
         */
        locationContainer.init();
        billingContainer.init();
        $("#shippingFirstname").focus();

        var self = checkoutInitContainer;
        self.estimateButtonClick();
    }
}