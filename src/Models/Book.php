<?php
namespace App\Models;

class Book
{
    private ?int $id;
    private string $judul;
    private string $penulis;
    private int $tahun;
    private int $stok;
    private ?string $createdAt;
    private ?string $updatedAt;

    public function __construct(
        string $judul = '',
        string $penulis = '',
        int $tahun = 0,
        int $stok = 0,
        ?int $id = null,
        ?string $createdAt = null,
        ?string $updatedAt = null
    ) {
        $this->id        = $id;
        $this->judul     = $judul;
        $this->penulis   = $penulis;
        $this->tahun     = $tahun;
        $this->stok      = $stok;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    // Getter
    public function getId(): ?int { return $this->id; }
    public function getJudul(): string { return $this->judul; }
    public function getPenulis(): string { return $this->penulis; }
    public function getTahun(): int { return $this->tahun; }
    public function getStok(): int { return $this->stok; }
    public function getCreatedAt(): ?string { return $this->createdAt; }
    public function getUpdatedAt(): ?string { return $this->updatedAt; }

    // Setter
    public function setId(?int $id): void { $this->id = $id; }
    public function setJudul(string $judul): void { $this->judul = $judul; }
    public function setPenulis(string $penulis): void { $this->penulis = $penulis; }
    public function setTahun(int $tahun): void { $this->tahun = $tahun; }
    public function setStok(int $stok): void { $this->stok = $stok; }

    // Mengubah object menjadi array
    public function toArray(): array
    {
        return [
            'id'         => $this->id,
            'judul'      => $this->judul,
            'penulis'    => $this->penulis,
            'tahun'      => $this->tahun,
            'stok'       => $this->stok,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }
}