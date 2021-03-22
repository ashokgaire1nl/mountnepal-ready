<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require "../bootstrap.php";
use Src\Controller\AuthController;
use Src\Controller\TimeController;
use Src\Controller\OfferController;
use Src\Controller\MenuController;
use Src\Controller\StatusController;


header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
//header("Content-Type: multipart/form-data");
header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");



$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);



if($uri[2] == '' ){
     
    echo json_encode('API is Working..');
}

elseif($uri[2] == 'status'){
  $requestMethod = $_SERVER["REQUEST_METHOD"];
   
  $statusId = null;
  if (isset($uri[3])) {
  $statusId = $uri[3];
    }

   


   // pass the request method and user creds to the AuthController:
   $controller = new StatusController($dbConnection, $requestMethod,$statusId);
   $controller->processRequest();

}
elseif($uri[2] =='auth'){
    
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $data = json_decode(file_get_contents('php://input'), true);
    $username= $data['username'];
    $password = $data['password'];



   // pass the request method and user creds to the AuthController:
   $controller = new AuthController($dbConnection, $requestMethod, $username,$password);
   $controller->processRequest();

}
elseif($uri[2] == 'time') {
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    error_log($requestMethod);
    $input = json_decode(file_get_contents('php://input'), TRUE);
   
    $timeId = null;
    if (isset($uri[3])) {
    $timeId = $uri[3];
      }


    // pass the request method and user ID to the TimeController:
    $controller = new TimeController($dbConnection, $requestMethod,$timeId);
    $controller->processRequest();
    
}

elseif($uri[2] == 'offer'){
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    
    $input = json_decode(file_get_contents('php://input'), TRUE);
    
    $offerId = null;
    $offerItemId = null;

    if (isset($uri[3])) {
    $offerId = (int) $uri[3];
     
    }
    if (isset($uri[4])) {
        $offerItemId = (int) $uri[4];
         
        }

    // pass the request method and user ID to the TimeController:
    $controller = new OfferController($dbConnection, $requestMethod,$offerId, $offerItemId);
    $controller->processRequest();
    

}

elseif($uri[2] == 'menu'){
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $input = json_decode(file_get_contents('php://input'), TRUE);
    
    $type = null;
    $subtype = null;
    $day = null;

    if (isset($uri[3])) {
    $type =  $uri[3];
     
    }
    if (isset($uri[4])) {
        $subtype = $uri[4];
         
        }

        if (isset($uri[5])) {
            $day = $uri[5];
             
            }

    // pass the request method and user ID to the TimeController:
    $controller = new MenuController($dbConnection, $requestMethod,$type, $subtype, $day);
    $controller->processRequest();
    

}

elseif($uri[2] == 'sendmail'){


    $requestMethod = $_SERVER["REQUEST_METHOD"];
   
    if ($requestMethod == 'POST'){
        $input = json_decode(file_get_contents('php://input'), TRUE);
        $name = $input['name'];
        $email = $input['email'];
        $phone = $input['phone'];
        $people = $input['people'];
        $date = $input['date'];
        $time = $input['time'];
       
    $mail = new PHPMailer();

    try {
    
    $mail->IsSMTP();
    $mail->Host = getenv('SMTP_HOST');
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = "ssl";  
    $mail->Username =  getenv('SMTP_USERNAME'); // SMTP account username example
    $mail->Password =  getenv('SMTP_PASS');        // SMTP account password example

    $mail->Port =  getenv('SMTP_PORT');               
    $mail->From= getenv('MAIL_FROM');
    $mail->FromName = getenv('MAIL_FROM_NAME');
    $mail->AddAddress(getenv('MAIL_TO'));
    
    $mail->IsHTML(true); 
    $mail->Subject  = "Table Book Request";
    $mail->MsgHTML("<b>New Table Request From: {$name}.</b>
    </br> Details:</br>.
    <table>
    <tr>
      
      <th>phone</th>
      <th>email</th>
      <th>people</th>
      <th>date</th>
      <th>time</th>
    </tr>
    <tr>
      <td>{$phone}</td>
      <td>{$email}</td>
      <td>{$people}</td>
      <td>{$date}</td>
      <td>{$time}</td>
    </tr>
  
  </table> 
    ");

    
  if($mail->Send()){
      echo "mail sent successfully.";
    $response['status_code_header'] = 'HTTP/1.1 200 OK';
    $response['body'] =  "Mail Sent Successfully";
    return $response;
  }
  else{
   
    echo "mail sent failed.{$mail->Username}";
    $response['status_code_header'] = 'HTTP/1.1 200 OK';
    $response['body'] =  "Mail Sent Failed";
    return $response;
        }
    
   
} catch (Exception $e) {
    print_r("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
    
}
$response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
        $response['body'] = json_encode([
            'error' => 'Invalid input'
        ]);
        return $response;

        }
        else{
            $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
            $response['body'] = "something went wrong";
           
            return $response;
        }

}

?>
