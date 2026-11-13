<?php
/**
 * Profile / resume page for one member.
 * Loaded from front-page.php when ?member=slug is in the URL.
 * get_header()/get_footer() are already called in front-page.php.
 */
require_once get_template_directory() . '/team-data.php';
require_once get_template_directory() . '/role-data.php';
$team_members = occ_team_with_roles();
$slug = sanitize_key(wp_unslash($_GET['member']));
$m    = $team_members[$slug];

$rm     = occ_role_meta($m['role'] ?? '');
$accent = 'var(' . $rm['color'] . ')';

/* Small inline icon helper */
$svg = function ($path) {
  return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $path . '</svg>';
};
$left   = $svg('<path d="M19 12H5M11 6l-6 6 6 6"/>');
$right  = $svg('<path d="M5 12h14M13 6l6 6-6 6"/>');
$down   = $svg('<path d="M12 4v12M6 11l6 6 6-6M5 20h14"/>');
$i_user  = $svg('<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>');
$i_cap   = $svg('<path d="M22 10 12 5 2 10l10 5 10-5z"/><path d="M6 12v5c3 2 9 2 12 0v-5"/>');
$i_bolt  = $svg('<path d="M13 2 3 14h9l-1 8 10-12h-9l1-8z"/>');
$i_mail  = $svg('<rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/>');
$i_phone = $svg('<path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8.1 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.7 2z"/>');
$i_pin   = $svg('<path d="M12 22s7-6.2 7-12a7 7 0 1 0-14 0c0 5.8 7 12 7 12z"/><circle cx="12" cy="10" r="2.5"/>');
$i_link  = $svg('<path d="M10 13a5 5 0 0 0 7.5.5l3-3a5 5 0 0 0-7-7l-1.7 1.7"/><path d="M14 11a5 5 0 0 0-7.5-.5l-3 3a5 5 0 0 0 7 7l1.7-1.7"/>');

// Previous / next member (wraps around)
$keys = array_keys($team_members);
$pos  = array_search($slug, $keys, true);
$n    = count($keys);
$prev_slug = $keys[($pos - 1 + $n) % $n];
$next_slug = $keys[($pos + 1) % $n];
$prev = $team_members[$prev_slug];
$next = $team_members[$next_slug];

$email = $m['email'] ?? '';
$phone = $m['phone'] ?? '';
$first      = strtoupper(strtok($m['name'], ' '));
$first_name = (string) strtok($m['name'], ' ');
$n_skills   = count($m['skills'] ?? []);
$n_edu      = count($m['education'] ?? []);
$profile_url = add_query_arg('member', $slug, home_url('/'));
?>

