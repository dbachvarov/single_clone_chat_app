<?php
    /**
     * Created by PhpStorm.
     * User: danielbachvarov
     * Date: 14/03/2020
     * Time: 13:58
     */
    // db configuration
    $dbHost = "mysqlsrv.dcs.bbk.ac.uk";
    $dbUsername = "dbachv01";
    $dbPassword = "bbkmysql";
    $dbName = "dbachv01db";
    
  
    // Create db connection
    $db =new mysqli($dbHost,$dbUsername, $dbPassword, $dbName);
    
    // Check connection
    if($db->connect_error){
        echo "Connection failed: ". $db->connect_error;
    }
    else{
        echo "connected";
    }
    
    ?>
