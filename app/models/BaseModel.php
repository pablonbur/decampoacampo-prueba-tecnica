<?php

abstract class BaseModel {
    protected PDO $db;
    protected string $table;

    public function __construct(PDO $db, string $table) {
        $this->db = $db;
        $this->table = $table;
    }

    public function getAll(): false|array|PDOException {
        try {
            $stmt = $this->db->query("SELECT * FROM {$this->table}");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return $e;
        }
    }

    public function getById($id): false|array|PDOException {
        try {
            $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id");
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return $e;
        }
    }

    public function delete($id): bool|PDOException
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {
            return $e;
        }
    }


}
