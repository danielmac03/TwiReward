<?php

//Upload the required files
require_once '../configs/connection.php';

//Collect the data by GET
$reward_id = $_GET['reward_id'];

//Extract the reward from the db and turn it into an array
$sql = "SELECT user_id FROM rewards WHERE id = $reward_id LIMIT 1";
$query = mysqli_query($db, $sql);
$reward = mysqli_fetch_assoc($query);

//If the creator of the reward is the same as the logged in user, the reward is removed
if($reward['user_id'] == $_SESSION['user']['id']){

    $sql = "DELETE FROM rewards WHERE id = $reward_id";
    mysqli_query($db, $sql);

    $_SESSION['completed'] = "It has been removed successfully";

}else{
    $_SESSION['errors'] = "You cannot delete a reward that has been created by you";
}

//If the creator of the reward is the same as the logged in user, the reward is removed
header('Location: ../index.php');
die();

?>