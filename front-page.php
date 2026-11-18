<?php
// Member info
require_once get_template_directory() . '/team-data.php';
require_once get_template_directory() . '/role-data.php';
$team_members = occ_team_with_roles();

get_header();

// Profile page (?member=slug)
if (isset($_GET['member']) && is_string($_GET['member'])
    && isset($team_members[sanitize_key(wp_unslash($_GET['member']))])) {
  include get_template_directory() . '/member-profile.php';
  get_footer();
  return;
}

$arrow = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>';
$left  = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5M11 6l-6 6 6 6"/></svg>';
$up    = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 19V5M6 11l6-6 6 6"/></svg>';
$ne    = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 17 17 7M8 7h9v9"/></svg>';
$icon  = function ($path) {
  return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $path . '</svg>';
};

/* Social icons shown under the skills (Facebook, Twitter, Instagram, TikTok).
   Links come from team-data.php: 'facebook', 'twitter', 'instagram', 'tiktok'
   (or inside a 'social' => [...] array). Missing link = the icon is not shown. */
// $socials = [
//   'facebook'  => ['Facebook',  '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M22 12a10 10 0 1 0-11.56 9.88v-6.99H7.9V12h2.54V9.8c0-2.5 1.49-3.89 3.77-3.89 1.09 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56V12h2.78l-.44 2.89h-2.34v6.99A10 10 0 0 0 22 12z"/></svg>'],
//   'twitter'   => ['Twitter',   '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M22.46 6c-.77.35-1.6.58-2.46.69a4.3 4.3 0 0 0 1.88-2.37 8.6 8.6 0 0 1-2.72 1.04 4.28 4.28 0 0 0-7.29 3.9A12.15 12.15 0 0 1 3.07 4.8a4.28 4.28 0 0 0 1.33 5.71 4.27 4.27 0 0 1-1.94-.54v.05a4.28 4.28 0 0 0 3.43 4.2 4.3 4.3 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.97A8.6 8.6 0 0 1 2 19.54a12.1 12.1 0 0 0 6.56 1.92c7.88 0 12.2-6.53 12.2-12.2l-.01-.56A8.7 8.7 0 0 0 22.46 6z"/></svg>'],
//   'instagram' => ['Instagram', '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>'],
//   'tiktok'    => ['TikTok',    '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M16.6 5.82A4.28 4.28 0 0 1 15.54 3h-3.09v12.4a2.59 2.59 0 0 1-2.59 2.5 2.6 2.6 0 0 1-2.6-2.6 2.6 2.6 0 0 1 2.6-2.6c.27 0 .53.04.77.12V9.66a5.7 5.7 0 0 0-.77-.05A5.69 5.69 0 0 0 4.17 15.3a5.69 5.69 0 0 0 5.69 5.69 5.69 5.69 0 0 0 5.69-5.69V9.01a7.35 7.35 0 0 0 4.29 1.37V7.3a4.3 4.3 0 0 1-3.24-1.48z"/></svg>'],
// ];

/* Skills for the marquee: collected from every member's profile (no duplicates) */
$skills = [];
foreach ($team_members as $mm) {
  foreach (($mm['skills'] ?? []) as $s) { $skills[strtolower(trim($s))] = trim($s); }
}
$skills = array_values($skills);
if (!$skills) { $skills = ['HTML', 'CSS', 'JavaScript', 'PHP', 'MySQL', 'WordPress', 'UI/UX', 'Git']; }
$mq = function (array $items) {
  while (count($items) < 12) { $items = array_merge($items, $items); }
  foreach ($items as $it) { echo '<span class="mq-item">' . esc_html($it) . '</span>'; }
};

/* ---------- FEEDBACK ----------
   Curated feedback (adviser etc). Add more entries to this list: arrows, dots and
   autoplay appear by themselves when there is more than one.
   Visitor comments are saved separately (see functions.php). */
$feedbacks = [
  [
    'quote'  => 'This team demonstrated excellent collaboration and technical skills. Their capstone project was well-executed, highly functional, and exceeded our expectations.',
    'name'   => 'Prof. Rachel Ana Dantes',
    'role'   => 'Project Adviser',
    'img'    => 'Rachel.jpg',
    'rating' => 5,
  ],
];

$quote_icon = '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M7.2 6C4.9 7.4 3.5 9.7 3.5 12.6V18h6v-6H6.3c.1-1.6.9-2.8 2.4-3.6L7.2 6zm9.5 0c-2.3 1.4-3.7 3.7-3.7 6.6V18h6v-6h-3.2c.1-1.6.9-2.8 2.4-3.6L16.7 6z"/></svg>';
?>

<main id="main">

<!-- HERO -->
<section class="hero">
  <!-- giant faded word behind the team -->
  <h1 class="hero-ghost">Meet the Minds Behind the Work</h1>

  <img class="hero-team" src="<?php echo esc_url(panto_img('team.png')); ?>" alt="Our team" decoding="async">
</section>

<!-- SKILLS MARQUEE (speeds up and reverses with your scroll) -->
<div class="marquee" aria-hidden="true">
  <div class="mq-row">
    <div class="mq-track" data-dir="-1" data-speed=".7">
      <div class="mq-group"><?php $mq($skills); ?></div>
      <div class="mq-group"><?php $mq($skills); ?></div>
    </div>
  </div>
  <div class="mq-row mq-outline">
    <div class="mq-track" data-dir="1" data-speed=".5">
      <div class="mq-group"><?php $mq(array_reverse($skills)); ?></div>
      <div class="mq-group"><?php $mq(array_reverse($skills)); ?></div>
    </div>
  </div>
</div>

<?php
$tm_svg = function ($path, $fill = false) {
  return $fill
    ? '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">' . $path . '</svg>'
    : '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $path . '</svg>';
};
$tm_i_mail = $tm_svg('<rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/>');
$tm_total  = count($team_members);
$tm_i      = 0;
?>
<!-- TEAM (one member at a time, arrows left / right) -->
<section class="team-section" id="team">
  <span class="ghost" aria-hidden="true">TEAM</span>

  <!-- background decoration -->
  <span class="tm-deco tm-d1" aria-hidden="true"></span>
  <span class="tm-deco tm-d2" aria-hidden="true"></span>
  <span class="tm-deco tm-d3" aria-hidden="true"></span>
  <span class="tm-deco tm-d4" aria-hidden="true"></span>
  <span class="tm-deco tm-d5" aria-hidden="true">TEAM</span>

  <div class="wrap">

    <div class="tm-head">
      <div data-tma="left">
        <p class="tm-kicker">Our team</p>
        <h2 class="tm-h2">Meet our team</h2>
      </div>
      <p class="tm-lead" data-tma="right">The people behind the work. Browse each member, read about them and get in touch.</p>
    </div>

    <div class="tm-showcase" data-tm data-tma="up" role="region" aria-roledescription="carousel" aria-label="Team members">

      <button type="button" class="tm-nav tm-prev" aria-label="Previous member"><?php echo $left; ?></button>

      <div class="tm-stage">
        <?php foreach ($team_members as $slug => $m) :
          $rm = occ_role_meta($m['role'] ?? '');

          // "About me" text: uses the first of these keys that exists in team-data.php
          $about = '';
          foreach (['about', 'bio', 'summary', 'description', 'intro'] as $k) {
            if (!empty($m[$k]) && is_string($m[$k])) { $about = $m[$k]; break; }
          }
          if ($about === '') { $about = $m['name'] . ' is the ' . $rm['label'] . ' of our capstone team.'; }

          $m_skills = array_filter((array) ($m['skills'] ?? []));

          // split name: first part dark, last word blue
          $parts = preg_split('/\s+/', trim($m['name']));
          $last  = count($parts) > 1 ? array_pop($parts) : '';
          $first = implode(' ', $parts);

          $m_email = !empty($m['email']) && is_string($m['email']) ? trim($m['email']) : '';
          $m_url   = add_query_arg('member', $slug, home_url('/'));

          // Email button: opens a new Gmail compose window (phones use the normal mailto: link instead)
          $mail_subj  = rawurlencode('Hello ' . $m['name']);
          $mail_body  = rawurlencode('Hi ' . $m['name'] . ",\n\n");
          $gmail_url  = $m_email ? 'https://mail.google.com/mail/?view=cm&fs=1&to=' . rawurlencode($m_email) . '&su=' . $mail_subj . '&body=' . $mail_body : '';
          $mailto_url = $m_email ? 'mailto:' . $m_email . '?subject=' . $mail_subj . '&body=' . $mail_body : '';
        ?>
          <article class="tm-slide<?php echo $tm_i === 0 ? ' show' : ''; ?>"
                   style="--a: var(<?php echo esc_attr($rm['color']); ?>)"
                   aria-hidden="<?php echo $tm_i === 0 ? 'false' : 'true'; ?>">

            <div class="tm-visual">
              <!-- <span class="tm-sage" aria-hidden="true"></span> -->
              <div class="tm-pic">
                <img src="<?php echo esc_url(panto_img($m['image'])); ?>" alt="<?php echo esc_attr($m['name']); ?>" decoding="async">
              </div>
            </div>

            <div class="tm-info">
              <p class="tm-hello">Hello, I'm</p>
              <h3 class="tm-title">
                <span class="tm-first"><?php echo esc_html($first); ?></span>
                <?php if ($last !== '') : ?><span class="tm-last"><?php echo esc_html($last); ?></span><?php endif; ?>
              </h3>
              <div class="tm-badge"><?php echo esc_html($rm['label']); ?></div>
              <span class="tm-line" aria-hidden="true"></span>
              <p class="tm-about"><?php echo esc_html($about); ?></p>

                            <p class="tm-about"><?php echo esc_html($about); ?></p>

              <!-- social icons (left) + buttons (right) in one row -->
              <div class="tm-row">

                
                <div class="tm-actions">
                  <a class="tm-cta" href="<?php echo esc_url($m_url); ?>">View full profile <?php echo $arrow; ?></a>
                  <?php if ($m_email) : ?>
                    <a class="tm-cta2" href="<?php echo esc_url($gmail_url); ?>" target="_blank" rel="noopener noreferrer"
                       data-mailto="<?php echo esc_attr($mailto_url); ?>"
                       aria-label="Email <?php echo esc_attr($m['name']); ?> (opens Gmail)"><?php echo $tm_i_mail; ?>Email</a>
                  <?php endif; ?>
                </div>

              </div>

            <aside class="tm-side" aria-hidden="true">
            </aside>
          </article>
        <?php $tm_i++; endforeach; ?>
      </div>

      <button type="button" class="tm-nav tm-next" aria-label="Next member"><?php echo $arrow; ?></button>

      <!-- avatars of every member (click to jump) -->
      <div class="tm-dots">
        <?php $d = 0; foreach ($team_members as $slug => $m) : ?>
          <button type="button" class="<?php echo $d === 0 ? 'active' : ''; ?>" aria-label="Show <?php echo esc_attr($m['name']); ?>">
            <img src="<?php echo esc_url(panto_img($m['image'])); ?>" alt="" loading="lazy" decoding="async">
          </button>
        <?php $d++; endforeach; ?>
      </div>
    </div>
  </div>

  <style>
  /* ===== TEAM: light, formal, animated (same family as the other sections) ===== */
  body main section.team-section#team {
    --tm-blue: #1f4fd8; --tm-ink: #15161a; --tm-soft: #4a4e5a; --tm-line: rgba(0, 0, 0, .12);
    padding: clamp(64px, 9vw, 110px) 0 clamp(70px, 9vw, 120px) !important;
    background:
      radial-gradient(ellipse 55% 60% at 90% 4%, rgba(31, 79, 216, .13), transparent 70%),
      radial-gradient(ellipse 50% 55% at 4% 96%, rgba(169, 182, 160, .5), transparent 70%),
      linear-gradient(180deg, #f6f7f9 0%, #eceeef 100%) !important;
  }

  /* decoration */
  body main #team .tm-deco { position: absolute; z-index: 0; display: block; pointer-events: none; user-select: none; }
  body main #team .tm-d1 {
    top: 70px; left: clamp(8px, 4vw, 60px); width: 240px; height: 170px;
    background-image: radial-gradient(rgba(31, 79, 216, .36) 1.5px, transparent 2px); background-size: 22px 22px;
    -webkit-mask-image: linear-gradient(135deg, #000, transparent 80%); mask-image: linear-gradient(135deg, #000, transparent 80%);
  }
  body main #team .tm-d2 {
    right: -150px; bottom: -160px; width: 440px; height: 440px; border-radius: 50%;
    background: #a9b6a0; opacity: .32; outline: 1px solid rgba(31, 79, 216, .4); outline-offset: 20px;
  }
  body main #team .tm-d3 {
    left: -110px; top: 42%; width: 300px; height: 300px; border-radius: 50%;
    border: 1px dashed rgba(31, 79, 216, .42); animation: spin 70s linear infinite;
  }
  body main #team .tm-d4 {
    right: 6%; top: 110px; width: 44px; height: 44px; border-radius: 10px;
    background: #1f4fd8; opacity: .16; animation: floaty 8s ease-in-out infinite;
  }
  body main #team .tm-d5 {
    left: 0; right: 0; bottom: -.12em; text-align: center; white-space: nowrap; line-height: 1;
    font-family: var(--sans); font-weight: 800; font-size: clamp(5rem, 17vw, 16rem); letter-spacing: .02em;
    color: transparent !important; -webkit-text-fill-color: transparent !important; -webkit-text-stroke: 1.5px rgba(31, 79, 216, .12);
  }

  /* heading */
  body main #team .tm-head {
    display: grid; grid-template-columns: minmax(0, 1.1fr) minmax(0, .9fr);
    gap: clamp(20px, 5vw, 72px); align-items: end; margin-bottom: clamp(34px, 5vw, 56px);
  }
  body main #team .tm-kicker {
    display: flex; align-items: center; gap: 12px; margin: 0 0 14px;
    font-family: var(--sans); font-size: .78rem; font-weight: 700; letter-spacing: .2em; text-transform: uppercase;
    color: var(--tm-blue) !important;
  }
  body main #team .tm-kicker::before { content: ""; width: 28px; height: 2px; background: var(--tm-blue); }
  body main #team .tm-h2 {
    margin: 0 !important; max-width: 14ch; text-align: left;
    font-family: var(--sans) !important; font-weight: 800 !important;
    font-size: clamp(1.9rem, 4.4vw, 3.2rem) !important; line-height: 1.08 !important; letter-spacing: -.02em !important;
    color: var(--tm-ink) !important; -webkit-text-fill-color: var(--tm-ink) !important;
  }
  body main #team .tm-h2::before, body main #team .tm-h2::after { content: none !important; }
  body main #team .tm-lead {
    margin: 0; max-width: 46ch; padding-left: 18px; border-left: 2px solid var(--tm-blue);
    font-family: var(--sans); font-size: 1rem; line-height: 1.75; color: var(--tm-soft) !important;
  }

  /* photo */
  body main #team .tm-visual {
    order: 2; position: relative; width: 100%; max-width: 360px; aspect-ratio: 1;
    margin-left: auto; margin-right: clamp(40px, 9vw, 130px); translate: 0 -20px;
  }
  body main #team .tm-sage { position: absolute; inset: 7% -7% -7% 7%; border-radius: 50%; background: #a9b6a0; opacity: .5; }
  body main #team .tm-visual .tm-pic { position: absolute; inset: 0; width: 100%; height: 100%; max-width: none; margin: 0; translate: none; }

  /* skills: colourful pills */
  body main #team .tm-skill-list li {
    --c: #1f4fd8;
    padding: 6px 15px; font-weight: 600;
    color: var(--c) !important; background: color-mix(in srgb, var(--c) 9%, #fff);
    border: 1px solid color-mix(in srgb, var(--c) 28%, transparent);
    transition: background .25s, color .25s, transform .25s;
  }
  body main #team .tm-skill-list li:nth-child(4n+2) { --c: #0f9d73; }
  body main #team .tm-skill-list li:nth-child(4n+3) { --c: #7c4dff; }
  body main #team .tm-skill-list li:nth-child(4n+4) { --c: #d9822b; }
  body main #team .tm-skill-list li:hover { background: var(--c); color: #fff !important; transform: translateY(-3px); }

  /* social icons */
  body main #team .tm-social a { background: #fff; border: 1px solid var(--tm-line); box-shadow: 0 6px 16px rgba(0, 0, 0, .07); }

  /* buttons */
  body main #team .tm-actions { display: flex; flex-wrap: wrap; gap: 12px; margin-top: 26px; }
  body main #team .tm-cta2 {
    display: inline-flex; align-items: center; gap: 10px; padding: 14px 24px; border-radius: 4px;
    font-family: var(--sans); font-size: .8rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase;
    color: var(--tm-ink) !important; background: transparent; border: 1px solid rgba(0, 0, 0, .3);
    transition: background .2s, color .2s, border-color .2s, transform .2s;
  }
  body main #team .tm-cta2 svg { width: 16px; height: 16px; }
  body main #team .tm-cta2:hover { background: var(--tm-ink); color: #fff !important; border-color: var(--tm-ink); transform: translateY(-2px); }

  /* later items of the info column rise in too */
  body main #team .tm-slide.show .tm-info > :nth-child(7) { animation-delay: .36s; }
  body main #team .tm-slide.show .tm-info > :nth-child(8) { animation-delay: .42s; }

  /* avatar strip */
  body main #team .tm-dots { padding-top: 6px; }

  /* ===== FIX: nothing may block the Email / View profile buttons ===== */
  body main #team .ghost,
  body main #team .tm-deco,
  body main #team .tm-side,
  body main #team .tm-sage,
  body main #team .tm-visual { pointer-events: none; }

  /* hidden slides must never sit on top of the active one */
  body main #team .tm-slide:not(.show),
  body main #team .tm-slide:not(.show) * { pointer-events: none !important; }
  body main #team .tm-slide.show { pointer-events: auto; }

  /* buttons always on top and clickable */
  body main #team .tm-info    { position: relative; z-index: 6; }
  body main #team .tm-actions { position: relative; z-index: 7; }
  body main #team .tm-cta,
  body main #team .tm-cta2    { position: relative; z-index: 8; pointer-events: auto !important; cursor: pointer; }

  /* ===== FIX: closed photo viewers / modals must never catch the mouse ===== */
  body .mx-lb:not(.open),
  body .pj-lb:not(.open) { pointer-events: none !important; }
  body .mx-lb:not(.open) *,
  body .pj-lb:not(.open) * { visibility: hidden !important; pointer-events: none !important; }

  /* ===== scroll IN / OUT (only when JS runs) ===== */
  html.tm-js body main #team [data-tma] { transition: opacity .8s var(--ease), transform .9s var(--ease); will-change: opacity, transform; }
  html.tm-js body main #team [data-tma]:not(.tm-in)         { opacity: 0 !important; transform: translateY(60px) scale(.96); }
  html.tm-js body main #team [data-tma="left"]:not(.tm-in)  { transform: translateX(-90px) scale(.96); }
  html.tm-js body main #team [data-tma="right"]:not(.tm-in) { transform: translateX(90px) scale(.96); }
  html.tm-js body main #team [data-tma].tm-up:not(.tm-in)   { transform: translateY(-50px) scale(.96); }
  html.tm-js body main #team [data-tma].tm-down:not(.tm-in) { transform: translateY(60px) scale(.96); }
  html.tm-js body main #team [data-tma].tm-in               { opacity: 1 !important; transform: none; }

  /* ===== responsive ===== */
  @media (max-width: 900px) {
    body main #team .tm-head { grid-template-columns: 1fr; align-items: start; }
  }
  @media (max-width: 760px) {
    body main #team .tm-visual { order: 1; max-width: 270px; margin-inline: auto; translate: none; }
    body main #team .tm-actions { justify-content: center; }
    body main #team .tm-d1, body main #team .tm-d4 { display: none; }
  }
  </style>

  <script>
  (function () {
    var root = document.querySelector('[data-tm]');
    if (!root) return;
    var slides = [].slice.call(root.querySelectorAll('.tm-slide'));
    var dots   = [].slice.call(root.querySelectorAll('.tm-dots button'));
    var i = 0, lock = false;
    if (slides.length < 2) { root.classList.add('single'); return; }

    function go(n, dir) {
      n = (n + slides.length) % slides.length;
      if (n === i || lock) return;
      lock = true;
      var cur = slides[i], nxt = slides[n];

      // place the incoming card off to the side (no animation), then slide it in
      nxt.classList.remove('out-l', 'out-r');
      nxt.style.transition = 'none';
      nxt.classList.add(dir > 0 ? 'pre-r' : 'pre-l');
      void nxt.offsetWidth;
      nxt.style.transition = '';
      nxt.classList.remove('pre-r', 'pre-l');

      cur.classList.remove('show');
      cur.classList.add(dir > 0 ? 'out-l' : 'out-r');
      cur.setAttribute('aria-hidden', 'true');
      nxt.classList.add('show');
      nxt.setAttribute('aria-hidden', 'false');

      dots.forEach(function (d, k) { d.classList.toggle('active', k === n); });
      i = n;
      setTimeout(function () { lock = false; }, 450);
    }

    root.querySelector('.tm-prev').addEventListener('click', function () { go(i - 1, -1); });
    root.querySelector('.tm-next').addEventListener('click', function () { go(i + 1, 1); });
    dots.forEach(function (d, k) { d.addEventListener('click', function () { go(k, k > i ? 1 : -1); }); });

    // keyboard (left / right) while focus is inside, and swipe on phones
    root.addEventListener('keydown', function (e) {
      if (e.key === 'ArrowLeft')  go(i - 1, -1);
      if (e.key === 'ArrowRight') go(i + 1, 1);
    });
    var x0 = null;
    root.addEventListener('touchstart', function (e) { x0 = e.touches[0].clientX; }, { passive: true });
    root.addEventListener('touchend', function (e) {
      if (x0 === null) return;
      var dx = e.changedTouches[0].clientX - x0;
      if (Math.abs(dx) > 50) go(dx < 0 ? i + 1 : i - 1, dx < 0 ? 1 : -1);
      x0 = null;
    });

    /* autoplay: only while the section is on screen, pauses on hover / focus / touch */
    var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (reduce || !('IntersectionObserver' in window)) return;
    var timer = null, paused = false, visible = false;
    function schedule() {
      clearTimeout(timer);
      if (visible && !paused) timer = setTimeout(function () { go(i + 1, 1); schedule(); }, 8000);
    }
    root.addEventListener('mouseenter', function () { paused = true;  clearTimeout(timer); });
    root.addEventListener('mouseleave', function () { paused = false; schedule(); });
    root.addEventListener('focusin',    function () { paused = true;  clearTimeout(timer); });
    root.addEventListener('focusout',   function () { paused = false; schedule(); });
    root.addEventListener('touchstart', function () { paused = true;  clearTimeout(timer); }, { passive: true });
    root.addEventListener('touchend',   function () { setTimeout(function () { paused = false; schedule(); }, 6000); }, { passive: true });
    new IntersectionObserver(function (en) { visible = en[0].isIntersecting; schedule(); }, { threshold: 0.35 }).observe(root);
  })();
  </script>

  <script>
  // Email button: click -> Gmail compose (PC) or the phone's mail app, sent to THAT member's email
  document.addEventListener('click', function (e) {
    var a = e.target.closest ? e.target.closest('#team a.tm-cta2') : null;
    if (!a) return;
    e.preventDefault(); e.stopPropagation();

    var mobile = /Android|iPhone|iPad|iPod|Mobile/i.test(navigator.userAgent);
    if (mobile) {
      window.location.href = a.getAttribute('data-mailto');          // opens the phone's mail app
    } else {
      window.open(a.getAttribute('href'), '_blank', 'noopener');     // opens Gmail compose
    }
  }, true);

  // social icons: open the member's own page directly
  document.addEventListener('click', function (e) {
    var a = e.target.closest ? e.target.closest('.tm-social a') : null;
    if (!a) return;
    var href = a.getAttribute('href');
    if (!href || href === '#') return;
    e.preventDefault(); e.stopPropagation();
    window.open(href, '_blank', 'noopener');
  }, true);

  // scroll in / out animation for the heading and the showcase
  (function () {
    var sec = document.getElementById('team');
    if (!sec) return;
    var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var els = sec.querySelectorAll('[data-tma]');
    if (!els.length || reduce || !('IntersectionObserver' in window)) return;   // stays visible
    document.documentElement.classList.add('tm-js');
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        var el = en.target;
        if (en.isIntersecting) { el.classList.remove('tm-up', 'tm-down'); el.classList.add('tm-in'); }
        else { el.classList.remove('tm-in'); el.classList.add(en.boundingClientRect.top < 0 ? 'tm-up' : 'tm-down'); }
      });
    }, { threshold: 0.12, rootMargin: '0px 0px -6% 0px' });
    [].forEach.call(els, function (el) { io.observe(el); });
  })();
  </script>
