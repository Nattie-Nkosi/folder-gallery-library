(function () {
  'use strict';

  function init() {
    var lb = document.getElementById('fg-lightbox');
    if (!lb || lb.dataset.fgInit === '1') return;
    lb.dataset.fgInit = '1';

    var imgEl = lb.querySelector('.fg-lightbox__image');
    var capEl = lb.querySelector('.fg-lightbox__caption');
    var btnClose = lb.querySelector('.fg-lightbox__close');
    var btnPrev = lb.querySelector('.fg-lightbox__nav--prev');
    var btnNext = lb.querySelector('.fg-lightbox__nav--next');

    var groups = {};
    document.querySelectorAll('.fg-thumb').forEach(function (el) {
      var key = el.dataset.gallery || '__default__';
      (groups[key] = groups[key] || []).push(el);
    });

    var current = { key: null, index: 0 };

    function open(key, index) {
      var list = groups[key];
      if (!list || !list[index]) return;
      current.key = key;
      current.index = index;
      var btn = list[index];
      imgEl.src = btn.dataset.src;
      imgEl.alt = btn.querySelector('img').alt;
      capEl.textContent = btn.dataset.caption || '';
      lb.hidden = false;
      document.body.style.overflow = 'hidden';
      btnClose.focus();
      updateNav();
    }

    function close() {
      lb.hidden = true;
      imgEl.src = '';
      document.body.style.overflow = '';
    }

    function step(delta) {
      var list = groups[current.key];
      if (!list) return;
      var next = current.index + delta;
      if (next < 0 || next >= list.length) return;
      open(current.key, next);
    }

    function updateNav() {
      var list = groups[current.key] || [];
      btnPrev.style.visibility = current.index > 0 ? 'visible' : 'hidden';
      btnNext.style.visibility = current.index < list.length - 1 ? 'visible' : 'hidden';
    }

    document.querySelectorAll('.fg-thumb').forEach(function (el) {
      el.addEventListener('click', function () {
        open(el.dataset.gallery || '__default__', parseInt(el.dataset.index, 10) || 0);
      });
    });

    btnClose.addEventListener('click', close);
    btnPrev.addEventListener('click', function () { step(-1); });
    btnNext.addEventListener('click', function () { step(1); });

    lb.addEventListener('click', function (e) {
      if (e.target === lb) close();
    });

    document.addEventListener('keydown', function (e) {
      if (lb.hidden) return;
      if (e.key === 'Escape') close();
      else if (e.key === 'ArrowLeft') step(-1);
      else if (e.key === 'ArrowRight') step(1);
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
