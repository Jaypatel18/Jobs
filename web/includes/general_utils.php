<?php
#######################################
#######################################
#####      GENERAL FUNCTIONS      #####
#######################################
#######################################
function get($index, $arr = null, $alt_value = null)
{
    // if an array is not provided, use the most recent $_REQUEST array
    // instead of giving us an undefined index error, this returns an empty strin
    if($arr === null) {
        $arr = $_REQUEST;
    }// end if array provided is null

    return isset($arr[$index]) ? $arr[$index] : $alt_value;
}// end function get
