<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/emailjs.php';
require_once __DIR__ . '/includes/functions.php';

/**
 * Send the contact form notification through EmailJS's HTTP API.
 * InfinityFree's free tier blocks all outbound SMTP, so PHPMailer/Gmail
 * SMTP never worked here. EmailJS sends over plain HTTPS instead, which
 * is not blocked.
 *
 * Returns true on success, false on failure (never throws, so a failed
 * email never breaks the "message received" experience for the visitor).
 */
function sendContactEmailViaEmailJS(string $name, string $email, string $subject, string $message): array
{
    $payload = [
        'service_id'  => EMAILJS_SERVICE_ID,
        'template_id' => EMAILJS_TEMPLATE_ID,
        'user_id'     => EMAILJS_PUBLIC_KEY,
        'accessToken' => EMAILJS_PRIVATE_KEY,
        'template_params' => [
            'from_name'  => $name,
            'from_email' => $email,
            'subject'    => $subject,
            'message'    => $message,
        ],
    ];

    $ch = curl_init('https://api.emailjs.com/api/v1.0/email/send');
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
        CURLOPT_POSTFIELDS     => json_encode($payload),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 15,
    ]);
    $response  = curl_exec($ch);
    $httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    $ok = ($httpCode === 200);
    if (!$ok) {
        error_log('EmailJS send failed (HTTP ' . $httpCode . '): ' . $response . ' ' . $curlError);
    }

    return [
        'ok'         => $ok,
        'http_code'  => $httpCode,
        'curl_error' => $curlError,
        'response'   => $response,
    ];
}

$activePage      = 'contact';
$fullName        = getSetting($pdo, 'full_name', 'Sean Gimeno');
$pageTitle       = 'Contact — ' . $fullName;
$pageDescription = 'Get in touch with ' . $fullName . ' for app development projects and collaborations.';
$extraCss        = ['contact'];
$extraScripts    = ['contact'];

$errors  = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name === '') {
        $errors['name'] = 'Please enter your name.';
    }
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email address.';
    }
    if (mb_strlen($subject) < 3) {
        $errors['subject'] = 'Subject must be at least 3 characters.';
    }
    if (mb_strlen($message) < 10) {
        $errors['message'] = 'Message must be at least 10 characters.';
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare(
            'INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([$name, $email, $subject, $message]);

        sendContactEmailViaEmailJS($name, $email, $subject, $message);

        echo json_encode([
            'success' => true,
            'message' => 'Thanks! Your message has been sent and I will get back to you soon.',
        ]);
        exit;
    }

    echo json_encode(['success' => false, 'errors' => $errors]);
    exit;
}

require __DIR__ . '/includes/header.php';
?>

  <main>
    <section class="page-header">
      <div class="container">
        <span class="section__label reveal">Connect</span>
        <h1 class="page-header__title reveal">Get in Touch</h1>
        <p class="page-header__subtitle reveal">
          Have a project in mind, a question, or just want to say hello?
          I'd love to hear from you.
        </p>
      </div>
    </section>

    <section class="section" style="padding-top:0">
      <div class="container">
        <div class="contact__inner">
          <div class="reveal reveal--left">
            <form class="contact__form" id="contact-form" novalidate>
              <div class="contact__form-row">
                <div class="form-field">
                  <label for="name">Name</label>
                  <input type="text" id="name" name="name" class="input" required placeholder="Your name" autocomplete="name">
                  <span class="field-error" id="name-error"></span>
                </div>
                <div class="form-field">
                  <label for="email">Email</label>
                  <input type="email" id="email" name="email" class="input" required placeholder="you@example.com" autocomplete="email">
                  <span class="field-error" id="email-error"></span>
                </div>
              </div>
              <div class="form-field">
                <label for="subject">Subject</label>
                <input type="text" id="subject" name="subject" class="input" required placeholder="What's this about?" minlength="3">
                <span class="field-error" id="subject-error"></span>
              </div>
              <div class="form-field">
                <label for="message">Message</label>
                <textarea id="message" name="message" class="input" required placeholder="Tell me about your project..." maxlength="1000" minlength="10"></textarea>
                <div class="char-count" id="char-count">0 / 1000</div>
                <span class="field-error" id="message-error"></span>
              </div>
              <div class="contact__form-status" id="form-status"></div>
              <button type="submit" class="btn btn--primary contact__submit">Send Message →</button>
            </form>
          </div>
          <div class="reveal reveal--right">
            <div class="availability">
              <span class="availability__dot"></span>
              <?php echo e(getSetting($pdo, 'hero_badge')); ?>
            </div>
            <h2 class="contact__info-title">Let's create something amazing</h2>
            <p class="contact__info-description">
              Whether you need a web app, a database-driven system, or technical
              consulting, I'm here to help bring your vision to life.
            </p>
            <div class="contact__info-item">
              <div class="contact__info-item-icon">✉️</div>
              <div class="contact__info-item-content">
                <span class="contact__info-item-label">Email</span>
                <span class="contact__info-item-value"><?php echo e(getSetting($pdo, 'email')); ?></span>
              </div>
            </div>
            <div class="contact__info-item">
              <div class="contact__info-item-icon">📍</div>
              <div class="contact__info-item-content">
                <span class="contact__info-item-label">Location</span>
                <span class="contact__info-item-value"><?php echo e(getSetting($pdo, 'location')); ?></span>
              </div>
            </div>
            <div class="contact__info-item">
              <div class="contact__info-item-icon">💼</div>
              <div class="contact__info-item-content">
                <span class="contact__info-item-label">Availability</span>
                <span class="contact__info-item-value"><?php echo e(getSetting($pdo, 'availability')); ?></span>
              </div>
            </div>
            <div class="contact__social">
              <a href="<?php echo e(getSetting($pdo, 'github_url')); ?>" class="contact__social-link" aria-label="GitHub" target="_blank" rel="noopener noreferrer">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/></svg>
              </a>
              <a href="<?php echo e(getSetting($pdo, 'linkedin_url')); ?>" class="contact__social-link" aria-label="LinkedIn" target="_blank" rel="noopener noreferrer">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
              </a>
              <a href="mailto:<?php echo e(getSetting($pdo, 'email')); ?>" class="contact__social-link" aria-label="Email" target="_blank" rel="noopener noreferrer">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

<?php require __DIR__ . '/includes/footer.php'; ?>