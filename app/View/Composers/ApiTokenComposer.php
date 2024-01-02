<?php
 
namespace App\View\Composers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Laravel\Sanctum\PersonalAccessToken as Pat;

class ApiTokenComposer
{
    private $request;
    /**
     * Create a new profile composer.
     */
    public function __construct(Request $request) 
    {
        $this->request = $request;
    }
 
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $apiToken = Session::get('api-Token');
        if($apiToken)
        {
            $expry = config('sanctum.expiration') - 1;
            $id = explode("|",$apiToken)[0];
            $token = Pat::select('created_at')->where('id',$id)->first();
            $time = strtotime($token['created_at']) + ($expry * 60);
            if(strtotime("now") > $time)
            {
                $user = User::where("id",Auth::user()->id)->first();
                $apiToken = $user->createToken("api")->plainTextToken;
                Session::put('api-Token',$apiToken);
                $token->delete();
            }
        }
        if(!$apiToken)
        {
            $user = User::where("id",Auth::user()->id)->first();
            $apiToken = $user->createToken("api")->plainTextToken;
            Session::put('api-Token',$apiToken);
        }
        $view->with('apiToken', $apiToken);
    }
}