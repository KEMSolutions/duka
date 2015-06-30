/**
 * Object responsible for handling the payment panel.
 *
 * @type {{displayPaymentPanel: Function, initPaymentPanel: Function, updatePaymentPanel: Function, getTaxes: Function, init: Function}}
 */
var paymentContainer = {
    /**
     * Displays the Payment panel.
     *
     */
    displayPaymentPanel : function() {
        $("#payment").removeClass("hidden fadeOutUp").addClass("animated fadeInDown");
        $("#checkoutButton").addClass("animated rubberBand");
    },

    /**
     * Populate the payment panel with default values.
     *
     * @param data
     */
    initPaymentPanel : function(data) {
        var subtotal = parseFloat(UtilityContainer.getProductsPriceFromLocalStorage()).toFixed(2),
            priceTransport = $("input:radio.shipping_method:checked").data("cost"),
            taxes = paymentContainer.getTaxes(data) + parseFloat($("input:radio.shipping_method:checked").data("taxes")),
            total = parseFloat(subtotal) + parseFloat(priceTransport) + parseFloat(taxes);

        $("#price_subtotal").text(subtotal);
        $("#price_transport").text(priceTransport);
        $("#price_taxes").text(taxes.toFixed(2));
        $("#price_total").text(total.toFixed(2));
    },

    /**
     * Update the payment panel with right values (shipping method)
     *
     * @param data
     */
    updatePaymentPanel : function(data) {
        var subtotal = parseFloat(UtilityContainer.getProductsPriceFromLocalStorage()).toFixed(2),
            priceTransport, taxes;

        $(".shipping_method").on("change", function() {
            priceTransport = $(this).data("cost");
            taxes = paymentContainer.getTaxes(data) + parseFloat($(this).data("taxes"));
            total = parseFloat(subtotal) + parseFloat(priceTransport) + parseFloat(taxes);

            $("#price_subtotal").text(subtotal);
            $("#price_transport").text(priceTransport);
            $("#price_taxes").text(taxes.toFixed(2));
            $("#price_total").text(total.toFixed(2));
        });
    },

    /**
     * Get the total taxes (TPS/TVQ or TVH or TPS or null) + shipping method taxes.
     *
     * @param data
     * @returns {number}
     */
    getTaxes : function(data) {
        var taxes = 0,
            dataTaxesLength = data.taxes.length;

        if (dataTaxesLength != 0)
        {
            for(var i=0; i<dataTaxesLength; i++)
            {
                taxes += data.taxes[i].amount;
            }
        }
        return taxes;
    },

    /**
     * Register methods for outside calling.
     *
     * @param data
     */
    init : function(data) {
        paymentContainer.displayPaymentPanel();
        paymentContainer.initPaymentPanel(data);
        paymentContainer.updatePaymentPanel(data);

        checkoutLogicContainer.init();
    }
}