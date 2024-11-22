<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\MyUtil;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return MyUtil::sendError('Validation Error.', $validator->errors());
        } else {
            if (User::where('email', '=', $request->email)->first() === null) {
                $input = $request->all();
                $input['password'] = bcrypt($input['password']);
                $user = User::create($input);
                $success['token'] = $user->createToken('MyApp')->plainTextToken;
                $success['name'] = $user->name;
                return MyUtil::sendResponse($success, 'User register successfully.');
            } else {
                return MyUtil::sendError('User already exists.', "user duplicate");
            }
        }
    }

    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $token = $user->createToken('MyApp')->plainTextToken;
            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return MyUtil::sendResponse("LOGOUT", 'User logout successfully.');
    }

}
?>
