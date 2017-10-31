<?php
#######################################
#######################################
#####      DATABASE FUNCTIONS     #####
#######################################
#######################################
function db_connect($username, $database, $password = null, $hostname = "job-applier.mysql.database.azure.com")
{    
    // connect to the mysql database
    $conn = new mysqli($hostname, $username, db_credentials($username), $database);

    // check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }// end if there was an error connecting to database
    
    $GLOBALS['db_most_recent_conn'] = $conn;
    return $conn;
}// end function db_connect()

function query($sql, $conn = false)
{
    if(!$conn) {
        $conn = most_recent_connection();
    }// end if there has

    return $conn->query($sql);
}// end function db_query

function fetch($result)
{
    return $result->fetch_assoc();
}// end function fetch

function escape($string, $conn = false)
{
    if(!$conn) {
        $conn = most_recent_connection();
    }// end if there has

    return $conn->real_escape_string($string);
}// end function for escaping a string

function most_recent_connection()
{
    // return the most recent database connection
    if(isset($GLOBALS['db_most_recent_conn'])) {
        $conn = $GLOBALS['db_most_recent_conn'];
    }// end if there is a recent database connection to use
    
    if(!$conn) {
        die("No connection established.");
    }// end if there has

    return $conn;
}// end function most_recent_connection()

function db_credentials($username)
{
    $credentials = array ("dbadmin@job-applier" => 'qrZy5HZYrd');
    return $credentials[$username];
}// end function db_credentials