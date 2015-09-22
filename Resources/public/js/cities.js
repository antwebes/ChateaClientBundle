(function(window, api_endpoint){
    $(document).ready(function(){
        var country_code = '';
        var country_auto_geo = '';
        var search_city_cache = {};
        var search_country_cache = {};

        // selects a component by data-city-finder attribute
        var selectComponent = function(component){
            return $('*[data-city-finder="' + component  + '"]');
        };

        var $currentCity = selectComponent('current_city');
        var $formSearchCountry = selectComponent('search_country');
        var $formSearchCity = selectComponent('search_city');
        var $suggestionCity = selectComponent('suggestion_city');

        function findCityByIp()
        {
            //console.log(api_endpoint+'/geo/locations');
            var promise = jQuery.ajax({
                url: api_endpoint+'/geo/locations',
                type: 'get',
                dataType:'json'
            });

            promise.done(function (response) {
                //save country auto geo locate
                country_auto_geo = response.country;

                $suggestionCity.text('i.e: '+ response.city +', '+ response.country );
                //$('#form_search_country').val(response.country);
                //$('#form_search_country option:contains('+country_auto_geo+')').prop('selected',true);
                $formSearchCity.val('');
                country_code = response.countryCode;

                //$("#form_search_country").trigger('change');

                $suggestionCity.click(function (){
                    $formSearchCity.val(response.city +', '+ response.country);
                    $currentCity.val(response.cityId);
                    country_code = response.countryCode;
                });
            });

            return promise;
        }

        function fillSelectCountry(userSelectedCountry)
        {
            var cityByIpPromise, url_source = api_endpoint+'/geo/countries';
            var actualCityId = $currentCity.val();

            if(userSelectedCountry === ''){
                cityByIpPromise = findCityByIp();

                console.log(cityByIpPromise);
            }

            if(userSelectedCountry === ''){
                cityByIpPromise.done(function(){
                    $formSearchCountry.find('option:contains('+country_auto_geo+')').prop('selected',true);
                    $formSearchCountry.trigger('change');
                    setSelectedCity();
                });
            }

            if(actualCityId != ''){
                var actualCityInfoPromise = $.getJSON(api_endpoint + '/geo/cities/' + actualCityId);

                actualCityInfoPromise.done(function(data){
                    var countryCode = data.country_code;
                    var countryName = data.country.name;
                    var cityName = data.name;
                    var $countryOptions = $formSearchCountry.find('option');

                    // search for the option that has the country code
                    var $currentCountry = $countryOptions.filter(function(i, elem){
                        var data = JSON.parse($(elem).val());

                        return data.country_code == countryCode;
                    });

                    $currentCountry.prop('selected', true);
                    $formSearchCountry.trigger('change');
                    $formSearchCity.val(cityName + ', ' + countryName);
                });
            }
        }

        /*
         * this function is activated for each click in the select of countries
         */
        $formSearchCountry.change(function(){
            // user_registration_city
            var countryJSON = $(this).children(":selected").val();
            countryJSON = $("<div/>").html(countryJSON).text();

            var country = $.parseJSON(countryJSON);
            country_code = country.country_code;
            //if country has not cities
            if (!country.has_cities){
                if (country.city_default > 0){
                    $currentCity.val(country.city_default);
                }else{
                    //error the country must has a city_default
                    $currentCity.val('666');
                }
                $formSearchCity.parent().parent().hide();
            }else{
                //empty input so user can write city and autocomplete
                $formSearchCity.val('');
                //empty value the id of city to save in user
                $formSearchCity.val('');
                //show the input to write city
                $formSearchCity.parent().parent().show();
            }
        });
        
        $formSearchCity.autocomplete({
            source: function( request, response ) {
                var url_source = api_endpoint+'/geo/countries/'+ country_code + '/cities/' + $formSearchCity.val();

                var term = request.term;
                if ( term in search_city_cache ) {
                    response($.map (search_city_cache[ term ], function( item ) {
                        return {
                            value   : item.name + ", " + item.country.name,
                            cityId  : item.id
                        }
                    }));
                    return;
                }
                $.ajax({
                    url: url_source,
                    dataType: "json",
                    success: function( data ) {
                        search_city_cache[ term ] = data;
                        response( $.map( data, function( item ) {
                            return {
                                value: item.name + ", " + item.country.name,
                                cityId: item.id
                            }
                        }));
                    }
                });
            },
            minLength: 2,
            select: function( event, ui ) {
                $currentCity.val(ui.item.cityId);
            },
            open: function() {
                $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
            },
            close: function() {
                $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
            }
        });


        var setSelectedCity = function(){
            var countryJSON = $formSearchCountry.children(":selected").val();
            var country = $.parseJSON(countryJSON);
            country_code = country.country_code;

            if(country.has_cities){
                $('#register_select_city').show();
            }
        };

        window.fillSelectCountry = fillSelectCountry;
    });
})(window, window.api_endpoint);