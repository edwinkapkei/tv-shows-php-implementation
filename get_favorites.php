<?php
include_once 'connection.php';
include_once 'constants.php';

$response = array();
$favorites = array();
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
        $get_favorites = $pdo->prepare("SELECT DISTINCT user_id,show_id FROM favorites WHERE user_id=? ORDER BY id DESC");
        $get_favorites->execute([$user_id]);
        if ($get_favorites) {
            foreach ($get_favorites as $row) {
                $show_id = $row['show_id'];

                $favorites[] = $show_id;
            }
            $status = 1;
            $message = "Favorite shows";
        } else {
            $status = 0;
            $message = $GENERIC_ERROR;
        }
    }
}

if ($status == 1) {
    $response = array('status' => $status, 'message' => $message, 'data' => $favorites);
} else {
    $response = array('status' => $status, 'message' => $message);
}

echo json_encode($response);
