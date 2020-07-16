<?php
//error_reporting(0);
include_once 'connection.php';
include_once 'constants.php';

$response = array();
$user_array = array();
$status = 0;
$message = "";
$favorite = false;
$scheduled = false;

$json = file_get_contents('php://input');
$data = json_decode($json);
if (empty($data)) {
    $status = 0;
    $message = "Invalid Request";
} else {
    $user_id = $data->user_id;
    $show_id = $data->show_id;

    $query_check_favorite = $pdo->prepare('SELECT * FROM favorites WHERE user_id=? AND show_id=?');
    $query_check_favorite->execute([$user_id, $show_id]);

    $status = 1;
    $message = "Success";

    if ($query_check_favorite->rowCount() > 0) {
        $favorite = true;
    } else {
        $favorite = false;
    }

    $query_check_schedule = $pdo->prepare('SELECT * FROM schedule WHERE user_id=? AND show_id=?');
    $query_check_schedule->execute([$user_id, $show_id]);
    if ($query_check_schedule->rowCount() > 0) {
        $scheduled = true;
    } else {
        $scheduled = false;
    }
}

if ($status == 1) {
    $response = array('status' => $status, 'message' => $message, "favorite" => $favorite, "scheduled" => $scheduled);
} else {
    $response = array('status' => $status, 'message' => $message);
}

echo json_encode($response);
