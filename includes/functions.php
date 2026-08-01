<?php
/**
 * includes/functions.php
 * -----------------------
 * Small reusable helper functions shared by every page.
 */

/** Escape text for safe HTML output (prevents XSS). */
function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

/** Read one value from the site_settings table, with a fallback default. */
function getSetting(PDO $pdo, string $key, string $default = ''): string
{
    static $cache = null;

    if ($cache === null) {
        $cache = [];
        $stmt = $pdo->query('SELECT setting_key, setting_value FROM site_settings');
        foreach ($stmt->fetchAll() as $row) {
            $cache[$row['setting_key']] = $row['setting_value'];
        }
    }

    return $cache[$key] ?? $default;
}

/** Turn a "a,b,c" string into a clean array ['a','b','c']. */
function splitList(?string $value): array
{
    if (!$value) {
        return [];
    }
    return array_values(array_filter(array_map('trim', explode(',', $value))));
}

/** Turn a newline separated string into an array of lines. */
function splitLines(?string $value): array
{
    if (!$value) {
        return [];
    }
    return array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $value))));
}

/** Format a date like "June 22, 2026". */
function formatDate(string $date): string
{
    $ts = strtotime($date);
    return $ts ? date('F j, Y', $ts) : $date;
}

/** Estimate reading time in minutes for a blog post. */
function estimateReadingTime(string $text): int
{
    $words = str_word_count(strip_tags($text));
    return max(1, (int) ceil($words / 200));
}
