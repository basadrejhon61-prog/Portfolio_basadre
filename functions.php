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


/* ===================== VISITOR FEEDBACK (Facebook-style comments) =====================
   true  = ang feedback mo-appear dayon
   false = pending una, i-approve sa WP Admin > Feedback (Publish) */
if (!defined('OCC_FB_AUTO_PUBLISH')) define('OCC_FB_AUTO_PUBLISH', true);

add_action('init', function () {
    register_post_type('occ_feedback', [
        'labels'          => ['name' => 'Feedback', 'singular_name' => 'Feedback', 'menu_name' => 'Feedback'],
        'public'          => false,
        'show_ui'         => true,
        'menu_icon'       => 'dashicons-star-filled',
        'menu_position'   => 26,
        'supports'        => ['title', 'editor'],
        'capability_type' => 'post',
    ]);
});

function occ_fb_item($p) {
    $name = get_post_meta($p->ID, '_fb_name', true);
    if ($name === '') { $name = 'Anonymous'; }
    return [
        'id'      => (int) $p->ID,
        'name'    => $name,
        'initial' => mb_strtoupper(mb_substr($name, 0, 1)),
        'hue'     => abs(crc32(strtolower($name))) % 360,
        'rating'  => (int) get_post_meta($p->ID, '_fb_rating', true),
        'message' => $p->post_content,
        'time'    => human_time_diff(get_post_time('U', true, $p), time()) . ' ago',
        'likes'   => (int) get_post_meta($p->ID, '_fb_likes', true),
    ];
}

function occ_fb_stats() {
    global $wpdb;
    $row = $wpdb->get_row("SELECT COUNT(*) c, COALESCE(SUM(m.meta_value),0) s
        FROM {$wpdb->posts} p
        JOIN {$wpdb->postmeta} m ON m.post_id = p.ID AND m.meta_key = '_fb_rating'
        WHERE p.post_type = 'occ_feedback' AND p.post_status = 'publish'");
    return ['count' => (int) ($row->c ?? 0), 'sum' => (int) ($row->s ?? 0)];
}

/* list (public read) */
function occ_fb_ajax_list() {
    $page = max(1, (int) ($_POST['page'] ?? 1));
    $per  = min(20, max(1, (int) ($_POST['per'] ?? 8)));
    $q = new WP_Query([
        'post_type' => 'occ_feedback', 'post_status' => 'publish',
        'posts_per_page' => $per, 'paged' => $page,
        'orderby' => 'date', 'order' => 'DESC',
    ]);
    $items = array_map('occ_fb_item', $q->posts);
    wp_send_json_success(['items' => $items, 'total' => (int) $q->found_posts, 'pages' => (int) $q->max_num_pages, 'stats' => occ_fb_stats()]);
}
add_action('wp_ajax_occ_fb_list', 'occ_fb_ajax_list');
add_action('wp_ajax_nopriv_occ_fb_list', 'occ_fb_ajax_list');

/* submit */
function occ_fb_ajax_submit() {
    check_ajax_referer('occ_fb', 'nonce');

    // honeypot: bots fill this, humans never see it
    if (!empty($_POST['website'])) { wp_send_json_success(['pending' => true]); }

    $name   = mb_substr(sanitize_text_field(wp_unslash($_POST['name'] ?? '')), 0, 60);
    $msg    = sanitize_textarea_field(wp_unslash($_POST['message'] ?? ''));
    $rating = (int) ($_POST['rating'] ?? 0);

    if ($rating < 1 || $rating > 5) { wp_send_json_error(['msg' => 'Please choose a star rating.']); }
    if (mb_strlen($msg) < 10)       { wp_send_json_error(['msg' => 'Please write at least 10 characters.']); }
    if (mb_strlen($msg) > 600)      { $msg = mb_substr($msg, 0, 600); }

    // simple rate limit: 1 feedback / 45 seconds per visitor
    $key = 'occ_fb_' . md5($_SERVER['REMOTE_ADDR'] ?? 'x');
    if (get_transient($key)) { wp_send_json_error(['msg' => 'Please wait a moment before sending another one.']); }

    $id = wp_insert_post(wp_slash([
        'post_type'    => 'occ_feedback',
        'post_title'   => $name !== '' ? $name : 'Anonymous',
        'post_content' => $msg,
        'post_status'  => OCC_FB_AUTO_PUBLISH ? 'publish' : 'pending',
    ]), true);
    if (is_wp_error($id)) { wp_send_json_error(['msg' => 'Could not save your feedback. Please try again.']); }

    update_post_meta($id, '_fb_name', $name);
    update_post_meta($id, '_fb_rating', $rating);
    update_post_meta($id, '_fb_likes', 0);
    set_transient($key, 1, 45);

    // optional email notice to the site admin
    wp_mail(get_option('admin_email'), 'New portfolio feedback (' . $rating . '/5)',
        "From: " . ($name !== '' ? $name : 'Anonymous') . "\nRating: $rating/5\n\n$msg");

    if (OCC_FB_AUTO_PUBLISH) {
        wp_send_json_success(['pending' => false, 'item' => occ_fb_item(get_post($id)), 'stats' => occ_fb_stats()]);
    }
    wp_send_json_success(['pending' => true]);
}
add_action('wp_ajax_occ_fb_submit', 'occ_fb_ajax_submit');
add_action('wp_ajax_nopriv_occ_fb_submit', 'occ_fb_ajax_submit');

/* like / unlike */
function occ_fb_ajax_like() {
    check_ajax_referer('occ_fb', 'nonce');
    $id = (int) ($_POST['id'] ?? 0);
    $p  = get_post($id);
    if (!$p || $p->post_type !== 'occ_feedback' || $p->post_status !== 'publish') { wp_send_json_error(); }
    $n = (int) get_post_meta($id, '_fb_likes', true);
    $n = (($_POST['do'] ?? '') === 'unlike') ? max(0, $n - 1) : $n + 1;
    update_post_meta($id, '_fb_likes', $n);
    wp_send_json_success(['likes' => $n]);
}
add_action('wp_ajax_occ_fb_like', 'occ_fb_ajax_like');
add_action('wp_ajax_nopriv_occ_fb_like', 'occ_fb_ajax_like');