<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    /**
     * Menampilkan daftar semua transaksi peminjaman buku.
     */
    public function index()
    {
        $peminjaman = Peminjaman::with(['anggota', 'buku'])
            ->latest()
            ->paginate(10);

        return view('peminjaman.index', compact('peminjaman'));
    }

    /**
     * Menampilkan form peminjaman buku.
     */
    public function create()
    {
        $anggota = Anggota::orderBy('nama')->get();
        // Buku dengan stok 0 tidak boleh dipilih
        $buku = Buku::where('stok', '>', 0)->orderBy('judul')->get();

        return view('peminjaman.create', compact('anggota', 'buku'));
    }

    /**
     * Menyimpan transaksi peminjaman buku baru.
     */
    /**
     * Menyimpan transaksi peminjaman buku baru.
     */
    public function store(Request $request)
    {
        // TODO 7a: Validasi input
        $validated = $request->validate([
            'anggota_id' => 'required|exists:anggota,id',
            'buku_id' => 'required|exists:buku,id',
            'tanggal_pinjam' => 'required|date',
        ], [
            'anggota_id.required' => 'Anggota wajib dipilih.',
            'anggota_id.exists' => 'Anggota yang dipilih tidak ditemukan.',
            'buku_id.required' => 'Buku wajib dipilih.',
            'buku_id.exists' => 'Buku yang dipilih tidak ditemukan.',
            'tanggal_pinjam.required' => 'Tanggal pinjam wajib diisi.',
            'tanggal_pinjam.date' => 'Format tanggal pinjam tidak valid.',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                // Kunci baris buku agar pengecekan stok aman dari transaksi bersamaan
                $buku = Buku::lockForUpdate()->findOrFail($validated['buku_id']);

                // Cek ketersediaan stok
                if ($buku->stok < 1) {
                    throw new \Exception('Stok buku "' . $buku->judul . '" sedang habis.');
                }

                // TODO 7b: Simpan transaksi dengan status 'Dipinjam'
                Peminjaman::create([
                    'anggota_id' => $validated['anggota_id'],
                    'buku_id' => $validated['buku_id'],
                    'tanggal_pinjam' => $validated['tanggal_pinjam'],
                    'status' => 'Dipinjam',
                ]);

                // Kurangi stok buku sebesar 1
                $buku->decrement('stok');
            });

            // TODO 7c: Redirect ke halaman index peminjaman dengan pesan sukses
            return redirect()->route('peminjaman.index')
                ->with('success', 'Peminjaman buku berhasil dicatat!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Menampilkan detail peminjaman buku.
     */
    public function show(Peminjaman $peminjaman)
    {
        $peminjaman->load(['anggota', 'buku']);

        return view('peminjaman.show', compact('peminjaman'));
    }

    /**
     * Memproses pengembalian buku.
     */
    public function kembali(Peminjaman $peminjaman)
    {
        // Tolak jika buku sudah dikembalikan sebelumnya
        if ($peminjaman->status === 'Dikembalikan') {
            return redirect()->route('peminjaman.index')
                ->with('error', 'Buku pada transaksi ini sudah pernah dikembalikan sebelumnya.');
        }

        try {
            DB::transaction(function () use ($peminjaman) {
                // Tambahkan stok buku sebesar 1
                $buku = Buku::lockForUpdate()->findOrFail($peminjaman->buku_id);
                $buku->increment('stok');

                // Update status dan tanggal kembali
                $peminjaman->update([
                    'status' => 'Dikembalikan',
                    'tanggal_kembali' => now()->toDateString(),
                ]);
            });

            return redirect()->route('peminjaman.index')
                ->with('success', 'Buku berhasil dikembalikan!');
        } catch (\Exception $e) {
            return redirect()->route('peminjaman.index')
                ->with('error', 'Gagal memproses pengembalian: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus catatan peminjaman.
     */
    public function destroy(Peminjaman $peminjaman)
    {
        // Jika masih dipinjam, kembalikan stok buku sebelum dihapus
        if ($peminjaman->status === 'Dipinjam') {
            $peminjaman->buku()->increment('stok');
        }

        $peminjaman->delete();

        return redirect()->route('peminjaman.index')
            ->with('success', 'Data peminjaman berhasil dihapus!');
    }
}
