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
    initMobileDrawer();
    initProfile();
    initSearch();
    initReadingProgress();
    initBackToTop();
    initStickyHeader();
    initLazyImages();
    initExternalLinks();
    initShareFallback();
    initTickers();
  });

  /* ── MOBILE NAV DRAWER ───────────────────────────────────────────────── */
  function initMobileDrawer() {
    var hamburger = document.getElementById('hamburger');
    var drawer    = document.getElementById('mobile-nav-drawer');
    var overlay   = document.getElementById('mobile-nav-overlay');
    var closeBtn  = document.getElementById('mnd-close');
    if (!hamburger || !drawer) return;

    function openDrawer() {
      drawer.hidden  = false;
      if (overlay) overlay.hidden = false;
      setTimeout(function () {
        drawer.classList.add('is-open');
        if (overlay) overlay.classList.add('is-visible');
      }, 10);
      hamburger.setAttribute('aria-expanded', 'true');
      document.body.classList.add('drawer-open');
    }

    function closeDrawer() {
      drawer.classList.remove('is-open');
      if (overlay) overlay.classList.remove('is-visible');
      hamburger.setAttribute('aria-expanded', 'false');
      document.body.classList.remove('drawer-open');
      setTimeout(function () {
        drawer.hidden  = true;
        if (overlay) overlay.hidden = true;
      }, 300);
    }

    hamburger.addEventListener('click', openDrawer);
    if (closeBtn)  closeBtn.addEventListener('click', closeDrawer);
    if (overlay)   overlay.addEventListener('click', closeDrawer);

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && !drawer.hidden) closeDrawer();
    });
  }

  /* ── PROFILE DROPDOWN ───────────────────────────────────────────────── */
  function initProfile() {
    var wrap      = document.getElementById('profile-wrap');
    var btn       = document.getElementById('profile-btn');
    var drop      = document.getElementById('profile-drop');
    if (!wrap || !btn || !drop) return;

    var loaded = false;

    function openDrop() {
      drop.hidden = false;
      btn.setAttribute('aria-expanded', 'true');
      if (!loaded) { loaded = true; loadProfileState(); }
    }

    function closeDrop() {
      drop.hidden = true;
      btn.setAttribute('aria-expanded', 'false');
    }

    function toggleDrop() {
      drop.hidden ? openDrop() : closeDrop();
    }

    btn.addEventListener('click', function (e) {
      e.stopPropagation();
      toggleDrop();
    });

    document.addEventListener('click', function (e) {
      if (!wrap.contains(e.target)) closeDrop();
    });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') closeDrop();
    });

    /* Sign-out */
    var signOutBtn = document.getElementById('pd-signout');
    if (signOutBtn) {
      signOutBtn.addEventListener('click', function () {
        fetch('https://auth.paisabot.com/auth/logout', {
          method: 'POST', credentials: 'include',
        }).finally(function () {
          window.location.reload();
        });
      });
    }
  }

  /* ── PII masking helpers ─────────────────────────────────────────── */
  function maskEmail(email) {
    if (!email) return '';
    var parts = email.split('@');
    if (parts.length !== 2) return '***';
    var local  = parts[0];
    var domain = parts[1];
    var show   = local.length > 2 ? local.slice(0, 2) : local.slice(0, 1);
    return show + '***@' + domain;
  }

  function maskPhone(phone) {
    if (!phone) return '';
    var digits = phone.replace(/\D/g, '');
    return '******' + digits.slice(-4);
  }

  function maskName(name) {
    if (!name) return '';
    var words = name.trim().split(/\s+/);
    return words.map(function (w, i) {
      // Show full first name, mask subsequent names to initial + ***
      return i === 0 ? w : w.slice(0, 1) + '***';
    }).join(' ');
  }

  function loadProfileState() {
    var loading  = document.getElementById('pd-loading');
    var userEl   = document.getElementById('pd-user');
    var guestEl  = document.getElementById('pd-guest');

    fetch('https://auth.paisabot.com/me', { credentials: 'include' })
      .then(function (r) { return r.ok ? r.json() : Promise.reject(r.status); })
      .then(function (me) {
        /* ── Populate signed-in panel (mask PII before display) ── */
        var rawName  = me.name     || me.display_name || '';
        var rawEmail = me.email    || '';
        var rawPhone = me.phone    || me.phone_number  || '';
        var tier     = (me.tier    || me.plan || 'free').toLowerCase();
        var expires  = me.expires_at ? new Date(me.expires_at).toLocaleDateString() : '';
        var avatarUrl = me.avatar_url || me.picture || '';

        var initials    = rawName.split(' ').map(function (w) { return w[0]; }).join('').slice(0,2).toUpperCase();
        var displayName = rawName;

        var el = function(id) { return document.getElementById(id); };

        if (avatarUrl) {
          el('pd-avatar-img').src = avatarUrl;
          el('pd-avatar-img').alt = initials;   /* no raw name in DOM attr */
          el('pd-avatar-img').hidden = false;
        } else {
          el('pd-avatar-initials').textContent = initials;
        }
        el('pd-name').textContent  = displayName;
        el('pd-email').textContent = maskEmail(rawEmail);

        /* Show phone only if present, always masked */
        if (rawPhone) {
          var phoneEl = el('pd-phone');
          if (phoneEl) { phoneEl.textContent = maskPhone(rawPhone); phoneEl.hidden = false; }
        }

        var tierEl = el('pd-tier');
        tierEl.textContent = tier === 'pro' ? 'Pro' : 'Free';
        tierEl.className   = 'pd-tier' + (tier === 'pro' ? ' is-pro' : '');

        if (expires) el('pd-expires').textContent = 'Until ' + expires;

        if (tier !== 'pro') el('pd-upgrade').hidden = false;

        /* Update header avatar initials */
        var avatarSpan = document.getElementById('profile-avatar');
        if (avatarSpan && initials) {
          avatarSpan.textContent = initials;
          avatarSpan.classList.add('has-initials');
        }

        if (loading) loading.hidden = true;
        if (userEl)  userEl.hidden  = false;
      })
      .catch(function () {
        if (loading) loading.hidden = true;
        if (guestEl) guestEl.hidden = false;
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

    overlay.addEventListener('click', function (e) {
      if (e.target === overlay) closeOverlay();
    });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') closeOverlay();
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
    var lastY   = 0;
    var ticking = false;

    function update() {
      var y = window.scrollY;
      if (y > 120) {
        hdr.classList.add('hdr-scrolled');
        if (y > lastY + 5)      hdr.classList.add('hdr-hidden');
        else if (y < lastY - 5) hdr.classList.remove('hdr-hidden');
      } else {
        hdr.classList.remove('hdr-scrolled', 'hdr-hidden');
      }
      lastY   = y;
      ticking = false;
    }

    window.addEventListener('scroll', function () {
      if (!ticking) { requestAnimationFrame(update); ticking = true; }
    }, { passive: true });
  }

  /* ── NATIVE LAZY IMAGES FALLBACK ────────────────────────────────────── */
  function initLazyImages() {
    if ('loading' in HTMLImageElement.prototype) return;
    document.querySelectorAll('img[loading="lazy"]').forEach(function (img) {
      img.src = img.dataset.src || img.src;
    });
  }

  /* ── EXTERNAL LINKS — open in new tab safely ────────────────────────── */
  function initExternalLinks() {
    document.querySelectorAll('.art-body a[href]').forEach(function (a) {
      try {
        var url = new URL(a.href);
        if (url.hostname !== window.location.hostname) {
          a.setAttribute('target', '_blank');
          a.setAttribute('rel', 'noopener noreferrer');
        }
      } catch (e) { /* relative URL */ }
    });
  }

  /* ── SHARE FALLBACK (Web Share API) ─────────────────────────────────── */
  function initShareFallback() {
    if (!navigator.share) return;
    document.querySelectorAll('.share-label').forEach(function (label) {
      label.style.cursor = 'pointer';
      label.addEventListener('click', function () {
        navigator.share({ title: document.title, url: window.location.href })
          .catch(function () { /* cancelled */ });
      });
    });
  }

  /* ── TICKER PAUSE ON HOVER ──────────────────────────────────────────── */
  function initTickers() {
    ['ticker-inner', 'breaking-inner'].forEach(function (cls) {
      var el = document.querySelector('.' + cls);
      if (!el) return;
      el.addEventListener('mouseenter', function () { el.style.animationPlayState = 'paused'; });
      el.addEventListener('mouseleave', function () { el.style.animationPlayState = 'running'; });
    });
  }

})();
