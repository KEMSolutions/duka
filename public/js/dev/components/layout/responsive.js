/**
 * Responsible for handling the switch between one, two and four columns per row depending on screen width.
 *
 * @type {{tablet: {setClasses: Function}, mobile: {setClasses: Function}, desktop: {setClasses: Function}, init: Function}}
 */
var responsiveContainer = {
    // Everything between 400px and 768px is considered tablet size.
    tablet : {
        setClasses: function () {
            // Take the stackable off the grid-layout.
            $(".grid-layout").removeClass("stackable");
            // Set two products per row.
            $(".dense-product").removeClass("four wide column").addClass("eight wide column");
        }
    },

    // Everything less than 400px is considered mobile size.
    mobile : {
        setClasses: function () {
            $(".grid-layout").addClass("stackable");
        }
    },

    // Everything more than 768px is considered desktop size.
    desktop: {
        setClasses: function () {
            $(".grid-layout").removeClass("stackable");
            // Set four products per row.
            $(".dense-product").removeClass("eight four wide column").addClass("four wide column");
        }
    },

    init: function () {
        var self = responsiveContainer;

        $(window).on("load resize", function () {
            if ($(this).width() < 768 && $(this).width() > 400) {
                self.tablet.setClasses();
            }
            else if ($(this).width() <= 400) {
                self.mobile.setClasses();
            }
            else if ($(this).width() >= 768) {
                self.desktop.setClasses();
            }
        });
    }
}