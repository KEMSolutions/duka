$(document).ready(function () {
    $(".input-qty").TouchSpin({
        initval: 1
    });

    $.getJSON("/js/data/country-list.en.json", function(data) {
        var listItems = '',
            $country = $("#country");

        $.each(data, function(key, val) {
            listItems += "<option value='" + key + "'>" + val + "</option>";
        });
        $country.append(listItems);
    });
});