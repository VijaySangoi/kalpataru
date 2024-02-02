<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Sheet;

class HomeController extends Controller
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
        $qy = Sheet::select('*');
        $rec2 = $qy->get();
        return view('web.dashboard')->with('rec2', $rec2);
    }
    public static function dashboard_component()
    {
        $name = $_POST['name'];
        $qy = DB::table('sensors');
        $qy->join('sheets', 'sensors.sheet', '=', 'sheets.id');
        $qy->select('sensors.*', 'sheets.name as sname');
        $qy->where('sheets.name', '=', $name);
        $rec = $qy->get();
        return response()->json($rec, 200);
    }
    public static function add_sheet($req)
    {
        $qy = new Sheet();
        $qy->name = $_POST['sheet'];
        $qy->save();
        return response()->json($_POST['sheet'], 200);
    }
}
