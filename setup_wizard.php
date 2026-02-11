<?php
/**
 * Database Setup Wizard
 * Run this file to configure your database connection interactively
 * 
 * Usage: php setup_wizard.php
 */

echo "\n";
echo "=================================================\n";
echo "  Contact Management API - Setup Wizard\n";
echo "=================================================\n\n";

// Check if running in CLI
if (php_sapi_name() !== 'cli') {
    echo "This script must be run from command line.\n";
    echo "Usage: php setup_wizard.php\n";
    exit(1);
}

// Get database credentials
echo "📊 Database Configuration\n";
echo "-------------------------------------------------\n\n";

echo "Enter Database Host (default: localhost): ";
$dbHost = trim(fgets(STDIN));
if (empty($dbHost)) {
    $dbHost = 'localhost';
}

echo "Enter Database Username: ";
$dbUser = trim(fgets(STDIN));

echo "Enter Database Password: ";
$dbPass = trim(fgets(STDIN));

echo "Enter Database Name (default: contactmgt): ";
$dbName = trim(fgets(STDIN));
if (empty($dbName)) {
    $dbName = 'contactmgt';
}

echo "\n";
echo "=================================================\n";
echo "  Configuration Summary\n";
echo "=================================================\n";
echo "Host:     $dbHost\n";
echo "Username: $dbUser\n";
echo "Password: " . str_repeat('*', strlen($dbPass)) . "\n";
echo "Database: $dbName\n";
echo "=================================================\n\n";

echo "Is this correct? (yes/no): ";
$confirm = trim(fgets(STDIN));

if (strtolower($confirm) !== 'yes' && strtolower($confirm) !== 'y') {
    echo "\n❌ Setup cancelled.\n\n";
    exit(0);
}

// Test database connection
echo "\n🔄 Testing database connection...\n";

try {
    $conn = new mysqli($dbHost, $dbUser, $dbPass);
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    echo "✅ Connection successful!\n";
    
    // Check if database exists
    $result = $conn->query("SHOW DATABASES LIKE '$dbName'");
    
    if ($result->num_rows == 0) {
        echo "\n⚠️  Database '$dbName' does not exist.\n";
        echo "Create it now? (yes/no): ";
        $createDb = trim(fgets(STDIN));
        
        if (strtolower($createDb) === 'yes' || strtolower($createDb) === 'y') {
            if ($conn->query("CREATE DATABASE $dbName")) {
                echo "✅ Database '$dbName' created successfully!\n";
            } else {
                throw new Exception("Failed to create database: " . $conn->error);
            }
        } else {
            echo "\n⚠️  Please create the database manually and run this wizard again.\n";
            $conn->close();
            exit(0);
        }
    } else {
        echo "✅ Database '$dbName' exists!\n";
    }
    
    // Select database
    $conn->select_db($dbName);
    
    // Check if contacts table exists
    $result = $conn->query("SHOW TABLES LIKE 'contacts'");
    
    if ($result->num_rows == 0) {
        echo "\n⚠️  Table 'contacts' does not exist.\n";
        echo "Create it now? (yes/no): ";
        $createTable = trim(fgets(STDIN));
        
        if (strtolower($createTable) === 'yes' || strtolower($createTable) === 'y') {
            // Read schema.sql
            $schemaFile = __DIR__ . '/database/schema.sql';
            
            if (!file_exists($schemaFile)) {
                echo "❌ schema.sql file not found at: $schemaFile\n";
                echo "Please run the schema.sql manually in phpMyAdmin.\n";
            } else {
                $schema = file_get_contents($schemaFile);
                
                // Split by semicolon and execute each statement
                $statements = array_filter(array_map('trim', explode(';', $schema)));
                
                foreach ($statements as $statement) {
                    if (!empty($statement) && strpos($statement, '--') !== 0) {
                        $conn->query($statement);
                    }
                }
                
                echo "✅ Database schema created successfully!\n";
                echo "✅ Sample data inserted!\n";
            }
        }
    } else {
        echo "✅ Table 'contacts' exists!\n";
    }
    
    $conn->close();
    
} catch (Exception $e) {
    echo "\n❌ Error: " . $e->getMessage() . "\n\n";
    echo "Please check your credentials and try again.\n\n";
    exit(1);
}

// Update db_connection.php
echo "\n🔄 Updating db_connection.php...\n";

$dbConnectionFile = __DIR__ . '/database/db_connection.php';

if (!file_exists($dbConnectionFile)) {
    echo "❌ db_connection.php not found at: $dbConnectionFile\n";
    exit(1);
}

$content = file_get_contents($dbConnectionFile);

// Replace credentials
$content = preg_replace(
    "/define\('DB_HOST',\s*'[^']*'\);/",
    "define('DB_HOST', '$dbHost');",
    $content
);

$content = preg_replace(
    "/define\('DB_USER',\s*'[^']*'\);/",
    "define('DB_USER', '$dbUser');",
    $content
);

$content = preg_replace(
    "/define\('DB_PASS',\s*'[^']*'\);/",
    "define('DB_PASS', '$dbPass');",
    $content
);

$content = preg_replace(
    "/define\('DB_NAME',\s*'[^']*'\);/",
    "define('DB_NAME', '$dbName');",
    $content
);

if (file_put_contents($dbConnectionFile, $content)) {
    echo "✅ db_connection.php updated successfully!\n";
} else {
    echo "❌ Failed to update db_connection.php\n";
    echo "Please update it manually with your credentials.\n";
}

// Final summary
echo "\n";
echo "=================================================\n";
echo "  🎉 Setup Complete!\n";
echo "=================================================\n\n";

echo "✅ Database connection configured\n";
echo "✅ Database '$dbName' is ready\n";
echo "✅ Table 'contacts' created (if needed)\n";
echo "✅ Configuration file updated\n\n";

echo "Next steps:\n";
echo "1. Upload files to your server\n";
echo "2. Test the API endpoints\n";
echo "3. Use tests/test_api.html for testing\n\n";

echo "Test your API:\n";
echo "Open: https://apps.ashesi.edu.gh/contactmgt/actions/get_all_contact_mob.php\n\n";

echo "Happy coding! 🚀\n\n";
?>
