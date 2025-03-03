<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
{
    $jumlah_pengguna = UserModel::where('level_id', 2)->count();
    return view('user', compact('jumlah_pengguna'));
}
}