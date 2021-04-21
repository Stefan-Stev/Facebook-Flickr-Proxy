<?php
require_once './fbdb.php';
class QueryManager extends DataBase
{
    public $conexiune;
    public function getConexiune()
    {
     $this->conexiune=$this->connect() ;
     if (!$this->conexiune) {
        $m = oci_error();
        echo $m['message'], "\n";
        exit;
    }
        
    }
    public function query($sql)
    {
        
        $stmt=$this->conexiune->prepare($sql);
        $stmt->execute();
        $array=[];
        while($row=$stmt->fetch(PDO::FETCH_ASSOC))
        {
            extract($row);
            //var_dump($row);
            echo $user_id;
            $array[]=$access_token;
            
            //$array[]=$app;
        }
        echo json_encode($array);
    }
}

?>