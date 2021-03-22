<?php
namespace Src\TableGateways;

class MenuGateway {

    private $db = null;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function findAll()
    {
        $statement = "
            SELECT
               *
            FROM
                menu;
        ";

        try {
            $statement = $this->db->query($statement);
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function findByDay($day)
    {
        $statement = "
        select * from menu where type='lunch' and day= ?;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array($day));
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }


    public function findBySubType($type,$subtype)
    {
        $statement = "
        select * from menu where type=:type and subtype= :subtype;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'type'=>$type,
                'subtype'=>$subtype));
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function findByType($type)
    {
       
        $statement = "
        select * from menu where type=:type;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array('type' => $type));
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function insert(Array $input)
    {
        $statement = "
        insert into menu (name,description_en, description_fi,pic,type,subtype,day,price)
            VALUES
                (:name, :description_en, :description_en, :pic,:type,:subtype,:day,:price);
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'name' => $input['name'],
                'description_en'  => $input['description_en'],
                'description_fi' => $input['description_fi'],
                'pic' => $input['pic'],
                'type' => $input['type'],
                'subtype' => $input['subtype'] ?? null,
                'day' => $input['day'] ?? null,
                'price' => $input['price'],
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function updateMenu(Array $input){

        $statement = "
            UPDATE menu
            SET
                name = :name,
                description_en  = :description_en,
                description_fi = :description_fi,
                type = :type,
                pic = :pic,
                subtype = :subtype,
                day = :day,
                price =:price
            WHERE id = :id;
        ";

       
    try {
        $statement = $this->db->prepare($statement);
        $statement->execute(array('id' => $input['id'],
        'name' => $input['name'],
                'description_en'  => $input['description_en'],
                'description_fi' => $input['description_fi'],
                'pic' => $input['pic'],
                'type' => $input['type'],
                'subtype' => $input['subtype'] ?? null,
                'day' => $input['day'] ?? null,
                'price' => $input['price'] ?? null,
    ));
        return $statement->rowCount();
    } catch (\PDOException $e) {
        exit($e->getMessage());
    }

    }
   
   

    

    public function delete(Array $input)
    {
        $statement = "
            DELETE FROM menu
            WHERE id = :id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array('id' => (int) $input['id']));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

   
}