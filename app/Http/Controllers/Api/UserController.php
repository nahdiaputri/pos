<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserModel;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(UserModel::all());
    }
    public function store(Request $request): JsonResponse
    {
    $validated = $request->validate([
        'username' => 'required|string|max:255|unique:m_user,username',
        'password' => 'required|string|min:6',
        'nama' => 'required|string|max:255',
        'level_id' => 'required|exists:m_level,level_id',
    ]);

    $user = UserModel::create($validated);
    return response()->json($user, 201);
}
    public function show(UserModel $user): JsonResponse
    {
        return response()->json($user);
    }

    public function update(Request $request, UserModel $user): JsonResponse
    {
    $validated = $request->validate([
        'username' => 'sometimes|required|string|max:255|unique:m_user,username,' . $user->user_id . ',user_id',
        'password' => 'nullable|string|min:6',
        'nama' => 'sometimes|required|string|max:255',
        'level_id' => 'sometimes|required|exists:m_level,level_id',
    ]);

    if (isset($validated['password'])) {
        // Password baru akan otomatis di-hash karena casts di model
        $user->update($validated);
    } else {
        // Jika password tidak dikirim, update tanpa password
        $user->update(array_diff_key($validated, ['password' => '']));
    }

    return response()->json($user, 200);
    }

    public function destroy(UserModel $user): JsonResponse
    {
        $user->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data deleted successfully',
        ]);
    }
}
