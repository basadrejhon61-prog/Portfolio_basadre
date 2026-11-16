</main>

<?php
/* ---------- Footer data: members + contact per member ---------- */
if (!function_exists('occ_team_with_roles')) {
  require_once get_template_directory() . '/team-data.php';
  require_once get_template_directory() . '/role-data.php';
}
$ft_members = function_exists('occ_team_with_roles') ? occ_team_with_roles() : [];
/* make sure each member's own contact + social links from team-data.php always come through */
$ft_raw = function_exists('occ_get_team') ? occ_get_team() : [];
foreach ($ft_members as $ft_slug => $ft_m) {
  if (isset($ft_raw[$ft_slug]) && is_array($ft_raw[$ft_slug])) {
    $ft_members[$ft_slug] = array_merge($ft_raw[$ft_slug], array_filter($ft_m, function ($v) { return $v !== '' && $v !== null; }));
  }
}
$ft_members = array_values($ft_members);

$ft_pick = function ($m, array $keys) {
  foreach ($keys as $k) {
    if (!empty($m[$k]) && is_string($m[$k])) { return trim($m[$k]); }
    if (!empty($m['social'][$k]) && is_string($m['social'][$k])) { return trim($m['social'][$k]); }
  }
  return '';
};
$ft_total = count($ft_members);

$ft_arrow_r = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>';
$ft_arrow_l = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5M11 6l-6 6 6 6"/></svg>';
$ft_i_mail  = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/></svg>';
$ft_i_phone = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8.1 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.7 2z"/></svg>';
$ft_i_pin   = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s7-6.2 7-12a7 7 0 1 0-14 0c0 5.8 7 12 7 12z"/><circle cx="12" cy="10" r="2.5"/></svg>';
$ft_socials = [
  'facebook'  => ['Facebook',  '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M22 12a10 10 0 1 0-11.56 9.88v-6.99H7.9V12h2.54V9.8c0-2.5 1.49-3.89 3.77-3.89 1.09 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56V12h2.78l-.44 2.89h-2.34v6.99A10 10 0 0 0 22 12z"/></svg>'],
  'twitter'   => ['Twitter',   '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M22.46 6c-.77.35-1.6.58-2.46.69a4.3 4.3 0 0 0 1.88-2.37 8.6 8.6 0 0 1-2.72 1.04 4.28 4.28 0 0 0-7.29 3.9A12.15 12.15 0 0 1 3.07 4.8a4.28 4.28 0 0 0 1.33 5.71 4.27 4.27 0 0 1-1.94-.54v.05a4.28 4.28 0 0 0 3.43 4.2 4.3 4.3 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.97A8.6 8.6 0 0 1 2 19.54a12.1 12.1 0 0 0 6.56 1.92c7.88 0 12.2-6.53 12.2-12.2l-.01-.56A8.7 8.7 0 0 0 22.46 6z"/></svg>'],
  'instagram' => ['Instagram', '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>'],
  'tiktok'    => ['TikTok',    '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M16.6 5.82A4.28 4.28 0 0 1 15.54 3h-3.09v12.4a2.59 2.59 0 0 1-2.59 2.5 2.6 2.6 0 0 1-2.6-2.6 2.6 2.6 0 0 1 2.6-2.6c.27 0 .53.04.77.12V9.66a5.7 5.7 0 0 0-.77-.05A5.69 5.69 0 0 0 4.17 15.3a5.69 5.69 0 0 0 5.69 5.69 5.69 5.69 0 0 0 5.69-5.69V9.01a7.35 7.35 0 0 0 4.29 1.37V7.3a4.3 4.3 0 0 1-3.24-1.48z"/></svg>'],
];
?>

<style>
/* ===== Footer: light theme (same as home page) ===== */
body footer.site-footer.occ-foot{
  background:#eceeef !important; color:#4a4e5a !important;
  border-top:1px solid rgba(0,0,0,.12) !important; padding:0 !important;
  opacity:1 !important; visibility:visible !important;
}
body footer.site-footer.occ-foot *{ opacity:1 !important; visibility:visible !important; }
body footer.site-footer.occ-foot a{ text-decoration:none; }
body footer.site-footer.occ-foot svg{ display:block; flex:none; }

