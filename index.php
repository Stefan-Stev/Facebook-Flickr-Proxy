<?php

    try {

        $myPDO = new PDO("pgsql:host=ec2-54-247-118-139.eu-west-1.compute.amazonaws.com dbname=ddc96j0h6vcha2",
                "ulcnrmhvugakls", "4173dfbb7c968ad0ca1333a89cc5ccd9b9cf3e7661eb18a252fefc377de72487");
        
        echo "Connected to database\n";

        $stmt = $myPDO->query("SELECT * FROM user");
        $user = $stmt->fetch();
        var_dump($user);

    } catch(PDOException $e) {
        echo "Error: \"" . $e->getMessage() . "\"";
    }
?>