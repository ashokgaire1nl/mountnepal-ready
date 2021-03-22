<?php
namespace Src\Controller;

use Src\TableGateways\OfferGateway;

class OfferController {

    private $db;
    private $requestMethod;
    private $offerId;
    private $offerItemId;

    private $offerGateway;

    public function __construct($db, $requestMethod, $offerId, $offerItemId)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->offerId = $offerId;
        $this->offerItemId = $offerItemId;

        $this->offerGateway = new OfferGateway($db);
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                if ($this->offerId) {
                    $response = $this->getOffer($this->offerId);
                } else {
                    $response = $this->getAllOffer();
                };
                break;
            case 'POST':

                if ($this->offerId) {
                    $response = $this->createOfferItemFromRequest($this->offerId);
                   
                } else {
                    $response = $this->createOfferFromRequest();
                   
                };
               
                break;
            case 'PUT':
                if ($this->offerItemId) {
                    $response = $this->updateOfferItemFromRequest($this->offerItemId);
                   
                } else {
                    $response = $this->updateOfferFromRequest($this->offerId);
                };
               
                break;
            case 'DELETE':
                if ($this->offerItemId) {
                    $response = $this->deleteOfferItem($this->offerItemId);
                   
                } else {
                    $response = $this->deleteOffer($this->offerId);
                   
                };
               
                break;
            default:
               
                break;
        }
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }

    private function getAllOffer()
    {
        $result = $this->offerGateway->findAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function getOffer($id)
    {
        $result = $this->offerGateway->find($id);
       
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }
  

    private function createOfferFromRequest()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
       
        if (! $this->validateOffer($input)) {
            return $this->unprocessableEntityResponse();
        }
        $this->offerGateway->insert($input);
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = "Offer Created Successfully";
        return $response;
    }

    private function createOfferItemFromRequest($id)
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        
        if (! $this->validateOfferItem($input)) {
            return $this->unprocessableEntityResponse();
        }
        $this->offerGateway->insertItem($input,$id);
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = "Offer Created Successfully";
        return $response;
    }

    private function updateOfferFromRequest($id)
    {
        $result = $this->offerGateway->find($id);
       
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! $this->validateOffer($input)) {
            return $this->unprocessableEntityResponse();
        }
        $this->offerGateway->update($id, $input);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] =  "Offer Updated Successfully";;
        return $response;
    }

    private function updateOfferItemFromRequest($id)
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! $this->validateOfferItem($input)) {
            return $this->unprocessableEntityResponse();
        }
        $this->offerGateway->updateItem($id, $input);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] =  "Offer Updated Successfully";;
        return $response;
    }

    private function deleteOffer($id)
    {
        
        $this->offerGateway->delete($id);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] =  "Offer Deleted Successfully";;
        return $response;
    }

    private function deleteOfferItem($id)
    {
       
        $this->offerGateway->deleteItem($id);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] =  "Offer Deleted Successfully";;
        return $response;
    }


    private function validateOffer($input)
    {
        if (! isset($input['name'])) {
            return false;
        }
        if (! isset($input['description_en'])) {
            return false;
        }
        if (! isset($input['description_fi'])) {
            return false;
        }
        if (! isset($input['status'])) {
            return false;
        }
        return true;
    }

    private function validateOfferItem($input)
    {
        if (! isset($input['menu_id'])) {
            return false;
        }
        if (! isset($input['disprice'])) {
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
        error_log("here");
        return $response;
    }
}
