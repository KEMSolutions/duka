$(document).ready(function () {
    //fancy plugin for product page (quantity input)
    $(".input-qty").TouchSpin({
        initval: 1
    });

    //Initialize overlay plugin.
    paymentOverlayContainer.init();

    //Initialize navigation header.
    headerContainer.init();
});