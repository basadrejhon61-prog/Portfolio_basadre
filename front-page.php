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
<section class="hero" style="background-image:url('<?php echo esc_url(panto_img('opol.jpg')); ?>')">
  <span class="hero-shape s1" aria-hidden="true"></span>
  <span class="hero-shape s2" aria-hidden="true"></span>
  <span class="hero-shape s3" aria-hidden="true"></span>
  <div class="wrap">
    <p class="eyebrow">Welcome to</p>
    <h1 class="rv-title" style="--ws:.09s">Our Portfolio</h1>
    <p class="lead">We are a team of BSIT students passionate about web development, system design, and creating digital solutions.</p>
    <div class="hero-actions">
      <a class="btn btn-dark" href="#projects">View our projects</a>
      <a class="btn btn-light" href="#team">Meet the team</a>
    </div>
    <div class="hero-stats">
      <div><b><?php echo count($team_members); ?></b><span>Team members</span></div>
      <div><b><?php echo count($skills); ?>+</b><span>Skills</span></div>
      <div><b>BSIT</b><span>Course</span></div>
    </div>
  </div>
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

<!-- TEAM -->
<section class="team-section" id="team">
  <span class="ghost" aria-hidden="true">TEAM</span>
  <div class="wrap">
    <div class="team-head">
      <p class="tag rv">01 &middot; The team</p>
      <h2 class="section-title rv-title">Meet our team</h2>
      <p class="team-intro rv">Each member has a role in our capstone project. Open a profile to see their responsibilities.</p>
    </div>

    <div class="tm-grid">
      <?php foreach ($team_members as $slug => $m) :
        $rm = occ_role_meta($m['role'] ?? ''); ?>
        <article class="tm-card rv" style="--a: var(<?php echo esc_attr($rm['color']); ?>)">
          <div class="tm-photo">
            <img src="<?php echo esc_url(panto_img($m['image'])); ?>" alt="<?php echo esc_attr($m['name']); ?>" loading="lazy" decoding="async">
          </div>
          <div class="tm-body">
            <h3 class="tm-name"><?php echo esc_html($m['name']); ?></h3>
            <p class="tm-role"><?php echo $icon($rm['icon']); ?> <?php echo esc_html($rm['label']); ?></p>
            <a class="tm-btn" href="<?php echo esc_url(add_query_arg('member', $slug, home_url('/'))); ?>">
              View profile <span class="screen-reader-text">of <?php echo esc_html($m['name']); ?></span> <?php echo $arrow; ?>
            </a>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
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
<section class="steps">
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