<?php
/**
 * Delete Contact API Endpoint
 * 
 * Endpoint: delete_contact.php
 * Method: POST
 * Request Data: {cid: int}
 * Response: true or false (boolean)
 * 
 * Example Request:
 * {
 *   "cid": 1
 * }
 * 
 * Example Response:
 * true
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
    echo json_encode(false);
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
    if (!isset($data['id']) || empty($data['id'])) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'Missing required parameter: id'
        ]);
        exit();
    }
    
    $contactId = intval($data['id']);
    
    // Validate data
    if ($contactId <= 0) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'Invalid contact ID'
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
            'error' => "Contact with ID {$contactId} not found"
        ]);
        exit();
    }
    $checkStmt->close();
    
    // Prepare SQL query with prepared statement
    $sql = "DELETE FROM contacts WHERE pid = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    
    // Bind parameters
    $stmt->bind_param("i", $contactId);
    
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
    error_log("Delete Contact Error: " . $e->getMessage());
    
    // Return error response
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Failed to delete contact'
    ]);
}
?>
