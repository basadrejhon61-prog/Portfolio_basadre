<?php
/**
 * Member profile page (included by front-page.php when the URL has ?member=slug)
 * Light, formal, animated - same style as Team / Memories / Projects / Process / Feedback.
 *
 * ORDER OF CONTENT
 *   1. Hero      : photo, name, role, short intro, buttons, quick numbers
 *   2. Ticker    : skills
 *   3. Main      : About me > Capstone role > Education + Skills
 *   4. Sidebar   : Contact > Find me online   (sticky on desktop)
 *   5. Pager     : previous / next member
 */
if (!isset($team_members) || !is_array($team_members)) {
  require_once get_template_directory() . '/team-data.php';
  require_once get_template_directory() . '/role-data.php';
  $team_members = occ_team_with_roles();
}

$mp_slug = isset($_GET['member']) && is_string($_GET['member']) ? sanitize_key(wp_unslash($_GET['member'])) : '';
if (!isset($team_members[$mp_slug])) { return; }

$m  = $team_members[$mp_slug];
$rm = occ_role_meta($m['role'] ?? '');

/* ---------- small helpers ---------- */
$mp_pick = function ($arr, array $keys) {
  foreach ($keys as $k) { if (!empty($arr[$k]) && is_string($arr[$k])) { return trim($arr[$k]); } }
  return '';
};
$mp_svg = function ($path, $fill = false) {
  return $fill
    ? '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">' . $path . '</svg>'
    : '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $path . '</svg>';
};
$i_arrow = $mp_svg('<path d="M5 12h14M13 6l6 6-6 6"/>');
$i_left  = $mp_svg('<path d="M19 12H5M11 6l-6 6 6 6"/>');
$i_mail  = $mp_svg('<rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/>');
$i_phone = $mp_svg('<path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8.1 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.7 2z"/>');
$i_pin   = $mp_svg('<path d="M12 22s7-6.2 7-12a7 7 0 1 0-14 0c0 5.8 7 12 7 12z"/><circle cx="12" cy="10" r="2.5"/>');
$i_copy  = $mp_svg('<rect x="9" y="9" width="11" height="11" rx="2"/><path d="M5 15V6a2 2 0 0 1 2-2h9"/>');
$i_down  = $mp_svg('<path d="M12 4v11M7 11l5 5 5-5M5 20h14"/>');
$i_user  = $mp_svg('<circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/>');
$i_cap   = $mp_svg('<path d="M2 9l10-5 10 5-10 5z"/><path d="M6 11.5V16c0 1.5 2.7 3 6 3s6-1.5 6-3v-4.5"/>');
$i_bolt  = $mp_svg('<path d="M13 2 4 14h7l-1 8 9-12h-7z"/>');
$i_check = $mp_svg('<path d="M5 12l5 5 9-10"/>');
$i_share = $mp_svg('<circle cx="6" cy="12" r="2.5"/><circle cx="18" cy="6" r="2.5"/><circle cx="18" cy="18" r="2.5"/><path d="M8.2 10.8l7.6-3.6M8.2 13.2l7.6 3.6"/>');

$mp_socials = [
  'facebook'  => ['Facebook',  $mp_svg('<path d="M22 12a10 10 0 1 0-11.56 9.88v-6.99H7.9V12h2.54V9.8c0-2.5 1.49-3.89 3.77-3.89 1.09 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56V12h2.78l-.44 2.89h-2.34v6.99A10 10 0 0 0 22 12z"/>', true)],
  'twitter'   => ['Twitter',   $mp_svg('<path d="M22.46 6c-.77.35-1.6.58-2.46.69a4.3 4.3 0 0 0 1.88-2.37 8.6 8.6 0 0 1-2.72 1.04 4.28 4.28 0 0 0-7.29 3.9A12.15 12.15 0 0 1 3.07 4.8a4.28 4.28 0 0 0 1.33 5.71 4.27 4.27 0 0 1-1.94-.54v.05a4.28 4.28 0 0 0 3.43 4.2 4.3 4.3 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.97A8.6 8.6 0 0 1 2 19.54a12.1 12.1 0 0 0 6.56 1.92c7.88 0 12.2-6.53 12.2-12.2l-.01-.56A8.7 8.7 0 0 0 22.46 6z"/>', true)],
  'instagram' => ['Instagram', $mp_svg('<rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/>')],
  'tiktok'    => ['TikTok',    $mp_svg('<path d="M16.6 5.82A4.28 4.28 0 0 1 15.54 3h-3.09v12.4a2.59 2.59 0 0 1-2.59 2.5 2.6 2.6 0 0 1-2.6-2.6 2.6 2.6 0 0 1 2.6-2.6c.27 0 .53.04.77.12V9.66a5.7 5.7 0 0 0-.77-.05A5.69 5.69 0 0 0 4.17 15.3a5.69 5.69 0 0 0 5.69 5.69 5.69 5.69 0 0 0 5.69-5.69V9.01a7.35 7.35 0 0 0 4.29 1.37V7.3a4.3 4.3 0 0 1-3.24-1.48z"/>', true)],
];

/* ---------- member data ---------- */
$name  = (string) ($m['name'] ?? '');
$parts = preg_split('/\s+/', trim($name));
$last  = count($parts) > 1 ? array_pop($parts) : '';
$first = implode(' ', $parts);
if ($last === '') { $last = $first; $first = ''; }

$role_label = (string) ($rm['label'] ?? ($m['role'] ?? ''));
$about = $mp_pick($m, ['about', 'bio', 'summary', 'description', 'intro']);
if ($about === '') { $about = $name . ' is the ' . $role_label . ' of our capstone team.'; }

/* short version of the about text for the hero (the full text is in the About card) */
$about_short = wp_trim_words($about, 26, '&hellip;');
$about_cut   = (wp_strip_all_tags($about_short) !== $about);

$email = $mp_pick($m, ['email']);

/* Gmail + mailto links (self-contained: no other function needed) */
$mail = null;
if ($email !== '' && is_email($email)) {
  $mp_subj = rawurlencode('Hello ' . $name);
  $mp_body = rawurlencode('Hi ' . $name . ",\r\n\r\n");
  $mail = [
    'gmail'  => 'https://mail.google.com/mail/?view=cm&fs=1&to=' . rawurlencode($email) . '&su=' . $mp_subj . '&body=' . $mp_body,
    'mailto' => 'mailto:' . $email . '?subject=' . $mp_subj . '&body=' . $mp_body,
  ];
}

