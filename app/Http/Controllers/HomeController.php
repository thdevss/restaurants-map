<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    public function index()
    {
        // return app.blade for boot Vuejs
        return view('app');
    }
}
