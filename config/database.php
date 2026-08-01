<?php
/**
 * config/database.php
 * -------------------
 * Single place that creates the PDO connection used by the whole site.
 * Every page just does:  require_once __DIR__ . '/config/database.php';
 * and then has a ready-to-use $pdo object.
 *
 * These values are for the InfinityFree MySQL database.
 */

define('DB_HOST', 'sql309.infinityfree.com');
define('DB_NAME', 'if0_42543007_portfolio_db');
define('DB_USER', 'if0_42543007');
define('DB_PASS', '1z2WvD6ZA2Ei');
define('DB_CHARSET', 'utf8mb4');

$dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // throw exceptions on SQL errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,        // fetch rows as associative arrays
    PDO::ATTR_EMULATE_PREPARES   => false,                   // use real prepared statements
];

try {
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    // Never leak DB credentials or full error details to visitors.
    http_response_code(500);
    die('Database connection failed. Please make sure the "portfolio_db" database has '
        . 'been imported in phpMyAdmin and the credentials in config/database.php are correct.');
}
