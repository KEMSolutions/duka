/**
 * Entry point of script.
 *
 */
; (function(window, document, $) {

    // Temporary for now, until we find a better file structure...
    Vue.config.debug = true;


    $(document).ready(function () {

        /**
         * Sets up Vue.js module on the Duka container.
         *
         */
        new Vue({
            el: ".duka-container"
        });

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
         * Initialize header.
         *
         */
        headerContainer.init();

        /**
         * Initialize overlay plugin.
         *
         */
        paymentOverlayContainer.init();

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
