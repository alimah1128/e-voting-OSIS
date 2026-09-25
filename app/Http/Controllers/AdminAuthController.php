<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    // Menampilkan halaman login admin
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    // Memproses login admin
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $admin = DB::table('admins')->where('username', $request->username)->first();

        if ($admin && Hash::check($request->password, $admin->password)) {
            session([
                'admin_id' => $admin->id,
                'admin_username' => $admin->username,
                'admin_nama' => $admin->nama_lengkap,
                'admin_foto' => $admin->foto,
            ]);

            return redirect('/admin/dashboard');
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->onlyInput('username');
    }

    // Logout admin
    public function logout(Request $request)
    {
        session()->forget(['admin_id', 'admin_username', 'admin_nama', 'admin_foto']);
        return redirect('/admin/login');
    }
}