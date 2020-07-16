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
    $addToFavorites = $data->addToFavorites;

    if ($addToFavorites) {
        $query_add_favorite = $pdo->prepare('INSERT INTO favorites(user_id,show_id) VALUES(?,?)');
        $query_add_favorite->execute([$user_id, $show_id]);

        if ($query_add_favorite) {
            $status = 1;
            $message = "Added to Favorites";
            $flag = true;
        } else {
            $status = 0;
            $message = $GENERIC_ERROR;
        }
    } else {
        $query_remove_favorite = $pdo->prepare('DELETE FROM favorites WHERE user_id=? AND show_id=?');
        $query_remove_favorite->execute([$user_id, $show_id]);

        if ($query_remove_favorite) {
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
    $response = array('status' => $status, 'message' => $message,"flag"=>$flag);
} else {
    $response = array('status' => $status, 'message' => $message);
}

echo json_encode($response);
