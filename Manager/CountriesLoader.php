<?php
/**
 * User: José Ramón Fernandez Leis
 * Email: jdeveloper.inxenio@gmail.com
 * Date: 2/05/16
 * Time: 11:24
 */

namespace Ant\Bundle\ChateaClientBundle\Manager;


class CountriesLoader
{
    private $contriesPath;

    /**
     * CountriesLoader constructor.
     * @param $contriesPath
     */
    public function __construct($contriesPath)
    {
        $this->contriesPath = $contriesPath;
    }

    public function loadCountries()
    {
        $content = json_decode(file_get_contents($this->contriesPath), true);

        $builder = function($entry){
            $country = $entry['name'];

            unset($entry['name']);

            $hasCities = $entry['has_cities'] ? 'true' : 'false';
            $value = '{"country_code":"'.$entry['country_code'].'","has_cities":'.$hasCities.',"id":'.$entry['id'].'}';

            return array('value' => $value, 'name' => $country);
        };

        return array_map($builder, $content);
    }
}