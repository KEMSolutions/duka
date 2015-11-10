/**
 * Component responsible for displaying the cart drawer.
 * Logic handled in dev/actions/layout/cart-drawer-logic.js
 *
 * @type {{$el: {$back: (*|jQuery|HTMLElement), $proceed: (*|jQuery|HTMLElement), $trigger: (*|jQuery|HTMLElement), $container: (*|jQuery|HTMLElement), $checkout: (*|jQuery|HTMLElement), $body: (*|jQuery|HTMLElement)}, displayOn: Function, displayOff: Function, animateIn: Function, animateOut: Function, setCartItemsHeight: Function, computeCartItemsHeight: Function, fadeInDimmer: Function, init: Function}}
 */
var cartDisplayContainer = {
    $el : {
        $back : $("#back"),
        $proceed : $("#proceed"),
        $trigger : $(".view-cart"),
        $container : $("#cart-container"),
        $checkout : $("#checkout"),
        $body : $("body")
    },

    /**
     * Display the cart drawer on first load if localStorage cookie "isDisplayed" is set to true.
     *
     */
    displayOn: function() {
        var _width = cartDisplayContainer.$el.$container.width();
        cartDisplayContainer.$el.$container.css( {
            "margin-right" : -_width
        });

        cartDisplayContainer.$el.$trigger.click(function() {
            $(window).width() > 768 ? cartDisplayContainer.animateIn() : cartDisplayContainer.fadeInDimmer();
        });
    },

    /**
     * Hide the cart drawer on first load if localStorage cookie "isDisplayed" is set to false.
     *
     */
    displayOff : function() {
        cartDisplayContainer.$el.$back.click(function() {
            cartDisplayContainer.animateOut();
        });
        cartDisplayContainer.$el.$checkout.click(function() {
            sessionStorage.isDisplayed = false;

            // Register a mixpanel event: checkoutPage
            mixpanelAnalytics.events.checkoutPage();
        });
    },

    /**
     * Animate in the cart drawer (sets its margin right to +width).
     *
     */
    animateIn : function() {
        cartDisplayContainer.$el.$container.css('visibility', 'visible');
        cartDisplayContainer.$el.$container.animate( {
            "margin-right" : 0
        }, 400);
        sessionStorage.isDisplayed = true;
    },

    /**
     * Animate out the cart drawer (sets its margin right to -width).
     *
     */
    animateOut: function() {
        var _width = cartDisplayContainer.$el.$container.width();
        cartDisplayContainer.$el.$container.animate( {
            "margin-right" : -_width
        }, 400, function() {
            $(this).css("visibility", "hidden");
        });
        sessionStorage.isDisplayed = false;
    },

    /**
     * Set the appropriate height for #cart-items list.
     *
     */
    setCartItemsHeight : function() {
        $(window).on("load resize", function() {
            $("#cart-items").css("height", cartDisplayContainer.computeCartItemsHeight());
        });
    },

    /**
     * Compute the appropriate height for #cart-items list.
     *
     */
    computeCartItemsHeight : function() {
        return $("#cart-container").height() - ($(".cart-header").height() + $(".cart-footer").height());
    },

    /**
     * Fade in the cart dimmer.
     *
     */
    fadeInDimmer: function () {
        $('.ui.dimmer')
            .dimmer('show')
        ;
    },

    /**
     * Fade out the cart dimmer.
     *
     */
    fadeOutDimmer: function () {
        $(".close-cart-dimmer").on("click touchend", function () {
            $(".ui.dimmer")
                .dimmer('hide')
            ;
        })
    },

    init : function() {
        cartDisplayContainer.displayOn();
        cartDisplayContainer.displayOff();
        UtilityContainer.populateCountry($("html").attr("lang"));
        cartDisplayContainer.fadeOutDimmer();

        if (sessionStorage.isDisplayed == "true")
        {
            cartDisplayContainer.$el.$container.css({
                "margin-right": 0,
                "visibility": "visible"
            });
        }

    }
};