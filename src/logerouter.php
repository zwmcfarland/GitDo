<?php 
    session_start();
    $_SESSION['access_store'] = "";
    session_destroy(); 
    header( 'Location: http://' . $_SERVER['SERVER_NAME']) ;
?>