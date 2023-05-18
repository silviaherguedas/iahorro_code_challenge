<?php

namespace App\Repositories;

interface RepositoryInterface
{
    /**
     * Get all resources.
     */
    public function getAll();

    /**
     * Get resource by Id
     */
    public function getById(int $id);

    /**
     * Create resource
     */
    public function create(array $data);

    /**
     * Update resource
     */
    public function update(array $data, int $id);

    /**
     * Delete resource
     */
    public function deleteById(int $id);
}
