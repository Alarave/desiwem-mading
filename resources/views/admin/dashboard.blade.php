@extends('layouts.admin')

@section('title', 'Pusat Kendali Admin - DeSiWeM')

@section('content')
<!-- Hero Welcome & Operational Actions -->
<div class="dash-hero">
  <div class="dash-hero-info">
    <h1>Pusat Kendali Admin</h1>
    <p>Halo, <strong>{{ Auth::user()->name ?? 'Administrator' }}</strong>. Pantau publikasi artikel, kategori, dan aktivitas mading dalam satu panel terpadu.</p>
  </div>
  <div class="dash-actions">
    <a href="{{ route('admin.articles') }}" class="btn-dash-action btn-dash-primary">
      <i class="bi bi-pencil-square"></i> Kelola Artikel
    </a>
    <a href="{{ route('admin.categories') }}" class="btn-dash-action btn-dash-outline">
      <i class="bi bi-folder-plus"></i> Kategori
    </a>
    <a href="{{ route('mading.index') }}" target="_blank" class="btn-dash-action btn-dash-outline">
      <i class="bi bi-box-arrow-up-right"></i> Papan Publik
    </a>
  </div>
</div>

<!-- 3-Column Key Metric Overview -->
<section class="dash-kpi-grid">
  <div class="dash-kpi-card articles">
    <div class="dash-kpi-icon">
      <i class="bi bi-newspaper"></i>
    </div>
    <div class="dash-kpi-data">
      <div class="dash-kpi-val" id="total-articles-cnt">{{ $totalArticles }}</div>
      <div class="dash-kpi-lbl">Total Artikel Terbit</div>
    </div>
  </div>

  <div class="dash-kpi-card categories">
    <div class="dash-kpi-icon">
      <i class="bi bi-tags"></i>
    </div>
    <div class="dash-kpi-data">
      <div class="dash-kpi-val" id="total-categories-cnt">{{ $totalCategories }}</div>
      <div class="dash-kpi-lbl">Kategori Terdaftar</div>
    </div>
  </div>

  <div class="dash-kpi-card users">
    <div class="dash-kpi-icon">
      <i class="bi bi-people"></i>
    </div>
    <div class="dash-kpi-data">
      <div class="dash-kpi-val" id="total-users-cnt">{{ $totalUsers ?? 1 }}</div>
      <div class="dash-kpi-lbl">Admin & Penulis</div>
    </div>
  </div>
</section>

