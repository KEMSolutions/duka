Vue.config.debug = true;

Vue.component("product-card", {
    template: '#product-card-template',

    props: {
        name: String,
        productId: Number,
        route: String,
        formatNumber: Number,
        image: String,
        thumbnail: String,
        thumbnailLg: String,
        description: String,
        products: Array,
        firstFormatPrice: Number,
        firstFormatReducedPrice: Number,
        firstFormatRebatePercent: String,
        brandSlug: String,
        brandName: String
    },

    data: function () {
        return {
            productFormat: ""
        }
    }
});

new Vue({
    el: ".duka-container"
});