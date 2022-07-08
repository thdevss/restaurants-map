<?php

namespace App\Models;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class Restaurant
{
    protected $cache_storage = 'file';

    public function All()
    {
        // wrapped for default keyword: Bang Sue
        return $this->Search('Bang Sue');
    }

    public function Search($keyword)
    {
        $query_arr = [];
        $query_arr['query'] = $keyword;
        $query_arr['language'] = 'TH';
        $query_arr['type'] = 'restaurant';
        $query_arr['business_status'] = 'OPERATIONAL';
        $query_arr['key'] = env("GOOGLE_MAP_API_KEY", null);

        if(!$query_arr['key']) {
            return false;
        }

        $query_str = http_build_query($query_arr);
        $cache_filename = md5($query_str);

        if(Cache::store($this->cache_storage)->has($cache_filename)) {
            return Cache::store($this->cache_storage)->get($cache_filename);
        }

        
        $response = $this->CallAPI($query_str);
        $full_response = array_merge([], $response->json('results'));

        $next_page_token = ($response->json('next_page_token')) ? $response->json('next_page_token') : null; 
        while ( ($next_page_token) ) {

            // if has many page; next_page_token is not null, and you can call it (with api key) to get another results.

            $response = $this->CallAPI('pagetoken='.$next_page_token.'&key='.$query_arr['key']);
            $full_response = array_merge($full_response, $response->json('results'));
            $next_page_token = ($response->json('next_page_token')) ? $response->json('next_page_token') : null; 
            sleep(rand(0.5, 1.2));
        }

        Cache::store($this->cache_storage)->put($cache_filename, $full_response, 600);
        return $full_response;
    }

    public function GetImage($photo_reference)
    {
        $query_arr = [];
        $query_arr['photo_reference'] = $photo_reference;
        $query_arr['maxwidth'] = '400';
        $query_arr['key'] = env("GOOGLE_MAP_API_KEY", null);

        if(!$query_arr['key']) {
            return false;
        }

        $query_str = http_build_query($query_arr);
        $cache_filename = md5($query_str);

        if(Cache::store($this->cache_storage)->has($cache_filename)) {
            return Cache::store($this->cache_storage)->get($cache_filename);
        }

        $response = Http::get('https://maps.googleapis.com/maps/api/place/photo?'.$query_str);
        // echo 'https://maps.googleapis.com/maps/api/place/photo?'.$query_str;
        // dd($response);
        if(!$response->ok()) {
            return false;
        }

        $base64 = base64_encode($response->body());
        $mime = $response->getHeader('Content-Type')[0];
        $img = ($base64);
        

        Cache::store($this->cache_storage)->put($cache_filename, [
            'data' => $img,
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