$phone = $mp_pick($m, ['phone', 'contact', 'mobile', 'number', 'tel']);
$addr  = $mp_pick($m, ['address']);
$phone_ok = $phone !== '' && strlen(preg_replace('/\D/', '', $phone)) >= 10 && stripos($phone, 'x') === false;

$skills = array_values(array_filter((array) ($m['skills'] ?? [])));
$edu    = array_values(array_filter((array) ($m['education'] ?? []), 'is_array'));
$edu_get = function ($e, $i, array $names) {
  if (isset($e[$i]) && is_string($e[$i])) { return $e[$i]; }
  foreach ($names as $n) { if (!empty($e[$n]) && is_string($e[$n])) { return $e[$n]; } }
  return '';
};

/* role data exactly as in role-data.php: desc, tasks, icon */
$role_desc = '';
foreach (['desc', 'description', 'summary', 'about', 'text'] as $k) { if (!empty($rm[$k]) && is_string($rm[$k])) { $role_desc = $rm[$k]; break; } }
$role_tasks = [];
foreach (['tasks', 'duties', 'responsibilities', 'resp', 'works', 'items'] as $k) { if (!empty($rm[$k]) && is_array($rm[$k])) { $role_tasks = array_values(array_filter($rm[$k], 'is_string')); break; } }
$role_icon   = !empty($rm['icon']) && is_string($rm['icon']) ? $mp_svg($rm['icon']) : $i_bolt;
$profile_url = add_query_arg('member', $mp_slug, home_url('/'));

/* social links: only the ones that have a link */
$mp_links = [];
foreach ($mp_socials as $key => $soc) {
  $url = $mp_pick($m, [$key]);
  if ($url === '' && !empty($m['social'][$key]) && is_string($m['social'][$key])) { $url = trim($m['social'][$key]); }
  if ($url === '') { continue; }
  if (!preg_match('#^https?://#i', $url)) { $url = 'https://' . ltrim($url, '/'); }
  $mp_links[$key] = [$soc[0], $soc[1], $url];
}

/* resume (PDF in the theme's "resume" folder) */
$resume_url = '';
if (!empty($m['resume']) && is_string($m['resume'])) {
  $rf = basename($m['resume']);
  if ($rf !== '' && file_exists(get_template_directory() . '/resume/' . $rf)) {
    $resume_url = get_template_directory_uri() . '/resume/' . rawurlencode($rf);
  }
}

/* previous / next member */
$slugs = array_keys($team_members);
$total = count($slugs);
$idx   = (int) array_search($mp_slug, $slugs, true);
$prev_slug = $slugs[($idx - 1 + $total) % $total];
$next_slug = $slugs[($idx + 1) % $total];
$member_url = function ($s) { return esc_url(add_query_arg('member', $s, home_url('/'))); };

/* quick numbers (only the ones that have something to count) */
$mp_stats = [];
if ($skills)     { $mp_stats[] = ['Skills',    count($skills)]; }
if ($edu)        { $mp_stats[] = ['Education', count($edu)]; }
if ($role_tasks) { $mp_stats[] = ['Tasks',     count($role_tasks)]; }

/* ticker words */
$mq_words = $skills ?: [$role_label];
while (count($mq_words) < 10) { $mq_words = array_merge($mq_words, $mq_words); }

$has_contact = ($email || $phone || $addr);
?>

