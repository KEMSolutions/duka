$(document).ready(function() {
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
    });


    /*
    Function to enable or disable fields according to the chosen country.
     */
    function updateChosenSelects(chosenCountry) {
        if (chosenCountry == 'CA' || chosenCountry == 'US' || chosenCountry == "MX"){
            $('#postcode').removeAttr('disabled');
            $('#province').removeAttr('disabled');
            $('#province').trigger('chosen:updated');
        } else {
            $('#province').attr('disabled','disabled');
            $('#postcode').attr('disabled', 'disabled');
        }

        $('#province optgroup').attr('disabled','disabled');

        if (chosenCountry == 'CA' || chosenCountry == 'US' || chosenCountry == 'MX'){
            $('#province [data-country="' + chosenCountry + '"]').removeAttr('disabled');
        }

        $('#province').trigger('chosen:updated');
    }

    $("#country").on("change", function() {
        updateChosenSelects($(this).val());
    });


    /**
     * AJAX TEST FOR CHECKOUT
     */
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("#estimateButton").on("click", function(e) {
        e.preventDefault();
        
        $.ajax({
            type: "POST",
            url: "/api/estimate",
            data: {
                country: "CA",
                postcode: "A5A 5A5",
                products: [
                    { id: "616", quantity: 500},
                    { id: "95"}
                ]
            },
            success: function(e) {
                console.log(e);
            },
            error: function(e, status, msg) {
                console.log(status + " : " +  msg);
            }
         })
    })



});