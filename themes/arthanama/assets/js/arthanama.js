/**
 * ArthaNama Theme — arthanama.js
 * Mobile menu · Search overlay · Sticky header · Reading progress · Back to top
 */
(function () {
  'use strict';

  /* ── DOM refs ─────────────────────────────────────────────────────── */
  const header      = document.getElementById('site-header');
  const menuToggle  = document.getElementById('menu-toggle');
  const primaryNav  = document.getElementById('primary-nav');
  const searchToggle= document.getElementById('search-toggle');
  const searchOverlay = document.getElementById('search-overlay');
  const searchInput = searchOverlay && searchOverlay.querySelector('input[type="search"]');
  const progress    = document.getElementById('reading-progress');
  const backToTop   = document.getElementById('back-to-top');

  /* ── Sticky header ─────────────────────────────────────────────────── */
  let lastScroll = 0;
  const SCROLL_THRESHOLD = 80;

  function onScroll() {
    const y = window.scrollY;

    // Sticky shadow
    if (header) {
      header.classList.toggle('scrolled', y > 10);
    }

    // Reading progress bar
    if (progress) {
      const article = document.querySelector('.post-content');
      if (article) {
        const rect   = article.getBoundingClientRect();
        const total  = article.offsetHeight;
        const scrolled = Math.max(0, -rect.top);
        const pct    = Math.min(100, (scrolled / total) * 100);
        progress.style.width = pct + '%';
      }
    }

    // Back to top button
    if (backToTop) {
      backToTop.classList.toggle('visible', y > 400);
    }

    lastScroll = y;
  }

  window.addEventListener('scroll', onScroll, { passive: true });

  /* ── Mobile menu ───────────────────────────────────────────────────── */
  if (menuToggle && primaryNav) {
    menuToggle.addEventListener('click', function () {
      const isOpen = primaryNav.classList.toggle('open');
      menuToggle.setAttribute('aria-expanded', isOpen);
      menuToggle.innerHTML = isOpen
        ? '<svg aria-hidden="true"><use href="#icon-close"/></svg>'
        : '<svg aria-hidden="true"><use href="#icon-menu"/></svg>';
      document.body.style.overflow = isOpen ? 'hidden' : '';
    });

    // Close on outside click
    document.addEventListener('click', function (e) {
      if (primaryNav.classList.contains('open') &&
          !primaryNav.contains(e.target) &&
          !menuToggle.contains(e.target)) {
        primaryNav.classList.remove('open');
        menuToggle.setAttribute('aria-expanded', 'false');
        menuToggle.innerHTML = '<svg aria-hidden="true"><use href="#icon-menu"/></svg>';
        document.body.style.overflow = '';
      }
    });
  }

  /* ── Search overlay ────────────────────────────────────────────────── */
  if (searchToggle && searchOverlay) {
    function openSearch() {
      searchOverlay.classList.add('open');
      searchToggle.setAttribute('aria-expanded', 'true');
      if (searchInput) searchInput.focus();
      document.body.style.overflow = 'hidden';
    }
    function closeSearch() {
      searchOverlay.classList.remove('open');
      searchToggle.setAttribute('aria-expanded', 'false');
      document.body.style.overflow = '';
    }

    searchToggle.addEventListener('click', openSearch);
    searchOverlay.addEventListener('click', function (e) {
      if (e.target === searchOverlay) closeSearch();
    });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && searchOverlay.classList.contains('open')) closeSearch();
    });
  }

  /* ── Back to top ───────────────────────────────────────────────────── */
  if (backToTop) {
    backToTop.addEventListener('click', function () {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  /* ── Market ticker duplicate for seamless loop ─────────────────────── */
  // The PHP already duplicates the ticker items; JS ensures smooth restart
  const tickers = document.querySelectorAll('.market-ticker, .breaking-ticker');
  tickers.forEach(function (ticker) {
    ticker.addEventListener('animationiteration', function () {
      // Reset cleanly — the CSS keyframe already handles this
    });
  });

  /* ── Lazy image fade-in ─────────────────────────────────────────────── */
  if ('IntersectionObserver' in window) {
    const imgObs = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.style.opacity = '1';
          imgObs.unobserve(entry.target);
        }
      });
    }, { rootMargin: '100px' });

    document.querySelectorAll('.post-card-image img, .news-item-image img').forEach(function (img) {
      img.style.opacity = '0';
      img.style.transition = 'opacity 0.4s ease';
      if (img.complete) {
        img.style.opacity = '1';
      } else {
        imgObs.observe(img);
        img.addEventListener('load', function () { img.style.opacity = '1'; });
      }
    });
  }

  /* ── Share buttons — track analytics (console for now) ─────────────── */
  document.querySelectorAll('.share-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
      console.log('[ArthaNama] Share:', btn.className.replace('share-btn ', ''));
    });
  });

})();
