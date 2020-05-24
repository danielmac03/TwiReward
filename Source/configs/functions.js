'use strict'

//When logging in with Twitch we must clean the url so that php can recognize the parameters
if (document.location.href.search("#") != -1) {
    location.replace(document.location.href.replace("#", "?"));
}

//When refresh_followsubs exists, remove that parameter from the url, to avoid problems when reloading
if (document.location.href.search("\\?refresh_followsubs") != -1) {
    history.pushState(null, "", "index.php")
}