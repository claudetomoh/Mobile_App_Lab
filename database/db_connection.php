<?php
/**
 * Database Connection Configuration
 * Contact Management System
 * 
 * This file establishes a connection to the MySQL database
 * Update the credentials according to your Ashesi server configuration
 */

// Database configuration constants
define('DB_HOST', 'localhost');                // Database host
define('DB_USER', 'tomoh.ikfingeh');           // Ashesi server username
define('DB_PASS', 'STCL@UDE20@?');             // Ashesi server password
define('DB_NAME', 'mobileapps_2026B_tomoh_ikfingeh');  // Database name

// Character set
define('DB_CHARSET', 'utf8mb4');

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

/**
 * Create database connection
 * 
 * @return mysqli Database connection object
 */
function getDBConnection() {
    // Create connection
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Check connection
    if ($conn->connect_error) {
        // Log error (in production, don't expose this)
        error_log("Database Connection Failed: " . $conn->connect_error);
        
        // Return error response
        http_response_code(500);
        echo json_encode([
            'status' => 'error',
            'message' => 'Database connection failed'
        ]);
        exit();
    }
    
    // Set charset to handle special characters properly
    $conn->set_charset(DB_CHARSET);
    
    return $conn;
}

/**
 * Close database connection
 * 
 * @param mysqli $conn Database connection object
 */
function closeDBConnection($conn) {
    if ($conn && !$conn->connect_error) {
        $conn->close();
    }
}

/**
 * Execute a prepared statement safely
 * 
 * @param mysqli $conn Database connection
 * @param string $sql SQL query with placeholders
 * @param string $types Parameter types (e.g., 'ss' for two strings)
 * @param array $params Parameters to bind
 * @return mysqli_result|bool Query result
 */
function executePreparedStatement($conn, $sql, $types, $params) {
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error);
        return false;
    }
    
    if (!empty($types) && !empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    
    if ($stmt->error) {
        error_log("Execute failed: " . $stmt->error);
        $stmt->close();
        return false;
    }
    
    return $stmt;
}

/**
 * Send JSON response
 * 
 * @param mixed $data Data to send
 * @param int $statusCode HTTP status code
 */
function sendJSONResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit();
}

/**
 * Get POST data as JSON
 * 
 * @return array Decoded JSON data
 */
function getJSONInput() {
    $input = file_get_contents('php://input');
    return json_decode($input, true);
}
?>