<main id="main">
<section class="profile">
  <span class="ghost ghost-profile" aria-hidden="true"><?php echo esc_html($first); ?></span>
  <div class="wrap">

    <a class="back-link" href="<?php echo esc_url(home_url('/#team')); ?>"><?php echo $left; ?> Back to team</a>

    <div class="profile-grid">

      <!-- LEFT: photo + contact -->
      <aside class="profile-card rv">
        <div class="profile-photo ph">
          <img class="cover" src="<?php echo esc_url(panto_img($m['image'])); ?>" alt="<?php echo esc_attr($m['name']); ?>" decoding="async">
        </div>
        <div class="profile-info">
          <h1><?php echo esc_html($m['name']); ?></h1>
          <?php if (!empty($m['role'])) : ?>
            <p class="role" style="--a: <?php echo esc_attr($accent); ?>"><span class="ri"><?php echo $svg($rm['icon']); ?></span> <?php echo esc_html($rm['label']); ?></p>
          <?php endif; ?>

          <ul class="contact">
            <?php if ($email) : ?>
              <li>
                <span class="ci"><?php echo $i_mail; ?></span>
                <div><b>Email</b><a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a></div>
              </li>
            <?php endif; ?>
            <?php if ($phone) : ?>
              <li>
                <span class="ci"><?php echo $i_phone; ?></span>
                <div><b>Phone</b><a href="tel:<?php echo esc_attr(preg_replace('/[^\d+]/', '', $phone)); ?>"><?php echo esc_html($phone); ?></a></div>
              </li>
            <?php endif; ?>
            <?php if (!empty($m['address'])) : ?>
              <li>
                <span class="ci"><?php echo $i_pin; ?></span>
                <div><b>Address</b><span><?php echo esc_html($m['address']); ?></span></div>
              </li>
            <?php endif; ?>
          </ul>

          <?php if (!empty($m['resume'])) : ?>
            <a class="profile-btn" href="<?php echo esc_url(get_template_directory_uri() . '/resume/' . $m['resume']); ?>" target="_blank" rel="noopener">
              Download resume <?php echo $down; ?>
            </a>
          <?php endif; ?>

          <button type="button" class="profile-btn btn-copy" data-copy="<?php echo esc_url($profile_url); ?>">
            <?php echo $i_link; ?> <span>Copy profile link</span>
          </button>
        </div>
      </aside>

      <!-- RIGHT: resume details -->
      <div class="profile-main">

        <!-- Greeting card -->
        <div class="profile-hero rv" style="--a: <?php echo esc_attr($accent); ?>">
          <span class="ph-shape" aria-hidden="true"></span>
          <p class="ph-label">Team member profile</p>
          <p class="ph-hello">Hi, I&rsquo;m <?php echo esc_html($first_name); ?></p>
          <?php if (!empty($m['role'])) : ?>
            <p class="ph-role"><span class="ri"><?php echo $svg($rm['icon']); ?></span> <?php echo esc_html($rm['label']); ?></p>
          <?php endif; ?>
          <div class="ph-stats">
            <?php if ($n_skills) : ?><div><b><?php echo (int) $n_skills; ?></b><span>Skills</span></div><?php endif; ?>
            <?php if ($n_edu) : ?><div><b><?php echo (int) $n_edu; ?></b><span>Education</span></div><?php endif; ?>
            <div><b><?php echo (int) ($pos + 1); ?> / <?php echo (int) $n; ?></b><span>In the team</span></div>
          </div>
        </div>

        <?php if (!empty($rm['tasks'])) : ?>
        <div class="profile-block role-block rv" style="--a: <?php echo esc_attr($accent); ?>">
          <h2><span class="ico"><?php echo $svg($rm['icon']); ?></span> Capstone role: <?php echo esc_html($rm['label']); ?></h2>
          <p><?php echo esc_html($rm['desc']); ?></p>
          <ul class="role-tasks">
            <?php foreach ($rm['tasks'] as $t) : ?>
              <li class="rv"><?php echo esc_html($t); ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
        <?php endif; ?>

        <?php if (!empty($m['about'])) : ?>
        <div class="profile-block about rv">
          <h2><span class="ico"><?php echo $i_user; ?></span> About me</h2>
          <p><?php echo esc_html($m['about']); ?></p>
        </div>
        <?php endif; ?>

        <?php if (!empty($m['education'])) : ?>
        <div class="profile-block blk-edu rv">
          <h2><span class="ico"><?php echo $i_cap; ?></span> Education</h2>
          <ul class="edu">
            <?php foreach ($m['education'] as $e) : ?>
              <li class="rv">
                <b><?php echo esc_html($e[0] ?? ''); ?></b>
                <span><?php echo esc_html($e[1] ?? ''); ?></span>
                <small><?php echo esc_html($e[2] ?? ''); ?></small>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
        <?php endif; ?>

        <?php if (!empty($m['skills'])) : ?>
        <div class="profile-block blk-skills rv">
          <h2><span class="ico"><?php echo $i_bolt; ?></span> Skills</h2>
          <ul class="skills">
            <?php foreach ($m['skills'] as $s) : ?>
              <li class="rv"><?php echo esc_html($s); ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
        <?php endif; ?>

        <nav class="profile-pager rv" aria-label="Other team members">
          <a href="<?php echo esc_url(add_query_arg('member', $prev_slug, home_url('/'))); ?>" rel="prev">
            <?php echo $left; ?>
            <span><small>Previous</small><b><?php echo esc_html($prev['name']); ?></b></span>
          </a>
          <a href="<?php echo esc_url(add_query_arg('member', $next_slug, home_url('/'))); ?>" rel="next">
            <span><small>Next</small><b><?php echo esc_html($next['name']); ?></b></span>
            <?php echo $right; ?>
          </a>
        </nav>

      </div>
    </div>
  </div>
</section>
</main>

<script>
document.querySelectorAll('[data-copy]').forEach(function (b) {
  b.addEventListener('click', function () {
    var t = b.getAttribute('data-copy');
    var label = b.querySelector('span');
    var old = label.textContent;
    function done() { label.textContent = 'Link copied!'; setTimeout(function () { label.textContent = old; }, 1800); }
    function fallback() {
      var i = document.createElement('input');
      i.value = t; document.body.appendChild(i); i.select();
      try { document.execCommand('copy'); } catch (e) {}
      i.remove(); done();
    }
    if (navigator.clipboard && navigator.clipboard.writeText) {
      navigator.clipboard.writeText(t).then(done, fallback);
    } else { fallback(); }
  });
});
</script>