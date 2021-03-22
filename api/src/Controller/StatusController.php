<?php
namespace Src\Controller;

use Src\TableGateways\StatusGateway;

class StatusController {

    private $db;
    private $requestMethod;
    private $statusId;
    private $offerItemId;

    private $statusGateway;

    public function __construct($db, $requestMethod, $statusId)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->statusId = $statusId;
       

        $this->statusGateway = new StatusGateway($db);
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                if ($this->statusId) {
                    $response = $this->getStatus($this->statusId);
                }
                break;
         
            case 'PUT':
                if ($this->statusId) {
                    $response = $this->updateStatusFromRequest($this->statusId);
                }
               
                break;
          
            default:
               
                break;
        }
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }

 
    private function getStatus($id)
    {
        $result = $this->statusGateway->find($id);
       
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }
  
    private function updateStatusFromRequest($id)
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
     
        $this->statusGateway->update($id, $input);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] =  "Status Updated Successfully";;
        return $response;
    }
  
    private function unprocessableEntityResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
        $response['body'] = json_encode([
            'error' => 'Invalid input'
        ]);
        return $response;
    }

    private function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = "something went wrong";
        error_log("here");
        return $response;
    }
}
