var projectsData = [];
var currentFilter = 'all';
var currentSearch = '';

document.addEventListener('DOMContentLoaded', function () {
  loadProjects();
  initSearch();
  initReadingProgress();
});

function loadProjects() {
  var grid = document.getElementById('blog-grid');
  if (!grid) return;

  showLoading(grid);

  fetch('data/projects.json')
    .then(function (res) {
      if (!res.ok) throw new Error('Failed to load projects');
      return res.json();
    })
    .then(function (data) {
      projectsData = data;
      renderProjects(data);
      populateFilters(data);
      if (window.initRevealAnimations) {
        window.initRevealAnimations();
      }
    })
    .catch(function () {
      showError(grid, 'Failed to load projects. Please try again later.');
    });
}

function renderProjects(projects) {
  var grid = document.getElementById('blog-grid');
  if (!grid) return;

  if (projects.length === 0) {
    grid.innerHTML =
      '<div class="empty-state col-span-full"><p class="empty-state__title">No projects found</p><p class="text-muted">Try adjusting your search or filter.</p></div>';
    return;
  }

  grid.innerHTML = projects
    .map(function (project) {
      var liveUrl = project.live || project.github;
      return (
        '<article class="blog-card reveal">' +
        '<a href="' + liveUrl + '" target="_blank" rel="noopener noreferrer" style="text-decoration:none;color:inherit">' +
        '<div class="blog-card__image-placeholder">' +
        escapeHtml(project.icon || '📱') +
        '</div>' +
        '<div class="blog-card__body">' +
        '<div class="blog-card__meta">' +
        '<span class="blog-card__category">' +
        escapeHtml(project.tags.slice(0, 2).join(', ')) +
        '</span>' +
        '</div>' +
        '<h3 class="blog-card__title">' +
        escapeHtml(project.title) +
        '</h3>' +
        '<p class="blog-card__excerpt">' +
        escapeHtml(project.summary) +
        '</p>' +
        '<div class="blog-card__footer">' +
        '<span class="blog-card__link">' +
        (project.live ? 'View Live Demo →' : 'View Source →') +
        '</span>' +
        '</div>' +
        '</div>' +
        '</a>' +
        '</article>'
      );
    })
    .join('');

  if (window.initRevealAnimations) window.initRevealAnimations();
}

function populateFilters(projects) {
  var tagSet = {};
  projects.forEach(function (p) {
    p.tags.forEach(function (t) {
      tagSet[t] = true;
    });
  });
  var tags = Object.keys(tagSet).sort();
  var allTags = ['all'].concat(tags);

  var container = document.getElementById('blog-categories');
  if (!container) return;

  container.innerHTML = allTags
    .map(function (tag) {
      return (
        '<button class="blog-category-btn' +
        (tag === currentFilter ? ' active' : '') +
        '" data-filter="' + tag + '">' +
        tag.charAt(0).toUpperCase() + tag.slice(1) +
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
      currentFilter = btn.getAttribute('data-filter');
      searchAndFilter();
    });
  });
}

function initSearch() {
  var input = document.getElementById('blog-search-input');
  if (!input) return;

  input.addEventListener(
    'input',
    debounce(function () {
      currentSearch = input.value.toLowerCase().trim();
      searchAndFilter();
    }, 300)
  );
}

function searchAndFilter() {
  var filtered = projectsData;

  if (currentFilter !== 'all') {
    filtered = filtered.filter(function (p) {
      return p.tags.some(function (t) {
        return t.toLowerCase() === currentFilter;
      });
    });
  }

  if (currentSearch) {
    filtered = filtered.filter(function (p) {
      return (
        p.title.toLowerCase().indexOf(currentSearch) !== -1 ||
        p.summary.toLowerCase().indexOf(currentSearch) !== -1 ||
        p.tags.some(function (t) {
          return t.toLowerCase().indexOf(currentSearch) !== -1;
        })
      );
    });
  }

  renderProjects(filtered);
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
    '<div class="loading-state"><div class="spinner spinner--lg"></div><p>Loading projects...</p></div>';
}

function showError(container, message) {
  container.innerHTML =
    '<div class="error-state"><p style="font-size:var(--fs-lg);font-weight:600;margin-bottom:var(--space-2)">Oops!</p><p>' +
    message +
    '</p></div>';
}