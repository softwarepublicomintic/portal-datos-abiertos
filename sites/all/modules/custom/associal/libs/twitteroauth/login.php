<?php
session_start();
require_once('twitteroauth.php');
//include('config.php');
$CONSUMER_KEY = $_GET['twkey'];
$CONSUMER_SECRET = $_GET['twsecret'];
$OAUTH_CALLBACK = $_GET['twcb'];


if(isset($_SESSION['name']) && isset($_SESSION['twitter_id'])) //check whether user already logged in with twitter
{

	echo "Name :".$_SESSION['name']."<br>";
	echo "Twitter ID :".$_SESSION['twitter_id']."<br>";
	echo "Image :<img src='".$_SESSION['image']."'/><br>";
	echo "<br/><a href='logout.php'>Logout</a>";


}
else // Not logged in
{
  print $CONSUMER_KEY . " ; ". $CONSUMER_SECRET." ; ".$OAUTH_CALLBACK;
	$connection = new TwitterOAuth($CONSUMER_KEY, $CONSUMER_SECRET);
	$request_token = $connection->getRequestToken($OAUTH_CALLBACK); //get Request Token

	$user_info = $connection->get('account/verify_credentials');

	if(	$request_token)
	{
		$token = $request_token['oauth_token'];


		$_SESSION['request_token'] = $token ;
		$_SESSION['request_token_secret'] = $request_token['oauth_token_secret'];

		switch ($connection->http_code)
		{
			case 200:
				$url = $connection->getAuthorizeURL($token);
				//redirect to Twitter .
		   // 	header('Location: ' . $url);
			
			    break;
			default:
			    echo "Coonection with twitter Failed";
		    	break;
		}

	}
	else //error receiving request token
	{
		echo "Error Receiving Request Token";
	}


}



?>
