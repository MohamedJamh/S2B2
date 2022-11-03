<?php
    // CONNECT TO MYSQL DATABASE USING MYSQLI
    try{
        $cnx  = mysqli_connect('localhost','root','','youcodescrumboard');
    }catch(Exception $e){
        // MANAGE EXCEPTION
        die('something went worng :   ' . $e->getMessage());
    }
?>