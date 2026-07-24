# Agentic Coding Guide

## Project Overview

Vanilla HTML/CSS/JS static portfolio site. No frameworks, no build tools, no package manager. Data is fetched from JSON files at runtime.

## File Structure

```
├── index.html         — Home page
├── about.html         — About page
├── blog.html          — Blog listing page
├── contact.html       — Contact form page
├── projects.html      — Projects listing page
├── blog-post.html     — Single blog post view
├── css/
│   ├── variables.css  — CSS custom properties (colors, spacing, typography, shadows)
│   ├── reset.css      — CSS reset / normalize
│   ├── components.css — Shared component styles (buttons, cards, tags, form elements, toasts, spinners)
│   ├── layout.css     — Layout utilities (container, grid, section)
│   ├── animations.css — Keyframe animations and transition classes
│   └── pages/         — Page-specific styles (one per page)
├── js/
│   ├── utils.js       — Shared utilities (debounce, formatDate, theme management, trapFocus, escapeHtml)
│   ├── navigation.js  — Nav menu toggle, scroll effects, reveal animations, theme toggle
│   ├── projects.js    — Projects page (filter, render, modal)
│   ├── blog.js        — Blog page (fetch, filter, search, reading progress)
│   └── contact.js     — Contact form validation and submission
└── data/
    ├── projects.json  — Project entries
    ├── posts.json     — Blog post entries
    └── skills.json    — Skill categories
```

## Code Conventions

- **JavaScript**: ES5-compatible syntax — use `function` declarations, `var`, no arrow functions or template literals. This is intentional for broad browser support.
- **CSS**: Use custom properties from `variables.css`. Follow BEM-like naming (`.block__element--modifier`). Prefixed CSS class naming (`.project-card__title`, `.blog-card__meta`, etc.).
- **HTML**: Semantic HTML5 elements, aria attributes for accessibility.
- **Data Flow**: Pages fetch their own JSON at runtime via `fetch('data/...')` in a DOMContentLoaded handler. No bundling or SPA routing.

## Common Patterns

### Adding a new page
1. Create the `.html` file extending the header/footer pattern from existing pages
2. Add a page-specific CSS file in `css/pages/`
3. Add a JS file in `js/` with a `DOMContentLoaded` entry point
4. Link all three in the HTML `<head>` (CSS) and before `</body>` (JS)
5. Add the nav link in all page headers

### Adding a new data source
1. Create a JSON file in `data/`
2. Fetch it in the relevant JS file with error handling (`.catch` shows a user-friendly message)
3. Include loading, empty, and error states in the UI

### Styling
- Use CSS custom properties from `variables.css` for colors, spacing, typography, and shadows
- Respect light/dark mode via `[data-theme="light"]` and `[data-theme="dark"]`
- Page-specific styles go in `css/pages/`; shared styles in the root CSS files
- Use the `.reveal` class for scroll-triggered fade-in animations
- Use `var(--space-*)` for spacing, `var(--fs-*)` for font sizes, `var(--color-*)` for colors

## Theme System

- Default is dark theme (`data-theme="dark"` on `<html>`)
- Light theme is toggled via `#theme-toggle` button and persisted in `localStorage`
- CSS variables switch under `[data-theme="light"]`
- Media query `prefers-color-scheme: light` works as fallback when no explicit theme is stored

## To Serve Locally

```bash
npx serve . -l 3000
```

## Agent Instructions

- Do not convert JS to modern syntax (ES6+). Keep `var`, `function`, and ES5 patterns.
- Do not add or switch to a build tool, bundler, or framework.
- When adding features, match the existing loading/empty/error state patterns.
- Use `escapeHtml()` from utils.js when rendering user or JSON data into innerHTML.
- Use `initRevealAnimations()` after dynamically inserting content with `.reveal` elements.
