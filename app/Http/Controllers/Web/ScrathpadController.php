<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class ScrathpadController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('web.scrathpad');
    }
    public function api()
    {
        $data = file(app_path('Jobs')."/samplejob.php");
        return $data;
    }
}
