<?php
/**
 * Get All Contacts API Endpoint
 * 
 * Endpoint: get_all_contact_mob.php
 * Method: GET
 * Request Data: None
 * Response: JSON list of contacts (pid, pname, pphone)
 * 
 * Example Response:
 * [
 *   {"pid": "1", "pname": "John Doe", "pphone": "+233501234567"},
 *   {"pid": "2", "pname": "Jane Smith", "pphone": "+233241234567"}
 * ]
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
    // Get database connection
    $conn = getDBConnection();
    
    // Prepare SQL query to fetch all contacts
    $sql = "SELECT pid, pname, pphone FROM contacts ORDER BY pname ASC";
    
    // Execute query
    $result = $conn->query($sql);
    
    // Check if query was successful
    if (!$result) {
        throw new Exception("Query failed: " . $conn->error);
    }
    
    // Fetch all contacts
    $contacts = [];
    while ($row = $result->fetch_assoc()) {
        $contacts[] = [
            'id' => $row['pid'],
            'name' => $row['pname'],
            'phone' => $row['pphone']
        ];
    }
    
    // Close connection
    $result->free();
    closeDBConnection($conn);
    
    // Return success response with contacts
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'data' => $contacts
    ]);
    
} catch (Exception $e) {
    // Log error
    error_log("Get All Contacts Error: " . $e->getMessage());
    
    // Return error response
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Failed to retrieve contacts'
    ]);
}
?>
