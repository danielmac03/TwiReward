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
        <link rel="stylesheet" href="../resources/custom.css">

        <!-- Roboto Medium 500 -->
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@500&display=swap" rel="stylesheet">

    </head>

    <body>

        <nav class="container-fluid navbar row mx-0">
            
            <div class="col-md-6 text-center text-md-left">
                <a href="../index.php"><h1>TwiReward</h1></a>
            </div>

            <div class="col-md-6">

                <?php if (isset($_SESSION['user'])) : ?>
                    <img class="mr-1" width="6%" src="<?= $_SESSION['user']['profile_image_url'] ?>" alt="Profile image url <?= $_SESSION['user']['display_name'] ?>">
                    <span class="mr-2 text-white"><?= $_SESSION['user']['display_name'] ?></span>
                <?php endif; ?>

            </div>

        </nav>

        <div class="container mt-3 cookies_policy">
            
            <h2>Cookies Policy</h2>
            <hr>

            <p>Last updated: April 17, 2019</p>

            <p>TwiReward ("us", "we", or "our") uses cookies on the www.twireward.com website (the "Service"). By using the Service, you consent to the use of cookies.</p>

            <p>Our Cookies Policy explains what cookies are, how we use cookies, how third-parties we may partner with may use cookies on the Service, your choices regarding cookies and further information about cookies.</p>

            <h3>What are cookies</h3>
            <hr>

            <p>Cookies are small pieces of text sent to your web browser by a website you visit. A cookie file is stored in your web browser and allows the Service or a third-party to recognize you and make your next visit easier and the Service more useful to you.</p>

            <p>Cookies can be "persistent" or "session" cookies. Persistent cookies remain on your personal computer or mobile device when you go offline, while session cookies are deleted as soon as you close your web browser.</p>

            <h3>How TwiReward uses cookies</h3>
            <hr>

            <p>When you use and access the Service, we may place a number of cookies files in your web browser.</p>

            <p>We use cookies for the following purposes:</p>

            <ul>
                <li>
                    <p>To enable certain functions of the Service</p>
                </li>
                <li>
                    <p>To provide analytics</p>
                </li>
                <li>
                    <p>To store your preferences</p>
                </li>
            </ul>

            <p>We use both session and persistent cookies on the Service and we use different types of cookies to run the Service:</p>

            <ul>
                <li>
                    <p>Essential cookies. We may use cookies to remember information that changes the way the Service behaves or looks, such as a user's language preference on the Service.</p>
                </li>
                <li>
                    <p>Accounts-related cookies. We may use accounts-related cookies to authenticate users and prevent fraudulent use of user accounts. We may use these cookies to remember information that changes the way the Service behaves or looks, such as the "remember me" functionality.</p>
                </li>
                <li>
                    <p>Analytics cookies. We may use analytics cookies to track information how the Service is used so that we can make improvements. We may also use analytics cookies to test new advertisements, pages, features or new functionality of the Service to see how our users react to them.</p>
                </li>
            </ul>

            <h3>Third-party cookies</h3>
            <hr>

            <p>In addition to our own cookies, we may also use various third-parties cookies to report usage statistics of the Service, deliver advertisements on and through the Service, and so on.</p>

            <h3>What are your choices regarding cookies</h3>
            <hr>

            <p>If you'd like to delete cookies or instruct your web browser to delete or refuse cookies, please visit the help pages of your web browser.</p>

            <p>Please note, however, that if you delete cookies or refuse to accept them, you might not be able to use all of the features we offer, you may not be able to store your preferences, and some of our pages might not display properly.</p>

            <ul>
                <li>
                    <p>For the Chrome web browser, please visit this page from Google: <a href="https://support.google.com/accounts/answer/32050">https://support.google.com/accounts/answer/32050</a></p>
                </li>
                <li>
                    <p>For the Internet Explorer web browser, please visit this page from Microsoft: <a href="http://support.microsoft.com/kb/278835">http://support.microsoft.com/kb/278835</a></p>
                </li>
                <li>
                    <p>For the Firefox web browser, please visit this page from Mozilla: <a href="https://support.mozilla.org/en-US/kb/delete-cookies-remove-info-websites-stored">https://support.mozilla.org/en-US/kb/delete-cookies-remove-info-websites-stored</a></p>
                </li>
                <li>
                    <p>For the Safari web browser, please visit this page from Apple: <a href="https://support.apple.com/guide/safari/manage-cookies-and-website-data-sfri11471/mac">https://support.apple.com/guide/safari/manage-cookies-and-website-data-sfri11471/mac</a></p>
                </li>
                <li>
                    <p>For any other web browser, please visit your web browser's official web pages.</p>
                </li>
            </ul>

            <h3>Where can you find more information about cookies</h3>
            <hr>

            <p>You can learn more about cookies and the following third-party websites:</p>

            <ul>
                <li>
                    <p>AllAboutCookies: <a href="http://www.allaboutcookies.org/">http://www.allaboutcookies.org/</a></p>
                </li>
                <li>
                    <p>Network Advertising Initiative: <a href="http://www.networkadvertising.org/">http://www.networkadvertising.org/</a></p>
                </li>
            </ul><hr>
            
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

    </body>
</html>