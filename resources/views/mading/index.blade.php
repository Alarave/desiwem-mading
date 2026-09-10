@extends('layouts.app')

@section('title', (isset($activeCategory) ? '#' . strtolower($activeCategory->name) . ' — ' : '') . 'Articles — ASESOR')

@section('content')
<div class="carbon-container">
    <!-- Header Section: Articles or #tagname -->
    <div class="carbon-header-block" id="articles-section">
        <div style="max-width: 38rem;">
            <h1 class="carbon-heading-articles" id="carbonGalleryTitle">
                {{ isset($activeCategory) ? '#' . strtolower($activeCategory->name) : 'Articles' }}
            </h1>
            <p class="carbon-heading-desc" id="carbonGalleryDesc">
                @if(isset($activeCategory))
                    Artikel dan publikasi dalam kategori <span class="carbon-tag-highlight">{{ strtolower($activeCategory->name) }}</span>.
                @else
                    Temukan kabar terkini, wawasan akademik, inovasi riset, dan karya ekspresi mahasiswa Sekolah Tinggi GUNDAR.
                @endif
            </p>
        </div>

        <!-- Tag Filter Pills (Horizontal Scroll) & Instant Search -->
        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mt-4">
            <div class="carbon-tags-scroll" id="tagFilterPills">
                <button type="button" 
                        class="carbon-tag-btn {{ !isset($activeCategory) && !request('category_id') ? 'active' : '' }}" 
                        data-filter="all"
                        data-name="all"
                        data-slug="">
                    semua
                </button>
                @foreach($categories as $cat)
                    <button type="button" 
                            class="carbon-tag-btn {{ (isset($activeCategory) && $activeCategory->id == $cat->id) || request('category_id') == $cat->id ? 'active' : '' }}" 
                            data-filter="{{ $cat->id }}"
                            data-name="{{ strtolower($cat->name) }}"
                            data-slug="{{ strtolower($cat->name) }}">
                        {{ strtolower($cat->name) }}
                    </button>
                @endforeach
            </div>

            <!-- Instant Search Input -->
            <div class="carbon-search-wrapper my-auto">
                <i class="bi bi-search carbon-search-icon"></i>
                <input type="text" id="gallerySearchInput" class="carbon-search-input" 
                       placeholder="Cari artikel..." value="{{ request('search') }}" aria-label="Cari artikel">
            </div>
        </div>
    </div>

    <!-- Blog Post Grid (4-Cols Responsive): IMAGE, TITLE, DATE, EXCERPT -->
    @if($articles->isEmpty())
        <div class="carbon-thumb-box text-center py-5 my-4">
            <h4 class="fw-bold text-base-900 mb-2">Artikel Belum Tersedia</h4>
            <p class="text-base-600 mb-0 small">Belum ada karya atau informasi yang diterbitkan pada edisi ini.</p>
        </div>
    @else
        <!-- Live Empty State for Filter/Search -->
        <div id="galleryLiveEmpty" class="carbon-thumb-box text-center py-5 d-none my-4">
            <h4 class="fw-bold text-base-900 mb-2">Artikel Tidak Ditemukan</h4>
            <p class="text-base-600 mb-3 small">Tidak ada karya yang sesuai dengan kategori atau kata kunci yang dicari.</p>
            <button type="button" class="carbon-tag-btn active" id="btnResetGalleryFilter">
                Reset Filter
            </button>
        </div>

        <div class="carbon-grid-4col" id="articleGalleryGrid">
            @foreach($articles as $article)
                <article class="carbon-article article-gallery-item position-relative" 
                         data-category="{{ $article->category_id }}" 
                         data-search="{{ strtolower($article->title . ' ' . strip_tags($article->content)) }}">
                    
                    <a href="{{ route('mading.show', $article->id) }}" 
                       class="position-absolute top-0 start-0 w-100 h-100 z-1" 
                       aria-label="{{ $article->title }}"></a>
                    
                    <!-- IMAGE (8:5 Aspect Ratio in Base-50 Padded Box) -->
                    <div class="carbon-thumb-box">
                        <img src="{{ $article->image_url ?: 'https://images.unsplash.com/photo-1585829365295-ab7cd400c167?w=600&auto=format&fit=crop&q=80' }}" 
                             alt="{{ $article->title }}" 
                             loading="lazy" 
                             decoding="async" 
                             class="carbon-thumb-img"
                             onerror="this.src='https://images.unsplash.com/photo-1585829365295-ab7cd400c167?w=600&auto=format&fit=crop&q=80'">
                    </div>

                    <!-- DATE & CATEGORY -->
                    <div class="carbon-meta-row">
                        <time datetime="{{ $article->created_at->toISOString() }}">{{ $article->created_at->format('D M d') }}</time>
                        <span aria-hidden="true">·</span>
                        <span>{{ strtolower($article->category->name ?? 'umum') }}</span>
                    </div>

                    <!-- TITLE -->
                    <h3 class="carbon-article-title">
                        {{ $article->title }}
                    </h3>

                    <!-- EXCERPT -->
                    <p class="carbon-article-excerpt">
                        {{ Str::limit(strip_tags($article->content), 120) }}
                    </p>
                </article>

                <!-- Modal Detail Pembaca Artikel -->
                <div class="modal fade" id="articleModal{{ $article->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content rounded-4 border-0 shadow-lg" style="background: var(--color-white);">
                            <div class="modal-header border-0 pb-0 pt-4 px-4">
                                <span class="badge px-3 py-1.5 rounded-pill fw-semibold text-uppercase" style="background: var(--accent-50); color: var(--accent-600); font-size: 0.75rem;">
                                    {{ $article->category->name ?? 'Umum' }}
                                </span>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-4">
                                <h2 class="fw-bold mb-3" style="font-family: var(--font-display); color: var(--base-900); font-size: 1.85rem; letter-spacing: -0.02em;">
                                    {{ $article->title }}
                                </h2>
                                <div class="d-flex align-items-center gap-3 text-muted mb-4 small border-bottom pb-3">
                                    <span>Oleh <strong>{{ $article->author->name ?? 'Admin' }}</strong></span>
                                    <span>·</span>
                                    <span>{{ $article->created_at->translatedFormat('l, d F Y') }}</span>
                                </div>

                                @if($article->image_url)
                                    <div class="mb-4 text-center carbon-thumb-box p-3">
                                        <img src="{{ $article->image_url }}" alt="{{ $article->title }}" class="img-fluid rounded shadow-sm" style="max-height: 420px; width: 100%; object-fit: cover;">
                                    </div>
                                @endif

                                <div class="article-body-content text-base-700 fs-6 lh-lg" style="color: var(--base-900); line-height: 1.8;">
                                    {!! nl2br(e($article->content)) !!}
                                </div>
                            </div>
                            <div class="modal-footer border-0 pt-0 pb-4 px-4">
                                <button type="button" class="carbon-tag-btn active px-4 py-2" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const pills = document.querySelectorAll('.carbon-tag-btn[data-filter]');
    const items = document.querySelectorAll('.article-gallery-item');
    const searchInput = document.getElementById('gallerySearchInput');
    const emptyState = document.getElementById('galleryLiveEmpty');
    const resetBtn = document.getElementById('btnResetGalleryFilter');

    let currentCategory = "{{ request('category_id') ?: 'all' }}";
    let currentSearch = (searchInput ? searchInput.value.toLowerCase().trim() : '');

    function applyFilter() {
        let visibleCount = 0;

        items.forEach(function(item) {
            const itemCat = item.getAttribute('data-category');
            const itemText = item.getAttribute('data-search') || '';

            const matchCat = (currentCategory === 'all' || itemCat === currentCategory);
            const matchSearch = (!currentSearch || itemText.includes(currentSearch));

            if (matchCat && matchSearch) {
                item.style.display = 'flex';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });

        if (emptyState) {
            if (visibleCount === 0 && items.length > 0) {
                emptyState.classList.remove('d-none');
            } else {
                emptyState.classList.add('d-none');
            }
        }
    }

    const titleEl = document.getElementById('carbonGalleryTitle');
    const descEl = document.getElementById('carbonGalleryDesc');
    const defaultTitle = 'Articles';
    const defaultDesc = 'Temukan kabar terkini, wawasan akademik, inovasi riset, dan karya ekspresi mahasiswa Sekolah Tinggi GUNDAR.';

    function setActiveCategory(catId, catName, catSlug) {
        currentCategory = String(catId);
        pills.forEach(function(pill) {
            if (pill.getAttribute('data-filter') === currentCategory) {
                pill.classList.add('active');
            } else {
                pill.classList.remove('active');
            }
        });

        if (titleEl && descEl) {
            if (currentCategory === 'all' || !catName || catName === 'all') {
                titleEl.textContent = defaultTitle;
                descEl.innerHTML = defaultDesc;
                if (window.history && window.history.pushState) {
                    window.history.pushState({}, '', '/');
                }
            } else {
                titleEl.textContent = '#' + catName;
                descEl.innerHTML = 'Artikel dan publikasi dalam kategori <span class="carbon-tag-highlight">' + catName + '</span>.';
                if (window.history && window.history.pushState && catSlug) {
                    window.history.pushState({}, '', '/tag/' + encodeURIComponent(catSlug));
                }
            }
        }

        applyFilter();
    }

    pills.forEach(function(pill) {
        pill.addEventListener('click', function() {
            const catId = this.getAttribute('data-filter');
            const catName = this.getAttribute('data-name');
            const catSlug = this.getAttribute('data-slug');
            setActiveCategory(catId, catName, catSlug);
        });
    });

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            currentSearch = this.value.toLowerCase().trim();
            applyFilter();
        });
    }

    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            if (searchInput) searchInput.value = '';
            currentSearch = '';
            setActiveCategory('all', 'all', '');
        });
    }

    if (currentCategory !== 'all' || currentSearch !== '') {
        applyFilter();
    }
});
</script>
@endsection
