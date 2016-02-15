var GAEAnalytics = {

    /**
     * Steps:
     *
     * 1. User completes a transaction.
     * 2. Thank you dimmer with embedded info about the purchase.
     * 3. Send info to Analytics.
     */


    /**
     * Get order information to:
     *      - Create transaction object
     *      - Populate items in transaction object
     *
     * @param order_details
     */
    register: function (order_details) {

        // Create the transaction
        GAEAnalytics.createTransaction({
            id: order_details.id,
            revenue: order_details.payment_details.total,
            shipping: order_details.payment_details.shipping,
            tax: order_details.payment_details.taxes
        });


        // Populate the transaction
        GAEAnalytics.populateTransaction(order_details.id, order_details.items);


        // Send Data
        ga('ecommerce:send');
    },



    /**
     * Create one transaction.
     *
     * @param transaction_details
     */
    createTransaction: function (transaction) {
        ga('ecommerce:addTransaction', {
            'id': transaction.id,
            'revenue': transaction.revenue,
            'shipping': transaction.shipping,
            'tax': transaction.tax
        });
    },


    /**
     * Populate the relevant transaction.
     *
     * @param items
     */
    populateTransaction: function (transaction_id, items) {
        $.each(items, function(index, item){
            ga('ecommerce:addItem', {
                'id': transaction_id,
                'name': items[index].id,
                'price': items[index].price,
                'quantity': items[index].quantity
            });
        });
    }
};