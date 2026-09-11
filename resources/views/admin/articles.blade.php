@extends('layouts.admin')

@section('title', 'Kelola Artikel - Admin Mading ASESOR')
@section('page-title', 'Kelola Artikel Mading')

@section('content')
<div class="card card-custom p-4">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4 border-bottom pb-3">
        <div>
            <h5 class="fw-bold mb-1 text-dark">Daftar Publikasi Artikel</h5>
            <p class="text-secondary small mb-0">Kelola seluruh pengumuman & artikel mading portal DeSiWeM.</p>
        </div>
        <a href="{{ route('articles.create') }}" class="btn btn-primary-custom px-4 fw-bold shadow-sm d-inline-flex align-items-center gap-2">
            <i class="bi bi-plus-lg"></i> Buat Artikel Baru
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-custom table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th style="width: 60px;" class="text-center">ID</th>
                    <th style="width: 76px;">Gambar</th>
                    <th>Judul & Ringkasan</th>
                    <th>Kategori</th>
                    <th>Penulis</th>
                    <th>Diterbitkan</th>
                    <th style="width: 140px;" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($articles as $article)
                    <tr>
                        <td class="text-center">
                            <span class="badge text-bg-light border text-secondary font-monospace small">#{{ $article->id }}</span>
                        </td>
                        <td>
                            @if($article->image_url)
                                <img src="{{ $article->image_url }}" 
                                     alt="{{ $article->title }}" 
                                     class="rounded-2 border object-fit-cover shadow-xs" 
                                     style="width: 58px; height: 42px;"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="rounded-2 border bg-light text-secondary d-none align-items-center justify-content-center" style="width: 58px; height: 42px;">
                                    <i class="bi bi-image text-muted"></i>
                                </div>
                            @else
                                <div class="rounded-2 border bg-light text-secondary d-flex align-items-center justify-content-center" style="width: 58px; height: 42px;">
                                    <i class="bi bi-file-earmark-text text-muted"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('articles.edit', $article->id) }}" class="fw-semibold text-dark text-decoration-none d-block mb-1" title="{{ $article->title }}">
                                {{ Str::limit($article->title, 55) }}
                            </a>
                            <div class="text-secondary small" style="max-width: 440px;">
                                {{ Str::limit(strip_tags($article->content), 75) }}
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border px-2.5 py-1.5 rounded-pill fw-medium">
                                <i class="bi bi-tag text-muted me-1"></i>{{ $article->category->name ?? 'Umum' }}
                            </span>
                        </td>
                        <td>
                            <span class="small fw-medium text-dark">{{ $article->author->name ?? 'Admin' }}</span>
                        </td>
                        <td>
                            <div class="small fw-medium text-dark">{{ $article->created_at->format('d M Y') }}</div>
                            <div class="text-secondary" style="font-size: 0.75rem;">{{ $article->created_at->format('H:i') }} WIB</div>
                        </td>
                        <td class="text-center">
                            <div class="d-inline-flex gap-1">
                                <a href="{{ route('mading.show', $article->id) }}" target="_blank" class="btn btn-sm btn-outline-secondary rounded-2" title="Lihat di Beranda">
                                    <i class="bi bi-box-arrow-up-right"></i>
                                </a>
                                <a href="{{ route('articles.edit', $article->id) }}" class="btn btn-sm btn-outline-primary rounded-2" title="Edit Artikel">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('articles.destroy', $article->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus artikel ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-2" title="Hapus Artikel">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-secondary">
                            <i class="bi bi-inbox fs-2 d-block mb-2 text-muted"></i>
                            Belum ada artikel yang ditambahkan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
