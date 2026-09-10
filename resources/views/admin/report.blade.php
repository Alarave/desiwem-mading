@extends('layouts.admin')

@section('title', 'Laporan & Rekapitulasi Eksekutif - DeSiWeM')

@section('content')
<div class="container-fluid px-0">
  <!-- Top Command Toolbar (Hidden on Print) -->
  <div class="report-control-bar no-print">
    <div>
      <div class="d-flex align-items-center gap-2 mb-1">
        <span class="report-doc-badge">
          <i class="bi bi-shield-check"></i> Dokumen Terverifikasi
        </span>
        <span class="text-muted font-monospace small" style="font-size: 0.725rem;">[ DOC-MADING-{{ date('Ym') }}-01 ]</span>
      </div>
      <h1 class="h5 fw-bold text-dark mb-0" style="letter-spacing: -0.02em;">Laporan & Rekapitulasi Eksekutif</h1>
      <p class="text-muted small mb-0 mt-1">Dicetak otomatis dari Arsip Resmi DeSiWeM per {{ date('d F Y') }}</p>
    </div>

    <div class="report-control-actions">
      <button type="button" class="btn-report-btn" onclick="copySummaryText()" title="Salin ringkasan angka ke clipboard">
        <i class="bi bi-copy"></i> Salin Ringkasan
      </button>
      <button type="button" class="btn-report-btn" onclick="downloadCSV()" title="Unduh dataset artikel format CSV">
        <i class="bi bi-download"></i> Export CSV
      </button>
      <button type="button" class="btn-report-btn btn-report-primary" onclick="window.print()" title="Cetak atau Simpan PDF Resmi">
        <i class="bi bi-printer"></i> Cetak Dokumen PDF
      </button>
    </div>
  </div>

  <!-- Main Report Document Sheet -->
  <div class="report-sheet">
    
    <!-- Institutional Kop Surat (Resmi) -->
    <div class="report-kop">
      <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
          <div class="report-kop-logo">
            <i class="bi bi-journal-text"></i>
          </div>
          <div>
            <h2 class="report-kop-title">DeSiWeM</h2>
            <div class="report-kop-subtitle">UNIT PENGELOLA MAJALAH DINDING DIGITAL</div>
            <p class="report-kop-meta">
              Jl. Margonda Raya No. 100, Depok, Jawa Barat &bull; Surel: redaksi@desiwem.id &bull; Portal: desiwem.id
            </p>
          </div>
        </div>

        <div class="text-end d-none d-sm-block">
          <div class="p-2 border rounded bg-white d-inline-block text-center" style="border-color: #e4e4e7;">
            <i class="bi bi-qr-code fs-4 text-dark"></i>
            <span class="d-block font-monospace text-muted" style="font-size: 0.625rem; letter-spacing: 0.08em;">VERIFIED DOC</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Official Report Banner Title -->
    <div class="report-banner">
      <h3 class="report-banner-title">Laporan Rekapitulasi Dokumentasi & Distribusi Konten Mading</h3>
      <p class="report-banner-period">
        PERIODE: {{ strtoupper(date('F Y')) }} &bull; SINKRONISASI: {{ date('d/m/Y H:i') }} WIB &bull; STATUS: RESMI
      </p>
    </div>

    <!-- Bento Analytics Matrix -->
    <div class="report-bento-grid">
      <!-- 01. Total Publikasi -->
      <div class="report-bento-card">
        <div>
          <div class="bento-label">
            <span>Total Publikasi</span>
            <span class="bento-idx">[ 01 ]</span>
          </div>
          <div class="bento-val">{{ $articles->count() }}</div>
        </div>
        <div class="bento-sub">
          <span class="report-badge-mono me-1">+{{ $articlesThisMonth }}</span> terbit bulan ini
        </div>
      </div>

      <!-- 02. Kategori Aktif -->
      <div class="report-bento-card">
        <div>
          <div class="bento-label">
            <span>Kategori Terkelola</span>
            <span class="bento-idx">[ 02 ]</span>
          </div>
          <div class="bento-val">{{ $categories->count() }}</div>
        </div>
        <div class="bento-sub">
          Rata-rata {{ $categories->count() > 0 ? round($articles->count() / $categories->count(), 1) : 0 }} artikel/kategori
        </div>
      </div>

      <!-- 03. Kategori Terpopuler -->
      <div class="report-bento-card">
        <div>
          <div class="bento-label">
            <span>Kategori Teratas</span>
            <span class="bento-idx">[ 03 ]</span>
          </div>
          <div class="bento-val text-truncate" style="font-size: 1.35rem;" title="{{ $topCategory->name ?? '-' }}">
            {{ $topCategory->name ?? '-' }}
          </div>
        </div>
        <div class="bento-sub">
          Menyumbang {{ $topCategory->articles_count ?? 0 }} publikasi
        </div>
      </div>

      <!-- 04. Kontributor Utama -->
      <div class="report-bento-card">
        <div>
          <div class="bento-label">
            <span>Penulis Teraktif</span>
            <span class="bento-idx">[ 04 ]</span>
          </div>
          <div class="bento-val text-truncate" style="font-size: 1.35rem;" title="{{ $topAuthor->name ?? 'Admin' }}">
            {{ $topAuthor->name ?? 'Admin' }}
          </div>
        </div>
        <div class="bento-sub">
          {{ $topAuthor->articles_count ?? 0 }} artikel terverifikasi
        </div>
      </div>
    </div>

    <!-- Category Proportional Distribution Meters -->
    @php $totalArt = max(1, $articles->count()); @endphp
    <div class="report-dist-box">
      <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="small fw-bold text-uppercase font-monospace text-dark mb-0" style="letter-spacing: 0.05em;">
          Proporsi Distribusi Artikel per Kategori
        </h4>
        <span class="text-muted font-monospace small" style="font-size: 0.75rem;">
          TOTAL: {{ $articles->count() }} PUBLIKASI
        </span>
      </div>

      <div>
        @foreach($categories as $cat)
          @php 
            $percentage = round(($cat->articles_count / $totalArt) * 100, 1);
          @endphp
          <div class="report-dist-item">
            <div class="report-dist-header">
              <span>{{ $cat->name }}</span>
              <span class="font-monospace text-muted" style="font-size: 0.75rem;">{{ $cat->articles_count }} artikel ({{ $percentage }}%)</span>
            </div>
            <div class="report-bar-wrap">
              <div class="report-bar-fill" style="width: {{ $percentage }}%;"></div>
            </div>
          </div>
        @endforeach
      </div>
    </div>

    <!-- Category Breakdown Table -->
    <div class="mb-5 print-avoid-break">
      <div class="d-flex align-items-center justify-content-between mb-2">
        <h4 class="small fw-bold text-uppercase font-monospace text-dark mb-0" style="letter-spacing: 0.05em;">
          Rincian Matriks Kategori
        </h4>
        <span class="report-badge-mono">{{ $categories->count() }} Kategori</span>
      </div>
      <div class="report-table-wrap">
        <table class="report-table">
          <thead>
            <tr>
              <th style="width: 7%;">No</th>
              <th style="width: 25%;">Kategori</th>
              <th style="width: 42%;">Deskripsi</th>
              <th style="width: 13%; text-align: center;">Jumlah</th>
              <th style="width: 13%; text-align: right;">Proporsi</th>
            </tr>
          </thead>
          <tbody>
            @foreach($categories as $index => $cat)
              @php $percentage = round(($cat->articles_count / $totalArt) * 100, 1); @endphp
              <tr>
                <td class="font-monospace text-muted">{{ sprintf('%02d', $index + 1) }}</td>
                <td class="fw-semibold text-dark">{{ $cat->name }}</td>
                <td class="text-muted small">{{ $cat->description ?: '—' }}</td>
                <td class="text-center font-monospace fw-semibold text-dark">{{ $cat->articles_count }}</td>
                <td class="text-end">
                  <span class="report-badge-mono">
                    {{ $percentage }}%
                  </span>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    <!-- Full Articles Ledger -->
    <div class="mb-4">
      <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2 mb-3">
        <div>
          <h4 class="small fw-bold text-uppercase font-monospace text-dark mb-0" style="letter-spacing: 0.05em;">
            Katalog Rekapitulasi Artikel
          </h4>
          <p class="text-muted small mb-0 mt-0.5">Daftar lengkap seluruh materi publikasi mading aktif</p>
        </div>
      </div>

      <!-- Interactive Filtering Toolbar (Screen only) -->
      <div class="report-filter-bar no-print">
        <div class="report-cat-pills">
          <button type="button" class="cat-pill-btn active" data-category="ALL" onclick="filterByCategory('ALL', this)">
            Semua ({{ $articles->count() }})
          </button>
          @foreach($categories as $cat)
            <button type="button" class="cat-pill-btn" data-category="{{ $cat->name }}" onclick="filterByCategory(this.getAttribute('data-category'), this)">
              {{ $cat->name }} ({{ $cat->articles_count }})
            </button>
          @endforeach
        </div>

        <div class="report-search-wrap">
          <i class="bi bi-search"></i>
          <input type="text" id="reportSearchInput" placeholder="Cari judul, penulis, tanggal..." onkeyup="filterReportArticles()">
        </div>
      </div>

      <div class="report-table-wrap">
        <table class="report-table" id="reportArticlesTable">
          <thead>
            <tr>
              <th style="width: 6%;">No</th>
              <th style="width: 44%;">Judul Artikel</th>
              <th style="width: 18%;">Kategori</th>
              <th style="width: 18%;">Penulis</th>
              <th style="width: 14%; text-align: right;">Tanggal Terbit</th>
            </tr>
          </thead>
          <tbody>
            @forelse($articles as $idx => $art)
              <tr class="report-article-row" data-category="{{ $art->category->name ?? 'Umum' }}">
                <td class="font-monospace text-muted">{{ sprintf('%02d', $idx + 1) }}</td>
                <td>
                  <div class="fw-semibold text-dark article-title-cell">{{ $art->title }}</div>
                </td>
                <td>
                  <span class="report-badge-mono article-cat-cell">
                    {{ $art->category->name ?? 'Umum' }}
                  </span>
                </td>
                <td>
                  <div class="d-flex align-items-center gap-1.5">
                    <span class="article-author-cell small text-muted">{{ $art->author->name ?? 'Admin' }}</span>
                  </div>
                </td>
                <td class="text-end font-monospace text-muted small article-date-cell">
                  {{ $art->created_at->format('d/m/Y H:i') }}
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center py-4 text-muted">
                  Belum ada rekaman artikel terbit.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div id="no-results-msg" class="text-center text-muted py-3 d-none">
        <small class="font-monospace">Tidak ada artikel yang cocok dengan kriteria pencarian.</small>
      </div>
    </div>

    <!-- Official Validation & Signatures (Essential for Formal Print) -->
    <div class="report-sign-block print-avoid-break">
      <div class="row align-items-end">
        <div class="col-7">
          <div class="small fw-bold text-uppercase font-monospace text-dark mb-1" style="font-size: 0.725rem; letter-spacing: 0.06em;">Catatan Dokumen Akademik</div>
          <p class="text-muted small mb-0" style="line-height: 1.6; max-width: 90%;">
            Laporan rekapitulasi data ini diterbitkan secara elektronik oleh Sistem Informasi Majalah Dinding Digital DeSiWeM dan diakui sebagai arsip dokumentasi resmi.
          </p>
        </div>
        <div class="col-5 text-end">
          <p class="mb-1 text-dark small font-monospace">Depok, {{ date('d F Y') }}</p>
          <p class="fw-semibold text-dark mb-5 small">Ketua Pengelola Mading Digital,</p>
          <br><br>
          <p class="fw-bold text-dark mb-0 font-monospace"><u>PAMBUDIANSYAH</u></p>
          <small class="text-muted font-monospace" style="font-size: 0.7rem;">NIP: 19880214 202601 1 002</small>
        </div>
      </div>
    </div>

  </div>
