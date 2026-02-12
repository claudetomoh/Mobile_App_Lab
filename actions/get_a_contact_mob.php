<?php
/**
 * Get One Contact API Endpoint
 * 
 * Endpoint: get_a_contact_mob.php
 * Method: GET
 * Request Data: contid (via query parameter)
 * Response: One JSON contact object (pid, pname, pphone)
 * 
 * Example Request:
 * GET get_a_contact_mob.php?contid=1
 * 
 * Example Response:
 * {
 *   "pid": "1",
 *   "pname": "John Doe",
 *   "pphone": "+233501234567"
 * }
 */

// Enable CORS for mobile app requests
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

// Include database connection
require_once '../database/db_connection.php';

// Check if request method is GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Method not allowed. Use GET request.'
    ]);
    exit();
}

try {
    // Get contact ID from query parameter
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'Missing required parameter: id'
        ]);
        exit();
    }
    
    $contactId = intval($_GET['id']);
    
    // Validate contact ID
    if ($contactId <= 0) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid contact ID'
        ]);
        exit();
    }
    
    // Get database connection
    $conn = getDBConnection();
    
    // Prepare SQL query with prepared statement
    $sql = "SELECT pid, pname, pphone FROM contacts WHERE pid = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    
    // Bind parameters
    $stmt->bind_param("i", $contactId);
    
    // Execute query
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }
    
    // Get result
    $result = $stmt->get_result();
    
    // Check if contact exists
    if ($result->num_rows === 0) {
        $stmt->close();
        closeDBConnection($conn);
        
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'error' => 'not found'
        ]);
        exit();
    }
    
    // Fetch contact
    $row = $result->fetch_assoc();
    $contact = [
        'id' => $row['pid'],
        'name' => $row['pname'],
        'phone' => $row['pphone']
    ];
    
    // Close connection
    $stmt->close();
    closeDBConnection($conn);
    
    // Return success response with contact
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'data' => $contact
    ]);
    
} catch (Exception $e) {
    // Log error
    error_log("Get One Contact Error: " . $e->getMessage());
    
    // Return error response
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to retrieve contact',
        'details' => $e->getMessage()
    ]);
}
?>
