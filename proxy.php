<?php

  // Set your YELP keys here
  $consumer_key = "SWJKI1PeIPjMduaeOxIktw";
  $consumer_secret = "wlDY1cOrC1pJEqD3xHFD-8vmp64";
  $token = "-7FBZ8oz26djYJBK-rc8fICXNDAOX0Mr";
  $token_secret = "g13gTqSjxE90PjZd5dphubSEXZs";

  require_once ('OAuth.php');
  header("Content-type: application/json\n\n");
  $params = $_SERVER['QUERY_STRING'];
  $unsigned_url = "http://api.yelp.com/v2/search?$params";
  $token = new OAuthToken($token, $token_secret);
  $consumer = new OAuthConsumer($consumer_key, $consumer_secret);
  $signature_method = new OAuthSignatureMethod_HMAC_SHA1();
  $oauthrequest = OAuthRequest::from_consumer_and_token($consumer, $token, 'GET', $unsigned_url);
  $oauthrequest->sign_request($signature_method, $consumer, $token);
  $signed_url = $oauthrequest->to_url();
  $ch = curl_init($signed_url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  $data = curl_exec($ch);
  curl_close($ch);
  print_r($data);

?>
