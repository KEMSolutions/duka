var piwikAnalytics = {

    /**
     * Add products to the order.
     *
     * @param items
     */
    addEcommerceItem: function (items) {
        $.each(items, function (index) {
            _paq.push(['addEcommerceItem',
                items[index].id, // (required) SKU: Product unique identifier
                items[index].price, // (recommended) Product price
                items[index].quantity // (optional, default to 1) Product quantity
            ]);
        })
    },


    /**
     * Creates a piwik order object, and sends the data to Piwik server.
     *
     * @param order_details
     */
    trackEcommerceOrder: function(order_details) {

        piwikAnalytics.addEcommerceItem(order_details.items);

        _paq.push(['trackEcommerceOrder',
            order_details.id, // (required) Unique Order ID
            order_details.payment_details.total, // (required) Order Revenue grand total (includes tax, shipping, and subtracted discount)
            order_details.payment_details.subtotal, // (optional) Order sub total (excludes shipping)
            order_details.payment_details.taxes, // (optional) Tax amount
            order_details.payment_details.shipping, // (optional) Shipping amount
            false // (optional) Discount offered (set to false for unspecified parameter)
        ]);
    }

};