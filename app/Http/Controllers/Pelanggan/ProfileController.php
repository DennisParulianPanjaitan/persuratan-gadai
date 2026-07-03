<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\UserModels;
use App\Models\PelangganModels;
use Cloudinary\Cloudinary;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $pelanggan = PelangganModels::where('id_user', $user->id_user)->first();
        
        return view('pelanggan.profile.index', compact('user', 'pelanggan'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'nama' => 'required|string|max:100',
            'username' => 'required|string|max:100|unique:users,username,' . $user->id_user . ',id_user',
            'password' => 'nullable|min:6',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'no_hp' => 'required|string|max:15',
            'email' => 'nullable|email|max:100',
            'alamat' => 'required|string|max:255',
        ]);

        $userModel = UserModels::find($user->id_user);
        $userModel->nama = $request->nama;
        $userModel->username = $request->username;

        if ($request->filled('password')) {
            $userModel->password = Hash::make($request->password);
        }

        if ($request->hasFile('foto_profil')) {
            $cloudinaryUrl = env('CLOUDINARY_URL');
            
            if ($cloudinaryUrl) {
                // Delete old photo if exists in Cloudinary
                if ($userModel->foto_profil && preg_match('/upload\/(?:v\d+\/)?([^\.]+)/', $userModel->foto_profil, $matches)) {
                    try {
                        $cloudinary = new Cloudinary($cloudinaryUrl);
                        $cloudinary->uploadApi()->destroy($matches[1]);
                    } catch (\Exception $e) {
                        // ignore error
                    }
                }
                
                $cloudinary = new Cloudinary($cloudinaryUrl);
                $upload = $cloudinary->uploadApi()->upload($request->file('foto_profil')->getRealPath(), [
                    'folder' => 'gerlian-jaya/profil'
                ]);
                $userModel->foto_profil = $upload['secure_url'];
            } else {
                // Fallback local storage
                if ($userModel->foto_profil && Storage::disk('public')->exists($userModel->foto_profil)) {
                    Storage::disk('public')->delete($userModel->foto_profil);
                }
                $file = $request->file('foto_profil');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('profile_photos', $filename, 'public');
                $userModel->foto_profil = $path;
            }
        }

        $userModel->save();

        // Update Pelanggan data
        $pelanggan = PelangganModels::where('id_user', $user->id_user)->first();
        if ($pelanggan) {
            $pelanggan->nama = $request->nama;
            $pelanggan->no_hp = $request->no_hp;
            $pelanggan->email = $request->email;
            $pelanggan->alamat = $request->alamat;
            $pelanggan->save();
        }

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }
}
