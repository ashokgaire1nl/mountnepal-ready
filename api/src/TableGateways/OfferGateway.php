<?php
namespace Src\TableGateways;

class offerGateway {

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
                offer;
        ";

        try {
            $statement = $this->db->query($statement);
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function find($id)
    {
        $statement = "
        select offer_menu_junction.id,name,description_en, description_fi ,pic,price,disprice from offer_menu_junction 
        inner join menu  on offer_menu_junction.menu_id = menu.id
        where offer_id = ?;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array($id));
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function insert(Array $input)
    {
        $statement = "
            INSERT INTO offer
                (name, description_en, description_fi, status)
            VALUES
                (:name, :description_en, :description_en, :status);
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'name' => $input['name'],
                'description_en'  => $input['description_en'],
                'description_fi' => $input['description_fi'],
                'status' => $input['status'],
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function insertItem(Array $input,$id)
    {

       
        $statement = "
        insert into offer_menu_junction(menu_id,offer_id,disprice) values 
            
                (:menu_id, :offer_id, :disprice);
        ";

        try {
            $statement = $this->db->prepare($statement);

            $statement->execute(array(
                'menu_id' => $input['menu_id'],
                'offer_id'  =>  (int) $id,
                'disprice' => $input['disprice'].'€',
                
            ));
           
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function update($id, Array $input)
    {
        $statement = "
            UPDATE offer
            SET
                name = :name,
                description_en  = :description_en,
                description_fi = :description_fi,
                status = :status
            WHERE id = :id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'id' => (int) $id,
                'name' => $input['name'],
                'description_en'  => $input['description_en'],
                'description_fi' => $input['description_fi'] ?? null,
                'status' => $input['status'] ?? null,
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function updateItem($id, Array $input)
    {
        $statement = "
        update offer_menu_junction set disprice=:disprice,
        menu_id=:menu_id
    
            WHERE id = :id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'id' => (int) $id,
                'disprice' => $input['disprice'],
                'menu_id'  => $input['menu_id'],
                
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function delete($id)
    {
        $statement = "
            DELETE FROM offer
            WHERE id = :id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array('id' => $id));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function deleteItem($id)
    {
        $statement = "
            DELETE FROM offer_menu_junction
            WHERE id = :id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array('id' => $id));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }
}