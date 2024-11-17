<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response( )->json([
            'user' => $user,
            'message' => 'User Registered successfully' 
        ],201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first( );

        if(!$user || !Hash::check($request->password, $user->password)){
            return response()->json(['message'=> 'Invalid credentials'], 401);
        }

        $token = $user->createToken('Personal Access Token')->plainTextToken;

        return response()->json([
            'detail' => $user,
            'token' => $token
        ]);
    }

    public function logout(Request $request){
        if(Auth::check()){
            $request->user()->CurrentAccessToken()->delete();
            return response()->json(['message' =>  'Logged out successfully']);
        }
        return response()->json(['message' => 'Not logged in'], 401);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8',
        ]);

        $user = User::find($id);

        if (!$user || $user->id !== Auth::id()) {
            return response()->json(['message' => 'User tidak ditemukan atau Anda tidak memiliki akses'], 403);
        }

        $user->name = $request->input('name', $user->name);
        $user->email = $request->input('email', $user->email);

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json([
            'message' => 'User berhasil diperbarui',
            'user' => $user,
        ], 200);
    }


    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user || $user->id !== Auth::id()) {
            return response()->json(['message' => 'User tidak ditemukan atau Anda tidak memiliki akses'], 403);
        }
        
        $user->delete();

        return response()->json(['message' => 'User berhasil dihapus'], 200);
    }


}