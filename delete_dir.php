<?php
function deleteDirectory($dir) {
    if (!is_dir($dir)) {
        return false;
    }
    
    $files = array_diff(scandir($dir), array('.', '..'));

    foreach ($files as $file) {
        $path = $dir . DIRECTORY_SEPARATOR . $file;

        if (is_dir($path)) {
            // Recursive call to delete subdirectories and files inside
            deleteDirectory($path);
        } else {
            // Delete the file
            unlink($path);
        }
    }

    // Remove the directory itself
    return rmdir($dir);
}

// replace directory_name_to_delete with the actual directory
$directoryToDelete = './directory_name_to_delete';

if (deleteDirectory($directoryToDelete)) {
    echo "Directory deleted successfully.";
} else {
    echo "Failed to delete the directory.";
}

?>