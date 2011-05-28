<?php
session_start();
require_once 'src/config.php';
require_once 'src/Instagram.php';

$access_token = isset($_SESSION['access_token']) ? $_SESSION['access_token'] : null;
$instagram = new Instagram(CLIENT_ID, CLIENT_SECRET, $access_token);

if(!$access_token){
    // If there is no access token in the session, let's have the user authenticate our application...
    /*
    /   You pass the Redirect Uri you registered with your app and an array of "scope" (aka permissions) you
    /   want to grab from the user. There is also a third parameter "response_type" which defaults to "code"
    */
    $loginUrl = $instagram->authorizeUrl(REDIRECT_URI, array('basic', 'comments', 'likes', 'relationships'));
} else {    
    try {
        $feed = $instagram->get('users/self/feed');
    }catch(InstagramApiError $e) {
        die($e->getMessage());
    }
}
?>

<?php if(isset($loginUrl)): ?>
<a href="<?php echo $loginUrl; ?>">Log in</a>
<?php else: ?>
    <ul>
    <?php foreach($feed->data as $item): ?>
        <li>
            ID: <?php echo $item->id; ?><br />
            <img src="<?php echo $item->images->low_resolution->url; ?>" alt="" /><br />
            By: <?php echo $item->user->username; ?>
        </li>
    <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?php
    try{
        // Like or unlike a photo
        $instagram->post('media/SOME-ID-HERE/likes');
        //$instagram->delete('media/SOME-ID-HERE/likes');
    }catch(InstagramApiError $e) {
        die($e->getMessage());
    }
?>