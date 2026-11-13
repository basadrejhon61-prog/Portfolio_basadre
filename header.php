<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="site-header">
  <div class="wrap">
    <div class="header-inner">

      <button class="nav-toggle" aria-label="Menu" aria-expanded="false">&#9776;</button>

      <nav class="main-nav" aria-label="Primary">
        <?php if (has_nav_menu('primary')) :
          wp_nav_menu(['theme_location'=>'primary','container'=>false]);
        else : ?>
          <ul>
            <li><a class="active" href="#top">Home</a></li>
            <li><a href="#team">Profile</a></li>
            <li><a href="#collections">Memories</a></li>
            <li><a href="#projects">Projects</a></li>
            <li><a href="#process">Process</a></li>
            <li><a href="#feedback">Feedback</a></li>
          </ul>
        <?php endif; ?>
      </nav>

      <a class="header-cta" href="#contact">Get in Touch</a>

    </div>
  </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', function () {
  var links = document.querySelectorAll('.main-nav a');

  function setActive(link) {
    links.forEach(function (l) {
      l.classList.remove('active');
      if (l.parentElement) l.parentElement.classList.remove('current-menu-item');
    });
    link.classList.add('active');
  }

  // click = active
  links.forEach(function (link) {
    link.addEventListener('click', function () { setActive(link); });
  });

  // scroll = the menu follows the section you are viewing
  var map = [];
  links.forEach(function (link) {
    var h = link.getAttribute('href') || '';
    if (h.charAt(0) === '#' && h.length > 1) {
      var el = document.querySelector(h);
      if (el) map.push({ link: link, el: el });
    }
  });
  function spy() {
    var y = window.scrollY + window.innerHeight * 0.35, cur = null;
    map.forEach(function (m) {
      if (m.el.getBoundingClientRect().top + window.scrollY <= y) cur = m;
    });
    if (!cur && map.length) cur = map[0];
    if (cur && !cur.link.classList.contains('active')) setActive(cur.link);
  }
  window.addEventListener('scroll', spy, { passive: true });
  spy();
});
</script>

<main id="top">