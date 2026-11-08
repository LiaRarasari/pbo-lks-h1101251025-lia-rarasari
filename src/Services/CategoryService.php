<?php

namespace App\Services;

use App\Repositories\CategoryRepositoryInterface;
use Exception;

class CategoryService
{
    private CategoryRepositoryInterface $categoryRepo;

    public function __construct(CategoryRepositoryInterface $categoryRepo)
    {
        $this->categoryRepo = $categoryRepo;
    }

    public function list(): array
    {
        $categories = $this->categoryRepo->findAll();
        return array_map(fn($cat) => $cat->toArray(), $categories);
    }

    public function getById(int $id): ?array
    {
        $cat = $this->categoryRepo->findById($id);
        return $cat ? $cat->toArray() : null;
    }

    public function create(array $data): array
    {
        $this->validate($data);
        $created = $this->categoryRepo->create($data);
        return $created->toArray();
    }

    public function update(int $id, array $data): bool
    {
        $this->validate($data);
        return $this->categoryRepo->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->categoryRepo->delete($id);
    }

    private function validate(array $data): void
    {
        if (empty($data['nama']) || trim($data['nama']) === '') {
            throw new Exception("Nama kategori wajib diisi", 422);
        }
    }
}