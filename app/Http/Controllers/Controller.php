<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     title="Kalpataru Api 1.0.0",
 *     version="0.1"
 * )
 * @OA\SecurityScheme(
 *      securityScheme="Authorization",
 *      in="header",
 *      name="Authorization",
 *      type="http",
 *      scheme="Bearer",
 *      bearerFormat="JWT",
 * ),
 */

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
