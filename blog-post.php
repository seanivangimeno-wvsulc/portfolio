<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$activePage = 'blog';
$fullName   = getSetting($pdo, 'full_name', 'Sean Gimeno');

// Always treat the id from the URL as an integer and use a prepared
// statement - never concatenate $_GET values into SQL directly.
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$post = null;
if ($id) {
    $stmt = $pdo->prepare('SELECT * FROM blog_posts WHERE id = ?');
    $stmt->execute([$id]);
    $post = $stmt->fetch();
}

$pageTitle       = $post ? $post['title'] . ' — ' . $fullName : 'Article Not Found — ' . $fullName;
$pageDescription = $post ? mb_substr($post['excerpt'], 0, 160) : 'Read full articles on ' . $fullName . '\'s portfolio blog.';
$extraCss        = [];

require __DIR__ . '/includes/header.php';
?>
  <style>
    .post-container { max-width: 800px; margin: 0 auto; padding: var(--space-12) var(--container-padding); }
    .post-header { text-align: center; margin-bottom: var(--space-8); }
    .post-meta { display: flex; align-items: center; justify-content: center; gap: var(--space-3); font-size: var(--fs-sm); color: var(--color-fg-muted); margin-bottom: var(--space-4); }
    .post-category { color: var(--color-accent); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
    .post-title { font-size: var(--fs-4xl); font-weight: 800; line-height: 1.2; margin-bottom: var(--space-4); letter-spacing: -0.02em; }
    .post-icon { font-size: 4rem; margin-bottom: var(--space-4); display: inline-block; }
    .post-content { font-size: var(--fs-lg); line-height: 1.8; color: var(--color-fg); }
    .post-content p { margin-bottom: var(--space-6); }
    .post-back { margin-top: var(--space-10); text-align: center; border-top: 1px solid var(--color-border); padding-top: var(--space-8); }
    .reading-progress { position: fixed; top: 0; left: 0; height: 3px; background: var(--color-accent); z-index: 1000; width: 0%; transition: width 0.1s ease-out; }
  </style>

  <div class="reading-progress" id="reading-progress"></div>

  <main style="padding-top: var(--header-height)">
    <article class="post-container">
      <?php if (!$post): ?>
        <p class="error-state"><?php echo $id ? 'Article not found.' : 'Invalid article link.'; ?></p>
      <?php else: ?>
        <?php $readingTime = estimateReadingTime($post['content']); ?>
        <div class="post-header reveal">
          <div class="post-icon"><?php echo e($post['icon'] ?: '📝'); ?></div>
          <div class="post-meta">
            <span class="post-category"><?php echo e($post['category']); ?></span>
            <span>·</span>
            <span><?php echo e(formatDate($post['post_date'])); ?></span>
            <span>·</span>
            <span><?php echo $readingTime; ?> min read</span>
          </div>
          <h1 class="post-title"><?php echo e($post['title']); ?></h1>
        </div>
        <div class="post-content reveal">
          <p><?php echo e($post['content']); ?></p>
        </div>
      <?php endif; ?>
      <div class="post-back">
        <a href="blog.php" class="btn btn--ghost">← Back to Blog</a>
      </div>
    </article>
  </main>

<?php require __DIR__ . '/includes/footer.php'; ?>
