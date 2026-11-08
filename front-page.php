<?php
// Impormasyon sa mga member + profile page (?member=slug)
require_once get_template_directory() . '/team-data.php';
$team_members = occ_get_team();
if (!empty($_GET['member']) && isset($team_members[sanitize_key($_GET['member'])])) {
  include get_template_directory() . '/member-profile.php';
  return;
}

get_header();
$arrow = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>';
$left  = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M11 6l-6 6 6 6"/></svg>';
$cap   = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 9l10-5 10 5-10 5-10-5z"/><path d="M6 11v5c0 1.5 3 3 6 3s6-1.5 6-3v-5"/><path d="M22 9v6"/></svg>';
$book  = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 5c3-1 7-1 10 1v14c-3-2-7-2-10-1V5z"/><path d="M22 5c-3-1-7-1-10 1v14c3-2 7-2 10-1V5z"/></svg>';
?>

<!-- HERO -->
<section class="hero" style="background-image:url('<?php echo panto_img('opol.jpg'); ?>')">
  <div class="wrap">
    <p class="eyebrow">WELCOME TO</p>
    <h1>OPOL COMMUNITY COLLEGE</h1>
    <p class="lead">Fueling ambition, advancing knowledge, and creating opportunities for every learner.</p>
    <div class="hero-actions">
      <a class="btn btn-dark" href="#collections">BSIT PROGRAM</a>
      <a class="btn btn-light" href="#about">LEARN MORE</a>
    </div>
  </div>
</section>

<!-- FEATURE CARDS (mo-lapaw sa hero) + SCHOOL MEMORIES -->
<section class="intro" id="collections">
  <div class="wrap">

    <div class="feat-cards" id="about">
      <?php foreach ($team_members as $slug => $m) : ?>
        <div class="feat" style="background:<?php echo esc_attr($m['color']); ?>">
          <div class="feat-img ph"><img class="cover" src="<?php echo panto_img($m['image']); ?>" alt="<?php echo esc_attr($m['name']); ?>"></div>
          <h3><?php echo esc_html($m['name']); ?></h3>
          <a class="feat-btn" href="<?php echo esc_url(add_query_arg('member', $slug, home_url('/'))); ?>">View Information <?php echo $arrow; ?></a>
        </div>
      <?php endforeach; ?>
    </div>

    <?php
    // 6 ka cards: 3 sa unang row, 3 sa ikaduha
    // [title, description, image filename]
    $memories = [
      ['School Memories',       'Mga hulagway ug storya nga dili nato malimtan.',        'opol.jpg'],
      ['Projects & Activities', 'Ang atong mga proyekto ug kalihokan sa eskwelahan.',     'opol.jpg'],
      ['Achievements',          'Mga kadaugan ug pagkilala sa atong mga estudyante.',     'opol.jpg'],
      ['Friends & Classmates',  'Ang mga higala ug classmates nga kauban nato.',          'opol.jpg'],
      ['School Events',         'Foundation day, graduation, ug uban pang selebrasyon.',  'opol.jpg'],
      ['Gallery',               'Tan-awa ang tanang hulagway sa tibuok school year.',     'opol.jpg'],
    ];
    ?>
    <div class="memo-grid">
      <?php foreach ($memories as $mem) : ?>
        <a class="memo-card ph" href="#">
          <img class="cover" src="<?php echo panto_img($mem[2]); ?>" alt="<?php echo esc_attr($mem[0]); ?>">
          <div class="memo-txt">
            <h3><?php echo esc_html($mem[0]); ?></h3>
            <p><?php echo esc_html($mem[1]); ?></p>
            <span class="memo-more">View <?php echo $arrow; ?></span>
          </div>
        </a>
      <?php endforeach; ?>
    </div>

  </div>
</section>

<!-- GALLERY / PROJECTS -->
<section class="gallery" id="inspiration">
  <div class="wrap">
    <h2 class="section-title">See How We've Transformed<br>Spaces Into Beautiful Works Of Art.</h2>
    <div class="filters" role="tablist">
      <button class="pill active" data-filter="all">All Projects</button>
      <button class="pill" data-filter="living">Living Room</button>
      <button class="pill" data-filter="bedroom">Bedroom</button>
      <button class="pill" data-filter="dining">Dining Room</button>
      <button class="pill" data-filter="outdoor">Outdoor</button>
    </div>
    <?php
    $projects = [
      ['living',  'Modern Living Room', 'Residential Project', 'hero-living-room.jpg'],
      ['bedroom', 'Calm Bedroom',       'Residential Project', 'bedroom.jpg'],
      ['dining',  'Warm Dining Room',   'Residential Project', 'dining-room.jpg'],
      ['outdoor', 'Garden Lounge',      'Outdoor Project',     'outdoor.jpg'],
    ];
    ?>
    <div class="slider">
      <?php foreach ($projects as $i => $p) : ?>
        <div class="slide ph<?php echo $i === 0 ? ' show' : ''; ?>" data-cat="<?php echo esc_attr($p[0]); ?>">
          <img class="cover" src="<?php echo panto_img($p[3]); ?>" alt="<?php echo esc_attr($p[1]); ?>">
          <div class="slide-tag"><i></i><div><b><?php echo esc_html($p[1]); ?></b><small><?php echo esc_html($p[2]); ?></small></div></div>
        </div>
      <?php endforeach; ?>
      <div class="slider-ui">
        <span class="count">1 / <?php echo count($projects); ?></span>
        <button class="prev" aria-label="Previous"><?php echo $left; ?></button>
        <button class="next" aria-label="Next"><?php echo $arrow; ?></button>
      </div>
    </div>
  </div>
</section>

<!-- STEPS -->
<section class="steps">
  <div class="wrap">
    <h2 class="section-title">Your Home In<br>Simple Steps</h2>
    <div class="steps-grid">
      <div class="step">
        <div class="step-head"><span class="step-num">01</span></div>
        <h3>Design &amp; Planning</h3>
        <p>We help you choose the perfect style and create a plan that fits your space and needs.</p>
        <div class="img ph"><img class="cover" src="<?php echo panto_img('step-planning.jpg'); ?>" alt="Design and planning"></div>
      </div>
      <div class="step">
        <div class="step-head"><span class="step-num">02</span></div>
        <h3>Execution &amp; Reveal</h3>
        <p>Our team handles the details, from delivery to installation, so you can enjoy the result.</p>
        <div class="img ph"><img class="cover" src="<?php echo panto_img('step-reveal.jpg'); ?>" alt="Execution and reveal"></div>
      </div>
    </div>
  </div>
</section>

<!-- TESTIMONIAL -->
<section class="testimonials">
  <div class="wrap">
    <h2 class="section-title">Our Clients Love To Talk About Us</h2>
    <p class="quote">"Panto completely changed the way we live at home. Their attention to detail, beautiful designs, and professional team made the whole process so easy and enjoyable."</p>
    <div class="who">
      <div class="who-l">
        <div class="avatar"><img class="cover" src="<?php echo panto_img('avatar-sarah.jpg'); ?>" alt=""></div>
        <div><b>Sarah M.</b><small>Homeowner</small></div>
      </div>
      <div class="stars" aria-label="5 out of 5 stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
    </div>
    <div class="t-img ph"><img class="cover" src="<?php echo panto_img('testimonial.jpg'); ?>" alt=""></div>
  </div>
</section>

<?php get_footer(); ?>