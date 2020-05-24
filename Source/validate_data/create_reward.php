<?php

//Check if data has been sent by POST
if (isset($_POST)) {

    //Upload the required files
    require_once '../configs/connection.php';

    //Collect the data by POST
    $user = $_SESSION['user']['id'];
    $name = isset($_POST['name']) ?  mysqli_real_escape_string($db, trim($_POST['name'])) : false;
    $content = isset($_POST['content']) ?  mysqli_real_escape_string($db, trim($_POST['content'])) : false;
    $type = isset($_POST['type']) ?  mysqli_real_escape_string($db, trim($_POST['type'])) : false;
    $follows = isset($_POST['follows']) ?  1 : 0;
    $subscribers = isset($_POST['subscribers']) ?  1 : 0;

    //Let's check that all the fields meet some requirements
    $errors = array();

    if (empty($name)) {
        $errors['name'] = "The name is invalid";
    }

    if (empty($content) || strlen($content) >= 350) {
        $errors['content'] = "The content is invalid";
    }
    
    if($type == "yt_link"){
        $content = parse_url($content);
        parse_str($content['query'], $content);
        $content = $content['v'];
    }
    
    if (empty($type)) {
        $errors['type'] = "The type is invalid";
    }

    if ($follows == 0 && $subscribers == 0){
        $errors['filter'] = "You must select at least one boxes";
    }

    //If there are no errors we insert the reward in the db and if it does not generate an error
    if (count($errors) == 0){

        $sql = "INSERT INTO rewards VALUE (null, '$user', '$name', '$content', '$type', $follows, $subscribers, NOW());";
        $query_save = mysqli_query($db, $sql);

        if($query_save){
            $_SESSION['completed'] = "It has been created successfully";
        }else{
            $_SESSION['errors'] = "There was an error sending the data";
        }

    }else{
        $_SESSION['errors_create_reward'] = $errors;
    }

}else{
    $_SESSION['errors'] = "There was an error sending the data";
}

//If the creator of the reward is the same as the logged in user, the reward is removed
header('Location: ../index.php');
die();

?>