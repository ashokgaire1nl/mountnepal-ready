<?php
require "../bootstrap.php";
$host = $_SERVER['SERVER_NAME'];

$pic = 'http://'.$host.'/uploads/icon.png';

//drop all table 
$drop_table = "DROP TABLE IF EXISTS menu; drop table IF EXISTS time; drop table IF EXISTS auth; drop table if exists status";
try{
    // use exec() because no results are returned

    $dbConnection->exec($drop_table);
    echo "Dropped all tabels\n";

    }
    catch(\PDOException $e){
        exit($e->getMessage());
    }



    //resturant status
    $status_sql =  "CREATE TABLE IF NOT EXISTS status (
         id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        status VARCHAR(80) NOT NULL
      
        )";
    
        try{
        // use exec() because no results are returned
    
        $dbConnection->exec($status_sql);
        echo "Status  created successfully";
    
        }
        catch(\PDOException $e){
            exit($e->getMessage());
        }



        
$statusvalues = "INSERT INTO status 
(status) 
VALUES 
('open')

";

try{
    // use exec() because no results are returned

    $dbConnection->exec($statusvalues);
    echo "\nValues Added to Status Table";

    }
    catch(\PDOException $e){
        exit($e->getMessage());
    }






//time section


  // sql to create table
  $time_sql = "CREATE TABLE IF NOT EXISTS time (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
    day VARCHAR(100) NOT NULL,
    starttime VARCHAR(80) NOT NULL,
    endtime VARCHAR(80) NOT NULL,
    type VARCHAR(80) NOT NULL
  
    )";

    try{
    // use exec() because no results are returned

    $dbConnection->exec($time_sql);
    echo "Table Time created successfully";

    }
    catch(\PDOException $e){
        exit($e->getMessage());
    }




$timevalues = "INSERT INTO time 
(day, starttime, endtime, type) 
VALUES 
('monday-friday', '11:00AM', '3:00PM','lunch'),
('monday-friday', '3:00AM', '8:00PM','ala-carte'),
('saturday-sunday', '11:00AM', '3:00PM','ala-carte')
";

try{
    // use exec() because no results are returned

    $dbConnection->exec($timevalues);
    echo "\nValues Added to Time Table";

    }
    catch(\PDOException $e){
        exit($e->getMessage());
    }



//auth section

  // sql to create table
  $auth_sql = "CREATE TABLE IF NOT EXISTS auth (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
    username VARCHAR(30) NOT NULL,
    password VARCHAR(30) NOT NULL)";

    try{
    // use exec() because no results are returned
    $dbConnection->exec($auth_sql);
    echo "\nTable Auth created successfully";

    }
    catch(\PDOException $e){
        exit($e->getMessage());
    }




$authvalues = "INSERT INTO auth 
(username, password) 
VALUES 
('admin','admin')

";

try{
    // use exec() because no results are returned

    $dbConnection->exec($authvalues);
    echo "\nValues Added to Auth Table";

    }
    catch(\PDOException $e){
        exit($e->getMessage());
    }





    //menu section

     // sql to create table
  $menu_sql = "CREATE TABLE IF NOT EXISTS menu (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
    name VARCHAR(255) NOT NULL,
    description_en VARCHAR(1500),
    description_fi VARCHAR(1000),
    type VARCHAR(255) NOT NULL,
    subtype VARCHAR(255),
    day VARCHAR(255),
    price VARCHAR(255) NOT NULL,
    pic VARCHAR(255)

  
    )";

    try{
    // use exec() because no results are returned

    $dbConnection->exec($menu_sql);
    echo "\nTable Menu created successfully";

    }
    catch(\PDOException $e){
        exit($e->getMessage());
    }



$translation = file_get_contents("translation.json");
$menu = file_get_contents("menu.json");
$json_menu = json_decode($menu,true);
$json_translation = json_decode($translation, true);




foreach($json_menu as $title=>$value):
   // echo $title."\n";
    $keys= array_keys($value);
    
    
     foreach($keys as $key):
      

        $name = $value[$key]["name"];
        $desc_en= $value[$key]["description"] ?? null;
        $desc_fi = $json_translation[$title][$key+1] ?? null;
        $type =  $title;
        $price= $value[$key]["price"] ?? null;

        $menuvalues = "INSERT INTO menu 
        (name,description_en,description_fi,type,subtype,day,price,pic)
        VALUES 
        ('$name','$desc_en','$desc_fi','$type',null,null,'$price','$pic')";
//echo $menuvalues;
        try{
    // use exec() because no results are returned
     //echo $menuvalues;
    $dbConnection->exec($menuvalues);
    echo "\nValues Added to Menu Table";

    }
    catch(\PDOException $e){
        exit($e->getMessage());
    }

    endforeach;
endforeach;




$lunchtranslation = file_get_contents("lunchtranslation.json");
$lunch = file_get_contents("lunch.json");
$json_lunch = json_decode($lunch,true);
$json_lunchtranslation = json_decode($lunchtranslation, true);




foreach($json_lunch as $title=>$value):
   // echo $title."\n";
    $keys= array_keys($value);


    
     foreach($keys as $key):
      
        $name = $value[$key]["name"];
        $desc_en= $value[$key]["description"];
        $desc_fi = $json_translation[$title][$key+1] ?? null;
        $type = "lunch";
        $day =  $title;
        $price= $value[$key]["price"];

        $lunchvalues = "INSERT INTO menu 
        (name,description_en,description_fi,type,subtype,day,price,pic)
        VALUES 
        ('$name','$desc_en','$desc_fi','$type',null,'$day','$price','$pic')";

        try{
   
    $dbConnection->exec($lunchvalues);
    echo "\nValues Added to lunch Table";

    }
    catch(\PDOException $e){
        exit($e->getMessage());
    }
     endforeach;
    endforeach;







//offer section

  // sql to create table
  $offer_sql = "CREATE TABLE IF NOT EXISTS offer (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
    name VARCHAR(255) NOT NULL,
    description_en VARCHAR(255),
    description_fi VARCHAR(255),
    status VARCHAR(20) default false
    
    )";

    try{
    // use exec() because no results are returned
    $dbConnection->exec($offer_sql);
    echo "\nTable Offer created successfully";

    }
    catch(\PDOException $e){
        exit($e->getMessage());
    }

   //juction tablefor many to many relationship between menu and offer table
   $offer_menu_sql = "CREATE TABLE IF NOT EXISTS offer_menu_junction (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
    disprice VARCHAR(100) NOT NULL,
    offer_id INT(10) NOT NULL,
    menu_id INT(10) NOT NULL
    )";

    try{
    // use exec() because no results are returned
    $dbConnection->exec($offer_menu_sql);
    echo "\nTable Offer MENU JUNCTION created successfully";

    }
    catch(\PDOException $e){
        exit($e->getMessage());
    }
