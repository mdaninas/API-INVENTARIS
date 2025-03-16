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
use Illuminate\Support\Facades\Storage;

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
    $user = Auth::user(); 
    $data = $request->validated();
    if ($request->hasFile('image')) {
        $imageName = time() . '_' . $user->id . '.' . $request->file('image')->extension();
        $imagePath = $request->file('image')->storeAs("images/profile/$user->id",$imageName ,'public');
        $data['image_url'] = $imagePath;  
    }
    $upd = User::where('email', $user->email)->first();
    if (!$upd) {
        return response()->json(['message' => 'User not found'], 404);
    }
    if ($upd->image_url && Storage::disk('public')->exists($upd->image_url)) {
        Storage::disk('public')->delete($upd->image_url);
    }
    $upd->fullname = $data['fullname'];
    $upd->username = $data['username'];
    $upd->email = $data['email'];
    if (isset($data['password'])) {
        $upd->password = bcrypt($data['password']); 
    }
    if (isset($data['image_url'])) {
        $upd->image_url = $data['image_url'];
    }
    $upd->save();
    return response()->json([
        'message' => 'User berhasil di update',
        'data' => new UserResource($upd)  
    ]);
}
}