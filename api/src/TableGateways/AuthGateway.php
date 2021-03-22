<?php

namespace Src\TableGateways;

class AuthGateway{
    private $db = null;

    public function __construct($db){
        $this->db = $db;

    }

    public function validate($username,$password){
        
        $statement="
        select * from auth where username='$username' and password='$password'
        ";

        try {
            $statement =$this->db->prepare($statement);
            $statement->execute();
            $result = $statement->fetchAll();
            return $result;
        }
        catch(\PDOException $e){
            exit($e->getMessage());
        }
    
    }
}