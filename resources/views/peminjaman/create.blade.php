@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h3 class="fw-bold mb-1">Transaksi Peminjaman Buku</h3>
                <p class="text-muted small mb-0">Catat peminjaman buku oleh anggota perpustakaan.</p>
            </div>
            <a href="{{ route('peminjaman.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('peminjaman.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="anggota_id" class="form-label fw-semibold small">
                            Pilih Anggota <span class="text-danger">*</span>
                        </label>
                        <select name="anggota_id" id="anggota_id"
                                class="form-select @error('anggota_id') is-invalid @enderror">
                            <option value="">-- Pilih Anggota Peminjam --</option>
                            @foreach ($anggota as $item)
                                <option value="{{ $item->id }}"
                                    {{ old('anggota_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('anggota_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="buku_id" class="form-label fw-semibold small">
                            Pilih Buku <span class="text-danger">*</span>
                        </label>
                        <select name="buku_id" id="buku_id"
                                class="form-select @error('buku_id') is-invalid @enderror">
                            <option value="">-- Pilih Buku yang Dipinjam --</option>
                            @foreach ($buku as $item)
                                <option value="{{ $item->id }}"
                                    {{ old('buku_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->judul }} (Stok: {{ $item->stok }})
                                </option>
                            @endforeach
                        </select>
                        @error('buku_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            <i class="bi bi-info-circle me-1"></i>
                            Hanya buku dengan stok &gt; 0 yang muncul dalam pilihan. Stok akan otomatis berkurang 1.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="tanggal_pinjam" class="form-label fw-semibold small">
                            Tanggal Pinjam <span class="text-danger">*</span>
                        </label>
                        <input type="date" name="tanggal_pinjam" id="tanggal_pinjam"
                               class="form-control @error('tanggal_pinjam') is-invalid @enderror"
                               value="{{ old('tanggal_pinjam', date('Y-m-d')) }}">
                        @error('tanggal_pinjam')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-2">
                        <a href="{{ route('peminjaman.index') }}" class="btn btn-light border">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Simpan Peminjaman
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection