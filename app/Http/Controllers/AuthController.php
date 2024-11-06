<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'nullable|string|in:user,admin', // Validasi untuk role
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Tentukan role, default ke 'user'
        $role = $request->role ?? 'user';

        // Buat pengguna baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $role,
        ]);

        // Kembalikan respons
        return response()->json([
            'message' => 'User registered successfully!',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ]
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = Auth::user();
        $user->tokens()->delete(); // Hapus token lama (opsional, jika ingin token tunggal)
        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
        ]);
    }

    public function logout(Request $request)
    {
        $user = $request->user('sanctum');
        if ($user) {
            $user->currentAccessToken()->delete();
            return response()->json(['message' => 'Successfully logged out']);
        } else {
            return response()->json(['message' => 'User is not authenticated'], 401);
        }
    }

    // Display Profile
    public function showProfile()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        return response()->json([
            'message' => 'Profile data retrieved successfully',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'gender' => $user->gender,
                'phone' => $user->phone,
                'profile_photo' => $user->profile_photo ? url('storage/' . $user->profile_photo) : null,
                'role' => $user->role,
            ]
        ], 200);
    }


    // Update Profile
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $user->id,
            'gender' => 'sometimes|in:male,female',
            'phone' => 'sometimes|string|max:15',
            'password' => 'sometimes|string|min:8|confirmed',
            'profile_photo' => 'sometimes|image|mimes:jpg,jpeg,png|max:2048', // Image validation
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Update user data if provided
        if ($request->has('name')) {
            $user->name = $request->name;
        }
        if ($request->has('email')) {
            $user->email = $request->email;
        }
        if ($request->has('gender')) {
            $user->gender = $request->gender;
        }
        if ($request->has('phone')) {
            $user->phone = $request->phone;
        }
        if ($request->has('password')) {
            $user->password = bcrypt($request->password);
        }

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($user->profile_photo && \Storage::exists($user->profile_photo)) {
                \Storage::delete($user->profile_photo);
            }

            // Store new profile photo in 'upload/profile' directory under 'public' disk
            $file = $request->file('profile_photo');
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension(); // Or use `$user->id . '.'` to use user ID as filename
            $path = $file->storeAs('upload/profile', $fileName, 'public');

            // Save the new file path in the user's profile
            $user->profile_photo = $path;
        }

        // Save the updated user data
        $user->save();

        // Return the response with updated user information
        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'gender' => $user->gender,
                'phone' => $user->phone,
                'profile_photo' => $user->profile_photo ? url('storage/' . $user->profile_photo) : null,
                'role' => $user->role,
            ]
        ], 200);
    }

}
