<?php
namespace Src\System;

class DatabaseConnector{
    private $dbConnection = null;

    public function __construct(){
        $host = getenv('DB_HOST');
        $port = getenv('DB_PORT');
        $db = getenv('DB_DATABASE');
        $user = getenv('DB_USER');
        $pass = getenv('DB_PASS');

    

    try{
        $this->dbConnection = new \PDO(
            "mysql:host=$host;charst=utf8;dbname=$db",
            $user,
            $pass
        );
        error_log("Database connection success");

    }
    catch(\PDOException $e){
        exit($e->getMessage());
    }
}

public function getConnection() {

return $this->dbConnection;

}

}