$('body').on('click', function (e) {
    // Hide all tooltips when user click outside
    if ($(e.target).data('toggle') !== 'tooltip'
        && $(e.target).parents('.tooltip.in').length === 0) {
        $('[data-toggle="tooltip"]').tooltip('hide');
    }
});

function updateBuyButtonsForProductWithId(id){
    $('button[data-product="' + id + '"]').html('<i class="fa fa-check-square"></i>');
    $('button[data-product="' + id + '"]').attr("disabled", "disabled");
}

if (page_lang == 'fr'){
    var localized_remove_item_string = "Enlever du panier";
    var localized_edit_item_quantity_string = "Mettre à jour la quantité";
} else {
    var localized_remove_item_string = "Remove from cart";
    var localized_edit_item_quantity_string = "Update quantity";
}

function updateCartOverview(openModal){
    $.getJSON( "/" + page_lang + "/cart/overview", function( data ) {

        $("#cart_modal_items").html("");
        var total_count = 0;


        $.each( data, function( index, val ) {
            total_count += parseInt(val.quantity);
            updateBuyButtonsForProductWithId(val.product_id);
            $(".cart_modal_items").append("<li class='media'><a class='pull-left' href='" + val.link + "'><img class='media-object' src='" + val.thumbnail + "' alt=''></a><div class='media-body'><h4 class='media-heading'>" + val.name + "</h4>" + val.quantity + " x " + val.price_paid + "</div></li>");

            var sidebarElement = '<li class="w-box animated bounceInDown" data-product="' + val.product_id + '" data-quantity=' + val.quantity + '>' +
                '<div class="col-xs-3 text-center"><img src=' + val.thumbnail_lg + ' class="img-responsive"></div>' +
                '<div class="col-xs-9 no-padding-left">' +
                '<div class="row"><div class="col-xs-10"><h2 class="product-name">' + val.name + '</h2></div><div class="col-xs-2"><h2 class="text-right"><i class="fa fa-trash fa-1 close-button"><span class="sr-only">' + localized_remove_item_string + '</span></i></h2></div></div>' +
                '<div class="row"><div class="col-xs-8"><div class="input-group"><input type="number" value="' + val.quantity + '" class="quantity form-control input-sm" min="1" step="1" >' +
                '<span class="input-group-addon update_quantity_indicator"><i class="fa" hidden><span class="sr-only">' + localized_edit_item_quantity_string + '</span></i></span></div></div>' +
                '<div class="col-xs-4 product-price text-right" data-price="' + val.price_paid + '">$' + (val.price_paid*val.quantity).toFixed(2) + '</div></div>' +
                '</div>' +
                '</li>';

            // Don't add the same product twice
            if (!$(".cart-items-list [data-product='" + val.product_id + "']").length){
                $(".cart-items-list").append(sidebarElement);
            }


            // Find products on the list view (if any)
            $('input[data-product=' + val.product_id + ']').val(val.quantity);
        });

        $("#cart_badge").text(total_count);
        if (openModal){
            $( "#modal_cart" ).load( "/" + $("html").attr("lang") + '/site/modalCart', function() {
                $("#cartModal").modal('show');
                $.each( data, function( index, val ) {
                    $("#cart_modal_items").append("<li class='media'><a class='pull-left' href='" + val.link + "'><img class='media-object' src='" + val.thumbnail + "' alt=''></a><div class='media-body'><h4 class='media-heading'>" + val.name + "</h4>" + val.quantity + " x " + val.price_paid + "</div></li>");
                });
            });
        }
    });
}



var page_lang = $("html").attr("lang");

function pageInitialization(){
    var w = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);


    $.getScript("/js/cartdrawer.js");


    $(".buybutton").click(function(){
        var buybutton = $(this);
        var product_id = buybutton.attr("data-product");

        var quantity;
        if($("#item_quantity").length) {
            quantity = $("#item_quantity").val();
        } else {
            quantity = 1;
        }

        buybutton.attr("disabled", "disabled");
        buybutton.html('<i class="fa fa-spinner fa-spin"></i>');

        $.post( "/" + page_lang + "/cart/add", { "product": product_id, "quantity" : quantity }, function( data ) {
            buybutton.addClass('animated tada');
            updateBuyButtonsForProductWithId(product_id);

            if (w < 961) {
                updateCartOverview(true);
            } else {
                updateCartOverview(false);
                cartDisplay.animateIn();
            }
        });
    })

    imagesLoaded( $("body"), function() {
        jQuery(window).load(function () {
            $(".input-qty").TouchSpin({
                initval: 1
            });
        });
        $( ".js-masonry" ).each(function( index ) {
            $(this).masonry();
        });
    });

    updateCartOverview(false);
}

// If our user is visiting a product page, we need to add that product to a json array stored in it's browser session storage
if( $('#product_info_box').length ){
    var product_id = $('#product_info_box').attr("data-product");
    var visited_products;
    if (localStorage.getItem("product_history")){
        visited_products = JSON.parse(localStorage.getItem("product_history"));
    } else {
        visited_products = [];
    }
    var current_index = visited_products.indexOf(product_id);
    if (current_index > -1) {
        visited_products.splice(current_index, 1);
    }
    visited_products.unshift(product_id);
    var visited_string = JSON.stringify(visited_products.slice(0,25));
    localStorage.setItem('product_history',visited_string);
}

// On pages where a product history section is included (index, cart), fetch a pre-formatted html list prior to initializing our buy buttons
if ($(".product_history_box").length && localStorage.getItem("product_history")){
    $(".product_history_title").removeClass("hidden");
    $(".product_history_box").removeClass("hidden");

    // Post the JSON formatted list of recent product IDs
    $.post( "/" + page_lang + "/product/thumbnails", { "products": localStorage.getItem("product_history"), "limit": $(".product_history_box").attr("data-limit")}, function( data ) {
        $(".product_history_box_content").html(data);
        // Wait until the images are loaded so we don't end up with a messed up layout

        pageInitialization();
    });
} else {
    // Initialize the buy button and cart counter immediatly
    pageInitialization();
}
