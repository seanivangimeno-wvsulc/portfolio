<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$activePage      = 'about';
$fullName        = getSetting($pdo, 'full_name', 'Sean Gimeno');
$pageTitle       = 'About — ' . $fullName;
$pageDescription = 'Learn more about ' . $fullName . ', an ICT student and app developer.';
$extraCss        = ['about'];

$experience = $pdo->query('SELECT * FROM experience ORDER BY sort_order')->fetchAll();
$values     = $pdo->query('SELECT * FROM core_values ORDER BY sort_order')->fetchAll();

require __DIR__ . '/includes/header.php';
?>

  <main>
    <section class="about-hero">
      <div class="about-hero__inner">
        <div class="about-hero__avatar reveal reveal--left">
          <img src="assets/images/profile.jpeg" alt="<?php echo e($fullName); ?>" style="width:100%;height:100%;object-fit:cover;border-radius:var(--radius-xl);">
        </div>
        <div class="about-hero__text reveal reveal--right">
          <h1 class="about-hero__title">Hi, I'm <span class="text-gradient"><?php echo e(getSetting($pdo, 'site_name', 'Sean')); ?></span></h1>
          <p class="about-hero__description"><?php echo e(getSetting($pdo, 'about_intro_1')); ?></p>
          <p class="about-hero__description" style="margin-top: var(--space-4);"><?php echo e(getSetting($pdo, 'about_intro_2')); ?></p>
          <div style="margin-top: var(--space-6); display: flex; gap: var(--space-4);">
            <a href="contact.php" class="btn btn--primary">Let's Work Together</a>
            <a href="projects.php" class="btn btn--ghost">View Projects</a>
          </div>
        </div>
      </div>
    </section>

    <section class="section section--alt">
      <div class="container">
        <div class="section__header reveal">
          <span class="section__label">Experience</span>
          <h2 class="section__title">Where I've Worked</h2>
        </div>
        <div class="timeline">
          <?php foreach ($experience as $item): ?>
          <div class="timeline__item reveal">
            <div class="timeline__dot"></div>
            <div class="timeline__date"><?php echo e($item['date_range']); ?></div>
            <h3 class="timeline__title"><?php echo e($item['role_title']); ?></h3>
            <div class="timeline__company"><?php echo e($item['organization']); ?></div>
            <p class="timeline__description"><?php echo e($item['description']); ?></p>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <section class="section">
      <div class="container">
        <div class="section__header reveal">
          <span class="section__label">Values</span>
          <h2 class="section__title">How I Work</h2>
        </div>
        <div class="values__grid">
          <?php foreach ($values as $v): ?>
          <div class="value-card reveal">
            <div class="value-card__icon"><?php echo e($v['icon']); ?></div>
            <h3 class="value-card__title"><?php echo e($v['title']); ?></h3>
            <p class="value-card__description"><?php echo e($v['description']); ?></p>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
  </main>

<?php require __DIR__ . '/includes/footer.php'; ?>
