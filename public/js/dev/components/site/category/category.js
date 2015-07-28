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


    /**
     * TODO: REFACTOR ALL LOGIC INTO ITS OWN CONTAINER
     *
     */
    itemsPerPage: function () {
        $(".items-per-page .item").on("click", function() {
            categoryContainer.URL_add_parameter("per_page", $(this).data("sort"));
        });
    },

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
    }

}