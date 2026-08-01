# Sean Gimeno — Portfolio (PHP + JavaScript + MySQL/PDO)

This is your portfolio site, rebuilt from static HTML/hardcoded JSON into a
PHP + JavaScript site backed by a real MySQL database (accessed through PDO).
Every project, blog post, skill, and message is now stored in the database
instead of being hardcoded — and comes with a small admin panel so you (or
your client) can add/edit/delete content without touching any code.

## 1. Folder structure

```
portfolio/
├── admin/                 Admin panel (login required)
│   ├── includes/          auth.php, admin_header.php, admin_footer.php
│   ├── index.php          Dashboard
│   ├── projects.php / project_form.php    Manage projects
│   ├── blog.php / blog_form.php           Manage blog posts
│   ├── skills.php         Manage skill categories & skills
│   ├── messages.php       View/delete contact form submissions
│   ├── login.php / logout.php
├── assets/images/         Photos used on the site
├── config/database.php    PDO database connection (edit this if needed)
├── css/                   All stylesheets (unchanged from the original design)
├── database/portfolio_db.sql   Import this in phpMyAdmin to create everything
├── includes/               Shared PHP: functions.php, header.php, footer.php
├── js/                      utils.js, navigation.js, projects.js, blog.js, contact.js
├── index.php / about.php / projects.php / blog.php / blog-post.php / contact.php
```

## 2. Set up the database (phpMyAdmin)

1. Start Apache + MySQL (e.g. in XAMPP / Laragon / WAMP).
2. Open phpMyAdmin (usually `http://localhost/phpmyadmin`).
3. Click **Import** → **Choose File** → select `database/portfolio_db.sql`
   → click **Go**.
   This will create the `portfolio_db` database with all tables and fill
   them with your current portfolio content (projects, skills, blog posts,
   experience, etc.), plus one admin login.

That's it — nothing else to configure in phpMyAdmin.

## 3. Configure the database connection

Open `config/database.php`. The defaults already match a typical XAMPP setup:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'portfolio_db');
define('DB_USER', 'root');
define('DB_PASS', '');
```

If your MySQL root user has a password, or you're using a different setup,
update these 4 lines only.

## 4. Run the site

Put the whole `portfolio` folder inside your server's web root
(e.g. `htdocs/portfolio` for XAMPP), then visit:

- Public site: `http://localhost/portfolio/index.php`
- Admin panel: `http://localhost/portfolio/admin/login.php`

**Default admin login:** `admin` / `admin123`
Please log in and change this — you can update the password directly in
phpMyAdmin (`admin_users` table) using PHP's `password_hash()`, or update
the login flow later to include a "change password" screen.

## 5. What's dynamic now (no more hardcoded content)

| Page | Data comes from |
|---|---|
| Home | `site_settings`, `skill_categories` + `skills`, featured `projects` |
| About | `site_settings`, `experience`, `core_values` |
| Projects | `projects` table (filter buttons + details modal use this data) |
| Blog | `blog_posts` table (search + category filter run against it) |
| Blog post | `blog_posts` table, looked up by `?id=` with a PDO prepared statement |
| Contact | Saves every submission into `contact_messages` via PDO |

## 6. Editing content

You have two options:

1. **Admin panel** (recommended) — log in at `/admin`, and add/edit/delete
   projects, blog posts, skills, and view contact messages through a normal
   form-based UI.
2. **phpMyAdmin directly** — open any table (e.g. `projects`) and edit rows
   there. Handy for quickly tweaking `site_settings` (your name, email,
   social links, hero text) which doesn't have its own admin screen yet.

## 7. Security notes

- All database queries use **PDO prepared statements** — no raw string
  concatenation of user input into SQL, which prevents SQL injection.
- All output is passed through `e()` (a `htmlspecialchars()` wrapper) before
  being echoed, which prevents XSS.
- Admin passwords are hashed with PHP's `password_hash()` / verified with
  `password_verify()` — never stored in plain text.
- The admin panel is protected by PHP sessions (`admin/includes/auth.php`)
  — every admin page requires login.

## 8. Possible next steps

- Add a "Change Password" screen in the admin panel.
- Add image uploads for projects/blog posts (currently they use emoji icons
  as lightweight placeholders, matching the original design).
- Add pagination if your project/blog list grows large.
