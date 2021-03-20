<?php
    include_once 'connection.php';
    class Create{
      
    }
    class Read{
        public $db;
        function __construct($sql){
            $data = array();
            
            $db = new Db();

            $conn = $db->connect();

            $result = $conn->query($sql);
               
            $lines = $result->num_rows;

            while ($row = $result->fetch_assoc()) {  
              $data .= $row;
              
            }
            return $data;
        }
    }
    class Update{

    }
    class Delete{

    }
    

    
?>