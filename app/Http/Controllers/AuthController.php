<?php
 
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
 
class AuthController extends Controller
{
    public function login()
    {
        if (Auth::check()) { // jika sudah login, maka redirect ke halaman home
            return redirect('/');
        }
        return view('auth.login');
    }
    public function postlogin(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $validatedData = $request->validate([
                'username' => 'required|string',
                'password' => 'required|string',
            ]);
             
            $credentials = $request->only('username', 'password');
            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                return response()->json([
                    'status' => true,
                    'message' => 'Login Berhasil',
                    'redirect' => url('/')
                ]);
            }
            return response()->json([
                'status' => false,
                'message' => 'Login Gagal'
            ]);
        }
        return redirect('login');
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('login');
    }
    public function register()
     {
         return view('auth.register');
     }
 
     public function postregister(Request $request)
     {
         $validator = Validator::make($request->all(), [
             'username' => 'required|string|min:3|unique:m_user,username',
             'nama' => 'required|string|max:100',
             'password' => 'required|min:5',
         ]);
 
         if ($validator->fails()) {
             if ($request->ajax() || $request->wantsJson()) {
                 return response()->json([
                     'status' => false,
                     'message' => 'Validation Error',
                     'errors' => $validator->errors()
                 ]);
             }
             return redirect('register')
                 ->withErrors($validator)
                 ->withInput();
         }
 
         UserModel::create([
             'level_id' => 3, // default user register is staff/kasir
             'username' => $request->username,
             'nama' => $request->nama,
             'password' => Hash::make($request->password)
         ]);
 
         if ($request->ajax() || $request->wantsJson()) {
             return response()->json([
                 'status' => true,
                 'message' => 'Registration Successful',
                 'redirect' => url('/')
             ]);
         }
 
         return redirect('/');
     }

     public function profil($id){
        $breadcrumb = (object) [
            'title' => 'Profil',
            'list'  => ['Home', 'Profil']
        ];

        $activeMenu = 'profile'; // set menu yang sedang aktif
        $user = UserModel::findOrFail($id);
        return view('auth.profile', [
            'breadcrumb' => $breadcrumb,
            'user'=> $user,
            'activeMenu' => $activeMenu
        ]);
     }
     
    public function upload_profile($id){

        $validator = Validator::make(request()->all(), [
            'profile' => 'required|image', // For base64 encoded images
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ]);
        }

        $user = UserModel::find($id);
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ]);
        }
        $image = request()->file('profile');
        $base64Image = 'data:image/' . $image->getClientOriginalExtension() . ';base64,' . base64_encode(file_get_contents($image->path()));
        
        // Save the base64 string directly to database
        $user->profile = $base64Image;
        $user->save();

        return redirect("/profile/{$id}")->with('success', 'Profile photo updated successfully');
    }
 }