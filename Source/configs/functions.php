<?php

function showErrors ($errors, $field){
	$alert = '';
	if(isset($errors[$field]) && !empty($field)) {
		$alert = "<div class='errors'>".$errors[$field].'</div>';
	}

	return $alert; 
}



function deleteErrors (){
    
    $deleted = false;

    if (isset($_SESSION['errors_create_reward'])){
        $_SESSION['errors_create_reward'] = null;
        $deleted = true;
    }
   
    if (isset($_SESSION['errors'])){
        $_SESSION['errors'] = null;
        $deleted = true;
    }

    if (isset($_SESSION['completed'])){
        $_SESSION['completed'] = null;
        $deleted = true;
    }

    return $deleted;

}

function getUser($id){

    //Set the headers for a query
    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $_SESSION['access_token'],
        'Client-ID: '. $_SESSION['client_id']
    ];

    if($id == null){
        $url = "https://api.twitch.tv/helix/users";
    }else{
        $url = "https://api.twitch.tv/helix/users?id=" . $id;
    }

    // Do an inquiry
    $cliente = curl_init();
    curl_setopt($cliente, CURLOPT_URL, $url);
    curl_setopt($cliente, CURLOPT_HEADER, 1);
    curl_setopt($cliente, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($cliente, CURLOPT_RETURNTRANSFER, TRUE);
    $result = curl_exec($cliente);
    curl_close($cliente);

    //Filter query
    $result = explode("{", $result, 2)[1];
    $result = "{" . $result;
    $result = json_decode($result, 1)['data'][0];

    return $result;

}

