/**
 * Vercel Tabs Engine (Native JavaScript)
 * Sliding hover highlight & active indicator line
 * Ponytail principle: zero dependencies, high performance, robust
 */
(function () {
  function setupVercelTabs(container) {
    if (!container || container.dataset.vercelTabsInit) return;
    container.dataset.vercelTabsInit = "true";

    const wrapper = container.classList.contains("vercel-tabs-wrapper")
      ? container
      : (container.querySelector(".vercel-tabs-wrapper") || container);

    let hoverPill = wrapper.querySelector(".vercel-tab-hover-pill");
    let activeIndicator = wrapper.querySelector(".vercel-tab-active-indicator");

    if (!hoverPill) {
      hoverPill = document.createElement("div");
      hoverPill.className = "vercel-tab-hover-pill";
      wrapper.prepend(hoverPill);
    }

    if (!activeIndicator) {
      activeIndicator = document.createElement("div");
      activeIndicator.className = "vercel-tab-active-indicator";
      wrapper.appendChild(activeIndicator);
    }

    const tabs = Array.from(wrapper.querySelectorAll(".vercel-tab-link"));
    if (tabs.length === 0) return;

    let activeTab = tabs.find(t => t.classList.contains("active")) || tabs[0];

    function updateActive(element) {
      if (!element || !activeIndicator) return;
      activeIndicator.style.left = element.offsetLeft + "px";
      activeIndicator.style.width = element.offsetWidth + "px";
      activeIndicator.style.opacity = "1";
    }

    function setHover(element) {
      if (!element || !hoverPill) return;
      hoverPill.style.left = element.offsetLeft + "px";
      hoverPill.style.width = element.offsetWidth + "px";
      hoverPill.style.opacity = "1";
    }

    function clearHover() {
      if (!hoverPill) return;
      hoverPill.style.opacity = "0";
    }

    tabs.forEach(tab => {
      tab.addEventListener("mouseenter", () => setHover(tab));
      tab.addEventListener("focus", () => setHover(tab));
      tab.addEventListener("mouseleave", clearHover);
      tab.addEventListener("blur", clearHover);
      tab.addEventListener("click", function () {
        tabs.forEach(t => t.classList.remove("active"));
        tab.classList.add("active");
        updateActive(tab);
      });
    });

    wrapper.addEventListener("mouseleave", clearHover);

    // Position indicator after paint and after fonts load
    function refreshActive() {
      const currentActive = tabs.find(t => t.classList.contains("active")) || tabs[0];
      updateActive(currentActive);
    }

    setTimeout(refreshActive, 60);
    setTimeout(refreshActive, 300);

    if (document.fonts && document.fonts.ready) {
      document.fonts.ready.then(refreshActive);
    }

    window.addEventListener("resize", refreshActive);
  }

  function initAllVercelTabs() {
    document.querySelectorAll(".vercel-tabs-nav, .vercel-tabs-wrapper").forEach(setupVercelTabs);
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initAllVercelTabs);
  } else {
    initAllVercelTabs();
  }

  window.initVercelTabs = initAllVercelTabs;
})();
