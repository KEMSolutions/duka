$('select').chosen({});

function updateChosenSelects() {

    var chosenCountry = $('#country').val();
    if (chosenCountry == 'CA' || chosenCountry == 'US'){
        $('#postcode').removeAttr('disabled');
        $('#province').removeAttr('disabled');
        $('#province').trigger('chosen:updated');
    } else {
        $('#province').attr('disabled','disabled');
        $('#postcode').attr('disabled');
    }

    $('#province optgroup').attr('disabled','disabled');

    if (chosenCountry == 'CA' || chosenCountry == 'US' || chosenCountry == 'MX'){
        $('#province [data-country="' + chosenCountry + '"]').removeAttr('disabled');
    }

    $('#province').trigger('chosen:updated');
}

$('#country').chosen().change( function(){

    updateChosenSelects();

});


function updateTotal(){
    var total_price = parseFloat($('#price_subtotal').text()) + parseFloat($('#price_transport').text()) + parseFloat($('#price_taxes').text());
    $('#price_total').text(total_price.toFixed(2));
};

var checkoutEnabled = false;
function enableCheckout(){

    $("#payment").removeClass("hidden");
    $("#payment").addClass("animated fadeInDown");
    $("#checkoutButton").addClass("animated rubberBand");

    checkoutEnabled = true;
}

function updateTransport(){
    if (!$(".shipping_method:checked").val()){
        return;
    }

    $.getJSON( details_url, function( data ) {
        $("#price_subtotal").text(data.subtotal);
        $('#price_transport').text($(".shipping_method:checked").attr('data-cost'));

        if (data.rebate){
            $("#rebate_row").removeClass("hidden");
            var rebate_description = $("#couponField").attr("placeholder") + " <em>" + data.rebate.coupon + "</em>";
            $("#rebate_name").html(rebate_description);

            var rebate_amount;
            if (data.rebate.percent){
                rebate_amount = (data.rebate.percent * 100) + " %";
            }

            $("#rebate_value").html(rebate_amount);

        }

        updateTotal();
        enableCheckout();
    });


}

/**
 *  Will start the fetch estimate procedure only if user has finished filling up the Taxes and delivery section.
 *  Is used by the cart drawer script to update the total and shipping based on user modifications to the cart content.
 */
function cartCheckoutFetchEstimateProgramatically(){
    if (!$("#estimate").hasClass("hidden")){
        fetchEstimate();
    }
}

function fetchEstimate(){

    $(".has-error").removeClass("has-error");

    var email_value = $("#customer_email").val();
    var postcode_value = $("#postcode").val();
    var country_value = $("#country").val();

    var shouldBlock = false;
    if (email_value == ""){

        $("#customer_email").parent().addClass("has-error");
        $('#customer_email').addClass('animated shake');
        $('#customer_email').bind('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
            $(this).removeClass("animated");
            $(this).removeClass("shake");
            $(this).unbind();
        });

        $("#why_email").removeClass("hidden");
        $("#why_email").addClass("animated bounceInRight");


        shouldBlock = true;
    }


    if ((country_value == "CA" || country_value == "US") && postcode_value == "") {

        $("#postcode").parent().addClass("has-error");
        $('#postcode').addClass('animated shake');
        $('#postcode').bind('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
            $(this).removeClass("animated");
            $(this).removeClass("shake");
            $(this).unbind();
        });

        shouldBlock = true;

    }


    if (shouldBlock){
        return;
    }


    var estimateButtonText = $('#estimateButton').text();
    $('#estimateButton').html('<i class="fa fa-spinner fa-spin"></i>');


    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: estimate_url,
        data: {'country':country_value, 'province':$("#province").val(), 'postalcode':postcode_value,'email':email_value},
        success: function(data){

            // If the element has already the animated class, that means our user ran the estimate script once already
            var firstRun = true;
            if ($("#estimate").hasClass("animated")){
                firstRun = false;
            }

            $('#estimate').html(data.shipping_block);
            $("#estimate").removeClass("hidden");
            $("#estimate").addClass("animated fadeInDown");

            $("#estimateButton").removeClass("btn-three");
            $("#estimateButton").addClass("btn-one");
            $('#estimateButton').text(estimateButtonText);

            $('html, body').animate({
                scrollTop: $("#estimate").offset().top
            }, 1000);

            $('#price_taxes').text(data.taxes.toFixed(2));

            // If this is not the first run, we need to manually update the total price for the order with the provided data
            if (firstRun){
                $('#estimate').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
                    updateTransport();
                });
            } else {
                updateTransport();
            }



            //$('body').scrollTo('#estimate');


            // Register the received radio buttons to trigger a total update
            $(".shipping_method").change(function(){
                updateTransport();
            });



        },
        error: function(xhr, textStatus){

            if (xhr.status == 403){
                window.location.replace(login_url);
                return;
            }

            $('#estimate').html('<div class="alert alert-danger">Une erreur est survenue. Veuillez vérifier les informations fournies.</div>');
        }

    });
}

$('#estimateButton').click(function( event ){

    event.preventDefault();
    fetchEstimate();

});


$('#checkoutButton').click( function(event){


    if (!checkoutEnabled){
        event.preventDefault();
    }

    $(this).removeClass("btn-three");
    $(this).addClass("btn-one");
    $(this).html("<i class=\"fa fa-spinner fa-spin\"></i>");
    $(".btn").attr("disabled", "disabled");


    $.ajax({
        type: "POST",
        url: paypaltoken_url,
        data: $("#cart_form").serialize(),
        success: function(data){
            // Restore part of the UI so our user can come back from paypal using the back button and still be able to order
            $("#checkoutButton").removeClass("btn-three");
            $("#checkoutButton").addClass("btn-one");
            $(".btn").removeAttr("disabled");
            window.location.href = data.paypal_url;

        },
        dataType: "json"
    });



    event.preventDefault();



});


$('.update_cart_quantity').click( function(){

    var row = $(this).closest('tr');
    var product_id = row.attr('data-product');
    var quantity = row.find('.quantity_field').val();

    $.post( update_url, { product: product_id, quantity: quantity })
        .done(function( data ) {
            location.reload();
        });

});


$('.cart_remove_button').click(function(){

    var row = $(this).closest('tr');
    var product_id = row.attr('data-product');
    var quantity = row.find('.quantity_field').val();

    $.post( remove_url, { product: product_id })
        .done(function( data ) {
            location.reload();
        });

});

$('#why_email').tooltip();



$("#couponField").keydown(function (e) {
    if (e.keyCode == 32) {
        return false; // prevent spaces
    }
});

$("#redeemCouponButton").click(function(){
    event.preventDefault();
    var field = $("#couponField");
    var button = $(this);
    var group = button.closest("div");
    var button_string = button.text();

    button.prop("disabled", true);
    field.prop("readonly", true);
    group.removeClass("has-error");

    button.html("<i class='fa fa-spinner fa-spin'></i>");
    $.post( redeem_url, {coupon:field.val()}, function( data ) {

        if (data.valid){

            button.html("<i class='fa fa-check-circle-o fa-lg'></i>");
            button.addClass("btn-success");
            group.addClass("has-success");
            cartCheckoutFetchEstimateProgramatically();

        } else {
            // The coupon code is invalid
            button.removeProp("disabled");
            field.removeProp("readonly");

            group.addClass('animated shake');
            group.addClass("has-error");
            button.text(button_string);

            group.bind('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
                $(this).removeClass("animated");
                $(this).removeClass("shake");
                $(this).unbind();
            });
        }



    }, "json");
});