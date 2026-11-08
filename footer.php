</main>
<style>
/* Cream footer (naka-embed aron dili madepende sa cache sa style.css) */
.site-footer{background:#f6f4ef !important;color:#3d4540 !important;padding:0 !important;border-top:1px solid #e2dfd6 !important}
.site-footer a{color:inherit;text-decoration:none}
.site-footer svg{display:block;flex:none}

/* 4 ka columns */
.site-footer .foot-grid{display:grid !important;grid-template-columns:1.5fr 1fr 1fr 1.4fr;gap:40px;padding:40px 0 36px}
.site-footer .foot-brand .logo{display:flex;align-items:center;gap:12px}
.site-footer .foot-brand .logo img{height:44px;width:auto;max-width:52px;background:none;padding:0;border-radius:0}
.site-footer .logo-text b{font-size:17px;font-weight:700;line-height:1.2;color:#0b1f3a !important;text-transform:none;letter-spacing:0}
.site-footer .logo-text small{display:block;font-size:11px;font-style:italic;color:#1d4ed8 !important;margin-top:2px}
.site-footer .foot-about{font-size:13px;line-height:1.6;margin:18px 0 0 !important;max-width:260px;color:#3d4540 !important}
.site-footer .foot-social{display:flex;gap:14px;margin-top:22px}
.site-footer .foot-social a{width:34px;height:34px;border-radius:50%;background:#0b1f3a;color:#fff !important;display:grid;place-items:center;transition:transform .2s,background .2s}
.site-footer .foot-social a:hover{transform:translateY(-2px);background:#1d4ed8}
.site-footer .foot-social svg{width:16px;height:16px}
.site-footer h4{font-size:15px;font-weight:600;color:#0b1f3a !important;margin:0 0 18px}
.site-footer ul{list-style:none;display:grid;gap:12px;margin:0;padding:0}
.site-footer li{font-size:13px;margin:0 !important;color:#3d4540}
.site-footer li a:hover{color:#1d4ed8;text-decoration:underline}
.site-footer .contact-list li{display:flex;gap:12px;align-items:flex-start;line-height:1.5}
.site-footer .contact-list svg{width:16px;height:16px;margin-top:2px;color:#0b1f3a}

/* Ubos nga bar */
.site-footer .copy{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;padding:20px 0 24px;margin:0 !important;border-top:1px solid #e2dfd6 !important;font-size:12px;color:#5d655f !important}
.site-footer .copy-links{display:flex;align-items:center;gap:18px}
.site-footer .copy-links i{width:1px;height:14px;background:#c9c5b8}
.site-footer .copy-links a:hover{color:#1d4ed8;text-decoration:underline}

@media(max-width:900px){
  .site-footer .foot-grid{grid-template-columns:1fr 1fr}
  .site-footer .foot-brand{grid-column:1 / -1}
}
@media(max-width:560px){
  .site-footer .foot-grid{grid-template-columns:1fr}
  .site-footer .copy{flex-direction:column;align-items:flex-start}
}
</style>

<footer class="site-footer" id="contact">

  <div class="wrap">

    <div class="foot-grid">

      <!-- Logo + tagline + social -->
      <div class="foot-brand">
        <a class="logo" href="<?php echo esc_url(home_url('/')); ?>">
          <img src="<?php echo panto_img('Occ_Logo.png'); ?>" alt="Opol Community College logo">
          <span class="logo-text">
            <b>Opol Community College</b>
            <small>Empowering Minds, Transforming Future</small>
          </span>
        </a>
        <p class="foot-about">Helping you build a bright future. Serving learners and communities in Opol, Misamis Oriental.</p>
        <div class="foot-social">
          <a href="https://www.facebook.com/share/1EowCJmZnn/" target="_blank" rel="noopener" aria-label="Facebook">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M14 8V6.5c0-.8.2-1.2 1.3-1.2H17V2.2C16.7 2.1 15.7 2 14.6 2 12.2 2 10.5 3.5 10.5 6.2V8H8v3.4h2.5V22H14V11.4h2.7L17 8h-3z"/></svg>
          </a>
          <a href="https://www.instagram.com/" target="_blank" rel="noopener" aria-label="Instagram">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>
          </a>
          <a href="https://twitter.com/" target="_blank" rel="noopener" aria-label="Twitter">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
          </a>
        </div>
      </div>

      <!-- Quick Links -->
      <div class="foot-col">
        <h4>Quick Links</h4>
        <ul>
          <li><a href="<?php echo esc_url(home_url('/')); ?>">Home</a></li>
          <li><a href="#about">Profile</a></li>
          <li><a href="#collections">Collection</a></li>
          <li><a href="#inspiration">Inspiration</a></li>
          <li><a href="#contact">Contact Us</a></li>
        </ul>
      </div>

      <!-- Resources -->
      <div class="foot-col">
        <h4>Resources</h4>
        <ul>
          <li><a href="#">Admissions</a></li>
          <li><a href="#">Academic Calendar</a></li>
          <li><a href="#">Student Handbook</a></li>
          <li><a href="#">Announcements</a></li>
          <li><a href="#">News &amp; Events</a></li>
        </ul>
      </div>

      <!-- Contact Us -->
      <div class="foot-col">
        <h4>Contact Us</h4>
        <ul class="contact-list">
          <li>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s7-6.2 7-12a7 7 0 1 0-14 0c0 5.8 7 12 7 12z"/><circle cx="12" cy="10" r="2.5"/></svg>
            <span>Opol, Misamis Oriental,<br>Philippines</span>
          </li>
          <li>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8.1 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.7 2z"/></svg>
            <span>(+63) 900 000 0000</span>
          </li>
          <li>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/></svg>
            <span>info@opolcommunitycollege.edu.ph</span>
          </li>
          <li>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
            <span>Mon &ndash; Fri: 8AM &ndash; 5PM</span>
          </li>
        </ul>
      </div>

    </div>

    <!-- Bottom bar -->
    <div class="copy">
      <span>&copy; <?php echo date('Y'); ?> Opol Community College. All Rights Reserved.</span>
      <div class="copy-links">
        <a href="#">Privacy Policy</a>
        <i></i>
        <a href="#">Terms of Use</a>
      </div>
    </div>

  </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>