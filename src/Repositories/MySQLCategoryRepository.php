<?php

namespace App\Repositories;

use App\Models\Category;
use PDO;

class MySQLCategoryRepository implements CategoryRepositoryInterface
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM categories ORDER BY id ASC");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $categories = [];
        foreach ($rows as $row) {
            $categories[] = new Category(
                (int)$row['id'],
                $row['nama'],
                $row['deskripsi'],
                $row['created_at'],
                $row['updated_at']
            );
        }

        return $categories;
    }

    public function findById(int $id): ?Category
    {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return new Category(
            (int)$row['id'],
            $row['nama'],
            $row['deskripsi'],
            $row['created_at'],
            $row['updated_at']
        );
    }

    public function create(array $data): Category
    {
        $stmt = $this->db->prepare(
            "INSERT INTO categories (nama, deskripsi) VALUES (:nama, :deskripsi)"
        );
        $stmt->execute([
            'nama' => $data['nama'],
            'deskripsi' => $data['deskripsi'] ?? null
        ]);

        $id = (int)$this->db->lastInsertId();
        return $this->findById($id);
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE categories SET nama = :nama, deskripsi = :deskripsi WHERE id = :id"
        );
        return $stmt->execute([
            'id' => $id,
            'nama' => $data['nama'],
            'deskripsi' => $data['deskripsi'] ?? null
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM categories WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}