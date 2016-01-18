/**
 * Component responsible for changing quantity on the product page view.
 *
 * @type {{addQuantity: Function, removeQuantity: Function, updateBuyButton: Function, init: Function}}
 */
var productQuantityContainer = {
    addQuantity : function(input, callback) {
        $(".qty-selector[data-action='add']").on("click", function() {
            input.val(parseInt(input.val()) + 1);

            callback();
        });
    },

    removeQuantity: function(input, callback) {
        $(".qty-selector[data-action='remove']").on("click", function() {
            var actual = parseInt(input.val());

            if (actual > 1) {
                input.val(parseInt(input.val()) - 1);
            }

            callback();
        });
    },

    updateBuyButton: function() {
        $(".buybutton").attr("data-quantity", $(".qty-selector-input").val());
    },

    init: function () {
        var self = productQuantityContainer;
        self.addQuantity($(".qty-selector-input"), self.updateBuyButton);
        self.removeQuantity($(".qty-selector-input"), self.updateBuyButton);

    }
};