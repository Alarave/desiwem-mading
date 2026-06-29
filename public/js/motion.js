// Motion & Spotlight Interactions with GSAP
document.addEventListener('DOMContentLoaded', () => {
  // 1. Mouse Spotlight Tracking (Delegation for dynamic cards)
  document.addEventListener('mousemove', (e) => {
    const card = e.target.closest('.board-card, .metric-card, .chart-card, .auth-card');
    if (card) {
      const rect = card.getBoundingClientRect();
      const x = e.clientX - rect.left;
      const y = e.clientY - rect.top;
      card.style.setProperty('--mouse-x', `${x}px`);
      card.style.setProperty('--mouse-y', `${y}px`);
    }
  });

  // 2. GSAP Entrance Animations
  if (typeof gsap !== 'undefined') {
    // Register ScrollTrigger if loaded
    if (typeof ScrollTrigger !== 'undefined') {
      gsap.registerPlugin(ScrollTrigger);
    }

    // Header Pill Entrance
    gsap.from('.nav-container', {
      y: -50,
      opacity: 0,
      duration: 1.2,
      ease: 'power4.out',
      delay: 0.2
    });

    // Hero Text Staggered Reveal
    const heroTimeline = gsap.timeline();
    if (document.querySelector('.hero-section h1')) {
      heroTimeline.from('.hero-section h1', {
        y: 40,
        opacity: 0,
        duration: 1,
        ease: 'power3.out'
      });
    }
    if (document.querySelector('.hero-section p')) {
      heroTimeline.from('.hero-section p', {
        y: 20,
        opacity: 0,
        duration: 0.8,
        ease: 'power3.out'
      }, '-=0.6');
    }
    if (document.querySelector('.hero-visual-card')) {
      heroTimeline.from('.hero-visual-card', {
        scale: 0.8,
        rotation: 0,
        opacity: 0,
        duration: 1.2,
        ease: 'elastic.out(1, 0.75)'
      }, '-=1');
    }

    // Controls Bar Entrance
    if (document.querySelector('.controls-bar')) {
      gsap.from('.controls-bar', {
        y: 30,
        opacity: 0,
        duration: 0.8,
        ease: 'power3.out'
      }, '-=0.4');
    }
  }
});

// Helper function to animate dynamically injected cards
window.animateInjectedCards = () => {
  if (typeof gsap !== 'undefined' && document.querySelectorAll('.board-card').length > 0) {
    gsap.from('.board-card', {
      y: 40,
      opacity: 0,
      duration: 0.8,
      stagger: 0.1,
      ease: 'power3.out',
      clearProps: 'transform,opacity' // allows hover states to work without override
    });
  }
};
