<?php

//Upload the required files
include_once 'configs/functions.php';
include_once 'configs/connection.php';

//When we click log out and php detects it, we destroy all sessions and refresh the page.
if (isset($_GET['log_out'])) {
    //Unset and session_destroy are used to avoid reloading the page
    unset($_SESSION);
    session_destroy();
}

//Set necessary variables
$_SESSION['client_id'] = null;
$_SESSION['client_secret'] = null;
$redirect_url = 'https://' . $_SERVER['HTTP_HOST'];

//If the user does not authorize the application it shows an error
if (isset($_GET['error_description']) && $_GET['error_description'] == "The user denied you access") {
    $_SESSION['errors'] = "If you don't log in with twitch you can't use the app";
}

//In this way, only update the information of subscribers, follows and subscriptions, 
//when the user needs them and the page will be faster.
if(isset($_GET['refresh_followsubs']) || !isset($_SESSION['app_token'])){
    //If you have logged in with twitch it get the access_token and the user
    if (isset($_GET['access_token'])) {
        $_SESSION['access_token'] = $_GET['access_token'];
        $_SESSION['user'] = getUser(null);
    } elseif (isset($_SESSION['access_token'])) {
        $_SESSION['user'] = getUser(null);
    }

    //If the user starts a session, it load the necessary functions
    if(isset($_SESSION['user'])){
        $_SESSION['app_token'] = getAppToken();
        $_SESSION['subscribers'] = getSubscribers();
        $_SESSION['follows'] = getFollows();
        $_SESSION['suscriptions'] = getSubscriptions($db);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

    <head>
    
        <title>TwiReward</title>

        <meta charset="UTF-8">
        <meta name="title" content="TwiReward">
        <meta name="description" content="TwiReward allows twitch streamers to reward their followers for following him on his streamings.">
        <meta name="keywords" content="Twitch, rewards">
        <meta name="robots" content="index, follow, all">
        <meta name="google" content="notranslate">
        <meta http-equiv="Content-Language" content="en">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">

        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

        <!-- Popper JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

        <!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

        <!-- Custom CSS -->
        <link rel="stylesheet" href="resources/custom.css">

        <!-- Functions JS -->
        <script src="configs/functions.js"></script>

        <!-- Icons Font Awesome -->
        <script src='https://kit.fontawesome.com/a076d05399.js'></script>

        <!-- Roboto Medium 500 -->
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@500&display=swap" rel="stylesheet">

        <!-- Conditions for bootstrap modals -->
        <?php if (isset($_GET['user_rewards'])) : ?>
            <script type="text/javascript">
                $(window).on('load', function() {
                    $('#user_rewards').modal('show');
                });
            </script>
        <?php endif; ?>

        <?php if (isset($_SESSION['errors_create_reward'])) : ?>
            <script type="text/javascript">
                $(window).on('load', function() {
                    $('#create_reward').modal('show');
                });
            </script>
        <?php endif; ?>

    </head>

    <body>

        <nav class="container-fluid navbar row mx-0">
            
            <div class="col-md-6 text-center text-md-left">
                <a href="index.php"><h1 class="text-xs-center">TwiReward</h1></a>
            </div>

            <div class="col-md-6 px-0 text-center text-md-right">

                <?php if (isset($_SESSION['user'])) : ?>
                    <img class="mr-1 profile_picture" width="6%" src="<?= $_SESSION['user']['profile_image_url'] ?>" alt="Profile image url <?= $_SESSION['user']['display_name'] ?>">
                    <span class="mr-2 text-white"><?= $_SESSION['user']['display_name'] ?></span>
                    <a class="btn" href="" data-toggle="modal" data-target="#create_reward"><i class="fas fa-plus"></i></a>

                    <?php if(isset($_GET['user_rewards']) && $_GET['user_rewards'] == $_SESSION['user']['id']): ?>
                        <a class="btn" href="" data-toggle="modal" data-target="#user_rewards"><i class="fas fa-star"></i></a>
                    <?php else: ?>
                        <a class="btn" href="index.php?user_rewards=<?= $_SESSION['user']['id'] ?>"><i class="fas fa-star"></i></a>
                    <?php endif; ?>

                    <a class="btn" href="index.php?log_out"><i class="fas fa-power-off"></i></a>
                <?php endif; ?>

            </div>

        </nav>

        <div class="container mt-3">

            <?php if (isset($_SESSION['completed'])) : ?>
                <div class="alert alert-success alert-dismissible mx-auto">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <?= $_SESSION['completed'] ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['errors'])) : ?>
                <div class="alert alert-success alert-danger mx-auto">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <?= $_SESSION['errors'] ?>
                </div>
            <?php endif; ?>

            <?php if (!isset($_SESSION['user'])) : ?>
                
                <div class="login_twitch text-center">
                    <a href="https://id.twitch.tv/oauth2/authorize?client_id=<?= $_SESSION['client_id'] ?>&redirect_uri=<?= $redirect_url ?>&response_type=token&scope=channel:read:subscriptions"><i class="fab fa-twitch mr-2"></i>Login with Twitch</a>
                </div>

            <?php endif; ?>

            <?php if (isset($_SESSION['user'])) : ?>

                <div class="row">

                    <div class="col-md-8">

                        <div class="mb-3">
                            <h2 class="d-inline">Rewards:</h2>
                            <a class="btn float-right" href="index.php?refresh_followsubs" data-toggle="tooltip" title="If you click, we will update the information of subscribers, follows and subscriptions">Refresh</a>
                            <hr>
                        </div>

                        <?php
                        for ($i = -1; $i <= (count($_SESSION['follows'])-1); $i++) :

                            //In this way we can see our rewards in the feed
                            if($i == -1){
                                $streamerRewards = getUserRewards($db, $_SESSION['user']['id']);
                            }else{
                                $streamerRewards = getUserRewards($db, $_SESSION['follows'][$i]['to_id']);
                            }

                            if(!empty($streamerRewards)):
                                //If at any time a match has been found
                                $rewards = true;
                                $streamer = getUser($streamerRewards[0][1]);
                        ?>
                            <div class="card mb-4">

                                <?php if(!empty($streamer['offline_image_url'])): ?>
                                    <img class="card-img-top" src="<?= $streamer['offline_image_url'] ?>" alt="Offline image url <?= $streamer['display_name'] ?>">
                                <?php endif; ?>

                                <div class="card-body">

                                    <h4 class="card-title"><?= $streamer['display_name'] ?></h4>
                                    <p class="card-text"><?= $streamer['description'] ?></p>

                                    <?php if(isset($_GET['user_rewards']) && $_GET['user_rewards'] == $streamer['id']): ?>
                                        <a class="btn" href="" data-toggle="modal" data-target="#user_rewards"><i class="fas fa-star mr-2"></i>Rewards</a>
                                    <?php else: ?>
                                        <a href="index.php?user_rewards=<?= $streamer['id'] ?>" class="btn"><i class="fas fa-star mr-2"></i>Rewards</a>
                                    <?php endif; ?>

                                    <a href="https://www.twitch.tv/<?= $streamer['display_name'] ?>" rel="noopener" target="_blank" class="btn"><i class="fab fa-twitch mr-2"></i>Watch</a>

                                </div>

                            </div>

                        <?php endif; endfor; ?>

                        <!-- Check if any reward has been found and if not, print a message -->
                        <?php if(!isset($rewards)): ?>
                            <label>There are no rewards for you, come back later</label>
                        <?php endif; ?>

                    </div>


                    <div id="subscribers" class="col-md-4 pl-0 d-none d-xs-block d-md-block">

                        <div class="ml-4">
                        
                            <h2>Subscribers:</h2>

                            <ul>

                                <?php if (empty($_SESSION['subscribers'])) : ?>

                                    <li>You have no subscribers yet</li>

                                <?php else : ?>

                                    <?php for ($i = 0; $i <= (count($_SESSION['subscribers']) - 1); $i++) : ?>
                                        <li><?= $_SESSION['subscribers'][$i]['user_name'] ?></li>
                                    <?php endfor; ?>

                                    <p>Total: <?= count($_SESSION['subscribers']) ?></p>

                                    <?php if (!isset($_GET['all_subs'])) : ?>
                                        <a href="index.php?follows&all_subs" class="btn mt-1">See more</a>
                                    <?php else : ?>
                                        <a href="index.php?follows" class="btn mt-1">See less</a>
                                    <?php endif; ?>

                                <?php endif; ?>

                            </ul>

                        </div>

                    </div>

                <?php endif; ?>

            </div>

        </div>

        <div class="container-fluid footer">

            <div class="float-left">
                <label>This site uses cookies</label>
            </div>

            <div class="float-right d-none d-xl-block d-lg-block d-md-block">
                <a href="/pages/cookies_policy.php">Cookies Policy</a>
                <a class="mx-4" href="mailto:contact@twireward.com">Contact</a>
                <a href="https://github.com/danielmac03/TwiReward">Github</a>
            </div>

        </div>

        <div id="create_reward" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">

                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Create reward</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form action="/validate_data/create_reward.php" method="post">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Name" name="name" required>
                                <?php echo isset($_SESSION['errors_create_reward']) ? showErrors($_SESSION['errors_create_reward'], 'name') : ''; ?>
                            </div>

                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Content" name="content" required>
                                <?php echo isset($_SESSION['errors_create_reward']) ? showErrors($_SESSION['errors_create_reward'], 'content') : ''; ?>
                            </div>

                            <div class="form-group mt-3 mx-5 row">

                                <div class="col-md-6">

                                    <label>Type of content:</label>

                                    <div class="form-group form-check">
                                        <label class="pl-3 form-check-label">
                                            <input class="form-check-input"  type="radio" name="type" value="text" checked required>Text
                                        </label>
                                    </div>

                                    <div class="form-group form-check">
                                        <label class="pl-3 form-check-label">
                                            <input class="form-check-input"  type="radio" name="type" value="link">Link
                                        </label>
                                    </div>

                                    <div class="form-group form-check">
                                        <label class="pl-3 form-check-label">
                                            <input class="form-check-input"  type="radio" name="type" value="yt_link">Youtube link
                                        </label>
                                    </div>

                                    <?php echo isset($_SESSION['errors_create_reward']) ? showErrors($_SESSION['errors_create_reward'], 'type') : ''; ?>

                                </div>

                                <div class="col-md-6">

                                    <label>Who is the reward for:</label>

                                    <div class="form-group form-check">
                                        <label class="pl-3 form-check-label">
                                            <input class="form-check-input" type="checkbox" name="follows">Twitch follow
                                        </label>
                                    </div>

                                    <div class="form-group form-check">
                                        <label class="pl-3 form-check-label">
                                            <input class="form-check-input" type="checkbox" name="subscribers">Twitch subscriber
                                        </label>
                                    </div>

                                    <?php echo isset($_SESSION['errors_create_reward']) ? showErrors($_SESSION['errors_create_reward'], 'filter') : ''; ?>

                                </div>

                            </div>

                            <button type="submit" class="btn float-right">Create</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div id="user_rewards" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">

                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Rewards</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <?php
                    $rewards = getUserRewards($db, $_GET['user_rewards']);
                    ?>

                    <div class="modal-body">

                        <?php if($rewards == null): ?>

                            <p>This streamer has no rewards</p>

                        <?php else: ?>

                            <?php for ($i = 0; $i <= (count($rewards) - 1); $i++) : ?>

                                <div class="mb-4">

                                    <?php if($rewards[$i][1] == $_SESSION['user']['id']): ?>
                                        <a class="float-right" href="/validate_data/delete_reward.php?reward_id=<?=$rewards[$i][0]?>"><i class="fas fa-trash-alt"></i></a>
                                    <?php endif; ?>

                                    <p><?= $rewards[$i][2] ?></p>

                                    <?php if ($rewards[$i][4] == 'text') : ?>
                                        <p><?= $rewards[$i][3] ?></p>
                                    <?php elseif ($rewards[$i][4] == 'link') : ?>
                                        <a class="btn" href="<?= $rewards[$i][3] ?>" rel="noopener" target="_blank">Open Link</a>
                                    <?php elseif ($rewards[$i][4] == 'yt_link') : ?>
                                        <div class="embed-responsive embed-responsive-16by9">
                                            <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?=$rewards[$i][3]?>" frameborder="0" allow="accelerometer; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                        </div>
                                    <?php endif; ?>

                                    <hr>
                                    
                                </div>

                            <?php endfor; ?>

                        <?php endif; ?>

                    </div>
                </div>
            </div>
            
        </div>

        <?php deleteErrors(); ?>

    </body>

</html>