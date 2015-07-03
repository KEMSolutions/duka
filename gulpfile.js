var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Less
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.scripts([
        "../../../public/js/dev/components/checkout/billing.js",
        "../../../public/js/dev/components/checkout/estimate.js",
        "../../../public/js/dev/components/checkout/location.js",
        "../../../public/js/dev/components/checkout/payment.js",
        "../../../public/js/dev/components/layout/drawer.js",
        "../../../public/js/dev/components/layout/header.js",
        "../../../public/js/dev/components/layout/payment-overlay.js",
        "../../../public/js/dev/components/site/category.js",
        "../../../public/js/dev/utils/utility.js",
        "../../../public/js/dev/actions/checkout/checkout-init.js",
        "../../../public/js/dev/actions/checkout/checkout-logic.js",
        "../../../public/js/dev/actions/checkout/checkout-validation.js",
        "../../../public/js/dev/actions/layout/cart-drawer-logic.js",
        "../../../public/js/dev/actions/layout/cart-drawer-init.js",
        "../../../public/js/dev/actions/init.js"
    ], "public/js/prod/boukem2.js");
});
