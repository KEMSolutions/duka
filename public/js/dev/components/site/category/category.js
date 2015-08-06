/**
 * Object responsible for the view component of each category page.
 *
 * @type {{blurBackground: Function, init: Function}}
 */
var categoryContainer = {

    /**
     * Contains the updated URL parameters,
     *
     */
    searchParameters: {
        page: 1,
        per_page: 8,
        order: 'relevance',
        min_price: null,
        max_price: null,
        brands: [],
        categories: []
    },

    /**
     * Blurs the background of each category's page header.
     *
     */
    blurBackground: function () {
        $(".category-header").blurjs({
            source: ".category-header"
        });
    },


    // SORTING FEATURE
    /**
     * TODO: REFACTOR ALL LOGIC INTO ITS OWN CONTAINER
     *
     */
    itemsPerPage: function () {
        $(".items-per-page .item").on("click", function() {
            UtilityContainer.urlAddParameters("per_page", $(this).data("sort"));
        });

        // Set the selected option.
        $('#items-per-page-box').dropdown('set selected', this.searchParameters.per_page);
    },

    sortBy: function () {
        $(".sort-by .item").on("click", function() {
            UtilityContainer.urlAddParameters("order", $(this).data("sort"));
        });

        // Set the selected option.
        $('#sort-by-box').dropdown('set selected', this.searchParameters.order);
    },

    /**
     * Adds the price filter to the search query.
     */
    priceUpdate: function() {

        $("#price-update").on("click", function()
        {
            UtilityContainer.urlAddParameters({
                min_price : $("#min-price").val(),
                max_price : $("#max-price").val()
            });
        });

        // Set the specified price range.
        if (this.searchParameters.min_price) {
            $('#min-price').val(this.searchParameters.min_price);
        }

        if (this.searchParameters.max_price) {
            $('#max-price').val(this.searchParameters.max_price);
        }
    },

    /**
     * Adds the category filter to the search query.
     */
    categoriesUpdate: function() {

    },

    /**
     * Adds the brands filter to the search query.
     */
    brandsUpdate: function() {

    },

    toggleLayout: function () {
        var $container = $(".layout-toggle-container"),
            $product = $(".dense_product"),
            $product_img = $(".product-image"),
            $product_buybutton = $(".dense_product .buybutton");

        $("#list-layout, #grid-layout").on("click", function () {

            if($container.hasClass("grid-layout"))
            {
                // List layout
                $container.removeClass("grid-layout").addClass("list-layout");

                $product.removeClass("col-xs-6 col-sm-4 col-md-3 text-center no-border")
                    .addClass("col-xs-12 col-sm-12 col-md-12 border-bottom padding-1");

                $product_img.removeClass("img-responsive center-block").addClass("pull-left").css("margin-right", "5%");

                $product_buybutton.css("margin-top", "3%");


                $(this).toggleClass("active");
            }
            else if ($container.hasClass("list-layout"))
            {
                // Grid layout
                $container.removeClass("list-layout").addClass("grid-layout");

                $product.removeClass("col-xs-12 col-sm-12 col-md-12 border-bottom padding-1").
                    addClass("col-xs-6 col-sm-4 col-md-3 text-center no-border");

                $product_img.addClass("img-responsive center-block").removeClass("pull-left").css("margin-right", "0");

                $product_buybutton.css("margin-top", "0");

                $(this).toggleClass("active");
            }
        })
    },

    /**
     * Retrieves the query parameters from the URL and stores them locally.
     *
     */
    retrieveSearchParameters: function() {

        var query = UtilityContainer.urlGetParameters();

        for (var key in query) {
            this.searchParameters[key] = query[key];
        }
    },

    init: function () {
        var self = categoryContainer;

        self.retrieveSearchParameters();
        self.blurBackground();
        self.itemsPerPage();
        self.sortBy();
        self.priceUpdate();
        self.categoriesUpdate();
        self.brandsUpdate();
        self.toggleLayout();
    }

};
