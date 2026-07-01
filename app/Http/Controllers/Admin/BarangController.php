<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BarangModels;
use App\Models\JenisBarangModels;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Cloudinary\Cloudinary;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $barangQuery = BarangModels::query()->with('jenisBarang');

        if ($request->filled('tanggal')) {
            $barangQuery->whereDate('created_at', $request->tanggal);
        }

        if ($request->filled('jenis_barang')) {
            $barangQuery->where('id_jenis_barang', $request->jenis_barang);
        }

        if ($request->filled('status')) {
            $barangQuery->where('status_verifikasi', strtolower($request->status));
        }

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();

            $barangQuery->where(function ($query) use ($search) {
                $query->where('nama_barang', 'like', '%' . $search . '%')
                    ->orWhere('kondisi', 'like', '%' . $search . '%')
                    ->orWhere('keterangan', 'like', '%' . $search . '%');
            });
        }

        $barangList = $barangQuery->latest('id_barang')->paginate(5)->withQueryString();
        $jenisBarangList = JenisBarangModels::query()->orderBy('nama_jenis')->get();

        return view('admin.barang.index', compact('barangList', 'jenisBarangList'));
    }

    public function create()
    {
        $jenisBarangList = JenisBarangModels::query()->orderBy('nama_jenis')->get();

        return view('admin.barang.create', compact('jenisBarangList'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'id_jenis_barang' => 'required|exists:m_jenis_barang,id_jenis_barang',
            'nama_barang'     => 'required|string|max:255',
            'harga_beli'      => 'required|numeric|min:0',
            'kondisi'         => 'required|string|max:100',
            'berat'           => 'nullable|numeric|min:0',
            'foto_barang'     => 'nullable|image|mimes:jpeg,png,webp|max:2048',
            'keterangan'      => 'nullable|string|max:1000',
        ], [
            'id_jenis_barang.required' => 'Jenis barang wajib dipilih.',
            'nama_barang.required'     => 'Nama barang wajib diisi.',
            'harga_beli.required'      => 'Harga beli wajib diisi.',
            'harga_beli.numeric'       => 'Harga beli harus berupa angka.',
            'kondisi.required'         => 'Kondisi barang wajib dipilih.',
            'foto_barang.image'        => 'File harus berupa gambar.',
            'foto_barang.max'          => 'Ukuran foto maksimal 2MB.',
        ]);

        $data = $request->only([
            'id_jenis_barang', 'nama_barang', 'harga_beli',
            'kondisi', 'berat', 'keterangan',
        ]);

        // Upload foto jika ada
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

        // Status default: pending
        $data['status_verifikasi'] = 'pending';

        BarangModels::create($data);

        return redirect()
            ->route('admin.barang.index')
            ->with('success', "Barang \"{$request->nama_barang}\" berhasil ditambahkan.");
    }

    public function show(BarangModels $barang)
    {
        $barang->load('jenisBarang');

        return view('admin.barang.show', compact('barang'));
    }

    public function edit(BarangModels $barang)
    {
        $jenisBarangList = JenisBarangModels::query()->orderBy('nama_jenis')->get();
        return view('admin.barang.edit', compact('barang', 'jenisBarangList'));
    }

    public function update(Request $request, BarangModels $barang): RedirectResponse
    {
        $request->validate([
            'id_jenis_barang' => 'required|exists:m_jenis_barang,id_jenis_barang',
            'nama_barang'     => 'required|string|max:255',
            'harga_beli'      => 'required|numeric|min:0',
            'kondisi'         => 'required|string|max:100',
            'berat'           => 'nullable|numeric|min:0',
            'foto_barang'     => 'nullable|image|mimes:jpeg,png,webp|max:2048',
            'keterangan'      => 'nullable|string|max:1000',
        ], [
            'id_jenis_barang.required' => 'Jenis barang wajib dipilih.',
            'nama_barang.required'     => 'Nama barang wajib diisi.',
            'harga_beli.required'      => 'Harga beli wajib diisi.',
            'harga_beli.numeric'       => 'Harga beli harus berupa angka.',
            'kondisi.required'         => 'Kondisi barang wajib dipilih.',
            'foto_barang.image'        => 'File harus berupa gambar.',
            'foto_barang.max'          => 'Ukuran foto maksimal 2MB.',
        ]);

        $data = $request->only([
            'id_jenis_barang', 'nama_barang', 'harga_beli',
            'kondisi', 'berat', 'keterangan',
        ]);

        // Upload foto baru jika ada
        if ($request->hasFile('foto_barang')) {
            // Hapus foto lama jika ada
            if ($barang->foto_barang) {
                if (preg_match('/^https?:\/\//', $barang->foto_barang)) {
                    $cloudinaryUrl = env('CLOUDINARY_URL');
                    if ($cloudinaryUrl && preg_match('/upload\/(?:v\d+\/)?([^\.]+)/', $barang->foto_barang, $matches)) {
                        try {
                            $cloudinary = new Cloudinary($cloudinaryUrl);
                            $cloudinary->uploadApi()->destroy($matches[1]);
                        } catch (\Exception $e) {}
                    }
                } else {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($barang->foto_barang);
                }
            }

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

        $barang->update($data);

        return redirect()
            ->route('admin.barang.index')
            ->with('success', "Barang \"{$request->nama_barang}\" berhasil diperbarui.");
    }

    public function destroy(BarangModels $barang): RedirectResponse
    {
        // Cek apakah barang memiliki transaksi gadai atau penjualan
        if ($barang->transaksiGadai()->exists() || $barang->transaksiPenjualan()->exists()) {
            return redirect()
                ->route('admin.barang.index')
                ->with('error', "Barang \"{$barang->nama_barang}\" tidak dapat dihapus karena masih terkait dengan transaksi gadai atau penjualan.");
        }

        // Hapus foto jika ada
        if ($barang->foto_barang) {
            if (preg_match('/^https?:\/\//', $barang->foto_barang)) {
                $cloudinaryUrl = env('CLOUDINARY_URL');
                if ($cloudinaryUrl && preg_match('/upload\/(?:v\d+\/)?([^\.]+)/', $barang->foto_barang, $matches)) {
                    try {
                        $cloudinary = new Cloudinary($cloudinaryUrl);
                        $cloudinary->uploadApi()->destroy($matches[1]);
                    } catch (\Exception $e) {}
                }
            } else {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($barang->foto_barang);
            }
        }

        $barang->delete();

        return redirect()
            ->route('admin.barang.index')
            ->with('success', "Barang \"{$barang->nama_barang}\" berhasil dihapus.");
    }
}