<!-- Interactive Analytics Charts Grid (Lexington Carbon Editorial) -->
<section class="carbon-chart-grid">
  <!-- Chart 1: Monthly Publication Trend (Discrete Bar Activity) -->
  <div class="carbon-chart-card">
    <div class="carbon-chart-header">
      <div class="carbon-chart-title-group">
        <h2 class="carbon-chart-title">
          <i class="bi bi-bar-chart-line text-dark"></i> Tren Publikasi Artikel
        </h2>
        <p class="carbon-chart-desc">Volume artikel terbit per bulan dalam 6 bulan terakhir</p>
      </div>
      <span class="carbon-chart-badge">Semester Berjalan</span>
    </div>
    <div class="carbon-chart-body">
      <canvas id="monthlyTrendChart"></canvas>
    </div>
  </div>

  <!-- Chart 2: Category Composition (Donut Ring + Breakdown List) -->
  <div class="carbon-chart-card">
    <div class="carbon-chart-header">
      <div class="carbon-chart-title-group">
        <h2 class="carbon-chart-title">
          <i class="bi bi-pie-chart text-dark"></i> Proporsi Kategori
        </h2>
        <p class="carbon-chart-desc">Distribusi konten aktif berdasarkan kategori</p>
      </div>
      <span class="carbon-chart-badge">{{ count($categoryChartLabels) }} Kategori</span>
    </div>
    <div class="carbon-chart-body">
      <div class="carbon-donut-split">
        <div class="carbon-donut-wrapper">
          <canvas id="categoryDistributionChart"></canvas>
          <div class="carbon-donut-center-metric">
            <span class="carbon-donut-center-val">{{ array_sum($categoryChartCounts) }}</span>
            <span class="carbon-donut-center-lbl">Artikel</span>
          </div>
        </div>
        <div class="carbon-category-breakdown-list">
          @php
            $catTotal = array_sum($categoryChartCounts);
            $catPalette = ['#18181b', '#2563eb', '#059669', '#d97706', '#dc2626', '#7c3aed'];
          @endphp
          @foreach($categoryChartLabels as $idx => $lbl)
            @php
              $cnt = $categoryChartCounts[$idx] ?? 0;
              $pct = $catTotal > 0 ? round(($cnt / $catTotal) * 100, 1) : 0;
              $clr = $catPalette[$idx % count($catPalette)];
            @endphp
            <div class="carbon-category-row">
              <div class="carbon-category-row-head">
                <span class="carbon-category-row-name">
                  <span style="width: 8px; height: 8px; border-radius: 50%; background: {{ $clr }}; display: inline-block;"></span>
                  {{ $lbl }}
                </span>
                <span class="carbon-category-row-count">{{ $cnt }} ({{ $pct }}%)</span>
              </div>
              <div class="carbon-category-progress-track">
                <div class="carbon-category-progress-fill" style="width: {{ $pct }}%; background: {{ $clr }};"></div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Two-Column Operational Section -->
