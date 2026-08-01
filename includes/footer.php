<?php
/**
 * includes/footer.php
 * ---------------------
 * Shared footer for every public page. Pulls its text from
 * the site_settings table instead of being hardcoded.
 *
 * $extraScripts (array) - optional extra js/*.js files a page needs
 */
$extraScripts = $extraScripts ?? [];

$siteName    = getSetting($pdo, 'site_name', 'Sean');
$fullName    = getSetting($pdo, 'full_name', 'Sean Gimeno');
$footerAbout = getSetting($pdo, 'footer_about', '');
$email       = getSetting($pdo, 'email', '');
$location    = getSetting($pdo, 'location', '');
$githubUrl   = getSetting($pdo, 'github_url', '#');
$linkedinUrl = getSetting($pdo, 'linkedin_url', '#');
$twitterUrl  = getSetting($pdo, 'twitter_url', '#');
$year        = getSetting($pdo, 'copyright_year', date('Y'));
?>
  <footer class="footer">
    <div class="footer__inner">
      <div class="footer__grid">
        <div>
          <div class="footer__brand"><?php echo e($siteName); ?><span>.</span></div>
          <p class="footer__description"><?php echo e($footerAbout); ?></p>
          <div class="footer__social">
            <a href="<?php echo e($githubUrl); ?>" class="footer__social-link" aria-label="GitHub" target="_blank" rel="noopener noreferrer">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/></svg>
            </a>
            <a href="<?php echo e($linkedinUrl); ?>" class="footer__social-link" aria-label="LinkedIn" target="_blank" rel="noopener noreferrer">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
            </a>
            <a href="mailto:<?php echo e($email); ?>" class="footer__social-link" aria-label="Email" target="_blank" rel="noopener noreferrer">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
            </a>
          </div>
        </div>
        <div>
          <h4 class="footer__heading">Pages</h4>
          <ul class="footer__links">
            <li><a href="index.php" class="footer__link">Home</a></li>
            <li><a href="projects.php" class="footer__link">Projects</a></li>
            <li><a href="blog.php" class="footer__link">Blog</a></li>
            <li><a href="about.php" class="footer__link">About</a></li>
            <li><a href="contact.php" class="footer__link">Contact</a></li>
          </ul>
        </div>
        <div>
          <h4 class="footer__heading">Services</h4>
          <ul class="footer__links">
            <li><a href="#" class="footer__link">Web Apps</a></li>
            <li><a href="#" class="footer__link">Mobile Apps</a></li>
            <li><a href="#" class="footer__link">API Development</a></li>
            <li><a href="#" class="footer__link">Consulting</a></li>
          </ul>
        </div>
        <div>
          <h4 class="footer__heading">Contact</h4>
          <ul class="footer__links">
            <li><a href="mailto:<?php echo e($email); ?>" class="footer__link"><?php echo e($email); ?></a></li>
            <li><span class="footer__link"><?php echo e($location); ?></span></li>
          </ul>
        </div>
      </div>
      <div class="footer__bottom">
        <span>&copy; <?php echo e($year); ?> <?php echo e($fullName); ?>. All rights reserved.</span>
        <span>Built with PHP, JavaScript &amp; MySQL (PDO)</span>
      </div>
    </div>
  </footer>

  <script src="js/utils.js"></script>
  <script src="js/navigation.js"></script>
  <?php foreach ($extraScripts as $script): ?>
  <script src="js/<?php echo e($script); ?>.js"></script>
  <?php endforeach; ?>
</body>
</html>
