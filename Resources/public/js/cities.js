(function(window, api_endpoint){
    $(document).ready(function(){
    var country_code = '';
    var country_auto_geo = '';
    var search_city_cache = {};
    var search_country_cache = {};

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

            $('#suggestion_city').text('i.e: '+ response.city +', '+ response.country );
            //$('#form_search_country').val(response.country);
            //$('#form_search_country option:contains('+country_auto_geo+')').prop('selected',true);
            $('#registration_form_search_city').val('');
            country_code = response.countryCode;

            //$("#form_search_country").trigger('change');

            $('#suggestion_city').click(function (){
                $('#registration_form_search_city').val(response.city +', '+ response.country);
                $('#').val(response.cityId);
                country_code = response.countryCode;
            });
        });

        return promise;
    }

    function fillSelectCountry(userSelectedCountry)
    {
        var cityByIpPromise, url_source = api_endpoint+'/geo/countries';

        if(userSelectedCountry === ''){
            cityByIpPromise = findCityByIp();

            console.log(cityByIpPromise);
        }

        if(userSelectedCountry === ''){
            cityByIpPromise.done(function(){
                $('#form_search_country option:contains('+country_auto_geo+')').prop('selected',true);
                $("#form_search_country").trigger('change');
                setSelectedCity();
            });
        }else{
            $('#form_search_country option[value="'+userSelectedCountry+'"]').prop('selected',true);
            setSelectedCity();
        }

        /*$.ajax({
            url: url_source,
            dataType: "json",
            success: function( data ) {
                for (var i=0;i<data.length;i++)
                {
                    var json = {'country_code': data[i].country_code, 'has_cities': data[i].has_cities, 'city_default': data[i].city_default};
                    var jsonToString = JSON.stringify(json);

                    $('#form_search_country').append($('<option>', {
                        value: jsonToString,
                        text: data[i].name
                    }));
                }

                if(userSelectedCountry === ''){
                    cityByIpPromise.done(function(){
                        $('#form_search_country option:contains('+country_auto_geo+')').prop('selected',true);
                        $("#form_search_country").trigger('change');
                    });
                }else{
                    $('#form_search_country option[value="'+userSelectedCountry+'"]').prop('selected',true);
                }

                setSelectedCity();
            }

        });*/
    }

    /*
     * this function is activated for each click in the select of countries
     */
    $("#form_search_country").change(function(){

        var countryJSON = $(this).children(":selected").val();
        countryJSON = $("<div/>").html(countryJSON).text();

        var country = $.parseJSON(countryJSON);
        country_code = country.country_code;
        //if country has not cities
        if (!country.has_cities){
            if (country.city_default > 0){
                $('#user_registration_city').val(country.city_default);
            }else{
                //error the country must has a city_default
                $('#user_registration_city').val('666');
            }
            $('#registration_form_search_city').parent().parent().hide();
        }else{
            //empty input so user can write city and autocomplete
            $('#registration_form_search_city').val('');
            //empty value the id of city to save in user
            $('#user_registration_city').val('');
            //show the input to write city
            $('#registration_form_search_city').parent().parent().show();
        }
    });


    $( "#registration_form_search_city" ).autocomplete({
        source: function( request, response ) {
            var url_source = api_endpoint+'/geo/countries/'+ country_code + '/cities/' + $('#registration_form_search_city').val();

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
            $('#user_registration_city').val(ui.item.cityId);
        },
        open: function() {
            $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
        },
        close: function() {
            $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
        }
    });


    var setSelectedCity = function(){
        var countryJSON = $('#form_search_country').children(":selected").val();
        var country = $.parseJSON(countryJSON);
        country_code = country.country_code;

        if(country.has_cities){
            $('#register_select_city').show();
        }
    };

    window.fillSelectCountry = fillSelectCountry;
    });
})(window, window.api_endpoint);