<div class="dash-split-grid">
  <!-- Left Column: Recent Articles (Main Workstream) -->
  <div class="dash-panel">
    <div class="dash-panel-header">
      <h2 class="dash-panel-title">
        <i class="bi bi-clock-history text-primary"></i> 5 Artikel Terbit Terkini
      </h2>
      <a href="{{ route('admin.articles') }}" class="dash-panel-link">
        Lihat Semua <i class="bi bi-arrow-right"></i>
      </a>
    </div>

    <div class="table-responsive">
      <table class="dash-table">
        <thead>
          <tr>
            <th>Judul Artikel</th>
            <th>Kategori</th>
            <th>Tanggal</th>
            <th style="text-align: right;">Aksi</th>
          </tr>
        </thead>
        <tbody id="recent-articles-list">
          @forelse($recentArticles as $article)
            <tr>
              <td>
                <a href="{{ route('admin.articles') }}" class="dash-article-title" title="{{ $article->title }}">
                  {{ $article->title }}
                </a>
                <div class="dash-author-sub">
                  <i class="bi bi-person"></i> {{ $article->author->name ?? 'Admin' }}
                </div>
              </td>
              <td>
                <span class="badge" style="background: rgba(79, 70, 229, 0.1); color: var(--primary); padding: 0.35rem 0.65rem; border-radius: 9999px; font-size: 0.775rem; font-weight: 700;">
                  {{ $article->category->name ?? 'Umum' }}
                </span>
              </td>
              <td style="color: var(--text-muted); font-size: 0.85rem; white-space: nowrap;">
                {{ $article->created_at->format('d M Y') }}
              </td>
              <td style="text-align: right; white-space: nowrap;">
                <a href="{{ route('admin.articles') }}" class="btn btn-sm btn-outline-secondary rounded-pill py-0 px-2" title="Kelola Artikel">
                  <i class="bi bi-pencil" style="font-size: 0.75rem;"></i>
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="4" style="text-align: center; color: var(--text-muted); padding: 2.5rem 1rem;">
                <i class="bi bi-journal-x d-block mb-2" style="font-size: 2rem; opacity: 0.4;"></i>
                Belum ada artikel terbit. <a href="{{ route('admin.articles') }}" class="text-primary fw-semibold">Mulai buat artikel pertama</a>.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <!-- Right Column: Category Distribution & Embedded Guide -->
  <div class="d-flex flex-column gap-3">
    <!-- Category Distribution Widget -->
    <div class="dash-panel">
      <div class="dash-panel-header">
        <h2 class="dash-panel-title">
          <i class="bi bi-pie-chart text-info"></i> Top Kategori
        </h2>
        <a href="{{ route('admin.categories') }}" class="dash-panel-link">
          Kelola <i class="bi bi-arrow-right"></i>
        </a>
      </div>

      <div class="category-dist-list">
        @forelse($categories as $cat)
          <div class="category-dist-item">
            <span class="text-truncate me-2">
              <i class="bi bi-folder2 text-secondary me-1"></i> {{ $cat->name }}
            </span>
            <span class="category-dist-count">
              {{ $cat->articles_count ?? 0 }} artikel
            </span>
          </div>
        @empty
          <div class="text-muted text-center py-3" style="font-size: 0.875rem;">
            Belum ada kategori terdaftar.
          </div>
        @endforelse
      </div>
    </div>

    <!-- Video Guide Compact Card -->
    <div class="dash-panel">
      <div class="dash-panel-header">
        <h2 class="dash-panel-title">
          <i class="bi bi-play-circle text-danger"></i> Panduan & Sorotan
        </h2>
      </div>
      <div class="video-dock-player">
        <iframe 
          src="https://www.youtube.com/embed/xdKSW7kpK3E?si=fpUPejSTxcXxWOgg" 
          title="Video Panduan Mading" 
          allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
          referrerpolicy="strict-origin-when-cross-origin" 
          allowfullscreen>
        </iframe>
      </div>
      <p class="text-muted mt-2 mb-0" style="font-size: 0.775rem;">
        <i class="bi bi-info-circle me-1"></i> Video tutorial panduan standar penggunaan sistem mading digital.
      </p>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Monthly Publication Trend (Editorial Discrete Column Chart)
    const trendCtx = document.getElementById('monthlyTrendChart');
    if (trendCtx) {
        const trendLabels = {!! json_encode($monthlyTrendLabels) !!};
        const trendData = {!! json_encode($monthlyTrendData) !!};

        new Chart(trendCtx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: trendLabels,
                datasets: [{
                    label: 'Artikel Terbit',
                    data: trendData,
                    backgroundColor: '#18181b',
                    hoverBackgroundColor: '#27272a',
                    borderRadius: 4,
                    borderSkipped: false,
                    maxBarThickness: 32
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#18181b',
                        titleColor: '#f4f4f5',
                        bodyColor: '#e4e4e7',
                        borderColor: '#27272a',
                        borderWidth: 1,
                        padding: 10,
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y + ' Artikel dipublikasikan';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#71717a',
                            font: {
                                family: "'Inter', sans-serif",
                                size: 11,
                                weight: 500
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            color: '#71717a',
                            font: {
                                family: "'Inter', sans-serif",
                                size: 11
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.04)',
                            drawBorder: false
                        }
                    }
                }
            }
        });
    }

    // 2. Category Composition (Precision Donut Ring)
    const catCtx = document.getElementById('categoryDistributionChart');
    if (catCtx) {
        const catLabels = {!! json_encode($categoryChartLabels) !!};
        const catData = {!! json_encode($categoryChartCounts) !!};
        const catColors = ['#18181b', '#2563eb', '#059669', '#d97706', '#dc2626', '#7c3aed'];

        new Chart(catCtx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: catLabels,
                datasets: [{
                    data: catData,
                    backgroundColor: catColors.slice(0, catLabels.length),
                    borderColor: '#ffffff',
                    borderWidth: 2,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '74%',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#18181b',
                        titleColor: '#f4f4f5',
                        bodyColor: '#e4e4e7',
                        borderColor: '#27272a',
                        borderWidth: 1,
                        padding: 8,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const value = context.parsed;
                                const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                return ' ' + context.label + ': ' + value + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>
@endsection
