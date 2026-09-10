@extends('layouts.admin')

@section('title', 'Edit Artikel #' . $article->id . ' — DeSiWeM')

@section('content')
<div class="mb-4">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.articles') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1.5 d-inline-flex align-items-center gap-1.5" style="border-color: var(--base-300); color: var(--base-700);">
                <i class="bi bi-arrow-left"></i>
                <span>Kembali</span>
            </a>
            <div>
                <div class="d-flex align-items-center gap-2">
                    <h4 class="fw-bold mb-0" style="font-family: var(--font-display); letter-spacing: -0.02em; color: var(--base-900);">Edit Artikel</h4>
                    <span class="badge bg-slate-200 text-slate-700 font-monospace" style="background: var(--base-100); color: var(--base-800);">#{{ $article->id }}</span>
                </div>
                <small class="text-muted">Terakhir diperbarui pada {{ $article->updated_at->format('d M Y, H:i') }} • Penulis: {{ $article->author->name ?? 'Admin' }}</small>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('articles.update', $article->id) }}" method="POST" id="articleEditForm">
    @csrf
    @method('PUT')

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4" role="alert">
            <h6 class="fw-bold mb-2"><i class="bi bi-exclamation-triangle-fill me-1"></i> Mohon periksa kembali form berikut:</h6>
            <ul class="mb-0 ps-3 small">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="carbon-editor-layout">
        <!-- Main Writing Area -->
        <div class="carbon-editor-main">
            <!-- Title Input -->
            <div>
                <input type="text" 
                       name="title" 
                       id="articleTitle"
                       class="carbon-editor-title-field" 
                       placeholder="Tuliskan judul artikel..."
                       value="{{ old('title', $article->title) }}" 
                       required>
            </div>

            <!-- Content Workspace Card -->
            <div class="carbon-editor-card">
                <!-- Header Toolbar & Tab Switcher -->
                <div class="carbon-editor-header-bar">
                    <div class="carbon-editor-tabs">
                        <button type="button" class="carbon-editor-tab-btn active" id="tabWriteBtn" onclick="switchEditorTab('write')">
                            <i class="bi bi-pen"></i> Tulis
                        </button>
                        <button type="button" class="carbon-editor-tab-btn" id="tabPreviewBtn" onclick="switchEditorTab('preview')">
                            <i class="bi bi-eye"></i> Pratinjau
                        </button>
                    </div>

                    <div class="d-flex align-items-center gap-2 small text-muted">
                        <span id="charCount">0 kata</span>
                        <span>•</span>
                        <span id="readTime">1 mnt baca</span>
                    </div>
                </div>

                <!-- Markdown Helper Toolbar -->
                <div class="carbon-editor-toolbar" id="editorToolbar">
                    <button type="button" class="carbon-toolbar-btn" onclick="insertFormat('h1')" title="Heading 1"><i class="bi bi-type-h1"></i></button>
                    <button type="button" class="carbon-toolbar-btn" onclick="insertFormat('h2')" title="Heading 2"><i class="bi bi-type-h2"></i></button>
                    <div class="carbon-toolbar-divider"></div>
                    <button type="button" class="carbon-toolbar-btn" onclick="insertFormat('bold')" title="Tebal (Bold)"><i class="bi bi-type-bold"></i></button>
                    <button type="button" class="carbon-toolbar-btn" onclick="insertFormat('italic')" title="Miring (Italic)"><i class="bi bi-type-italic"></i></button>
                    <div class="carbon-toolbar-divider"></div>
                    <button type="button" class="carbon-toolbar-btn" onclick="insertFormat('quote')" title="Kutipan (Quote)"><i class="bi bi-quote"></i></button>
                    <button type="button" class="carbon-toolbar-btn" onclick="insertFormat('list')" title="Daftar Poin"><i class="bi bi-list-ul"></i></button>
                    <button type="button" class="carbon-toolbar-btn" onclick="insertFormat('link')" title="Tautan Link"><i class="bi bi-link-45deg"></i></button>
                    <button type="button" class="carbon-toolbar-btn" onclick="insertFormat('code')" title="Blok Kode"><i class="bi bi-code-slash"></i></button>
                </div>

                <!-- Write View -->
                <div id="writePane">
                    <textarea name="content" 
                              id="articleContent" 
                              class="carbon-editor-textarea" 
                              placeholder="Ketikkan narasi artikel Anda di sini..."
                              required>{{ old('content', $article->content) }}</textarea>
                </div>

                <!-- Live Preview View -->
                <div id="previewPane" class="d-none">
                    <div class="carbon-editor-preview-container carbon-prose" id="previewContainer"></div>
                </div>
            </div>
        </div>

        <!-- Sidebar Settings & Actions -->
        <div class="carbon-editor-meta-panel">
            <!-- Publishing Actions -->
            <div class="carbon-meta-box">
                <div class="carbon-meta-title">
                    <i class="bi bi-check2-circle text-accent"></i> Simpan Perubahan
                </div>
                <p class="small text-muted mb-3">Perubahan yang disimpan akan langsung diupdate pada mading publik.</p>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary-custom py-2.5 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-save2"></i>
                        <span>Simpan Perubahan</span>
                    </button>
                    <a href="{{ route('mading.show', $article->id) }}" target="_blank" class="btn btn-sm btn-outline-secondary py-2" style="border-color: var(--base-200); color: var(--base-700);">
                        <i class="bi bi-box-arrow-up-right me-1"></i> Lihat di Mading
                    </a>
                    <a href="{{ route('admin.articles') }}" class="btn btn-sm btn-link text-muted text-decoration-none">
                        Batal
                    </a>
                </div>
            </div>

            <!-- Category Selector -->
            <div class="carbon-meta-box">
                <div class="carbon-meta-title">
                    <i class="bi bi-folder2-open"></i> Kategori Mading
                </div>
                <select name="category_id" id="categorySelect" class="form-select py-2" required>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ (old('category_id', $article->category_id) == $cat->id) ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Cover Image & Live Preview -->
            <div class="carbon-meta-box">
                <div class="carbon-meta-title">
                    <i class="bi bi-image"></i> Gambar Sampul
                </div>
                <div class="mb-3">
                    <input type="text" 
                           name="image_url" 
                           id="imageUrlInput" 
                           class="form-control form-control-sm py-2" 
                           placeholder="/images/foto.png atau URL eksternal" 
                           value="{{ old('image_url', $article->image_url) }}">
                </div>
                <div id="imgPreviewWrapper">
                    <div id="imgPlaceholder" class="carbon-img-placeholder {{ $article->image_url ? 'd-none' : '' }}">
                        <i class="bi bi-card-image fs-3"></i>
                        <span>Pratinjau gambar sampul</span>
                    </div>
                    <img id="imgPreview" 
                         src="{{ $article->image_url }}" 
                         alt="Pratinjau Sampul" 
                         class="carbon-img-preview {{ $article->image_url ? '' : 'd-none' }}">
                </div>
            </div>

            <!-- Article Details Stat -->
            <div class="carbon-meta-box">
                <div class="carbon-meta-title">
                    <i class="bi bi-info-circle"></i> Metadata Artikel
                </div>
                <div class="carbon-stat-pill">
                    <span>ID Artikel:</span>
                    <strong>#{{ $article->id }}</strong>
                </div>
                <div class="carbon-stat-pill">
                    <span>Dibuat:</span>
                    <strong>{{ $article->created_at->format('d M Y') }}</strong>
                </div>
                <div class="carbon-stat-pill">
                    <span>Penulis:</span>
                    <strong>{{ $article->author->name ?? 'Admin' }}</strong>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Marked.js for fast, safe markdown client-side rendering -->
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script>
    const contentInput = document.getElementById('articleContent');
    const writePane = document.getElementById('writePane');
    const previewPane = document.getElementById('previewPane');
    const previewContainer = document.getElementById('previewContainer');
    const tabWriteBtn = document.getElementById('tabWriteBtn');
    const tabPreviewBtn = document.getElementById('tabPreviewBtn');
    const editorToolbar = document.getElementById('editorToolbar');
    const charCount = document.getElementById('charCount');
    const readTime = document.getElementById('readTime');
    const imageUrlInput = document.getElementById('imageUrlInput');
    const imgPreview = document.getElementById('imgPreview');
    const imgPlaceholder = document.getElementById('imgPlaceholder');

    // Switch Tab Mode (Write vs Preview)
    function switchEditorTab(mode) {
        if (mode === 'preview') {
            tabWriteBtn.classList.remove('active');
            tabPreviewBtn.classList.add('active');
            writePane.classList.add('d-none');
            previewPane.classList.remove('d-none');
            editorToolbar.style.opacity = '0.4';
            editorToolbar.style.pointerEvents = 'none';

            // Render Markdown
            const text = contentInput.value.trim();
            if (text) {
                previewContainer.innerHTML = marked.parse(text);
            } else {
                previewContainer.innerHTML = '<p class="text-muted fst-italic">Belum ada konten yang ditulis...</p>';
            }
        } else {
            tabPreviewBtn.classList.remove('active');
            tabWriteBtn.classList.add('active');
            previewPane.classList.add('d-none');
            writePane.classList.remove('d-none');
            editorToolbar.style.opacity = '1';
            editorToolbar.style.pointerEvents = 'auto';
            contentInput.focus();
        }
    }

    // Markdown Quick Insertion
    function insertFormat(type) {
        const textarea = contentInput;
        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const selected = textarea.value.substring(start, end);
        let replacement = '';

        switch (type) {
            case 'h1':
                replacement = `\n# ${selected || 'Judul Utama'}\n`;
                break;
            case 'h2':
                replacement = `\n## ${selected || 'Sub Judul'}\n`;
                break;
            case 'bold':
                replacement = `**${selected || 'teks tebal'}**`;
                break;
            case 'italic':
                replacement = `*${selected || 'teks miring'}*`;
                break;
            case 'quote':
                replacement = `\n> ${selected || 'Kutipan penting...'}\n`;
                break;
            case 'list':
                replacement = `\n- ${selected || 'Poin daftar'}\n`;
                break;
            case 'link':
                replacement = `[${selected || 'Judul Tautan'}](https://example.com)`;
                break;
            case 'code':
                replacement = `\n\`\`\`\n${selected || '// Kode di sini'}\n\`\`\`\n`;
                break;
        }

        textarea.setRangeText(replacement, start, end, 'select');
        updateStats();
        textarea.focus();
    }

    // Live Stats (Word count & Reading Time)
    function updateStats() {
        const text = contentInput.value.trim();
        if (!text) {
            charCount.textContent = '0 kata';
            readTime.textContent = '1 mnt baca';
            return;
        }
        const words = text.split(/\s+/).filter(w => w.length > 0).length;
        charCount.textContent = `${words} kata`;
        const minutes = Math.max(1, Math.ceil(words / 200));
        readTime.textContent = `${minutes} mnt baca`;
    }

    // Cover Image Live Preview
    function updateImagePreview() {
        const url = imageUrlInput.value.trim();
        if (url) {
            imgPreview.src = url;
            imgPreview.onload = () => {
                imgPreview.classList.remove('d-none');
                imgPlaceholder.classList.add('d-none');
            };
            imgPreview.onerror = () => {
                imgPreview.classList.add('d-none');
                imgPlaceholder.classList.remove('d-none');
            };
        } else {
            imgPreview.classList.add('d-none');
            imgPlaceholder.classList.remove('d-none');
        }
    }

    contentInput.addEventListener('input', updateStats);
    imageUrlInput.addEventListener('input', updateImagePreview);

    // Initial setup
    updateStats();
    if (imageUrlInput.value) {
        updateImagePreview();
    }
</script>
@endsection
