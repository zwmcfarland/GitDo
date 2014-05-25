<!-- This is the Header, contains nav bar and script includes. -->
<?php
    include("HeaderFooter.php");
    incHeader("GitDo");
    if ($_SESSION['access_token'])
    {
        $redirect = "https://". $_SERVER['SERVER_NAME'] . "/ToDo.php";
        header("Location: $redirect");
    }
        
?>

<!-- Script --->
<script>
    $(document).on('click', '#btn_get_repos', function() {
        window.location = 'https://github.com/login/oauth/authorize?client_id=7e84085f49b17f9068d2&redirect_uri=https://<?php echo $_SERVER['SERVER_NAME']; ?>/access_token.php&scope=repo&state=asdfasdfasdf';
    });
    $(document).on('click', '#btn_no_repos', function() {
        window.location = 'ToDo.php';
    });
    $(document).on('click', '#title', function() {
        window.location = 'index.php';
    });
</script>
<!-- END: Script -->
<!-- style -->
<style>
    #title:hover{
        cursor: pointer;  
    }
</style>

<!-- Body --->
<div id='title'>
<h1><b>GitDo<font color="#5bc0de">es</font></b></h1>
</div>
<div style="margin-top:15%" align="center">
    <h3> Generate a clean, simple to-do list right in your GitHub repo<h3></h3>
    <button type="button" id="btn_get_repos" class="btn btn-info btn-lg btn-block" style="display:inline-block; width:20%;">Authorize with GitHub</button>
    <button type="button" id="btn_no_repos" class="btn btn-lg btn-block" style="display:inline-block; width:20%;margin-top: 0px !important;">Continue Without Authorization</button>
    <p style="margin-top:5px;">Note: Continuing without authorizing will restrict you from posting directly to your repos.</p>
</div>

<!-- Footer This contains the closing of html and stuffs. -->
<?php
    incFooter();
?>
