<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Services\CategoryService;
use Exception;

class CategoryController
{
    private CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index(): void
    {
        $categories = $this->categoryService->list();
        Response::json([
            'status' => 'success',
            'message' => 'Daftar kategori',
            'data' => $categories
        ]);
    }

    public function show(Request $request, string|int $id): void
    {
        $id = (int)$id;
        $category = $this->categoryService->getById($id);
        if (!$category) {
            Response::json([
                'status' => 'error',
                'message' => "Kategori dengan ID {$id} tidak ditemukan"
            ], 404);
            return;
        }

        Response::json([
            'status' => 'success',
            'message' => 'Detail kategori',
            'data' => $category
        ]);
    }

    public function store(Request $request): void
    {
        try {
            $body = $request->getBody();
            $category = $this->categoryService->create($body);
            Response::json([
                'status' => 'success',
                'message' => 'Kategori berhasil ditambahkan',
                'data' => $category
            ], 201);
        } catch (Exception $e) {
            Response::json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], $e->getCode() >= 400 && $e->getCode() < 500 ? $e->getCode() : 400);
        }
    }

    public function update(Request $request, string|int $id): void
    {
        $id = (int)$id;
        try {
            $category = $this->categoryService->getById($id);
            if (!$category) {
                Response::json([
                    'status' => 'error',
                    'message' => "Kategori dengan ID {$id} tidak ditemukan"
                ], 404);
                return;
            }

            $body = $request->getBody();
            $this->categoryService->update($id, $body);
            $updatedCategory = $this->categoryService->getById($id);

            Response::json([
                'status' => 'success',
                'message' => 'Kategori berhasil diperbarui',
                'data' => $updatedCategory
            ]);
        } catch (Exception $e) {
            Response::json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], $e->getCode() >= 400 && $e->getCode() < 500 ? $e->getCode() : 400);
        }
    }

    public function destroy(Request $request, string|int $id): void
    {
        $id = (int)$id;
        $category = $this->categoryService->getById($id);
        if (!$category) {
            Response::json([
                'status' => 'error',
                'message' => "Kategori dengan ID {$id} tidak ditemukan"
            ], 404);
            return;
        }

        $this->categoryService->delete($id);
        Response::json([
            'status' => 'success',
            'message' => 'Kategori berhasil dihapus'
        ]);
    }
}