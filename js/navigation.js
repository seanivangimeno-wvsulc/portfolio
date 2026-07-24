document.addEventListener('DOMContentLoaded', function () {
  initTheme();
  initNavigation();
  initScrollEffects();
  initRevealAnimations();
  initThemeToggle();
  initSmoothAnchors();
  initParallaxHero();
  initHeroMouseInteractivity();
  initMagneticHover();
  initScrollProgress();
  initTimelineReveal();
});

function initNavigation() {
  var menuBtn = document.getElementById('menu-btn');
  var nav = document.getElementById('nav');
  if (!menuBtn || !nav) return;

  menuBtn.addEventListener('click', function () {
    var isOpen = nav.classList.toggle('open');
    menuBtn.classList.toggle('active');
    menuBtn.setAttribute('aria-expanded', isOpen);
  });

  document.addEventListener('click', function (e) {
    if (window.innerWidth <= 767 && nav.classList.contains('open')) {
      if (!nav.contains(e.target) && !menuBtn.contains(e.target)) {
        nav.classList.remove('open');
        menuBtn.classList.remove('active');
        menuBtn.setAttribute('aria-expanded', 'false');
      }
    }
  });

  document.querySelectorAll('.nav__link').forEach(function (link) {
    link.addEventListener('click', function () {
      if (window.innerWidth <= 767) {
        nav.classList.remove('open');
        menuBtn.classList.remove('active');
        menuBtn.setAttribute('aria-expanded', 'false');
      }
    });
  });

  window.addEventListener('resize', function () {
    if (window.innerWidth > 767 && nav.classList.contains('open')) {
      nav.classList.remove('open');
      menuBtn.classList.remove('active');
      menuBtn.setAttribute('aria-expanded', 'false');
    }
  });
}

function initScrollEffects() {
  var header = document.getElementById('header');
  if (!header) return;

  var ticking = false;
  window.addEventListener('scroll', function () {
    if (!ticking) {
      window.requestAnimationFrame(function () {
        header.classList.toggle('header--scrolled', window.scrollY > 50);
        ticking = false;
      });
      ticking = true;
    }
  });
}

function initRevealAnimations() {
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    document.querySelectorAll('.reveal').forEach(function (el) {
      el.classList.add('visible');
    });
    return;
  }

  var observer = new IntersectionObserver(
    function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');

          var delay = entry.target.getAttribute('data-reveal-delay');
          if (delay) {
            entry.target.style.transitionDelay = delay + 'ms';
          }

          observer.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.1, rootMargin: '0px 0px -60px 0px' }
  );

  document.querySelectorAll('.reveal').forEach(function (el) {
    observer.observe(el);
  });
}

function initThemeToggle() {
  var btn = document.getElementById('theme-toggle');
  if (!btn) return;

  btn.addEventListener('click', function () {
    var current = getTheme();
    var next = current === 'dark' ? 'light' : 'dark';
    setTheme(next);

    btn.style.transform = 'rotate(360deg)';
    setTimeout(function () {
      btn.style.transform = '';
    }, 400);
  });
}

function initSmoothAnchors() {
  document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
    anchor.addEventListener('click', function (e) {
      var href = anchor.getAttribute('href');
      if (href === '#') return;
      var target = document.querySelector(href);
      if (target) {
        e.preventDefault();
        var header = document.getElementById('header');
        var offset = header ? header.offsetHeight : 72;
        var targetPos = target.getBoundingClientRect().top + window.scrollY - offset;
        window.scrollTo({ top: targetPos, behavior: 'smooth' });
      }
    });
  });
}

function initParallaxHero() {
  var hero = document.querySelector('.hero');
  if (!hero) return;

  var heroBg = hero.querySelector('.hero__bg');
  var heroGrid = hero.querySelector('.hero__bg-grid');
  var heroGradients = hero.querySelectorAll('.hero__bg-gradient');
  if (!heroBg) return;

  window.addEventListener('scroll', function () {
    var scrollY = window.scrollY;
    var heroHeight = hero.offsetHeight;
    if (scrollY <= heroHeight) {
      var progress = scrollY / heroHeight;

      if (heroGrid) {
        heroGrid.style.transform = 'translateY(' + (progress * 40) + 'px)';
      }

      heroGradients.forEach(function (grad, i) {
        var direction = i === 0 ? 1 : -1;
        grad.style.transform = 'translate(' + (progress * 20 * direction) + 'px, ' + (progress * 30 * direction) + 'px) scale(' + (1 + progress * 0.1) + ')';
      });
    }
  });
}

function initHeroMouseInteractivity() {
  var hero = document.querySelector('.hero');
  if (!hero) return;

  var ticking = false;

  hero.addEventListener('mousemove', function (e) {
    if (!ticking) {
      window.requestAnimationFrame(function () {
        var rect = hero.getBoundingClientRect();
        var x = e.clientX - rect.left;
        var y = e.clientY - rect.top;
        hero.style.setProperty('--mouse-x', (x / rect.width).toFixed(3));
        hero.style.setProperty('--mouse-y', (y / rect.height).toFixed(3));
        ticking = false;
      });
      ticking = true;
    }
  });

  hero.addEventListener('mouseleave', function () {
    hero.style.setProperty('--mouse-x', '0.5');
    hero.style.setProperty('--mouse-y', '0.5');
  });
}

function initMagneticHover() {
  var magneticEls = document.querySelectorAll('.magnetic');
  if (magneticEls.length === 0) return;

  magneticEls.forEach(function (el) {
    el.addEventListener('mousemove', function (e) {
      var rect = el.getBoundingClientRect();
      var x = e.clientX - rect.left - rect.width / 2;
      var y = e.clientY - rect.top - rect.height / 2;
      var strength = 8;
      el.style.transform = 'translate(' + (x / strength) + 'px, ' + (y / strength) + 'px)';
    });

    el.addEventListener('mouseleave', function () {
      el.style.transform = 'translate(0, 0)';
      el.style.transition = 'transform 0.3s ease-out';
      setTimeout(function () {
        el.style.transition = '';
      }, 300);
    });
  });
}

function initScrollProgress() {
  var progressBar = document.getElementById('reading-progress');
  if (!progressBar) return;

  window.addEventListener('scroll', function () {
    var scrollTop = window.scrollY;
    var docHeight = document.documentElement.scrollHeight - window.innerHeight;
    if (docHeight > 0) {
      var progress = (scrollTop / docHeight) * 100;
      progressBar.style.width = progress + '%';
    }
  });
}

function initTimelineReveal() {
  var timelineItems = document.querySelectorAll('.timeline__item');
  if (timelineItems.length === 0) return;

  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    timelineItems.forEach(function (el) {
      el.classList.add('visible');
    });
    return;
  }

  var observer = new IntersectionObserver(
    function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
          observer.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.15, rootMargin: '0px 0px -40px 0px' }
  );

  timelineItems.forEach(function (el) {
    observer.observe(el);
  });
}
