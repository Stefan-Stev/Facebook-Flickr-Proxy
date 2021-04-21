<?php
require_once './fbdb.php';
class QueryManager extends DataBase
{
    public $conexiune;
    public function getConexiune()
    {
        $this->conexiune = $this->connect();
        if (!$this->conexiune) {
            $m = oci_error();
            echo $m['message'], "\n";
            exit;
        }
    }
    public function query($sql)
    {
        $stmt = $this->conexiune->prepare($sql);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_LAZY);
        return $user;
    }
    public function insert($sql)
    {
        $stmt = $this->conexiune->prepare($sql);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_LAZY);
        return $user;
    }
}
/*
$obiect = new QueryManager();

$obiect->getConexiune();
$sql = "Select * from facebook_token";
$sql2 = "insert into facebook_token (access_token) values ('1234567')";
//$obiect->query($sql);
$obiect->query($sql2);*/
