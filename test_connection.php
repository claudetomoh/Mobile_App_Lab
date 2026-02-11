<?php
/**
 * Database Connection Tester
 * Run this file to verify your database connection is working
 * 
 * Usage: 
 * - Command line: php test_connection.php
 * - Browser: Open this file in your browser
 */

echo "<html><head><title>Database Connection Test</title>";
echo "<style>
    body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; background: #f5f5f5; }
    .box { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    .success { border-left: 4px solid #4CAF50; }
    .error { border-left: 4px solid #F44336; }
    .warning { border-left: 4px solid #FF9800; }
    h1 { color: #333; }
    h2 { color: #666; margin-top: 0; }
    pre { background: #2d2d2d; color: #f8f8f2; padding: 15px; border-radius: 5px; overflow-x: auto; }
    .status { font-weight: bold; font-size: 18px; }
</style></head><body>";

echo "<h1>🔧 Database Connection Test</h1>";

// Include database connection
$dbFile = __DIR__ . '/database/db_connection.php';

if (!file_exists($dbFile)) {
    echo "<div class='box error'>";
    echo "<h2>❌ Error</h2>";
    echo "<p>db_connection.php not found at: <code>$dbFile</code></p>";
    echo "</div></body></html>";
    exit;
}

require_once $dbFile;

echo "<div class='box'>";
echo "<h2>Testing Database Connection...</h2>";

// Test 1: Check if constants are defined
echo "<h3>Step 1: Configuration Check</h3>";
if (defined('DB_HOST') && defined('DB_USER') && defined('DB_NAME')) {
    echo "<p class='status' style='color: #4CAF50;'>✅ Configuration constants defined</p>";
    echo "<ul>";
    echo "<li><strong>Host:</strong> " . DB_HOST . "</li>";
    echo "<li><strong>User:</strong> " . DB_USER . "</li>";
    echo "<li><strong>Database:</strong> " . DB_NAME . "</li>";
    echo "</ul>";
} else {
    echo "<p class='status' style='color: #F44336;'>❌ Configuration constants not defined</p>";
    echo "</div></body></html>";
    exit;
}

// Test 2: Try to connect
echo "<h3>Step 2: Connection Test</h3>";
try {
    $conn = getDBConnection();
    
    echo "<p class='status' style='color: #4CAF50;'>✅ Database connection successful!</p>";
    
    // Test 3: Check if database exists and is selected
    echo "<h3>Step 3: Database Verification</h3>";
    $result = $conn->query("SELECT DATABASE()");
    if ($result) {
        $row = $result->fetch_row();
        echo "<p class='status' style='color: #4CAF50;'>✅ Connected to database: <strong>{$row[0]}</strong></p>";
    }
    
    // Test 4: Check if contacts table exists
    echo "<h3>Step 4: Table Verification</h3>";
    $result = $conn->query("SHOW TABLES LIKE 'contacts'");
    if ($result && $result->num_rows > 0) {
        echo "<p class='status' style='color: #4CAF50;'>✅ Table 'contacts' exists</p>";
        
        // Test 5: Count records
        echo "<h3>Step 5: Data Verification</h3>";
        $result = $conn->query("SELECT COUNT(*) as count FROM contacts");
        if ($result) {
            $row = $result->fetch_assoc();
            $count = $row['count'];
            
            if ($count > 0) {
                echo "<p class='status' style='color: #4CAF50;'>✅ Found {$count} contact(s) in database</p>";
                
                // Show sample data
                $result = $conn->query("SELECT * FROM contacts LIMIT 3");
                if ($result && $result->num_rows > 0) {
                    echo "<h4>Sample Data:</h4>";
                    echo "<pre>";
                    while ($row = $result->fetch_assoc()) {
                        echo json_encode($row, JSON_PRETTY_PRINT) . "\n";
                    }
                    echo "</pre>";
                }
            } else {
                echo "<p class='status' style='color: #FF9800;'>⚠️ Table 'contacts' is empty</p>";
                echo "<p>Run the schema.sql file to insert sample data.</p>";
            }
        }
        
    } else {
        echo "<p class='status' style='color: #F44336;'>❌ Table 'contacts' does not exist</p>";
        echo "<p>Please run the schema.sql file to create the table.</p>";
    }
    
    // Test 6: Test API endpoint
    echo "<h3>Step 6: API Endpoint Test</h3>";
    echo "<p>Try accessing your API:</p>";
    echo "<ul>";
    echo "<li><a href='actions/get_all_contact_mob.php' target='_blank'>Get All Contacts</a></li>";
    echo "</ul>";
    
    closeDBConnection($conn);
    
} catch (Exception $e) {
    echo "<p class='status' style='color: #F44336;'>❌ Connection failed</p>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    
    echo "<div class='box warning'>";
    echo "<h3>🔧 Troubleshooting Steps:</h3>";
    echo "<ol>";
    echo "<li>Verify your database credentials in <code>database/db_connection.php</code></li>";
    echo "<li>Make sure MySQL service is running</li>";
    echo "<li>Check that database '<strong>" . DB_NAME . "</strong>' exists</li>";
    echo "<li>Verify user '<strong>" . DB_USER . "</strong>' has access to the database</li>";
    echo "<li>Run: <code>php setup_wizard.php</code> for guided setup</li>";
    echo "</ol>";
    echo "</div>";
}

echo "</div>";

// Final summary
echo "<div class='box'>";
echo "<h2>📋 Summary</h2>";
echo "<p>If all tests passed, your database is configured correctly!</p>";
echo "<p><strong>Next steps:</strong></p>";
echo "<ol>";
echo "<li>Upload files to your Ashesi server</li>";
echo "<li>Test API endpoints using <code>tests/test_api.html</code></li>";
echo "<li>Take screenshots for submission</li>";
echo "</ol>";
echo "</div>";

echo "</body></html>";
?>
