<?php
#######################################
#######################################
#####   LINKEDIN LANGING PAGE     #####
#######################################
#######################################
session_start();

#######################################
#####           INCLUDES          #####
#######################################
include("db_utils.php");
include("general_utils.php");
include("api_utils.php");

#######################################
#####           DATABASE          #####
#######################################
db_connect('dbadmin@job-applier', 'auth');

#######################################
#####           HEADER            #####
#######################################
include_once('header.php');

#######################################
#####           BODY              #####
#######################################
$client_id = '78gx3kdyhwup6v';
$client_secret = 'dICXfIKRurHI5XWV';
$base_url = 'http://localhost';

$req_data = array (
    'grant_type'     => 'authorization_code',
    'code'           =>  get('code'),
    'redirect_uri'   =>  $base_url . $_SERVER['PHP_SELF'],
    'client_id'      =>  $client_id,
    'client_secret'  =>  $client_secret
);

$headers = array('Content-Type: application/x-www-form-urlencoded');
$access_token_url = 'https://www.linkedin.com/oauth/v2/accessToken';

// POST request to retreieve the linkedin API access token
$response = request($access_token_url, 'POST', $req_data, $headers);
$response = json_decode($response, true);
$access_token = get('access_token', $response);

if($access_token != null) {
    // set the user's access token in the session
    $_SESSION['access_token'] = $access_token;
}// end if we generated a new access token

$linkedin_fields = array('id', 'first-name', 'last-name', 'headline', 'location', 
                         'industry', 'summary', 'specialties', 'positions', 'picture-url', 'email-address',
                         'public-profile-url');

// GET request using the access token we just got
// add the access token to the header of the GET request
$api_url = 'https://api.linkedin.com/v1/people/~:('.implode(',', $linkedin_fields).')?format=json';
$headers = array('Authorization: Bearer ' . get('access_token', $_SESSION));
$response = request($api_url, 'GET', null, $headers);
$response = json_decode($response, true);

if(get('status', $response) != '401'){
  $position = $response['positions']['values'][0];
  $sql = "INSERT IGNORE INTO users SET 
                              linkedin_id = '".escape($response['id'])."',
                              first_name = '".escape($response['firstName'])."',
                              last_name = '".escape($response['lastName'])."',
                              email_address = '".escape($response['emailAddress'])."',
                              summary = '".escape($response['summary'])."',
                              headline = '".escape($response['headline'])."',
                              country_code = '".escape($response['location']['country']['code'])."',
                              location = '".escape($response['location']['name'])."',
                              industry = '".escape($response['industry'])."',
                              picture_url = '".escape($response['pictureUrl'])."',
                              linkedin_profile_url = '".escape($response['publicProfileUrl'])."',
                              position_id_1 = '".escape($position['id'])."',
                              company_id_1 = '".escape($position['company']['id'])."',
                              company_industry_1 = '".escape($position['company']['industry'])."',
                              company_name_1 = '".escape($position['company']['name'])."',
                              company_size_1 = '".escape($position['company']['size'])."',
                              company_type_1 = '".escape($position['company']['type'])."',
                              company_is_current_1 = '".escape($position['isCurrent'] ? 'Y' : 'N')."',
                              company_location_1 = '".escape($position['location']['name'])."',
                              company_start_1 = '".escape($position['startDate']['year'] . '-' . $position['startDate']['month'] . '-01')."',
                              company_summary_1 = '".escape($position['summary'])."',
                              company_title_1 = '".escape($position['title'])."'";
  if(query($sql)){
    echo "Successfully added user to database";
  }// end if query success
}else{
    var_dump($response);
}// end if we don't have a 401 error
