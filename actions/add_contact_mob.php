<?php
/**
 * Add New Contact API Endpoint
 * 
 * Endpoint: add_contact_mob.php
 * Method: POST
 * Request Data: {ufullname: string, uphonename: string}
 * Response: "success" or "failed" (string)
 * 
 * Example Request:
 * {
 *   "ufullname": "John Doe",
 *   "uphonename": "+233501234567"
 * }
 * 
 * Example Response:
 * "success"
 */

// Enable CORS for mobile app requests
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

// Handle OPTIONS request for CORS preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Include database connection
require_once '../database/db_connection.php';

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode("failed - Method not allowed. Use POST request.");
    exit();
}

try {
    // Get POST data (handle both JSON and form-data)
    $data = null;
    $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
    
    if (strpos($contentType, 'application/json') !== false) {
        // JSON input
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
    } else {
        // Form data input
        $data = $_POST;
    }
    
    // Validate input
    if (!isset($data['ufullname']) || !isset($data['uphonename'])) {
        http_response_code(400);
        echo json_encode("failed - Missing required fields: ufullname and uphonename");
        exit();
    }
    
    $fullname = trim($data['ufullname']);
    $phonename = trim($data['uphonename']);
    
    // Validate data
    if (empty($fullname)) {
        http_response_code(400);
        echo json_encode("failed - Name cannot be empty");
        exit();
    }
    
    if (empty($phonename)) {
        http_response_code(400);
        echo json_encode("failed - Phone number cannot be empty");
        exit();
    }
    
    if (strlen($phonename) < 10) {
        http_response_code(400);
        echo json_encode("failed - Phone number must be at least 10 digits");
        exit();
    }
    
    // Get database connection
    $conn = getDBConnection();
    
    // Prepare SQL query with prepared statement to prevent SQL injection
    $sql = "INSERT INTO contacts (pname, pphone) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    
    // Bind parameters
    $stmt->bind_param("ss", $fullname, $phonename);
    
    // Execute query
    if ($stmt->execute()) {
        $insertedId = $stmt->insert_id;
        $stmt->close();
        closeDBConnection($conn);
        
        // Return success
        http_response_code(200);
        echo json_encode("success");
    } else {
        throw new Exception("Execute failed: " . $stmt->error);
    }
    
} catch (Exception $e) {
    // Log error
    error_log("Add Contact Error: " . $e->getMessage());
    
    // Return error response
    http_response_code(500);
    echo json_encode("failed - " . $e->getMessage());
}
?>
