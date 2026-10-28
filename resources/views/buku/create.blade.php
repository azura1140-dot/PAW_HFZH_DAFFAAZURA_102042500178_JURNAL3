@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h3 class="fw-bold mb-1">Tambah Buku Baru</h3>
                <p class="text-muted small mb-0">Isi formulir berikut untuk menambahkan buku ke sistem perpustakaan.</p>
            </div>
            <a href="{{ route('buku.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('buku.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="judul" class="form-label fw-semibold small">
                            Judul Buku <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="judul" id="judul"
                               class="form-control @error('judul') is-invalid @enderror"
                               value="{{ old('judul') }}"
                               placeholder="Contoh: Pemrograman Web dengan Laravel">
                        @error('judul')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="penulis" class="form-label fw-semibold small">
                                Penulis <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="penulis" id="penulis"
                                   class="form-control @error('penulis') is-invalid @enderror"
                                   value="{{ old('penulis') }}"
                                   placeholder="Contoh: John Doe">
                            @error('penulis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="penerbit" class="form-label fw-semibold small">
                                Penerbit <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="penerbit" id="penerbit"
                                   class="form-control @error('penerbit') is-invalid @enderror"
                                   value="{{ old('penerbit') }}"
                                   placeholder="Contoh: Informatika Bandung">
                            @error('penerbit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tahun_terbit" class="form-label fw-semibold small">
                                Tahun Terbit <span class="text-danger">*</span>
                            </label>
                            <input type="number" name="tahun_terbit" id="tahun_terbit"
                                   class="form-control @error('tahun_terbit') is-invalid @enderror"
                                   value="{{ old('tahun_terbit', date('Y')) }}">
                            @error('tahun_terbit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="stok" class="form-label fw-semibold small">
                                Jumlah Stok <span class="text-danger">*</span>
                            </label>
                            <input type="number" name="stok" id="stok" min="0"
                                   class="form-control @error('stok') is-invalid @enderror"
                                   value="{{ old('stok', 1) }}">
                            @error('stok')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-2">
                        <a href="{{ route('buku.index') }}" class="btn btn-light border">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Simpan Buku
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection