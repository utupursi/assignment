<?php

namespace App\Repositories\Eloquent;

use App\Http\Request\LoginRequest;
use App\Http\Request\RegisterRequest;
use App\Models\User;
use App\Repositories\AuthRepositoryInterface;
use App\Repositories\Eloquent\Base\BaseRepository;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthRepository extends BaseRepository implements AuthRepositoryInterface
{

    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    /**
     * Authenticate login user.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = [
            'username' => $request->post('email'),
            'password' => $request->post('password'),
        ];
        $user = User::where('email', $credentials['username'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'success' => 'false',
                'message' => 'Bad credentials',
            ]);
        }

        $token = $user->createToken('token')->plainTextToken;

        return response()->json([
            'success' => 'true',
            'message' => 'You are successfully log in',
            'token' => $token
        ]);
    }

    /**
     * Register user
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request)
    {
        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => bcrypt($request['password']),
            'balance' => $request['balance']
        ]);

        $token = $user->createToken('token')->plainTextToken;
        return response()->json([
            'success' => 'true',
            'message' => 'User was created',
            'data' => $user,
            'token' => $token
        ], 201);

    }

}
