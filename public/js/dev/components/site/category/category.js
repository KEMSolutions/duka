/**
 * Object responsible for the view component of each category page.
 *
 * @type {{blurBackground: Function, init: Function}}
 */
var categoryContainer = {

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
            categoryContainer.URL_add_parameter("per_page", $(this).data("sort"));
        });
    },

    sortBy: function () {
        $(".sort-by .item").on("click", function() {
            categoryContainer.URL_add_parameter("order", $(this).data("sort"));
        })
    },


    // FILTERING FEATURE
    priceUpdate: function() {

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


    // HELPER FUNCTION : TO BE MOVED IN UTILITYCONTAINER
    // Courtesy of http://stackoverflow.com/a/1917916
    URL_add_parameter: function(key, value){
        key = escape(key); value = escape(value);

        var kvp = document.location.search.substr(1).split('&');
        if (kvp == '') {
            document.location.search = '?' + key + '=' + value;
        }
        else {

            var i = kvp.length; var x; while (i--) {
                x = kvp[i].split('=');

                if (x[0] == key) {
                    x[1] = value;
                    kvp[i] = x.join('=');
                    break;
                }
            }

            if (i < 0) { kvp[kvp.length] = [key, value].join('='); }

            //this will reload the page, it's likely better to store this until finished
            document.location.search = kvp.join('&');
        }
    },

    init: function () {
        var self = categoryContainer;

        self.blurBackground();
        self.itemsPerPage();
        self.sortBy();
        self.toggleLayout();
    }

}