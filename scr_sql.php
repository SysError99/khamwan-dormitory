<?php
    $sql_host = "localhost";
    $sql_username = "root";
    $sql_password = "";
    $sql_dbname = "apartment";
    
    $sql = new mysqli($sql_host, $sql_username, $sql_password, $sql_dbname);
    
    if(mysqli_connect_error()){
        echo "<script type=\"text/javascript\">alert('Database connection failed.');</script>";
    }
?>
