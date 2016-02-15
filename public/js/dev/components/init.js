/**
 * Entry point of script.
 *
 */
; (function(window, document, $) {
    $(document).ready(function () {

        /**
         * Sets up the ajax token for all ajax requests.
         *
         */
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'locale': $('html').attr('lang')
            }
        });

        /**
         * Sets up Localization and ApiEndpoints variables.
         *
         */
        var env = UtilityContainer.getLocalizationAndEndpointUrl().responseJSON;
        Localization = env.Localization;
        ApiEndpoints = env.ApiEndpoints;


        /**
         * Initialize semantic UI modules.
         *
         */
        semanticInitContainer.init();

        /**
         * Initialize responsiveness feature.
         *
         */
        responsiveContainer.init();

        /**
         * Initialize checkout logic.
         *
         */
        checkoutContainer.init();

        /**
         * Initialize cart slider logic.
         *
         */
        cartSliderContainer.init();

        /**
         * Initialize category container.
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
         * Initialize card product formats feature.
         *
         */
        productCardFormatContainer.init();

        /**
         * Initialize product formats feature.
         *
         */
        productFormatContainer.init();

        /**
         * Initialize product quantity change.
         *
         */
        productQuantityContainer.init();

        /**
         * Initialize wishlist page.
         *
         */
        wishlistLogicContainer.init();

    });

})(window, window.document, jQuery, undefined)
