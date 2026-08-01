<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$activePage      = 'blog';
$fullName        = getSetting($pdo, 'full_name', 'Sean Gimeno');
$pageTitle       = 'Blog — ' . $fullName;
$pageDescription = 'Technical blog about app development, architecture, and engineering by ' . $fullName . '.';
$extraCss        = ['blog'];
$extraScripts    = ['blog'];

$posts = $pdo->query('SELECT * FROM blog_posts ORDER BY post_date DESC')->fetchAll();

$postsForJs = array_map(function ($p) {
    return [
        'id'       => (int) $p['id'],
        'title'    => $p['title'],
        'excerpt'  => $p['excerpt'],
        'category' => $p['category'],
        'tags'     => splitList($p['tags']),
        'date'     => $p['post_date'],
        'icon'     => $p['icon'],
        'url'      => 'blog-post.php?id=' . (int) $p['id'],
        'content'  => $p['content'],
    ];
}, $posts);

require __DIR__ . '/includes/header.php';
?>

  <main>
    <section class="page-header">
      <div class="container">
        <span class="section__label reveal">Writing</span>
        <h1 class="page-header__title reveal">Blog</h1>
        <p class="page-header__subtitle reveal">
          Thoughts on app development, system architecture, engineering practices,
          and lessons learned from building products.
        </p>
      </div>
    </section>

    <section class="section" style="padding-top:0">
      <div class="container">
        <div class="blog-search reveal">
          <span class="blog-search__icon">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
          </span>
          <input type="text" class="input" id="blog-search-input" placeholder="Search articles..." aria-label="Search blog posts">
        </div>
        <div class="blog-categories reveal" id="blog-categories"></div>
        <div class="blog-grid" id="blog-grid"></div>
      </div>
    </section>
  </main>

  <script>
    /* Posts fetched server-side via PDO/MySQL in blog.php. */
    window.POSTS_DATA = <?php echo json_encode($postsForJs, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>;
  </script>

<?php require __DIR__ . '/includes/footer.php'; ?>
