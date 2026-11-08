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

function panto_assets() {
    wp_enqueue_style('panto-font', 'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap', [], null);
    wp_enqueue_style('panto-style', get_stylesheet_uri(), ['panto-font'], wp_get_theme()->get('Version'));
    wp_enqueue_script('panto-main', get_template_directory_uri() . '/assets/js/main.js', [], wp_get_theme()->get('Version'), true);
}
add_action('wp_enqueue_scripts', 'panto_assets');

/* Image helper: assets/images/<file> */
function panto_img($file) {
    return esc_url(get_template_directory_uri() . '/assets/images/' . $file);
}