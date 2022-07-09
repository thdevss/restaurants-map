<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Response;


class RestaurantController extends Controller
{

    public function index(Request $request)
    {
        // call from model Restaurant
        $restaurant = new \App\Models\Restaurant();

        if($request->input('keyword')) {
            // if have input: keyword, send keyword to function
            $rows = $restaurant->Search($request->input('keyword'));
        } else {
            // if not, default keyword in method: Search = Bang Sue
            $rows = $restaurant->Search();
        }

        // count total rows
        $rows_count = count($rows);

        return [
            'status' => true,
            'total_data' => $rows_count,
            'data' => $rows
        ];
    }

    public function get_image($photo_reference) 
    {
        // call from model Restaurant
        $restaurant = new \App\Models\Restaurant();

        // get image data from photo_reference
        $data = $restaurant->GetImage($photo_reference);

        if(!$data) {
            // if data is null or can't get, return false
            return [
                'status' => false
            ];
        }
        
        return [
            'status' => true,
            'mime' => $data['mime'],
            'data' => $data['data']
        ];

    }


}
