<?php
include_once 'connection.php';
include_once 'constants.php';

$response = array();
$comment = array();
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

    $get_comment = $pdo->prepare("SELECT * FROM comments WHERE user_id=? && show_id=? ORDER BY id DESC LIMIT 1");
    $get_comment->execute([$user_id,$show_id]);
    if ($get_comment->rowCount() > 0) {
        $row = $get_comment->fetch();
        $comment = array('comment' => $row['comment']);
        $status = 1;
        $message = "Comment Added";
    } else {
        $status = 0;
        $message = $GENERIC_ERROR;
    }
}

if ($status == 1) {
    $response = array('status' => $status, 'message' => $message, 'data' => $comment);
} else {
    $response = array('status' => $status, 'message' => $message);
}

echo json_encode($response);
