document.addEventListener('DOMContentLoaded', async () => {
  const loginForm = document.getElementById('login-form');
  const usernameInput = document.getElementById('username');
  const passwordInput = document.getElementById('password');
  const errorMessage = document.getElementById('error-message');

  // Hide error message initially
  errorMessage.style.display = 'none';

  // Check if already logged in
  try {
    const auth = await API.get('/api/auth/me');
    if (auth.loggedIn) {
      window.location.href = '/admin/dashboard.html';
    }
  } catch (err) {
    console.error("Gagal memeriksa status login:", err);
  }

  // Handle submit login
  loginForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    errorMessage.style.display = 'none';

    const username = usernameInput.value.trim();
    const password = passwordInput.value;

    if (!username || !password) {
      errorMessage.textContent = 'Username dan password wajib diisi.';
      errorMessage.style.display = 'block';
      return;
    }

    try {
      const response = await API.post('/api/auth/login', { username, password });
      // Redirect to admin dashboard on success
      window.location.href = '/admin/dashboard.html';
    } catch (err) {
      errorMessage.textContent = err.message || 'Gagal masuk. Periksa jaringan Anda.';
      errorMessage.style.display = 'block';
    }
  });
});
