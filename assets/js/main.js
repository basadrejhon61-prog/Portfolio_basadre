(function () {
  // Mobile nav
  var t = document.querySelector('.nav-toggle'), n = document.querySelector('.main-nav');
  if (t && n) t.addEventListener('click', function () {
    var o = n.classList.toggle('open'); t.setAttribute('aria-expanded', o);
  });

  // Gallery slider + filters
  var slider = document.querySelector('.slider');
  if (!slider) return;
  var slides = [].slice.call(slider.querySelectorAll('.slide'));
  var count = slider.querySelector('.count');
  var pills = document.querySelectorAll('.pill');
  var active = slides.slice(), idx = 0;

  function show() {
    slides.forEach(function (s) { s.classList.remove('show'); });
    if (active.length) active[idx].classList.add('show');
    count.textContent = (active.length ? idx + 1 : 0) + ' / ' + active.length;
  }
  slider.querySelector('.next').onclick = function () { if (active.length) { idx = (idx + 1) % active.length; show(); } };
  slider.querySelector('.prev').onclick = function () { if (active.length) { idx = (idx - 1 + active.length) % active.length; show(); } };
  pills.forEach(function (p) {
    p.addEventListener('click', function () {
      pills.forEach(function (x) { x.classList.remove('active'); });
      p.classList.add('active');
      var f = p.dataset.filter;
      active = slides.filter(function (s) { return f === 'all' || s.dataset.cat === f; });
      idx = 0; show();
    });
  });
})();