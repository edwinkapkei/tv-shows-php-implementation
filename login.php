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
    $email = $data->email;
    $password = $data->password;

    if (empty($email)) {
        $status = 0;
        $message = "Missing email field";
    } else if (empty($password)) {
        $status = 0;
        $message = "Missing password field";
    } else {
        $query_login = $pdo->prepare('SELECT * FROM users WHERE email=? AND password=?');
        $query_login->execute([$email, $password]);
        if ($query_login) {
            if ($query_login->rowCount() == 1) {
                $row = $query_login->fetch();
                $user_id = $row['id'];

                $status = 1;
                $message = "Login Successful";
                $user_array = array('user_id' => $user_id);
            } else {
                $status = 0;
                $message = "Invalid email or password";
            }
        } else {
            $status = 0;
            $message = $GENERIC_ERROR;
        }
    }
}

if ($status == 1) {
    $response = array('status' => $status, 'message' => $message, 'data' => $user_array);
} else {
    $response = array('status' => $status, 'message' => $message);
}

echo json_encode($response);
