<?php
include_once("general_utils.php");

if(!isset($header_config) || !is_array($header_config)){
    $header_config = array();
}// end if the header configuration is not set
?>
<head>
    <meta charset="UTF-8">
    <title><?= get('page_title', $header_config, 'Title') ?></title>
    <link rel="stylesheet" type="text/css" href="global/css/bootstrap.min.css">
    <script src="global/js/jquery-3.2.1.min.js"></script>
    <script src="global/js/tether.min.js"></script>
    <script src="global/js/bootstrap.min.js"></script>
</head>