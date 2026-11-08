<?php
namespace App\Repositories;

use App\Models\Book;
use PDO;

class BookRepository implements BookRepositoryInterface
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM books ORDER BY id DESC");
        $rows = $stmt->fetchAll();

        $books = [];
        foreach ($rows as $row) {
            $books[] = new Book(
                $row['judul'],
                $row['penulis'],
                (int)$row['tahun'],
                (int)$row['stok'],
                (int)$row['id'],
                $row['created_at'],
                $row['updated_at']
            );
        }

        return $books;
    }

    public function findById(int $id): ?Book
    {
        $stmt = $this->db->prepare("SELECT * FROM books WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        return new Book(
            $row['judul'],
            $row['penulis'],
            (int)$row['tahun'],
            (int)$row['stok'],
            (int)$row['id'],
            $row['created_at'],
            $row['updated_at']
        );
    }

    public function save(Book $book): bool
    {
        $sql = "INSERT INTO books (judul, penulis, tahun, stok, created_at, updated_at) 
                VALUES (:judul, :penulis, :tahun, :stok, NOW(), NOW())";
        $stmt = $this->db->prepare($sql);

        $success = $stmt->execute([
            'judul'   => $book->getJudul(),
            'penulis' => $book->getPenulis(),
            'tahun'   => $book->getTahun(),
            'stok'    => $book->getStok(),
        ]);

        if ($success) {
            $book->setId((int)$this->db->lastInsertId());
        }

        return $success;
    }

    public function update(Book $book): bool
    {
        $sql = "UPDATE books 
                SET judul = :judul, penulis = :penulis, tahun = :tahun, stok = :stok, updated_at = NOW() 
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'id'      => $book->getId(),
            'judul'   => $book->getJudul(),
            'penulis' => $book->getPenulis(),
            'tahun'   => $book->getTahun(),
            'stok'    => $book->getStok(),
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM books WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}