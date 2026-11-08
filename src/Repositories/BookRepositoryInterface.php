<?php
namespace App\Repositories;

use App\Models\Book;

interface BookRepositoryInterface
{
    public function findAll(): array;
    public function findById(int $id): ?Book;
    public function save(Book $book): bool;
    public function update(Book $book): bool;
    public function delete(int $id): bool;
}