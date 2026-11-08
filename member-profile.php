<?php
/**
 * Profile / resume page sa usa ka member.
 * Gi-load gikan sa front-page.php kung naay ?member=slug sa URL.
 * Gikinahanglan nga naka-define na ang $team_members (team-data.php).
 */
require_once get_template_directory() . '/team-data.php';
$team_members = occ_get_team();
$slug = sanitize_key($_GET['member']);
$m    = $team_members[$slug];

$left  = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M11 6l-6 6 6 6"/></svg>';
$down  = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 4v12M6 11l6 6 6-6M5 20h14"/></svg>';

get_header(); ?>

<section class="profile">
  <div class="wrap">

    <a class="back-link" href="<?php echo esc_url(home_url('/#about')); ?>"><?php echo $left; ?> Back</a>

    <div class="profile-grid">

      <!-- LEFT: picture + contact -->
      <aside class="profile-card" style="background:<?php echo esc_attr($m['color']); ?>">
        <div class="profile-photo ph">
          <img class="cover" src="<?php echo panto_img($m['image']); ?>" alt="<?php echo esc_attr($m['name']); ?>">
        </div>
        <div class="profile-info">
          <h1><?php echo esc_html($m['name']); ?></h1>
          <p class="role"><?php echo esc_html($m['role']); ?></p>

          <ul class="contact">
            <li><b>Email</b><?php echo esc_html($m['email']); ?></li>
            <li><b>Phone</b><?php echo esc_html($m['phone']); ?></li>
            <li><b>Address</b><?php echo esc_html($m['address']); ?></li>
          </ul>

          <?php if (!empty($m['resume'])) : ?>
            <a class="profile-btn" href="<?php echo esc_url(get_template_directory_uri() . '/resume/' . $m['resume']); ?>" target="_blank" rel="noopener">
              Download Resume <?php echo $down; ?>
            </a>
          <?php endif; ?>
        </div>
      </aside>

      <!-- RIGHT: resume details -->
      <div class="profile-main">

        <div class="profile-block">
          <h2>About Me</h2>
          <p><?php echo esc_html($m['about']); ?></p>
        </div>

        <div class="profile-block">
          <h2>Education</h2>
          <ul class="edu">
            <?php foreach ($m['education'] as $e) : ?>
              <li>
                <b><?php echo esc_html($e[0]); ?></b>
                <span><?php echo esc_html($e[1]); ?></span>
                <small><?php echo esc_html($e[2]); ?></small>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>

        <div class="profile-block">
          <h2>Skills</h2>
          <ul class="skills">
            <?php foreach ($m['skills'] as $s) : ?>
              <li><?php echo esc_html($s); ?></li>
            <?php endforeach; ?>
          </ul>
        </div>

      </div>
    </div>
  </div>
</section>

<?php get_footer(); ?>