</div>

@php
$exportArticles = $articles->map(function($a, $i) {
    return [
        'no' => $i + 1,
        'title' => $a->title,
        'category' => $a->category->name ?? 'Umum',
        'author' => $a->author->name ?? 'Admin',
        'date' => $a->created_at->format('d/m/Y H:i')
    ];
})->values();
@endphp

<script>
let selectedCategory = 'ALL';
const reportArticlesData = @json($exportArticles);

function filterByCategory(categoryName, btnElement) {
  selectedCategory = categoryName;
  
  // Update active button state
  document.querySelectorAll('.cat-pill-btn').forEach(b => b.classList.remove('active'));
  if (btnElement) {
    btnElement.classList.add('active');
  }
  
  filterReportArticles();
}

function filterReportArticles() {
  const filter = (document.getElementById('reportSearchInput')?.value || '').toLowerCase();
  const rows = document.querySelectorAll('.report-article-row');
  let visibleCount = 0;

  rows.forEach(row => {
    const rowCategory = row.getAttribute('data-category') || '';
    const title = row.querySelector('.article-title-cell')?.textContent.toLowerCase() || '';
    const author = row.querySelector('.article-author-cell')?.textContent.toLowerCase() || '';
    const date = row.querySelector('.article-date-cell')?.textContent.toLowerCase() || '';

    const matchesCategory = (selectedCategory === 'ALL') || (rowCategory === selectedCategory);
    const matchesSearch = title.includes(filter) || author.includes(filter) || rowCategory.toLowerCase().includes(filter) || date.includes(filter);

    if (matchesCategory && matchesSearch) {
      row.style.display = '';
      visibleCount++;
    } else {
      row.style.display = 'none';
    }
  });

  const noResults = document.getElementById('no-results-msg');
  if (noResults) {
    if (visibleCount === 0 && rows.length > 0) {
      noResults.classList.remove('d-none');
    } else {
      noResults.classList.add('d-none');
    }
  }
}

