<?php

namespace App\Repositories;

use App\Models\Category;

interface CategoryRepositoryInterface
{
    public function findAll(): array;
    public function findById(int $id): ?Category;
    public function create(array $data): Category;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}