<main id="main">
<section class="mp" id="mp" aria-label="<?php echo esc_attr($name); ?> profile">

  <!-- background decoration -->
  <span class="mp-deco mp-d1" aria-hidden="true"></span>
  <span class="mp-deco mp-d2" aria-hidden="true"></span>
  <span class="mp-deco mp-d3" aria-hidden="true"></span>
  <span class="mp-deco mp-d4" aria-hidden="true"></span>
  <span class="mp-deco mp-d5" aria-hidden="true"><?php echo esc_html(strtoupper($last)); ?></span>

  <div class="wrap">

    <a class="mp-back" href="<?php echo esc_url(home_url('/#team')); ?>" data-mp="up">
      <?php echo $i_left; ?> Back to team
    </a>

    <!-- ===== 1. HERO ===== -->
    <div class="mp-hero">

      <div class="mp-visual" data-mp="left" data-mp-tilt>
        <span class="mp-sage" aria-hidden="true"></span>
        <span class="mp-ring" aria-hidden="true"></span>
        <div class="mp-photo">
          <img src="<?php echo esc_url(panto_img($m['image'])); ?>" alt="<?php echo esc_attr($name); ?>" decoding="async">
        </div>
      </div>

      <div class="mp-intro">
        <p class="mp-kicker" data-mp="right">Hello, I&rsquo;m</p>
        <h1 class="mp-name" data-mp="right">
          <?php if ($first !== '') : ?><span class="mp-first"><?php echo esc_html($first); ?></span> <?php endif; ?><span class="mp-last"><?php echo esc_html($last); ?></span>
        </h1>
        <p class="mp-role" data-mp="right"><span></span><?php echo esc_html($role_label); ?></p>

        <p class="mp-lead" data-mp="right">
          <?php echo wp_kses_post($about_short); ?>
          <?php if ($about_cut) : ?><a class="mp-more" href="#mp-about">Read more</a><?php endif; ?>
        </p>

        <div class="mp-cta" data-mp="up">
          <?php if ($mail) : ?>
            <a class="mp-btn mp-primary" href="<?php echo esc_attr($mail['gmail']); ?>"
               target="_blank" rel="noopener noreferrer"
               data-mailto="<?php echo esc_attr($mail['mailto']); ?>"
               aria-label="Email <?php echo esc_attr($name); ?> (opens Gmail)">Send email <?php echo $i_arrow; ?></a>
          <?php endif; ?>
          <?php if ($resume_url) : ?>
            <a class="mp-btn mp-outline" href="<?php echo esc_url($resume_url); ?>" target="_blank" rel="noopener" download>Resume <?php echo $i_down; ?></a>
          <?php endif; ?>
          <button type="button" class="mp-btn mp-ghost" data-mp-copy="<?php echo esc_attr($profile_url); ?>"><?php echo $i_share; ?><span>Copy profile link</span></button>
        </div>

        <?php if ($mp_stats) : ?>
          <div class="mp-stats" data-mp="up">
            <?php foreach ($mp_stats as $st) : ?>
              <div><b data-count="<?php echo (int) $st[1]; ?>"><?php echo (int) $st[1]; ?></b><span><?php echo esc_html($st[0]); ?></span></div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>

    </div>
  </div>

  <!-- ===== 2. SKILLS TICKER ===== -->
  <div class="mp-marq" aria-hidden="true" data-mp="zoom">
    <div class="mp-marq-track">
      <div class="mp-marq-group"><?php foreach ($mq_words as $w) : ?><span><?php echo esc_html($w); ?></span><?php endforeach; ?></div>
      <div class="mp-marq-group"><?php foreach ($mq_words as $w) : ?><span><?php echo esc_html($w); ?></span><?php endforeach; ?></div>
    </div>
  </div>

  <div class="wrap">

    <div class="mp-layout<?php echo ($has_contact || $mp_links) ? '' : ' no-side'; ?>">

      <!-- ===== 3. MAIN COLUMN ===== -->
      <div class="mp-col">

        <article class="mp-card mp-about" id="mp-about" data-mp="left">
          <span class="mp-quote" aria-hidden="true">&ldquo;</span>
          <div class="mp-card-h"><span class="mp-ico"><?php echo $i_user; ?></span><h2>About me</h2></div>
          <p class="mp-text"><?php echo esc_html($about); ?></p>
        </article>

        <?php if ($role_tasks || $role_desc) : ?>
        <article class="mp-card mp-roleblk" data-mp="left">
          <div class="mp-card-h"><span class="mp-ico"><?php echo $role_icon; ?></span><h2>Capstone role: <?php echo esc_html($role_label); ?></h2></div>
          <?php if ($role_desc) : ?><p class="mp-text"><?php echo esc_html($role_desc); ?></p><?php endif; ?>
          <?php if ($role_tasks) : ?>
            <ul class="mp-tasks" data-mp-stagger>
              <?php foreach ($role_tasks as $t) : ?><li><i><?php echo $i_check; ?></i><span><?php echo esc_html($t); ?></span></li><?php endforeach; ?>
            </ul>
          <?php endif; ?>
        </article>
        <?php endif; ?>

        <?php if ($edu || $skills) : ?>
        <div class="mp-duo<?php echo ($edu && $skills) ? '' : ' one'; ?>">

          <?php if ($edu) : ?>
          <article class="mp-card mp-edu" data-mp="left">
            <div class="mp-card-h"><span class="mp-ico"><?php echo $i_cap; ?></span><h2>Education</h2></div>
            <ol class="mp-tl" data-mp-stagger>
              <?php foreach ($edu as $e) :
                $e0 = $edu_get($e, 0, ['school', 'name', 'title', 'level']);
                $e1 = $edu_get($e, 1, ['course', 'degree', 'program', 'detail']);
                $e2 = $edu_get($e, 2, ['year', 'years', 'date', 'period']); ?>
                <li>
                  <b><?php echo esc_html($e0); ?></b>
                  <?php if ($e1 !== '') : ?><span><?php echo esc_html($e1); ?></span><?php endif; ?>
                  <?php if ($e2 !== '') : ?><small><?php echo esc_html($e2); ?></small><?php endif; ?>
                </li>
              <?php endforeach; ?>
            </ol>
          </article>
          <?php endif; ?>

          <?php if ($skills) : ?>
          <article class="mp-card mp-skills" data-mp="right">
            <div class="mp-card-h"><span class="mp-ico"><?php echo $i_bolt; ?></span><h2>Skills</h2></div>
            <ul class="mp-pills" data-mp-stagger>
              <?php foreach ($skills as $s) : ?><li><?php echo esc_html($s); ?></li><?php endforeach; ?>
            </ul>
          </article>
          <?php endif; ?>

        </div>
        <?php endif; ?>

      </div>

      <!-- ===== 4. SIDEBAR ===== -->
      <?php if ($has_contact || $mp_links) : ?>
      <aside class="mp-side" aria-label="Contact">

        <?php if ($has_contact) : ?>
        <div class="mp-card mp-contact" data-mp="right">
          <div class="mp-card-h"><span class="mp-ico"><?php echo $i_mail; ?></span><h2>Contact</h2></div>
          <ul class="mp-clist" data-mp-stagger>
            <?php if ($email) : ?>
              <li>
                <span class="mp-ci"><?php echo $i_mail; ?></span>
                <span class="mp-cv"><small>Email</small>
                  <?php if ($mail) : ?>
                    <a href="<?php echo esc_attr($mail['gmail']); ?>" target="_blank" rel="noopener noreferrer"
                       data-mailto="<?php echo esc_attr($mail['mailto']); ?>"><?php echo esc_html($email); ?></a>
                  <?php else : ?>
                    <em><?php echo esc_html($email); ?></em>
                  <?php endif; ?>
                </span>
              </li>
            <?php endif; ?>
            <?php if ($phone) : ?>
              <li>
                <span class="mp-ci mp-ci-v"><?php echo $i_phone; ?></span>
                <span class="mp-cv"><small>Phone</small>
                  <?php if ($phone_ok) : ?><a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $phone)); ?>"><?php echo esc_html($phone); ?></a>
                  <?php else : ?><em><?php echo esc_html($phone); ?></em><?php endif; ?>
                </span>
              </li>
            <?php endif; ?>
            <?php if ($addr) : ?>
              <li>
                <span class="mp-ci mp-ci-g"><?php echo $i_pin; ?></span>
                <span class="mp-cv"><small>Address</small><em><?php echo esc_html($addr); ?></em></span>
              </li>
            <?php endif; ?>
          </ul>
          <?php if ($email) : ?>
            <button type="button" class="mp-btn mp-ghost mp-block" data-mp-copy="<?php echo esc_attr($email); ?>"><?php echo $i_copy; ?><span>Copy email</span></button>
          <?php endif; ?>
        </div>
        <?php endif; ?>

        <?php if ($mp_links) : ?>
        <div class="mp-card mp-online" data-mp="right">
          <div class="mp-card-h"><span class="mp-ico"><?php echo $i_share; ?></span><h2>Find me online</h2></div>
          <ul class="mp-social" aria-label="<?php echo esc_attr($name); ?> on social media">
            <?php foreach ($mp_links as $l) : ?>
              <li>
                <a href="<?php echo esc_url($l[2]); ?>" target="_blank" rel="noopener noreferrer"
                   aria-label="<?php echo esc_attr($name . ' on ' . $l[0]); ?>" title="<?php echo esc_attr($l[0]); ?>"><?php echo $l[1]; ?><span><?php echo esc_html($l[0]); ?></span></a>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
        <?php endif; ?>

      </aside>
      <?php endif; ?>

    </div>

    <!-- ===== 5. PREVIOUS / NEXT MEMBER ===== -->
    <?php if ($total > 1) :
      $pm = $team_members[$prev_slug]; $nm = $team_members[$next_slug]; ?>
      <nav class="mp-pager" aria-label="More team members">
        <a class="mp-pg" rel="prev" href="<?php echo $member_url($prev_slug); ?>" data-mp="left">
          <?php echo $i_left; ?>
          <span class="mp-pg-av"><img src="<?php echo esc_url(panto_img($pm['image'])); ?>" alt="" loading="lazy" decoding="async"></span>
          <span class="mp-pg-t"><small>Previous member</small><b><?php echo esc_html($pm['name']); ?></b></span>
        </a>
        <a class="mp-pg mp-pg-next" rel="next" href="<?php echo $member_url($next_slug); ?>" data-mp="right">
          <span class="mp-pg-t"><small>Next member</small><b><?php echo esc_html($nm['name']); ?></b></span>
          <span class="mp-pg-av"><img src="<?php echo esc_url(panto_img($nm['image'])); ?>" alt="" loading="lazy" decoding="async"></span>
          <?php echo $i_arrow; ?>
        </a>
      </nav>
    <?php endif; ?>

  </div>

  <style>
  /* ===== PROFILE PAGE: light, formal, animated ===== */
  body main section.mp#mp {
    --mp-blue: #1f4fd8; --mp-sage: #a9b6a0; --mp-ink: #15161a; --mp-soft: #4a4e5a; --mp-line: rgba(0, 0, 0, .11);
    position: relative; overflow: clip;
    padding: clamp(110px, 13vw, 140px) 0 clamp(70px, 9vw, 110px) !important;
    border: 0 !important;
    background:
      radial-gradient(ellipse 55% 60% at 8% 6%, rgba(169, 182, 160, .5), transparent 70%),
      radial-gradient(ellipse 55% 60% at 94% 45%, rgba(31, 79, 216, .10), transparent 70%),
      radial-gradient(ellipse 60% 50% at 50% 0%, #ffffff, transparent 70%),
      linear-gradient(180deg, #f6f7f9 0%, #eceeef 100%) !important;
  }

  /* decoration */
  body main #mp .mp-deco { position: absolute; z-index: 0; display: block; pointer-events: none; user-select: none; }
  body main #mp .mp-d1 {
    top: 96px; right: clamp(8px, 4vw, 60px); width: 240px; height: 170px;
    background-image: radial-gradient(rgba(31, 79, 216, .36) 1.5px, transparent 2px); background-size: 22px 22px;
    -webkit-mask-image: linear-gradient(225deg, #000, transparent 80%); mask-image: linear-gradient(225deg, #000, transparent 80%);
  }
  body main #mp .mp-d2 {
    left: -150px; top: 46%; width: 420px; height: 420px; border-radius: 50%;
    background: var(--mp-sage); opacity: .3; outline: 1px solid rgba(31, 79, 216, .4); outline-offset: 20px;
  }
  body main #mp .mp-d3 {
    right: -110px; top: 60%; width: 300px; height: 300px; border-radius: 50%;
    border: 1px dashed rgba(31, 79, 216, .42); animation: spin 70s linear infinite;
  }
  body main #mp .mp-d4 {
    left: 7%; bottom: 120px; width: 44px; height: 44px; border-radius: 10px;
    background: var(--mp-blue); opacity: .16; animation: floaty 8s ease-in-out infinite;
  }
  body main #mp .mp-d5 {
    left: 0; right: 0; bottom: -.12em; text-align: center; white-space: nowrap; line-height: 1;
    font-family: var(--sans); font-weight: 800; font-size: clamp(4.5rem, 15vw, 14rem); letter-spacing: .02em;
    color: transparent !important; -webkit-text-fill-color: transparent !important; -webkit-text-stroke: 1.5px rgba(31, 79, 216, .12);
  }

  

  /* back link */
  body main #mp .mp-back {
    display: inline-flex; align-items: center; gap: 8px; margin-bottom: clamp(22px, 3vw, 34px);
    padding: 9px 18px; border-radius: 999px; background: #fff; border: 1px solid var(--mp-line);
    box-shadow: 0 6px 16px rgba(0, 0, 0, .06);
    font-family: var(--sans); font-size: .82rem; font-weight: 600; color: var(--mp-ink) !important;
    transition: background .25s, color .25s, border-color .25s, transform .25s;
  }
  body main #mp .mp-back svg { width: 16px; height: 16px; }
  body main #mp .mp-back:hover { background: var(--mp-blue); border-color: var(--mp-blue); color: #fff !important; transform: translateX(-4px); }

  /* ---------- hero ---------- */
  body main #mp .mp-hero { display: grid; grid-template-columns: minmax(0, .9fr) minmax(0, 1.1fr); gap: clamp(28px, 5vw, 72px); align-items: center; }

  body main #mp .mp-visual { position: relative; width: 100%; max-width: 420px; margin-inline: auto; aspect-ratio: 1; }
  body main #mp .mp-sage { position: absolute; inset: 6% -6% -6% 6%; border-radius: 50%; background: var(--mp-sage); opacity: .55; }
  body main #mp .mp-ring { position: absolute; inset: -22px; border-radius: 50%; border: 1px solid rgba(31, 79, 216, .38); }
  body main #mp .mp-ring::after {
    content: ""; position: absolute; top: 12%; left: 4%; width: 14px; height: 14px; border-radius: 50%;
    background: var(--mp-blue); box-shadow: 0 0 0 6px rgba(31, 79, 216, .15);
  }
  body main #mp .mp-photo {
    position: absolute; inset: 0; overflow: hidden; border-radius: 50%; background: var(--mp-sage);
    box-shadow: 0 0 0 8px rgba(255, 255, 255, .9), 0 34px 70px rgba(0, 0, 0, .2);
  }
  body main #mp .mp-photo img { width: 100%; height: 100%; object-fit: cover; object-position: center top; transition: transform 1s var(--ease); }
  body main #mp .mp-visual:hover .mp-photo img { transform: scale(1.07); }

  body main #mp .mp-kicker {
    display: flex; align-items: center; gap: 12px; margin: 0 0 12px;
    font-family: var(--sans); font-size: .92rem; font-weight: 600; color: var(--mp-blue) !important;
  }
  body main #mp .mp-kicker::before { content: ""; width: 28px; height: 2px; background: var(--mp-blue); }
  body main #mp .mp-name {
    margin: 0 0 14px !important; font-family: var(--sans) !important; text-transform: uppercase;
    font-size: clamp(2rem, 4.6vw, 3.8rem) !important; line-height: 1.04 !important; font-weight: 800 !important; letter-spacing: .005em !important;
    overflow-wrap: anywhere;
  }
  body main #mp .mp-name::before, body main #mp .mp-name::after { content: none !important; }
  body main #mp .mp-first { color: var(--mp-ink) !important; }
  body main #mp .mp-last  { color: var(--mp-blue) !important; }
  body main #mp .mp-role {
    display: inline-flex; align-items: center; gap: 10px; margin: 0 0 18px; padding: 7px 18px; border-radius: 999px;
    background: rgba(31, 79, 216, .08); border: 1px solid rgba(31, 79, 216, .25);
    font-family: var(--sans); font-size: .8rem; font-weight: 700; letter-spacing: .06em; color: var(--mp-blue) !important;
  }
  body main #mp .mp-role span { width: 8px; height: 8px; border-radius: 50%; background: var(--mp-blue); animation: mpPulse 2s ease-in-out infinite; }
  @keyframes mpPulse { 0%, 100% { box-shadow: 0 0 0 0 rgba(31, 79, 216, .5); } 50% { box-shadow: 0 0 0 7px rgba(31, 79, 216, 0); } }
  body main #mp .mp-lead {
    margin: 0 0 24px; max-width: 56ch; font-family: var(--sans); font-size: 1.02rem; line-height: 1.75;
    color: var(--mp-soft) !important;
  }
  body main #mp .mp-more { margin-left: 6px; font-weight: 700; color: var(--mp-blue) !important; border-bottom: 1px solid currentColor; }
  body main #mp .mp-more:hover { color: #183fb0 !important; }

  /* buttons */
  body main #mp .mp-cta { display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 26px; }
  body main #mp .mp-btn {
    display: inline-flex; align-items: center; justify-content: center; gap: 10px; padding: 13px 24px; border-radius: 999px; cursor: pointer; border: 0;
    font-family: var(--sans); font-size: .78rem; font-weight: 700; letter-spacing: .06em; text-transform: uppercase;
    transition: transform .2s, box-shadow .2s, background .2s, color .2s, border-color .2s;
  }
  body main #mp .mp-btn svg { width: 16px; height: 16px; flex: none; transition: transform .2s; }
  body main #mp .mp-primary { color: #fff !important; background: var(--mp-blue); box-shadow: 0 10px 24px rgba(31, 79, 216, .3); }
  body main #mp .mp-primary:hover { background: #183fb0; transform: translateY(-2px); box-shadow: 0 14px 30px rgba(31, 79, 216, .38); }
  body main #mp .mp-primary:hover svg { transform: translateX(4px); }
  body main #mp .mp-outline { color: var(--mp-ink) !important; background: transparent; border: 1px solid rgba(0, 0, 0, .3); }
  body main #mp .mp-outline:hover { background: var(--mp-ink); color: #fff !important; border-color: var(--mp-ink); transform: translateY(-2px); }
  body main #mp .mp-ghost { color: var(--mp-ink) !important; background: #fff; border: 1px solid var(--mp-line); box-shadow: 0 6px 16px rgba(0, 0, 0, .06); }
  body main #mp .mp-ghost:hover { border-color: var(--mp-blue); color: var(--mp-blue) !important; transform: translateY(-2px); }
  body main #mp .mp-ghost.done { background: #0f8a62; border-color: #0f8a62; color: #fff !important; }
  body main #mp .mp-block { width: 100%; margin-top: 16px; }
  body main #mp .mp-btn:focus-visible, body main #mp a:focus-visible { outline: 2px solid var(--mp-blue); outline-offset: 3px; }

  /* quick numbers */
  body main #mp .mp-stats { display: flex; flex-wrap: wrap; gap: 0; padding-top: 22px; border-top: 1px solid var(--mp-line); }
  body main #mp .mp-stats div { display: flex; flex-direction: column; padding-right: 32px; margin-right: 32px; border-right: 1px solid var(--mp-line); }
  body main #mp .mp-stats div:last-child { border-right: 0; margin-right: 0; padding-right: 0; }
  body main #mp .mp-stats b { font-family: var(--sans); font-size: 1.9rem; font-weight: 800; line-height: 1.1; color: var(--mp-ink) !important; }
  body main #mp .mp-stats span { font-family: var(--sans); font-size: .78rem; font-weight: 600; letter-spacing: .04em; color: var(--mp-soft) !important; }

  /* ---------- ticker ---------- */
  body main #mp .mp-marq {
    position: relative; z-index: 2; overflow: hidden; margin: clamp(46px, 6vw, 70px) 0 clamp(36px, 5vw, 56px);
    padding: 14px 0; background: #fff; border-block: 1px solid var(--mp-line);
  }
  body main #mp .mp-marq-track { display: flex; width: max-content; animation: mpMarq 38s linear infinite; }
  body main #mp .mp-marq-group { display: flex; flex-shrink: 0; }
  body main #mp .mp-marq-group span {
    display: inline-flex; align-items: center; gap: 1.1em; padding-right: 1.1em; white-space: nowrap;
    font-family: var(--sans); font-size: clamp(.95rem, 1.8vw, 1.25rem); font-weight: 700; letter-spacing: .04em; text-transform: uppercase;
    color: var(--mp-ink) !important;
  }
  body main #mp .mp-marq-group span::after { content: "/"; color: var(--mp-blue); font-weight: 800; }
  body main #mp .mp-marq-group span:nth-child(even) { color: transparent !important; -webkit-text-fill-color: transparent !important; -webkit-text-stroke: 1px rgba(21, 22, 26, .55); }
  body main #mp .mp-marq-group span:nth-child(even)::after { -webkit-text-fill-color: var(--mp-blue); }
  @keyframes mpMarq { to { transform: translateX(-50%); } }

  /* ---------- layout: main column + sticky sidebar ---------- */
  body main #mp .mp-layout { display: grid; grid-template-columns: minmax(0, 1fr) minmax(280px, 340px); gap: 22px; align-items: start; }
  body main #mp .mp-layout.no-side { grid-template-columns: minmax(0, 1fr); }
  body main #mp .mp-col { display: grid; gap: 22px; min-width: 0; }
  body main #mp .mp-side { display: grid; gap: 22px; position: sticky; top: 90px; min-width: 0; }
  body main #mp .mp-duo { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 22px; align-items: start; }
  body main #mp .mp-duo.one { grid-template-columns: minmax(0, 1fr); }

  body main #mp .mp-card {
    --a: #1f4fd8;
    position: relative; overflow: hidden; padding: clamp(22px, 3vw, 30px); border-radius: 22px;
    background: #fff; border: 1px solid var(--mp-line); border-top: 3px solid var(--a);
    box-shadow: 0 14px 34px rgba(0, 0, 0, .07);
    scroll-margin-top: 96px;
  }
  body main #mp .mp-contact, body main #mp .mp-edu { --a: #7c4dff; }
  body main #mp .mp-skills, body main #mp .mp-online { --a: #0f9d73; }
  body main #mp .mp-roleblk { --a: #d9822b; }

  body main #mp .mp-quote {
    position: absolute; right: 22px; top: -8px; pointer-events: none; user-select: none;
    font: 800 7rem/1 Georgia, serif; color: var(--a) !important; opacity: .1;
  }
  body main #mp .mp-card-h { display: flex; align-items: center; gap: 12px; margin-bottom: 18px; }
  body main #mp .mp-card-h h2 {
    margin: 0 !important; font-family: var(--sans) !important; font-size: 1.12rem !important; font-weight: 800 !important;
    letter-spacing: -.01em !important; color: var(--mp-ink) !important; -webkit-text-fill-color: var(--mp-ink) !important;
  }
  body main #mp .mp-card-h h2::before, body main #mp .mp-card-h h2::after { content: none !important; }
  body main #mp .mp-ico {
    display: grid; place-items: center; flex: none; width: 40px; height: 40px; border-radius: 50%;
    background: color-mix(in srgb, var(--a) 12%, #fff); color: var(--a) !important;
  }
  body main #mp .mp-ico svg { width: 19px; height: 19px; }
  body main #mp .mp-text {
    margin: 0; max-width: 70ch; font-family: var(--sans); font-size: 1rem; line-height: 1.85; color: var(--mp-soft) !important;
    text-align: left;
  }

  /* contact list */
  body main #mp .mp-clist { display: grid; gap: 12px; margin: 0; padding: 0; list-style: none; }
  body main #mp .mp-clist li {
    display: flex; align-items: center; gap: 14px; padding: 12px 14px; border-radius: 16px;
    background: #f6f7f9; border: 1px solid var(--mp-line);
    transition: background .25s, border-color .25s;
  }
  body main #mp .mp-clist li:hover { background: #fff; border-color: var(--mp-blue); }
  body main #mp .mp-ci {
    display: grid; place-items: center; flex: none; width: 40px; height: 40px; border-radius: 50%;
    background: rgba(31, 79, 216, .1); color: var(--mp-blue) !important;
  }
  body main #mp .mp-ci svg { width: 18px; height: 18px; }
  body main #mp .mp-ci-v { background: rgba(124, 77, 255, .1); color: #7c4dff !important; }
  body main #mp .mp-ci-g { background: rgba(15, 157, 115, .1); color: #0f9d73 !important; }
  body main #mp .mp-cv { display: flex; flex-direction: column; min-width: 0; font-family: var(--sans); font-size: .93rem; overflow-wrap: anywhere; }
  body main #mp .mp-cv small { font-size: .74rem; font-weight: 600; letter-spacing: .04em; color: var(--mp-soft) !important; }
  body main #mp .mp-cv a, body main #mp .mp-cv em { font-style: normal; font-weight: 600; color: var(--mp-ink) !important; }
  body main #mp .mp-cv a:hover { color: var(--mp-blue) !important; }

  /* social: icon + name rows */
  body main #mp .mp-social { display: grid; gap: 10px; margin: 0; padding: 0; list-style: none; }
  body main #mp .mp-social li { padding: 0; background: none; border: 0; }
  body main #mp .mp-social a {
    display: flex; align-items: center; gap: 12px; padding: 10px 14px; border-radius: 14px; cursor: pointer;
    color: var(--mp-ink) !important; background: #f6f7f9; border: 1px solid var(--mp-line);
    font-family: var(--sans); font-size: .9rem; font-weight: 600;
    transition: background .25s, color .25s, border-color .25s, transform .25s;
  }
  body main #mp .mp-social a svg { width: 20px; height: 20px; flex: none; }
  body main #mp .mp-social a:hover { background: var(--mp-blue); border-color: var(--mp-blue); color: #fff !important; transform: translateX(4px); }

  /* role tasks */
  body main #mp .mp-roleblk .mp-text { margin-bottom: 16px; }
  body main #mp .mp-tasks { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; margin: 0; padding: 0; list-style: none; }
  body main #mp .mp-tasks li {
    display: flex; align-items: flex-start; gap: 12px; padding: 14px 16px; border-radius: 16px;
    font-family: var(--sans); font-size: .93rem; line-height: 1.5; color: var(--mp-ink) !important;
    background: #f6f7f9; border: 1px solid var(--mp-line); transition: background .25s, border-color .25s;
  }
  body main #mp .mp-tasks li:hover { background: #fff; border-color: var(--a); }
  body main #mp .mp-tasks li span { color: var(--mp-ink) !important; }
  body main #mp .mp-tasks i {
    display: grid; place-items: center; flex: none; width: 24px; height: 24px; margin-top: 1px; border-radius: 50%;
    background: var(--a); color: #fff !important; font-style: normal;
  }
  body main #mp .mp-tasks i svg { width: 13px; height: 13px; stroke-width: 3.2; }

  /* education timeline */
  body main #mp .mp-tl { display: grid; gap: 22px; margin: 0; padding: 0; list-style: none; }
  body main #mp .mp-tl li { position: relative; display: flex; flex-direction: column; gap: 2px; padding-left: 34px; }
  body main #mp .mp-tl li::before {
    content: ""; position: absolute; left: 0; top: 4px; width: 16px; height: 16px; border-radius: 50%; background: #fff;
    border: 3px solid var(--a); box-shadow: 0 0 0 5px color-mix(in srgb, var(--a) 14%, transparent);
  }
  body main #mp .mp-tl li::after { content: ""; position: absolute; left: 7px; top: 26px; bottom: -24px; width: 2px; background: color-mix(in srgb, var(--a) 28%, transparent); }
  body main #mp .mp-tl li:last-child::after { display: none; }
  body main #mp .mp-tl b { font-family: var(--sans); font-size: 1rem; color: var(--mp-ink) !important; }
  body main #mp .mp-tl span { font-family: var(--sans); font-size: .9rem; color: var(--mp-soft) !important; }
  body main #mp .mp-tl small {
    width: fit-content; margin-top: 5px; padding: 3px 12px; border-radius: 999px;
    font-family: var(--sans); font-size: .76rem; font-weight: 700; letter-spacing: .02em;
    background: color-mix(in srgb, var(--a) 10%, #fff); color: var(--a) !important;
  }

  /* skill pills */
  body main #mp .mp-pills { display: flex; flex-wrap: wrap; gap: 10px; margin: 0; padding: 0; list-style: none; }
  body main #mp .mp-pills li {
    --c: #1f4fd8;
    padding: 8px 18px; border-radius: 999px; cursor: default;
    font-family: var(--sans); font-size: .86rem; font-weight: 600;
    color: var(--c) !important; background: color-mix(in srgb, var(--c) 9%, #fff); border: 1px solid color-mix(in srgb, var(--c) 30%, transparent);
    transition: background .25s, color .25s, transform .25s, box-shadow .25s;
  }
  body main #mp .mp-pills li:nth-child(4n+2) { --c: #0f9d73; }
  body main #mp .mp-pills li:nth-child(4n+3) { --c: #7c4dff; }
  body main #mp .mp-pills li:nth-child(4n+4) { --c: #d9822b; }
  body main #mp .mp-pills li:hover { background: var(--c); color: #fff !important; transform: translateY(-3px); box-shadow: 0 10px 20px color-mix(in srgb, var(--c) 35%, transparent); }

  /* pager */
  body main #mp .mp-pager { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 18px; margin-top: 26px; }
  body main #mp .mp-pg {
    display: flex; align-items: center; gap: 16px; padding: 16px 20px; border-radius: 20px;
    background: #fff; border: 1px solid var(--mp-line); box-shadow: 0 10px 26px rgba(0, 0, 0, .06);
    transition: transform .35s var(--ease), border-color .3s, box-shadow .3s;
  }
  body main #mp .mp-pg-next { justify-content: flex-end; text-align: right; }
  body main #mp .mp-pg:hover { transform: translateY(-4px); border-color: var(--mp-blue); box-shadow: 0 20px 40px rgba(31, 79, 216, .16); }
  body main #mp .mp-pg > svg {
    flex: none; width: 40px; height: 40px; padding: 11px; border-radius: 50%;
    color: var(--mp-blue) !important; background: rgba(31, 79, 216, .09); transition: background .25s, color .25s;
  }
  body main #mp .mp-pg:hover > svg { background: var(--mp-blue); color: #fff !important; }
  body main #mp .mp-pg-av { flex: none; width: 52px; height: 52px; border-radius: 50%; overflow: hidden; background: var(--mp-sage); box-shadow: 0 0 0 3px #fff, 0 0 0 4px rgba(31, 79, 216, .35); }
  body main #mp .mp-pg-av img { width: 100%; height: 100%; object-fit: cover; object-position: center top; }
  body main #mp .mp-pg-t { display: flex; flex-direction: column; min-width: 0; }
  body main #mp .mp-pg-t small { font-family: var(--sans); font-size: .76rem; font-weight: 600; color: var(--mp-soft) !important; }
  body main #mp .mp-pg-t b { font-family: var(--sans); font-size: 1rem; color: var(--mp-ink) !important; }

  /* ===== scroll IN / OUT (only when JS runs) ===== */
  html.mp-js body main #mp [data-mp] { transition: opacity .8s var(--ease), transform .9s var(--ease), box-shadow .4s ease, border-color .3s, background .25s, color .25s; will-change: opacity, transform; }
  html.mp-js body main #mp [data-mp]:not(.mp-in)         { opacity: 0 !important; transform: translateY(60px) scale(.96); }
  html.mp-js body main #mp [data-mp="left"]:not(.mp-in)  { transform: translateX(-70px) scale(.96); }
  html.mp-js body main #mp [data-mp="right"]:not(.mp-in) { transform: translateX(70px) scale(.96); }
  html.mp-js body main #mp [data-mp="zoom"]:not(.mp-in)  { transform: scale(.92); }
  html.mp-js body main #mp [data-mp].mp-up:not(.mp-in)   { transform: translateY(-50px) scale(.96); }
  html.mp-js body main #mp [data-mp].mp-down:not(.mp-in) { transform: translateY(60px) scale(.96); }
  html.mp-js body main #mp [data-mp].mp-in               { opacity: 1 !important; transform: none; }
  html.mp-js body main #mp .mp-pg.mp-in:hover            { transform: translateY(-4px); }

  /* list items: fade + rise, one after another */
  html.mp-js body main #mp [data-mp-stagger] > * { transition: opacity .6s var(--ease), transform .7s var(--ease), background .25s, color .25s, border-color .25s, box-shadow .25s; transition-delay: var(--sd, 0s); }
  html.mp-js body main #mp [data-mp-stagger]:not(.mp-sin) > * { opacity: 0 !important; transform: translateY(26px) scale(.96); }
  html.mp-js body main #mp [data-mp-stagger].mp-sin > *     { opacity: 1 !important; transform: none; }

  /* ===== responsive ===== */
  @media (max-width: 960px) {
    body main #mp .mp-hero { grid-template-columns: 1fr; }
    body main #mp .mp-visual { max-width: 320px; }
    body main #mp .mp-layout { grid-template-columns: minmax(0, 1fr); }
    body main #mp .mp-side { position: static; }
  }
  @media (max-width: 700px) {
    body main #mp .mp-duo, body main #mp .mp-tasks, body main #mp .mp-pager { grid-template-columns: 1fr; }
  }
  @media (max-width: 640px) {
    body main #mp .mp-d1, body main #mp .mp-d4 { display: none; }
    body main #mp .mp-stats div { padding-right: 20px; margin-right: 20px; }
    body main #mp .mp-cta .mp-btn { flex: 1 1 100%; }
    body main #mp .mp-text { text-align: left; }
    body main #mp .mp-pg-next { justify-content: flex-start; text-align: left; flex-direction: row-reverse; }
    body main #mp .mp-pg-next > svg { display: none; }
  }
  </style>

  <script>
  (function () {
    var sec = document.getElementById('mp');
    if (!sec) return;
    var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    /* ---------- email: Gmail on desktop, mailto: (mail app) on phones ---------- */
    if (/Android|iPhone|iPad|iPod|Mobile/i.test(navigator.userAgent)) {
      [].forEach.call(sec.querySelectorAll('a[data-mailto]'), function (a) {
        a.href = a.getAttribute('data-mailto');
        a.removeAttribute('target');
      });
    }

    /* ---------- copy buttons ---------- */
    [].forEach.call(sec.querySelectorAll('[data-mp-copy]'), function (b) {
      var label = b.querySelector('span'), old = label ? label.textContent : '';
      function ok() {
        b.classList.add('done'); if (label) label.textContent = 'Copied!';
        setTimeout(function () { b.classList.remove('done'); if (label) label.textContent = old; }, 1800);
      }
      function fallback(text) {
        var t = document.createElement('textarea');
        t.value = text; t.style.position = 'fixed'; t.style.opacity = '0';
        document.body.appendChild(t); t.select();
        try { document.execCommand('copy'); ok(); } catch (e) {}
        document.body.removeChild(t);
      }
      b.addEventListener('click', function () {
        var text = b.getAttribute('data-mp-copy');
        if (navigator.clipboard && navigator.clipboard.writeText) navigator.clipboard.writeText(text).then(ok, function () { fallback(text); });
        else fallback(text);
      });
    });

    /* ---------- social links: open the member's own page directly ---------- */
    sec.addEventListener('click', function (e) {
      var a = e.target.closest ? e.target.closest('.mp-social a') : null;
      if (!a) return;
      var href = a.getAttribute('href');
      if (!href || href === '#') return;
      e.preventDefault(); e.stopPropagation();
      window.open(href, '_blank', 'noopener');
    }, true);

    /* ---------- count up (the real number is already in the HTML) ---------- */
    var counters = sec.querySelectorAll('[data-count]');
    function countUp(el) {
      var to = parseInt(el.getAttribute('data-count'), 10) || 0;
      if (reduce || to < 1) { el.textContent = to; return; }
      var t0 = null, dur = 900;
      el.textContent = '0';
      function step(ts) {
        if (t0 === null) t0 = ts;
        var p = Math.min((ts - t0) / dur, 1);
        el.textContent = Math.round(to * (1 - Math.pow(1 - p, 3)));
        if (p < 1) requestAnimationFrame(step);
      }
      requestAnimationFrame(step);
    }

    /* ---------- photo tilt (desktop only) ---------- */
    var vis = sec.querySelector('[data-mp-tilt]');
    if (vis && !reduce && window.matchMedia && window.matchMedia('(hover: hover)').matches) {
      var ph = vis.querySelector('.mp-photo');
      ph.style.transition = 'transform .35s ease';
      vis.addEventListener('mousemove', function (e) {
        var r = vis.getBoundingClientRect();
        var x = (e.clientX - r.left) / r.width - .5, y = (e.clientY - r.top) / r.height - .5;
        ph.style.transform = 'perspective(900px) rotateY(' + (x * 10) + 'deg) rotateX(' + (-y * 10) + 'deg)';
      });
      vis.addEventListener('mouseleave', function () { ph.style.transform = ''; });
    }

    /* ---------- scroll in / out ---------- */
    var els = sec.querySelectorAll('[data-mp]');
    var lists = sec.querySelectorAll('[data-mp-stagger]');
    [].forEach.call(lists, function (ul) {
      [].forEach.call(ul.children, function (li, i) { li.style.setProperty('--sd', (i * 0.09) + 's'); });
    });

    if (reduce || !('IntersectionObserver' in window)) return;   // everything stays visible
    document.documentElement.classList.add('mp-js');

    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        var el = en.target;
        if (en.isIntersecting) { el.classList.remove('mp-up', 'mp-down'); el.classList.add('mp-in'); }
        else { el.classList.remove('mp-in'); el.classList.add(en.boundingClientRect.top < 0 ? 'mp-up' : 'mp-down'); }
      });
    }, { threshold: 0.12, rootMargin: '0px 0px -6% 0px' });
    [].forEach.call(els, function (el) { io.observe(el); });

    var io2 = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        if (en.isIntersecting) en.target.classList.add('mp-sin');
        else en.target.classList.remove('mp-sin');
      });
    }, { threshold: 0.2 });
    [].forEach.call(lists, function (ul) { io2.observe(ul); });

    var done = false;
    var st = sec.querySelector('.mp-stats');
    if (st && counters.length) {
      new IntersectionObserver(function (entries) {
        if (done || !entries[0].isIntersecting) return;
        done = true; [].forEach.call(counters, countUp);
      }, { threshold: 0.4 }).observe(st);
    }
  })();
  </script>
</section>
</main>