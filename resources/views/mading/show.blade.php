@extends('layouts.app')

@section('title', $article->title . ' — ASESOR')
@section('meta_description', Str::limit(strip_tags($article->content), 160))
@section('og_type', 'article')
@section('og_image', $article->image_url ?: 'https://images.unsplash.com/photo-1585829365295-ab7cd400c167?w=1200&auto=format&fit=crop&q=80')

@section('content')
<main>
    <!-- Article Header & Hero -->
    <section>
        <div class="carbon-container carbon-post-wrapper">
            <!-- Title & Metadata -->
            <div class="text-center mx-auto" style="max-width: 48rem;">
                <h1 class="carbon-post-title">
                    {{ $article->title }}
                </h1>
                <p class="carbon-post-lead">
                    Dipublikasikan pada {{ $article->created_at->translatedFormat('l, d F Y') }} · Oleh {{ $article->author->name ?? 'Admin Portal' }}
                </p>
            </div>

            <!-- Category Tag Pill -->
            <div class="carbon-post-tags">
                <a href="{{ route('mading.index', ['category_id' => $article->category_id]) }}"
                   class="carbon-tag-btn">
                    {{ strtolower($article->category->name ?? 'umum') }}
                </a>
            </div>

            <!-- Featured Hero Image Box (8:5 Aspect Ratio) -->
            <div class="carbon-post-hero-box">
                <img src="{{ $article->image_url ?: 'https://images.unsplash.com/photo-1585829365295-ab7cd400c167?w=1400&auto=format&fit=crop&q=80' }}"
                     alt="{{ $article->title }}"
                     loading="lazy"
                     decoding="async"
                     class="carbon-post-hero-img"
                     onerror="this.src='https://images.unsplash.com/photo-1585829365295-ab7cd400c167?w=1400&auto=format&fit=crop&q=80'">
            </div>

            <!-- Article Prose Content -->
            <div class="mx-auto" style="max-width: 42rem;">
                <article class="carbon-prose">
                    {!! \Illuminate\Support\Str::markdown($article->content, ['html_input' => 'strip', 'allow_unsafe_links' => false]) !!}
                </article>
            </div>
        </div>
    </section>

    <!-- Latest Posts Recommendation Section -->
    @if(isset($latestArticles) && $latestArticles->isNotEmpty())
        <section>
            <div class="carbon-container carbon-related-section">
                <div class="carbon-related-header">
                    <h2 class="carbon-related-title">
                        Latest posts
                    </h2>
                    <a href="{{ route('mading.index') }}" class="carbon-btn-see-all">
                        See all posts
                    </a>
                </div>

                <div class="carbon-grid">
                    @foreach($latestArticles as $latest)
                        <article class="carbon-article position-relative">
                            <a href="{{ route('mading.show', $latest->id) }}"
                               class="position-absolute top-0 start-0 w-100 h-100 z-1"
                               aria-label="{{ $latest->title }}"></a>

                            <div class="carbon-thumb-box">
                                <img src="{{ $latest->image_url ?: 'https://images.unsplash.com/photo-1585829365295-ab7cd400c167?w=600&auto=format&fit=crop&q=80' }}"
                                     alt="{{ $latest->title }}"
                                     loading="lazy"
                                     decoding="async"
                                     class="carbon-thumb-img"
                                     onerror="this.src='https://images.unsplash.com/photo-1585829365295-ab7cd400c167?w=600&auto=format&fit=crop&q=80'">
                            </div>

                            <div class="carbon-meta-row">
                                <time datetime="{{ $latest->created_at->toISOString() }}">
                                    {{ $latest->created_at->format('D M d') }}
                                </time>
                                <span aria-hidden="true">·</span>
                                <span>{{ strtolower($latest->category->name ?? 'umum') }}</span>
                            </div>

                            <h3 class="carbon-article-title">
                                {{ $latest->title }}
                            </h3>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</main>
@endsection
