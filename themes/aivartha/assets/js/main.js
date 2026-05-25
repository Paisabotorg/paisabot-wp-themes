/**
 * AI Vartha — main.js
 * Production-grade interactive layer for the theme.
 */

(function () {
  'use strict';

  /* ── DOM READY ──────────────────────────────────────────────────────────── */
  function ready(fn) {
    if (document.readyState !== 'loading') fn();
    else document.addEventListener('DOMContentLoaded', fn);
  }

  ready(function () {
    initHamburger();
    initSearch();
    initReadingProgress();
    initBackToTop();
    initStickyHeader();
    initLazyImages();
    initExternalLinks();
    initShareFallback();
  });

  /* ── HAMBURGER / MOBILE MENU ─────────────────────────────────────────── */
  function initHamburger() {
    var btn = document.getElementById('hamburger');
    var nav = document.getElementById('primary-nav');
    if (!btn || !nav) return;

    btn.addEventListener('click', function () {
      var open = nav.classList.toggle('nav-open');
      btn.setAttribute('aria-expanded', String(open));
      document.body.classList.toggle('nav-is-open', open);
    });

    // Close on outside click
    document.addEventListener('click', function (e) {
      if (!nav.contains(e.target) && !btn.contains(e.target)) {
        nav.classList.remove('nav-open');
        btn.setAttribute('aria-expanded', 'false');
        document.body.classList.remove('nav-is-open');
      }
    });

    // Close on Escape
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') {
        nav.classList.remove('nav-open');
        btn.setAttribute('aria-expanded', 'false');
        document.body.classList.remove('nav-is-open');
      }
    });
  }

  /* ── SEARCH OVERLAY ─────────────────────────────────────────────────── */
  function initSearch() {
    var btn     = document.getElementById('search-btn');
    var overlay = document.getElementById('search-overlay');
    if (!btn || !overlay) return;

    var input = overlay.querySelector('input[type="search"]');

    function openOverlay() {
      overlay.classList.add('search-open');
      btn.setAttribute('aria-expanded', 'true');
      document.body.classList.add('search-is-open');
      if (input) setTimeout(function () { input.focus(); }, 80);
    }

    function closeOverlay() {
      overlay.classList.remove('search-open');
      btn.setAttribute('aria-expanded', 'false');
      document.body.classList.remove('search-is-open');
    }

    btn.addEventListener('click', function () {
      overlay.classList.contains('search-open') ? closeOverlay() : openOverlay();
    });

    // Click outside search box closes overlay
    overlay.addEventListener('click', function (e) {
      if (e.target === overlay) closeOverlay();
    });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') closeOverlay();
      // "/" to open search (when not in input)
      if (e.key === '/' && document.activeElement.tagName !== 'INPUT' && document.activeElement.tagName !== 'TEXTAREA') {
        e.preventDefault();
        openOverlay();
      }
    });
  }

  /* ── READING PROGRESS BAR ───────────────────────────────────────────── */
  function initReadingProgress() {
    var bar = document.getElementById('rp');
    if (!bar) return;

    var article = document.querySelector('.art-body') || document.querySelector('.single-article');
    if (!article) return;

    function updateProgress() {
      var rect   = article.getBoundingClientRect();
      var start  = rect.top + window.scrollY;
      var end    = rect.bottom + window.scrollY - window.innerHeight;
      var scroll = window.scrollY;
      if (end <= start) { bar.style.width = '100%'; return; }
      var pct = Math.min(100, Math.max(0, ((scroll - start) / (end - start)) * 100));
      bar.style.width = pct + '%';
      bar.setAttribute('aria-valuenow', Math.round(pct));
    }

    window.addEventListener('scroll', updateProgress, { passive: true });
    updateProgress();
  }

  /* ── BACK TO TOP ────────────────────────────────────────────────────── */
  function initBackToTop() {
    var btn = document.getElementById('back-top');
    if (!btn) return;

    window.addEventListener('scroll', function () {
      btn.classList.toggle('visible', window.scrollY > 500);
    }, { passive: true });

    btn.addEventListener('click', function () {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  /* ── STICKY HEADER ──────────────────────────────────────────────────── */
  function initStickyHeader() {
    var hdr = document.getElementById('site-header');
    if (!hdr) return;
    var lastY = 0;
    var ticking = false;

    function update() {
      var y = window.scrollY;
      if (y > 120) {
        hdr.classList.add('hdr-scrolled');
        if (y > lastY + 5) {
          hdr.classList.add('hdr-hidden');
        } else if (y < lastY - 5) {
          hdr.classList.remove('hdr-hidden');
        }
      } else {
        hdr.classList.remove('hdr-scrolled', 'hdr-hidden');
      }
      lastY   = y;
      ticking = false;
    }

    window.addEventListener('scroll', function () {
      if (!ticking) {
        requestAnimationFrame(update);
        ticking = true;
      }
    }, { passive: true });
  }

  /* ── NATIVE LAZY IMAGES FALLBACK ────────────────────────────────────── */
  function initLazyImages() {
    // If browser does not support native lazy loading, load all
    if ('loading' in HTMLImageElement.prototype) return;
    var imgs = document.querySelectorAll('img[loading="lazy"]');
    imgs.forEach(function (img) {
      img.src = img.dataset.src || img.src;
    });
  }

  /* ── EXTERNAL LINKS — open in new tab safely ────────────────────────── */
  function initExternalLinks() {
    var links = document.querySelectorAll('.art-body a[href]');
    links.forEach(function (a) {
      try {
        var url = new URL(a.href);
        if (url.hostname !== window.location.hostname) {
          a.setAttribute('target', '_blank');
          a.setAttribute('rel', 'noopener noreferrer');
        }
      } catch (e) { /* relative URL, skip */ }
    });
  }

  /* ── SHARE FALLBACK (Web Share API) ─────────────────────────────────── */
  function initShareFallback() {
    if (!navigator.share) return;
    var shareBtns = document.querySelectorAll('.share-label');
    shareBtns.forEach(function (label) {
      label.style.cursor = 'pointer';
      label.addEventListener('click', function () {
        navigator.share({
          title : document.title,
          url   : window.location.href,
        }).catch(function () { /* user cancelled */ });
      });
    });
  }

  /* ── TICKER PAUSE ON HOVER ──────────────────────────────────────────── */
  ready(function () {
    var inner = document.querySelector('.ticker-inner');
    if (!inner) return;
    inner.addEventListener('mouseenter', function () {
      inner.style.animationPlayState = 'paused';
    });
    inner.addEventListener('mouseleave', function () {
      inner.style.animationPlayState = 'running';
    });

    var breaking = document.querySelector('.breaking-inner');
    if (!breaking) return;
    breaking.addEventListener('mouseenter', function () {
      breaking.style.animationPlayState = 'paused';
    });
    breaking.addEventListener('mouseleave', function () {
      breaking.style.animationPlayState = 'running';
    });
  });

})();
