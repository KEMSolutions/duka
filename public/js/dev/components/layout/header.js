/**
 * Object responsible for displaying the navigation header.
 *
 * @type {{md: {removeCartDescription: Function}, sm: {btnTransform_sm: Function}, init: Function}}
 */
var headerContainer = {
    /**
     * Desktop size
     *
     */
    md: {
        removeCartDescription : function() {
            if ($(window).width() <= 1195) {
                $("#nav-right #cart-description").text("");
                $("#nav-right").css("padding-bottom", "18px");
            }
        }
    },

    /**
     * Tablet size
     *
     */
    sm : {
        btnTransform_sm : function() {
            if ($(window).width() <= 934 && ($(window).width() >= 769)) {
                $(".row:first .btn").addClass("btn-sm");
                $("#searchBar").addClass("input-sm");
                $("#view-cart-wrapper").addClass("btn-xs btn-xs-btn-sm-height");
            }
        }
    },

    /**
     * Changes text from dropdown button within the parent node passed in the argument
     *
     * @param $elem
     */
    changeTextFromDropdown : function($elem) {
        $($elem + " .dropdown-menu li a").click(function(){

            $($elem + " .btn:first-child").html($(this).text() + '<span class=\"caret\"></span>');
            $($elem + " .btn:first-child").val($(this).text());

        });
    },

    /**
     * Register functions in event handler (onload, onresize) to be called outside of this object.
     *
     */
    init: function () {
        var self = headerContainer;

        $(window).on("load resize", function() {
            self.md.removeCartDescription();
            self.sm.btnTransform_sm();
        });

        self.changeTextFromDropdown(".search-filter");
    }
}
