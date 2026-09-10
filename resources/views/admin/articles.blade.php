@extends('layouts.admin')

@section('title', 'Kelola Artikel - Admin Mading ASESOR')
@section('page-title', 'Kelola Artikel Mading')

@section('content')
<div class="card card-custom p-4">
    <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
        <div>
            <h5 class="fw-bold mb-1 text-slate-900">Daftar Publikasi Artikel</h5>
            <small class="text-muted">Kelola seluruh pengumuman & artikel mading kampus Sekolah Tinggi GUNDAR.</small>
        </div>
        <a href="{{ route('articles.create') }}" class="btn btn-primary-custom px-4 fw-bold shadow d-inline-flex align-items-center gap-1.5">
            <i class="bi bi-plus-lg"></i> Buat Artikel Baru
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-custom align-middle">
            <thead>
                <tr>
                    <th style="width: 60px;">ID</th>
                    <th style="width: 90px;">Gambar</th>
                    <th>Judul & Ringkasan</th>
                    <th>Kategori</th>
                    <th>Penulis</th>
                    <th>Tanggal Dibuat</th>
                    <th style="width: 140px;" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($articles as $article)
                    <tr>
                        <td><span class="badge bg-slate-200 text-slate-700 font-bold" style="background: #e2e8f0; color: #334155;">#{{ $article->id }}</span></td>
                        <td>
                            <img src="{{ $article->image_url ?: 'https://images.unsplash.com/photo-1585829365295-ab7cd400c167?w=120&auto=format&fit=crop&q=80' }}" 
                                 class="rounded-3 shadow-sm" style="width: 58px; height: 44px; object-fit: cover;"
                                 onerror="this.src='https://images.unsplash.com/photo-1585829365295-ab7cd400c167?w=120&auto=format&fit=crop&q=80'">
                        </td>
                        <td>
                            <span class="fw-bold text-slate-800 d-block fs-6 mb-1">{{ Str::limit($article->title, 55) }}</span>
                            <small class="text-muted">{{ Str::limit(strip_tags($article->content), 65) }}</small>
                        </td>
                        <td>
                            @php
                                $catName = $article->category->name ?? 'Umum';
                                $badgeClass = 'badge-category-slate';
                                if (str_contains(strtolower($catName), 'info')) {
                                    $badgeClass = 'badge-category-indigo';
                                } elseif (str_contains(strtolower($catName), 'seni')) {
                                    $badgeClass = 'badge-category-purple';
                                } elseif (str_contains(strtolower($catName), 'ilmiah')) {
                                    $badgeClass = 'badge-category-emerald';
                                }
                            @endphp
                            <span class="badge {{ $badgeClass }}">
                                {{ $catName }}
                            </span>
                        </td>
                        <td class="small fw-semibold text-slate-700">{{ $article->author->name ?? 'Admin' }}</td>
                        <td class="small text-muted fw-medium">{{ $article->created_at->format('d M Y H:i') }}</td>
                        <td class="text-center">
                            <a href="{{ route('articles.edit', $article->id) }}" class="btn btn-sm btn-outline-warning rounded-circle me-1" title="Edit Artikel">
                                <i class="bi bi-pencil-fill"></i>
                            </a>
                            <form action="{{ route('articles.destroy', $article->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus artikel ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" title="Hapus Artikel">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">Belum ada artikel yang ditambahkan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
