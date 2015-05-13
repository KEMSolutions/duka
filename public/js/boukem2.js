$(document).ready(function () {
    $(".input-qty").TouchSpin({
        initval: 1
    });

    /*
        Function to populate country list
        Activates the chosen plugin on the country select list.
     */
    $.getJSON("/js/data/country-list.en.json", function(data) {
        var listItems = '',
            $country = $("#country");

        $.each(data, function(key, val) {
            listItems += "<option value='" + key + "'>" + val + "</option>";
        });
        $country.append(listItems);
    }).done(function() {
        $("#country").chosen();
    });

    /*
        Function to populate provinces and states
        Activates the chosen plugin on the province select list.
     */
    function populateProvincesAndStates(country, callback) {
        $.getJSON("/js/data/world-states.json", function(data) {
            for(var i=0; i<country.length; i++) {
                var listItems = '',
                    $provinces = $("#province").find("[data-country='" + country[i] +"']");

                $.each(data, function(key, val) {
                    if (data[key].country === country[i]){
                        listItems += "<option value='" + data[key].short + "'>" + data[key].name + "</option>";
                    }
                });
                $provinces.append(listItems);
            }
            callback();
        });
    }

    /*
        Call the populateProvincesAndStates function with an array of countries.
        Callback to activate the chosen plugin.
     */
    populateProvincesAndStates(["CA", "US", "MX"], function() {
        $("#province").chosen();
    })

});