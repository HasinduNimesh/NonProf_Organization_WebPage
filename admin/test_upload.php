<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Upload Test Script</h1>";

// Check PHP configuration
echo "<h2>PHP Upload Configuration</h2>";
echo "<p>upload_max_filesize: " . ini_get('upload_max_filesize') . "</p>";
echo "<p>post_max_size: " . ini_get('post_max_size') . "</p>";
echo "<p>max_execution_time: " . ini_get('max_execution_time') . "</p>";
echo "<p>memory_limit: " . ini_get('memory_limit') . "</p>";

// Check directories
echo "<h2>Directory Permissions</h2>";

$main_dir = "../images";
$blog_dir = "../images/Blog_Projects";

// Test main images directory
testDirectory($main_dir);

// Test blog images directory
testDirectory($blog_dir);

// Function to test directory permissions
function testDirectory($dir) {
    echo "<h3>Testing: $dir</h3>";
    
    if (!file_exists($dir)) {
        echo "<p>Directory doesn't exist. Attempting to create...</p>";
        if (mkdir($dir, 0755, true)) {
            echo "<p style='color:green'>✅ Successfully created directory.</p>";
        } else {
            echo "<p style='color:red'>❌ Failed to create directory!</p>";
            echo "<p>Error: " . error_get_last()['message'] . "</p>";
        }
    } else {
        echo "<p>✓ Directory exists.</p>";
    }
    
    if (file_exists($dir)) {
        if (is_writable($dir)) {
            echo "<p style='color:green'>✅ Directory is writable.</p>";
            
            // Try creating a test file
            $test_file = $dir . "/test_" . time() . ".txt";
            if (file_put_contents($test_file, "Test file for upload permissions")) {
                echo "<p style='color:green'>✅ Successfully created test file: " . basename($test_file) . "</p>";
                if (unlink($test_file)) {
                    echo "<p style='color:green'>✅ Successfully deleted test file.</p>";
                } else {
                    echo "<p style='color:orange'>⚠️ Could not delete test file!</p>";
                }
            } else {
                echo "<p style='color:red'>❌ Could not create test file!</p>";
                echo "<p>Error: " . error_get_last()['message'] . "</p>";
            }
        } else {
            echo "<p style='color:red'>❌ Directory is NOT writable!</p>";
            echo "<p>Directory owner: " . getDirectoryOwner($dir) . "</p>";
            echo "<p>PHP process owner: " . getCurrentProcessUser() . "</p>";
        }
    }
}

// Get directory owner
function getDirectoryOwner($dir) {
    if (function_exists('posix_getpwuid')) {
        $owner = posix_getpwuid(fileowner($dir));
        return $owner['name'] ?? 'Unknown';
    }
    return 'Unknown (posix functions not available)';
}

// Get current process user
function getCurrentProcessUser() {
    if (function_exists('posix_geteuid')) {
        $processUser = posix_getpwuid(posix_geteuid());
        return $processUser['name'] ?? 'Unknown';
    }
    return 'Unknown (posix functions not available)';
}

echo "<h2>Upload Form Test</h2>";
?>

<form action="" method="post" enctype="multipart/form-data">
    <p>
        <input type="file" name="test_upload">
    </p>
    <p>
        <button type="submit">Test Upload</button>
    </p>
</form>

<?php
// Process test upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['test_upload'])) {
    echo "<h3>Upload Results:</h3>";
    echo "<pre>";
    print_r($_FILES['test_upload']);
    echo "</pre>";
    
    if ($_FILES['test_upload']['error'] === 0) {
        $upload_dir = "../images/test";
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        $upload_path = $upload_dir . "/" . basename($_FILES['test_upload']['name']);
        if (move_uploaded_file($_FILES['test_upload']['tmp_name'], $upload_path)) {
            echo "<p style='color:green'>✅ Test file uploaded successfully to $upload_path</p>";
            echo "<p><img src='$upload_path' style='max-width:300px;'></p>";
        } else {
            echo "<p style='color:red'>❌ Failed to move uploaded test file!</p>";
        }
    } else {
        echo "<p style='color:red'>❌ Error during file upload: " . uploadErrorMessage($_FILES['test_upload']['error']) . "</p>";
    }
}

function uploadErrorMessage($errorCode) {
    $errors = [
        UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize in php.ini',
        UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE in the HTML form',
        UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
        UPLOAD_ERR_NO_FILE => 'No file was uploaded',
        UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
        UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
        UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the upload'
    ];
    
    return $errors[$errorCode] ?? 'Unknown error';
}
?>