<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Response;


class RestaurantController extends Controller
{

    public function index(Request $request)
    {
        // 
        $restaurant = new \App\Models\Restaurant();

        if($request->input('keyword')) {
            $rows = $restaurant->search($request->input('keyword'));
        } else {
            $rows = $restaurant->all();
        }
        $rows_count = count($rows);

        return [
            'status' => true,
            'total_data' => $rows_count,
            'data' => $rows
        ];
    }

    public function get_image($photo_reference) 
    {
        $restaurant = new \App\Models\Restaurant();
        $data = $restaurant->GetImage($photo_reference);
        if(!$data) {
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
