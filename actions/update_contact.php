<?php
/**
 * Update Contact API Endpoint
 * 
 * Endpoint: update_contact.php
 * Method: POST
 * Request Data: {cid: int, cname: string, cnum: string}
 * Response: "success" or "failed" (string)
 * 
 * Example Request:
 * {
 *   "cid": 1,
 *   "cname": "John Doe Updated",
 *   "cnum": "+233501234567"
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
    if (!isset($data['id']) || !isset($data['name']) || !isset($data['phone'])) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'Missing required fields: id, name, and phone'
        ]);
        exit();
    }
    
    $contactId = intval($data['id']);
    $contactName = trim($data['name']);
    $contactPhone = trim($data['phone']);
    
    // Validate data
    if ($contactId <= 0) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'Invalid contact ID'
        ]);
        exit();
    }
    
    if (empty($contactName)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'Name cannot be empty'
        ]);
        exit();
    }
    
    if (empty($contactPhone)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'Phone number cannot be empty'
        ]);
        exit();
    }
    
    if (strlen($contactPhone) < 10) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'Phone number must be at least 10 digits'
        ]);
        exit();
    }
    
    // Get database connection
    $conn = getDBConnection();
    
    // Check if contact exists first
    $checkSql = "SELECT pid FROM contacts WHERE pid = ? LIMIT 1";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("i", $contactId);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    
    if ($checkResult->num_rows === 0) {
        $checkStmt->close();
        closeDBConnection($conn);
        
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'error' => 'not found'
        ]);
        exit();
    }
    $checkStmt->close();
    
    // Prepare SQL query with prepared statement
    $sql = "UPDATE contacts SET pname = ?, pphone = ? WHERE pid = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    
    // Bind parameters
    $stmt->bind_param("ssi", $contactName, $contactPhone, $contactId);
    
    // Execute query
    if ($stmt->execute()) {
        $affectedRows = $stmt->affected_rows;
        $stmt->close();
        closeDBConnection($conn);
        
        http_response_code(200);
        echo json_encode([
            'success' => true
        ]);
    } else {
        throw new Exception("Execute failed: " . $stmt->error);
    }
    
} catch (Exception $e) {
    // Log error
    error_log("Update Contact Error: " . $e->getMessage());
    
    // Return error response
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Failed to update contact'
    ]);
}
?>
