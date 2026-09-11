@extends('layouts.admin')

@section('title', 'Kelola Kategori - Admin Mading ASESOR')
@section('page-title', 'Kelola Kategori Artikel')

@section('content')
<div class="card card-custom p-4">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4 border-bottom pb-3">
        <div>
            <h5 class="fw-bold mb-1 text-dark">Daftar Kategori Mading</h5>
            <p class="text-secondary small mb-0">Kelola pengelompokan tulisan dan tag mading portal DeSiWeM.</p>
        </div>
        <button type="button" class="btn btn-primary-custom px-4 fw-bold shadow-sm d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
            <i class="bi bi-plus-lg"></i> Tambah Kategori Baru
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-custom table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th style="width: 60px;" class="text-center">ID</th>
                    <th>Nama Kategori</th>
                    <th>Deskripsi Singkat</th>
                    <th>Jumlah Artikel</th>
                    <th style="width: 140px;" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td class="text-center">
                            <span class="badge text-bg-light border text-secondary font-monospace small">#{{ $category->id }}</span>
                        </td>
                        <td>
                            <div class="fw-semibold text-dark mb-0.5">{{ $category->name }}</div>
                            <span class="text-muted small font-monospace">/tag/{{ \Illuminate\Support\Str::slug($category->name) }}</span>
                        </td>
                        <td>
                            <span class="text-secondary small">{{ $category->description ?: 'Tidak ada deskripsi' }}</span>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border px-2.5 py-1.5 rounded-pill fw-medium">
                                <i class="bi bi-journal-text text-muted me-1"></i> {{ $category->articles_count }} Artikel
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="d-inline-flex gap-1">
                                <a href="{{ route('mading.tag', \Illuminate\Support\Str::slug($category->name)) }}" target="_blank" class="btn btn-sm btn-outline-secondary rounded-2" title="Lihat di Beranda">
                                    <i class="bi bi-box-arrow-up-right"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-2" 
                                        data-bs-toggle="modal" data-bs-target="#editCategoryModal{{ $category->id }}"
                                        title="Edit Kategori">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-2" title="Hapus Kategori">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <!-- Modal Edit Kategori -->
                    <div class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content rounded-4 border-0 shadow-lg">
                                <form action="{{ route('categories.update', $category->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header border-bottom">
                                        <h5 class="modal-title fw-bold">Edit Kategori #{{ $category->id }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body p-4">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-dark small text-uppercase">Nama Kategori</label>
                                            <input type="text" name="name" class="form-control py-2" value="{{ old('name', $category->name) }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-dark small text-uppercase">Deskripsi</label>
                                            <textarea name="description" class="form-control" rows="3">{{ old('description', $category->description) }}</textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0 pt-0">
                                        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary-custom px-4 fw-bold">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-secondary">
                            <i class="bi bi-inbox fs-2 d-block mb-2 text-muted"></i>
                            Belum ada kategori yang ditambahkan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Kategori -->
<div class="modal fade" id="createCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <div class="modal-header border-bottom">
                    <h5 class="modal-title fw-bold">Tambah Kategori Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark small text-uppercase">Nama Kategori</label>
                        <input type="text" name="name" class="form-control py-2" placeholder="Contoh: Pengumuman, Seni, Ilmiah" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark small text-uppercase">Deskripsi Singkat</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Jelaskan mengenai jenis artikel dalam kategori ini..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary-custom px-4 fw-bold">Simpan Kategori</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