</section>

<!-- MEMORIES : bento gallery + scroll in / out animation + photo viewer -->
<section class="intro" id="collections">
  <?php
  $memories = [
    ['Team Collaboration',    'How we work together to solve problems.',      'team.png'],
    ['Coding Sessions',       'Late night coding and debugging moments.',     'team2.jpg'],
    ['Project Presentations', 'Defending our systems in front of the panel.', 'presentation.jpg'],
    ['Friends & Classmates',  'The bond we built throughout college.',        'sch_evnts2.jpg'],
    ['School Events',         'Foundation day, IT Week, and celebrations.',   'sch_evnts.jpg'],
    ['Our Gallery',           'A collection of our favorite memories.',       'team6.jpg'],
  ];
  // direction each card flies in from: left / right / up / zoom
  $mx_dir = ['left', 'right', 'left', 'right', 'left', 'right'];
  ?>
  <!-- background decoration -->
  <span class="mx-deco mx-d1" aria-hidden="true"></span>
  <span class="mx-deco mx-d2" aria-hidden="true"></span>
  <span class="mx-deco mx-d3" aria-hidden="true"></span>
  <span class="mx-deco mx-d4" aria-hidden="true"></span>
  <span class="mx-deco mx-d5" aria-hidden="true"></span>
  <span class="mx-deco mx-d6" aria-hidden="true">MEMORIES</span>

  <div class="wrap">

    <div class="mx-head">
      <div data-mx="left">
        <p class="mx-kicker">Memories</p>
        <h2 class="mx-h2">School memories &amp; activities</h2>
      </div>
      <p class="mx-lead" data-mx="right">A few snapshots from our journey as BSIT students. Click a photo to see it bigger.</p>
    </div>

    <div class="mx-grid">
      <?php foreach ($memories as $i => $mem) : $src = esc_url(panto_img($mem[2])); ?>
        <a class="mx-card mx-c<?php echo $i + 1; ?>" data-mx="<?php echo esc_attr($mx_dir[$i % 6]); ?>"
           href="<?php echo $src; ?>" target="_blank" rel="noopener"
           style="--d: <?php echo ($i % 2) * 0.12; ?>s">
          <img class="mx-img" src="<?php echo $src; ?>" alt="<?php echo esc_attr($mem[0]); ?>" loading="lazy" decoding="async">
          <span class="mx-open" aria-hidden="true"><?php echo $ne; ?></span>
          <span class="mx-cap">
            <span class="mx-ct">
              <h3 class="mx-ttl"><?php echo esc_html($mem[0]); ?></h3>
              <span class="mx-dsc"><?php echo esc_html($mem[1]); ?></span>
            </span>
          </span>
        </a>
      <?php endforeach; ?>
    </div>

  </div>

  <!-- photo viewer (moved to <body> by the script below) -->
  <div class="mx-lb" role="dialog" aria-modal="true" aria-label="Photo viewer" aria-hidden="true">
    <figure class="mx-lb-fig">
      <img class="mx-lb-img" src="" alt="">
      <figcaption>
        <span class="mx-lb-title"></span>
        <span class="mx-lb-desc"></span>
        <span class="mx-lb-count"></span>
      </figcaption>
    </figure>
    <button type="button" class="mx-lb-btn mx-lb-close" aria-label="Close"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18"/></svg></button>
    <button type="button" class="mx-lb-btn mx-lb-prev" aria-label="Previous photo"><?php echo $left; ?></button>
    <button type="button" class="mx-lb-btn mx-lb-next" aria-label="Next photo"><?php echo $arrow; ?></button>
  </div>

  <style>
  /* ===== MEMORIES: bento gallery (light, formal) ===== */
  body main section.intro#collections {
    --mx-blue: #1f4fd8; --mx-ink: #15161a; --mx-soft: #4a4e5a; --mx-line: rgba(0, 0, 0, .12);
    padding: clamp(64px, 9vw, 110px) 0 !important;
    background:
      radial-gradient(ellipse 55% 60% at 88% 4%, rgba(31, 79, 216, .14), transparent 70%),
      radial-gradient(ellipse 50% 55% at 6% 96%, rgba(169, 182, 160, .50), transparent 70%),
      linear-gradient(180deg, #f4f6fa 0%, #e5e9f0 100%) !important;
    border-block: 0 !important;
    border-top: 1px solid var(--mx-line) !important;
  }

  /* background decoration (rings, dots, shapes, big outline word) */
  body main #collections .mx-deco { position: absolute; z-index: 0; display: block; pointer-events: none; user-select: none; }
  body main #collections .mx-d1 {
    top: 56px; left: clamp(8px, 4vw, 60px); width: 260px; height: 180px;
    background-image: radial-gradient(rgba(31, 79, 216, .38) 1.5px, transparent 2px); background-size: 22px 22px;
    -webkit-mask-image: linear-gradient(135deg, #000, transparent 80%); mask-image: linear-gradient(135deg, #000, transparent 80%);
  }
  body main #collections .mx-d2 {
    left: -140px; bottom: -170px; width: 460px; height: 460px; border-radius: 50%;
    background: #a9b6a0; opacity: .35; outline: 1px solid rgba(31, 79, 216, .4); outline-offset: 20px;
  }
  body main #collections .mx-d3 {
    right: -90px; top: 90px; width: 300px; height: 300px; border-radius: 50%;
    border: 1px dashed rgba(31, 79, 216, .45); animation: spin 70s linear infinite;
  }
  body main #collections .mx-d4 {
    right: 7%; bottom: 90px; width: 46px; height: 46px; border-radius: 10px;
    background: #1f4fd8; opacity: .18; animation: floaty 8s ease-in-out infinite;
  }
  body main #collections .mx-d5 {
    left: 46%; top: 38px; width: 16px; height: 16px; border-radius: 50%;
    background: #1f4fd8; opacity: .4; animation: floaty 6s ease-in-out infinite;
  }
  body main #collections .mx-d6 {
    left: 0; right: 0; bottom: -.1em; text-align: center; white-space: nowrap; line-height: 1;
    font-family: var(--sans); font-weight: 800; font-size: clamp(5rem, 16vw, 15rem); letter-spacing: .02em;
    color: transparent !important; -webkit-text-stroke: 1.5px rgba(31, 79, 216, .14);
  }

  /* heading */
  body main #collections .mx-head {
    display: grid; grid-template-columns: minmax(0, 1.1fr) minmax(0, .9fr);
    gap: clamp(20px, 5vw, 72px); align-items: end; margin-bottom: clamp(28px, 4vw, 48px);
  }
  body main #collections .mx-kicker {
    display: flex; align-items: center; gap: 12px; margin: 0 0 14px;
    font-family: var(--sans); font-size: .78rem; font-weight: 700;
    letter-spacing: .2em; text-transform: uppercase; color: var(--mx-blue) !important;
  }
  body main #collections .mx-kicker::before { content: ""; width: 28px; height: 2px; background: var(--mx-blue); }
  body main #collections .mx-h2 {
    margin: 0; max-width: 18ch;
    font-family: var(--sans) !important; font-weight: 800 !important;
    font-size: clamp(1.9rem, 4.4vw, 3.2rem) !important; line-height: 1.08 !important; letter-spacing: -.02em !important;
    color: var(--mx-ink) !important; -webkit-text-fill-color: var(--mx-ink) !important;
  }
  body main #collections .mx-lead {
    margin: 0; max-width: 46ch; padding-left: 18px; border-left: 2px solid var(--mx-blue);
    font-family: var(--sans); font-size: 1rem; line-height: 1.75; color: var(--mx-soft) !important;
  }

  /* bento grid: 7+5 / 5+7 / 4+8 */
  body main #collections .mx-grid {
    display: grid; grid-template-columns: repeat(12, 1fr);
    grid-auto-rows: clamp(250px, 30vw, 340px); gap: 22px;
  }
  body main #collections .mx-c1 { grid-column: span 7; }
  body main #collections .mx-c2 { grid-column: span 5; }
  body main #collections .mx-c3 { grid-column: span 5; }
  body main #collections .mx-c4 { grid-column: span 7; }
  body main #collections .mx-c5 { grid-column: span 4; }
  body main #collections .mx-c6 { grid-column: span 8; }

  /* card */
  body main #collections .mx-card {
    position: relative; display: block; overflow: hidden; cursor: zoom-in;
    border-radius: 18px; background: #dfe3e6; border: 1px solid var(--mx-line);
    box-shadow: 0 14px 34px rgba(0, 0, 0, .10);
  }
  body main #collections .mx-card:focus-visible { outline: 2px solid var(--mx-blue); outline-offset: 4px; }
  body main #collections .mx-img {
    position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover;
    transition: transform .9s var(--ease);
  }
  body main #collections .mx-card:hover .mx-img { transform: scale(1.07); }

  /* arrow button (top right) */
  body main #collections .mx-open {
    position: absolute; top: 14px; right: 14px; z-index: 2;
    width: 40px; height: 40px; border-radius: 50%; display: grid; place-items: center;
    color: var(--mx-ink); background: #fff; box-shadow: 0 6px 16px rgba(0, 0, 0, .16);
    transition: background .25s, color .25s, transform .25s;
  }
  body main #collections .mx-open svg { width: 18px; height: 18px; }
  body main #collections .mx-card:hover .mx-open { background: var(--mx-blue); color: #fff; transform: rotate(45deg); }

  /* caption: transparent, text sits directly on the photo (soft dark fade keeps it readable) */
  body main #collections .mx-card::after {
    content: ""; position: absolute; left: 0; right: 0; bottom: 0; height: 60%; z-index: 1; pointer-events: none;
    background: linear-gradient(to top, rgba(0, 0, 0, .75), rgba(0, 0, 0, 0));
  }
  body main #collections .mx-cap {
    position: absolute; left: 22px; right: 22px; bottom: 20px; z-index: 2;
    display: block; padding: 0; background: none; border: 0; box-shadow: none;
    transition: transform .45s var(--ease);
  }
  body main #collections .mx-card:hover .mx-cap { transform: translateY(-4px); }
  body main #collections .mx-ct { display: block; min-width: 0; }
  body main #collections .mx-ttl {
    margin: 0 0 3px !important; font-family: var(--sans) !important; font-size: 1.05rem !important; font-weight: 700 !important;
    letter-spacing: 0 !important; line-height: 1.3 !important;
    color: #fff !important; -webkit-text-fill-color: #fff !important; text-shadow: 0 1px 10px rgba(0, 0, 0, .55);
  }
  body main #collections .mx-dsc {
    display: block; font-family: var(--sans); font-size: .88rem; line-height: 1.45;
    color: rgba(255, 255, 255, .92) !important; -webkit-text-fill-color: rgba(255, 255, 255, .92) !important;
    text-shadow: 0 1px 10px rgba(0, 0, 0, .55);
  }

  /* ===== scroll IN / OUT animation (only when JS runs) ===== */
  html.mx-js body main #collections [data-mx] {
    transition: opacity .8s var(--ease), transform .9s var(--ease), box-shadow .3s ease;
    transition-delay: var(--d, 0s), var(--d, 0s), 0s;
    will-change: opacity, transform;
  }
  /* hidden state (before it enters) */
  html.mx-js body main #collections [data-mx]:not(.mx-in)         { opacity: 0 !important; transform: translateY(70px) scale(.95); }
  html.mx-js body main #collections [data-mx="left"]:not(.mx-in)  { transform: translateX(-90px) scale(.95); }
  html.mx-js body main #collections [data-mx="right"]:not(.mx-in) { transform: translateX(90px) scale(.95); }
  html.mx-js body main #collections [data-mx="zoom"]:not(.mx-in)  { transform: scale(.82); }
  /* leaving: through the top (scrolling down) or the bottom (scrolling up) */
  html.mx-js body main #collections [data-mx].mx-up:not(.mx-in)   { transform: translateY(-60px) scale(.95); transition-delay: 0s; }
  html.mx-js body main #collections [data-mx].mx-down:not(.mx-in) { transform: translateY(70px) scale(.95);  transition-delay: 0s; }
  /* visible */
  html.mx-js body main #collections [data-mx].mx-in               { opacity: 1 !important; transform: none; }

  /* ===== photo viewer ===== */
  body .mx-lb {
    position: fixed; inset: 0; z-index: 10050; display: grid; place-items: center; padding: 24px;
    background: rgba(8, 10, 18, .94); -webkit-backdrop-filter: blur(8px); backdrop-filter: blur(8px);
    opacity: 0; visibility: hidden; transition: opacity .3s ease, visibility .3s;
  }
  body .mx-lb.open { opacity: 1; visibility: visible; }
  body .mx-lb-fig { margin: 0; max-width: min(92vw, 1000px); text-align: center; transform: scale(.96); transition: transform .4s var(--ease); }
  body .mx-lb.open .mx-lb-fig { transform: none; }
  body .mx-lb-img { display: block; max-width: 100%; max-height: 74vh; margin: 0 auto; object-fit: contain; border-radius: 8px; background: #111; box-shadow: 0 30px 80px rgba(0, 0, 0, .6); }
  body .mx-lb figcaption { display: flex; flex-direction: column; gap: 3px; margin-top: 14px; font-family: var(--sans); }
  body .mx-lb-title { color: #fff; font-size: 1.05rem; font-weight: 700; }
  body .mx-lb-desc  { color: rgba(255, 255, 255, .8); font-size: .88rem; }
  body .mx-lb-count { color: rgba(255, 255, 255, .6); font-size: .8rem; font-weight: 600; letter-spacing: .08em; margin-top: 4px; }
  body .mx-lb-btn {
    position: absolute; width: 44px; height: 44px; border-radius: 50%; display: grid; place-items: center; cursor: pointer;
    color: #15161a; background: #fff; border: 0; transition: background .2s, color .2s, transform .2s;
  }
  body .mx-lb-btn:hover { background: #1f4fd8; color: #fff; transform: scale(1.08); }
  body .mx-lb-btn svg { width: 20px; height: 20px; }
  body .mx-lb-close { top: 18px; right: 18px; }
  body .mx-lb-prev  { left: 18px; top: 50%; margin-top: -22px; }
  body .mx-lb-next  { right: 18px; top: 50%; margin-top: -22px; }
  html.mx-lock { overflow: hidden; }

  /* ===== responsive ===== */
  @media (max-width: 900px) {
    body main #collections .mx-head { grid-template-columns: 1fr; align-items: start; }
    body main #collections .mx-grid { grid-template-columns: repeat(2, 1fr); gap: 16px; }
    body main #collections .mx-c1,
    body main #collections .mx-c6 { grid-column: span 2; }
    body main #collections .mx-c2,
    body main #collections .mx-c3,
    body main #collections .mx-c4,
    body main #collections .mx-c5 { grid-column: span 1; }
  }
  @media (max-width: 600px) {
    body main #collections .mx-d1,
    body main #collections .mx-d5 { display: none; }
    body main #collections .mx-d2 { width: 300px; height: 300px; left: -120px; bottom: -120px; }
    body main #collections .mx-d3 { width: 200px; height: 200px; right: -80px; }
    body main #collections .mx-grid { grid-template-columns: 1fr; grid-auto-rows: 270px; }
    body main #collections .mx-c1,
    body main #collections .mx-c2,
    body main #collections .mx-c3,
    body main #collections .mx-c4,
    body main #collections .mx-c5,
    body main #collections .mx-c6 { grid-column: span 1; }
    body .mx-lb-prev { top: auto; bottom: 18px; left: 18px; margin: 0; }
    body .mx-lb-next { top: auto; bottom: 18px; right: 18px; margin: 0; }
    body .mx-lb-img { max-height: 62vh; }
  }
  </style>

  <script>
  (function () {
    var sec = document.getElementById('collections');
    if (!sec) return;
    var cards = [].slice.call(sec.querySelectorAll('.mx-card'));

    /* ---------- photo viewer ---------- */
    var lb = sec.querySelector('.mx-lb');
    if (lb && cards.length) {
      document.body.appendChild(lb);   // so nothing in the page can clip or hide it
      var img = lb.querySelector('.mx-lb-img');
      var tt  = lb.querySelector('.mx-lb-title');
      var ds  = lb.querySelector('.mx-lb-desc');
      var ct  = lb.querySelector('.mx-lb-count');
      var cur = 0, last = null;

      function show(i) {
        cur = (i + cards.length) % cards.length;
        var c = cards[cur];
        img.src = c.getAttribute('href');
        img.alt = c.querySelector('.mx-ttl').textContent;
        tt.textContent = c.querySelector('.mx-ttl').textContent;
        ds.textContent = c.querySelector('.mx-dsc').textContent;
        ct.textContent = (cur + 1) + ' / ' + cards.length;
      }
      function openLb(i) {
        last = document.activeElement;
        show(i);
        lb.classList.add('open');
        lb.setAttribute('aria-hidden', 'false');
        document.documentElement.classList.add('mx-lock');
        lb.querySelector('.mx-lb-close').focus();
      }
      function closeLb() {
        lb.classList.remove('open');
        lb.setAttribute('aria-hidden', 'true');
        document.documentElement.classList.remove('mx-lock');
        if (last && last.focus) last.focus();
      }

      cards.forEach(function (c, i) {
        c.addEventListener('click', function (e) { e.preventDefault(); e.stopPropagation(); openLb(i); });
      });
      lb.querySelector('.mx-lb-close').addEventListener('click', closeLb);
      lb.querySelector('.mx-lb-prev').addEventListener('click', function () { show(cur - 1); });
      lb.querySelector('.mx-lb-next').addEventListener('click', function () { show(cur + 1); });
      lb.addEventListener('click', function (e) { if (e.target === lb) closeLb(); });
      document.addEventListener('keydown', function (e) {
        if (!lb.classList.contains('open')) return;
        if (e.key === 'Escape')     closeLb();
        if (e.key === 'ArrowLeft')  show(cur - 1);
        if (e.key === 'ArrowRight') show(cur + 1);
      });
    }

    /* ---------- scroll in / out animation ---------- */
    var items = sec.querySelectorAll('[data-mx]');
    var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (!items.length || reduce || !('IntersectionObserver' in window)) return;   // stays visible

    document.documentElement.classList.add('mx-js');
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        var el = en.target;
        if (en.isIntersecting) {
          el.classList.remove('mx-up', 'mx-down');
          el.classList.add('mx-in');                                   // IN
        } else {
          el.classList.remove('mx-in');                                // OUT
          // left through the top = scrolled down, left through the bottom = scrolled up
          el.classList.add(en.boundingClientRect.top < 0 ? 'mx-up' : 'mx-down');
        }
      });
    }, { threshold: 0.15, rootMargin: '0px 0px -6% 0px' });
    [].forEach.call(items, function (el) { io.observe(el); });
  })();
  </script>
