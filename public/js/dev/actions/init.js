$(document).ready(function () {

    /**
     * Sets up the ajax token for all ajax requests
     *
     */
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    /**
     * Initialize checkout logic.
     *
     */
    checkoutInitContainer.init();

    /**
     * Initialize cart drawer logic.
     *
     */
    cartDrawerInitContainer.init();

    /**
     * Initialize category container
     *
     */
    categoryContainer.init();

    /**
     * Global initialization of elements.
     *
     */
    //fancy plugin for product page (quantity input)
    $(".input-qty").TouchSpin({
        initval: 1
    });

    //Initialize overlay plugin.
    paymentOverlayContainer.init();

    //Initialize navigation header.
    headerContainer.init();

    //Initialize favorite products feature
    productLayoutFavorite.init();
});