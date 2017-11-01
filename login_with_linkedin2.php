<?php
#######################################
#######################################
#####   LINKEDIN LANGING PAGE     #####
#######################################
#######################################

#######################################
#####           INCLUDES          #####
#######################################
include("db_utils.php");
include("general_utils.php");
include("api_utils.php");

#######################################
#####           BODY              #####
#######################################
$client_id = '78gx3kdyhwup6v';
$client_secret = 'dICXfIKRurHI5XWV';

$req_data = array(
    'grant_type' => 'authorization_code',
    'code'       =>  get('code'),
    'redirect_uri' => 'http://localhost/Jobs/login_with_linkedin2.php',
    'client_id' => $client_id,
    'client_secret' => $client_secret
);

$headers = array('Content-Type: application/x-www-form-urlencoded');
$access_token_url = 'https://www.linkedin.com/oauth/v2/accessToken';

// POST request to retreieve the linkedin API access token
$response = request($access_token_url, 'POST', $req_data, $headers);
$response = json_decode($response, true);

echo "Linkedin API Access token: " . get('access_token', $response, 'error retreiving the access token');