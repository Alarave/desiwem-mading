document.addEventListener('DOMContentLoaded', () => {
  let categories = [];
  let articles = [];
  let selectedCategoryId = 'all';
  let searchQuery = '';

  const boardGrid = document.getElementById('board-grid');
  const filterTagsContainer = document.getElementById('filter-tags');
  const searchInput = document.getElementById('search-input');
  const authNavItem = document.getElementById('auth-nav-item');

  // Modal elements
  const detailModal = document.getElementById('detail-modal');
  const modalClose = document.getElementById('modal-close');
  const modalTag = document.getElementById('modal-tag');
  const modalDate = document.getElementById('modal-date');
  const modalAvatar = document.getElementById('modal-avatar');
  const modalAuthor = document.getElementById('modal-author');
  const modalTitle = document.getElementById('modal-title');
  const modalImgContainer = document.getElementById('modal-img-container');
  const modalImg = document.getElementById('modal-img');
  const modalContent = document.getElementById('modal-content');

  // Check auth state
  async function checkAuth() {
    try {
      const state = await API.get('/api/auth/me');
      if (state.loggedIn) {
        authNavItem.innerHTML = `<a href="/admin/dashboard.html" class="btn-login" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">Panel Admin</a>`;
      }
    } catch (err) {
      console.error("Gagal memeriksa status login:", err);
    }
  }

  // Load categories
  async function loadCategories() {
    try {
      categories = await API.get('/api/categories');
      renderCategoryFilters();
    } catch (err) {
      console.error("Gagal memuat kategori:", err);
    }
  }

  function getCategoryClass(name) {
    const norm = name.toLowerCase().trim();
    if (norm.includes('info sekolah') || norm.includes('sekolah')) return 'tag-info-sekolah';
    if (norm.includes('seni')) return 'tag-seni';
    if (norm.includes('ilmiah')) return 'tag-ilmiah';
    return 'tag-custom';
  }

  // Render category filter buttons
  function renderCategoryFilters() {
    // Keep 'Semua' button
    filterTagsContainer.innerHTML = `<button class="filter-btn ${selectedCategoryId === 'all' ? 'active' : ''}" data-category-id="all">Semua</button>`;
    
    categories.forEach(cat => {
      const btn = document.createElement('button');
      btn.className = `filter-btn ${selectedCategoryId == cat.id ? 'active' : ''}`;
      btn.dataset.categoryId = cat.id;
      btn.textContent = cat.name;
      filterTagsContainer.appendChild(btn);
    });

    // Add click listeners
    filterTagsContainer.querySelectorAll('.filter-btn').forEach(btn => {
      btn.addEventListener('click', (e) => {
        filterTagsContainer.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        e.target.classList.add('active');
        selectedCategoryId = e.target.dataset.categoryId;
        loadArticles();
      });
    });
  }

  // Load articles
  async function loadArticles() {
    boardGrid.innerHTML = `
      <div class="empty-state">
        <div class="empty-icon">⏳</div>
        <p>Memuat artikel mading...</p>
      </div>
    `;

    try {
      let url = '/api/articles?';
      if (selectedCategoryId !== 'all') {
        url += `category_id=${selectedCategoryId}&`;
      }
      if (searchQuery) {
        url += `search=${encodeURIComponent(searchQuery)}&`;
      }

      articles = await API.get(url);
      renderArticles();
    } catch (err) {
      boardGrid.innerHTML = `
        <div class="empty-state">
          <div class="empty-icon">⚠️</div>
          <p>Gagal memuat artikel mading. Coba muat ulang halaman.</p>
        </div>
      `;
    }
  }

  // Render article list
  function renderArticles() {
    if (articles.length === 0) {
      boardGrid.innerHTML = `
        <div class="empty-state">
          <div class="empty-icon">📦</div>
          <p>Belum ada artikel mading yang cocok.</p>
        </div>
      `;
      return;
    }

    boardGrid.innerHTML = '';
    articles.forEach(art => {
      const card = document.createElement('div');
      card.className = 'board-card';
      card.style.cursor = 'pointer';

      const tagClass = getCategoryClass(art.category_name);
      const dateStr = new Date(art.created_at).toLocaleDateString('id-ID', {
        day: 'numeric', month: 'long', year: 'numeric'
      });

      const firstLetter = art.author_name ? art.author_name.charAt(0).toUpperCase() : 'A';
      
      const hasImage = art.image_url && art.image_url.trim() !== '';

      card.innerHTML = `
        ${hasImage ? `
          <div class="card-img-wrapper">
            <span class="card-tag ${tagClass}">${art.category_name}</span>
            <img src="${art.image_url}" alt="${art.title}" class="card-img">
          </div>
        ` : `
          <div class="card-img-wrapper" style="height: 60px; background: linear-gradient(135deg, #f1f5f9 0%, rgba(31, 108, 159, 0.05) 100%);">
            <span class="card-tag ${tagClass}">${art.category_name}</span>
          </div>
        `}
        <div class="card-body">
          <div class="card-date">
            <span>📅</span> ${dateStr}
          </div>
          <h3 class="card-title">${art.title}</h3>
          <p class="card-excerpt">${art.content}</p>
          <div class="card-footer">
            <div class="author-info">
              <div class="author-avatar">${firstLetter}</div>
              <span>${art.author_name || 'Admin'}</span>
            </div>
            <span class="card-link-text">Baca Selengkapnya &rarr;</span>
          </div>
        </div>
      `;

      card.addEventListener('click', () => openDetail(art));
      boardGrid.appendChild(card);
    });

    // Stagger animation on cards via GSAP
    if (window.animateInjectedCards) {
      window.animateInjectedCards();
    }
  }

  // Open detail modal
  function openDetail(art) {
    const tagClass = getCategoryClass(art.category_name);
    modalTag.className = `card-tag ${tagClass}`;
    modalTag.textContent = art.category_name;

    const dateStr = new Date(art.created_at).toLocaleDateString('id-ID', {
      day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit'
    });
    modalDate.textContent = dateStr;

    modalAuthor.textContent = art.author_name || 'Admin';
    modalAvatar.textContent = art.author_name ? art.author_name.charAt(0).toUpperCase() : 'A';
    modalTitle.textContent = art.title;
    modalContent.textContent = art.content;

    if (art.image_url && art.image_url.trim() !== '') {
      modalImg.src = art.image_url;
      modalImgContainer.style.display = 'block';
    } else {
      modalImgContainer.style.display = 'none';
    }

    detailModal.classList.add('active');
  }

  // Close modal
  function closeModal() {
    detailModal.classList.remove('active');
  }

  modalClose.addEventListener('click', closeModal);
  detailModal.addEventListener('click', (e) => {
    if (e.target === detailModal) closeModal();
  });

  // Search input event with debounce
  let searchTimeout;
  searchInput.addEventListener('input', (e) => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
      searchQuery = e.target.value;
      loadArticles();
    }, 300);
  });

  // Initial load
  checkAuth();
  loadCategories();
  loadArticles();
});
