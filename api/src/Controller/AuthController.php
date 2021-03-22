<?php
namespace Src\Controller;

use Src\TableGateways\AuthGateway;

class AuthController{

    private $db;
    private $requestMethod;

    private $authGateway;
    private $username;
    private $psasword;

    public function __construct($db, $requestMethod,$username,$password){
        $this->db = $db;
        $this->username = $username;
        $this->password = $password;
        $this->requestMethod = $requestMethod;
        $this->authGateway = new AuthGateway($db);
    }

    public function processRequest(){
        if ($this->requestMethod == 'POST'){
            $response = $this-> validateUser($this->username, $this->password);
        }
        header($response['status_code_header']);
        if($response['body']){
            echo json_encode('success');
        }
        else{
            echo json_encode('username or password doesnot match');

        }
    }

    private function validateUser($username,$password){
       $result = $this->authGateway->validate($username,$password);
       if(!$result){
           return $this->notFoundResponse();

       }
       $response['status_code_header'] = 'HTTP/1.1 200 OK';
       $response['body'] = json_encode($result);
       return $response;
    }

    private function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = null;
        return $response;
    }
}