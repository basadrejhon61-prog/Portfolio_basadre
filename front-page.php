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
$icon  = function ($path) {
  return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $path . '</svg>';
};

/* Social icons shown under the skills (Facebook, Twitter, Instagram, TikTok).
   Links come from team-data.php: 'facebook', 'twitter', 'instagram', 'tiktok'
   (or inside a 'social' => [...] array). Missing link = the icon points to "#". */
$socials = [
  'facebook'  => ['Facebook',  '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M22 12a10 10 0 1 0-11.56 9.88v-6.99H7.9V12h2.54V9.8c0-2.5 1.49-3.89 3.77-3.89 1.09 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56V12h2.78l-.44 2.89h-2.34v6.99A10 10 0 0 0 22 12z"/></svg>'],
  'twitter'   => ['Twitter',   '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M22.46 6c-.77.35-1.6.58-2.46.69a4.3 4.3 0 0 0 1.88-2.37 8.6 8.6 0 0 1-2.72 1.04 4.28 4.28 0 0 0-7.29 3.9A12.15 12.15 0 0 1 3.07 4.8a4.28 4.28 0 0 0 1.33 5.71 4.27 4.27 0 0 1-1.94-.54v.05a4.28 4.28 0 0 0 3.43 4.2 4.3 4.3 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.97A8.6 8.6 0 0 1 2 19.54a12.1 12.1 0 0 0 6.56 1.92c7.88 0 12.2-6.53 12.2-12.2l-.01-.56A8.7 8.7 0 0 0 22.46 6z"/></svg>'],
  'instagram' => ['Instagram', '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>'],
  'tiktok'    => ['TikTok',    '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M16.6 5.82A4.28 4.28 0 0 1 15.54 3h-3.09v12.4a2.59 2.59 0 0 1-2.59 2.5 2.6 2.6 0 0 1-2.6-2.6 2.6 2.6 0 0 1 2.6-2.6c.27 0 .53.04.77.12V9.66a5.7 5.7 0 0 0-.77-.05A5.69 5.69 0 0 0 4.17 15.3a5.69 5.69 0 0 0 5.69 5.69 5.69 5.69 0 0 0 5.69-5.69V9.01a7.35 7.35 0 0 0 4.29 1.37V7.3a4.3 4.3 0 0 1-3.24-1.48z"/></svg>'],
];

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
   Add more entries to this list: arrows, dots and autoplay appear by themselves
   when there is more than one. Only add real feedback from real people. */
$feedbacks = [
  [
    'quote'  => 'This team demonstrated excellent collaboration and technical skills. Their capstone project was well-executed, highly functional, and exceeded our expectations.',
    'name'   => 'Prof. Rachel Ana Dantes',
    'role'   => 'Project Adviser',
    'img'    => 'Rachel.jpg',
    'rating' => 5,
  ],
];

/* Feedback form sends an email to the Project Manager (falls back to the first member with an email) */
$fb_email = '';
foreach ($team_members as $mm) {
  $r = occ_role_meta($mm['role'] ?? '');
  if ($r['key'] === 'manager' && !empty($mm['email'])) { $fb_email = $mm['email']; break; }
}
if (!$fb_email) {
  foreach ($team_members as $mm) { if (!empty($mm['email'])) { $fb_email = $mm['email']; break; } }
}
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

