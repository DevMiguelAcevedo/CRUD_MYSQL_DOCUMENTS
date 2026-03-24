<?php

namespace App\Models;

use Config\Database;

class BaseModel
{
    protected $db;
    protected $table;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    protected function query($sql)
    {
        return $this->db->query($sql);
    }

    protected function escape($string)
    {
        return $this->db->real_escape_string($string);
    }

    protected function prepare($sql)
    {
        return $this->db->prepare($sql);
    }

    public function findById($id)
    {
        $stmt = $this->prepare("SELECT * FROM {$this->table} WHERE ID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function findAll()
    {
        $result = $this->query("SELECT * FROM {$this->table}");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function delete($id)
    {
        $stmt = $this->prepare("DELETE FROM {$this->table} WHERE DOC_ID = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
