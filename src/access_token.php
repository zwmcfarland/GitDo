<?php
    session_start();
    $code  = $_GET['code'];
    $url = 'https://github.com/login/oauth/access_token';
    $data = array('client_id' => '7e84085f49b17f9068d2', 'client_secret' => '5ea4cf7fd9db2378131bc52d821515ebe68db439', 'code' => $code , 'redirect_uri' => 'http://' . $_SERVER['SERVER_NAME'] . '/access_token.php');

    // use key 'http' even if you send the request to https://
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
        ),
    );
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    
    preg_match("/access_token=(.*?)&/", $result, $matches);
    $_SESSION['access_token'] = $matches[1];
    $redirect = "http://". $_SERVER['SERVER_NAME'] . "/ToDo.php";
    header("Location: $redirect");
?>