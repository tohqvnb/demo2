<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $email = $request->email;
        $password = $request->password;

        if (empty($email) or empty($password)) {
            return response()->json(['status' => 'error', 'message' => 'You must fill all fields']);
        }

        $client = new Client();

        try {
            return $client->post('http://127.0.0.1:8000/v1/oauth/token', [
                "form_params" => [
                    "client_secret" => 'WVdRl5jFc7ewYiaFLtI9DIliZLNfujOH8Oquoiwi',
                    "grant_type" => "password",
                    "client_id" => 2,
                    "username" => $request->email,
                    "password" => $request->password
                ]
            ]);
        } catch (BadResponseException $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function register(Request $request)
    {
        $name = $request->name;
        $email = $request->email;
        $password = $request->password;

        if (empty($name) or empty($email) or empty($password)) {
            return response()->json(['status' => 'error', 'message' => 'You must fill all the fields']);
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return response()->json(['status' => 'error', 'message' => 'You must enter a valid email']);
        }
        if (strlen($password) < 6) {
            return response()->json(['status' => 'error', 'message' => 'Password should be min 6 character']);
        }
        if (User::where('email', '=', $email)->exists()) {
            return response()->json(['status' => 'error', 'message' => 'User already exists with this email']);
        }

        try {
            $user = new User();
            $user->name = $name;
            $user->email = $email;
            $user->password = app('hash')->make($password);

            if ($user->save()) {
                //chỗ này sẽ dk cho user hoặc writer
                $user->assignRole('user');
                return $this->login($request);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function logout(Request $request)
    {
        try {
            auth()->user()->tokens()->each(function ($token) {
                $token->delete();
            });

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
    public function me($id)
    {
        $user = USer::where('id', $id)->get();
        return response()->json($user);
    }

    public function edit(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'filled',
            'email' => 'filled',
        ]);
        $user = User::find($id);
        try {
            if ($user->fill($request->all())->save()) {
                return response()->json(['status' => 'success']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    //Assigning Roles Permission to user

    public function RoleToUser(Request $request, $id)
    {
        $user = User::find($id);
        if ($user->hasRole($request->role)) {
            return response()->json(['status' => 'error', 'message' => 'exit']);
        }
        $user->assignRole($request->role);
        return response()->json(['status' => 'success', 'message' => 'successfully']);
    }
}
