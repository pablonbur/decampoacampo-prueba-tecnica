<?php

class Producto extends BaseModel {
    public function __construct() {
        parent::__construct(Database::getInstance(), 'productos');
    }
    public function create($data): int|false|PDOException
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO {$this->table} (nombre, descripcion, precio) VALUES (:nombre, :descripcion, :precio)");

            $stmt->execute([
                'nombre' => $data['nombre'],
                'descripcion' => $data['descripcion'],
                'precio' => $data['precio'],
            ]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            return $e;
        }
    }

    public function update($id, $data): bool|PDOException
    {
        try {
            $stmt = $this->db->prepare("UPDATE {$this->table} SET nombre = :nombre, descripcion = :descripcion, precio = :precio WHERE id = :id");
            return $stmt->execute([
                'id' => $id,
                'nombre' => $data['nombre'],
                'descripcion' => $data['descripcion'],
                'precio' => $data['precio'],
            ]);
        } catch (PDOException $e) {
            return $e;
        }
    }

}