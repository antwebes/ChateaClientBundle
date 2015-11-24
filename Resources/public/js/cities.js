(function(window, api_endpoint, language){
	var language = language == '' ? 'en' : language;
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

        var $sendButton = selectComponent('send');

        var disableButtonIfNoCity = function(){
            if($currentCity.val().length == 0){
                $sendButton.attr('disabled','disabled');
            }else{
                $sendButton.removeAttr('disabled');
            }
        };

        $formSearchCity.change(disableButtonIfNoCity);

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
                    $formSearchCity.val(response.city +', '+ response.region);
                    $currentCity.val(response.id);
                    selectActualCountry(response.country_code);
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
            }else{
                findCityByIp();
            }

            if(userSelectedCountry === ''){
                cityByIpPromise.done(function(){
                	$formSearchCountry.find('option').filter(function(i, e) { return $(e).text() == country_auto_geo}).prop('selected',true);
//                    $formSearchCountry.find('option:contains('+country_auto_geo+')').prop('selected',true);
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

                    selectActualCountry(countryCode);
                    $formSearchCountry.trigger('change');
                    $formSearchCity.val(cityName + ', ' + countryName);
                    $currentCity.val(actualCityId);
                    disableButtonIfNoCity();
                });
            }
        }

        function selectActualCountry(countryCode){
            var $countryOptions = $formSearchCountry.find('option');

            // search for the option that has the country code
            var $currentCountry = $countryOptions.filter(function(i, elem){
                var data = JSON.parse($(elem).val());

                return data.country_code == countryCode;
            });

            $currentCountry.prop('selected', true);
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
                $currentCity.val('');
                //show the input to write city
                $formSearchCity.parent().parent().show();
            }

            disableButtonIfNoCity();
        });
        
      //fill value with data to city_es of api
        function fillItemWithValuesInSpanish(item, value){
        	var item_es = item.city_es;
        	
        	if (item_es.name != ""){
        		value['name'] = item_es.name;
        	}
        	if (item_es.province_name != ""){
        		value['province_name'] = item_es.province_name;
        	}
        	if (item_es.region_name != ""){
        		value['region_name'] = item_es.region_name;
        	}
        	
        	return value;
        }

        //Show value to insert in options to choose city
        function getValueShowOptionsCity(item){
        	var value = new Array();
        	
        	if (item.name != ""){
        		value['name'] = item.name;
        	}
        	if (item.province_name != ""){
        		value['province_name'] = item.province_name;
        	}
        	if (item.region_name != ""){
        		value['region_name'] = item.region_name;
        	}
        	
        	if (language == "es"){
        		value = fillItemWithValuesInSpanish(item, value);
        	}    	
        	
        	var result = "";
        	var cont = 0;
        	var cont_comma = 0;
        	
        	for (item in value) {
        		if (cont > cont_comma && value[item] != ""){
        			cont_comma++;
        			result += ", ";
        		}
        		if (value[item] != ""){
        			cont++;
        			result += value[item];
        		}
        	}
        	
        	return result;
        }

        var formSearchCitySelectChanged = false;
        
        $formSearchCity.autocomplete({
            source: function( request, response ) {
                var url_source = api_endpoint+'/geo/countries/'+ country_code + '/cities/' + $formSearchCity.val();

                var term = request.term;
                if ( term in search_city_cache ) {
                    response($.map (search_city_cache[ term ], function( item ) {
                        return {
                            value   : getValueShowOptionsCity(item),
                            cityId  : item.id
                        }
                    }));
                    return;
                }
                $.ajax({
                    url: url_source,
                    dataType: "json",
                    success: function( data ) {
                    	if (data){
	                        search_city_cache[ term ] = data;
	                        response( $.map( data, function( item ) {
	                            return {
	                                value: getValueShowOptionsCity(item),
	                                cityId: item.id
	                            }
	                        }));
                    	}
                    }
                });
            },
            minLength: 2,
            select: function( event, ui ) {
                formSearchCitySelectChanged = true;
                $currentCity.val(ui.item.cityId);
                disableButtonIfNoCity(); // check button again
            },
            open: function() {
                $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
            },
            close: function() {
                $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
            }
        });

        $formSearchCity.change(function(){
            var $this = $(this);

            if(formSearchCitySelectChanged){ // change occurs after changing city so we don't change current city to empty
                formSearchCitySelectChanged = false;
            }else{
                $currentCity.val(''); //the change does not occur due the autocomplete                 
            }

            disableButtonIfNoCity();
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
})(window, window.api_endpoint, window.language);