</section>

<!-- PROJECTS -->
<section class="gallery" id="projects">
  <?php
  $projects = [
    ['web',    'Capstone Project',  'E-Commerce System',   'project1.jpg'],
    ['app',    'Student Portal',    'Web Application',     'project2.jpg'],
    ['uiux',   'Mobile App Design', 'UI/UX Prototype',     'project3.jpg'],
    ['system', 'Inventory System',  'Database Management', 'project4.jpg'],
  ];
  $pj_labels = [
    'web'    => 'Web development',
    'app'    => 'App development',
    'uiux'   => 'UI/UX design',
    'system' => 'System development',
  ];
  $pj_dir = ['left', 'right', 'left', 'right'];
  ?>

  <!-- background decoration -->
  <span class="pj-deco pj-d1" aria-hidden="true"></span>
  <span class="pj-deco pj-d2" aria-hidden="true"></span>
  <span class="pj-deco pj-d3" aria-hidden="true"></span>
  <span class="pj-deco pj-d4" aria-hidden="true"></span>
  <span class="pj-deco pj-d5" aria-hidden="true">PROJECTS</span>

  <div class="wrap">

    <div class="pj-head">
      <div data-pj="left">
        <p class="pj-kicker">Projects</p>
        <h2 class="pj-h2">A showcase of our best works</h2>
      </div>
      <p class="pj-lead" data-pj="right">Systems and designs we built, tested and defended as BSIT students. Filter by category or click a project to see it bigger.</p>
    </div>

    <div class="pj-grid">
      <?php foreach ($projects as $i => $p) : $src = esc_url(panto_img($p[3])); ?>
        <div class="pj-item" data-cat="<?php echo esc_attr($p[0]); ?>" data-pj="<?php echo esc_attr($pj_dir[$i % 4]); ?>"
             style="--d: <?php echo ($i % 2) * 0.12; ?>s">
          <a class="pj-card pj-k-<?php echo esc_attr($p[0]); ?>" href="<?php echo $src; ?>" target="_blank" rel="noopener"
             aria-label="<?php echo esc_attr($p[1] . ' – ' . $p[2] . ' (view photo)'); ?>">
            <span class="pj-media">
              <img class="pj-img" src="<?php echo $src; ?>" alt="<?php echo esc_attr($p[1] . ' – ' . $p[2]); ?>" loading="lazy" decoding="async">
              <span class="pj-num" aria-hidden="true"><?php echo str_pad($i + 1, 2, '0', STR_PAD_LEFT); ?></span>
              <span class="pj-cat"><i></i><?php echo esc_html($pj_labels[$p[0]]); ?></span>
              <span class="pj-open" aria-hidden="true"><?php echo $ne; ?></span>
            </span>
            <span class="pj-body">
              <span class="pj-meta">
                <b class="pj-ttl"><?php echo esc_html($p[1]); ?></b>
                <span class="pj-sub"><?php echo esc_html($p[2]); ?></span>
              </span>
              <span class="pj-view">View <?php echo $arrow; ?></span>
            </span>
          </a>
        </div>
      <?php endforeach; ?>
    </div>

  </div>

  <!-- photo viewer (moved to <body> by the script below) -->
  <div class="pj-lb" role="dialog" aria-modal="true" aria-label="Project viewer" aria-hidden="true">
    <figure class="pj-lb-fig">
      <img class="pj-lb-img" src="" alt="">
      <figcaption>
        <span class="pj-lb-title"></span>
        <span class="pj-lb-desc"></span>
        <span class="pj-lb-count"></span>
      </figcaption>
    </figure>
    <button type="button" class="pj-lb-btn pj-lb-close" aria-label="Close"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18"/></svg></button>
    <button type="button" class="pj-lb-btn pj-lb-prev" aria-label="Previous project"><?php echo $left; ?></button>
    <button type="button" class="pj-lb-btn pj-lb-next" aria-label="Next project"><?php echo $arrow; ?></button>
  </div>

  <style>
  /* ===== PROJECTS: light, formal, animated ===== */
  body main section.gallery#projects {
    --pj-blue: #1f4fd8; --pj-ink: #15161a; --pj-soft: #4a4e5a; --pj-line: rgba(0, 0, 0, .12);
    padding: clamp(64px, 9vw, 110px) 0 !important;
    background:
      radial-gradient(ellipse 55% 60% at 12% 6%, rgba(31, 79, 216, .12), transparent 70%),
      radial-gradient(ellipse 50% 55% at 94% 92%, rgba(169, 182, 160, .45), transparent 70%),
      linear-gradient(180deg, #eceeef 0%, #f6f7f9 100%) !important;
    border-block: 0 !important;
    border-top: 1px solid var(--pj-line) !important;
  }

  /* decoration */
  body main #projects .pj-deco { position: absolute; z-index: 0; display: block; pointer-events: none; user-select: none; }
  body main #projects .pj-d1 {
    top: 70px; right: clamp(8px, 4vw, 60px); width: 240px; height: 170px;
    background-image: radial-gradient(rgba(31, 79, 216, .36) 1.5px, transparent 2px); background-size: 22px 22px;
    -webkit-mask-image: linear-gradient(225deg, #000, transparent 80%); mask-image: linear-gradient(225deg, #000, transparent 80%);
  }
  body main #projects .pj-d2 {
    right: -150px; bottom: -170px; width: 460px; height: 460px; border-radius: 50%;
    background: #a9b6a0; opacity: .32; outline: 1px solid rgba(31, 79, 216, .4); outline-offset: 20px;
  }
  body main #projects .pj-d3 {
    left: -100px; top: 36%; width: 280px; height: 280px; border-radius: 50%;
    border: 1px dashed rgba(31, 79, 216, .42); animation: spin 70s linear infinite;
  }
  body main #projects .pj-d4 {
    left: 6%; bottom: 80px; width: 44px; height: 44px; border-radius: 10px;
    background: #1f4fd8; opacity: .16; animation: floaty 8s ease-in-out infinite;
  }
  body main #projects .pj-d5 {
    left: 0; right: 0; bottom: -.1em; text-align: center; white-space: nowrap; line-height: 1;
    font-family: var(--sans); font-weight: 800; font-size: clamp(4rem, 14vw, 13rem); letter-spacing: .02em;
    color: transparent !important; -webkit-text-fill-color: transparent !important; -webkit-text-stroke: 1.5px rgba(31, 79, 216, .13);
  }

  /* heading */
  body main #projects .pj-head {
    display: grid; grid-template-columns: minmax(0, 1.1fr) minmax(0, .9fr);
    gap: clamp(20px, 5vw, 72px); align-items: end; margin-bottom: clamp(26px, 4vw, 40px);
  }
  body main #projects .pj-kicker {
    display: flex; align-items: center; gap: 12px; margin: 0 0 14px;
    font-family: var(--sans); font-size: .78rem; font-weight: 700;
    letter-spacing: .2em; text-transform: uppercase; color: var(--pj-blue) !important;
  }
  body main #projects .pj-kicker::before { content: ""; width: 28px; height: 2px; background: var(--pj-blue); }
  body main #projects .pj-h2 {
    margin: 0; max-width: 16ch;
    font-family: var(--sans) !important; font-weight: 800 !important;
    font-size: clamp(1.9rem, 4.4vw, 3.2rem) !important; line-height: 1.08 !important; letter-spacing: -.02em !important;
    color: var(--pj-ink) !important; -webkit-text-fill-color: var(--pj-ink) !important;
  }
  body main #projects .pj-lead {
    margin: 0; max-width: 46ch; padding-left: 18px; border-left: 2px solid var(--pj-blue);
    font-family: var(--sans); font-size: 1rem; line-height: 1.75; color: var(--pj-soft) !important;
  }

  /* filter bar */
  body main #projects .pj-bar {
    display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 14px 24px;
    margin-bottom: clamp(24px, 3.5vw, 40px); padding-bottom: 18px; border-bottom: 1px solid var(--pj-line);
  }
  body main #projects .pj-chips { display: flex; flex-wrap: wrap; gap: 8px; }
  body main #projects .pj-chip {
    cursor: pointer; padding: 9px 18px; border-radius: 999px;
    font-family: var(--sans); font-size: .82rem; font-weight: 600; letter-spacing: .01em;
    color: var(--pj-ink) !important; background: #fff; border: 1px solid var(--pj-line);
    transition: background .25s, color .25s, border-color .25s, transform .25s, box-shadow .25s;
  }
  body main #projects .pj-chip:hover { border-color: var(--pj-blue); color: var(--pj-blue) !important; transform: translateY(-2px); }
  body main #projects .pj-chip.active {
    background: var(--pj-blue); border-color: var(--pj-blue); color: #fff !important;
    box-shadow: 0 10px 22px rgba(31, 79, 216, .3);
  }
  body main #projects .pj-chip:focus-visible { outline: 2px solid var(--pj-blue); outline-offset: 3px; }
  body main #projects .pj-count { margin: 0; font-family: var(--sans); font-size: .85rem; color: var(--pj-soft) !important; }
  body main #projects .pj-count b { color: var(--pj-ink) !important; font-size: 1rem; }

  /* grid (2 columns, every second card sits lower = not boring) */
  body main #projects .pj-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 30px; align-items: start; padding-bottom: 48px; }
  body main #projects .pj-item[hidden] { display: none !important; }
  body main #projects .pj-grid:not(.pj-filtered) .pj-item:nth-child(even) { margin-top: 52px; }

  /* card */
  body main #projects .pj-card {
    --c: #1f4fd8;
    display: block; overflow: hidden; border-radius: 20px; cursor: zoom-in;
    background: #fff; border: 1px solid var(--pj-line);
    box-shadow: 0 14px 34px rgba(0, 0, 0, .09);
    transition: transform .5s var(--ease), box-shadow .4s ease, border-color .3s, opacity .35s ease;
  }
  body main #projects .pj-k-app    { --c: #0f9d73; }
  body main #projects .pj-k-uiux   { --c: #7c4dff; }
  body main #projects .pj-k-system { --c: #d9822b; }
  body main #projects .pj-card:hover, body main #projects .pj-card:focus-visible {
    transform: translateY(-8px); border-color: var(--c);
    box-shadow: 0 26px 54px rgba(0, 0, 0, .15), 0 0 0 1px var(--c);
  }
  body main #projects .pj-card:focus-visible { outline: 2px solid var(--pj-blue); outline-offset: 4px; }
  body main #projects .pj-card.pj-off { opacity: 0; transform: scale(.9); pointer-events: none; }

  body main #projects .pj-media { position: relative; display: block; aspect-ratio: 16 / 11; overflow: hidden; background: #dfe3e6; }
  body main #projects .pj-img {
    position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover;
    transition: transform .9s var(--ease);
  }
  body main #projects .pj-card:hover .pj-img { transform: scale(1.08); }
  body main #projects .pj-media::after {
    content: ""; position: absolute; left: 0; right: 0; bottom: 0; height: 45%; pointer-events: none;
    background: linear-gradient(to top, rgba(0, 0, 0, .35), rgba(0, 0, 0, 0));
  }

  /* big outlined number */
  body main #projects .pj-num {
    position: absolute; left: 18px; bottom: 6px; z-index: 2; line-height: 1;
    font-family: var(--sans); font-weight: 800; font-size: clamp(3rem, 6vw, 4.6rem); letter-spacing: -.03em;
    color: transparent !important; -webkit-text-fill-color: transparent !important; -webkit-text-stroke: 1.5px rgba(255, 255, 255, .9);
    transition: transform .5s var(--ease);
  }
  body main #projects .pj-card:hover .pj-num { transform: translateY(-6px); }

  /* category chip */
  body main #projects .pj-cat {
    position: absolute; top: 16px; left: 16px; z-index: 2;
    display: inline-flex; align-items: center; gap: 8px; padding: 6px 13px; border-radius: 999px;
    font-family: var(--sans); font-size: .72rem; font-weight: 700; letter-spacing: .06em; text-transform: uppercase;
    color: var(--pj-ink) !important; background: rgba(255, 255, 255, .94);
    box-shadow: 0 6px 16px rgba(0, 0, 0, .14);
  }
  body main #projects .pj-cat i { width: 8px; height: 8px; border-radius: 50%; background: var(--c); }

  /* arrow button */
  body main #projects .pj-open {
    position: absolute; top: 14px; right: 14px; z-index: 2;
    width: 40px; height: 40px; border-radius: 50%; display: grid; place-items: center;
    color: var(--pj-ink) !important; background: #fff; box-shadow: 0 6px 16px rgba(0, 0, 0, .16);
    transition: background .25s, color .25s, transform .3s;
  }
  body main #projects .pj-open svg { width: 18px; height: 18px; }
  body main #projects .pj-card:hover .pj-open { background: var(--c); color: #fff !important; transform: rotate(45deg); }

  /* text under photo */
  body main #projects .pj-body { position: relative; display: flex; align-items: center; justify-content: space-between; gap: 16px; padding: 20px 22px 22px; }
  body main #projects .pj-body::before {
    content: ""; position: absolute; left: 22px; top: 0; width: 38px; height: 3px; background: var(--c);
    transition: width .5s var(--ease);
  }
  body main #projects .pj-card:hover .pj-body::before { width: calc(100% - 44px); }
  body main #projects .pj-meta { display: block; min-width: 0; padding-top: 6px; }
  body main #projects .pj-ttl {
    display: block; margin: 0 0 3px; font-family: var(--sans); font-size: 1.12rem; font-weight: 700; letter-spacing: -.01em;
    color: var(--pj-ink) !important; -webkit-text-fill-color: var(--pj-ink) !important;
  }
  body main #projects .pj-sub { display: block; font-family: var(--sans); font-size: .9rem; color: var(--pj-soft) !important; }
  body main #projects .pj-view {
    flex: none; display: inline-flex; align-items: center; gap: 8px; padding-top: 6px;
    font-family: var(--sans); font-size: .74rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase;
    color: var(--c) !important;
  }
  body main #projects .pj-view svg { width: 15px; height: 15px; transition: transform .25s; }
  body main #projects .pj-card:hover .pj-view svg { transform: translateX(5px); }

  /* ===== scroll IN / OUT (only when JS runs) ===== */
  html.pj-js body main #projects [data-pj] {
    transition: opacity .8s var(--ease), transform .9s var(--ease);
    transition-delay: var(--d, 0s);
    will-change: opacity, transform;
  }
  html.pj-js body main #projects [data-pj]:not(.pj-in)         { opacity: 0 !important; transform: translateY(70px) scale(.95); }
  html.pj-js body main #projects [data-pj="left"]:not(.pj-in)  { transform: translateX(-90px) scale(.95); }
  html.pj-js body main #projects [data-pj="right"]:not(.pj-in) { transform: translateX(90px) scale(.95); }
  html.pj-js body main #projects [data-pj].pj-up:not(.pj-in)   { transform: translateY(-60px) scale(.95); transition-delay: 0s; }
  html.pj-js body main #projects [data-pj].pj-down:not(.pj-in) { transform: translateY(70px) scale(.95); transition-delay: 0s; }
  html.pj-js body main #projects [data-pj].pj-in               { opacity: 1 !important; transform: none; }

  /* ===== viewer ===== */
  body .pj-lb {
    position: fixed; inset: 0; z-index: 10050; display: grid; place-items: center; padding: 24px;
    background: rgba(8, 10, 18, .94); -webkit-backdrop-filter: blur(8px); backdrop-filter: blur(8px);
    opacity: 0; visibility: hidden; transition: opacity .3s ease, visibility .3s;
  }
  body .pj-lb.open { opacity: 1; visibility: visible; }
  body .pj-lb-fig { margin: 0; max-width: min(92vw, 1000px); text-align: center; transform: scale(.96); transition: transform .4s var(--ease); }
  body .pj-lb.open .pj-lb-fig { transform: none; }
  body .pj-lb-img { display: block; max-width: 100%; max-height: 74vh; margin: 0 auto; object-fit: contain; border-radius: 8px; background: #111; box-shadow: 0 30px 80px rgba(0, 0, 0, .6); }
  body .pj-lb figcaption { display: flex; flex-direction: column; gap: 3px; margin-top: 14px; font-family: var(--sans); }
  body .pj-lb-title { color: #fff; font-size: 1.05rem; font-weight: 700; }
  body .pj-lb-desc  { color: rgba(255, 255, 255, .8); font-size: .88rem; }
  body .pj-lb-count { color: rgba(255, 255, 255, .6); font-size: .8rem; font-weight: 600; letter-spacing: .08em; margin-top: 4px; }
  body .pj-lb-btn {
    position: absolute; width: 44px; height: 44px; border-radius: 50%; display: grid; place-items: center; cursor: pointer;
    color: #15161a; background: #fff; border: 0; transition: background .2s, color .2s, transform .2s;
  }
  body .pj-lb-btn:hover { background: #1f4fd8; color: #fff; transform: scale(1.08); }
  body .pj-lb-btn svg { width: 20px; height: 20px; }
  body .pj-lb-close { top: 18px; right: 18px; }
  body .pj-lb-prev  { left: 18px; top: 50%; margin-top: -22px; }
  body .pj-lb-next  { right: 18px; top: 50%; margin-top: -22px; }
  html.pj-lock { overflow: hidden; }

  /* ===== responsive ===== */
  @media (max-width: 900px) {
    body main #projects .pj-head { grid-template-columns: 1fr; align-items: start; }
  }
  @media (max-width: 700px) {
    body main #projects .pj-grid { grid-template-columns: 1fr; gap: 22px; }
    body main #projects .pj-grid:not(.pj-filtered) .pj-item:nth-child(even) { margin-top: 0; }
    body main #projects .pj-d1, body main #projects .pj-d4 { display: none; }
    body main #projects .pj-bar { flex-direction: column; align-items: flex-start; }
    body .pj-lb-prev { top: auto; bottom: 18px; left: 18px; margin: 0; }
    body .pj-lb-next { top: auto; bottom: 18px; right: 18px; margin: 0; }
    body .pj-lb-img { max-height: 62vh; }
  }
  </style>

  <script>
  (function () {
    var sec = document.getElementById('projects');
    if (!sec) return;
    var grid  = sec.querySelector('.pj-grid');
    var items = [].slice.call(sec.querySelectorAll('.pj-item'));
    var chips = [].slice.call(sec.querySelectorAll('.pj-chip'));
    var count = sec.querySelector('.pj-count');
    var filter = 'all';

    function matches(it) { return filter === 'all' || it.getAttribute('data-cat') === filter; }

    /* ---------- filter ---------- */
    function apply(f) {
      filter = f;
      var shown = 0;
      items.forEach(function (it) {
        var card = it.querySelector('.pj-card');
        clearTimeout(it._t);
        if (matches(it)) {
          shown++;
          it.hidden = false;
          requestAnimationFrame(function () { requestAnimationFrame(function () { card.classList.remove('pj-off'); }); });
        } else {
          card.classList.add('pj-off');
          it._t = setTimeout(function () { it.hidden = true; }, 350);
        }
      });
      grid.classList.toggle('pj-filtered', f !== 'all');
      count.innerHTML = 'Showing <b>' + shown + '</b> of ' + items.length;
    }
    chips.forEach(function (c) {
      c.addEventListener('click', function () {
        chips.forEach(function (x) { x.classList.toggle('active', x === c); });
        apply(c.getAttribute('data-filter'));
      });
    });

    /* ---------- photo viewer ---------- */
    var lb = sec.querySelector('.pj-lb');
    if (lb) {
      document.body.appendChild(lb);
      var img = lb.querySelector('.pj-lb-img');
      var tt = lb.querySelector('.pj-lb-title');
      var ds = lb.querySelector('.pj-lb-desc');
      var ct = lb.querySelector('.pj-lb-count');
      var list = [], cur = 0, last = null;

      function show(i) {
        cur = (i + list.length) % list.length;
        var c = list[cur].querySelector('.pj-card');
        img.src = c.getAttribute('href');
        img.alt = c.querySelector('.pj-ttl').textContent;
        tt.textContent = c.querySelector('.pj-ttl').textContent;
        ds.textContent = c.querySelector('.pj-sub').textContent;
        ct.textContent = (cur + 1) + ' / ' + list.length;
      }
      function openLb(item) {
        list = items.filter(matches);
        var idx = list.indexOf(item);
        if (idx < 0) return;
        last = document.activeElement;
        show(idx);
        lb.classList.add('open');
        lb.setAttribute('aria-hidden', 'false');
        document.documentElement.classList.add('pj-lock');
        lb.querySelector('.pj-lb-close').focus();
      }
      function closeLb() {
        lb.classList.remove('open');
        lb.setAttribute('aria-hidden', 'true');
        document.documentElement.classList.remove('pj-lock');
        if (last && last.focus) last.focus();
      }
      items.forEach(function (it) {
        it.querySelector('.pj-card').addEventListener('click', function (e) {
          e.preventDefault(); e.stopPropagation(); openLb(it);
        });
      });
      lb.querySelector('.pj-lb-close').addEventListener('click', closeLb);
      lb.querySelector('.pj-lb-prev').addEventListener('click', function () { show(cur - 1); });
      lb.querySelector('.pj-lb-next').addEventListener('click', function () { show(cur + 1); });
      lb.addEventListener('click', function (e) { if (e.target === lb) closeLb(); });
      document.addEventListener('keydown', function (e) {
        if (!lb.classList.contains('open')) return;
        if (e.key === 'Escape')     closeLb();
        if (e.key === 'ArrowLeft')  show(cur - 1);
        if (e.key === 'ArrowRight') show(cur + 1);
      });
    }

    /* ---------- scroll in / out animation ---------- */
    var els = sec.querySelectorAll('[data-pj]');
    var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (!els.length || reduce || !('IntersectionObserver' in window)) return;   // stays visible

    document.documentElement.classList.add('pj-js');
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        var el = en.target;
        if (en.isIntersecting) {
          el.classList.remove('pj-up', 'pj-down');
          el.classList.add('pj-in');
        } else {
          el.classList.remove('pj-in');
          el.classList.add(en.boundingClientRect.top < 0 ? 'pj-up' : 'pj-down');
        }
      });
    }, { threshold: 0.15, rootMargin: '0px 0px -6% 0px' });
    [].forEach.call(els, function (el) { io.observe(el); });
  })();
  </script>
