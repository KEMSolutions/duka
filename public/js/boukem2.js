$(document).ready(function () {
    $(".input-qty").TouchSpin({
        initval: 1
    });


    //Populate country list
    $.getJSON("/js/data/country-list.en.json", function(data) {
        var listItems = '',
            $country = $("#country");

        $.each(data, function(key, val) {
            listItems += "<option value='" + key + "'>" + val + "</option>";
        });
        $country.append(listItems);
    });

    //Populate CA provinces list
    $.getJSON("/js/data/ca-provinces.json", function(data) {
        var listItems = '',
            $provinces = $("#province").find("[data-country='CA']");

        $.each(data, function(key, val) {
            listItems += "<option value='" + key + "'>" + val + "</option>";
        });
        $provinces.append(listItems);
    });

    //Populate US states list
    $.getJSON("/js/data/us-states.json", function(data) {
        var listItems = '',
            $provinces = $("#province").find("[data-country='US']");

        $.each(data, function(key, val) {
            listItems += "<option value='" + key + "'>" + val + "</option>";
        });
        $provinces.append(listItems);
    });

    //Populate MX states list
    $.getJSON("/js/data/world-states.json", function(data) {
        var listItems = '',
            $provinces = $("#province").find("[data-country='MX']");

        $.each(data, function(key, val) {
            if (data[key].country === "MX"){
                listItems += "<option value='" + data[key].short + "'>" + data[key].name + "</option>";
            }
        });
        $provinces.append(listItems);
    });
});