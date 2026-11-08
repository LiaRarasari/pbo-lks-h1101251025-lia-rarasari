<?php
namespace App\Services;

use App\Models\Book;
use App\Repositories\BookRepositoryInterface;
use InvalidArgumentException;

class BookService
{
    private BookRepositoryInterface $repository;

    public function __construct(BookRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAllBooks(): array
    {
        $books = $this->repository->findAll();
        return array_map(fn(Book $book) => $book->toArray(), $books);
    }

    public function getBookById(int $id): ?array
    {
        $book = $this->repository->findById($id);
        return $book ? $book->toArray() : null;
    }

    public function createBook(array $data): array
    {
        $this->validateBookData($data);

        $book = new Book(
            judul: trim($data['judul']),
            penulis: trim($data['penulis']),
            tahun: (int)$data['tahun'],
            stok: (int)$data['stok']
        );

        $success = $this->repository->save($book);

        if (!$success) {
            throw new \RuntimeException("Gagal menyimpan data buku ke database.");
        }

        return $book->toArray();
    }

    public function updateBook(int $id, array $data): ?array
    {
        $book = $this->repository->findById($id);
        if (!$book) {
            return null;
        }

        $this->validateBookData($data);

        $book->setJudul(trim($data['judul']));
        $book->setPenulis(trim($data['penulis']));
        $book->setTahun((int)$data['tahun']);
        $book->setStok((int)$data['stok']);

        $success = $this->repository->update($book);

        if (!$success) {
            throw new \RuntimeException("Gagal memperbarui data buku.");
        }

        return $book->toArray();
    }

    public function deleteBook(int $id): bool
    {
        $book = $this->repository->findById($id);
        if (!$book) {
            return false;
        }

        return $this->repository->delete($id);
    }

    private function validateBookData(array $data): void
    {
        if (empty($data['judul']) || trim($data['judul']) === '') {
            throw new InvalidArgumentException("Judul buku tidak boleh kosong.");
        }

        if (empty($data['penulis']) || trim($data['penulis']) === '') {
            throw new InvalidArgumentException("Penulis buku tidak boleh kosong.");
        }

        if (!isset($data['tahun']) || !is_numeric($data['tahun']) || (int)$data['tahun'] < 1000 || (int)$data['tahun'] > (int)date('Y') + 1) {
            throw new InvalidArgumentException("Tahun terbit harus berupa angka tahun yang valid.");
        }

        if (!isset($data['stok']) || !is_numeric($data['stok']) || (int)$data['stok'] < 0) {
            throw new InvalidArgumentException("Stok tidak boleh bernilai negatif.");
        }
    }
}