</section>

<!-- PROCESS -->
<section class="steps" id="process">
  <?php
  // add more steps here: [number, title, text, image]
    $pc_steps = [
    ['01', 'Requirements gathering', 'We meet with the adviser and end users to collect, clarify, and document what the system needs to do.', 'step-requirements.jpg'],
    ['02', 'Analysis & design',      'We analyze the requirements, then create wireframes, database structure, and the system architecture.', 'step-planning.jpg'],
    ['03', 'Development',            'We write clean code and build the features module by module based on the approved design.', 'step-development.jpg'],
    ['04', 'Testing & evaluation',   'We test the system thoroughly, fix bugs, and gather feedback to make sure it meets the requirements.', 'step-reveal.jpg'],
    ['05', 'Deployment',             'We launch the finished system, prepare the documentation, and present it to the panel and end users.', 'step-deployment.jpg'],
  ];
  $pc_total = count($pc_steps);
  ?>
  <span class="pc-deco pc-d1" aria-hidden="true"></span>
  <span class="pc-deco pc-d2" aria-hidden="true"></span>
  <span class="pc-deco pc-d3" aria-hidden="true"></span>
  <span class="pc-deco pc-d4" aria-hidden="true">PROCESS</span>

  <div class="wrap">
    <div class="pc-head">
      <div data-pc="left">
        <p class="pc-kicker">Process</p>
        <h2 class="pc-h2">Our development process</h2>
      </div>
      <p class="pc-lead" data-pc="right">From the first sketch to the final defense, this is how we turn an idea into a working system. Pick a step to see it.</p>
    </div>

    <div class="pc-layout">

      <div class="pc-tabs" role="tablist" aria-label="Development steps" aria-orientation="vertical" data-pc="left">
        <?php foreach ($pc_steps as $i => $s) : ?>
          <button type="button" class="pc-tab<?php echo $i === 0 ? ' active' : ''; ?>" role="tab"
                  id="pc-tab-<?php echo $i; ?>" aria-controls="pc-panel-<?php echo $i; ?>"
                  aria-selected="<?php echo $i === 0 ? 'true' : 'false'; ?>" tabindex="<?php echo $i === 0 ? '0' : '-1'; ?>">
            <span class="pc-tnum"><?php echo esc_html($s[0]); ?></span>
            <span class="pc-ttext">
              <small class="pc-tstep">Step <?php echo esc_html($s[0]); ?></small>
              <b class="pc-ttl"><?php echo esc_html($s[1]); ?></b>
            </span>
            <span class="pc-tarrow" aria-hidden="true"><?php echo $arrow; ?></span>
          </button>
        <?php endforeach; ?>
      </div>

      <div class="pc-stage" data-pc="right">
        <div class="pc-panels">
          <?php foreach ($pc_steps as $i => $s) : ?>
            <article class="pc-panel<?php echo $i === 0 ? ' show' : ''; ?>" role="tabpanel"
                     id="pc-panel-<?php echo $i; ?>" aria-labelledby="pc-tab-<?php echo $i; ?>"
                     aria-hidden="<?php echo $i === 0 ? 'false' : 'true'; ?>">
              <div class="pc-media">
                <img class="pc-img" src="<?php echo esc_url(panto_img($s[3])); ?>" alt="<?php echo esc_attr($s[1]); ?>"<?php echo $i === 0 ? '' : ' loading="lazy"'; ?> decoding="async">
                <span class="pc-big" aria-hidden="true"><?php echo esc_html($s[0]); ?></span>
              </div>
              <div class="pc-body">
                <h3 class="pc-h3"><?php echo esc_html($s[1]); ?></h3>
                <p class="pc-text"><?php echo esc_html($s[2]); ?></p>
              </div>
            </article>
          <?php endforeach; ?>
        </div>

        <div class="pc-foot">
          <div class="pc-bar" aria-hidden="true"><i></i></div>
          <span class="pc-counter" aria-live="polite">1 / <?php echo $pc_total; ?></span>
          <div class="pc-arrows">
            <button type="button" class="pc-prev" aria-label="Previous step"><?php echo $left; ?></button>
            <button type="button" class="pc-next" aria-label="Next step"><?php echo $arrow; ?></button>
          </div>
        </div>
      </div>

    </div>
  </div>

  <style>
  /* ===== PROCESS: light, formal, animated ===== */
  body main section.steps#process {
    --pc-blue: #1f4fd8; --pc-ink: #15161a; --pc-soft: #4a4e5a; --pc-line: rgba(0, 0, 0, .12);
    padding: clamp(64px, 9vw, 110px) 0 !important;
    background:
      radial-gradient(ellipse 50% 55% at 6% 8%, rgba(169, 182, 160, .5), transparent 70%),
      radial-gradient(ellipse 55% 60% at 94% 90%, rgba(31, 79, 216, .12), transparent 70%),
      linear-gradient(180deg, #f6f7f9 0%, #eceeef 100%) !important;
    border-block: 0 !important; border-top: 1px solid var(--pc-line) !important;
  }

  body main #process .pc-deco { position: absolute; z-index: 0; display: block; pointer-events: none; user-select: none; }
  body main #process .pc-d1 {
    top: 60px; left: clamp(8px, 4vw, 60px); width: 240px; height: 170px;
    background-image: radial-gradient(rgba(31, 79, 216, .36) 1.5px, transparent 2px); background-size: 22px 22px;
    -webkit-mask-image: linear-gradient(135deg, #000, transparent 80%); mask-image: linear-gradient(135deg, #000, transparent 80%);
  }
  body main #process .pc-d2 {
    right: -90px; top: 80px; width: 300px; height: 300px; border-radius: 50%;
    border: 1px dashed rgba(31, 79, 216, .42); animation: spin 70s linear infinite;
  }
  body main #process .pc-d3 {
    left: 5%; bottom: 80px; width: 44px; height: 44px; border-radius: 10px;
    background: #1f4fd8; opacity: .16; animation: floaty 8s ease-in-out infinite;
  }
  body main #process .pc-d4 {
    left: 0; right: 0; bottom: -.1em; text-align: center; white-space: nowrap; line-height: 1;
    font-family: var(--sans); font-weight: 800; font-size: clamp(4rem, 14vw, 13rem); letter-spacing: .02em;
    color: transparent !important; -webkit-text-fill-color: transparent !important; -webkit-text-stroke: 1.5px rgba(31, 79, 216, .13);
  }

  body main #process .pc-head {
    display: grid; grid-template-columns: minmax(0, 1.1fr) minmax(0, .9fr);
    gap: clamp(20px, 5vw, 72px); align-items: end; margin-bottom: clamp(28px, 4vw, 48px);
  }
  body main #process .pc-kicker {
    display: flex; align-items: center; gap: 12px; margin: 0 0 14px;
    font-family: var(--sans); font-size: .78rem; font-weight: 700; letter-spacing: .2em; text-transform: uppercase;
    color: var(--pc-blue) !important;
  }
  body main #process .pc-kicker::before { content: ""; width: 28px; height: 2px; background: var(--pc-blue); }
  body main #process .pc-h2 {
    margin: 0; max-width: 16ch; font-family: var(--sans) !important; font-weight: 800 !important;
    font-size: clamp(1.9rem, 4.4vw, 3.2rem) !important; line-height: 1.08 !important; letter-spacing: -.02em !important;
    color: var(--pc-ink) !important; -webkit-text-fill-color: var(--pc-ink) !important;
  }
  body main #process .pc-lead {
    margin: 0; max-width: 46ch; padding-left: 18px; border-left: 2px solid var(--pc-blue);
    font-family: var(--sans); font-size: 1rem; line-height: 1.75; color: var(--pc-soft) !important;
  }

  /* layout */
  body main #process .pc-layout { display: grid; grid-template-columns: minmax(0, 5fr) minmax(0, 7fr); gap: clamp(22px, 4vw, 52px); align-items: start; }

  /* step list */
  body main #process .pc-tabs { position: relative; display: flex; flex-direction: column; gap: 14px; }
  body main #process .pc-tab {
    position: relative; display: grid; grid-template-columns: auto minmax(0, 1fr) auto; align-items: center; gap: 18px;
    width: 100%; padding: 18px 20px; text-align: left; cursor: pointer; border-radius: 18px;
    background: rgba(255, 255, 255, .55); border: 1px solid var(--pc-line);
    transition: background .3s, border-color .3s, box-shadow .3s, transform .3s var(--ease);
  }
  body main #process .pc-tab:hover { transform: translateX(6px); border-color: var(--pc-blue); }
  body main #process .pc-tab:focus-visible { outline: 2px solid var(--pc-blue); outline-offset: 3px; }
  body main #process .pc-tab.active {
    background: #fff; border-color: var(--pc-blue); transform: translateX(10px);
    box-shadow: 0 18px 40px rgba(31, 79, 216, .16);
  }
  body main #process .pc-tnum {
    width: 52px; height: 52px; border-radius: 50%; display: grid; place-items: center;
    font-family: var(--sans); font-size: 1rem; font-weight: 800; letter-spacing: .02em;
    color: var(--pc-blue) !important; background: #fff; border: 1.5px solid rgba(31, 79, 216, .35);
    transition: background .3s, color .3s, border-color .3s;
  }
  body main #process .pc-tab.active .pc-tnum { background: var(--pc-blue); color: #fff !important; border-color: var(--pc-blue); }
  body main #process .pc-ttext { display: block; min-width: 0; }
  body main #process .pc-tstep {
    display: block; margin-bottom: 3px; font-family: var(--sans); font-size: .7rem; font-weight: 700;
    letter-spacing: .16em; text-transform: uppercase; color: var(--pc-soft) !important;
  }
  body main #process .pc-ttl {
    display: block; font-family: var(--sans); font-size: 1.12rem; font-weight: 700; letter-spacing: -.01em;
    color: var(--pc-ink) !important; -webkit-text-fill-color: var(--pc-ink) !important;
  }
  body main #process .pc-tarrow { width: 20px; height: 20px; color: var(--pc-blue) !important; opacity: 0; transform: translateX(-8px); transition: opacity .3s, transform .3s; }
  body main #process .pc-tarrow svg { width: 20px; height: 20px; }
  body main #process .pc-tab.active .pc-tarrow { opacity: 1; transform: none; }

  /* stage */
  body main #process .pc-stage {
    overflow: hidden; border-radius: 22px; background: #fff; border: 1px solid var(--pc-line);
    box-shadow: 0 22px 50px rgba(0, 0, 0, .1);
  }
  body main #process .pc-panels { display: grid; }
  body main #process .pc-panel {
    grid-area: 1 / 1; opacity: 0; visibility: hidden; pointer-events: none; transform: translateX(40px);
    transition: opacity .55s ease, transform .7s var(--ease), visibility .55s;
  }
  body main #process .pc-panel.show { opacity: 1; visibility: visible; pointer-events: auto; transform: none; }
  body main #process .pc-panel.before { transform: translateX(-40px); }
  body main #process .pc-media { position: relative; aspect-ratio: 16 / 10; overflow: hidden; background: #dfe3e6; }
  body main #process .pc-img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; transform: scale(1.1); transition: transform 1.4s var(--ease); }
  body main #process .pc-panel.show .pc-img { transform: scale(1); }
  body main #process .pc-media::after { content: ""; position: absolute; inset: auto 0 0 0; height: 50%; background: linear-gradient(to top, rgba(0, 0, 0, .4), transparent); pointer-events: none; }
  body main #process .pc-big {
    position: absolute; left: 22px; bottom: 8px; z-index: 2; line-height: 1;
    font-family: var(--sans); font-weight: 800; font-size: clamp(4rem, 9vw, 6.5rem); letter-spacing: -.03em;
    color: transparent !important; -webkit-text-fill-color: transparent !important; -webkit-text-stroke: 1.8px rgba(255, 255, 255, .92);
  }
  body main #process .pc-body { padding: 24px 28px 8px; }
  body main #process .pc-h3 {
    margin: 0 0 8px !important; font-family: var(--sans) !important; font-size: 1.35rem !important; font-weight: 800 !important;
    color: var(--pc-ink) !important; -webkit-text-fill-color: var(--pc-ink) !important;
  }
  body main #process .pc-text { margin: 0 !important; max-width: 52ch; font-family: var(--sans); font-size: 1rem; line-height: 1.75; color: var(--pc-soft) !important; }

  body main #process .pc-foot { display: flex; align-items: center; gap: 16px; padding: 18px 28px 24px; }
  body main #process .pc-bar { flex: 1; height: 3px; border-radius: 3px; background: rgba(0, 0, 0, .1); overflow: hidden; }
  body main #process .pc-bar i { display: block; height: 100%; width: 0; background: var(--pc-blue); }
  body main #process .pc-bar i.run { animation: pcFill var(--pc-time, 6s) linear forwards; }
  @keyframes pcFill { to { width: 100%; } }
  body main #process .pc-counter { font-family: var(--sans); font-size: .82rem; font-weight: 700; letter-spacing: .08em; color: var(--pc-soft) !important; }
  body main #process .pc-arrows { display: flex; gap: 8px; }
  body main #process .pc-arrows button {
    width: 40px; height: 40px; border-radius: 50%; display: grid; place-items: center; cursor: pointer;
    color: var(--pc-ink) !important; background: #fff; border: 1px solid var(--pc-line); box-shadow: 0 6px 14px rgba(0, 0, 0, .08);
    transition: background .2s, color .2s, transform .2s, border-color .2s;
  }
  body main #process .pc-arrows button:hover { background: var(--pc-blue); color: #fff !important; border-color: var(--pc-blue); transform: scale(1.08); }
  body main #process .pc-arrows svg { width: 17px; height: 17px; }

  /* scroll in / out */
  html.pc-js body main #process [data-pc] { transition: opacity .8s var(--ease), transform .9s var(--ease); will-change: opacity, transform; }
  html.pc-js body main #process [data-pc]:not(.pc-in)         { opacity: 0 !important; transform: translateY(70px) scale(.95); }
  html.pc-js body main #process [data-pc="left"]:not(.pc-in)  { transform: translateX(-90px) scale(.95); }
  html.pc-js body main #process [data-pc="right"]:not(.pc-in) { transform: translateX(90px) scale(.95); }
  html.pc-js body main #process [data-pc].pc-up:not(.pc-in)   { transform: translateY(-60px) scale(.95); }
  html.pc-js body main #process [data-pc].pc-down:not(.pc-in) { transform: translateY(70px) scale(.95); }
  html.pc-js body main #process [data-pc].pc-in               { opacity: 1 !important; transform: none; }

  @media (max-width: 900px) {
    body main #process .pc-head, body main #process .pc-layout { grid-template-columns: 1fr; align-items: start; }
    body main #process .pc-tabs { flex-direction: row; overflow-x: auto; padding-bottom: 6px; scrollbar-width: none; }
    body main #process .pc-tab { min-width: 230px; }
    body main #process .pc-tab:hover, body main #process .pc-tab.active { transform: none; }
    body main #process .pc-tarrow { display: none; }
  }
  @media (max-width: 600px) {
    body main #process .pc-d1, body main #process .pc-d3 { display: none; }
    body main #process .pc-body { padding: 20px 20px 6px; }
    body main #process .pc-foot { padding: 16px 20px 20px; }
  }
  </style>

  <script>
  (function () {
    var sec = document.getElementById('process');
    if (!sec) return;
    var tabs   = [].slice.call(sec.querySelectorAll('.pc-tab'));
    var panels = [].slice.call(sec.querySelectorAll('.pc-panel'));
    var fill   = sec.querySelector('.pc-bar i');
    var cnt    = sec.querySelector('.pc-counter');
    var stage  = sec.querySelector('.pc-stage');
    var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var cur = 0, timer = null, auto = !reduce && tabs.length > 1, visible = false, TIME = 6000;

    function bar() {
      if (!fill) return;
      fill.classList.remove('run'); void fill.offsetWidth;
      if (auto && visible) { fill.style.setProperty('--pc-time', (TIME / 1000) + 's'); fill.classList.add('run'); }
      else fill.style.width = '0';
    }
    function go(n) {
      n = (n + tabs.length) % tabs.length;
      panels.forEach(function (p, k) {
        p.classList.toggle('show', k === n);
        p.classList.toggle('before', k < n);
        p.setAttribute('aria-hidden', k === n ? 'false' : 'true');
      });
      tabs.forEach(function (t, k) {
        t.classList.toggle('active', k === n);
        t.setAttribute('aria-selected', k === n ? 'true' : 'false');
        t.tabIndex = k === n ? 0 : -1;
      });
      cur = n;
      if (cnt) cnt.textContent = (n + 1) + ' / ' + tabs.length;
      bar(); schedule();
    }
    function schedule() {
      clearTimeout(timer);
      if (auto && visible) timer = setTimeout(function () { go(cur + 1); }, TIME);
    }
    function stopAuto() { auto = false; clearTimeout(timer); bar(); }

    tabs.forEach(function (t, k) {
      t.addEventListener('click', function () { stopAuto(); go(k); });
      t.addEventListener('keydown', function (e) {
        var d = (e.key === 'ArrowDown' || e.key === 'ArrowRight') ? 1 : (e.key === 'ArrowUp' || e.key === 'ArrowLeft') ? -1 : 0;
        if (!d) return;
        e.preventDefault(); stopAuto(); go(cur + d); tabs[cur].focus();
      });
    });
    sec.querySelector('.pc-prev').addEventListener('click', function () { stopAuto(); go(cur - 1); });
    sec.querySelector('.pc-next').addEventListener('click', function () { stopAuto(); go(cur + 1); });
    if (stage) {
      stage.addEventListener('mouseenter', function () { clearTimeout(timer); if (fill) fill.classList.remove('run'); });
      stage.addEventListener('mouseleave', function () { bar(); schedule(); });
    }

    /* swipe on phones */
    var x0 = null;
    if (stage) {
      stage.addEventListener('touchstart', function (e) { x0 = e.touches[0].clientX; }, { passive: true });
      stage.addEventListener('touchend', function (e) {
        if (x0 === null) return;
        var dx = e.changedTouches[0].clientX - x0;
        if (Math.abs(dx) > 50) { stopAuto(); go(dx < 0 ? cur + 1 : cur - 1); }
        x0 = null;
      });
    }

    /* scroll in / out + autoplay only while the section is on screen */
    if (!('IntersectionObserver' in window)) { visible = false; return; }
    var els = sec.querySelectorAll('[data-pc]');
    if (!reduce) document.documentElement.classList.add('pc-js');
    new IntersectionObserver(function (en) {
      visible = en[0].isIntersecting; bar(); schedule();
    }, { threshold: 0.3 }).observe(sec);

    if (reduce) return;
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        var el = en.target;
        if (en.isIntersecting) { el.classList.remove('pc-up', 'pc-down'); el.classList.add('pc-in'); }
        else { el.classList.remove('pc-in'); el.classList.add(en.boundingClientRect.top < 0 ? 'pc-up' : 'pc-down'); }
      });
    }, { threshold: 0.15, rootMargin: '0px 0px -6% 0px' });
    [].forEach.call(els, function (el) { io.observe(el); });
  })();
  </script>
