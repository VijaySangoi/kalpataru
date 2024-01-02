<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct()
    {
    }
    /**
     * @OA\POST(
     *     path="/api/login",
     *     summary="generate a token",
     *     description="generates a token for valid credential",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="json body",
     *        @OA\JsonContent(
     *          required={"username","password"},
     *          type="object",
     *            @OA\Property(property="username", type="string", example="admin", description="username"),
     *            @OA\Property(property="password", type="string", example="password", description="password"),
     *        ),
     *     ),
     *    @OA\Response(response=200,description="OK")
     * )
     */
    public function login(Request $req)
    {
        $ip = $req->ip();
        // if ($ip !== config('apex.allowed_ip')) return response()->json('only ' . config('apex.allowed_ip') . ' allowed', 500);
        $validated = Validator::make($req->all(), [
            'username' => 'required|max:12',
            'password' => 'required|max:15'
        ]);
        if ($validated->fails()) return response()->json($validated->errors(), 500);
        $user = User::where('name', $req->username)->first();
        if (!$user || !Hash::check($req->password, $user->password)) {
            return response()->json("user not found", 500);
        }
        return $user->createToken("api")->plainTextToken;
    }
    /**
     * @OA\POST(
     *     path="/api/register",
     *     summary="create a user",
     *     description="create a user",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="json body",
     *        @OA\JsonContent(
     *          required={"username","email","password","confirm_password"},
     *          type="object",
     *            @OA\Property(property="username", type="string", example="admin", description="username"),
     *            @OA\Property(property="email", type="email", example="sample123@gmail.com", description="email"),
     *            @OA\Property(property="password", type="string", example="password", description="password"),
     *            @OA\Property(property="confirm_password", type="string", example="password", description="same as password"),
     *        ),
     *     ),
     *    @OA\Response(response=200,description="OK")
     * )
     */
    public function register(Request $req)
    {
        $ip = $req->ip();
        // if ($ip !== config('apex.allowed_ip')) return response()->json('only ' . config('apex.allowed_ip') . ' allowed', 500);
        $validated = Validator::make($req->all(), [
            'username' => 'required|max:12|unique:App\Models\User,username',
            'email' => 'required|email:rfc,dns|unique:App\Models\User,email',
            'password' => 'required|max:15',
            'confirm_password' => 'required|same:password|max:15',
        ]);
        if ($validated->fails()) return response()->json($validated->errors(), 500);
        $user = new User();
        $user->name = "user_" . Str::random(6);
        $user->username = $req->username;
        $user->email = $req->email;
        $user->password = Hash::make($req->password);
        $user->save();
        return $user->createToken("api")->plainTextToken;
    }
}
