<?php
/**
 * Configuration Template
 * 
 * INSTRUCTIONS:
 * 1. Copy this file and rename it to 'config.php'
 * 2. Update the values below with your actual database credentials
 * 3. DO NOT commit config.php to version control
 */

// Database Configuration
define('DB_HOST', 'localhost');              // e.g., 'localhost' or 'apps.ashesi.edu.gh'
define('DB_USER', 'your_database_username'); // Your MySQL username (e.g., tomoh_ikfingeh)
define('DB_PASS', 'your_database_password'); // Your MySQL password
define('DB_NAME', 'mobileapps_2026B_tomoh_ikfingeh');  // Database name

// Application Configuration
define('APP_ENV', 'development');            // 'development' or 'production'
define('APP_DEBUG', true);                   // Set to false in production

// CORS Configuration (if needed for mobile apps)
define('ALLOW_ORIGIN', '*');                 // '*' or specific domain

// API Configuration
define('API_VERSION', '1.0');
define('API_BASE_URL', 'https://apps.ashesi.edu.gh/contactmgt/actions/');

// Error Reporting
if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}
?>