<!-- TEAM (one member at a time, arrows left / right) -->
<section class="team-section" id="team">
  <span class="ghost" aria-hidden="true">TEAM</span>
  <div class="wrap">
    <div class="team-head">
      <h2 class="section-title rv-title">Meet our team</h2>
    </div>

    <?php $tm_total = count($team_members); $tm_i = 0; ?>
    <div class="tm-showcase" data-tm role="region" aria-roledescription="carousel" aria-label="Team members">

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
        ?>
          <article class="tm-slide<?php echo $tm_i === 0 ? ' show' : ''; ?>"
                   style="--a: var(<?php echo esc_attr($rm['color']); ?>)"
                   aria-hidden="<?php echo $tm_i === 0 ? 'false' : 'true'; ?>">
            <div class="tm-pic">
              <img src="<?php echo esc_url(panto_img($m['image'])); ?>" alt="<?php echo esc_attr($m['name']); ?>" decoding="async">
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
              <?php if ($m_skills) : ?>
                <ul class="tm-skill-list">
                  <?php foreach ($m_skills as $s) : ?><li><?php echo esc_html($s); ?></li><?php endforeach; ?>
                </ul>
              <?php endif; ?>

              <!-- social links -->
              <ul class="tm-social" aria-label="<?php echo esc_attr($m['name']); ?> on social media">
                <?php foreach ($socials as $key => $soc) :
                  $url = '';
                  if (!empty($m[$key]) && is_string($m[$key])) { $url = $m[$key]; }
                  elseif (!empty($m['social'][$key]) && is_string($m['social'][$key])) { $url = $m['social'][$key]; }
                  $has = $url !== '';
                ?>
                  <li>
                    <a href="<?php echo $has ? esc_url($url) : '#'; ?>"
                       aria-label="<?php echo esc_attr($soc[0]); ?>" title="<?php echo esc_attr($soc[0]); ?>"
                       <?php echo $has ? 'target="_blank" rel="noopener noreferrer"' : 'aria-disabled="true"'; ?>>
                      <?php echo $soc[1]; ?>
                    </a>
                  </li>
                <?php endforeach; ?>
              </ul>

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
  })();
  </script>
</section>

<!-- MEMORIES -->
<section class="intro" id="collections">
  <span class="memo-deco d1" aria-hidden="true"></span>
  <span class="memo-deco d2" aria-hidden="true"></span>
  <span class="ghost" data-dir="-1" aria-hidden="true">MOMENTS</span>
  <div class="wrap">
    <p class="tag rv">02 &middot; Memories</p>
    <h2 class="section-title rv-title">School memories &amp; activities</h2>
    <p class="memo-sub rv">A few snapshots from our journey as BSIT students. Click a photo to see it bigger.</p>
    <?php
    $memories = [
      ['Team Collaboration',    'How we work together to solve problems.',      'team1.jpg'],
      ['Coding Sessions',       'Late night coding and debugging moments.',     'team2.jpg'],
      ['Project Presentations', 'Defending our systems in front of the panel.', 'presentation.jpg'],
      ['Friends & Classmates',  'The bond we built throughout college.',        'sch_evnts2.jpg'],
      ['School Events',         'Foundation day, IT Week, and celebrations.',   'sch_evnts.jpg'],
      ['Our Gallery',           'A collection of our favorite memories.',       'team6.jpg'],
    ];
    $tilts = ['-2.2deg', '1.6deg', '-1.2deg', '2deg', '-1.6deg', '1.2deg'];
    $tapes = ['--sun', '--sky', '--rose', '--emerald', '--violet', '--cobalt'];
    ?>
    <div class="memo-grid">
      <?php foreach ($memories as $i => $mem) : $src = esc_url(panto_img($mem[2])); ?>
        <a class="memo-card rv" data-parallax href="<?php echo $src; ?>" target="_blank" rel="noopener"
           style="--tilt: <?php echo $tilts[$i % 6]; ?>; --tape: var(<?php echo $tapes[$i % 6]; ?>)">
          <span class="memo-photo"><img class="memo-img" src="<?php echo $src; ?>" alt="<?php echo esc_attr($mem[0]); ?>" loading="lazy" decoding="async"></span>
          <div class="memo-txt">
            <span class="memo-n"><?php echo sprintf('%02d', $i + 1); ?></span>
            <h3><?php echo esc_html($mem[0]); ?></h3>
            <p><?php echo esc_html($mem[1]); ?></p>
            <span class="memo-more">View photo <?php echo $arrow; ?></span>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- PROJECTS -->