function getAppToken(){

    // Do an inquiry
    $cliente = curl_init();
    curl_setopt($cliente, CURLOPT_URL, "https://id.twitch.tv/oauth2/token?client_id=" . $_SESSION['client_id'] . "&client_secret=" . $_SESSION['client_secret'] ."&grant_type=client_credentials");
    curl_setopt($cliente, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($cliente, CURLOPT_POST, TRUE);
    $result = curl_exec($cliente);
    curl_close($cliente);

    //Filter query
    $result = json_decode($result, 1)["access_token"];

    return $result;

}

function getFollows(){

    //Set the headers for a query
    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $_SESSION['app_token'],
        'Client-ID: '. $_SESSION['client_id']
    ];

    // Do an inquiry
    $cliente = curl_init();
    curl_setopt($cliente, CURLOPT_URL, "https://api.twitch.tv/helix/users/follows?first=100&from_id=" . $_SESSION['user']['id']);
    curl_setopt($cliente, CURLOPT_HEADER, 1);
    curl_setopt($cliente, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($cliente, CURLOPT_RETURNTRANSFER, TRUE);
    $result = curl_exec($cliente);
    curl_close($cliente);

    //Filter query
    $result = explode("{", $result, 2);
    $result = "{" . $result[1];
    $result = json_decode($result, 1);

    $result2 = $result['data'];
    
    while(count($result['data']) == 100){

        // Do an inquiry
        $cliente = curl_init();
        curl_setopt($cliente, CURLOPT_URL, "https://api.twitch.tv/helix/subscriptions?first=100&after=".$result['pagination']['cursor']."&from_id="  . $_SESSION['user']['id']);
        curl_setopt($cliente, CURLOPT_HEADER, 1);
        curl_setopt($cliente, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($cliente, CURLOPT_RETURNTRANSFER, TRUE);
        $result = curl_exec($cliente);
        curl_close($cliente);

        //Filter query
        $result = explode("{", $result, 2);
        $result = "{" . $result[1];
        $result = json_decode($result, 1);
        
        $result2 = array_merge($result2, $result['data']);
    
    }

    return $result2;
    
}

function getSubscriptions(){

    $return_result = [];

    //When we get the follows we check if we are subscribed
    for($i = 0; $i <= (count($_SESSION['follows'])-1); $i++){

        //Set the headers for a query
        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $_SESSION['app_token'],
            'Client-ID: '. $_SESSION['client_id']
        ];

        // Do an inquiry
        $cliente = curl_init();
        curl_setopt($cliente, CURLOPT_URL, "https://api.twitch.tv/helix/subscriptions?first=100&broadcaster_id=" . $_SESSION['follows'][$i]['to_id'] . "&user_id=" . $_SESSION['user']['id']);
        curl_setopt($cliente, CURLOPT_HEADER, 1);
        curl_setopt($cliente, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($cliente, CURLOPT_RETURNTRANSFER, TRUE);
        $result = curl_exec($cliente);
        curl_close($cliente);

        //Filter query
        $result = explode("{", $result, 2);
        $result = "{" . $result[1];
        $result = json_decode($result, 1);

        $result = $result['data'];

        if(!empty($result)){
            $count_array = count($return_result);
            $return_result[$count_array] = $result[0];
        }

    }

    return $return_result;

}

function getSubscribers(){

    //Set the headers for a query
    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $_SESSION['app_token'],
        'Client-ID: '. $_SESSION['client_id']
    ];

    // Do an inquiry
    $cliente = curl_init();
    curl_setopt($cliente, CURLOPT_URL, "https://api.twitch.tv/helix/subscriptions?first=100&broadcaster_id=" . $_SESSION['user']['id']);
    curl_setopt($cliente, CURLOPT_HEADER, 1);
    curl_setopt($cliente, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($cliente, CURLOPT_RETURNTRANSFER, TRUE);
    $result = curl_exec($cliente);
    curl_close($cliente);

    //Filter query
    $result = explode("{", $result, 2);
    $result = "{" . $result[1];
    $result = json_decode($result, 1);

    $return_result = $result['data'];
    
    while(count($result['data']) == 100 && isset($_GET['all_subs'])){

        // Do an inquiry
        $cliente = curl_init();
        curl_setopt($cliente, CURLOPT_URL, "https://api.twitch.tv/helix/subscriptions?first=100&after=".$result['pagination']['cursor']."&broadcaster_id="  . $_SESSION['user']['id']);
        curl_setopt($cliente, CURLOPT_HEADER, 1);
        curl_setopt($cliente, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($cliente, CURLOPT_RETURNTRANSFER, TRUE);
        $result = curl_exec($cliente);
        curl_close($cliente);

        //Filter query
        $result = explode("{", $result, 2);
        $result = "{" . $result[1];
        $result = json_decode($result, 1);
        
        $return_result = array_merge($return_result, $result['data']);
    
    }

    return $return_result;

}

function getUserRewards($connection, $user_rewards){

    //If the user starts a session, we load the necessary functions
    $sql = "SELECT * FROM rewards WHERE user_id = $user_rewards";
    $query = mysqli_query($connection, $sql);

    //If the streamer offers any reward we convert it into an array
    if(mysqli_num_rows($query)){

        $result = mysqli_fetch_all($query);

        //Now apply some filters that should only be met if we are not the creator of the reward
        if($user_rewards != $_SESSION['user']['id']){

            $count_result = count($result)-1;

            //If the reward is only for subscribers, we check if the user is subscribed
            for($i = 0; $i <= $count_result; $i++){

                //In the hypothetical case that the reward is not for followes or subscribers, we would discard it
                if($result[$i][5] == 0 && $result[$i][6] == 0){
                    unset($result[$i]);
                    continue;
                }
                
                //If the reward is only for subscribers, we check if the user is subscribed
                for($x = 0; $x <= (count($_SESSION['suscriptions'])-1); $x++){

                    if($result[$i][5] == 0 && $result[$i][6] == 1 && $result[$i][1] != $_SESSION['suscriptions'][$x]['broadcaster_id']){
                        unset($result[$i]);
                    }

                }

            }
        }

    }else{
        //If the streamer does not offer any reward we do not return null
        $result = null;
    }

    //If the reward is only for subscribers, we check if the user is subscribed
    if(is_array($result)){
        $result = array_values($result);
    }

    return $result;

}