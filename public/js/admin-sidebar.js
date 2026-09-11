/**
 * DeSiWeM Admin Sidebar Controller
 * Handles responsive sidebar drawer toggling, ESC key listener, and accessibility attributes.
 */
document.addEventListener('DOMContentLoaded', function () {
  const mobileDrawer = document.getElementById('acetMobileDrawer');
  const mobileToggle = document.getElementById('acetMobileToggle');
  const mobileClose = document.getElementById('acetMobileClose');

  // Open mobile drawer
  function openMobileDrawer() {
    if (!mobileDrawer) return;
    mobileDrawer.classList.add('is-open');
    mobileDrawer.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
  }

  // Close mobile drawer
  function closeMobileDrawer() {
    if (!mobileDrawer) return;
    mobileDrawer.classList.remove('is-open');
    mobileDrawer.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
  }

  if (mobileToggle) {
    mobileToggle.addEventListener('click', openMobileDrawer);
  }

  if (mobileClose) {
    mobileClose.addEventListener('click', closeMobileDrawer);
  }

  // Close drawer when clicking nav links on mobile
  if (mobileDrawer) {
    const mobileLinks = mobileDrawer.querySelectorAll('.acet-nav-link');
    mobileLinks.forEach(function (link) {
      link.addEventListener('click', function () {
        closeMobileDrawer();
      });
    });
  }

  // Close on ESC key
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && mobileDrawer && mobileDrawer.classList.contains('is-open')) {
      closeMobileDrawer();
    }
  });
});
