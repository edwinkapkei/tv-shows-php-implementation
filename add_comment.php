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
    $user_id = $data->user_id;
    $show_id = $data->show_id;
    $comment = $data->comment;

    $query_add_comment = $pdo->prepare('INSERT INTO comments(user_id,show_id,comment) VALUES(?,?,?)');
    $query_add_comment->execute([$user_id, $show_id,$comment]);

    if ($query_add_comment) {
        $status = 1;
        $message = "Comment added";
    } else {
        $status = 0;
        $message = $GENERIC_ERROR;
    }
}

if ($status == 1) {
    $response = array('status' => $status, 'message' => $message);
} else {
    $response = array('status' => $status, 'message' => $message);
}

echo json_encode($response);
