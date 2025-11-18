<?php

namespace App\Repositories;

use App\Repositories\BaseRepositoryInterface;
use App\Helpers\ApiResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;


use App\Models\User;

class UserRepository extends BaseRepositoryInterface
{
    protected $model;
    protected $response;

    public function __construct(User $user, ApiResponse $response)
    {
        $this->model = $user;
        $this->response = $response;
    }

    public function create(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $user = $this->find($id);

        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        return $user ? $user->update($data) : false;
    }

    public function findByEmail($email)
    {
        return $this->model->where('email', $email)->first();
    }

    public function logIn(array $credentials)
    {
        $user = $this->findByEmail($credentials['email']);

        if (! $user) {
            return response()->json(['message' => 'Email not found.'], 404);
        }

        if (! Hash::check($credentials['password'], $user->password)) {
            return response()->json(['message' => 'Incorrect password.'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
            'message' => 'Login successful.'
        ], 200);
    }


    public function logOut($user)
    {
        $user->currentAccessToken()->delete();
        return true;
    }

    public function changePassword($request)
    {
        $user = $request->user();
        if (! Hash::check($request['current_password'], $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['The provided current password is incorrect.'],
            ]);
        }
        if ($request['new_password'] !== $request['confirm_password']) {
            throw ValidationException::withMessages([
                'new_password' => ['The new password and confirmation password do not match.'],
            ]);
        }
        $user->password = bcrypt($request['new_password']);
        $user->save();

        return [
            'success' => true,
            'message' => 'Password changed successfully',
        ];
    }
}