body footer.site-footer.occ-foot .occ-grid{
  display:grid !important; grid-template-columns:1.1fr .6fr 1.1fr 1fr; gap:clamp(24px,4vw,56px);
  padding:clamp(22px,3vw,30px) 0 clamp(14px,2vw,18px); align-items:start;
}

/* brand: small IT logo only */
body footer.site-footer.occ-foot .occ-itlink{ display:inline-block; line-height:0; }
body footer.site-footer.occ-foot .occ-itlogo{ height:48px; width:auto; max-width:72px; object-fit:contain; background:none !important; padding:0 !important; border-radius:6px !important; filter:url(#occ-knockout); pointer-events:none; user-select:none; -webkit-user-select:none; -webkit-user-drag:none; -webkit-touch-callout:none; --cx:12%; --cy:18%; clip-path:polygon(var(--cx) 0, calc(100% - var(--cx)) 0, calc(100% - var(--cx)) var(--cy), 100% var(--cy), 100% calc(100% - var(--cy)), calc(100% - var(--cx)) calc(100% - var(--cy)), calc(100% - var(--cx)) 100%, var(--cx) 100%, var(--cx) calc(100% - var(--cy)), 0 calc(100% - var(--cy)), 0 var(--cy), var(--cx) var(--cy)); }
body footer.site-footer.occ-foot .occ-brandrow{ display:flex; align-items:center; gap:14px; }
body footer.site-footer.occ-foot .occ-logo{ display:flex; align-items:center; gap:12px; }
body footer.site-footer.occ-foot .occ-logo img{ height:44px; width:auto; max-width:52px; background:none !important; padding:0 !important; border-radius:0 !important; }
body footer.site-footer.occ-foot .occ-logo b{ display:block; font-family:var(--sans); font-size:16px; font-weight:700; line-height:1.2; color:#15161a !important; }
body footer.site-footer.occ-foot .occ-logo small{ display:block; margin-top:2px; font-family:var(--sans); font-size:11px; font-style:italic; color:#1f4fd8 !important; }
body footer.site-footer.occ-foot .occ-about{ margin:10px 0 0; max-width:34ch; font-family:var(--sans); font-size:13px; line-height:1.65; color:#4a4e5a !important; }

/* headings */
body footer.site-footer.occ-foot .occ-h{
  display:block; margin:0 0 12px; font-family:var(--sans); font-size:12px; font-weight:700;
  letter-spacing:.14em; text-transform:uppercase; color:#15161a !important;
}

/* quick links */
body footer.site-footer.occ-foot .occ-links{ display:grid; gap:7px; margin:0; padding:0; list-style:none; }
body footer.site-footer.occ-foot .occ-links a{ font-family:var(--sans); font-size:13px; color:#4a4e5a !important; transition:color .2s; }
body footer.site-footer.occ-foot .occ-links a:hover{ color:#1f4fd8 !important; }

/* ---------- contact (changes with the selected member) ---------- */
body footer.site-footer.occ-foot .occ-panes{ display:block; }
/* only the selected member's contact exists on screen: nothing hidden can sit on top of it */
body footer.site-footer.occ-foot .occ-pane{ display:none !important; }
body footer.site-footer.occ-foot .occ-pane.show{ display:block !important; animation:occIn .4s cubic-bezier(.2,.7,.2,1); }
@keyframes occIn{ from{ transform:translateX(14px); } to{ transform:none; } }
body footer.site-footer.occ-foot .occ-note{ min-height:1.2em; margin:10px 0 0; font-family:var(--sans); font-size:12px; color:#1f4fd8 !important; }
body footer.site-footer.occ-foot .occ-pane{
  grid-area:1/1; min-width:0;
  opacity:0 !important; visibility:hidden !important; pointer-events:none; transform:translateX(18px);
  transition:opacity .35s ease, transform .4s cubic-bezier(.2,.7,.2,1), visibility .35s;
}
body footer.site-footer.occ-foot .occ-pane.pre-l,
body footer.site-footer.occ-foot .occ-pane.out-l{ transform:translateX(-18px); }
body footer.site-footer.occ-foot .occ-pane.out-r{ transform:translateX(18px); }
body footer.site-footer.occ-foot .occ-pane.show{ opacity:1 !important; visibility:visible !important; pointer-events:auto; transform:none; }

body footer.site-footer.occ-foot .occ-for{ display:block; margin:0 0 10px; font-family:var(--sans); font-size:14px; font-weight:700; color:#15161a !important; }
body footer.site-footer.occ-foot .occ-list{ display:grid; gap:8px; margin:0; padding:0; list-style:none; }
body footer.site-footer.occ-foot .occ-list li{ display:flex; align-items:flex-start; gap:12px; font-family:var(--sans); font-size:13px; line-height:1.5; color:#4a4e5a !important; overflow-wrap:anywhere; }
body footer.site-footer.occ-foot .occ-list li span{ color:#4a4e5a !important; }
body footer.site-footer.occ-foot .occ-list svg{ width:16px; height:16px; margin-top:2px; color:#1f4fd8; }
body footer.site-footer.occ-foot .occ-list a{ color:#15161a !important; font-weight:500; }
body footer.site-footer.occ-foot .occ-list a:hover{ color:#1f4fd8 !important; text-decoration:underline; }

body footer.site-footer.occ-foot .occ-social{ display:flex; flex-wrap:wrap; gap:8px; margin:12px 0 0; padding:0; list-style:none; }
body footer.site-footer.occ-foot .occ-social a{
  width:34px; height:34px; border-radius:50%; display:grid; place-items:center;
  color:#15161a !important; background:rgba(0,0,0,.07); transition:background .2s,color .2s,transform .2s;
}
body footer.site-footer.occ-foot .occ-social a svg{ width:15px; height:15px; }
body footer.site-footer.occ-foot .occ-social a:hover{ background:#1f4fd8; color:#fff !important; transform:translateY(-2px); }
/* hidden members' links/buttons must never catch clicks (they are stacked under the visible one) */
body footer.site-footer.occ-foot .occ-pane:not(.show),
body footer.site-footer.occ-foot .occ-pane:not(.show) *,
body footer.site-footer.occ-foot .occ-pslide:not(.show),
body footer.site-footer.occ-foot .occ-pslide:not(.show) *{ pointer-events:none !important; }
body footer.site-footer.occ-foot .occ-pane.show,
body footer.site-footer.occ-foot .occ-pane.show a{ pointer-events:auto !important; }
body footer.site-footer.occ-foot .occ-social a{ position:relative; z-index:3; cursor:pointer !important; }
body footer.site-footer.occ-foot .occ-social .occ-nolink{
  width:34px; height:34px; border-radius:50%; display:grid; place-items:center;
  color:#15161a; background:rgba(0,0,0,.07); opacity:.3 !important; cursor:default;
}
body footer.site-footer.occ-foot .occ-social .occ-nolink svg{ width:15px; height:15px; }

/* ---------- member picker (photo + name only, no card) ---------- */
body footer.site-footer.occ-foot .occ-pick{ display:grid; grid-template-columns:auto minmax(0,1fr) auto; align-items:center; gap:10px; }
body footer.site-footer.occ-foot .occ-pstage{ display:grid; min-width:0; }
body footer.site-footer.occ-foot .occ-pslide{
  grid-area:1/1; display:flex; flex-direction:column; align-items:center; text-align:center; gap:4px;
  opacity:0 !important; visibility:hidden !important; pointer-events:none; transform:translateX(24px);
  transition:opacity .35s ease, transform .4s cubic-bezier(.2,.7,.2,1), visibility .35s;
}
body footer.site-footer.occ-foot .occ-pslide.pre-l,
body footer.site-footer.occ-foot .occ-pslide.out-l{ transform:translateX(-24px); }
body footer.site-footer.occ-foot .occ-pslide.out-r{ transform:translateX(24px); }
body footer.site-footer.occ-foot .occ-pslide.show{ opacity:1 !important; visibility:visible !important; pointer-events:auto; transform:none; }
body footer.site-footer.occ-foot .occ-avatar{
  width:84px; height:84px; margin-bottom:6px; border-radius:50%; overflow:hidden; background:#a9b6a0;
  box-shadow:0 0 0 4px #fff, 0 0 0 5px rgba(31,79,216,.35);
}
body footer.site-footer.occ-foot .occ-avatar img{ width:100%; height:100%; object-fit:cover; object-position:center top; }
body footer.site-footer.occ-foot .occ-name{ display:block; font-family:var(--sans); font-size:15px; font-weight:700; line-height:1.25; color:#15161a !important; }
body footer.site-footer.occ-foot .occ-role{ display:block; font-family:var(--sans); font-size:11px; font-weight:500; letter-spacing:.08em; text-transform:uppercase; color:#1f4fd8 !important; }
body footer.site-footer.occ-foot .occ-count{ grid-column:1 / -1; margin-top:0; text-align:center; font-family:var(--sans); font-size:12px; font-weight:600; letter-spacing:.08em; color:#4a4e5a !important; }

body footer.site-footer.occ-foot .occ-btn{
  width:40px; height:40px; border-radius:50%; display:grid; place-items:center; cursor:pointer; padding:0;
  color:#15161a; background:#fff; border:1px solid rgba(0,0,0,.14); box-shadow:0 4px 12px rgba(0,0,0,.06);
  transition:background .2s,color .2s,border-color .2s,transform .2s;
}
body footer.site-footer.occ-foot .occ-btn svg{ width:18px; height:18px; }
body footer.site-footer.occ-foot .occ-btn:hover{ background:#1f4fd8; color:#fff; border-color:#1f4fd8; transform:scale(1.07); }
body footer.site-footer.occ-foot .occ-pick.single{ grid-template-columns:minmax(0,1fr); }
body footer.site-footer.occ-foot .occ-pick.single .occ-btn,
body footer.site-footer.occ-foot .occ-pick.single .occ-count{ display:none; }

/* bottom bar */
body footer.site-footer.occ-foot .occ-bottom{
  display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;
  padding:12px 0 14px; border-top:1px solid rgba(0,0,0,.12);
  font-family:var(--sans); font-size:12px; color:#4a4e5a !important;
}
body footer.site-footer.occ-foot .occ-bottom span{ color:#4a4e5a !important; }
body footer.site-footer.occ-foot .occ-bottom a{ color:#4a4e5a !important; }
body footer.site-footer.occ-foot .occ-bottom a:hover{ color:#1f4fd8 !important; text-decoration:underline; }
body footer.site-footer.occ-foot .occ-bottom .occ-sep{ display:inline-block; width:1px; height:12px; margin:0 14px; vertical-align:middle; background:rgba(0,0,0,.2); }

@media(max-width:1000px){
  body footer.site-footer.occ-foot .occ-grid{ grid-template-columns:1fr 1fr; }
  body footer.site-footer.occ-foot .occ-brand{ grid-column:1 / -1; }
}
@media(max-width:560px){
  body footer.site-footer.occ-foot .occ-grid{ grid-template-columns:1fr; }
  body footer.site-footer.occ-foot .occ-pickcol{ order:3; }
  body footer.site-footer.occ-foot .occ-bottom{ flex-direction:column; align-items:flex-start; }
}
</style>

<footer class="site-footer occ-foot" id="contact" data-occ-ct>
  <div class="wrap">

    <div class="occ-grid">

      <!-- Brand -->
      <div class="occ-brand">
        <a class="occ-itlink" href="<?php echo esc_url(home_url('/')); ?>">
          <img class="occ-itlogo" src="<?php echo esc_url(panto_img('IT_logo.jpg')); ?>" alt="BSIT logo" decoding="async" draggable="false">
        </a>
        <p class="occ-about">Helping you build a bright future. Serving learners and communities in Opol, Misamis Oriental.</p>
      </div>

      <!-- Quick Links -->
      <div class="occ-quick">
        <span class="occ-h">Quick Links</span>
        <ul class="occ-links">
          <li><a href="<?php echo esc_url(home_url('/')); ?>">Home</a></li>
          <li><a href="#team">Profile</a></li>
          <li><a href="#collections">Memories</a></li>
          <li><a href="#projects">Projects</a></li>
          <li><a href="#contact">Process</a></li>
          <li><a href="#feedback">Feedback</a></li>
        </ul>
      </div>

      <!-- Contact Us: shows the contact of the selected member -->
      <div class="occ-contact">
        <span class="occ-h">Contact Us</span>
        <div class="occ-panes" aria-live="polite">
          <?php if ($ft_total) : foreach ($ft_members as $n => $m) :
            $email = $ft_pick($m, ['email']);
            $phone = $ft_pick($m, ['phone', 'contact', 'mobile', 'number', 'tel']);
            $addr  = $ft_pick($m, ['address']);
            $has_digits = strlen(preg_replace('/\D/', '', $phone)) >= 10 && stripos($phone, 'x') === false;
          ?>
            <div class="occ-pane<?php echo $n === 0 ? ' show' : ''; ?>" aria-hidden="<?php echo $n === 0 ? 'false' : 'true'; ?>">
              <span class="occ-for"><?php echo esc_html($m['name']); ?></span>
              <ul class="occ-list">
                <?php if ($email) : ?>
                  <li><?php echo $ft_i_mail; ?><a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a></li>
                <?php endif; ?>
                <?php if ($phone) : ?>
                  <li><?php echo $ft_i_phone; ?>
                    <?php if ($has_digits) : ?><a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $phone)); ?>"><?php echo esc_html($phone); ?></a>
                    <?php else : ?><span><?php echo esc_html($phone); ?></span><?php endif; ?>
                  </li>
                <?php endif; ?>
                <?php if ($addr) : ?>
                  <li><?php echo $ft_i_pin; ?><span><?php echo esc_html($addr); ?></span></li>
                <?php endif; ?>
                <?php if (!$email && !$phone && !$addr) : ?>
                  <li><?php echo $ft_i_mail; ?><a href="mailto:info@opolcommunitycollege.edu.ph">info@opolcommunitycollege.edu.ph</a></li>
                <?php endif; ?>
              </ul>
              <ul class="occ-social" aria-label="<?php echo esc_attr($m['name']); ?> on social media">
                <?php foreach ($ft_socials as $key => $soc) :
                  $url = $ft_pick($m, [$key]);
                  if ($url === '') { continue; }   // no link = icon is not shown at all
                  if (!preg_match('#^https?://#i', $url)) { $url = 'https://' . ltrim($url, '/'); }   // e.g. www.tiktok.com/@name
                  $has = true; ?>
                  <li>
                    <?php if ($has) : ?>
                      <a href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener noreferrer"
                         aria-label="<?php echo esc_attr($m['name'] . ' on ' . $soc[0]); ?>" title="<?php echo esc_attr($m['name'] . ' - ' . $soc[0]); ?>"><?php echo $soc[1]; ?></a>
                    <?php else : ?>
                      <span class="occ-nolink" title="<?php echo esc_attr($soc[0]); ?> (no link yet)" data-msg="<?php echo esc_attr($m['name'] . ' has no ' . $soc[0] . ' link yet.'); ?>"><?php echo $soc[1]; ?></span>
                    <?php endif; ?>
                  </li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endforeach; else : ?>
            <div class="occ-pane show">
              <span class="occ-for">Opol Community College</span>
              <ul class="occ-list">
                <li><?php echo $ft_i_pin; ?><span>Opol, Misamis Oriental, Philippines</span></li>
                <li><?php echo $ft_i_mail; ?><a href="mailto:info@opolcommunitycollege.edu.ph">info@opolcommunitycollege.edu.ph</a></li>
              </ul>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Member picker (right side): photo + name, arrows left / right -->
      <div class="occ-pickcol">
        <span class="occ-h">Team Members</span>
        <div class="occ-pick<?php echo $ft_total < 2 ? ' single' : ''; ?>" role="region" aria-roledescription="carousel" aria-label="Choose a team member">

          <button type="button" class="occ-btn occ-prev" aria-label="Previous member"><?php echo $ft_arrow_l; ?></button>

          <div class="occ-pstage">
            <?php foreach ($ft_members as $n => $m) :
              $label = '';
              if (function_exists('occ_role_meta')) {
                $rm = occ_role_meta($m['role'] ?? '');
                $label = $rm['label'] ?? '';
              }
              if ($label === '') { $label = (string) ($m['role'] ?? ''); }
            ?>
              <div class="occ-pslide<?php echo $n === 0 ? ' show' : ''; ?>" aria-hidden="<?php echo $n === 0 ? 'false' : 'true'; ?>">
                <?php if (!empty($m['image'])) : ?>
                  <span class="occ-avatar"><img src="<?php echo esc_url(panto_img($m['image'])); ?>" alt="<?php echo esc_attr($m['name']); ?>" loading="lazy" decoding="async"></span>
                <?php endif; ?>
                <span class="occ-name"><?php echo esc_html($m['name']); ?></span>
                <?php if ($label !== '') : ?><span class="occ-role"><?php echo esc_html($label); ?></span><?php endif; ?>
              </div>
            <?php endforeach; ?>
          </div>

          <button type="button" class="occ-btn occ-next" aria-label="Next member"><?php echo $ft_arrow_r; ?></button>
          <div class="occ-count">1 / <?php echo max(1, $ft_total); ?></div>
        </div>
      </div>

    </div>

    <!-- Bottom bar -->
    <div class="occ-bottom">
      <span>&copy; <?php echo date('Y'); ?> Opol Community College. All Rights Reserved.</span>
      <span>
        <a href="#">Privacy Policy</a><i class="occ-sep"></i><a href="#">Terms of Use</a>
      </span>
    </div>

  </div>

  <script>
  (function () {
    var root = document.querySelector('[data-occ-ct]');
    if (!root) return;
    var pick   = root.querySelector('.occ-pick');
    var pslide = [].slice.call(root.querySelectorAll('.occ-pslide'));
    var panes  = [].slice.call(root.querySelectorAll('.occ-pane'));
    var count  = root.querySelector('.occ-count');
    var i = 0, lock = false;

    // links: open the member's own page directly (capture phase, so no other script can swallow the click)
    root.addEventListener('click', function (e) {
      var t = e.target;
      var a = t.closest ? t.closest('.occ-pane a') : null;
      if (a) {
        var href = a.getAttribute('href');
        if (!href || href === '#') return;
        e.preventDefault(); e.stopPropagation();
        if (a.getAttribute('target') === '_blank') { window.open(href, '_blank', 'noopener'); }
        else { window.location.href = href; }
        return;
      }
      var s = t.closest ? t.closest('.occ-nolink') : null;
      if (s) {
        var note = s.closest('.occ-pane').querySelector('.occ-note');
        if (note) note.textContent = s.getAttribute('data-msg') || '';
      }
    }, true);

    if (pslide.length < 2) { if (pick) pick.classList.add('single'); return; }

    function swap(list, from, to, dir) {
      var cur = list[from], nxt = list[to];
      if (!cur || !nxt) return;
      nxt.classList.remove('out-l', 'out-r');
      nxt.style.transition = 'none';
      nxt.classList.toggle('pre-l', dir < 0);
      void nxt.offsetWidth;
      nxt.style.transition = '';
      nxt.classList.remove('pre-l');
      cur.classList.remove('show');
      cur.classList.add(dir > 0 ? 'out-l' : 'out-r');
      cur.setAttribute('aria-hidden', 'true');
      nxt.classList.add('show');
      nxt.setAttribute('aria-hidden', 'false');
    }

    function go(n, dir) {
      n = (n + pslide.length) % pslide.length;
      if (n === i || lock) return;
      lock = true;
      swap(pslide, i, n, dir);
      swap(panes, i, n, dir);
      i = n;
      if (count) count.textContent = (i + 1) + ' / ' + pslide.length;
      setTimeout(function () { lock = false; }, 420);
    }

    root.querySelector('.occ-prev').addEventListener('click', function () { go(i - 1, -1); });
    root.querySelector('.occ-next').addEventListener('click', function () { go(i + 1, 1); });

    // keyboard (left / right) while focus is on the picker
    pick.addEventListener('keydown', function (e) {
      if (e.key === 'ArrowLeft')  go(i - 1, -1);
      if (e.key === 'ArrowRight') go(i + 1, 1);
    });

    // swipe on phones (picker or contact area)
    var x0 = null;
    [pick, root.querySelector('.occ-panes')].forEach(function (el) {
      if (!el) return;
      el.addEventListener('touchstart', function (e) { x0 = e.touches[0].clientX; }, { passive: true });
      el.addEventListener('touchend', function (e) {
        if (x0 === null) return;
        var dx = e.changedTouches[0].clientX - x0;
        if (Math.abs(dx) > 50) go(dx < 0 ? i + 1 : i - 1, dx < 0 ? 1 : -1);
        x0 = null;
      });
    });
  })();
  </script>
</footer>
<?php wp_footer(); ?>
</body>
</html>