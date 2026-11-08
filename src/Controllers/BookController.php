<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Services\BookService;
use InvalidArgumentException;
use Exception;

class BookController
{
    private BookService $bookService;

    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    public function index(Request $request): void
    {
        try {
            $books = $this->bookService->getAllBooks();
            Response::success($books, 'Daftar buku berhasil diambil');
        } catch (Exception $e) {
            Response::error('Gagal mengambil data buku: ' . $e->getMessage(), 500);
        }
    }

    public function show(Request $request, string|int $id): void
    {
        try {
            $book = $this->bookService->getBookById((int)$id);

            if (!$book) {
                Response::error('Buku dengan ID ' . $id . ' tidak ditemukan', 404);
                return;
            }

            Response::success($book, 'Detail buku berhasil diambil');
        } catch (Exception $e) {
            Response::error('Gagal mengambil detail buku: ' . $e->getMessage(), 500);
        }
    }

    public function store(Request $request): void
    {
        try {
            $body = $request->getBody();
            $newBook = $this->bookService->createBook($body);
            Response::success($newBook, 'Buku berhasil ditambahkan', 201);
        } catch (InvalidArgumentException $e) {
            Response::error($e->getMessage(), 422);
        } catch (Exception $e) {
            Response::error('Terjadi kesalahan saat menyimpan buku: ' . $e->getMessage(), 500);
        }
    }

    public function update(Request $request, string|int $id): void
    {
        try {
            $body = $request->getBody();
            $updatedBook = $this->bookService->updateBook((int)$id, $body);

            if (!$updatedBook) {
                Response::error('Buku dengan ID ' . $id . ' tidak ditemukan untuk diperbarui', 404);
                return;
            }

            Response::success($updatedBook, 'Data buku berhasil diperbarui');
        } catch (InvalidArgumentException $e) {
            Response::error($e->getMessage(), 422);
        } catch (Exception $e) {
            Response::error('Terjadi kesalahan saat memperbarui buku: ' . $e->getMessage(), 500);
        }
    }

    public function destroy(Request $request, string|int $id): void
    {
        try {
            $deleted = $this->bookService->deleteBook((int)$id);

            if (!$deleted) {
                Response::error('Buku dengan ID ' . $id . ' tidak ditemukan untuk dihapus', 404);
                return;
            }

            Response::success(null, 'Buku berhasil dihapus');
        } catch (Exception $e) {
            Response::error('Terjadi kesalahan saat menghapus buku: ' . $e->getMessage(), 500);
        }
    }
}