<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(UserCreateRequest $request)
    {
        $data = $request->validated();
        if (User::where('email', $data['email'])->exists()) {
            return response()->json([
                'message' => 'Email ini sudah terdaftar'
            ], 400);
        }
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        return response()->json([
            'message' => 'Akun berhasil dibuat',
            'data' => new UserResource($user)
        ]);
    }
    public function login(UserLoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                'message' => 'User tidak ditemukan'
            ], 400);
        }
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Password Anda Salah'
            ], 400);
        }
        $token = $user->createToken('login_token')->plainTextToken;
        return response()->json([
            'message' => 'Login berhasil',
            'token' => $token,
            'data' => new UserResource($user)
        ]);
    }
    public function logout()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'message' => 'User belum login'
            ], 404);
        }
        $user->currentAccessToken()->delete();

        return response()->json([
            'message' => 'User berhasil dihapus'
        ]);
    }

    public function update(UserUpdateRequest $request)
    {
       // Get the currently authenticated user
    $user = Auth::user(); 
    
    // Get the validated data from the request
    $data = $request->validated();

    // Check if there's an uploaded image
    if ($request->hasFile('image')) {
        // Store the image in a specific directory based on user ID, and get the relative path
        $imagePath = $request->file('image')->store("images/profile/$user->id", 'public');
        // Only store the relative path in the database
        $data['image_url'] = $imagePath;  // Save only the relative path
    }

    // Find the user by email
    $upd = User::where('email', $user->email)->first();

    // If user not found, return a 404 response
    if (!$upd) {
        return response()->json(['message' => 'User not found'], 404);
    }

    // Update the user's data
    $upd->fullname = $data['fullname'];
    $upd->username = $data['username'];
    $upd->email = $data['email'];

    // Update password if it's provided
    if (isset($data['password'])) {
        $upd->password = bcrypt($data['password']);  // Make sure password is hashed
    }

    // Update the image URL (relative path) if it's provided
    if (isset($data['image_url'])) {
        $upd->image_url = $data['image_url'];
    }

    // Save the updated user record
    $upd->save();

    // Return success response
    return response()->json([
        'message' => 'User berhasil di update',
        'data' => new UserResource($upd)  // Assuming you're using a resource for the user response
    ]);
}
}