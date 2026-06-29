document.addEventListener('DOMContentLoaded', async () => {
  const tableBody = document.getElementById('report-table-body');
  const printBtn = document.getElementById('btn-print-report');
  const logoutBtn = document.getElementById('logout-btn');

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

  // Load and Render Report Data
  async function loadReport() {
    try {
      const articles = await API.get('/api/articles');
      renderTable(articles);
    } catch (err) {
      tableBody.innerHTML = `
        <tr>
          <td colspan="5" style="text-align: center; color: var(--error);">Gagal memuat data laporan.</td>
        </tr>
      `;
    }
  }

  function renderTable(articles) {
    if (articles.length === 0) {
      tableBody.innerHTML = `
        <tr>
          <td colspan="5" style="text-align: center; color: var(--text-muted);">Belum ada artikel untuk dilaporkan.</td>
        </tr>
      `;
      return;
    }

    tableBody.innerHTML = '';
    articles.forEach((art, index) => {
      const tr = document.createElement('tr');
      const dateStr = new Date(art.created_at).toLocaleDateString('id-ID', {
        day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit'
      });

      tr.innerHTML = `
        <td style="font-weight: 700; color: var(--text-muted);">${index + 1}</td>
        <td style="font-weight: 600; color: var(--text-main);">${art.title}</td>
        <td><span style="font-size: 0.8rem; font-weight: 700; color: #a78bfa; text-transform: uppercase;">${art.category_name}</span></td>
        <td style="color: var(--text-muted);">${dateStr}</td>
        <td style="font-weight: 500; color: var(--text-main);">${art.author_name || 'Admin'}</td>
      `;
      tableBody.appendChild(tr);
    });
  }

  // Handle Print Action
  printBtn.addEventListener('click', () => {
    window.print();
  });

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
  await loadReport();
});
