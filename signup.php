<?php
//error_reporting(0);
include_once 'connection.php';
include_once 'constants.php';

$response = array();
$user_array = array();
$status = 0;
$message = "";

$json = file_get_contents('php://input');
$data = json_decode($json);
if (empty($data)) {
    $status = 0;
    $message = "Invalid Request";
} else {
    $name = $data->name;
    $email = $data->email;
    $password = $data->password;

    $query_signup = $pdo->prepare('INSERT IGNORE INTO users(name,email,password) VALUES(?,?,?)');
    $query_signup->execute([$name, $email, $password]);

    if ($query_signup) {
        if ($query_signup->rowCount() > 0) {
            $id = $pdo->lastInsertId();
            $status = 1;
            $message = "Signup Successful";
            $user_array = array('user_id' => $id);
        } else {
            $status = 0;
            $message = "Email already in use";
        }
    } else {
        $status = 0;
        $message = $GENERIC_ERROR;
    }
}

if ($status == 1) {
    $response = array('status' => $status, 'message' => $message, 'data' => $user_array);
} else {
    $response = array('status' => $status, 'message' => $message);
}

echo json_encode($response);
