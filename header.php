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

      <!-- LEFT: logo + school name -->
      <a class="logo" href="<?php echo esc_url(home_url('/')); ?>">
        <img src="<?php echo panto_img('occLogo1.png'); ?>" alt="Opol Community College logo">
        <span class="logo-text">
          <b>OPOL COMMUNITY COLLEGE</b>
          <small>Empowering Minds, Transforming Future</small>
        </span>
      </a>

      <button class="nav-toggle" aria-label="Menu" aria-expanded="false">&#9776;</button>

      <!-- RIGHT: menu -->
      <nav class="main-nav" aria-label="Primary">
        <?php if (has_nav_menu('primary')) :
          wp_nav_menu(['theme_location'=>'primary','container'=>false]);
        else : ?>
          <ul>
            <li><a class="active" href="#top">Home</a></li>
            <li><a href="#about">Profile</a></li>
            <li><a href="#collections">Collections</a></li>
            <li><a href="#inspiration">Inspiration</a></li>
            <li><a href="#contact">Contact</a></li>
          </ul>
        <?php endif; ?>
      </nav>

    </div>
  </div>
</header>
<main id="top">