function copySummaryText() {
  const text = `LAPORAN REKAPITULASI DeSiWeM
Portal DeSiWeM
Periode: {{ date('F Y') }}
Tanggal Sinkronisasi: {{ date('d F Y H:i') }} WIB

RINGKASAN METRIK:
- Total Artikel: {{ $articles->count() }}
- Artikel Bulan Ini: {{ $articlesThisMonth }}
- Kategori Terdaftar: {{ $categories->count() }}
- Kategori Teratas: {{ $topCategory->name ?? '-' }} ({{ $topCategory->articles_count ?? 0 }} artikel)
- Penulis Teraktif: {{ $topAuthor->name ?? 'Admin' }} ({{ $topAuthor->articles_count ?? 0 }} artikel)

Generated by Sistem DeSiWeM.`;

  navigator.clipboard.writeText(text).then(() => {
    alert('Ringkasan eksekutif laporan berhasil disalin ke clipboard!');
  }).catch(() => {
    prompt('Salin teks ringkasan ini:', text);
  });
}

function downloadCSV() {
  let csvContent = "\uFEFFNo,Judul Artikel,Kategori,Penulis,Tanggal Terbit\r\n";
  const escapeCsv = (str) => `"${String(str ?? '').replace(/"/g, '""')}"`;

  reportArticlesData.forEach(item => {
    csvContent += `${escapeCsv(item.no)},${escapeCsv(item.title)},${escapeCsv(item.category)},${escapeCsv(item.author)},${escapeCsv(item.date)}\r\n`;
  });

  const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
  const link = document.createElement('a');
  const url = URL.createObjectURL(blob);
  link.setAttribute('href', url);
  link.setAttribute('download', `Rekapitulasi_DeSiWeM_{{ date('Ymd') }}.csv`);
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
  URL.revokeObjectURL(url);
}
</script>
@endsection
