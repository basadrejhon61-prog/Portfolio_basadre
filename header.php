<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<style>
/* IT logo: far left of the header (same spot logic as the "Get in Touch" button on the right) */
body header .site-logo{
  position:absolute; left:clamp(16px,3vw,40px); top:50%; transform:translateY(-50%);
  display:inline-flex; align-items:center; line-height:0; z-index:2;
}
body header .site-logo img{
  height:44px; width:auto; max-width:80px; object-fit:contain; margin:0;
  transition:height .3s ease;
  pointer-events:none; user-select:none; -webkit-user-select:none; -webkit-user-drag:none; -webkit-touch-callout:none; --cx:12%; --cy:18%; clip-path:polygon(var(--cx) 0, calc(100% - var(--cx)) 0, calc(100% - var(--cx)) var(--cy), 100% var(--cy), 100% calc(100% - var(--cy)), calc(100% - var(--cx)) calc(100% - var(--cy)), calc(100% - var(--cx)) 100%, var(--cx) 100%, var(--cx) calc(100% - var(--cy)), 0 calc(100% - var(--cy)), 0 var(--cy), var(--cx) var(--cy));
}
body header.is-scrolled .site-logo img{ height:38px; }
/* the JPG has a white background: blend it away on the light home page, keep a small white tile on the dark profile page */
body:not(.is-profile-page) header .site-logo img{ filter:url(#occ-knockout); }
body.is-profile-page header .site-logo img{ background:#fff; padding:3px; border-radius:8px; }

@media (max-width:960px){
  body header .site-logo{ position:static; transform:none; order:0; }
  body header .site-logo img{ height:38px; }
}
</style>

<!-- turns the white background of IT_logo.jpg transparent (used by the header and footer logo) -->
<svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false">
  <filter id="occ-knockout" color-interpolation-filters="sRGB" x="0" y="0" width="100%" height="100%">
    <feColorMatrix type="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  -2.5 -2.5 -2.5 0 7.2"/>
  </filter>
</svg>

<header class="site-header">
  <div class="wrap">
    <div class="header-inner">

      <a class="site-logo" href="<?php echo esc_url(home_url('/')); ?>" aria-label="Home">
        <img src="<?php echo esc_url(panto_img('IT_logo.jpg')); ?>" alt="BSIT logo" decoding="async" draggable="false">
      </a>

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