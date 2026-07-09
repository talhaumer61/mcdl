<?php
require_once("../../include/dbsetting/lms_vars_config.php");

// Check if the file is being accessed via POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Get the pos_id from POST data
    $pos_id = $_POST['pos_id'] ?? null;

    if (!$pos_id) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing POS ID']);
        exit;
    }

    // Define DB credentials (assuming they are constants)
    $conn = new mysqli(LMS_HOSTNAME, LMS_USERNAME, LMS_USERPASS, LMS_NAME);

    if ($conn->connect_error) {
        http_response_code(500);
        echo json_encode(['error' => 'Database connection failed']);
        exit;
    }
    
    // Define the current date for comparison
    $today = date('Y-m-d');

    // Prepare and execute query to check if a record exists and the date is valid
    $stmt = $conn->prepare("
        SELECT start_date, end_date FROM pos_registrations 
        WHERE pos_id = ? 
        AND ? BETWEEN start_date AND end_date
    ");
    $stmt->bind_param("ss", $pos_id, $today);
    $stmt->execute();
    $result = $stmt->get_result();

    // Response based on result
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        http_response_code(200);
        echo json_encode([
            'status'     => true,
            'start_date' => $row['start_date'],
            'end_date'   => $row['end_date']
        ]);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'No active access record found for this POS ID']);
    }

    $stmt->close();
    $conn->close();

} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request method. Only POST is allowed.']);
}
?>