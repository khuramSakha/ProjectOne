<?php
//include "keys.php";
$GLOBALS['opencage']  = '4721141949f04dd488956b52432cc62f';
$GLOBALS['weather']  = 'e3e4efbcb8f1cc66e88d6069848cd669';
$GLOBALS['tripToken']  = 'l849pa51to2txof7bvx44h1it4qws85h';
$GLOBALS['triposo']  = 'HMCHJ7LI';


ini_set("display_errors", "1");
error_reporting(E_ALL);

if ($api = $_GET["get"] ?? null) {
    switch ($api) {
        case "countryList":
            echo getCountrylist();
            break;
        case "country":
            echo getCountry();
            break;
        case "geocode":
            echo geocode();
            break;
        case "covid":
            echo getCovid();
            break;
    }
}

function curl($url)
{
    $ch = curl_init();
    // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
    // curl_setopt($ch, CURLOPT_CAINFO, "../curl_ssl/cacert.pem");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

function geocode()
{
    if (($lat = $_GET["lat"] ?? null) && ($lng = $_GET["lng"] ?? null)) {
        $opencage =  json_decode(opencage("$lat+$lng"));
        if ($code = $opencage->results[0]->components->country_code ?? null) {
            return json_encode(["country_code" => $code]);
        }
    }
}

function getBorders($countryCode)
{
    $json =  file_get_contents("../json/countryBorders.geo.json");
    $data = json_decode($json)->features;
    foreach ($data as $country) {
        if ($country->properties->iso_a2 === $countryCode) {
            return $country;
        }
    }
}

function getCovid($code)
{
    $now = date("Y-m-d", time());
    // $url = '';
    // $url = "https://covidapi.info/api/v1/country/$code/timeseries/2020-01-01/$now";
    if($code == 'United Kingdom'){
        $code = 'UK';
    }else if($code == 'United Arab Emirates'){
        $code = 'UAE';
    }else if($code == 'United States'){
        $code = 'USA';
    }
    
    else if($code == 'New Zealand'){
        $code = 'New%20Zealand';
    }
    else if($code == 'Bosnia and Herzegovina'){
        $code = 'Bosnia%20and%20Herzegovina';
    }
    else if($code == 'Burkina Faso'){
        $code = 'Burkina%20Faso';
    }
    else if($code == 'Central African Republic'){
        $code = 'CAR';
    }
    else if($code == 'Czech Republic'){
        $code = 'Czechia';
    }
    else if($code == 'Democratic Republic of The Congo'){
        $code = 'DRC';
    }
    else if($code == 'Dominican Republic'){
        $code = 'Dominican%20Republic';
    }
    else if($code == 'El Salvador'){
        $code = 'El%20Salvador';
    }
    else if($code == 'El Salvador'){
        $code = 'El%20Salvador';
    }
    else if($code == 'Equatorial Guinea'){
        $code = 'Equatorial%20Guinea';
    }
    else if($code == 'Falkland Islands'){
        $code = 'Falkland%20Islands';
    }
    else if($code == 'Ivory Coast'){
        $code = 'Ivory%20Coast';
    }
    else if($code == 'New Caledonia'){
        $code = 'New%20Caledonia';
    }
    else if($code == 'North Korea'){
        $code = 'DPRK';
    }

    else if($code == 'Papua New Guinea'){
        $code = 'Papua%20New%20Guinea';
    }
    else if($code == 'Saudi Arabia'){
        $code = 'Saudi%20Arabia';
    }
    else if($code == 'Sierra Leone'){
        $code = 'Sierra%20Leone';
    }
    else if($code == 'Solomon Islands'){
        $code = 'Solomon%20Islands';
    }
    else if($code == 'South Africa'){
        $code = 'South%20Africa';
    }
    else if($code == 'South Korea'){
        $code = 'S.%20Korea';
    }
    else if($code == 'South Sudan'){
        $code = 'South%20Sudan';
    }
    else if($code == 'Sri Lanka'){
        $code = 'Sri%20Lanka';
    }
    else if($code == 'The Bahamas'){
        $code = 'Bahamas';
    }

    else if($code == 'Trinidad and Tobago'){
        $code = 'Trinidad%20and%20Tobago';
    }
    
    else if($code == 'Zimbabwe'){
        $code = 'Zimbabwe';
    }
    
  
     $url = "https://coronavirus-19-api.herokuapp.com/countries/{$code}";

    $data = curl($url);
    return   $data = json_decode($data);
    // print_r($data);die;
    // if (isset($data->result)) {
    //     return $data->result;
    // }
}
function getCurrencies($base)
{
 
    $url = "https://api.exchangerate.host/latest?base=$base&symbols=AUD,CAD,CHF,CNY,EUR,GBP,HKD,JPY,USD";
    $ratesResult = curl($url);
    $ratesResult = json_decode($ratesResult);
    $flags = ["AUD" => "svg\Australia.svg", "CAD" => "svg\Canada.svg", "CHF" => "svg\Switzerland.svg", "CNY" => "svg\China.svg", "EUR" => "svg\Europe.svg", "GBP" => "svg\UK.svg", "HKD" => "svg\Hong_Kong.svg", "JPY" => "svg\Japan.svg", "USD" => "svg\USA.svg"];
    if ($ratesResult->success ?? null) {
        $ratesResult->flags = $flags;
        return $ratesResult;
    }
}

function getCountry()
{
    
    
    $output = new stdClass();
    if (($lat = $_GET["lat"] ?? null) && ($lng = $_GET["lng"] ?? null)) {
        $opencage = opencage("$lat+$lng");
    } elseif ($country = $_GET["country"] ?? null) {
        
        $opencage = opencage($country);
    }
    


    $opencage = json_decode($opencage);
    
    $result = $opencage->results[0] ?? null;
    
    if ($countryCode = $result->components->country_code ?? null) {
        $result->components->country_code = strtoupper($countryCode);

        if ($result->components->country_code === "CI") {
            $result->components->country = "Ivory Coast";
        } else if ($result->components->country_code === "XK") {
            $result->components->country = "Kosovo";
        } elseif ($result->components->country === "Somaliland") {
            $result->components->country = "Somalia";
        }

        $country = $country ?? $result->components->country;
        $countryCode = $result->components->country_code;

        if ($borders = getBorders($countryCode) ?? null) {
            
            $output->borders = $borders;

            $output->opencage = $result;

            $rest = json_decode(restCountry($countryCode));
            
            $output->rest = $rest ?? null;

            $wiki = json_decode(Wiki($country));

            
            $output->wiki = $wiki[3][0] ?? null;


         if($result->components->country == 'Zimbabwe')
         {
            $base = 'USD';

         }
         else{

            $base = $result->annotations->currency->iso_code;

         }
            
            $rates = getCurrencies($base);
            
            $output->rates = $rates ?? null;

            $cities = triposo($countryCode, "cities")->results ?? null;
            $output->cities = $cities;

            $mountains = triposo($countryCode, "mountains")->results ?? null;
            $output->mountains = $mountains;

            $POIs = triposo($countryCode, "poi")->results ?? null;
            $output->POIs = $POIs;

            $iso3Code = $borders->properties->iso_a3;

            $covid = getCovid($country);
      

            $public_holidays = getPublicHolidays($countryCode);


           

            $get_weather = r($output->opencage->geometry->lat,$output->opencage->geometry->lng, 'country');

            $wikipedia_details = getwikipedia_detail($country);
            
            
            $output->covid = $covid;
            $output->weather = $get_weather;
            $output->public_holidays = $public_holidays;
            $output->wikipedia_details = $wikipedia_details;
        }
        return json_encode($output);
    }
}

function getCountryList()
{
    $json =  file_get_contents("../json/countryBorders.geo.json");
    $data = json_decode($json)->features;
    $countries = [];

    foreach ($data as $country) {
        $name = $country->properties->name;
        $code = $country->properties->iso_a2;
        array_push($countries, [$name, $code]);
    }
    return json_encode($countries);

    
}

function r($lat,$long, $country)
{
   
    global $weather;
  
    // $url = "https://api.openweathermap.org/data/2.5/weather?q=$location,$country&units=metric&appid=$weather";
     $url = "api.openweathermap.org/data/2.5/weather?lat={$lat}&lon={$long}&appid=9b3ba7e0e483c2b533c578785df35bd6";
    $result = json_decode(curl($url));
    

    if (isset($result->cod) && $result->cod === 200) {
        return $result;
    }
}


function getPublicHolidays($country)
{
    $now = date("Y");
    
        $url = "https://calendarific.com/api/v2/holidays?api_key=e188ef3bcdd04181d7899c302722fcaa2e132dca&country=$country&year=$now";
    $result = json_decode(curl($url));
 return $result;
    
}

function getwikipedia_detail($country)
{
    
    $countryName = str_replace(' ', '', $country);
    $url = "https://en.wikipedia.org/w/api.php?action=query&generator=search&gsrlimit=5&prop=pageimages%7Cextracts&exintro&explaintext&exlimit=max&format=json&gsrsearch={$countryName}";
// $url = "https://en.wikipedia.org/w/api.php?action=query&generator=search&gsrlimit=5&prop=pageimages%7Cextracts&exintro&explaintext&exlimit=max&format=json&gsrsearch=UnitedKingdom";
    $result = json_decode(curl($url));
    
    $pages = (array)$result->query->pages;
    // print_r();
    $extract_data  = array_values($pages);
    // print_r($extract_data);die;
    return $extract_data[0]->extract;
    
    
}


function openCage($search)
{
    //  $opencage = '4721141949f04dd488956b52432cc62f';
    
    global $opencage;
    $search = urlencode($search);
    $url = "https://api.opencagedata.com/geocode/v1/json?q=$search&pretty=1&limit=1&key=$opencage";

    return curl($url);
}

function restCountry($code)
{
    $url = "https://restcountries.com/v2/alpha/$code";
    return curl($url);
}

function triposo($code, $query)
{
    global $triposo;
    global $tripToken;
    $code = ($code === "GB") ? "uk" : strtolower($code);
    switch ($query) {
        case 'cities':
            $api = "location";
            $type = "type=city&";
            break;
        case 'mountains':
            $api = "poi";
            $type = "tag_labels=poitype-Mountain&";
            break;
        case 'poi':
            $api = "poi";
            $type = "";
            break;
    }

 $url = "https://www.triposo.com/api/20210317/$api.json?$type" . "countrycode=$code&fields=attribution,coordinates,images,name,snippet&account=$triposo&token=$tripToken";
   
   
    $data = json_decode(curl($url));
    if (isset($data->results)) {
        foreach ($data->results as $place) {
            $place->images = $place->images[0] ?? null;
            foreach ($place->attribution as $link) {
                if ($link->source_id === "wikipedia") {
                    $place->wiki = $link->url;
                }
            }
            // if ($query === "cities") {
            //     if (!isset($place->wiki)) {
            //         $wikiResult = json_decode(Wiki($place->name));
            //         $place->wiki = $wikiResult[3][0] ?? null;
            //     }
            //     $place->we($place->name, $code) ?? null;
            // }
        }
        return $data;
    }
}

function Wiki($search)
{
    $search = urlencode($search);
    $url = "https://en.wikipedia.org/w/api.php?action=opensearch&search=$search&limit=1";
    return curl($url);
}
