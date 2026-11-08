<?php
namespace App\Core;

use Exception;

class Container
{
    private array $bindings = [];
    private array $instances = [];

    public function bind(string $key, callable $resolver): void
    {
        $this->bindings[$key] = $resolver;
    }

    public function singleton(string $key, callable $resolver): void
    {
        $this->bindings[$key] = function ($container) use ($key, $resolver) {
            if (!isset($this->instances[$key])) {
                $this->instances[$key] = $resolver($container);
            }
            return $this->instances[$key];
        };
    }

    public function get(string $key): mixed
    {
        if (isset($this->bindings[$key])) {
            return $this->bindings[$key]($this);
        }

        if (class_exists($key)) {
            return new $key();
        }

        throw new Exception("Layanan atau kelas [{$key}] tidak terdaftar di dalam Container.");
    }
}