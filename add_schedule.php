<?php
//error_reporting(0);
include_once 'connection.php';
include_once 'constants.php';

$response = array();
$user_array = array();
$status = 0;
$message = "";
$flag = false;

$json = file_get_contents('php://input');
$data = json_decode($json);
if (empty($data)) {
    $status = 0;
    $message = "Invalid Request";
} else {
    $user_id = $data->user_id;
    $show_id = $data->show_id;
    $addToSchedule = $data->addToSchedule;

    if ($addToSchedule) {
        $query_add_schedule = $pdo->prepare('INSERT INTO schedule(user_id,show_id) VALUES(?,?)');
        $query_add_schedule->execute([$user_id, $show_id]);

        if ($query_add_schedule) {
            $status = 1;
            $message = "Added to Schedule";
            $flag = true;
        } else {
            $status = 0;
            $message = $GENERIC_ERROR;
        }
    } else {
        $query_remove_schedule = $pdo->prepare('DELETE FROM schedule WHERE user_id=? AND show_id=?');
        $query_remove_schedule->execute([$user_id, $show_id]);

        if ($query_remove_schedule) {
            $status = 1;
            $message = "Removed from Favorites";
            $flag = false;
        } else {
            $status = 0;
            $message = $GENERIC_ERROR;
        }
    }
}

if ($status == 1) {
    $response = array('status' => $status, 'message' => $message, "flag" => $flag);
} else {
    $response = array('status' => $status, 'message' => $message);
}

echo json_encode($response);
