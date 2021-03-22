<?php
namespace Src\TableGateways;

class TimeGateway {

    private $db = null;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function findAll()
    {
        $statement = "
            SELECT
                id, day, starttime, endtime, type
            FROM
                time;
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
            SELECT
                id, day, starttime, endtime, type
            FROM
                time
            WHERE id = ?;
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
            INSERT INTO time
                (day, starttime, endtime, type)
            VALUES
                (:day, :starttime, :endtime, :type);
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'day' => $input['day'],
                'starttime'  => $input['starttime'],
                'endtime' => $input['endtime'],
                'type' => $input['type'],
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function update($id, Array $input)
    {
        $statement = "
            UPDATE time
            SET
                day = :day,
                starttime  = :starttime,
                endtime = :endtime,
                type = :type
            WHERE id = :id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'id' => (int) $id,
                'day' => $input['day'],
                'starttime'  => $input['starttime'],
                'endtime' => $input['endtime'] ,
                'type' => $input['type'],
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function delete($id)
    {
        $statement = "
            DELETE FROM time
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