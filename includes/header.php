<?php
/**
 * includes/header.php
 * --------------------
 * Shared <head> + top navigation for every public page.
 *
 * Expected variables set by the page BEFORE including this file:
 *   $pageTitle        (string)  e.g. "Projects — Sean Gimeno"
 *   $pageDescription   (string)  meta description
 *   $activePage        (string)  one of: home, projects, blog, about, contact
 *   $extraCss           (array)   extra css files under css/pages/, optional
 *   $pdo                (PDO)     must already be created (config/database.php)
 */

$activePage      = $activePage ?? '';
$extraCss        = $extraCss ?? [];
$siteName        = getSetting($pdo, 'site_name', 'Sean');

function navClass(string $page, string $active): string
{
    return 'nav__link' . ($page === $active ? ' nav__link--active' : '');
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo e($pageTitle ?? $siteName); ?></title>
  <meta name="description" content="<?php echo e($pageDescription ?? ''); ?>">
  <link rel="stylesheet" href="css/variables.css">
  <link rel="stylesheet" href="css/reset.css">
  <link rel="stylesheet" href="css/components.css">
  <link rel="stylesheet" href="css/layout.css">
  <link rel="stylesheet" href="css/animations.css">
  <link rel="stylesheet" href="css/pages/nav-footer.css">
  <?php foreach ($extraCss as $css): ?>
  <link rel="stylesheet" href="css/pages/<?php echo e($css); ?>.css">
  <?php endforeach; ?>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
  <header class="header" id="header">
    <div class="header__inner">
      <a href="index.php" class="header__logo"><?php echo e($siteName); ?><span>.</span></a>
      <button class="header__menu-btn" id="menu-btn" aria-label="Toggle menu" aria-expanded="false">
        <span></span>
      </button>
      <nav class="nav" id="nav" role="navigation" aria-label="Main navigation">
        <ul class="nav__list">
          <li><a href="index.php" class="<?php echo navClass('home', $activePage); ?>">Home</a></li>
          <li><a href="projects.php" class="<?php echo navClass('projects', $activePage); ?>">Projects</a></li>
          <li><a href="blog.php" class="<?php echo navClass('blog', $activePage); ?>">Blog</a></li>
          <li><a href="about.php" class="<?php echo navClass('about', $activePage); ?>">About</a></li>
          <li><a href="contact.php" class="<?php echo navClass('contact', $activePage); ?>">Contact</a></li>
        </ul>
      </nav>
      <div class="nav__actions">
        <button id="theme-toggle" class="btn btn--icon magnetic" aria-label="Switch to light mode">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="5" />
            <path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42" />
          </svg>
        </button>
      </div>
    </div>
  </header>