<section class="gallery" id="projects">
  <span class="ghost" aria-hidden="true">WORK</span>
  <div class="wrap">
    <p class="tag rv">03 &middot; Projects</p>
    <h2 class="section-title rv-title">A showcase of<br>our best works</h2>
    <div class="filters rv" role="group" aria-label="Filter projects">
      <button type="button" class="pill active" data-filter="all">All projects</button>
      <button type="button" class="pill" data-filter="web">Web development</button>
      <button type="button" class="pill" data-filter="app">App development</button>
      <button type="button" class="pill" data-filter="uiux">UI/UX design</button>
      <button type="button" class="pill" data-filter="system">System development</button>
    </div>
    <?php
    $projects = [
      ['web',    'Capstone Project',  'E-Commerce System',    'project1.jpg'],
      ['app',    'Student Portal',    'Web Application',      'project2.jpg'],
      ['uiux',   'Mobile App Design', 'UI/UX Prototype',      'project3.jpg'],
      ['system', 'Inventory System',  'Database Management',  'project4.jpg'],
    ];
    ?>
    <div class="slider rv" role="region" aria-roledescription="carousel" aria-label="Project showcase">
      <?php foreach ($projects as $i => $p) : ?>
        <div class="slide ph<?php echo $i === 0 ? ' show' : ''; ?>" data-cat="<?php echo esc_attr($p[0]); ?>">
          <img class="cover" src="<?php echo esc_url(panto_img($p[3])); ?>" alt="<?php echo esc_attr($p[1] . ' – ' . $p[2]); ?>"<?php echo $i === 0 ? '' : ' loading="lazy"'; ?> decoding="async">
          <div class="slide-tag"><i></i><div><b><?php echo esc_html($p[1]); ?></b><small><?php echo esc_html($p[2]); ?></small></div></div>
        </div>
      <?php endforeach; ?>
      <div class="slider-ui">
        <span class="count" aria-live="polite">1 / <?php echo count($projects); ?></span>
        <button type="button" class="prev" aria-label="Previous project"><?php echo $left; ?></button>
        <button type="button" class="next" aria-label="Next project"><?php echo $arrow; ?></button>
      </div>
    </div>
  </div>
</section>

<!-- PROCESS -->
<section class="steps" id="process">
  <span class="ghost" data-dir="-1" aria-hidden="true">BUILD</span>
  <div class="wrap">
    <p class="tag rv">04 &middot; Process</p>
    <h2 class="section-title rv-title">Our development<br>process</h2>
    <div class="steps-grid">
      <div class="step rv">
        <div class="step-head"><span class="step-num">01</span></div>
        <h3>Planning &amp; design</h3>
        <p>We brainstorm ideas, create wireframes, and plan the system architecture based on project requirements.</p>
        <div class="img ph"><img class="cover" src="<?php echo esc_url(panto_img('step-planning.jpg')); ?>" alt="" loading="lazy" decoding="async"></div>
      </div>
      <div class="step rv">
        <div class="step-head"><span class="step-num">02</span></div>
        <h3>Coding &amp; testing</h3>
        <p>We write clean code, integrate features, and test the system thoroughly so the experience is seamless.</p>
        <div class="img ph"><img class="cover" src="<?php echo esc_url(panto_img('step-reveal.jpg')); ?>" alt="" loading="lazy" decoding="async"></div>
      </div>
    </div>
  </div>
</section>

