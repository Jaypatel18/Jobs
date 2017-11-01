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
#####           INCLUDES          #####
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
                         'industry', 'summary', 'specialties', 'positions', 'picture-url');

// GET request using the access token we just got
// add the access token to the header of the GET request
$api_url = 'https://api.linkedin.com/v1/people/~:('.implode(',', $linkedin_fields).')?format=json';
$headers = array('Authorization: Bearer ' . get('access_token', $_SESSION));
$response = request($api_url, 'GET', null, $headers);
$response = json_decode($response, true);

if(get('status', $response) != '401'){
?>
<table class="table table-inverse">
  <tbody>
    <tr>
      <td>First Name</td>
      <td><?= $response['firstName'] ?></td>
    </tr>
    <tr>
      <td>Last name</td>
      <td><?= $response['lastName'] ?></td>
    </tr>
    <tr>
      <td>Headline</td>
      <td><?= $response['headline'] ?></td>
    </tr>
    <tr>
      <td>Indsutry</td>
      <td><?= $response['industry'] ?></td>
    </tr>
    <tr>
      <td>Summary</td>
      <td><?= $response['summary'] ?></td>
    </tr>
  </tbody>
</table>
<?php
}else{
    var_dump($response);
}// end if we don't have a 401 error
