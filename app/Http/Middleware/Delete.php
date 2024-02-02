<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Delete
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethod('delete'))
        {
            $tmp = explode("Content-Disposition: form-data; ",$request->getContent());
            $sd = [];
            foreach($tmp as $ky => $val)
            {
                $xc = str_replace($tmp[0],"",$val)."\n";
                $xc = str_replace(str_replace("\r\n","",$tmp[0])."--","",$xc)."\n";
                $xc = explode("\r\n",$xc);
                if($ky > 0)
                {
                    $tx = str_replace("\"","",$xc[0]);
                    $tx = str_replace("name=","",$tx);
                    $_POST[$tx] = $xc[2];
                }
            }
            // var_dump($_DELETE);
        }
        return $next($request);
    }
}
