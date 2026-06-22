let projectsData = [];
let currentFilter = 'all';
let removeFocusTrap = null;

document.addEventListener('DOMContentLoaded', function () {
  loadProjects();
  initFilterButtons();
  initModalClose();
});

function loadProjects() {
  const grid = document.getElementById('projects-grid');
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
    })
    .catch(function () {
      showError(grid, 'Failed to load projects. Please try again later.');
    });
}

function renderProjects(projects) {
  const grid = document.getElementById('projects-grid');
  if (!grid) return;

  if (projects.length === 0) {
    grid.innerHTML =
      '<div class="empty-state"><p class="empty-state__title">No projects found</p><p class="text-muted">Try a different filter category.</p></div>';
    return;
  }

  grid.innerHTML = projects
    .map(function (project) {
      return (
        '<article class="project-card reveal" data-id="' +
        project.id +
        '" onclick="openProjectModal(' +
        project.id +
        ')">' +
        '<div class="project-card__image-placeholder">' +
        escapeHtml(project.icon || '📱') +
        '</div>' +
        '<div class="project-card__body">' +
        '<div class="project-card__tags">' +
        project.tags
          .map(function (t) {
            return '<span class="tag">' + escapeHtml(t) + '</span>';
          })
          .join('') +
        '</div>' +
        '<h3 class="project-card__title">' +
        escapeHtml(project.title) +
        '</h3>' +
        '<p class="project-card__description">' +
        escapeHtml(project.summary) +
        '</p>' +
        '<div class="project-card__footer">' +
        '<span class="project-card__link">View Case Study →</span>' +
        '</div>' +
        '</div>' +
        '</article>'
      );
    })
    .join('');
}

function initFilterButtons() {
  document.querySelectorAll('.filter-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
      document.querySelectorAll('.filter-btn').forEach(function (b) {
        b.classList.remove('active');
      });
      btn.classList.add('active');
      currentFilter = btn.getAttribute('data-filter');
      filterProjects();
    });
  });
}

function filterProjects() {
  var filtered = projectsData;
  if (currentFilter !== 'all') {
    filtered = projectsData.filter(function (p) {
      return p.tags.some(function (t) {
        return t.toLowerCase() === currentFilter;
      });
    });
  }
  renderProjects(filtered);
  initRevealAnimations();
}

function openProjectModal(id) {
  var project = projectsData.find(function (p) {
    return p.id === id;
  });
  if (!project) return;

  var overlay = document.getElementById('modal-overlay');
  var title = document.getElementById('modal-title');
  var body = document.getElementById('modal-body');
  if (!overlay || !title || !body) return;

  title.textContent = project.title;

  body.innerHTML =
    '<div class="modal__image-placeholder">' +
    escapeHtml(project.icon || '📱') +
    '</div>' +
    '<p class="modal__description">' +
    escapeHtml(project.description) +
    '</p>' +
    '<h4 class="modal__section-title">Technologies Used</h4>' +
    '<div class="modal__tech-list">' +
    project.tech
      .map(function (t) {
        return '<span class="tag">' + escapeHtml(t) + '</span>';
      })
      .join('') +
    '</div>' +
    (project.features
      ? '<h4 class="modal__section-title">Key Features</h4>' +
        '<ul class="modal__features" style="padding-left:1.25rem;margin-bottom:var(--space-4)">' +
        project.features
          .map(function (f) {
            return '<li style="list-style:disc;margin-bottom:var(--space-2);color:var(--color-fg-muted)">' + escapeHtml(f) + '</li>';
          })
          .join('') +
        '</ul>'
      : '') +
    (project.link
      ? '<div class="modal__footer"><a href="' +
        escapeHtml(project.link) +
        '" target="_blank" rel="noopener noreferrer" class="btn btn--primary">View Project →</a></div>'
      : '');

  overlay.classList.add('open');
  document.body.classList.add('modal-open');

  removeFocusTrap = trapFocus(overlay);
}

function initModalClose() {
  var overlay = document.getElementById('modal-overlay');
  if (!overlay) return;

  function closeModal() {
    overlay.classList.remove('open');
    document.body.classList.remove('modal-open');
    if (removeFocusTrap) {
      removeFocusTrap();
      removeFocusTrap = null;
    }
  }

  document.getElementById('modal-close').addEventListener('click', closeModal);

  overlay.addEventListener('click', function (e) {
    if (e.target === overlay) closeModal();
  });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && overlay.classList.contains('open')) {
      closeModal();
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
