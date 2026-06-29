<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PelangganModels;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

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
            'nama'       => 'required|string|max:100',
            'no_hp'      => 'required|string|max:20',
            'alamat'     => 'required|string|max:500',
            'email'      => 'nullable|email|max:100',
            'keterangan' => 'nullable|string|max:1000',
        ], [
            'nama.required'   => 'Nama lengkap wajib diisi.',
            'no_hp.required'  => 'Nomor handphone wajib diisi.',
            'alamat.required' => 'Alamat lengkap wajib diisi.',
            'email.email'     => 'Format email tidak valid.',
        ]);

        $data = $request->only([
            'nama', 'no_hp', 'alamat', 'email', 'keterangan'
        ]);

        PelangganModels::create($data);

        return redirect()
            ->route('admin.pelanggan.index')
            ->with('success', "Pelanggan \"{$request->nama}\" berhasil ditambahkan.");
    }
}
