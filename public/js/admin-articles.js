document.addEventListener('DOMContentLoaded', async () => {
  const tableBody = document.getElementById('articles-table-body');
  const alertMsg = document.getElementById('alert-msg');
  const logoutBtn = document.getElementById('logout-btn');
  
  // Modal Elements
  const modal = document.getElementById('article-modal');
  const modalForm = document.getElementById('article-form');
  const modalTitle = document.getElementById('modal-title-text');
  const modalError = document.getElementById('modal-error');
  const closeBtn = document.getElementById('modal-close-btn');
  const cancelBtn = document.getElementById('modal-cancel-btn');
  const addBtn = document.getElementById('btn-add-article');
  
  // Form inputs
  const articleIdInput = document.getElementById('article-id');
  const articleTitleInput = document.getElementById('article-title');
  const articleCategorySelect = document.getElementById('article-category');
  const articleImageInput = document.getElementById('article-image');
  const articleContentInput = document.getElementById('article-content');

  let categoriesList = [];

  // Verify Admin Authentication
  async function checkAuth() {
    try {
      const state = await API.get('/api/auth/me');
      if (!state.loggedIn) {
        window.location.href = '/login.html';
      }
    } catch (err) {
      window.location.href = '/login.html';
    }
  }

  // Load and cache categories for select dropdown
  async function loadCategories() {
    try {
      categoriesList = await API.get('/api/categories');
      // Populate select
      articleCategorySelect.innerHTML = '<option value="">Pilih Kategori</option>';
      categoriesList.forEach(cat => {
        const opt = document.createElement('option');
        opt.value = cat.id;
        opt.textContent = cat.name;
        articleCategorySelect.appendChild(opt);
      });
    } catch (err) {
      console.error("Gagal memuat kategori:", err);
    }
  }

  // Load and Render Articles Table
  async function loadArticles() {
    try {
      const articles = await API.get('/api/articles');
      renderTable(articles);
    } catch (err) {
      tableBody.innerHTML = `
        <tr>
          <td colspan="5" style="text-align: center; color: var(--error);">Gagal memuat artikel.</td>
        </tr>
      `;
    }
  }

  function renderTable(articles) {
    if (articles.length === 0) {
      tableBody.innerHTML = `
        <tr>
          <td colspan="5" style="text-align: center; color: var(--text-muted);">Belum ada artikel terbit.</td>
        </tr>
      `;
      return;
    }

    tableBody.innerHTML = '';
    articles.forEach(art => {
      const tr = document.createElement('tr');
      const dateStr = new Date(art.created_at).toLocaleDateString('id-ID', {
        day: 'numeric', month: 'long', year: 'numeric'
      });
      
      const hasImage = art.image_url && art.image_url.trim() !== '';
      const imgCellContent = hasImage 
        ? `<img src="${art.image_url}" alt="Cover" style="width: 50px; height: 35px; object-fit: cover; border-radius: 6px; border: 1px solid var(--border-color);">`
        : `<div style="width: 50px; height: 35px; background: rgba(255,255,255,0.03); border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 0.6rem; color: var(--text-muted); border: 1px dashed var(--border-color);">N/A</div>`;

      tr.innerHTML = `
        <td>${imgCellContent}</td>
        <td style="font-weight: 600; color: var(--text-main);">${art.title}</td>
        <td><span style="font-size: 0.8rem; font-weight: 700; color: #a78bfa; text-transform: uppercase;">${art.category_name}</span></td>
        <td style="color: var(--text-muted);">${dateStr}</td>
        <td style="text-align: right;">
          <div class="actions-cell" style="justify-content: flex-end;">
            <button class="btn-table btn-edit" title="Edit Artikel">✏️</button>
            <button class="btn-table btn-delete" title="Hapus Artikel">🗑️</button>
          </div>
        </td>
      `;

      // Attach row action listeners
      tr.querySelector('.btn-edit').addEventListener('click', () => openEditModal(art));
      tr.querySelector('.btn-delete').addEventListener('click', () => handleDelete(art));
      
      tableBody.appendChild(tr);
    });
  }

  // Show Alert Box
  function showAlert(msg, type = 'success') {
    alertMsg.textContent = msg;
    alertMsg.className = `alert-message alert-${type}`;
    alertMsg.style.display = 'block';
    setTimeout(() => {
      alertMsg.style.display = 'none';
    }, 4000);
  }

  // Open Modal for Add
  addBtn.addEventListener('click', () => {
    modalForm.reset();
    articleIdInput.value = '';
    modalTitle.textContent = 'Tambah Artikel Baru';
    modalError.style.display = 'none';
    modal.classList.add('active');
  });

  // Open Modal for Edit
  function openEditModal(art) {
    modalForm.reset();
    articleIdInput.value = art.id;
    articleTitleInput.value = art.title;
    articleCategorySelect.value = art.category_id;
    articleImageInput.value = art.image_url || '';
    articleContentInput.value = art.content;
    modalTitle.textContent = 'Edit Artikel';
    modalError.style.display = 'none';
    modal.classList.add('active');
  }

  // Close Modal
  function closeModal() {
    modal.classList.remove('active');
  }

  closeBtn.addEventListener('click', closeModal);
  cancelBtn.addEventListener('click', closeModal);
  modal.addEventListener('click', (e) => {
    if (e.target === modal) closeModal();
  });

  // Handle Form Submit (Add / Edit)
  modalForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    modalError.style.display = 'none';

    const id = articleIdInput.value;
    const title = articleTitleInput.value.trim();
    const category_id = parseInt(articleCategorySelect.value);
    const image_url = articleImageInput.value.trim();
    const content = articleContentInput.value.trim();

    if (!title || !category_id || !content) {
      modalError.textContent = 'Semua field wajib diisi kecuali URL Gambar.';
      modalError.style.display = 'block';
      return;
    }

    try {
      if (id) {
        // Edit article
        await API.put(`/api/articles/${id}`, { title, category_id, image_url, content });
        showAlert("Artikel berhasil diperbarui.");
      } else {
        // Add article
        await API.post('/api/articles', { title, category_id, image_url, content });
        showAlert("Artikel baru berhasil dipublikasikan.");
      }
      closeModal();
      loadArticles();
    } catch (err) {
      modalError.textContent = err.message || 'Terjadi kesalahan.';
      modalError.style.display = 'block';
    }
  });

  // Handle Delete
  async function handleDelete(art) {
    if (confirm(`Apakah Anda yakin ingin menghapus artikel "${art.title}"?`)) {
      try {
        await API.delete(`/api/articles/${art.id}`);
        showAlert("Artikel berhasil dihapus.");
        loadArticles();
      } catch (err) {
        showAlert(err.message, 'error');
      }
    }
  }

  // Handle Logout
  logoutBtn.addEventListener('click', async (e) => {
    e.preventDefault();
    if (confirm("Apakah Anda yakin ingin keluar?")) {
      try {
        await API.post('/api/auth/logout');
        window.location.href = '/login.html';
      } catch (err) {
        alert("Gagal logout: " + err.message);
      }
    }
  });

  await checkAuth();
  await loadCategories();
  await loadArticles();
});
