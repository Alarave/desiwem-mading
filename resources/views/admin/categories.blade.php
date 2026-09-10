@extends('layouts.admin')

@section('title', 'Kelola Kategori - Admin Mading ASESOR')
@section('page-title', 'Kelola Kategori Artikel')

@section('content')
<div class="card card-custom p-4">
    <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
        <div>
            <h5 class="fw-bold mb-1 text-slate-900">Daftar Kategori Mading</h5>
            <small class="text-muted">Kelola pengelompokan tulisan mading portal DeSiWeM.</small>
        </div>
        <button type="button" class="btn btn-primary-custom px-4 fw-bold shadow" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
            <i class="bi bi-plus-lg me-1"></i> Tambah Kategori Baru
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-custom align-middle">
            <thead>
                <tr>
                    <th style="width: 70px;">ID</th>
                    <th>Nama Kategori</th>
                    <th>Deskripsi Singkat</th>
                    <th>Jumlah Artikel</th>
                    <th style="width: 150px;" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td><span class="badge bg-slate-200 text-slate-700 font-bold" style="background: #e2e8f0; color: #334155;">#{{ $category->id }}</span></td>
                        <td class="fw-bold text-slate-800 fs-6">{{ $category->name }}</td>
                        <td class="text-muted small">{{ $category->description ?: '-' }}</td>
                        <td>
                            <span class="badge bg-info bg-opacity-10 text-info px-3 py-1.5 rounded-pill fw-bold">
                                <i class="bi bi-file-earmark-text me-1"></i> {{ $category->articles_count }} Artikel
                            </span>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-warning rounded-circle me-1" 
                                    data-bs-toggle="modal" data-bs-target="#editCategoryModal{{ $category->id }}"
                                    title="Edit Kategori">
                                <i class="bi bi-pencil-fill"></i>
                            </button>
                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" title="Hapus Kategori">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </form>
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
                                            <label class="form-label fw-bold text-slate-700 small text-uppercase">Nama Kategori</label>
                                            <input type="text" name="name" class="form-control py-2.5" value="{{ old('name', $category->name) }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-slate-700 small text-uppercase">Deskripsi</label>
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
                        <td colspan="5" class="text-center py-4 text-muted">Belum ada kategori yang ditambahkan.</td>
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
                        <label class="form-label fw-bold text-slate-700 small text-uppercase">Nama Kategori</label>
                        <input type="text" name="name" class="form-control py-2.5" placeholder="Contoh: Pengumuman, Seni, Ilmiah" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-slate-700 small text-uppercase">Deskripsi Singkat</label>
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
