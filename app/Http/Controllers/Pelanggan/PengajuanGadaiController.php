<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\BarangModels;
use App\Models\JenisBarangModels;
use App\Models\PelangganModels;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Cloudinary\Cloudinary;

class PengajuanGadaiController extends Controller
{
    public function create()
    {
        $jenisBarangList = JenisBarangModels::query()->orderBy('nama_jenis')->get();
        return view('pelanggan.pengajuan.create', compact('jenisBarangList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_jenis_barang' => 'required|exists:m_jenis_barang,id_jenis_barang',
            'nama_barang'     => 'required|string|max:255',
            'harga_beli'      => 'required|numeric|min:0',
            'kondisi'         => 'required|string|max:100',
            'berat'           => 'required|numeric|min:0',
            'foto_barang'     => 'required|image|mimes:jpeg,png,webp|max:2048',
            'keterangan'      => 'nullable|string|max:1000',
        ], [
            'id_jenis_barang.required' => 'Jenis barang wajib dipilih.',
            'nama_barang.required'     => 'Nama barang wajib diisi.',
            'harga_beli.required'      => 'Perkiraan harga beli wajib diisi.',
            'harga_beli.numeric'       => 'Harga harus berupa angka.',
            'kondisi.required'         => 'Kondisi barang wajib dipilih.',
            'foto_barang.required'     => 'Foto barang wajib diunggah.',
            'foto_barang.image'        => 'File harus berupa gambar.',
            'foto_barang.max'          => 'Ukuran foto maksimal 2MB.',
        ]);

        $pelanggan = PelangganModels::where('id_user', Auth::id())->first();

        if (!$pelanggan) {
            return back()->with('error', 'Profil pelanggan Anda tidak ditemukan. Harap lengkapi profil terlebih dahulu.');
        }

        $data = $request->only([
            'id_jenis_barang', 'nama_barang', 'harga_beli',
            'kondisi', 'berat', 'keterangan'
        ]);

        $data['id_pelanggan'] = $pelanggan->id_pelanggan;
        $data['status_verifikasi'] = 'pending';

        if ($request->hasFile('foto_barang')) {
            $cloudinaryUrl = env('CLOUDINARY_URL');
            if ($cloudinaryUrl) {
                $cloudinary = new Cloudinary($cloudinaryUrl);
                $upload = $cloudinary->uploadApi()->upload($request->file('foto_barang')->getRealPath(), [
                    'folder' => 'gerlian-jaya/barang'
                ]);
                $data['foto_barang'] = $upload['secure_url'];
            } else {
                $data['foto_barang'] = $request->file('foto_barang')->store('barang', 'public');
            }
        }

        BarangModels::create($data);

        return redirect()
            ->route('pelanggan.dashboard')
            ->with('success', "Pengajuan gadai untuk \"{$request->nama_barang}\" berhasil dikirim dan sedang menunggu persetujuan admin.");
    }
}
