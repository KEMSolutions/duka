/**
 * Entry point of script.
 *
 */
$(document).ready(function () {

    /**
     * Sets up the ajax token for all ajax requests
     *
     */
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'locale': $('html').attr('lang')
        }
    });

    /**
     * Initialize semantic UI modules
     *
     */
    semanticInitContainer.init();

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
     * Initialize overlay plugin.
     *
     */
    paymentOverlayContainer.init();

    /**
     * Initialize homepage sections.
     *
     */
    homepageContainer.init();

    /**
     * Initialize favorite products feature.
     *
     */
    productLayoutFavoriteContainer.init();

    /**
     * Initialize product formats feature.
     *
     */
    productFormatContainer.init();

    /**
     * Initialize wishlist page.
     *
     */
    wishlistLogicContainer.init();

    /**
     * Global initialization of elements.
     *
     */
    //fancy plugin for product page (quantity input)
    $(".input-qty").TouchSpin({
        initval: 1
    });

});