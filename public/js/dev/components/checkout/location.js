/**
 * Object responsible for building the select list populating countries, provinces and states on checkout page.
 *
 * @type {{populateCountry: {getLocalizedCountryList: Function, loadCountryList: Function}, populateProvincesAndStates: Function, updateChosenSelects: Function, callUpdateChosenSelects: Function, init: Function}}
 */
var locationContainer = {

    /**
     * Function to populate country list
     * Activates the chosen plugin on the country select list.
     *
     */
    populateCountry : {
        getLocalizedCountryList : function() {
            if ($("html").data("lang") === "fr") {
                $.getJSON("/js/data/country-list.fr.json", populateCountry.loadCountryList(data))
                    .done(function() {
                        $(".country").chosen();
                    });
            }
            else {
                $.getJSON("/js/data/country-list.en.json", populateCountry.loadCountryList(data))
                    .done(function() {
                        $(".country").chosen();
                    });
            }
        },

        loadCountryList: function(data) {
            var listItems = '',
                $country = $(".country");

            $.each(data, function(key, val) {
                if (key == "CA") {
                    listItems += "<option value='" + key + "' selected>" + val + "</option>";
                }
                else {
                    listItems += "<option value='" + key + "'>" + val + "</option>";
                }
            });
            $country.append(listItems);
        }
    },

    //populateCountry : function() {
    //    $.getJSON("/js/data/country-list.en.json", function(data) {
    //        var listItems = '',
    //            $country = $(".country");
    //
    //        $.each(data, function(key, val) {
    //            if (key == "CA") {
    //                listItems += "<option value='" + key + "' selected>" + val + "</option>";
    //            }
    //            else {
    //                listItems += "<option value='" + key + "'>" + val + "</option>";
    //            }
    //        });
    //        $country.append(listItems);
    //    }).done(function() {
    //        $(".country").chosen();
    //    });
    //},

    /**
     * Function to populate provinces and states
     * Activates the chosen plugin on the province select list.
     *
     * @param country
     * @param callback
     */
    populateProvincesAndStates : function (country, callback) {
        $.getJSON("/js/data/world-states.json", function(data) {
            for(var i= 0, length = country.length; i<length; i++) {
                var listItems = '',
                    $province = $(".province").find("[data-country='" + country[i] +"']");

                $.each(data, function(key, val) {
                    if (data[key].country === country[i] && data[key].short == "QC" ){
                        listItems += "<option value='" + data[key].short + "' selected>" + data[key].name + "</option>";
                    }
                    else if (data[key].country === country[i]){
                        listItems += "<option value='" + data[key].short + "'>" + data[key].name + "</option>";
                    }
                });
                $province.append(listItems);
            }
            callback();
        });
    },

    /**
     * Event function enabling or disabling postcode and province fields according to the chosen country and the provided input (shipping or billing)
     *
     * @param chosenCountry
     * @param input
     */
    updateChosenSelects: function(chosenCountry, input) {
        if (chosenCountry == 'CA' || chosenCountry == 'US' || chosenCountry == "MX"){
            $(input).removeAttr('disabled').trigger("chosen:updated");
        } else {
            $(input).attr('disabled','disabled');
        }

        $(input + ' optgroup').attr('disabled','disabled');

        if (chosenCountry == 'CA' || chosenCountry == 'US' || chosenCountry == 'MX'){
            $(input + ' [data-country="' + chosenCountry + '"]').removeAttr('disabled');

        }

        $(input).trigger('chosen:updated');
    },

    /**
     * Triggers updateChosenSelects($country, $input)
     * This function will be registered in init().
     *
     */
    callUpdateChosenSelects: function(self) {
        $("#billingCountry").on("change", function() {
            self.updateChosenSelects($(this).val(), "#billingProvince");
        });

        $("#shippingCountry").on("change", function() {
            self.updateChosenSelects($(this).val(), "#shippingProvince");
        });
    },

    /**
     * Registering functions to be called outside of this object.
     *
     */
    init : function() {
        var self = locationContainer;

        self.populateCountry.getLocalizedCountryList();
        self.populateProvincesAndStates(["CA", "US", "MX"], function() {
            $(".province").chosen();
        });
        self.callUpdateChosenSelects(self);

    }
}