<?php

namespace Chat\MySQLC;


class MySQLC 
{
    private $link;
    
    public function __construct()
    {
        $host = "localhost";
        $user = "brenden";
        $pass = "password";
        $db = "fibbage";
        
        $this->link = mysqli_connect($host,$user,$pass,$db); 
        
        if ($this->link->connect_errno>0) {
            die('Could not connect: ' . $db->error ); 

        }
        else{
            print("connected to server \n");
        }

        $db_selected = mysqli_select_db($this->link, $db); 
        if (!$db_selected) {
            die ('Can\'t use database $db : ' . $db->error); 
        }else{
            print("connected to database \n");
        }
        
       
    }

   public function getQuestion($topic = "question"){
       $query= "Select question from Question";
       $result = mysqli_query($this->link, $query);
       
       while($row = mysqli_fetch_array($result))
       {
           print($row['question']);
           print("\n");
           return($row['question']);
       }
       #mysqli_close($this->link);
   }
}
