let postsData = [];
let currentCategory = 'all';
let currentSearch = '';

document.addEventListener('DOMContentLoaded', function () {
  loadPosts();
  initBlogCategories();
  initBlogSearch();
  initReadingProgress();
});

function loadPosts() {
  const grid = document.getElementById('blog-grid');
  if (!grid) return;

  showLoading(grid);

  fetch('data/posts.json')
    .then(function (res) {
      if (!res.ok) throw new Error('Failed to load posts');
      return res.json();
    })
    .then(function (data) {
      postsData = data.map(function (post) {
        var words = post.content ? post.content.trim().split(/\s+/).length : 0;
        return {
          ...post,
          readingTime: Math.max(1, Math.ceil(words / 200)),
        };
      });
      renderPosts(postsData);
      populateCategories(postsData);
    })
    .catch(function () {
      showError(grid, 'Failed to load blog posts. Please try again later.');
    });
}

function renderPosts(posts) {
  const grid = document.getElementById('blog-grid');
  if (!grid) return;

  if (posts.length === 0) {
    grid.innerHTML =
      '<div class="empty-state col-span-full"><p class="empty-state__title">No posts found</p><p class="text-muted">Try adjusting your search or filter.</p></div>';
    return;
  }

  grid.innerHTML = posts
    .map(function (post) {
      return (
        '<article class="blog-card reveal">' +
        '<div class="blog-card__image-placeholder">' +
        escapeHtml(post.icon || '📝') +
        '</div>' +
        '<div class="blog-card__body">' +
        '<div class="blog-card__meta">' +
        '<span class="blog-card__category">' +
        escapeHtml(post.category) +
        '</span>' +
        '<span class="blog-card__read-time">' +
        formatDate(post.date) +
        ' · ' +
        post.readingTime +
        ' min read</span>' +
        '</div>' +
        '<h3 class="blog-card__title">' +
        escapeHtml(post.title) +
        '</h3>' +
        '<p class="blog-card__excerpt">' +
        escapeHtml(post.excerpt) +
        '</p>' +
        '<div class="blog-card__footer">' +
        '<a href="' +
        escapeHtml(post.url) +
        '" class="blog-card__link">Read Article →</a>' +
        '</div>' +
        '</div>' +
        '</article>'
      );
    })
    .join('');

  posts.forEach(function () {
    if (window.initRevealAnimations) window.initRevealAnimations();
  });
}

function populateCategories(posts) {
  var categories = ['all'];
  posts.forEach(function (post) {
    if (categories.indexOf(post.category) === -1) {
      categories.push(post.category);
    }
  });

  var container = document.getElementById('blog-categories');
  if (!container) return;

  container.innerHTML = categories
    .map(function (cat) {
      return (
        '<button class="blog-category-btn' +
        (cat === currentCategory ? ' active' : '') +
        '" data-category="' +
        cat +
        '">' +
        cat.charAt(0).toUpperCase() + cat.slice(1) +
        '</button>'
      );
    })
    .join('');

  container.querySelectorAll('.blog-category-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
      container.querySelectorAll('.blog-category-btn').forEach(function (b) {
        b.classList.remove('active');
      });
      btn.classList.add('active');
      currentCategory = btn.getAttribute('data-category');
      filterAndSearchPosts();
    });
  });
}

function initBlogCategories() {

}

function initBlogSearch() {
  var input = document.getElementById('blog-search-input');
  if (!input) return;

  input.addEventListener(
    'input',
    debounce(function () {
      currentSearch = input.value.toLowerCase().trim();
      filterAndSearchPosts();
    }, 300)
  );
}

function filterAndSearchPosts() {
  var filtered = postsData;

  if (currentCategory !== 'all') {
    filtered = filtered.filter(function (p) {
      return p.category.toLowerCase() === currentCategory;
    });
  }

  if (currentSearch) {
    filtered = filtered.filter(function (p) {
      return (
        p.title.toLowerCase().indexOf(currentSearch) !== -1 ||
        p.excerpt.toLowerCase().indexOf(currentSearch) !== -1 ||
        p.tags.some(function (t) {
          return t.toLowerCase().indexOf(currentSearch) !== -1;
        })
      );
    });
  }

  renderPosts(filtered);
}

function initReadingProgress() {
  var bar = document.getElementById('reading-progress');
  if (!bar) return;

  window.addEventListener('scroll', function () {
    var scrollTop = window.scrollY;
    var docHeight = document.documentElement.scrollHeight - window.innerHeight;
    if (docHeight > 0) {
      var progress = (scrollTop / docHeight) * 100;
      bar.style.width = progress + '%';
    }
  });
}

function showLoading(container) {
  container.innerHTML =
    '<div class="loading-state"><div class="spinner spinner--lg"></div><p>Loading posts...</p></div>';
}

function showError(container, message) {
  container.innerHTML =
    '<div class="error-state"><p style="font-size:var(--fs-lg);font-weight:600;margin-bottom:var(--space-2)">Oops!</p><p>' +
    message +
    '</p></div>';
}
