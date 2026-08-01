<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$activePage      = 'home';
$pageTitle       = getSetting($pdo, 'site_title', 'Sean Gimeno — App Developer');
$pageDescription = 'Portfolio of ' . getSetting($pdo, 'full_name', 'Sean Gimeno') . ', an ICT student and app developer specializing in web applications.';
$extraCss        = ['home'];

// Skills grid data, grouped by category (skill_categories -> skills)
$skillCategories = $pdo->query('SELECT * FROM skill_categories ORDER BY sort_order')->fetchAll();
$skillsStmt = $pdo->prepare('SELECT skill_name FROM skills WHERE category_id = ? ORDER BY sort_order');

// The 3 featured projects for the home page
$featuredStmt = $pdo->prepare('SELECT * FROM projects WHERE is_featured = 1 ORDER BY sort_order LIMIT 3');
$featuredStmt->execute();
$featuredProjects = $featuredStmt->fetchAll();

require __DIR__ . '/includes/header.php';
?>

  <main>
    <section class="hero">
      <div class="hero__bg">
        <div class="hero__bg-image"></div>
        <div class="hero__bg-grid"></div>
        <div class="hero__bg-gradient"></div>
        <div class="hero__bg-gradient hero__bg-gradient--2"></div>
      </div>
      <div class="hero__inner">
        <div class="hero__badge reveal">
          <span class="hero__badge-dot"></span>
          <?php echo e(getSetting($pdo, 'hero_badge')); ?>
        </div>
        <h1 class="hero__title reveal">
          <?php echo getSetting($pdo, 'hero_title'); /* contains safe inline markup from settings */ ?>
        </h1>
        <p class="hero__description reveal">
          <?php echo e(getSetting($pdo, 'hero_description')); ?>
        </p>
        <div class="hero__actions reveal">
          <a href="projects.php" class="btn btn--primary">View My Work →</a>
          <a href="contact.php" class="btn btn--ghost">Get in Touch</a>
        </div>
        <div class="hero__stats reveal">
          <div class="hero__stat">
            <div class="hero__stat-number"><?php echo e(getSetting($pdo, 'stat_1_number')); ?></div>
            <div class="hero__stat-label"><?php echo e(getSetting($pdo, 'stat_1_label')); ?></div>
          </div>
          <div class="hero__stat">
            <div class="hero__stat-number"><?php echo e(getSetting($pdo, 'stat_2_number')); ?></div>
            <div class="hero__stat-label"><?php echo e(getSetting($pdo, 'stat_2_label')); ?></div>
          </div>
          <div class="hero__stat">
            <div class="hero__stat-number"><?php echo e(getSetting($pdo, 'stat_3_number')); ?></div>
            <div class="hero__stat-label"><?php echo getSetting($pdo, 'stat_3_label'); ?></div>
          </div>
        </div>
      </div>
    </section>

    <section class="section section--alt" id="skills">
      <div class="container">
        <div class="section__header reveal">
          <span class="section__label">Expertise</span>
          <h2 class="section__title">What I Do Best</h2>
          <p class="section__description">
            Full-cycle app development from concept to deployment, with a focus on
            performance, scalability, and user experience.
          </p>
        </div>
        <div class="skills__grid" id="skills-grid">
          <?php foreach ($skillCategories as $cat): ?>
            <?php
              $skillsStmt->execute([$cat['id']]);
              $names = array_column($skillsStmt->fetchAll(), 'skill_name');
            ?>
            <div class="skill-card reveal">
              <div class="skill-card__icon"><?php echo e($cat['icon']); ?></div>
              <div>
                <h3 class="skill-card__title"><?php echo e($cat['category_name']); ?></h3>
                <p class="skill-card__description"><?php echo e(implode(', ', $names)); ?></p>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <section class="section" id="featured">
      <div class="container">
        <div class="section__header reveal">
          <span class="section__label">Portfolio</span>
          <h2 class="section__title">Featured Projects</h2>
          <p class="section__description">
            A selection of projects that showcase my expertise across different
            platforms and technologies.
          </p>
        </div>
        <div class="featured-grid" id="featured-grid">
          <?php foreach ($featuredProjects as $p): ?>
            <?php $tags = splitList($p['tags']); ?>
            <article class="featured-card reveal">
              <div class="featured-card__image-placeholder"><?php echo e($p['icon']); ?></div>
              <div class="featured-card__body">
                <div class="featured-card__tags">
                  <?php foreach ($tags as $tag): ?>
                    <span class="tag"><?php echo e($tag); ?></span>
                  <?php endforeach; ?>
                </div>
                <h3 class="featured-card__title"><?php echo e($p['title']); ?></h3>
                <p class="featured-card__description"><?php echo e($p['summary']); ?></p>
                <a href="projects.php" class="project-card__link">View Case Study →</a>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
        <div class="text-center mt-8 reveal">
          <a href="projects.php" class="btn btn--ghost">View All Projects →</a>
        </div>
      </div>
    </section>

    <section class="section section--alt">
      <div class="container">
        <div class="cta reveal">
          <h2 class="cta__title">Let's Build Something Great</h2>
          <p class="cta__description">
            Have a project in mind? I'd love to hear about it. Let's discuss how
            we can bring your ideas to life.
          </p>
          <div class="cta__actions">
            <a href="contact.php" class="btn btn--primary">Start a Conversation →</a>
            <a href="about.php" class="btn btn--ghost">More About Me</a>
          </div>
        </div>
      </div>
    </section>
  </main>

<?php require __DIR__ . '/includes/footer.php'; ?>
