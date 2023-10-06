<?php
  // FCM API Url
  $url = 'https://fcm.googleapis.com/fcm/send';
  // Put your Server Response Key here
  $apiKey = "AAAAGmIejYs:APA91bH60UYqkiT8h2aVOrbzUJwibOcu6y0TLpBYG8ukVsETMNFuSSo1RooTblWLwY7_buCFrIwM5J55CGh7SfNoTjBd_oWzuiy-Pg5SV24G1dJUMe461PxYwWQB50DO9gZNAF9fYYBl";
  $json = json_decode(file_get_contents('php://input'));
  // Compile headers in one variable
  $headers = array (
    'Authorization:key=' . $apiKey,
    'Content-Type:application/json'
  );

  // Add notification content to a variable for easy reference
  $notifData = [
    'title' => "New Order",
    'body' => "You have new order",
    'click_action' => "android.intent.action.MAIN",
    'tag' => $json->resource_url
  ];

  // Create the api body
  $apiBody = [
    'notification' => $notifData,
    'data' => $notifData,
    "time_to_live" => 600, // Optional
    'to' => '/topics/'.$_GET['android_id']
  ];

  // Initialize curl with the prepared headers and body
  $ch = curl_init();
  curl_setopt ($ch, CURLOPT_URL, $url );
  curl_setopt ($ch, CURLOPT_POST, true );
  curl_setopt ($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true );
  curl_setopt ($ch, CURLOPT_POSTFIELDS, json_encode($apiBody));

  // Execute call and save result
  curl_exec ( $ch );

  // Close curl after call
  curl_close ( $ch );
?>
