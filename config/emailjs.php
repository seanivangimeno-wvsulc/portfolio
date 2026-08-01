<?php
/**
 * config/emailjs.php
 * -------------------
 * Credentials for sending the contact form email through EmailJS's
 * HTTP API instead of SMTP. InfinityFree's free tier blocks all
 * outbound SMTP, but normal outbound HTTPS (which this uses) works fine.
 *
 * Get/replace these at https://dashboard.emailjs.com
 *   Service ID  -> Email Services
 *   Template ID -> Email Templates
 *   Public Key  -> Account > General
 *   Private Key -> Account > Security (keep this secret, never expose in JS)
 */

define('EMAILJS_SERVICE_ID', 'service_68t6m2r');
define('EMAILJS_TEMPLATE_ID', 'template_kaeqrbp');
define('EMAILJS_PUBLIC_KEY', 'XoxjlnNf_taWFPAEi');
define('EMAILJS_PRIVATE_KEY', 'kRQwvR30F8cgXWLaOqquj');