<!-- FEEDBACK -->
<section class="testimonials" id="feedback">
  <span class="ghost" aria-hidden="true">VOICES</span>
  <div class="wrap">
    <p class="tag rv">05 &middot; Feedback</p>
    <h2 class="section-title rv-title">What people say<br>about our team</h2>

    <div class="fb-grid<?php echo $fb_email ? '' : ' single'; ?>">

      <!-- feedback slider -->
      <div class="fb-main rv" role="region" aria-roledescription="carousel" aria-label="Feedback about our team" tabindex="-1">
        <div class="fb-slides">
          <?php foreach ($feedbacks as $i => $f) :
            $rate = max(0, min(5, (int) ($f['rating'] ?? 5))); ?>
            <figure class="fb-slide<?php echo $i === 0 ? ' show' : ''; ?>" aria-hidden="<?php echo $i === 0 ? 'false' : 'true'; ?>">
              <span class="fb-quote-ico"><?php echo $quote_icon; ?></span>
              <blockquote class="fb-quote"><?php echo esc_html($f['quote']); ?></blockquote>
              <figcaption class="fb-who">
                <span class="fb-avatar"><img src="<?php echo esc_url(panto_img($f['img'])); ?>" alt="" loading="lazy" decoding="async"></span>
                <span class="fb-person"><b><?php echo esc_html($f['name']); ?></b><small><?php echo esc_html($f['role']); ?></small></span>
                <span class="fb-stars" role="img" aria-label="<?php echo $rate; ?> out of 5 stars"><?php echo str_repeat('&#9733;', $rate); ?><i><?php echo str_repeat('&#9733;', 5 - $rate); ?></i></span>
              </figcaption>
            </figure>
          <?php endforeach; ?>
        </div>

        <?php if (count($feedbacks) > 1) : ?>
        <div class="fb-nav">
          <div class="fb-dots">
            <?php foreach ($feedbacks as $i => $f) : ?>
              <button type="button" class="<?php echo $i === 0 ? 'active' : ''; ?>" aria-label="Show feedback <?php echo $i + 1; ?>"></button>
            <?php endforeach; ?>
          </div>
          <div class="fb-arrows">
            <button type="button" class="fb-prev" aria-label="Previous feedback"><?php echo $left; ?></button>
            <button type="button" class="fb-next" aria-label="Next feedback"><?php echo $arrow; ?></button>
          </div>
        </div>
        <?php endif; ?>
      </div>

      <!-- rate us -->
      <?php if ($fb_email) : ?>
      <form class="fb-form rv" data-to="<?php echo esc_attr($fb_email); ?>" novalidate>
        <h3>Rate our work</h3>
        <p class="fb-sub">Tell us what you think. This opens your email app with your message ready to send to the team.</p>

        <fieldset class="fb-rate">
          <legend class="screen-reader-text">Your rating</legend>
          <div class="fb-stars-in">
            <?php for ($i = 5; $i >= 1; $i--) : ?>
              <input type="radio" name="rate" id="fbr<?php echo $i; ?>" value="<?php echo $i; ?>">
              <label for="fbr<?php echo $i; ?>" title="<?php echo $i; ?> star<?php echo $i > 1 ? 's' : ''; ?>"><span class="screen-reader-text"><?php echo $i; ?> star<?php echo $i > 1 ? 's' : ''; ?></span>&#9733;</label>
            <?php endfor; ?>
          </div>
        </fieldset>

        <label class="fb-field"><span>Your name <em>(optional)</em></span>
          <input type="text" name="name" maxlength="60" autocomplete="name">
        </label>
        <label class="fb-field"><span>Message</span>
          <textarea name="message" rows="4" maxlength="600" required></textarea>
        </label>

        <button type="submit" class="btn btn-dark">Send feedback <?php echo $arrow; ?></button>
        <p class="fb-note" role="status" aria-live="polite"></p>
      </form>
      <?php endif; ?>

    </div>
  </div>
</section>

<!-- CLOSING CALL TO ACTION -->
<section class="cta">
  <div class="wrap">
    <h2 class="rv-title">Like what you see?<br>Meet the people behind it.</h2>
    <div class="cta-actions rv">
      <a class="btn btn-dark" href="#team">Meet the team <?php echo $arrow; ?></a>
      <a class="btn btn-outline" href="#main">Back to top <?php echo $up; ?></a>
    </div>
  </div>
</section>

</main>

<?php get_footer(); ?>