<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PelangganModels;
use App\Models\UserModels;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        $pelangganQuery = PelangganModels::query();

        if ($request->filled('tanggal')) {
            $pelangganQuery->whereDate('created_at', $request->tanggal);
        }

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();

            $pelangganQuery->where(function ($query) use ($search) {
                $query->where('nama', 'like', '%' . $search . '%')
                    ->orWhere('no_hp', 'like', '%' . $search . '%')
                    ->orWhere('alamat', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('keterangan', 'like', '%' . $search . '%');
            });
        }

        $pelangganList = $pelangganQuery->latest('id_pelanggan')->paginate(5)->withQueryString();

        return view('admin.pelanggan.index', compact('pelangganList'));
    }

    public function create()
    {
        return view('admin.pelanggan.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'id_user'    => 'nullable|exists:users,id_user',
            'nama'       => 'required|string|max:100|unique:m_pelanggan,nama',
            'no_hp'      => 'required|string|max:20|unique:m_pelanggan,no_hp',
            'alamat'     => 'required|string|max:500',
            'email'      => 'nullable|email|max:100|unique:m_pelanggan,email',
            'keterangan' => 'nullable|string|max:1000',
        ], [
            'id_user.exists'  => 'User tidak ditemukan.',
            'nama.required'   => 'Nama lengkap wajib diisi.',
            'nama.unique'     => 'Nama pelanggan ini sudah terdaftar.',
            'no_hp.required'  => 'Nomor handphone wajib diisi.',
            'no_hp.unique'    => 'Nomor handphone ini sudah digunakan pelanggan lain.',
            'alamat.required' => 'Alamat lengkap wajib diisi.',
            'email.email'     => 'Format email tidak valid.',
            'email.unique'    => 'Email ini sudah digunakan pelanggan lain.',
        ]);

        $data = $request->only([
            'id_user', 'nama', 'no_hp', 'alamat', 'email', 'keterangan'
        ]);

        PelangganModels::create($data);

        return redirect()
            ->route('admin.pelanggan.index')
            ->with('success', "Pelanggan \"{$request->nama}\" berhasil ditambahkan.");
    }

    public function edit($id)
    {
        $pelanggan = PelangganModels::findOrFail($id);
        return view('admin.pelanggan.edit', compact('pelanggan'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $pelanggan = PelangganModels::findOrFail($id);

        $request->validate([
            'id_user'    => 'nullable|exists:users,id_user',
            'nama'       => 'required|string|max:100|unique:m_pelanggan,nama,' . $pelanggan->id_pelanggan . ',id_pelanggan',
            'no_hp'      => 'required|string|max:20|unique:m_pelanggan,no_hp,' . $pelanggan->id_pelanggan . ',id_pelanggan',
            'alamat'     => 'required|string|max:500',
            'email'      => 'nullable|email|max:100|unique:m_pelanggan,email,' . $pelanggan->id_pelanggan . ',id_pelanggan',
            'keterangan' => 'nullable|string|max:1000',
        ], [
            'id_user.exists'  => 'User tidak ditemukan.',
            'nama.required'   => 'Nama lengkap wajib diisi.',
            'nama.unique'     => 'Nama pelanggan ini sudah terdaftar.',
            'no_hp.required'  => 'Nomor handphone wajib diisi.',
            'no_hp.unique'    => 'Nomor handphone ini sudah digunakan pelanggan lain.',
            'alamat.required' => 'Alamat lengkap wajib diisi.',
            'email.email'     => 'Format email tidak valid.',
            'email.unique'    => 'Email ini sudah digunakan pelanggan lain.',
        ]);

        $data = $request->only([
            'id_user', 'nama', 'no_hp', 'alamat', 'email', 'keterangan'
        ]);

        $pelanggan->update($data);

        if ($request->reset_password_toggle && $request->filled('new_password') && $pelanggan->id_user) {
            $user = UserModels::find($pelanggan->id_user);
            if ($user) {
                $user->password = Hash::make($request->new_password);
                $user->save();
            }
        }

        if ($request->has('role') && $pelanggan->id_user) {
            $user = UserModels::find($pelanggan->id_user);
            if ($user) {
                $user->role = $request->role;
                $user->save();
            }
        }

        return redirect()
            ->route('admin.pelanggan.index')
            ->with('success', "Pelanggan \"{$request->nama}\" berhasil diperbarui.");
    }

    public function destroy($id): RedirectResponse
    {
        $pelanggan = PelangganModels::findOrFail($id);

        if ($pelanggan->transaksiGadai()->exists()) {
            return redirect()
                ->route('admin.pelanggan.index')
                ->with('error', "Pelanggan \"{$pelanggan->nama}\" tidak dapat dihapus karena masih memiliki riwayat transaksi gadai.");
        }

        $pelanggan->delete();

        return redirect()
            ->route('admin.pelanggan.index')
            ->with('success', "Pelanggan \"{$pelanggan->nama}\" berhasil dihapus.");
    }
}

