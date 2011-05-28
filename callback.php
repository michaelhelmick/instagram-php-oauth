<?php
session_start();
require_once 'src/config.php';
require_once 'src/Instagram.php';

$instagram = new Instagram(CLIENT_ID, CLIENT_SECRET, null);

if(isset($_GET['error']) || isset($_GET['error_reason']) || isset($_GET['error_description'])){
    // Throw error message... the user might have pressed Deny.
}

// If there is no error, we should try and grab an access token that we can store in a database or something
// for future use if the user revisits our application
/*
/   Pass the code that you get back from the Authorization call and pass the Redirect URI
/   There is a third parameter "grant_type" which defaults to "authorization_code" (limited by Instagram, currently)
*/
$access_token = $instagram->getAccessToken($_GET['code'], REDIRECT_URI);
$_SESSION['access_token'] = $access_token->access_token;

// Do what you want with the access token, maybe store it in a database?
// Close window or redirect the user back to our index.php so we can pull in some data.

header("Location: index.php");
//exit('<script> window.close(); </script>');
?>