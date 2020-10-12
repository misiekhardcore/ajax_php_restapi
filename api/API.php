<?php

include 'config/Database.php';

class API
{
    private $conn = null;
    private $table = 'list';

    function __construct()
    {
        $this->database_connection();
    }

    function database_connection()
    {
        $conn = new Database;
        $this->conn = $conn->connect();
    }

    function fetch_all()
    {
        $query = "SELECT * FROM " . $this->table . " ORDER BY  id";
        $stmt = $this->conn->prepare($query);
        if ($stmt->execute()) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        }
    }

    function insert()
    {
        if (isset($_POST['first_name'])) {
            $form_data = array(
                ':first_name' => $_POST['first_name'],
                ':last_name' => $_POST['last_name']
            );

            $query = "INSERT INTO " . $this->table . " (first_name, last_name) VALUES (:first_name, :last_name)";

            $stmt = $this->conn->prepare($query);
            if ($stmt->execute($form_data)) {
                $data[] = array(
                    'success' => '1'
                );
            } else {
                $data[] = array(
                    'success' => '0'
                );
            }
        } else {
            $data[] = array(
                'success' => '0'
            );
        }
        return $data;
    }

    function fetch_single($id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id='" . $id;
        $stmt = $this->conn->prepare($query);
        if ($stmt->execute()) {
            foreach ($stmt->fetchAll() as $row) {
                $data['first_name'] = $row['first_name'];
                $data['last_name'] = $row['last_name'];
            }
            return $data;
        }
    }

    function update()
    {
        if (isset($_POST['first_name'])) {
            $form_data = array(
                ':first_name' => $_POST['first_name'],
                ':last_name' => $_POST['last_name'],
                ':id' => $_POST['id']
            );

            $query = "UPDATE " . $this->table . " SET first_name = :first_name, last_name = :last_name WHERE id=:id";

            $stmt = $this->conn->prepare($query);

            if ($stmt->execute($form_data)) {
                $data[] = array(
                    'success' => '1'
                );
            } else {
                $data[] = array(
                    'success' => '0'
                );
            }
        } else {
            $data[] = array(
                'success' => '0'
            );
        }
        return $data;
    }

    function delete($id)
    {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id =' . $id;

        $stmt = $this->conn->prepare($query);

        if ($stmt->execute()) {
            $data[] = array(
                'success' => '1'
            );
        } else {
            $data[] = array(
                'success' => '0'
            );
        }
        return $data;
    } 
}
