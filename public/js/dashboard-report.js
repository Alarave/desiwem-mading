document.addEventListener('DOMContentLoaded', async () => {
  const totalArticlesVal = document.getElementById('total-articles-val');
  const totalCategoriesVal = document.getElementById('total-categories-val');
  const authNavItem = document.getElementById('auth-nav-item');

  // Check auth state
  async function checkAuth() {
    try {
      const state = await API.get('/api/auth/me');
      if (state.loggedIn) {
        authNavItem.innerHTML = `<a href="/admin/dashboard.html" class="btn-login">Panel Admin</a>`;
      }
    } catch (err) {
      console.error("Gagal memeriksa status login:", err);
    }
  }

  // Load metrics data
  async function loadMetrics() {
    try {
      const stats = await API.get('/api/dashboard/metrics');

      // Update counters
      totalArticlesVal.textContent = stats.total_articles;
      totalCategoriesVal.textContent = stats.total_categories;

      // Prepare Chart Data
      const labels = stats.categories_chart.map(item => item.category_name);
      const dataValues = stats.categories_chart.map(item => item.article_count);

      // Render Charts
      renderBarChart(labels, dataValues);
      renderPieChart(labels, dataValues);
    } catch (err) {
      console.error("Gagal memuat metrik grafik:", err);
    }
  }

  function renderBarChart(labels, data) {
    const ctx = document.getElementById('categoryBarChart').getContext('2d');
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [{
          label: 'Jumlah Artikel',
          data: data,
          backgroundColor: 'rgba(31, 108, 159, 0.15)',
          borderColor: '#1f6c9f',
          borderWidth: 2,
          borderRadius: 8,
          hoverBackgroundColor: 'rgba(31, 108, 159, 0.3)'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false
          }
        },
        scales: {
          y: {
            grid: {
              color: 'rgba(0, 0, 0, 0.05)'
            },
            ticks: {
              color: '#64748b',
              font: {
                family: 'Outfit',
                weight: '500'
              },
              stepSize: 1,
              beginAtZero: true
            }
          },
          x: {
            grid: {
              display: false
            },
            ticks: {
              color: '#64748b',
              font: {
                family: 'Outfit',
                weight: '600'
              }
            }
          }
        }
      }
    });
  }

  function renderPieChart(labels, data) {
    const ctx = document.getElementById('categoryPieChart').getContext('2d');
    
    // Check if there are articles at all
    const total = data.reduce((a, b) => a + b, 0);
    if (total === 0) {
      // Draw empty placeholder chart
      new Chart(ctx, {
        type: 'doughnut',
        data: {
          labels: ['Belum ada artikel'],
          datasets: [{
            data: [1],
            backgroundColor: ['rgba(0, 0, 0, 0.03)'],
            borderColor: ['rgba(0, 0, 0, 0.08)'],
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'bottom',
              labels: {
                color: '#64748b',
                font: { family: 'Outfit', weight: '500' }
              }
            }
          }
        }
      });
      return;
    }

    new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: labels,
        datasets: [{
          data: data,
          backgroundColor: [
            'rgba(31, 108, 159, 0.65)',  // Info Sekolah (blue)
            'rgba(204, 164, 59, 0.65)',  // Seni (gold)
            'rgba(52, 211, 153, 0.65)',  // Ilmiah (green)
            'rgba(59, 130, 246, 0.65)',  // Blue placeholder
            'rgba(236, 72, 153, 0.65)'   // Pink placeholder
          ],
          borderColor: [
            '#1f6c9f',
            '#cca43b',
            '#34d399',
            '#3b82f6',
            '#ec4899'
          ],
          borderWidth: 2,
          hoverOffset: 4
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'bottom',
            labels: {
              color: '#475569',
              font: {
                family: 'Outfit',
                weight: '600',
                size: 11
              },
              padding: 15
            }
          }
        }
      }
    });
  }

  checkAuth();
  loadMetrics();
});
