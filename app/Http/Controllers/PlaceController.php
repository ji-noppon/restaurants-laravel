<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class PlaceController extends Controller
{
    public function searchPlace(Request $request)
    {   
        $location = $request->query('location');
        $radius   = $request->query('radius')  ;
        if(is_null($location)){
            return 'pls send location'; // return when user not send location
        }
        if(is_null($radius)){
            $radius = 500;      // set default radius if user not select
        }
        
        $params = [
            'query' => [
                'fields'    => 'geometry',
                'input'     => $location,
                'inputtype' => 'textquery',
                'key'       => env('API_MAP_KEY')
            ]
         ];
         
        $client = new Client(['base_uri' => 'https://maps.googleapis.com/maps/api/place/findplacefromtext/']);
        $response = $client->request('GET','json',$params);
        $body = json_decode($response->getBody(),true); // true = json -> Array  || not true = json -> obj

        $lat = $body['candidates'][0]['geometry']['location']['lat'] ;
        $lng = $body['candidates'][0]['geometry']['location']['lng'] ; 
        $position = $lat .','.$lng; // $lat + , + $lng

        return $this->nearbySearch($position,$radius); // send $position to function nearbySearch
    }

    function nearbySearch ($position,$radius){
        $params = [
            'query' => [
                'keyword'   => 'restaurant',
                'location'  => $position,
                'radius'    => $radius,
                'type'      => 'restaurant',
                'key'       => env('API_MAP_KEY')
            ]
         ];
         
        $client = new Client(['base_uri' => 'https://maps.googleapis.com/maps/api/place/nearbysearch/']);
        $response = $client->request('GET','json',$params);
        $body = json_decode($response->getBody(),true); // true = json -> Array  || not true = json -> obj
        $name = $body['results'];
        //return count($name);
        return $body;
    }

    function photo(){
        $params = [
            'query' => [
                'maxwidth'          => '200',
                'photo_reference'   => 'Aap_uEAruqdfTmx2OrNTEfzm7jrhvlqq1KZQnfSSxEHJZtImFriZd6LTtYxY7xOtdGKYe1-MpkvHG436Q0Gk1v2vtWRf-luW7Cp84J7ASZD2iozOQSFp2pOMhJAuPH4sJanwD8qFtTlMPG-6DpT1Fr9foUOlwo-wilhnMPN85AhiEg97ibrN',
                'key'               => env('API_MAP_KEY')
            ]
         ];
         
        $client = new Client(['base_uri' => 'https://maps.googleapis.com/maps/api/place/']);
        $response = $client->request('GET','photo',$params);

        return $response;
    }
}
