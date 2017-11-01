<?php
#######################################
#######################################
#####           API UTILS         #####
#######################################
#######################################
function request($url, $type = 'GET', $body = null, $headers = null, $return_body = 1)
{ 
    // create curl resource 
    $ch = curl_init(); 

    // set url 
    curl_setopt($ch, CURLOPT_URL, $url); 

    if($headers != null){
        // set the header
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }// end if we have headers to send over
   
    if(strtolower($type) === 'post') {
        // set body of request if POST
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($body));
    }// end if sending a post request
    
    //return the transfer as a string 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, $return_body); 

    // $output contains the output string 
    $output = curl_exec($ch); 

    echo curl_error ($ch);
    
    // close curl resource to free up system resources 
    curl_close($ch);     

    return $output;
}// end function request