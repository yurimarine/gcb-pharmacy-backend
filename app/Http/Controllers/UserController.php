<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use App\Http\Resources\UserResource;
use App\Models\User;



use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

   public function signUp(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|string|max:50',
            'password' => 'required|string|min:6',
        ]);
        $user = $this->userRepo->create($validated);
        return response()->json([
            'message' => 'User successfully created',
            'user' => $user,

        ], 201);
    }

    public function logIn(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        return $this->userRepo->logIn($credentials);
    }

    public function logOut(Request $request)
    {
        $user = $request->user();
        $logout = $this->userRepo->logOut($user);
        return $this->response->success($logout, "Successfully logged out", 200);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string|min:6',
            'new_password' => 'required|string|min:6',
            'confirm_password' => 'required|string|min:6'
        ]);
        $result = $this->userRepo->changePassword($request);
        return response()->json(['message' => $result['message']]);
    }


}
