(function () {
  'use strict';
  var d = document, root = d.documentElement;
  var mm = function (q) { return window.matchMedia && window.matchMedia(q).matches; };
  var reduce = mm('(prefers-reduced-motion: reduce)');
  var fine = mm('(hover: hover) and (pointer: fine)');

  /* ==========================================
     1. MOBILE NAVIGATION
     ========================================== */
  var t = d.querySelector('.nav-toggle'), n = d.querySelector('.main-nav');
  if (t && n) {
    t.addEventListener('click', function () {
      var o = n.classList.toggle('open'); t.setAttribute('aria-expanded', o);
    });
    n.addEventListener('click', function (e) {
      if (e.target.closest && e.target.closest('a')) { n.classList.remove('open'); t.setAttribute('aria-expanded', 'false'); }
    });
  }

  /* ==========================================
     2. GALLERY SLIDER + FILTERS
     ========================================== */
  var slider = d.querySelector('.slider');
  var nx = null, pv = null;
  if (slider) {
    var slides = [].slice.call(slider.querySelectorAll('.slide'));
    var count = slider.querySelector('.count');
    var pills = d.querySelectorAll('.pill');
    var active = slides.slice(), idx = 0;

    var show = function () {
      slides.forEach(function (s) { s.classList.remove('show'); });
      if (active.length) active[idx].classList.add('show');
      if (count) count.textContent = (active.length ? idx + 1 : 0) + ' / ' + active.length;
    };

    nx = slider.querySelector('.next'); pv = slider.querySelector('.prev');
    if (nx) nx.onclick = function () { if (active.length) { idx = (idx + 1) % active.length; show(); } };
    if (pv) pv.onclick = function () { if (active.length) { idx = (idx - 1 + active.length) % active.length; show(); } };

    pills.forEach(function (p) {
      p.addEventListener('click', function () {
        pills.forEach(function (x) { x.classList.remove('active'); });
        p.classList.add('active');
        var f = p.dataset.filter;
        active = slides.filter(function (s) { return f === 'all' || s.dataset.cat === f; });
        idx = 0; show();
      });
    });
  }


  /* ==========================================
     2a. PHOTO LIGHTBOX (memories)
     ========================================== */
  var memoCards = [].slice.call(d.querySelectorAll('.memo-card'));
  if (memoCards.length) {
    var ic = function (path) {
      return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' + path + '</svg>';
    };
    var lb = d.createElement('div');
    lb.className = 'lb';
    lb.setAttribute('role', 'dialog'); lb.setAttribute('aria-modal', 'true');
    lb.setAttribute('aria-label', 'Photo viewer'); lb.setAttribute('aria-hidden', 'true');
    lb.innerHTML =
      '<button type="button" class="lb-close" aria-label="Close photo viewer">' + ic('<path d="M6 6l12 12M18 6L6 18"/>') + '</button>' +
      '<button type="button" class="lb-prev" aria-label="Previous photo">' + ic('<path d="M15 5l-7 7 7 7"/>') + '</button>' +
      '<figure class="lb-fig"><img class="lb-img" alt=""><figcaption><b class="lb-title"></b><span class="lb-desc"></span><em class="lb-count"></em></figcaption></figure>' +
      '<button type="button" class="lb-next" aria-label="Next photo">' + ic('<path d="M9 5l7 7-7 7"/>') + '</button>';
    d.body.appendChild(lb);

    var lbImg = lb.querySelector('.lb-img'), lbTitle = lb.querySelector('.lb-title'),
        lbDesc = lb.querySelector('.lb-desc'), lbCount = lb.querySelector('.lb-count'),
        lbBtns = [lb.querySelector('.lb-close'), lb.querySelector('.lb-prev'), lb.querySelector('.lb-next')];
    var lbIdx = 0, lbOpener = null;

    var lbShow = function (i) {
      lbIdx = (i + memoCards.length) % memoCards.length;
      var c = memoCards[lbIdx], h = c.querySelector('h3'), pp = c.querySelector('p');
      lbImg.src = c.getAttribute('href');
      lbImg.alt = h ? h.textContent : '';
      lbTitle.textContent = h ? h.textContent : '';
      lbDesc.textContent = pp ? pp.textContent : '';
      lbCount.textContent = (lbIdx + 1) + ' / ' + memoCards.length;
    };
    var lbOpen = function (i) {
      lbOpener = d.activeElement;
      lbShow(i);
      lb.classList.add('open'); lb.setAttribute('aria-hidden', 'false');
      root.classList.add('lb-lock');
      lbBtns[0].focus();
    };
    var lbClose = function () {
      lb.classList.remove('open'); lb.setAttribute('aria-hidden', 'true');
      root.classList.remove('lb-lock');
      if (lbOpener && lbOpener.focus) lbOpener.focus();
    };

    memoCards.forEach(function (c, i) {
      c.addEventListener('click', function (e) {
        if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey || e.button !== 0) return; // let "open in new tab" work
        e.preventDefault();
        lbOpen(i);
      });
    });
    lbBtns[0].addEventListener('click', lbClose);
    lbBtns[1].addEventListener('click', function () { lbShow(lbIdx - 1); });
    lbBtns[2].addEventListener('click', function () { lbShow(lbIdx + 1); });
    lb.addEventListener('click', function (e) {
      if (!e.target.closest('.lb-fig') && !e.target.closest('button')) lbClose();
    });
    d.addEventListener('keydown', function (e) {
      if (!lb.classList.contains('open')) return;
      if (e.key === 'Escape') { lbClose(); }
      else if (e.key === 'ArrowLeft') { lbShow(lbIdx - 1); }
      else if (e.key === 'ArrowRight') { lbShow(lbIdx + 1); }
      else if (e.key === 'Tab') {
        var k = lbBtns.indexOf(d.activeElement);
        e.preventDefault();
        lbBtns[(k + (e.shiftKey ? -1 : 1) + lbBtns.length) % lbBtns.length].focus();
      }
    });
    var tx = null;
    lb.addEventListener('touchstart', function (e) { tx = e.touches[0].clientX; }, { passive: true });
    lb.addEventListener('touchend', function (e) {
      if (tx === null) return;
      var dx = e.changedTouches[0].clientX - tx; tx = null;
      if (Math.abs(dx) > 50) lbShow(lbIdx + (dx < 0 ? 1 : -1));
    }, { passive: true });
  }

  /* ==========================================
     2b. FEEDBACK: slider + rating form
     ========================================== */
  var fbBox = d.querySelector('.fb-main');
  var fbGo = null, fbCount = 0;
  if (fbBox) {
    var fbSlides = [].slice.call(fbBox.querySelectorAll('.fb-slide'));
    var fbDots = [].slice.call(fbBox.querySelectorAll('.fb-dots button'));
    var fbIdx = 0;
    fbCount = fbSlides.length;
    fbGo = function (i) {
      fbIdx = (i + fbCount) % fbCount;
      fbSlides.forEach(function (sl, k) {
        sl.classList.toggle('show', k === fbIdx);
        sl.setAttribute('aria-hidden', k === fbIdx ? 'false' : 'true');
      });
      fbDots.forEach(function (b, k) {
        b.classList.toggle('active', k === fbIdx);
        b.setAttribute('aria-current', k === fbIdx ? 'true' : 'false');
      });
    };
    var fbPrev = fbBox.querySelector('.fb-prev'), fbNext = fbBox.querySelector('.fb-next');
    if (fbPrev) fbPrev.addEventListener('click', function () { fbGo(fbIdx - 1); });
    if (fbNext) fbNext.addEventListener('click', function () { fbGo(fbIdx + 1); });
    fbDots.forEach(function (b, k) { b.addEventListener('click', function () { fbGo(k); }); });
    fbBox.addEventListener('keydown', function (e) {
      if (e.key === 'ArrowLeft') fbGo(fbIdx - 1);
      if (e.key === 'ArrowRight') fbGo(fbIdx + 1);
    });
  }

  var fbForm = d.querySelector('.fb-form');
  if (fbForm) {
    fbForm.addEventListener('submit', function (e) {
      e.preventDefault();
      var note = fbForm.querySelector('.fb-note');
      var rate = fbForm.querySelector('input[name="rate"]:checked');
      var msg = fbForm.elements.message.value.trim();
      var name = fbForm.elements.name.value.trim();
      if (!rate) { note.textContent = 'Please choose a star rating first.'; return; }
      if (!msg) { note.textContent = 'Please write a short message.'; fbForm.elements.message.focus(); return; }
      var subject = 'Portfolio feedback: ' + rate.value + '/5';
      var body = (name ? 'From: ' + name + '\n' : '') + 'Rating: ' + rate.value + '/5\n\n' + msg;
      note.textContent = 'Opening your email app...';
      window.location.href = 'mailto:' + fbForm.getAttribute('data-to') + '?subject=' + encodeURIComponent(subject) + '&body=' + encodeURIComponent(body);
    });
  }

  /* ==========================================
     Everything below is motion only.
     Reduced-motion visitors get a calm, static page.
     ========================================== */
  if (reduce || !('IntersectionObserver' in window)) { root.classList.remove('js'); return; }

  function ready(fn) { d.readyState === 'loading' ? d.addEventListener('DOMContentLoaded', fn) : fn(); }

  ready(function () {
    root.classList.add('motion');
    var vh = window.innerHeight;
    var clamp = function (v, a, b) { return Math.max(a, Math.min(b, v)); };

    /* ---------- 3. LIVING BACKGROUND ---------- */
    var fx = d.createElement('div');
    fx.className = 'bg-fx';
    fx.setAttribute('aria-hidden', 'true');
    fx.innerHTML = '<i class="bg-blob b1"></i><i class="bg-blob b2"></i><i class="bg-blob b3"></i>' + (fine ? '<i class="bg-spot"></i>' : '');
    d.body.insertBefore(fx, d.body.firstChild);
    var spotEl = fx.querySelector('.bg-spot');

    /* ---------- 4. PROGRESS BAR + HERO CUE ---------- */
    var bar = d.createElement('div');
    bar.className = 'scroll-progress';
    bar.setAttribute('aria-hidden', 'true');
    d.body.appendChild(bar);

    var hero = d.querySelector('.hero');
    if (hero && d.getElementById('team')) {
      var cue = d.createElement('a');
      cue.className = 'scroll-cue'; cue.href = '#team';
      cue.setAttribute('aria-label', 'Scroll to the team section');
      cue.innerHTML = '<span></span>';
      hero.appendChild(cue);
    }

    /* ---------- 5. WORD-BY-WORD HEADLINES ---------- */
    function split(el) {
      var label = (el.innerText || el.textContent).replace(/\s+/g, ' ').trim();
      var i = 0, frag = d.createDocumentFragment();
      [].slice.call(el.childNodes).forEach(function (node) {
        if (node.nodeType === 3) {
          node.textContent.split(/(\s+)/).forEach(function (part) {
            if (!part) return;
            if (/^\s+$/.test(part)) { frag.appendChild(d.createTextNode(' ')); return; }
            var w = d.createElement('span'), wi = d.createElement('span');
            w.className = 'rvw'; w.setAttribute('aria-hidden', 'true');
            wi.className = 'rvi'; wi.textContent = part; wi.style.setProperty('--i', i++);
            w.appendChild(wi); frag.appendChild(w);
          });
        } else { frag.appendChild(node.cloneNode(true)); }
      });
      el.textContent = '';
      el.appendChild(frag);
      el.setAttribute('aria-label', label);
      el.classList.add('rv-split');
    }
    var titles = [].slice.call(d.querySelectorAll('.rv-title'));
    titles.forEach(split);

    /* ---------- 6. REVEAL IN / OUT, BOTH DIRECTIONS ---------- */
    var items = [].slice.call(d.querySelectorAll('.rv')).concat(titles).sort(function (a, b) {
      return a.compareDocumentPosition(b) & 4 ? -1 : 1;
    });
    var io = new IntersectionObserver(function (entries) {
      entries.sort(function (a, b) { return a.target.compareDocumentPosition(b.target) & 4 ? -1 : 1; });
      var n = 0;
      entries.forEach(function (e) {
        var el = e.target;
        if (e.isIntersecting) {
          if (!el.classList.contains('is-in')) {
            el.style.setProperty('--d', Math.min(n++, 10) * (el.tagName === 'LI' ? 0.05 : 0.09) + 's');
          }
          el.classList.add('is-in');
          el.classList.remove('is-above', 'is-below');
        } else {
          var above = e.boundingClientRect.top < 0;
          el.classList.remove('is-in');
          el.classList.toggle('is-above', above);
          el.classList.toggle('is-below', !above);
        }
      });
    }, { threshold: 0.12, rootMargin: '0px 0px -6% 0px' });
    items.forEach(function (el) { io.observe(el); });

    /* ---------- 7. MARQUEE (scroll speed drives it) ---------- */
    var marquee = d.querySelector('.marquee');
    var mqVisible = false;
    var tracks = [].slice.call(d.querySelectorAll('.mq-track')).map(function (el) {
      return { el: el, dir: parseFloat(el.dataset.dir) || -1, speed: parseFloat(el.dataset.speed) || 0.6, pos: 0, w: 1 };
    });
    function measure() { tracks.forEach(function (tr) { tr.w = Math.max(1, tr.el.scrollWidth / 2); }); }
    if (marquee) {
      new IntersectionObserver(function (es) { mqVisible = es[0].isIntersecting; }).observe(marquee);
      measure();
      window.addEventListener('load', measure);
    }

    /* ---------- 8. POINTER: soft light that follows the mouse ---------- */
    var ptr = { x: window.innerWidth / 2, y: window.innerHeight / 3 }, spot = { x: ptr.x, y: ptr.y };
    if (fine) {
      d.addEventListener('pointermove', function (e) {
        ptr.x = e.clientX; ptr.y = e.clientY;
        var c = e.target.closest && e.target.closest('.step, .profile-block');
        if (!c) return;
        var r = c.getBoundingClientRect();
        c.style.setProperty('--mx', (e.clientX - r.left) + 'px');
        c.style.setProperty('--my', (e.clientY - r.top) + 'px');
      }, { passive: true });
    }

    /* ---------- 9. ONE ANIMATION LOOP ---------- */
    var header = d.querySelector('header');
    var parallax = [].slice.call(d.querySelectorAll('[data-parallax]'));
    var ghosts = [].slice.call(d.querySelectorAll('.ghost'));
    var root_ = root, lastY = -1, prevY = window.pageYOffset, vel = 0;

    function scrollWork(y) {
      var max = Math.max(1, root_.scrollHeight - vh);
      fx.style.setProperty('--sy', y);
      bar.style.transform = 'scaleX(' + clamp(y / max, 0, 1).toFixed(4) + ')';
      if (header) header.classList.toggle('is-scrolled', y > 24);
      if (hero && y < vh * 1.3) {
        hero.style.setProperty('--hy', (y * 0.22).toFixed(1) + 'px');
        hero.style.setProperty('--ho', Math.max(0, 1 - y / (vh * 0.75)).toFixed(3));
      }
      parallax.forEach(function (c) {
        var r = c.getBoundingClientRect();
        if (r.bottom < -100 || r.top > vh + 100) return;
        var p = clamp((r.top + r.height / 2 - vh / 2) / (vh / 2 + r.height / 2), -1, 1);
        c.style.setProperty('--py', (p * -22).toFixed(1));
      });
      ghosts.forEach(function (g) {
        var s = g.parentElement.getBoundingClientRect();
        if (s.bottom < -200 || s.top > vh + 200) return;
        var p = (s.top + s.height / 2 - vh / 2) / vh;
        var dir = parseFloat(g.dataset.dir) || 1;
        g.style.setProperty('--gx', (p * -220 * dir).toFixed(1));
      });
    }

    function frame() {
      var y = window.pageYOffset;
      vel += ((y - prevY) - vel) * 0.15; prevY = y;
      if (y !== lastY) { lastY = y; scrollWork(y); }

      if (mqVisible) {
        tracks.forEach(function (tr) {
          tr.pos += tr.speed + vel * 0.4;
          var off = ((tr.pos % tr.w) + tr.w) % tr.w;
          tr.el.style.transform = 'translate3d(' + (tr.dir < 0 ? -off : off - tr.w).toFixed(2) + 'px,0,0)';
        });
      }
      if (spotEl) {
        spot.x += (ptr.x - spot.x) * 0.08; spot.y += (ptr.y - spot.y) * 0.08;
        spotEl.style.transform = 'translate3d(' + spot.x.toFixed(1) + 'px,' + spot.y.toFixed(1) + 'px,0)';
      }
      requestAnimationFrame(frame);
    }
    requestAnimationFrame(frame);

    window.addEventListener('resize', function () { vh = window.innerHeight; lastY = -1; measure(); });

    /* ---------- 10. SLIDER AUTOPLAY (pauses on hover / off-screen) ---------- */
    if (slider && nx) {
      var inView = false;
      new IntersectionObserver(function (es) { inView = es[0].isIntersecting; }, { threshold: 0.4 }).observe(slider);
      setInterval(function () {
        if (inView && !d.hidden && !slider.matches(':hover') && !slider.contains(d.activeElement)) nx.click();
      }, 5000);
    }

    /* ---------- 10b. FEEDBACK AUTOPLAY ---------- */
    if (fbBox && fbCount > 1 && fbGo) {
      var fbIn = false;
      new IntersectionObserver(function (es) { fbIn = es[0].isIntersecting; }, { threshold: 0.4 }).observe(fbBox);
      var fbTimer = setInterval(function () {
        if (fbIn && !d.hidden && !fbBox.matches(':hover') && !fbBox.contains(d.activeElement)) {
          var cur = fbBox.querySelector('.fb-slide.show');
          var k = fbBox.querySelectorAll('.fb-slide');
          for (var q = 0; q < k.length; q++) { if (k[q] === cur) { fbGo(q + 1); break; } }
        }
      }, 7000);
    }

    /* ---------- 11. PAGE TRANSITIONS ---------- */
    d.addEventListener('click', function (e) {
      var a = e.target.closest && e.target.closest('a[href]');
      if (!a || e.defaultPrevented || e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
      if ((a.target && a.target !== '_self') || a.hasAttribute('download')) return;
      var u; try { u = new URL(a.href, location.href); } catch (_) { return; }
      if (u.origin !== location.origin) return;
      if (u.pathname === location.pathname && u.search === location.search) return;
      e.preventDefault();
      root.classList.add('is-leaving');
      setTimeout(function () { location.href = u.href; }, 280);
    });
    window.addEventListener('pageshow', function (e) { if (e.persisted) root.classList.remove('is-leaving'); });
  });
})();