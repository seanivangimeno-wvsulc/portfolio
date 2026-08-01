-- =========================================================
--  PORTFOLIO DATABASE (InfinityFree-ready version)
--  Import this file in phpMyAdmin: Import tab -> Choose File
--  NOTE: On InfinityFree the database already exists (it was
--  created for you as if0_42543007_portfolio_db when you set up
--  MySQL in the control panel) and shared hosting does not allow
--  CREATE DATABASE / USE statements, so this version starts
--  straight from the tables. Import this file while phpMyAdmin
--  is already open INSIDE that database.
-- =========================================================

-- ---------------------------------------------------------
-- 1. site_settings  -> editable text used all over the site
--    (name, tagline, email, socials, footer text, etc.)
-- ---------------------------------------------------------
CREATE TABLE `site_settings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `setting_key` VARCHAR(60) NOT NULL UNIQUE,
  `setting_value` TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `site_settings` (`setting_key`, `setting_value`) VALUES
('site_name', 'Sean'),
('full_name', 'Sean Ivan Gimeno'),
('site_title', 'Sean Gimeno — App Developer'),
('hero_badge', 'Available for new projects'),
('hero_title', 'Crafting <span class="text-gradient">exceptional</span> app experiences'),
('hero_description', 'I''m Sean Gimeno, an ICT student specializing in web systems and software tools, who recently completed an intensive AI training program to build intelligent web apps.'),
('stat_1_number', 'ICT'),
('stat_1_label', 'Major/Field'),
('stat_2_number', '15+'),
('stat_2_label', 'Projects Built'),
('stat_3_number', 'AI'),
('stat_3_label', 'Trained &amp; Certified'),
('about_intro_1', 'I''m a passionate Information and Communications Technology (ICT) student who recently completed an intensive AI training program. I focus on bridging traditional software infrastructure with cognitive and intelligent systems.'),
('about_intro_2', 'I love learning how to develop functional web systems, manage databases, and leverage advanced AI tools and custom prompt engineering to solve real-world problems.'),
('about_photo', 'assets/images/FB_IMG_1773493087526.jpg'),
('email', 'seanivandoks@gmail.com'),
('location', 'West Visayas State University — Lambunao Campus'),
('availability', 'Open for freelance & student projects'),
('github_url', 'https://github.com/seanivangimeno-wvsulc'),
('linkedin_url', 'https://www.linkedin.com/in/sean-ivan-gimeno-27b055380/'),
('footer_about', 'ICT student and app developer crafting high-quality web experiences, based at WVSU Lambunao Campus.'),
('copyright_year', '2026'),
('smtp_host', 'smtp.gmail.com'),
('smtp_user', 'seanivandoks@gmail.com'),
('smtp_pass', ''),
('smtp_port', '587');

-- ---------------------------------------------------------
-- 2. skill_categories + skills  -> "What I Do Best" section
-- ---------------------------------------------------------
CREATE TABLE `skill_categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `category_name` VARCHAR(100) NOT NULL,
  `icon` VARCHAR(20) DEFAULT NULL,
  `sort_order` INT DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `skills` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `category_id` INT NOT NULL,
  `skill_name` VARCHAR(100) NOT NULL,
  `skill_level` TINYINT UNSIGNED NOT NULL DEFAULT 0,
  `sort_order` INT DEFAULT 0,
  FOREIGN KEY (`category_id`) REFERENCES `skill_categories`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `skill_categories` (`id`, `category_name`, `icon`, `sort_order`) VALUES
(1, 'Artificial Intelligence', '🤖', 1),
(2, 'ICT Core & Web Development', '💻', 2),
(3, 'Databases & Tools', '⚙️', 3),
(4, 'Systems & Networking', '🌐', 4);

INSERT INTO `skills` (`category_id`, `skill_name`, `skill_level`, `sort_order`) VALUES
(1, 'Python', 85, 1),
(1, 'Prompt Engineering', 92, 2),
(1, 'LLM Integration', 88, 3),
(1, 'Fine-Tuning Models', 80, 4),
(1, 'TensorFlow / PyTorch', 75, 5),
(2, 'HTML5 / CSS3', 90, 1),
(2, 'JavaScript (ES5/ES6)', 88, 2),
(2, 'PHP', 82, 3),
(2, 'Responsive Web Design', 90, 4),
(3, 'MySQL / PostgreSQL', 85, 1),
(3, 'Git / GitHub', 88, 2),
(3, 'VS Code', 92, 3),
(3, 'Jupyter Notebooks', 90, 4),
(4, 'Linux / Bash Scripting', 80, 1),
(4, 'Computer Networking', 85, 2),
(4, 'System Administration', 82, 3),
(4, 'Docker Basics', 75, 4);