</section>

<!-- FEEDBACK -->
<?php
$fv_count = count($feedbacks);
$fv_sum = 0;
foreach ($feedbacks as $f) { $fv_sum += max(0, min(5, (int) ($f['rating'] ?? 5))); }
$fv_base_count = $fv_count;
$fv_base_sum   = $fv_sum;

// include visitor ratings in the average
$vs = function_exists('occ_fb_stats') ? occ_fb_stats() : ['count' => 0, 'sum' => 0];
$fv_all_count = $fv_base_count + $vs['count'];
$fv_all_sum   = $fv_base_sum + $vs['sum'];
$fv_avg = $fv_all_count ? number_format($fv_all_sum / $fv_all_count, 1) : '0.0';
?>
<section class="testimonials" id="feedback">
  <span class="fv-deco fv-d1" aria-hidden="true"></span>
  <span class="fv-deco fv-d2" aria-hidden="true"></span>
  <span class="fv-deco fv-d3" aria-hidden="true"></span>
  <span class="fv-deco fv-d4" aria-hidden="true">FEEDBACK</span>

  <div class="wrap">
    <div class="fv-head">
      <div data-fv="left">
        <p class="fv-kicker">Feedback</p>
        <h2 class="fv-h2">What people say about our team</h2>
      </div>
      <div class="fv-lead" data-fv="right">
        <p>Words from the people who guided and reviewed our work. Share your own thoughts about our system too, it takes less than a minute.</p>
        <div class="fv-stat">
          <span class="fv-avg"><?php echo esc_html($fv_avg); ?></span>
          <span class="fv-stat-r">
            <span class="fv-stat-stars" aria-hidden="true">&#9733;&#9733;&#9733;&#9733;&#9733;</span>
            <small>Average from <span class="fv-stat-n"><?php echo (int) $fv_all_count; ?></span> review<span class="fv-stat-s"><?php echo $fv_all_count === 1 ? '' : 's'; ?></span></small>
          </span>
        </div>
      </div>
    </div>

    <div class="fv-grid">

      <!-- slider (feedback from advisers, written by you in $feedbacks) -->
      <div class="fv-main" data-fv="left" data-fv-slider role="region" aria-roledescription="carousel" aria-label="Feedback about our team">
        <span class="fv-bigq" aria-hidden="true"><?php echo $quote_icon; ?></span>
        <div class="fv-slides" aria-live="polite">
          <?php foreach ($feedbacks as $i => $f) :
            $rate = max(0, min(5, (int) ($f['rating'] ?? 5))); ?>
            <figure class="fv-slide<?php echo $i === 0 ? ' show' : ''; ?>" aria-hidden="<?php echo $i === 0 ? 'false' : 'true'; ?>">
              <blockquote class="fv-quote"><?php echo esc_html($f['quote']); ?></blockquote>
              <figcaption class="fv-who">
                <span class="fv-avatar"><img src="<?php echo esc_url(panto_img($f['img'])); ?>" alt="" loading="lazy" decoding="async"></span>
                <span class="fv-person"><b><?php echo esc_html($f['name']); ?></b><small><?php echo esc_html($f['role']); ?></small></span>
                <span class="fv-stars-out" role="img" aria-label="<?php echo $rate; ?> out of 5 stars"><?php echo str_repeat('&#9733;', $rate); ?><i><?php echo str_repeat('&#9733;', 5 - $rate); ?></i></span>
              </figcaption>
            </figure>
          <?php endforeach; ?>
        </div>

        <?php if ($fv_count > 1) : ?>
        <div class="fv-nav">
          <div class="fv-dots">
            <?php foreach ($feedbacks as $i => $f) : ?>
              <button type="button" class="<?php echo $i === 0 ? 'active' : ''; ?>" aria-label="Show feedback <?php echo $i + 1; ?>"></button>
            <?php endforeach; ?>
          </div>
          <span class="fv-counter">1 / <?php echo $fv_count; ?></span>
          <div class="fv-arrows">
            <button type="button" class="fv-prev" aria-label="Previous feedback"><?php echo $left; ?></button>
            <button type="button" class="fv-next" aria-label="Next feedback"><?php echo $arrow; ?></button>
          </div>
        </div>
        <?php endif; ?>
      </div>

      <!-- visitor feedback form (saved on the website) -->
      <form class="fv-form" id="fv-form" data-fv="right" novalidate>
        <h3 class="fv-h3">Rate our work</h3>
        <p class="fv-sub">Tell us what you think about our system. Tap &ldquo;View comments&rdquo; to read what other visitors said.</p>

        <fieldset class="fv-rate">
          <legend class="screen-reader-text">Your rating</legend>
          <div class="fv-stars-in">
            <?php for ($i = 5; $i >= 1; $i--) : ?>
              <input type="radio" name="fv_rate" id="fvr<?php echo $i; ?>" value="<?php echo $i; ?>">
              <label for="fvr<?php echo $i; ?>" title="<?php echo $i; ?> star<?php echo $i > 1 ? 's' : ''; ?>"><span class="screen-reader-text"><?php echo $i; ?> star<?php echo $i > 1 ? 's' : ''; ?></span>&#9733;</label>
            <?php endfor; ?>
          </div>
          <span class="fv-rate-txt" aria-live="polite">Tap a star</span>
        </fieldset>

        <label class="fv-field"><span>Your name <em>(optional)</em></span>
          <input type="text" name="fv_name" maxlength="60" autocomplete="name">
        </label>
        <label class="fv-field"><span>Comment</span>
          <textarea name="fv_message" rows="4" maxlength="600" required placeholder="What do you think about our system?"></textarea>
          <small class="fv-chars">0 / 600</small>
        </label>

        <!-- honeypot (hidden from people, bots fill it) -->
        <input type="text" name="website" tabindex="-1" autocomplete="off" aria-hidden="true" style="position:absolute;left:-9999px;opacity:0;height:0;width:0">

        <div class="fv-actions">
          <button type="submit" class="fv-send">Post feedback <?php echo $arrow; ?></button>
        </div>
        <button type="button" class="fc-viewall">View comments (0)</button>
        <p class="fv-note" role="status" aria-live="polite"></p>
      </form>

    </div>
  </div>

  <!-- comments modal (moved to <body> by the script below) -->
  <div class="fc-modal" role="dialog" aria-modal="true" aria-label="All comments" aria-hidden="true">
    <div class="fc-dialog">
      <div class="fc-mhead">
        <h3 class="fc-h3">All comments <span class="fc-badge fc-total">0</span></h3>
        <button type="button" class="fc-x" aria-label="Close"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18"/></svg></button>
      </div>
      <div class="fc-mbody">
        <ul class="fc-list fc-full"></ul>
        <p class="fc-empty" hidden>No comments yet. Be the first to share your thoughts!</p>
        <button type="button" class="fc-more" hidden>Load more comments</button>
      </div>
      <div class="fc-mfoot">
        <button type="button" class="fc-write">Write a comment <?php echo $arrow; ?></button>
      </div>
    </div>
  </div>

  <style>
  /* ===== FEEDBACK: light, formal, animated ===== */
  body main section.testimonials#feedback {
    --fv-blue: #1f4fd8; --fv-ink: #15161a; --fv-soft: #4a4e5a; --fv-line: rgba(0, 0, 0, .12); --fv-star: #f2a900;
    padding: clamp(64px, 9vw, 110px) 0 !important;
    background:
      radial-gradient(ellipse 55% 60% at 90% 6%, rgba(31, 79, 216, .13), transparent 70%),
      radial-gradient(ellipse 50% 55% at 4% 94%, rgba(169, 182, 160, .5), transparent 70%),
      linear-gradient(180deg, #eceeef 0%, #f6f7f9 100%) !important;
    border-block: 0 !important; border-top: 1px solid var(--fv-line) !important;
  }

  body main #feedback .fv-deco { position: absolute; z-index: 0; display: block; pointer-events: none; user-select: none; }
  body main #feedback .fv-d1 {
    top: 70px; left: clamp(8px, 4vw, 60px); width: 240px; height: 170px;
    background-image: radial-gradient(rgba(31, 79, 216, .36) 1.5px, transparent 2px); background-size: 22px 22px;
    -webkit-mask-image: linear-gradient(135deg, #000, transparent 80%); mask-image: linear-gradient(135deg, #000, transparent 80%);
  }
  body main #feedback .fv-d2 {
    left: -140px; bottom: -170px; width: 440px; height: 440px; border-radius: 50%;
    background: #a9b6a0; opacity: .32; outline: 1px solid rgba(31, 79, 216, .4); outline-offset: 20px;
  }
  body main #feedback .fv-d3 {
    right: -90px; top: 40%; width: 280px; height: 280px; border-radius: 50%;
    border: 1px dashed rgba(31, 79, 216, .42); animation: spin 70s linear infinite;
  }
  body main #feedback .fv-d4 {
    left: 0; right: 0; bottom: -.1em; text-align: center; white-space: nowrap; line-height: 1;
    font-family: var(--sans); font-weight: 800; font-size: clamp(4rem, 14vw, 13rem); letter-spacing: .02em;
    color: transparent !important; -webkit-text-fill-color: transparent !important; -webkit-text-stroke: 1.5px rgba(31, 79, 216, .13);
  }

  body main #feedback .fv-head {
    display: grid; grid-template-columns: minmax(0, 1.1fr) minmax(0, .9fr);
    gap: clamp(20px, 5vw, 72px); align-items: end; margin-bottom: clamp(28px, 4vw, 48px);
  }
  body main #feedback .fv-kicker {
    display: flex; align-items: center; gap: 12px; margin: 0 0 14px;
    font-family: var(--sans); font-size: .78rem; font-weight: 700; letter-spacing: .2em; text-transform: uppercase;
    color: var(--fv-blue) !important;
  }
  body main #feedback .fv-kicker::before { content: ""; width: 28px; height: 2px; background: var(--fv-blue); }
  body main #feedback .fv-h2 {
    margin: 0; max-width: 18ch; font-family: var(--sans) !important; font-weight: 800 !important;
    font-size: clamp(1.9rem, 4.4vw, 3.2rem) !important; line-height: 1.08 !important; letter-spacing: -.02em !important;
    color: var(--fv-ink) !important; -webkit-text-fill-color: var(--fv-ink) !important;
  }
  body main #feedback .fv-lead { padding-left: 18px; border-left: 2px solid var(--fv-blue); }
  body main #feedback .fv-lead p { margin: 0 0 14px; max-width: 46ch; font-family: var(--sans); font-size: 1rem; line-height: 1.75; color: var(--fv-soft) !important; }
  body main #feedback .fv-stat { display: flex; align-items: center; gap: 14px; }
  body main #feedback .fv-avg { font-family: var(--sans); font-size: 2.4rem; font-weight: 800; line-height: 1; color: var(--fv-ink) !important; }
  body main #feedback .fv-stat-r { display: flex; flex-direction: column; gap: 2px; }
  body main #feedback .fv-stat-stars { color: var(--fv-star) !important; letter-spacing: 3px; font-size: 1.05rem; }
  body main #feedback .fv-stat-r small { font-family: var(--sans); font-size: .8rem; color: var(--fv-soft) !important; }

  body main #feedback .fv-grid { display: grid; grid-template-columns: minmax(0, 1.25fr) minmax(0, 1fr); gap: clamp(20px, 3vw, 34px); align-items: stretch; }
  body main #feedback .fc-card { grid-column: 1 / -1; }

  /* slider card */
  body main #feedback .fv-main {
    position: relative; display: flex; flex-direction: column; overflow: hidden;
    padding: clamp(26px, 3.4vw, 40px); border-radius: 22px; background: #fff;
    border: 1px solid var(--fv-line); box-shadow: 0 22px 50px rgba(0, 0, 0, .1);
  }
  body main #feedback .fv-main::before { content: ""; position: absolute; left: 0; top: 0; bottom: 0; width: 5px; background: linear-gradient(180deg, var(--fv-blue), #a9b6a0); }
  body main #feedback .fv-bigq { display: grid; place-items: center; width: 48px; height: 48px; margin-bottom: 18px; border-radius: 50%; color: #fff !important; background: var(--fv-blue); box-shadow: 0 10px 22px rgba(31, 79, 216, .3); }
  body main #feedback .fv-bigq svg { width: 22px; height: 22px; }
  body main #feedback .fv-slides { display: grid; flex: 1; }
  body main #feedback .fv-slide {
    grid-area: 1 / 1; display: flex; flex-direction: column; gap: 24px; margin: 0;
    opacity: 0; visibility: hidden; pointer-events: none; transform: translateX(40px);
    transition: opacity .5s ease, transform .7s var(--ease), visibility .5s;
  }
  body main #feedback .fv-slide.show { opacity: 1; visibility: visible; pointer-events: auto; transform: none; }
  body main #feedback .fv-slide.before { transform: translateX(-40px); }
  body main #feedback .fv-quote {
    margin: 0; font-family: var(--sans); font-size: clamp(1.05rem, 1.7vw, 1.3rem); line-height: 1.7; font-weight: 500;
    color: var(--fv-ink) !important; -webkit-text-fill-color: var(--fv-ink) !important;
  }
  body main #feedback .fv-who { display: flex; align-items: center; flex-wrap: wrap; gap: 14px; margin-top: auto; padding-top: 20px; border-top: 1px solid var(--fv-line); }
  body main #feedback .fv-avatar { width: 54px; height: 54px; border-radius: 50%; overflow: hidden; flex: none; background: #a9b6a0; box-shadow: 0 0 0 3px #fff, 0 0 0 4px rgba(31, 79, 216, .4); }
  body main #feedback .fv-avatar img { width: 100%; height: 100%; object-fit: cover; object-position: center top; }
  body main #feedback .fv-person { display: flex; flex-direction: column; min-width: 0; }
  body main #feedback .fv-person b { font-family: var(--sans); font-size: 1rem; line-height: 1.3; color: var(--fv-ink) !important; }
  body main #feedback .fv-person small { font-family: var(--sans); font-size: .82rem; letter-spacing: .04em; color: var(--fv-soft) !important; }
  body main #feedback .fv-stars-out { margin-left: auto; font-size: 1.1rem; letter-spacing: 3px; white-space: nowrap; color: var(--fv-star) !important; }
  body main #feedback .fv-stars-out i { font-style: normal; color: #d5d8de !important; }

  body main #feedback .fv-nav { display: flex; align-items: center; gap: 16px; margin-top: 24px; }
  body main #feedback .fv-dots { display: flex; gap: 8px; }
  body main #feedback .fv-dots button { width: 9px; height: 9px; padding: 0; border: 0; border-radius: 99px; cursor: pointer; background: rgba(0, 0, 0, .18); transition: width .3s var(--ease), background .2s; }
  body main #feedback .fv-dots button.active { width: 28px; background: var(--fv-blue); }
  body main #feedback .fv-counter { margin-left: auto; font-family: var(--sans); font-size: .82rem; font-weight: 700; letter-spacing: .08em; color: var(--fv-soft) !important; }
  body main #feedback .fv-arrows { display: flex; gap: 8px; }
  body main #feedback .fv-arrows button {
    width: 40px; height: 40px; border-radius: 50%; display: grid; place-items: center; cursor: pointer;
    color: var(--fv-ink) !important; background: #fff; border: 1px solid var(--fv-line); box-shadow: 0 6px 14px rgba(0, 0, 0, .08);
    transition: background .2s, color .2s, transform .2s, border-color .2s;
  }
  body main #feedback .fv-arrows button:hover { background: var(--fv-blue); color: #fff !important; border-color: var(--fv-blue); transform: scale(1.08); }
  body main #feedback .fv-arrows svg { width: 17px; height: 17px; }

  /* form card */
  body main #feedback .fv-form {
    position: relative; overflow: hidden; display: flex; flex-direction: column; gap: 14px;
    padding: clamp(24px, 3vw, 32px); border-radius: 22px; background: #fff;
    border: 1px solid var(--fv-line); box-shadow: 0 22px 50px rgba(0, 0, 0, .1);
  }
  body main #feedback .fv-form::before { content: ""; position: absolute; left: 0; right: 0; top: 0; height: 4px; background: linear-gradient(90deg, var(--fv-blue), #a9b6a0); }
  body main #feedback .fv-h3 { margin: 0 !important; font-family: var(--sans) !important; font-size: 1.3rem !important; font-weight: 800 !important; color: var(--fv-ink) !important; -webkit-text-fill-color: var(--fv-ink) !important; }
  body main #feedback .fv-sub { margin: 0; font-family: var(--sans); font-size: .9rem; line-height: 1.6; color: var(--fv-soft) !important; }

  body main #feedback .fv-rate { display: flex; align-items: center; gap: 14px; border: 0; min-width: 0; padding: 0; margin: 0; }
  body main #feedback .fv-stars-in { position: relative; display: inline-flex; flex-direction: row-reverse; gap: 4px; }
  body main #feedback .fv-stars-in input { position: absolute; opacity: 0; width: 1px; height: 1px; pointer-events: none; }
  body main #feedback .fv-stars-in label { cursor: pointer; font-size: 2.2rem; line-height: 1; color: #d5d8de !important; transition: color .15s, transform .15s; }
  body main #feedback .fv-stars-in label:hover,
  body main #feedback .fv-stars-in label:hover ~ label,
  body main #feedback .fv-stars-in input:checked ~ label { color: var(--fv-star) !important; }
  body main #feedback .fv-stars-in label:hover { transform: scale(1.18); }
  body main #feedback .fv-stars-in input:focus-visible + label { outline: 2px solid var(--fv-blue); outline-offset: 3px; border-radius: 4px; }
  body main #feedback .fv-rate-txt { font-family: var(--sans); font-size: .85rem; font-weight: 600; color: var(--fv-soft) !important; }
  body main #feedback .fv-rate.bad { animation: fvShake .4s; }
  @keyframes fvShake { 20%, 60% { transform: translateX(-6px); } 40%, 80% { transform: translateX(6px); } }

  body main #feedback .fv-field { display: flex; flex-direction: column; gap: 6px; }
  body main #feedback .fv-field > span { font-family: var(--sans); font-size: .82rem; font-weight: 700; color: var(--fv-ink) !important; }
  body main #feedback .fv-field em { font-style: normal; font-weight: 400; color: var(--fv-soft) !important; }
  body main #feedback .fv-field input,
  body main #feedback .fv-field textarea {
    width: 100%; padding: 12px 14px; border-radius: 12px; resize: vertical;
    font-family: var(--sans); font-size: .95rem; line-height: 1.5;
    color: var(--fv-ink); background: #f6f7f9; border: 1px solid var(--fv-line); caret-color: var(--fv-blue);
    transition: border-color .2s, box-shadow .2s, background .2s;
  }
  body main #feedback .fv-field input:focus,
  body main #feedback .fv-field textarea:focus { outline: none; background: #fff; border-color: var(--fv-blue); box-shadow: 0 0 0 4px rgba(31, 79, 216, .15); }
  body main #feedback .fv-field.bad input, body main #feedback .fv-field.bad textarea { border-color: #d6453d; box-shadow: 0 0 0 4px rgba(214, 69, 61, .12); }
  body main #feedback .fv-chars { align-self: flex-end; font-family: var(--sans); font-size: .76rem; color: var(--fv-soft) !important; }

  body main #feedback .fv-actions { display: flex; flex-wrap: wrap; gap: 10px; }
  body main #feedback .fv-send {
    display: inline-flex; align-items: center; gap: 12px; padding: 14px 26px; border-radius: 999px; cursor: pointer; border: 0;
    font-family: var(--sans); font-size: .8rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase;
    color: #fff !important; background: var(--fv-blue); box-shadow: 0 10px 24px rgba(31, 79, 216, .3);
    transition: transform .2s, box-shadow .2s, background .2s, opacity .2s;
  }
  body main #feedback .fv-send svg { width: 16px; height: 16px; transition: transform .2s; }
  body main #feedback .fv-send:hover { background: #183fb0; transform: translateY(-2px); box-shadow: 0 14px 30px rgba(31, 79, 216, .38); }
  body main #feedback .fv-send:hover svg { transform: translateX(4px); }
  body main #feedback .fv-send:disabled { opacity: .6; cursor: wait; transform: none; }
  body main #feedback .fv-note { min-height: 1.3em; margin: 0; font-family: var(--sans); font-size: .86rem; font-weight: 600; color: var(--fv-soft) !important; }
  body main #feedback .fv-note.ok  { color: #0f8a62 !important; }
  body main #feedback .fv-note.err { color: #d6453d !important; }

  /* ===== Facebook-style comments ===== */
  body main #feedback .fc-card {
    position: relative; padding: clamp(22px, 3vw, 32px); border-radius: 22px; background: #fff;
    border: 1px solid var(--fv-line); box-shadow: 0 22px 50px rgba(0, 0, 0, .1);
  }
  body main #feedback .fc-top, body .fc-mhead { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 18px; }
  body main #feedback .fc-h3, body .fc-h3 {
    margin: 0 !important; display: flex; align-items: center; gap: 10px;
    font-family: var(--sans) !important; font-size: 1.2rem !important; font-weight: 800 !important;
    color: #15161a !important; -webkit-text-fill-color: #15161a !important;
  }
  body main #feedback .fc-badge, body .fc-badge {
    min-width: 28px; padding: 2px 10px; border-radius: 99px; text-align: center;
    font-family: var(--sans); font-size: .8rem; font-weight: 700; color: #1f4fd8; background: rgba(31, 79, 216, .1);
  }
  body main #feedback .fc-viewall {
    cursor: pointer; padding: 10px 18px; border-radius: 999px; background: transparent;
    font-family: var(--sans); font-size: .78rem; font-weight: 700; letter-spacing: .06em; text-transform: uppercase;
    color: var(--fv-ink) !important; border: 1px solid rgba(0, 0, 0, .3); transition: background .2s, color .2s, border-color .2s;
  }
  body main #feedback .fc-viewall:hover { background: var(--fv-blue); color: #fff !important; border-color: var(--fv-blue); }
  body main #feedback .fv-form .fc-viewall {
    align-self: flex-start; padding: 8px 14px; font-size: .72rem;
    border-color: rgba(0, 0, 0, .2);
  }

  body main #feedback .fc-list, body .fc-list { list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 16px; }
  body main #feedback .fc-item, body .fc-item { display: flex; gap: 12px; align-items: flex-start; animation: fcIn .5s var(--ease, ease) both; }
  @keyframes fcIn { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: none; } }
  body main #feedback .fc-av, body .fc-av {
    flex: none; width: 42px; height: 42px; border-radius: 50%; display: grid; place-items: center;
    font-family: var(--sans); font-size: 1.05rem; font-weight: 800; color: #fff;
    background: hsl(var(--h, 220) 60% 46%);
  }
  body main #feedback .fc-main, body .fc-main { min-width: 0; flex: 1; }
  body main #feedback .fc-bubble, body .fc-bubble {
    display: inline-block; max-width: 100%; padding: 10px 16px 12px; border-radius: 18px; background: #f0f2f5;
  }
  body main #feedback .fc-name, body .fc-name { display: block; font-family: var(--sans); font-size: .92rem; font-weight: 700; color: #15161a !important; }
  body main #feedback .fc-stars, body .fc-stars { display: block; font-size: .85rem; letter-spacing: 2px; color: #f2a900 !important; }
  body main #feedback .fc-stars i, body .fc-stars i { font-style: normal; color: #cfd3da !important; }
  body main #feedback .fc-text, body .fc-text {
    margin: 4px 0 0 !important; font-family: var(--sans); font-size: .95rem; line-height: 1.55; color: #15161a !important;
    white-space: pre-wrap; word-break: break-word;
  }
  body main #feedback .fc-meta, body .fc-meta { display: flex; align-items: center; gap: 14px; padding: 4px 8px 0; font-family: var(--sans); font-size: .78rem; color: #65676b; }
  body main #feedback .fc-like, body .fc-like {
    padding: 0; border: 0; background: none; cursor: pointer; font: inherit; font-weight: 700; color: #65676b;
  }
  body main #feedback .fc-like:hover, body .fc-like:hover { text-decoration: underline; }
  body main #feedback .fc-like.on, body .fc-like.on { color: #1f4fd8; }
  body main #feedback .fc-likes, body .fc-likes { display: inline-flex; align-items: center; gap: 4px; font-weight: 600; }
  body main #feedback .fc-likes[hidden], body .fc-likes[hidden] { display: none; }
  body main #feedback .fc-empty, body .fc-empty { margin: 8px 0 0; font-family: var(--sans); font-size: .95rem; color: #65676b; text-align: center; padding: 18px 0; }

  /* modal */
  body .fc-modal {
    position: fixed; inset: 0; z-index: 10060; display: grid; place-items: center; padding: 20px;
    background: rgba(8, 10, 18, .7); -webkit-backdrop-filter: blur(6px); backdrop-filter: blur(6px);
    opacity: 0; visibility: hidden; pointer-events: none; transition: opacity .3s ease, visibility .3s;
  }
  body .fc-modal.open { opacity: 1; visibility: visible; pointer-events: auto; }
  body .fc-modal:not(.open), body .fc-modal:not(.open) * { pointer-events: none !important; }
  body .fc-dialog {
    display: flex; flex-direction: column; width: min(640px, 100%); max-height: min(86vh, 760px);
    border-radius: 20px; background: #fff; box-shadow: 0 30px 80px rgba(0, 0, 0, .45);
    transform: translateY(20px) scale(.97); transition: transform .4s cubic-bezier(.2, .7, .2, 1);
  }
  body .fc-modal.open .fc-dialog { transform: none; }
  body .fc-mhead { padding: 18px 22px; margin: 0; border-bottom: 1px solid rgba(0, 0, 0, .1); }
  body .fc-x {
    width: 38px; height: 38px; border-radius: 50%; display: grid; place-items: center; cursor: pointer;
    color: #15161a; background: #f0f2f5; border: 0; transition: background .2s, color .2s;
  }
  body .fc-x:hover { background: #1f4fd8; color: #fff; }
  body .fc-x svg { width: 18px; height: 18px; }
  body .fc-mbody { flex: 1; overflow-y: auto; padding: 20px 22px; overscroll-behavior: contain; }
  body .fc-more {
    display: block; margin: 18px auto 0; padding: 10px 22px; border-radius: 999px; cursor: pointer;
    font-family: var(--sans); font-size: .8rem; font-weight: 700; color: #15161a; background: #f0f2f5; border: 0;
  }
  body .fc-more:hover { background: #e2e5ea; }
  body .fc-more[hidden] { display: none; }
  body .fc-mfoot { padding: 14px 22px; border-top: 1px solid rgba(0, 0, 0, .1); }
  body .fc-write {
    width: 100%; padding: 13px 22px; border-radius: 999px; cursor: pointer; border: 0;
    font-family: var(--sans); font-size: .8rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase;
    color: #fff; background: #1f4fd8; display: inline-flex; align-items: center; justify-content: center; gap: 10px;
  }
  body .fc-write svg { width: 16px; height: 16px; }
  body .fc-write:hover { background: #183fb0; }
  html.fc-lock { overflow: hidden; }

  /* scroll in / out */
  html.fv-js body main #feedback [data-fv] { transition: opacity .8s var(--ease), transform .9s var(--ease); will-change: opacity, transform; }
  html.fv-js body main #feedback [data-fv]:not(.fv-in)         { opacity: 0 !important; transform: translateY(70px) scale(.95); }
  html.fv-js body main #feedback [data-fv="left"]:not(.fv-in)  { transform: translateX(-90px) scale(.95); }
  html.fv-js body main #feedback [data-fv="right"]:not(.fv-in) { transform: translateX(90px) scale(.95); }
  html.fv-js body main #feedback [data-fv].fv-up:not(.fv-in)   { transform: translateY(-60px) scale(.95); }
  html.fv-js body main #feedback [data-fv].fv-down:not(.fv-in) { transform: translateY(70px) scale(.95); }
  html.fv-js body main #feedback [data-fv].fv-in               { opacity: 1 !important; transform: none; }

  @media (max-width: 900px) {
    body main #feedback .fv-head, body main #feedback .fv-grid { grid-template-columns: 1fr; align-items: start; }
  }
  @media (max-width: 600px) {
    body main #feedback .fv-d1 { display: none; }
    body main #feedback .fv-stars-out { margin-left: 0; }
    body main #feedback .fv-actions .fv-send { width: 100%; justify-content: center; }
    body main #feedback .fc-top { flex-wrap: wrap; }
    body .fc-modal { padding: 0; align-items: end; }
    body .fc-dialog { max-height: 92vh; border-radius: 20px 20px 0 0; }
  }
  </style>

  <script>
  window.OCC_FB = {
    ajax:  <?php echo wp_json_encode(admin_url('admin-ajax.php')); ?>,
    nonce: <?php echo wp_json_encode(wp_create_nonce('occ_fb')); ?>,
    baseCount: <?php echo (int) $fv_base_count; ?>,
    baseSum:   <?php echo (int) $fv_base_sum; ?>
  };

  (function () {
    var sec = document.getElementById('feedback');
    if (!sec) return;
    var cfg = window.OCC_FB;
    var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    /* ---------- feedback slider ---------- */
    var box = sec.querySelector('[data-fv-slider]');
    if (box) {
      var slides = [].slice.call(box.querySelectorAll('.fv-slide'));
      var dots = [].slice.call(box.querySelectorAll('.fv-dots button'));
      var cnt = box.querySelector('.fv-counter');
      var cur = 0, timer = null, paused = false, visible = false;

      function go(n) {
        n = (n + slides.length) % slides.length;
        slides.forEach(function (s, k) {
          s.classList.toggle('show', k === n);
          s.classList.toggle('before', k < n);
          s.setAttribute('aria-hidden', k === n ? 'false' : 'true');
        });
        dots.forEach(function (d, k) { d.classList.toggle('active', k === n); });
        if (cnt) cnt.textContent = (n + 1) + ' / ' + slides.length;
        cur = n; schedule();
      }
      function schedule() {
        clearTimeout(timer);
        if (slides.length > 1 && !paused && visible && !reduce) timer = setTimeout(function () { go(cur + 1); }, 7000);
      }
      if (slides.length > 1) {
        var p = box.querySelector('.fv-prev'), nx = box.querySelector('.fv-next');
        if (p)  p.addEventListener('click', function () { go(cur - 1); });
        if (nx) nx.addEventListener('click', function () { go(cur + 1); });
        dots.forEach(function (d, k) { d.addEventListener('click', function () { go(k); }); });
        box.addEventListener('mouseenter', function () { paused = true; clearTimeout(timer); });
        box.addEventListener('mouseleave', function () { paused = false; schedule(); });
        box.addEventListener('focusin',  function () { paused = true; clearTimeout(timer); });
        box.addEventListener('focusout', function () { paused = false; schedule(); });
        box.setAttribute('tabindex', '0');
        box.addEventListener('keydown', function (e) {
          if (e.key === 'ArrowLeft')  go(cur - 1);
          if (e.key === 'ArrowRight') go(cur + 1);
        });
        var x0 = null;
        box.addEventListener('touchstart', function (e) { x0 = e.touches[0].clientX; }, { passive: true });
        box.addEventListener('touchend', function (e) {
          if (x0 === null) return;
          var dx = e.changedTouches[0].clientX - x0;
          if (Math.abs(dx) > 50) go(dx < 0 ? cur + 1 : cur - 1);
          x0 = null;
        });
        if ('IntersectionObserver' in window) {
          new IntersectionObserver(function (en) { visible = en[0].isIntersecting; schedule(); }, { threshold: 0.3 }).observe(box);
        }
      }
    }

    /* ---------- helpers: ajax + liked store ---------- */
    function api(action, data) {
      var fd = new FormData();
      fd.append('action', action);
      fd.append('nonce', cfg.nonce);
      Object.keys(data || {}).forEach(function (k) { fd.append(k, data[k]); });
      return fetch(cfg.ajax, { method: 'POST', body: fd, credentials: 'same-origin' }).then(function (r) { return r.json(); });
    }
    function getLiked() { try { return JSON.parse(localStorage.getItem('occ_fb_liked') || '[]'); } catch (e) { return []; } }
    function setLiked(a) { try { localStorage.setItem('occ_fb_liked', JSON.stringify(a)); } catch (e) {} }

    /* ---------- comments (preview + modal) ---------- */
    var viewAll = sec.querySelector('.fc-viewall');
    var modal   = sec.querySelector('.fc-modal');
    var full = modal.querySelector('.fc-full');
    var emptyM = modal.querySelector('.fc-empty');
    var moreBtn = modal.querySelector('.fc-more');
    var totals = [].slice.call(document.querySelectorAll('#feedback .fc-total, .fc-modal .fc-total'));
    var PER = 8, fullPage = 0, fullPages = 1, fullLoaded = false, total = 0;
    document.body.appendChild(modal);

    function updateStats(s, t) {
      if (typeof t === 'number') {
        total = t;
        totals.forEach(function (el) { el.textContent = t; });
        viewAll.textContent = 'View comments (' + t + ')';
        emptyM.hidden = t > 0;
      }
      if (s) {
        var n = cfg.baseCount + s.count, sum = cfg.baseSum + s.sum;
        var a = sec.querySelector('.fv-avg'); if (a) a.textContent = n ? (sum / n).toFixed(1) : '0.0';
        var c = sec.querySelector('.fv-stat-n'); if (c) c.textContent = n;
        var ss = sec.querySelector('.fv-stat-s'); if (ss) ss.textContent = n === 1 ? '' : 's';
      }
    }

    function el(tag, cls, text) {
      var e = document.createElement(tag);
      if (cls) e.className = cls;
      if (text != null) e.textContent = text;
      return e;
    }
    function buildItem(it) {
      var li = el('li', 'fc-item');
      li.setAttribute('data-id', it.id);
      var av = el('span', 'fc-av', it.initial); av.style.setProperty('--h', it.hue); av.setAttribute('aria-hidden', 'true');

      var main = el('div', 'fc-main');
      var bub = el('div', 'fc-bubble');
      bub.appendChild(el('b', 'fc-name', it.name));
      var st = el('span', 'fc-stars', '\u2605'.repeat(it.rating));
      if (it.rating < 5) st.appendChild(el('i', '', '\u2605'.repeat(5 - it.rating)));
      st.setAttribute('aria-label', it.rating + ' out of 5 stars');
      bub.appendChild(st);
      bub.appendChild(el('p', 'fc-text', it.message));

      var meta = el('div', 'fc-meta');
      var liked = getLiked().indexOf(it.id) > -1;
      var like = el('button', 'fc-like' + (liked ? ' on' : ''), 'Like');
      like.type = 'button';
      var cntEl = el('span', 'fc-likes');
      function paint(n) { cntEl.textContent = '\uD83D\uDC4D ' + n; cntEl.hidden = n < 1; }
      paint(it.likes);
      like.addEventListener('click', function () {
        var on = like.classList.toggle('on');
        var list = getLiked(), i = list.indexOf(it.id);
        if (on && i < 0) list.push(it.id);
        if (!on && i > -1) list.splice(i, 1);
        setLiked(list);
        paint(Math.max(0, it.likes + (on ? 1 : -1)));
        api('occ_fb_like', { id: it.id, 'do': on ? 'like' : 'unlike' }).then(function (r) {
          if (r && r.success) { it.likes = r.data.likes; paint(it.likes); }
        }).catch(function () {});
      });
      meta.appendChild(like);
      meta.appendChild(cntEl);
      meta.appendChild(el('span', '', it.time));

      main.appendChild(bub); main.appendChild(meta);
      li.appendChild(av); li.appendChild(main);
      return li;
    }

    function loadPreview() {
      api('occ_fb_list', { page: 1, per: 1 }).then(function (r) {
        if (!r || !r.success) return;
        updateStats(r.data.stats, r.data.total);
      }).catch(function () {});
    }
    function loadFull(reset) {
      if (reset) { fullPage = 0; full.innerHTML = ''; }
      moreBtn.disabled = true;
      api('occ_fb_list', { page: fullPage + 1, per: PER }).then(function (r) {
        moreBtn.disabled = false;
        if (!r || !r.success) return;
        fullPage++; fullPages = r.data.pages; fullLoaded = true;
        r.data.items.forEach(function (it) { full.appendChild(buildItem(it)); });
        moreBtn.hidden = fullPage >= fullPages;
        updateStats(r.data.stats, r.data.total);
      }).catch(function () { moreBtn.disabled = false; });
    }
    moreBtn.addEventListener('click', function () { loadFull(false); });
    loadPreview();

    /* modal open / close */
    var lastFocus = null;
    function openModal() {
      lastFocus = document.activeElement;
      if (!fullLoaded) loadFull(true);
      modal.classList.add('open'); modal.setAttribute('aria-hidden', 'false');
      document.documentElement.classList.add('fc-lock');
      modal.querySelector('.fc-x').focus();
    }
    function closeModal() {
      modal.classList.remove('open'); modal.setAttribute('aria-hidden', 'true');
      document.documentElement.classList.remove('fc-lock');
      if (lastFocus && lastFocus.focus) lastFocus.focus();
    }
    viewAll.addEventListener('click', openModal);
    modal.querySelector('.fc-x').addEventListener('click', closeModal);
    modal.addEventListener('click', function (e) { if (e.target === modal) closeModal(); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && modal.classList.contains('open')) closeModal(); });
    modal.querySelector('.fc-write').addEventListener('click', function () {
      closeModal();
      var f = document.getElementById('fv-form');
      f.scrollIntoView({ behavior: reduce ? 'auto' : 'smooth', block: 'center' });
      setTimeout(function () { f.querySelector('textarea').focus({ preventScroll: true }); }, 500);
    });

    /* ---------- feedback form (saved on the website) ---------- */
    var form = document.getElementById('fv-form');
    if (form) {
      var note = form.querySelector('.fv-note');
      var msg = form.querySelector('textarea');
      var nm = form.querySelector('input[name="fv_name"]');
      var hp = form.querySelector('input[name="website"]');
      var chars = form.querySelector('.fv-chars');
      var rateBox = form.querySelector('.fv-rate');
      var rateTxt = form.querySelector('.fv-rate-txt');
      var sendBtn = form.querySelector('.fv-send');
      var labels = ['', 'Poor', 'Fair', 'Good', 'Very good', 'Excellent'];

      function rating() { var r = form.querySelector('input[name="fv_rate"]:checked'); return r ? parseInt(r.value, 10) : 0; }
      function say(t, c) { note.textContent = t; note.className = 'fv-note' + (c ? ' ' + c : ''); }
      function check() {
        var ok = true;
        rateBox.classList.remove('bad');
        msg.parentNode.classList.remove('bad');
        if (!rating()) { void rateBox.offsetWidth; rateBox.classList.add('bad'); say('Please choose a star rating.', 'err'); ok = false; }
        else if (msg.value.trim().length < 10) { msg.parentNode.classList.add('bad'); say('Please write at least 10 characters.', 'err'); msg.focus(); ok = false; }
        return ok;
      }

      [].forEach.call(form.querySelectorAll('input[name="fv_rate"]'), function (r) {
        r.addEventListener('change', function () { rateTxt.textContent = labels[rating()]; say(''); });
      });
      msg.addEventListener('input', function () {
        chars.textContent = msg.value.length + ' / ' + (msg.maxLength > 0 ? msg.maxLength : 600);
        msg.parentNode.classList.remove('bad');
      });

      form.addEventListener('submit', function (e) {
        e.preventDefault();
        if (!check()) return;
        sendBtn.disabled = true; say('Posting…', '');
        api('occ_fb_submit', { rating: rating(), name: nm.value.trim(), message: msg.value.trim(), website: hp.value })
          .then(function (r) {
            sendBtn.disabled = false;
            if (!r || !r.success) { say((r && r.data && r.data.msg) || 'Something went wrong. Please try again.', 'err'); return; }
            form.reset();
            rateTxt.textContent = 'Tap a star'; chars.textContent = '0 / 600';
            if (r.data.pending) { say('Thank you! Your feedback will appear once it is approved.', 'ok'); return; }
            say('Thank you! Your comment was posted.', 'ok');
            var it = r.data.item;
            if (fullLoaded) full.insertBefore(buildItem(it), full.firstChild);
            updateStats(r.data.stats, total + 1);
          })
          .catch(function () { sendBtn.disabled = false; say('No connection. Please try again.', 'err'); });
      });
    }

    /* ---------- scroll in / out animation ---------- */
    var els = sec.querySelectorAll('[data-fv]');
    if (!els.length || reduce || !('IntersectionObserver' in window)) return;
    document.documentElement.classList.add('fv-js');
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        var e = en.target;
        if (en.isIntersecting) { e.classList.remove('fv-up', 'fv-down'); e.classList.add('fv-in'); }
        else { e.classList.remove('fv-in'); e.classList.add(en.boundingClientRect.top < 0 ? 'fv-up' : 'fv-down'); }
      });
    }, { threshold: 0.15, rootMargin: '0px 0px -6% 0px' });
    [].forEach.call(els, function (e) { io.observe(e); });
  })();
  </script>
