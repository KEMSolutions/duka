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
     *
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
     *
     */
    categoriesUpdate: function() {
        this.filterListUpdate($("#refine-by-category"), "categories");
    },

    /**
     * Adds the brands filter to the search query.
     *
     */
    brandsUpdate: function() {
        this.filterListUpdate($("#refine-by-brand"), "brands");
    },

    /**
     * Shortcut to handle filter lists such as brands and categories.
     *
     * @param el
     * @param type
     */
    filterListUpdate : function(el, type)
    {
        // Performance check.
        if (!el) {
            return;
        }

        // Add the event listeners to each child element.
        el.find(".item").on("change",
            {
                filter : type || "brands"
            },

            function(event)
            {
                var ID = $(this).data("filter"), filterList = categoryContainer.searchParameters[event.data.filter];

                // Add brand to filter.
                if ($(this).prop("checked")) {
                    filterList.push(ID);
                }

                // Or remove it.
                else
                {
                    var newList = [];

                    if (filterList.length > 1) {
                        for (var index in filterList) {
                            if (filterList[index] != ID) {
                                newList.push(filterList[index]);
                            }
                        }
                    }

                    filterList = newList;
                }

                // Reorder filter list.
                filterList.sort(function(a, b) {
                    return a - b;
                });

                // Update page.
                if (filterList.length > 0) {
                    var filter = filterList.length > 1 ? filterList.join(';') : filterList[0];
                    UtilityContainer.urlAddParameters(event.data.filter, filter);
                } else {
                    UtilityContainer.urlRemoveParameters(event.data.filter);
                }
        });

        // Update selected checkboxes. IDs are stored as strings in "categoryContainer.searchParameters".
        el.find(".item").each(function() {
            $(this).prop("checked", categoryContainer.searchParameters[type].indexOf(""+ $(this).data("filter")) > -1);
        });
    },

    /**
     * Switch between grid or list layout.
     *
     */
    toggleLayout: function () {
        var self= categoryContainer,
            $container = $(".layout-toggle-container"),
            $product = $(".dense-product"),
            $product_img = $(".product-image"),
            $product_buybutton = $(".dense-product .buybutton"),
            $product_shortDescription = $(".dense-product .short-description"),
            $product_name = $(".dense-product .name a");

        $("#category-layout-switcher").on("click", function () {

            if($container.hasClass("grid-layout"))
            {
                // List layout
                $container.removeClass("grid-layout").addClass("list-layout");

                $product.removeClass("four wide column text-center no-border")
                    .addClass("sixteen wide column border-bottom-clear");

                $product_shortDescription.removeClass("hidden");

                $product_name.addClass("ui medium header");

                $product_img.removeClass("center-block").addClass("pull-left").css("margin-right", "5%");

                $product_buybutton.css("margin-top", "2rem");

                self.localizeSwitcher($(this), "grid");
            }
            else if ($container.hasClass("list-layout"))
            {
                // Grid layout
                $container.removeClass("list-layout").addClass("grid-layout");

                $product.removeClass("sixteen wide column border-bottom-clear").
                    addClass("four wide column text-center no-border");

                $product_img.addClass("center-block").removeClass("pull-left").css("margin-right", "0");

                $product_shortDescription.addClass("hidden");

                $product_name.removeClass("medium").addClass("tiny");

                $product_buybutton.css("margin-top", "0");

                self.localizeSwitcher($(this), "list");
            }
        })
    },

    /**
     * Utility function to localize the layout switch button in the appropriate locale.
     *
     * @param element
     * @param layout
     */
    localizeSwitcher: function(element, layout) {
        layout === "list" ?
            element.html("<i class='list layout icon'></i>" + Localization.list) :
            element.html("<i class='grid layout icon'></i>" + Localization.grid);
    },

    /**
     * Retrieves the query parameters from the URL and stores them locally.
     *
     */
    retrieveSearchParameters: function() {

        var query = UtilityContainer.urlGetParameters();

        for (var key in query)
        {
            this.searchParameters[key] = query[key];

            // For brands and categories, the value should be an array.
            if (["brands", "categories"].indexOf(key) > -1 && typeof query[key] != 'object') {
                this.searchParameters[key] = [query[key]];
            }
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

