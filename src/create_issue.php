<?php
   date_default_timezone_set('UTC');
   session_start();
   $userName  = $_POST['userName'];
   $issueName = $_POST['issueName'];
   $body      = $_POST['body'];
   $repoName  = $_POST['repoName'];
   $data['title'] = $issueName;
   $data['body']  = $body;
   
   $data = json_encode($data);
   exec('curl -H "Authorization: token ' . $_SESSION['access_token'] . '" -X POST -i -d \''. $data . '\' https://api.github.com/repos/' . $userName . '/' . $repoName . '/issues', $result);
   echo json_encode($result);
?>