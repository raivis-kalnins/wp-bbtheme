document.addEventListener('DOMContentLoaded', function () {
  var body = document.body;
  var storageKey = 'wpThemeDarkMode';
  var savedMode = '';
  try { savedMode = window.localStorage.getItem(storageKey) || ''; } catch (e) {}
  if (savedMode === '1') body.classList.add('wp-theme-dark-mode');

  document.querySelectorAll('.btn-back-home').forEach(function (el) {
    el.setAttribute('href', window.wpThemeHome || '/');
  });

  function closeOpenMenus(scope) {
    (scope || document).querySelectorAll('.menu-item-open').forEach(function (item) {
      item.classList.remove('menu-item-open');
    });
  }

  function clearMegaPanels(scope) {
    (scope || document).querySelectorAll('.wp-theme-mega-panel.is-active').forEach(function (panel) {
      panel.classList.remove('is-active');
    });
  }

  function updateHeaderState() {
    var scrolled = window.scrollY > 8;
    body.classList.toggle('wp-theme-scrolled', scrolled);
    document.querySelectorAll('.wp-theme-site-header').forEach(function (header) {
      header.classList.toggle('is-scrolled', scrolled);
    });
  }
  updateHeaderState();
  window.addEventListener('scroll', updateHeaderState, { passive: true });

  document.querySelectorAll('[data-wp-theme-toggle]').forEach(function (toggle) {
    toggle.addEventListener('click', function () {
      body.classList.toggle('wp-theme-dark-mode');
      try {
        window.localStorage.setItem(storageKey, body.classList.contains('wp-theme-dark-mode') ? '1' : '0');
      } catch (e) {}
    });
  });

  document.querySelectorAll('.wp-theme-menu-shortcode--header').forEach(function (wrap) {
    var button = wrap.querySelector('.wp-theme-menu-toggle');
    var nav = wrap.querySelector('.wp-theme-header-nav');
    if (!button || !nav) return;

    var menuRoot = nav.querySelector(':scope > ul, :scope > .menu') || nav.querySelector('ul, .menu');
    var topItems = menuRoot ? Array.prototype.slice.call(menuRoot.children).filter(function (item) {
      return item && item.classList && item.classList.contains('menu-item-has-children');
    }) : [];
    var panelContainer = wrap.querySelector('.wp-theme-mega-panels');
    var panels = panelContainer ? Array.prototype.slice.call(panelContainer.querySelectorAll('.wp-theme-mega-panel')) : [];

    function closeMenu() {
      wrap.classList.remove('is-open');
      button.setAttribute('aria-expanded', 'false');
      closeOpenMenus(nav);
      clearMegaPanels(wrap);
    }

    document.addEventListener('click', function (event) {
      if (!wrap.contains(event.target)) closeMenu();
    });

    button.addEventListener('click', function () {
      var open = !wrap.classList.contains('is-open');
      wrap.classList.toggle('is-open', open);
      button.setAttribute('aria-expanded', open ? 'true' : 'false');
      if (!open) {
        closeOpenMenus(nav);
      }
    });

    window.addEventListener('resize', function () {
      if (window.innerWidth >= 1200) {
        wrap.classList.remove('is-open');
        button.setAttribute('aria-expanded', 'false');
        closeOpenMenus(nav);
      }
      if (window.innerWidth < 1200) {
        clearMegaPanels(wrap);
      }
    });

    topItems.forEach(function (item, idx) {
      var trigger = item.querySelector(':scope > a');
      if (!trigger) return;

      trigger.addEventListener('click', function (event) {
        if (window.innerWidth >= 1200 && wrap.dataset.headerMode === 'megamenu') {
          return;
        }
        if (window.innerWidth < 1200) {
          event.preventDefault();
          var isOpen = item.classList.contains('menu-item-open');
          closeOpenMenus(nav);
          if (!isOpen) item.classList.add('menu-item-open');
        }
      });

      ['mouseenter', 'focusin'].forEach(function (evt) {
        item.addEventListener(evt, function () {
          if (window.innerWidth < 1200 || wrap.dataset.headerMode !== 'megamenu') return;
          clearMegaPanels(wrap);
          if (panels[idx]) panels[idx].classList.add('is-active');
        });
      });
    });

    if (wrap.dataset.headerMode === 'megamenu') {
      wrap.addEventListener('mouseleave', function () {
        if (window.innerWidth >= 1200) clearMegaPanels(wrap);
      });
    }
  });
});