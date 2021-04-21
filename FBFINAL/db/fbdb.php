<?php
class DataBase
{
    // private $accessToken;
    private $host = "ec2-54-247-118-139.eu-west-1.compute.amazonaws.com";
    private $user = "ulcnrmhvugakls";
    private $pwd = "4173dfbb7c968ad0ca1333a89cc5ccd9b9cf3e7661eb18a252fefc377de72487";
    private $dbName = "ddc96j0h6vcha2";

    public function connect()
    {
        $dsn = 'pgsql:host=' . $this->host . ';dbname=' . $this->dbName;
        $pdo = new PDO($dsn, $this->user, $this->pwd);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    }
};
?>
