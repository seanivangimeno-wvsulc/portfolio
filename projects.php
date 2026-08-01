<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$activePage      = 'projects';
$fullName        = getSetting($pdo, 'full_name', 'Sean Gimeno');
$pageTitle       = 'Projects — ' . $fullName;
$pageDescription = 'Explore a portfolio of app development projects by ' . $fullName . '.';
$extraCss        = ['projects'];
$extraScripts    = ['projects'];

$projects = $pdo->query('SELECT * FROM projects ORDER BY sort_order')->fetchAll();

// Prepare a clean array (with tags/tech/features split out) to hand to JS
// for the filter buttons and the "Details" modal, so projects.js does not
// need to fetch anything - the data already came from PDO/MySQL server-side.
$projectsForJs = array_map(function ($p) {
    return [
        'id'          => (int) $p['id'],
        'title'       => $p['title'],
        'summary'     => $p['summary'],
        'description' => $p['description'],
        'icon'        => $p['icon'],
        'tags'        => splitList($p['tags']),
        'tech'        => splitList($p['tech']),
        'features'    => splitLines($p['features']),
        'github'      => $p['github_url'],
        'live'        => $p['live_url'],
    ];
}, $projects);

require __DIR__ . '/includes/header.php';
?>

  <main>
    <section class="page-header">
      <div class="container">
        <span class="section__label reveal">Portfolio</span>
        <h1 class="page-header__title reveal">Projects</h1>
        <p class="page-header__subtitle reveal">
          A collection of apps and platforms I've designed and built from the ground up.
          Each project represents a unique challenge and solution.
        </p>
      </div>
    </section>

    <section class="section" style="padding-top:0">
      <div class="container">
        <div class="filter-bar reveal" id="filter-bar">
          <button class="filter-btn active" data-filter="all">All</button>
          <button class="filter-btn" data-filter="mobile">Mobile</button>
          <button class="filter-btn" data-filter="web">Web</button>
          <button class="filter-btn" data-filter="fullstack">Full Stack</button>
          <button class="filter-btn" data-filter="ai">AI / ML</button>
          <button class="filter-btn" data-filter="game">Game</button>
        </div>
        <div class="projects-grid" id="projects-grid">
        </div>
      </div>
    </section>
  </main>

  <div class="modal-overlay" id="modal-overlay">
    <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modal-title">
      <div class="modal__header">
        <h2 class="modal__title" id="modal-title"></h2>
        <button class="btn-close" id="modal-close" aria-label="Close modal">&times;</button>
      </div>
      <div class="modal__body" id="modal-body"></div>
    </div>
  </div>

  <script>
    /* Data fetched server-side via PDO/MySQL in projects.php,
       passed to js/projects.js so there is no more hardcoded JSON file. */
    window.PROJECTS_DATA = <?php echo json_encode($projectsForJs, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>;
  </script>

<?php require __DIR__ . '/includes/footer.php'; ?>
