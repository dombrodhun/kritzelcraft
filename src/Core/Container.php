<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Dependency Injection Container.
 */
class Container
{
    /** @var array<string, callable> */
    private array $services = [];
    /** @var array<string, mixed> */
    private array $instances = [];

    /**
     * Registriere einen Service.
     */
    public function set(string $name, callable $factory): void
    {
        $this->services[$name] = $factory;
    }

    /**
     * Hole einen Service. Falls er noch nicht existiert, erstelle ihn über die Factory.
     */
    public function get(string $name): mixed
    {
        if (isset($this->instances[$name])) {
            return $this->instances[$name];
        }

        if (!isset($this->services[$name])) {
            throw new \Exception("Service '{$name}' wurde nicht im Container gefunden.");
        }

        $this->instances[$name] = ($this->services[$name])($this);
        return $this->instances[$name];
    }
}
