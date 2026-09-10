@extends('layouts.admin')

@section('title', 'Laporan & Rekapitulasi Eksekutif - DeSiWeM')

@section('content')
<div class="container-fluid px-0">
  <!-- Top Command Toolbar (Hidden on Print) -->
  <div class="report-control-bar no-print">
    <div>
      <div class="d-flex align-items-center gap-2 mb-1">
        <span class="report-doc-badge">
          <i class="bi bi-shield-check"></i> Dokumen Sah Terverifikasi
        </span>
        <small class="text-muted font-monospace" style="font-size: 0.775rem;">DOC-MADING-{{ date('Ym') }}-01</small>
      </div>
      <h1 class="h4 fw-bold text-dark mb-0">Laporan & Rekapitulasi Eksekutif</h1>
      <p class="text-muted small mb-0 mt-1">Dicetak otomatis dari Arsip Resmi DeSiWeM per {{ date('d F Y') }}</p>
    </div>

    <div class="report-control-actions">
      <button type="button" class="btn-report-btn" onclick="copySummaryText()" title="Salin ringkasan angka ke clipboard">
        <i class="bi bi-clipboard-check"></i> Salin Ringkasan
      </button>
      <button type="button" class="btn-report-btn" onclick="downloadCSV()" title="Unduh dataset artikel format CSV">
        <i class="bi bi-file-earmark-spreadsheet"></i> Export CSV
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
            <i class="bi bi-journal-bookmark-fill"></i>
          </div>
          <div>
            <h2 class="report-kop-title">DeSiWeM</h2>
            <div class="report-kop-subtitle">UNIT PENGELOLA MAJALAH DINDING DIGITAL</div>
            <p class="report-kop-meta">
              Jl. Margonda Raya No. 100, Depok, Jawa Barat | Surel: redaksi@desiwem.id | Portal: desiwem.id
            </p>
          </div>
        </div>

        <div class="text-end d-none d-sm-block">
          <div class="p-2 border rounded-3 bg-white d-inline-block text-center shadow-xs" style="border-color: #cbd5e1;">
            <i class="bi bi-qr-code fs-3 text-dark"></i>
            <span class="d-block font-monospace text-muted" style="font-size: 0.65rem; letter-spacing: 0.05em;">VALIDATED DOC</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Official Report Banner Title -->
    <div class="report-banner">
      <h3 class="report-banner-title">LAPORAN REKAPITULASI DOKUMENTASI & DISTRIBUSI KONTEN MADING</h3>
      <p class="report-banner-period">
        Periode: <strong>{{ date('F Y') }}</strong> &bull; Waktu Sinkronisasi: <strong>{{ date('d F Y, H:i') }} WIB</strong>
      </p>
    </div>

    <!-- Bento Analytics Matrix -->
    <div class="report-bento-grid">
      <!-- 1. Total Publikasi -->
      <div class="report-bento-card">
        <div>
          <div class="bento-label">
            <span>Total Publikasi</span>
            <i class="bi bi-newspaper text-primary"></i>
          </div>
          <div class="bento-val">{{ $articles->count() }}</div>
        </div>
        <div class="bento-sub">
          <span class="badge bg-success-subtle text-success font-monospace me-1">+{{ $articlesThisMonth }}</span>
          terbit bulan ini
        </div>
      </div>

      <!-- 2. Kategori Aktif -->
      <div class="report-bento-card">
        <div>
          <div class="bento-label">
            <span>Kategori Terkelola</span>
            <i class="bi bi-folder2-open text-info"></i>
          </div>
          <div class="bento-val">{{ $categories->count() }}</div>
        </div>
        <div class="bento-sub">
          Rata-rata {{ $categories->count() > 0 ? round($articles->count() / $categories->count(), 1) : 0 }} artikel/kategori
        </div>
      </div>

      <!-- 3. Kategori Terpopuler -->
      <div class="report-bento-card">
        <div>
          <div class="bento-label">
            <span>Kategori Teratas</span>
            <i class="bi bi-trophy text-warning"></i>
          </div>
          <div class="bento-val text-truncate" style="font-size: 1.45rem;" title="{{ $topCategory->name ?? '-' }}">
            {{ $topCategory->name ?? '-' }}
          </div>
        </div>
        <div class="bento-sub">
          Menyumbang {{ $topCategory->articles_count ?? 0 }} publikasi
        </div>
      </div>

      <!-- 4. Kontributor Utama -->
      <div class="report-bento-card">
        <div>
          <div class="bento-label">
            <span>Penulis Teraktif</span>
            <i class="bi bi-person-check text-success"></i>
          </div>
          <div class="bento-val text-truncate" style="font-size: 1.45rem;" title="{{ $topAuthor->name ?? 'Admin' }}">
            {{ $topAuthor->name ?? 'Admin' }}
          </div>
        </div>
        <div class="bento-sub">
          {{ $topAuthor->articles_count ?? 0 }} artikel diverifikasi
        </div>
      </div>
    </div>

    <!-- Category Proportional Distribution Meters -->
    @php $totalArt = max(1, $articles->count()); @endphp
    <div class="report-dist-box">
      <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="h6 fw-bold text-dark mb-0">
          <i class="bi bi-bar-chart-steps text-primary me-1"></i> Proporsi Distribusi Artikel per Kategori
        </h4>
        <span class="text-muted small">Total: <strong>{{ $articles->count() }}</strong> Tulisan Terbit</span>
      </div>

      <div>
        @foreach($categories as $cat)
          @php 
            $percentage = round(($cat->articles_count / $totalArt) * 100, 1);
          @endphp
          <div class="report-dist-item">
            <div class="report-dist-header">
              <span>{{ $cat->name }}</span>
              <span class="font-monospace text-muted">{{ $cat->articles_count }} Artikel ({{ $percentage }}%)</span>
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
        <h4 class="h6 fw-bold text-dark mb-0">
          <i class="bi bi-folder-check text-primary me-1"></i> Rincian Kategori Mading
        </h4>
        <span class="badge bg-light text-dark border font-monospace">{{ $categories->count() }} Kategori</span>
      </div>
      <div class="table-responsive border rounded-3 overflow-hidden">
        <table class="report-table">
          <thead>
            <tr>
              <th style="width: 6%;">No</th>
              <th style="width: 25%;">Nama Kategori</th>
              <th style="width: 43%;">Deskripsi</th>
              <th style="width: 14%; text-align: center;">Jumlah Artikel</th>
              <th style="width: 12%; text-align: center;">Proporsi</th>
            </tr>
          </thead>
          <tbody>
            @foreach($categories as $index => $cat)
              @php $percentage = round(($cat->articles_count / $totalArt) * 100, 1); @endphp
              <tr>
                <td class="font-monospace text-muted">{{ $index + 1 }}</td>
                <td class="fw-bold text-dark">{{ $cat->name }}</td>
                <td class="text-muted small">{{ $cat->description ?: '-' }}</td>
                <td class="text-center font-monospace fw-bold text-dark">{{ $cat->articles_count }}</td>
                <td class="text-center">
                  <span class="badge bg-light text-dark border font-monospace">
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
          <h4 class="h6 fw-bold text-dark mb-0">
            <i class="bi bi-journal-text text-primary me-1"></i> Katalog Rekapitulasi Artikel
          </h4>
          <p class="text-muted small mb-0">Daftar lengkap seluruh materi publikasi mading aktif</p>
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

      <div class="table-responsive border rounded-3 overflow-hidden">
        <table class="report-table" id="reportArticlesTable">
          <thead>
            <tr>
              <th style="width: 5%;">No</th>
              <th style="width: 44%;">Judul Artikel</th>
              <th style="width: 18%;">Kategori</th>
              <th style="width: 18%;">Penulis</th>
              <th style="width: 15%; text-align: right;">Tanggal Terbit</th>
            </tr>
          </thead>
          <tbody>
            @forelse($articles as $idx => $art)
              <tr class="report-article-row" data-category="{{ $art->category->name ?? 'Umum' }}">
                <td class="font-monospace text-muted">{{ $idx + 1 }}</td>
                <td>
                  <div class="fw-bold text-dark article-title-cell">{{ $art->title }}</div>
                </td>
                <td>
                  <span class="badge bg-light text-dark border article-cat-cell">
                    {{ $art->category->name ?? 'Umum' }}
                  </span>
                </td>
                <td>
                  <div class="d-flex align-items-center gap-1.5">
                    <i class="bi bi-person-circle text-muted"></i>
                    <span class="article-author-cell">{{ $art->author->name ?? 'Admin' }}</span>
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
        <small>Tidak ada artikel yang cocok dengan filter pencarian.</small>
      </div>
    </div>

    <!-- Official Validation & Signatures (Essential for Formal Print) -->
    <div class="report-sign-block print-avoid-break">
      <div class="row align-items-end">
        <div class="col-7">
          <div class="small fw-bold text-dark mb-1">Catatan Dokumen Akademik:</div>
          <p class="text-muted small mb-0" style="line-height: 1.5;">
            Laporan rekapitulasi data ini diterbitkan secara elektronik oleh Sistem Informasi Majalah Dinding Digital DeSiWeM dan diakui sebagai arsip dokumentasi resmi.
          </p>
        </div>
        <div class="col-5 text-end">
          <p class="mb-1 text-dark small">Depok, {{ date('d F Y') }}</p>
          <p class="fw-bold text-dark mb-5">Ketua Pengelola Mading Digital,</p>
          <br><br>
          <p class="fw-bold text-dark mb-0"><u>PAMBUDIANSYAH</u></p>
          <small class="text-muted font-monospace">NIP: 19880214 202601 1 002</small>
        </div>
      </div>
    </div>

  </div>
</div>

<script>
let selectedCategory = 'ALL';
const reportArticlesData = @json($articles->map(fn($a, $i) => [
    'no' => $i + 1,
    'title' => $a->title,
    'category' => $a->category->name ?? 'Umum',
    'author' => $a->author->name ?? 'Admin',
    'date' => $a->created_at->format('d/m/Y H:i')
]));

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
