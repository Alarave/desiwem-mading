document.addEventListener('DOMContentLoaded', async () => {
  const totalArticlesCnt = document.getElementById('total-articles-cnt');
  const totalCategoriesCnt = document.getElementById('total-categories-cnt');
  const recentArticlesList = document.getElementById('recent-articles-list');
  const logoutBtn = document.getElementById('logout-btn');

  // Verify Admin Authentication
  async function checkAuth() {
    try {
      const state = await API.get('/api/auth/me');
      if (!state.loggedIn) {
        window.location.href = '/login.html';
      }
    } catch (err) {
      console.error(err);
      window.location.href = '/login.html';
    }
  }

  // Load Dashboard Data
  async function loadDashboard() {
    try {
      const stats = await API.get('/api/dashboard/metrics');
      
      // Update counters
      totalArticlesCnt.textContent = stats.total_articles;
      totalCategoriesCnt.textContent = stats.total_categories;

      // Render recent articles table
      if (stats.latest_articles.length === 0) {
        recentArticlesList.innerHTML = `
          <tr>
            <td colspan="3" style="text-align: center; color: var(--text-muted);">Belum ada artikel mading.</td>
          </tr>
        `;
        return;
      }

      recentArticlesList.innerHTML = '';
      stats.latest_articles.forEach(art => {
        const tr = document.createElement('tr');
        const dateStr = new Date(art.created_at).toLocaleDateString('id-ID', {
          day: 'numeric', month: 'long', year: 'numeric'
        });

        tr.innerHTML = `
          <td style="font-weight: 600; color: var(--text-main);">${art.title}</td>
          <td><span style="font-size: 0.8rem; font-weight: 700; color: #a78bfa; text-transform: uppercase;">${art.category_name}</span></td>
          <td style="color: var(--text-muted);">${dateStr}</td>
        `;
        recentArticlesList.appendChild(tr);
      });

    } catch (err) {
      console.error("Gagal memuat statistik dashboard:", err);
    }
  }

  // Handle Logout
  logoutBtn.addEventListener('click', async (e) => {
    e.preventDefault();
    if (confirm("Apakah Anda yakin ingin keluar dari panel admin?")) {
      try {
        await API.post('/api/auth/logout');
        window.location.href = '/login.html';
      } catch (err) {
        alert("Gagal logout: " + err.message);
      }
    }
  });

  await checkAuth();
  await loadDashboard();
});
