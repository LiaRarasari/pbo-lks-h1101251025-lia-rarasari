<?php

namespace App\Models;

class Category
{
    private ?int $id;
    private string $nama;
    private ?string $deskripsi;
    private ?string $createdAt;
    private ?string $updatedAt;

    public function __construct(
        ?int $id,
        string $nama,
        ?string $deskripsi = null,
        ?string $createdAt = null,
        ?string $updatedAt = null
    ) {
        $this->id = $id;
        $this->nama = $nama;
        $this->deskripsi = $deskripsi;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNama(): string
    {
        return $this->nama;
    }

    public function getDeskripsi(): ?string
    {
        return $this->deskripsi;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?string
    {
        return $this->updatedAt;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nama' => $this->nama,
            'deskripsi' => $this->deskripsi,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt
        ];
    }
}