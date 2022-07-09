<?php

namespace App\Models;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class Restaurant
{
    // set type of cache storage
    protected $cache_storage = 'file';

    public function Search($keyword = 'Bang Sue')
    {
        // Prepare Variable before convert to Query String
        // Ref: https://developers.google.com/maps/documentation/places/web-service/search-text#required-parameters
        $query_arr = [];
        $query_arr['query'] = $keyword;
        $query_arr['language'] = 'TH';
        // $query_arr['region'] = 'TH';
        $query_arr['type'] = 'restaurant';
        $query_arr['business_status'] = 'OPERATIONAL';
        $query_arr['key'] = env("GOOGLE_MAP_API_KEY", null);

        if(!$query_arr['key']) {
            // if not set API_KEY in environment (.env), function always return: false
            return false;
        }

        // build Array variable to Query String
        $query_str = http_build_query($query_arr);

        // create cache name for caching result of this keyword
        $cache_filename = md5($query_str);

        // if cache existed and not expired, return cache result.
        if(Cache::store($this->cache_storage)->has($cache_filename)) {
            return Cache::store($this->cache_storage)->get($cache_filename);
        }

        // if not, function will call API directly
        $response = $this->CallAPI($query_str);

        // get result and save to tmp array first-time
        $full_response = array_merge([], $response->json('results'));

        // if have more than 20 results, get next_page_token to continue get data
        $next_page_token = ($response->json('next_page_token')) ? $response->json('next_page_token') : null; 

        // and loop get data while next_page_token is null
        while ( ($next_page_token) ) {

            // call API with next_page_token and API_KEY
            $response = $this->CallAPI('pagetoken='.$next_page_token.'&key='.$query_arr['key']);

            // get result and merge to tmp array before that.
            $full_response = array_merge($full_response, $response->json('results'));

            // if have next_page_token, get it and loop until next_page_token is null
            $next_page_token = ($response->json('next_page_token')) ? $response->json('next_page_token') : null; 

            // random time sleep, for prevent blocking from Google
            sleep(rand(0.5, 1.2));
        }
        
        // save tmp array to cache, default time is 10 minute (600 sec)
        Cache::store($this->cache_storage)->put($cache_filename, $full_response, 600);

        // return tmp array
        return $full_response;
    }

    public function GetImage($photo_reference)
    {
        // Prepare Variable before convert to Query String
        // Ref: https://developers.google.com/maps/documentation/places/web-service/photos#required-parameters
        $query_arr = [];
        $query_arr['photo_reference'] = $photo_reference;
        $query_arr['maxwidth'] = '400';
        $query_arr['key'] = env("GOOGLE_MAP_API_KEY", null);

        if(!$query_arr['key']) {
            // if not set API_KEY in environment (.env), function always return: false
            return false;
        }
        
        // build Array variable to Query String
        $query_str = http_build_query($query_arr);

        // create cache name for caching result of this keyword
        $cache_filename = md5($query_str);

        // if cache existed and not expired, return cache result.
        if(Cache::store($this->cache_storage)->has($cache_filename)) {
            return Cache::store($this->cache_storage)->get($cache_filename);
        }

        // if not, function will call API directly
        $response = Http::get('https://maps.googleapis.com/maps/api/place/photo?'.$query_str);
        if(!$response->ok()) {
            return false;
        }

        // get image body and convert to base64
        $base64_image = base64_encode($response->body());

        // read type of mime
        $mime = $response->getHeader('Content-Type')[0];
        
        // save base64 data to cache, default time is 10 minute (600 sec)
        Cache::store($this->cache_storage)->put($cache_filename, [
            'data' => $base64_image,
            'mime' => $mime
        ], 600);

        return [
            'data' => $img,
            'mime' => $mime
        ];
    }

    private function CallAPI($query_str)
    {
        $response = Http::get('https://maps.googleapis.com/maps/api/place/textsearch/json?'.$query_str);
        if(!$response->ok()) {
            return false;
        }

        return $response;
    }
}
