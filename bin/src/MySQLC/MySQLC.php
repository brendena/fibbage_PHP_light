<?php

namespace Chat\MySQLC;


class MySQLC implements MySQLInterface
{
    $link;
    
    public function __construct()
    {
        $host = "localhost";
        $user = "brenden";
        $pass = "password";
        $db = "fibbage";

        $this->$link = mysqli_connect($host,$user,$pass,$db); 

        if ($link->connect_errno>0) {
            die('Could not connect: ' . $db->error ); 

        }

        $db_selected = mysqli_select_db($link, $db); 
        if (!$db_selected) {
            die ('Can\'t use database $db : ' . $db->error); 
        }
    }

   
}
