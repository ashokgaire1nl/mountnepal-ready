<?php
namespace Src\Controller;
use Src\TableGateways\MenuGateway;

class MenuController {

    private $db;
    private $requestMethod;
    private $type;
    private $subtype;
    private $day;

    private $menuGateway;

    public function __construct($db, $requestMethod, $type, $subtype, $day)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->type = $type;
        $this->subtype = $subtype;
        $this->day = $day;

        $this->menuGateway = new MenuGateway($db);

        
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                if ($this->day) {
                    $response = $this->getMenuByDay($this->day);
                } elseif ($this->subtype){
                    $response = $this->getMenuBySubType($this->type,$this->subtype);
                }
                elseif($this -> type){
                    $response = $this->getMenuByType($this->type);
                }
                else{
                    $response = $this->getAllMenu();
                }
                break;

            case 'POST':
                if($this -> type){
                    $response = $this->updateMenu();
                
                }
                else{
                    $response = $this->addMenu();
                }
                break;
            case 'PUT':
               
                    $response = $this->updateMenu();
                
               
                break;
            case 'DELETE':
                    $response = $this->deleteMenu();
               
                break;
            default:
               
                break;
        }
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }

    private function getAllMenu()
    {
        $result = $this->menuGateway->findAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function getMenuByDay($day)
    {
        $result = $this->menuGateway->findByDay($day);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function getMenuBySubType($type,$subtype)
    {
        $result = $this->menuGateway->findBySubType($type,$subtype);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }
  

    private function getMenuByType($type)
    {
        
        $result = $this->menuGateway->findByType($type);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }
  

  
    private function addMenu()

    {
        $directory_path = dirname( __FILE__ ) . '/../../public/uploads/';

        if (!file_exists($directory_path)) {
            mkdir($directory_path, 0777, true);
        }
        

       
        $pic = null;
        
        if (isset($_FILES["pic"])) {
            
         
          if ($_FILES["pic"]["error"] > 0)
          {
             $pic = null;
          }
         else
        {
        $host = $_SERVER['SERVER_NAME'];
        $port = $_SERVER['SERVER_PORT'];
        move_uploaded_file($_FILES["pic"]["tmp_name"],
        $directory_path . $_FILES["pic"]["name"]);
        $pic =  'http://'.$host.'/uploads/'. $_FILES["pic"]["name"];
      }
    }
    elseif (isset($_POST['pic'])) {
            $pic = $_POST['pic'];
           
           
        

    }

    $name= $_POST['name'];
    $description_en = $_POST['description_en'];
    $description_fi = $_POST['description_fi'];
    $type = $_POST['type'];
    $subtype = $_POST['subtype'] ?? null;
    $day = $_POST['day'] ?? null;
    $price = $_POST['price'];

    $input = $array = array(
        "name" => $name,
        "description_en" => $description_en,
        "description_fi" => $description_fi,
        "type" => $type,
        "subtype" => $subtype ?? null,
        "day" => $day ?? null,
        "price" => $price.'€',
        "pic" => $pic
    );
   
    $this->menuGateway->insert($input);
        
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] =  "Menu Added Successfully";;
        return $response;
    }


   private function updateMenu(){

    $directory_path = dirname( __FILE__ ) . '/../../public/uploads/';

    if (!file_exists($directory_path)) {
        mkdir($directory_path, 0777, true);
    }
    

   
    $pic = null;
    
    if (isset($_FILES["pic"])) {
        
     
      if ($_FILES["pic"]["error"] > 0)
      {
         $pic = null;
      }
     else
    {
            $host = $_SERVER['SERVER_NAME'];
            $port = $_SERVER['SERVER_PORT'];
            move_uploaded_file($_FILES["pic"]["tmp_name"],
            $directory_path . $_FILES["pic"]["name"]);
            $pic =  'http://'.$host.':'.$port.'/uploads/'. $_FILES["pic"]["name"];
        }
        }
                elseif (isset($_POST['pic'])) {
                        $pic = $_POST['pic'];
            
            
            

        }
        $id = $_POST['id'];
        $name= $_POST['name'];
        $description_en = $_POST['description_en'];
        $description_fi = $_POST['description_fi'];
        $type = $_POST['type'];
        $subtype = $_POST['subtype'] ?? null;
        $day = $_POST['day'] ?? null;
        $price = $_POST['price'];
    
        $input = $array = array(
            "id" =>$id,
            "name" => $name,
            "description_en" => $description_en,
            "description_fi" => $description_fi,
            "type" => $type,
            "subtype" => $subtype ?? null,
            "day" => $day ?? null,
            "price" => $price,
            "pic" => $pic
        );
        
           
            
            $this->menuGateway->updateMenu($input);
                
                $response['status_code_header'] = 'HTTP/1.1 200 OK';
                $response['body'] =  "Menu Update Successfully";;
                return $response;

   }
  

  

    private function deleteMenu()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        $this->menuGateway->delete($input);
        
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] =  "Menu Deleted Successfully";;
        return $response;
    }

  


    private function validateMenu($input)
    {
        if (! isset($input['name'])) {
            return false;
        }
        if (! isset($input['description_en'])) {
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