<?php
include_once 'connection.php';
include_once 'constants.php';

$response = array();
$schedule = array();
$status = 0;
$message = "";

$json = file_get_contents('php://input');
$data = json_decode($json);
if (empty($data)) {
    $status = 0;
    $message = "Invalid Request";
} else {
    $user_id = $data->user_id;

    if (empty($user_id)) {
        $status = 0;
        $message = "Missing user id field";
    } else {
        $get_schedule = $pdo->prepare("SELECT DISTINCT user_id,show_id FROM schedule WHERE user_id=? ORDER BY id DESC");
        $get_schedule->execute([$user_id]);
        if ($get_schedule) {
            foreach ($get_schedule as $row) {
                $show_id = $row['show_id'];

                $schedule[] = $show_id;
            }
            $status = 1;
            $message = "Scheduled shows";
        } else {
            $status = 0;
            $message = $GENERIC_ERROR;
        }
    }
}

if ($status == 1) {
    $response = array('status' => $status, 'message' => $message, 'data' => $schedule);
} else {
    $response = array('status' => $status, 'message' => $message);
}

echo json_encode($response);
