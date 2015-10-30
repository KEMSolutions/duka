/**
 * Component responsible for the view component of each category page.
 *
 * @type {{searchParameters: {page: number, per_page: number, order: string, min_price: null, max_price: null, brands: Array, categories: Array}, blurBackground: Function, itemsPerPage: Function, sortBy: Function, price: Function, categories: Function, brands: Function, updateFilterList: Function, addTag: Function, tags: Function, addFilter: Function, removeFilter: Function, updateFilters: Function, toggleLayout: Function, localizeSwitcher: Function, retrieveSearchParameters: Function, toggleTagsList: Function, localizeDimmer: Function, addDimmer: Function, init: Function}}
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


    /**
     * Sets a number of items per page and set the value to the appropriate input.
     *
     */
    itemsPerPage: function () {
        $(".items-per-page .item").on("click", function() {
            categoryContainer.addDimmer();
            UtilityContainer.urlAddParameters("per_page", $(this).data("sort"));
        });

        // Set the selected option.
        $('#items-per-page-box').dropdown('set selected', this.searchParameters.per_page);
    },


    /**
     * Sets the sort by filter and set the value to the appropriate input.
     *
     */
    sortBy: function () {
        $(".sort-by .item").on("click", function() {
            categoryContainer.addDimmer();
            UtilityContainer.urlAddParameters("order", $(this).data("sort"));
        });

        // Find the text for the selected option.
        $(".sort-by .item").each(function(index, element) {
            if ($(element).data('sort') == categoryContainer.searchParameters.order) {
                $("#sort-by-box").dropdown("set selected", $(element).data('sort'));
                return false;
            }
        });
    },

    /**
     * Adds the price filter to the search query and updates the filter on the page.
     *
     */
    price: function() {

        $("#price-update").on("click", function()
        {
            categoryContainer.addDimmer();

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
     * Adds the category filter to the search query and updates the filter on the page.
     *
     */
    categories: function() {
        this.updateFilterList($("#refine-by-category"), "categories");
    },

    /**
     * Adds the brands filter to the search query and updates the filter on the page.
     *
     */
    brands: function() {
        this.updateFilterList($("#refine-by-brand"), "brands");
    },

    /**
     * Shortcut to handle filter lists such as brands and categories.
     *
     * @param element
     * @param filterType
     */
    updateFilterList : function(element, filterType)
    {
        // Add the event listeners to each child element.
        element.find(".item").on("change",
            {
                filter : filterType || "brands"
            },

            function(event)
            {
                var id = $(this).data("filter"),
                    filterList = categoryContainer.searchParameters[event.data.filter],
                    filter = $(this);

                // If the checkbox is checked, add the filter to the list.
                if (filter.prop("checked")) {
                    categoryContainer.addFilter(event.data.filter, id);
                }

                // If not, then remove it from the list.
                else {
                    categoryContainer.removeFilter(event.data.filter, id);
                }
            }
        );

        // Update selected checkboxes. IDs are stored as strings in "categoryContainer.searchParameters".
        element.find(".item").each(function() {

            $(this).prop("checked", categoryContainer.searchParameters[filterType].indexOf(""+ $(this).data("filter")) > -1);

            // And add the filter as a tag.
            if ($(this).prop("checked")) {
                categoryContainer.addTag($(this));
            }
        });
    },

    /**
     * Create a new tag to be appended to the tags list.
     *
     * @param filter (filter being the checkbox DOM node)
     */
    addTag: function (filter) {
        var item =
        '<div class="item">' +
        '<a class="ui grey tag label">' + filter.data("name") +
        '<i class="icon remove right floated" data-id="' + filter.data("filter") + '" data-type="' + filter.data('type') + '"></i>' +
        '</a>' +
        '</div>';

        $(".tags-list").append(item);
    },

    /**
     * Attaches the remove event to the tags.
     *
     */
    tags: function() {
        $(".tags-list .item .remove").on("click", function() {
            categoryContainer.removeFilter($(this).data('type'), $(this).data('id'));
        });
    },

    /**
     * Adds a filter and refreshes the page.
     *
     * @param filterType    Either "brands" or "categories".
     * @param id            ID of brand or category.
     */
    addFilter: function(filterType, id) {
        this.searchParameters[filterType].push(id);
        this.updateFilters(filterType);
    },

    /**
     * Removes a filter and refreshes the page.
     *
     * @param filterType    Either "brands" or "categories".
     * @param id            ID of brand or category.
     */
    removeFilter: function(filterType, id) {

        // Retrieve filter list.
        var filterList = this.searchParameters[filterType], newList = [];

        // Rebuild a new list, without the filter we want removed.
        if (filterList.length > 1) {
            for (var index in filterList) {
                if (filterList[index] != id) {
                    newList.push(filterList[index]);
                }
            }
        }

        this.searchParameters[filterType] = newList;
        this.updateFilters(filterType);
    },

    updateFilters: function(filterType) {

        // Reorder filter list (this will help with caching on Laravel's end).
        var filterList = this.searchParameters[filterType];
        filterList.sort(function(a, b) {
            return a - b;
        });

        // If we have filters, update the query string and refresh the page.
        if (filterList.length > 0) {
            var filter = filterList.length > 1 ? filterList.join(';') : filterList[0];
            categoryContainer.addDimmer();
            UtilityContainer.urlAddParameters(filterType, filter);
        }

        // If we don't have any filters left, refresh the page without the filter parameter.
        else {
            UtilityContainer.urlRemoveParameters(filterType);
        }
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

    toggleTagsList: function () {
        $(".tags-list").children().size() > 0 ? $(".tags-list").parent().removeClass("hidden") : "";
    },

    /**
     * Localize the dimmer text with the appropriate message.
     *
     */
    localizeDimmer: function () {
        $(".loading-text").text(Localization.loading);
    },

    /**
     * Add a dimmer to the body when adding / removing a new filter.
     *
     */
    addDimmer: function () {
        var dimmer =
        '<div class="ui page dimmer loading-dimmer">' +
        '<div class="content">' +
        '<div class="center">' +
        '<div class="ui text loader">' +
        '<h1 class="ui header loading-text"></h1></div>' +
        '</div>' +
        '</div>';

        $("body").append(dimmer);

        categoryContainer.localizeDimmer();

        $('.ui.dimmer.loading-dimmer')
            .dimmer('show')
        ;
    },

    init: function () {
        var self = categoryContainer;

        self.retrieveSearchParameters();
        self.blurBackground();
        self.itemsPerPage();
        self.sortBy();
        self.price();
        self.categories();
        self.brands();
        self.tags();
        self.toggleLayout();
        self.toggleTagsList();
    }
};
