document.addEventListener('DOMContentLoaded', async () => {
  const tableBody = document.getElementById('categories-table-body');
  const alertMsg = document.getElementById('alert-msg');
  const logoutBtn = document.getElementById('logout-btn');
  
  // Modal Elements
  const modal = document.getElementById('category-modal');
  const modalForm = document.getElementById('category-form');
  const modalTitle = document.getElementById('modal-title-text');
  const modalError = document.getElementById('modal-error');
  const closeBtn = document.getElementById('modal-close-btn');
  const cancelBtn = document.getElementById('modal-cancel-btn');
  const addBtn = document.getElementById('btn-add-category');
  
  // Form inputs
  const categoryIdInput = document.getElementById('category-id');
  const categoryNameInput = document.getElementById('category-name');
  const categoryDescInput = document.getElementById('category-desc');

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

  // Load and Render Categories Table
  async function loadCategories() {
    try {
      const categories = await API.get('/api/categories');
      renderTable(categories);
    } catch (err) {
      tableBody.innerHTML = `
        <tr>
          <td colspan="4" style="text-align: center; color: var(--error);">Gagal memuat kategori.</td>
        </tr>
      `;
    }
  }

  function renderTable(categories) {
    if (categories.length === 0) {
      tableBody.innerHTML = `
        <tr>
          <td colspan="4" style="text-align: center; color: var(--text-muted);">Belum ada kategori terdaftar.</td>
        </tr>
      `;
      return;
    }

    tableBody.innerHTML = '';
    categories.forEach(cat => {
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td style="font-weight: 700; color: var(--text-muted);">${cat.id}</td>
        <td style="font-weight: 600; color: var(--text-main);">${cat.name}</td>
        <td style="color: var(--text-muted);">${cat.description || '-'}</td>
        <td style="text-align: right;">
          <div class="actions-cell" style="justify-content: flex-end;">
            <button class="btn-table btn-edit" data-id="${cat.id}" title="Edit Kategori">✏️</button>
            <button class="btn-table btn-delete" data-id="${cat.id}" title="Hapus Kategori">🗑️</button>
          </div>
        </td>
      `;

      // Attach row action listeners
      tr.querySelector('.btn-edit').addEventListener('click', () => openEditModal(cat));
      tr.querySelector('.btn-delete').addEventListener('click', () => handleDelete(cat));
      
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
    categoryIdInput.value = '';
    modalTitle.textContent = 'Tambah Kategori Baru';
    modalError.style.display = 'none';
    modal.classList.add('active');
  });

  // Open Modal for Edit
  function openEditModal(cat) {
    modalForm.reset();
    categoryIdInput.value = cat.id;
    categoryNameInput.value = cat.name;
    categoryDescInput.value = cat.description || '';
    modalTitle.textContent = 'Edit Kategori';
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

    const id = categoryIdInput.value;
    const name = categoryNameInput.value.trim();
    const description = categoryDescInput.value.trim();

    try {
      if (id) {
        // Edit category
        await API.put(`/api/categories/${id}`, { name, description });
        showAlert("Kategori berhasil diperbarui.");
      } else {
        // Add category
        await API.post('/api/categories', { name, description });
        showAlert("Kategori baru berhasil ditambahkan.");
      }
      closeModal();
      loadCategories();
    } catch (err) {
      modalError.textContent = err.message || 'Terjadi kesalahan.';
      modalError.style.display = 'block';
    }
  });

  // Handle Delete
  async function handleDelete(cat) {
    if (confirm(`Apakah Anda yakin ingin menghapus kategori "${cat.name}"?`)) {
      try {
        await API.delete(`/api/categories/${cat.id}`);
        showAlert("Kategori berhasil dihapus.");
        loadCategories();
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
});
