<?php
if (!defined('ABSPATH')) exit;

function panto_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    add_theme_support('html5', ['search-form','comment-form','gallery','caption']);
    register_nav_menus(['primary' => __('Primary Menu', 'panto-design')]);
}
add_action('after_setup_theme', 'panto_setup');

/* Styles + script. No web fonts: the theme uses the visitor's default system font. */
function panto_assets() {
   wp_enqueue_style(
  'panto-style',
  get_stylesheet_uri(),
  [],
  filemtime(get_stylesheet_directory() . '/style.css')
);
    wp_enqueue_script('panto-main', get_template_directory_uri() . '/assets/js/main.js', [], wp_get_theme()->get('Version'), [
        'in_footer' => true,
        'strategy'  => 'defer',
    ]);
}
add_action('wp_enqueue_scripts', 'panto_assets');

/* Image helper: assets/images/<file> */
function panto_img($file) {
    return esc_url(get_template_directory_uri() . '/assets/images/' . $file);
}

/* Is this request a team-member profile page? (?member=slug) */
function panto_is_profile() {
    return isset($_GET['member']) && is_string($_GET['member']);
}

add_filter('body_class', function ($classes) {
    if (panto_is_profile()) $classes[] = 'is-profile-page';
    return $classes;
});

add_filter('pre_get_document_title', function ($title) {
    if (panto_is_profile()) {
        require_once get_template_directory() . '/team-data.php';
        $team = occ_get_team();
        $key  = sanitize_key(wp_unslash($_GET['member']));
        if (isset($team[$key])) return $team[$key]['name'] . ' – ' . get_bloginfo('name');
    }
    return $title;
});

/* Phone browser bar color */
add_action('wp_head', function () {
    echo '<meta name="theme-color" content="#ffffff">' . "\n";
}, 1);

/* Hides animated items from the very first paint (no flash before JS runs).
   Falls back to "everything visible" if main.js never starts, or the visitor
   prefers reduced motion. */
add_action('wp_head', function () {
    echo "<script>\n";
    echo <<<'JS'
(function(d){
  if (window.matchMedia && matchMedia('(prefers-reduced-motion: reduce)').matches) return;
  d.className += ' js';
  setTimeout(function(){ if (!/\bmotion\b/.test(d.className)) d.className = d.className.replace(/\bjs\b/, ''); }, 4000);
})(document.documentElement);
JS;
    echo "\n</script>\n";
}, 0);

