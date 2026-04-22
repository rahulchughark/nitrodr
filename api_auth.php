<?php
include('includes/include.php');
function getTokenData($token) {
    $query = "SELECT * FROM explore_tokens WHERE token = '" . addslashes($token) . "'";
    $result = db_query($query);
    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    return false;
}

// New function to mark token as used
function markTokenAsUsed($token) {
    $updateQuery = "UPDATE explore_tokens SET is_used = 1 WHERE token = '" . addslashes($token) . "'";
    return db_query($updateQuery);
}

// Function to validate token
function validateToken($token) {
    $tokenData = getTokenData($token);
    if (!$tokenData) {
        return ['status' => false, 'message' => 'Invalid Token'];
    }

    // Check expiry
    if (strtotime($tokenData['expires_at']) < time()) {
        return ['status' => false, 'message' => 'Token expired'];
    }

    // Additional user check (optional, based on your user table)
    // Example: check if user exists
    $userQuery = "SELECT * FROM users WHERE id = '" . addslashes($tokenData['user_id']) . "'";
    $userResult = db_query($userQuery);
    if (!$userResult || mysqli_num_rows($userResult) == 0) {
        return ['status' => false, 'message' => 'Invalid user'];
    }   

    return ['status' => true, 'data' => $tokenData];
}

// Main validation logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $headers = getallheaders();
    $authHeader = isset($headers['Authorization']) ? $headers['Authorization'] : '';

    if (preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
        $token = $matches[1];

        $validation = validateToken($token);
        if ($validation['status'] === true) {
            // Token valid, proceed
            // Optional: mark token as used or refresh expiry
            echo json_encode(['status' => 'success', 'message' => 'Token valid', 'user_data' => $validation['data']]);
        } else {
            // Token invalid or expired
            http_response_code(401);
            echo json_encode(['status' => 'fail', 'message' => $validation['message']]);
        }
    } else {
        http_response_code(400);
        echo json_encode(['status' => 'fail', 'message' => 'Authorization header missing or invalid']);
    }
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['status' => 'fail', 'message' => 'Invalid request method']);
}
?>
