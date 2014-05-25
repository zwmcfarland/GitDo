<?php
        session_start();
        date_default_timezone_set('UTC');
        function incHeader($title = "", $JS_String = "", $JS_Include = "", $CSS_Include = "", $CSS_String = "", $MenuFlag = "true")
        {

                echo '<!DOCTYPE html>
                                <html> 
                                        <head>
                                            <meta charset="utf-8">
                                            <title>'. $title .'</title>
                                            <meta content="width=device-width, initial-scale=1.0" name="viewport">
                                            <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
                                            <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>   
                                            <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>';

                //CSS Includes
                foreach(explode(',',$CSS_Include) as $path)
                {
                        echo '<link rel="stylesheet" type="text/css" href="' . $path . '">';
                }
                echo '<style type="text/css">' . $CSS_String . '</style>';
                //END: CSS Includes

                echo    '</head>
                                <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">';

                //JS Includes
                foreach(explode(',',$JS_Include) as $path)
                {
                            echo '<script src="' . $path . '"></script>';
                }
                echo '<script type="text/javascript">' . $JS_String . '</script>';
                //END: JS Includes
                echo '<body class="'. $title .'">
                                <div class="container-fluid">
                                        <div class="center-container row-fluid">
                                        <div class="span12">';
                 
        }

        function incFooter()
        {
                echo '                                          </div>
                                                                </div>
                                                        </div>
                                        </body>
                                </html>';
        }


?>
                                                                                                                                
