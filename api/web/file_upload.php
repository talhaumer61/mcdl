<?php
// Check if the file is being uploaded via a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the directory and file name from POST data
    $file_dir = $_POST['file_dir'] ?? null;
    $file_name = $_POST['file_name'] ?? null;

    if (!$file_dir || !$file_name) {
        error_log("Missing required parameters: file_dir or file_name");
        http_response_code(400);
        echo '0';
        exit;
    }

    $file_path = $file_dir.$file_name;

    // Ensure the directory exists
    if (!file_exists($file_dir)) {
        mkdir($file_dir, 0777, true); // Create directory if not exists
    }

    // Detect the uploaded file dynamically
    if (!empty($_FILES)) {
        $uploadedFile = reset($_FILES); // Get the first uploaded file
        $tempFilePath = $uploadedFile['tmp_name'];

        if (move_uploaded_file($tempFilePath, $file_path)) {
            chmod($file_path, 0644);
            error_log("File moved to: " . $file_path);
            echo '1';
        } else {
            error_log("Failed to move file to: " . $file_path);
            echo '0';
        }
    } else {
        error_log("No files were uploaded.");
        http_response_code(400);
        echo '0';
    }
} else {
    http_response_code(400);
    echo 'Invalid request';
}
?>