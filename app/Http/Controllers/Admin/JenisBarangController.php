<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisBarangModels;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class JenisBarangController extends Controller
{
    public function index(Request $request)
    {
        $jenisBarangQuery = JenisBarangModels::query();

        if ($request->filled('tanggal')) {
            $jenisBarangQuery->whereDate('created_at', $request->tanggal);
        }

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();

            $jenisBarangQuery->where(function ($query) use ($search) {
                $query->where('nama_jenis', 'like', '%' . $search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $search . '%');
            });
        }

        $jenisBarangList = $jenisBarangQuery->latest('id_jenis_barang')->paginate(5)->withQueryString();

        return view('admin.jenis_barang.index', compact('jenisBarangList'));
    }

    public function create()
    {
        return view('admin.jenis_barang.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama_jenis' => 'required|string|max:100',
            'deskripsi'  => 'nullable|string|max:1000',
        ], [
            'nama_jenis.required' => 'Nama jenis barang wajib diisi.',
        ]);

        $data = $request->only([
            'nama_jenis', 'deskripsi'
        ]);

        JenisBarangModels::create($data);

        return redirect()
            ->route('admin.jenis_barang.index')
            ->with('success', "Jenis barang \"{$request->nama_jenis}\" berhasil ditambahkan.");
    }
    public function edit(JenisBarangModels $jenis_barang)
    {
        return view('admin.jenis_barang.edit', ['jenisBarang' => $jenis_barang]);
    }

    public function update(Request $request, JenisBarangModels $jenis_barang): RedirectResponse
    {
        $request->validate([
            'nama_jenis' => 'required|string|max:100',
            'deskripsi'  => 'nullable|string|max:1000',
        ], [
            'nama_jenis.required' => 'Nama jenis barang wajib diisi.',
        ]);

        $jenis_barang->update([
            'nama_jenis' => $request->nama_jenis,
            'deskripsi'  => $request->deskripsi
        ]);

        return redirect()
            ->route('admin.jenis_barang.index')
            ->with('success', "Jenis barang \"{$request->nama_jenis}\" berhasil diperbarui.");
    }

    public function destroy(JenisBarangModels $jenis_barang): RedirectResponse
    {
        try {
            $nama = $jenis_barang->nama_jenis;
            $jenis_barang->delete();

            return redirect()
                ->route('admin.jenis_barang.index')
                ->with('success', "Jenis barang \"{$nama}\" berhasil dihapus.");
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.jenis_barang.index')
                ->with('error', "Gagal menghapus! Data \"{$jenis_barang->nama_jenis}\" masih digunakan di tabel lain (misal di data barang).");
        }
    }
}
