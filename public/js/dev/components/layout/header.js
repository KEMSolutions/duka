/**
 * Responsible for toggling mobile menu.
 *
 * @type {{init: headerContainer.init}}
 */
var headerContainer = {
    init: function() {
        $(".mobile-main-menu-trigger").on("click", function () {
            $(".mobile-main-menu").sidebar("toggle");
        });
    }
}