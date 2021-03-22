<?php
namespace Src\Controller;

use Src\TableGateways\TimeGateway;

class TimeController {

    private $db;
    private $requestMethod;
    private $timeId;
    

    private $timeGateway;

    public function __construct($db, $requestMethod, $timeId)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->timeId = $timeId;

        $this->timeGateway = new TimeGateway($db);
    }

    public function processRequest()
    {
        
        switch ($this->requestMethod) {
            case 'GET':
                if ($this->timeId) {
                    $response = $this->getTime($this->timeId);
                } else {
                    $response = $this->getAllTime();
                };
                break;
            case 'POST':
                $response = $this->createTimeFromRequest();
                break;
            case 'PUT':
                $response = $this->updateTimeFromRequest($this->timeId);
                break;
            case 'DELETE':
                $response = $this->deleteTime($this->timeId);
                break;
            default:
                //$response = $this->notFoundResponse();
                break;
        }
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }

    private function getAllTime()
    {
        $result = $this->timeGateway->findAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function getTime($id)
    {
        $result = $this->timeGateway->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }
  

    private function createTimeFromRequest()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        error_log($input);
        if (! $this->validateTime($input)) {
            return $this->unprocessableEntityResponse();
        }
        $this->timeGateway->insert($input);
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = "Time Created Successfully";
        return $response;
    }

    private function updateTimeFromRequest($id)
    {
        $result = $this->timeGateway->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! $this->validateTime($input)) {
            return $this->unprocessableEntityResponse();
        }
        $this->timeGateway->update($id, $input);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] =  "Time Updated Successfully";;
        return $response;
    }

    private function deleteTime($id)
    {
        $result = $this->timeGateway->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $this->timeGateway->delete($id);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] =  "Time Deleted Successfully";;
        return $response;
    }

    private function validateTime($input)
    {
        if (! isset($input['day'])) {
            return false;
        }
        if (! isset($input['starttime'])) {
            return false;
        }
        if (! isset($input['endtime'])) {
            return false;
        }
        if (! isset($input['type'])) {
            return false;
        }
        return true;
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
        return $response;
    }
}