</section>

<!-- CLOSING CALL TO ACTION -->
<section class="cta" id="closing">
  <span class="ct-deco" aria-hidden="true"></span>

  <div class="wrap">
    <div class="ct-box">
      <p class="ct-kicker" data-ct>Let&rsquo;s connect</p>
      <h2 class="ct-h2" data-ct>Like what you see?<br>Meet the people behind it.</h2>
      <p class="ct-lead" data-ct>Every project here was planned, built and defended by this team.</p>
      <div class="ct-actions" data-ct>
        <a class="ct-btn ct-primary" href="#team" data-ct-scroll="#team">Meet the team <?php echo $arrow; ?></a>
        <a class="ct-btn ct-outline" href="#main" data-ct-top>Back to top <?php echo $up; ?></a>
      </div>
    </div>
  </div>

  <style>
  /* ===== CLOSING CTA: simple, light ===== */
  body main section.cta#closing {
    --ct-blue: #1f4fd8; --ct-ink: #15161a; --ct-soft: #4a4e5a; --ct-line: rgba(0, 0, 0, .12);
    padding: clamp(64px, 8vw, 100px) 0 !important;
    background:
      radial-gradient(ellipse 50% 70% at 50% 100%, rgba(169, 182, 160, .45), transparent 70%),
      linear-gradient(180deg, #f6f7f9 0%, #eceeef 100%) !important;
    border-block: 0 !important;
    border-top: 1px solid var(--ct-line) !important;
  }
  /* one soft dotted corner, nothing else */
  body main #closing .ct-deco {
    position: absolute; z-index: 0; top: 40px; right: clamp(8px, 4vw, 60px); width: 200px; height: 130px; pointer-events: none;
    background-image: radial-gradient(rgba(31, 79, 216, .3) 1.5px, transparent 2px); background-size: 22px 22px;
    -webkit-mask-image: linear-gradient(225deg, #000, transparent 80%); mask-image: linear-gradient(225deg, #000, transparent 80%);
  }

  body main #closing .ct-box { position: relative; max-width: 680px; margin-inline: auto; text-align: center; }

  body main #closing .ct-kicker {
    display: inline-flex; align-items: center; gap: 12px; margin: 0 0 16px;
    font-family: var(--sans); font-size: .78rem; font-weight: 700; letter-spacing: .2em; text-transform: uppercase;
    color: var(--ct-blue) !important;
  }
  body main #closing .ct-kicker::before,
  body main #closing .ct-kicker::after { content: ""; width: 24px; height: 2px; background: var(--ct-blue); }

  body main #closing .ct-h2 {
    margin: 0 0 14px !important;
    font-family: var(--sans) !important; font-weight: 800 !important;
    font-size: clamp(1.8rem, 4vw, 2.8rem) !important; line-height: 1.12 !important; letter-spacing: -.02em !important;
    color: var(--ct-ink) !important; -webkit-text-fill-color: var(--ct-ink) !important;
  }
  body main #closing .ct-h2::before, body main #closing .ct-h2::after { content: none !important; }
  body main #closing .ct-h2 br { display: inline; }

  body main #closing .ct-lead {
    margin: 0 auto 28px; max-width: 46ch;
    font-family: var(--sans); font-size: 1rem; line-height: 1.7; color: var(--ct-soft) !important;
  }

  body main #closing .ct-actions { display: flex; flex-wrap: wrap; justify-content: center; gap: 12px; }
  body main #closing .ct-btn {
    display: inline-flex; align-items: center; gap: 10px; padding: 14px 28px; border-radius: 4px; cursor: pointer;
    font-family: var(--sans); font-size: .8rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase;
    transition: transform .2s, box-shadow .2s, background .2s, color .2s, border-color .2s;
  }
  body main #closing .ct-btn svg { width: 16px; height: 16px; transition: transform .2s; }
  body main #closing .ct-primary { color: #fff !important; background: var(--ct-blue); box-shadow: 0 10px 24px rgba(31, 79, 216, .28); }
  body main #closing .ct-primary:hover { background: #183fb0; transform: translateY(-2px); box-shadow: 0 14px 30px rgba(31, 79, 216, .36); }
  body main #closing .ct-primary:hover svg { transform: translateX(4px); }
  body main #closing .ct-outline { color: var(--ct-ink) !important; background: transparent; border: 1px solid rgba(0, 0, 0, .3); }
  body main #closing .ct-outline:hover { background: var(--ct-ink); color: #fff !important; border-color: var(--ct-ink); transform: translateY(-2px); }
  body main #closing .ct-outline:hover svg { transform: translateY(-3px); }
  body main #closing .ct-btn:focus-visible { outline: 2px solid var(--ct-blue); outline-offset: 3px; }

  /* scroll in / out: soft fade + rise, one after another */
  html.ct-js body main #closing [data-ct] { transition: opacity .8s var(--ease), transform .9s var(--ease); will-change: opacity, transform; }
  html.ct-js body main #closing [data-ct]:not(.ct-in)         { opacity: 0 !important; transform: translateY(40px); }
  html.ct-js body main #closing [data-ct].ct-up:not(.ct-in)   { transform: translateY(-30px); }
  html.ct-js body main #closing [data-ct].ct-down:not(.ct-in) { transform: translateY(40px); }
  html.ct-js body main #closing [data-ct].ct-in               { opacity: 1 !important; transform: none; }
  html.ct-js body main #closing .ct-h2.ct-in      { transition-delay: .08s; }
  html.ct-js body main #closing .ct-lead.ct-in    { transition-delay: .16s; }
  html.ct-js body main #closing .ct-actions.ct-in { transition-delay: .24s; }

  @media (max-width: 600px) {
    body main #closing .ct-deco { display: none; }
    body main #closing .ct-actions .ct-btn { width: 100%; justify-content: center; }
  }
  </style>

  <script>
  (function () {
    var sec = document.getElementById('closing');
    if (!sec) return;
    var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var behavior = reduce ? 'auto' : 'smooth';

    /* smooth scroll buttons */
    var go = sec.querySelector('[data-ct-scroll]');
    if (go) go.addEventListener('click', function (e) {
      var t = document.querySelector(go.getAttribute('data-ct-scroll'));
      if (!t) return;
      e.preventDefault();
      t.scrollIntoView({ behavior: behavior, block: 'start' });
    });
    var top = sec.querySelector('[data-ct-top]');
    if (top) top.addEventListener('click', function (e) {
      e.preventDefault();
      window.scrollTo({ top: 0, behavior: behavior });
    });

    /* scroll in / out */
    var els = sec.querySelectorAll('[data-ct]');
    if (!els.length || reduce || !('IntersectionObserver' in window)) return;
    document.documentElement.classList.add('ct-js');
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        var el = en.target;
        if (en.isIntersecting) { el.classList.remove('ct-up', 'ct-down'); el.classList.add('ct-in'); }
        else { el.classList.remove('ct-in'); el.classList.add(en.boundingClientRect.top < 0 ? 'ct-up' : 'ct-down'); }
      });
    }, { threshold: 0.15, rootMargin: '0px 0px -6% 0px' });
    [].forEach.call(els, function (el) { io.observe(el); });
  })();
  </script>
</section>
</main>

<?php get_footer(); ?>