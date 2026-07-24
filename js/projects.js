var projectsData = [];
var currentFilter = 'all';
var removeFocusTrap = null;

document.addEventListener('DOMContentLoaded', function () {
  loadProjects();
  initFilterButtons();
  initModalClose();
});

function loadProjects() {
  var grid = document.getElementById('projects-grid');
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
      if (window.initRevealAnimations) {
        window.initRevealAnimations();
      }
    })
    .catch(function () {
      showError(grid, 'Failed to load projects. Please try again later.');
    });
}

function getProjectUrl(project) {
  if (project.live) return project.live;
  return project.github;
}

function renderProjects(projects) {
  var grid = document.getElementById('projects-grid');
  if (!grid) return;

  if (projects.length === 0) {
    grid.innerHTML =
      '<div class="empty-state"><p class="empty-state__title">No projects found</p><p class="text-muted">Try a different filter category.</p></div>';
    return;
  }

  grid.innerHTML = projects
    .map(function (project) {
      var targetUrl = getProjectUrl(project);
      return (
        '<article class="project-card reveal" data-id="' +
        project.id +
        '">' +
        '<a href="' +
        targetUrl +
        '" target="_blank" rel="noopener noreferrer" class="project-card__link-wrap">' +
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
        '</div>' +
        '</a>' +
        '<div class="project-card__footer">' +
        '<a href="' +
        project.github +
        '" target="_blank" rel="noopener noreferrer" class="project-card__link project-card__link--github">' +
        '<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/></svg>' +
        ' Source' +
        '</a>' +
        (project.live
          ? '<a href="' +
            project.live +
            '" target="_blank" rel="noopener noreferrer" class="project-card__link project-card__link--live">' +
            '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>' +
            ' Live' +
            '</a>'
          : '') +
        '<button class="project-card__link project-card__link--details" onclick="openProjectModal(' +
        project.id +
        ')">Details →</button>' +
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

  var linksHtml = '<div class="modal__links">';
  linksHtml +=
    '<a href="' +
    project.github +
    '" target="_blank" rel="noopener noreferrer" class="btn btn--outline">' +
    '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" style="margin-right:6px;vertical-align:middle"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/></svg> View Source Code' +
    '</a>';
  if (project.live) {
    linksHtml +=
      '<a href="' +
      project.live +
      '" target="_blank" rel="noopener noreferrer" class="btn btn--primary">' +
      '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:6px;vertical-align:middle"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg> View Live Demo' +
      '</a>';
  }
  linksHtml += '</div>';

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
    linksHtml;

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