-- ---------------------------------------------------------
-- 3. projects  -> Projects page + Featured section on home
--    tags/tech/features are stored as comma separated text
--    so they are easy to edit directly inside phpMyAdmin.
-- ---------------------------------------------------------
CREATE TABLE `projects` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(150) NOT NULL,
  `summary` VARCHAR(300) NOT NULL,
  `description` TEXT NOT NULL,
  `icon` VARCHAR(20) DEFAULT '📱',
  `tags` VARCHAR(255) NOT NULL COMMENT 'comma separated e.g. web,fullstack',
  `tech` VARCHAR(255) NOT NULL COMMENT 'comma separated e.g. PHP,MySQL',
  `features` TEXT COMMENT 'one feature per line',
  `github_url` VARCHAR(255) DEFAULT NULL,
  `live_url` VARCHAR(255) DEFAULT NULL,
  `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
  `sort_order` INT DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `projects`
(`title`, `summary`, `description`, `icon`, `tags`, `tech`, `features`, `github_url`, `live_url`, `is_featured`, `sort_order`) VALUES
('Kiosk WVSU LC',
 'Event management kiosk system built for WVSU LC. Features event scheduling, attendance tracking, and real-time status updates.',
 'A full-featured event management kiosk system designed for West Visayas State University - Lambunao Campus. Built with TypeScript and Next.js, it handles event scheduling, attendee registration, QR code check-ins, and real-time status dashboards. Deployed on Vercel for scalability.',
 '🏛️', 'web,fullstack', 'TypeScript,Next.js,Vercel,PostgreSQL',
 'Event scheduling and management\nQR code attendee check-in\nReal-time status dashboards\nResponsive kiosk interface',
 'https://github.com/seanivangimeno-wvsulc/kiosk-wvsu-lc', 'https://kiosk-wvsu-lc.vercel.app', 1, 1),

('Flappy Clone',
 'A browser-based Flappy Bird clone built with vanilla HTML, CSS, and JavaScript. Arcade-style gameplay with retro visuals.',
 'A faithful recreation of the classic Flappy Bird game built entirely with vanilla web technologies. Features smooth physics, collision detection, score tracking, and retro-styled visuals. Deployed and playable directly in the browser via Vercel.',
 '🐦', 'web,game', 'HTML,CSS,JavaScript',
 'Smooth arcade physics engine\nCollision detection system\nScore tracking with persistence\nRetro pixel-art visual style',
 'https://github.com/seanivangimeno-wvsulc/flappy_clone', 'https://flappy-clone-three.vercel.app', 1, 2),

('SaligAI',
 'AI-powered assistant designed to support persons with disabilities (PWD) through voice commands, accessibility features, and intelligent task automation.',
 'SaligAI is an accessibility-focused AI assistant built with TypeScript that helps persons with disabilities navigate daily tasks through voice commands, screen reader optimization, and intelligent automation. Features include voice-activated controls, text-to-speech responses, and customizable accessibility profiles.',
 '♿', 'web,ai', 'TypeScript,AI/ML,Web Speech API,Accessibility APIs',
 'Voice command recognition and execution\nText-to-speech response system\nCustomizable accessibility profiles\nIntelligent task automation workflows',
 'https://github.com/seanivangimeno-wvsulc/SaligAI', '', 1, 3),

('ColorPointer',
 'Real-time hand gesture color changer using computer vision. Control your screen colors with hand movements via webcam.',
 'An interactive computer vision application that tracks hand gestures through the webcam to dynamically change screen colors in real-time. Built with TypeScript and MediaPipe hand tracking, it detects finger positions and gestures to control a virtual color palette.',
 '✋', 'web,ai', 'TypeScript,MediaPipe,Computer Vision,Webcam API',
 'Real-time hand gesture tracking via webcam\nDynamic color palette control\nMediaPipe-powered finger detection\nLow-latency visual feedback',
  'https://github.com/seanivangimeno-wvsulc/colorpointer', 'https://colorpointer.vercel.app/', 0, 4),

('Chess Game',
 'Full chess game implementation with move validation, AI opponent, and clean TypeScript architecture.',
 'A complete chess game built from scratch in TypeScript featuring full move validation (including castling, en passant, and pawn promotion), an AI opponent with minimax algorithm, and a clean terminal-based interface. Demonstrates strong algorithmic thinking and game logic implementation.',
 '♟️', 'web,game', 'TypeScript,Algorithms,Game Logic',
 'Complete chess move validation\nAI opponent with minimax algorithm\nCastling, en passant, and pawn promotion\nClean modular architecture',
 'https://github.com/seanivangimeno-wvsulc/Chess', '', 0, 5),

('Teachable Dino',
 'An AI-powered 8-bit retro platformer runner controlled using trained machine learning webcam gesture inputs.',
 'Designed and built an interactive retro game environment integrated with Google''s Teachable Machine API. Users can train a custom image classification model in their browser and control the game character''s actions hands-free using real-time webcam gestures.',
 '🦖', 'web,ai,game', 'TypeScript,TensorFlow.js,Teachable Machine,Webcam API',
 'Real-time gesture recognition via webcam\nHands-free game controls\nCustom model training in browser\n8-bit retro platformer gameplay',
  'https://github.com/seanivangimeno-wvsulc/dinorun', 'https://dinorun-cyan.vercel.app/', 0, 6),

('Portfolio Website',
 'This portfolio site — now rebuilt with PHP, JavaScript, and a MySQL/PDO backend instead of static hardcoded data.',
 'A portfolio website originally built with vanilla HTML, CSS and JavaScript, now upgraded to PHP with a PDO-powered MySQL database. Features include dark/light theme toggle, scroll-triggered reveal animations, database-driven project and blog content, a working contact form that stores messages in MySQL, and a small admin panel to manage everything.',
 '👨‍💻', 'web,fullstack', 'PHP,MySQL,PDO,JavaScript,HTML,CSS',
 'Dark/light theme with localStorage persistence\nScroll-triggered reveal animations\nDatabase-driven dynamic content (PDO + MySQL)\nAdmin panel to manage projects, blog posts and messages',
 'https://github.com/seanivangimeno-wvsulc/portfolio', 'https://portfolio-sean26.vercel.app', 1, 7);

-- ---------------------------------------------------------
-- 4. experience  -> About page timeline
-- ---------------------------------------------------------
CREATE TABLE `experience` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `role_title` VARCHAR(150) NOT NULL,
  `organization` VARCHAR(150) NOT NULL,
  `date_range` VARCHAR(60) NOT NULL,
  `description` TEXT NOT NULL,
  `sort_order` INT DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `experience` (`role_title`, `organization`, `date_range`, `description`, `sort_order`) VALUES
('AI Training Program Graduate', 'Intensive AI Bootcamp', '2026', 'Completed an intensive AI training program covering prompt engineering, LLM integration, and applied machine learning, building projects like Teachable Dino and SaligAI.', 1),
('ICT Student', 'West Visayas State University — Lambunao Campus', '2023 — Present', 'Studying Information and Communications Technology, building full-stack web systems, learning networking, databases, and software development fundamentals.', 2),
('Independent Developer', 'Personal Projects', '2022 — Present', 'Built and shipped several personal projects including games, AI tools, and web applications, published and version-controlled on GitHub.', 3);

-- ---------------------------------------------------------
-- 5. core_values  -> About page "How I Work" cards
-- ---------------------------------------------------------
CREATE TABLE `core_values` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `icon` VARCHAR(20) DEFAULT NULL,
  `title` VARCHAR(100) NOT NULL,
  `description` TEXT NOT NULL,
  `sort_order` INT DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `core_values` (`icon`, `title`, `description`, `sort_order`) VALUES
('🎯', 'User First', 'Every technical decision starts with understanding the user''s needs and delivering the best possible experience.', 1),
('🏗️', 'Clean Architecture', 'Code that''s maintainable, testable, and scalable. Good architecture prevents technical debt and enables rapid iteration.', 2),
('📈', 'Data Driven', 'Decisions backed by metrics and analytics. Measure, iterate, and improve continuously based on real user behavior.', 3);

-- ---------------------------------------------------------
-- 6. blog_posts  -> Blog page + single post view
-- ---------------------------------------------------------
CREATE TABLE `blog_posts` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(200) NOT NULL,
  `excerpt` VARCHAR(400) NOT NULL,
  `content` TEXT NOT NULL,
  `category` VARCHAR(50) NOT NULL,
  `tags` VARCHAR(255) DEFAULT NULL COMMENT 'comma separated',
  `icon` VARCHAR(20) DEFAULT '📝',
  `post_date` DATE NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `blog_posts` (`title`, `excerpt`, `content`, `category`, `tags`, `icon`, `post_date`) VALUES
('My Journey as an ICT Student Entering the World of AI',
 'How I transitioned from traditional ICT studies into machine learning, and how I built my first AI-powered retro game.',
 'As an Information and Communications Technology (ICT) student, I spent a lot of time learning standard web systems, networking protocols, and databases. But my perspective changed when I underwent an intensive AI training program. In this post, I share my journey of diving into machine learning, learning TensorFlow.js, and building Teachable Dino—a retro platformer controlled entirely via custom webcam gesture models. Discover how you can bridge traditional development with AI today.',
 'personal', 'personal,ai,student-life', '🎓', '2026-06-22'),

('Building Scalable Mobile Apps with Microservices',
 'Learn how to architect mobile applications using a microservices backend for better scalability and maintainability.',
 'When building mobile applications that need to scale to millions of users, a monolithic architecture quickly becomes a bottleneck. In this post, I''ll walk through how we migrated FoodDash from a monolith to a microservices architecture. We''ll cover service decomposition, API gateway patterns, database per service, inter-service communication with message queues, and deployment strategies with Kubernetes. The result was a 3x improvement in deployment frequency and 99.99% uptime.',
 'architecture', 'microservices,mobile,backend', '🏗️', '2026-05-15'),

('Optimizing React Native Performance',
 'Practical techniques for improving render performance, reducing bundle size, and enhancing user experience in React Native apps.',
 'React Native performance optimization is crucial for delivering a smooth user experience. This article covers profiling with the React DevTools, optimizing FlatList for large datasets, reducing JavaScript bundle size with Metro configuration, using Hermes engine, implementing image caching strategies, and avoiding common performance pitfalls. Real-world benchmarks show a 40% reduction in time-to-interactive.',
 'engineering', 'react-native,performance,mobile', '⚡', '2026-04-28'),

('CI/CD Pipelines for Mobile Apps',
 'Setting up automated build, test, and deployment pipelines for iOS and Android applications.',
 'Automating mobile app delivery is essential for teams that ship frequently. This guide covers setting up GitHub Actions for React Native apps, automated testing on physical devices via AWS Device Farm, code signing management, beta distribution with TestFlight and Play Console, staged rollouts, and monitoring crash reports with Sentry integration.',
 'devops', 'ci-cd,mobile,automation', '🔄', '2026-03-10'),

('Designing RESTful APIs That Developers Love',
 'Best practices for API design including versioning, error handling, pagination, and documentation.',
 'A well-designed API is a joy to work with. This post covers RESTful API design principles: resource naming conventions, proper use of HTTP methods and status codes, pagination strategies (cursor vs offset), versioning approaches, error response formats, rate limiting, and auto-generated documentation with OpenAPI. Includes practical examples from production APIs.',
 'architecture', 'api,rest,backend', '🔌', '2026-02-22'),

('Machine Learning on Mobile Devices',
 'Running ML models directly on-device with TensorFlow Lite and Core ML for real-time features.',
 'On-device machine learning enables real-time features without network latency. This article explores model conversion from TensorFlow to TFLite, quantization techniques for size reduction, platform-specific acceleration with Core ML and Android NN API, and real-world use cases like image classification, pose estimation, and natural language processing in mobile apps.',
 'ai', 'machine-learning,mobile,ai', '🤖', '2026-01-15'),

('The Developer''s Guide to WebSockets',
 'Implementing real-time features with WebSockets for chat, live updates, and collaborative editing.',
 'Real-time features are expected in modern applications. This guide covers WebSocket fundamentals, implementing a chat system with Socket.io, handling reconnection and presence, scaling WebSockets with Redis Pub/Sub, and security considerations including origin checking and authentication. Includes complete code examples for both client and server.',
 'engineering', 'websocket,real-time,fullstack', '🔗', '2025-12-08');

-- ---------------------------------------------------------
-- 7. contact_messages  -> filled in by visitors via contact.php
-- ---------------------------------------------------------
CREATE TABLE `contact_messages` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(150) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `subject` VARCHAR(200) NOT NULL,
  `message` TEXT NOT NULL,
  `is_read` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- 8. admin_users  -> login for the admin panel
--    default account -> username: admin | password: admin123
--    (please change the password after first login!)
-- ---------------------------------------------------------
CREATE TABLE `admin_users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(60) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- password hash below corresponds to plaintext: admin123
INSERT INTO `admin_users` (`username`, `password_hash`) VALUES
('admin', '$2y$10$s9s5r.UXLAQ0mrPXQzB5XOuisYaM6M34MarRN4DFRYYwnBiDHcv1u');
