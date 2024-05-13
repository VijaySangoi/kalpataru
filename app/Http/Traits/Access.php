<?php 
namespace App\Http\Traits;

use App\Models\Com;
use Illuminate\Support\Facades\DB;

trait Access
{
    public static function access(String $name)
    {
        $qy = DB::table('cip');
        $qy->select('peer');
        $qy->join('sensors','sensors.node','=','cip.id');
        $qy->where('sensors.name','=',$name);
        $var = $qy->first();
        if($var) return $var;
        $qy = DB::table('coms');
        $qy->select('port');
        $qy->where('name',$name);
        $var = $qy->first();
        if($var) return $var;
        return null;
    }
}