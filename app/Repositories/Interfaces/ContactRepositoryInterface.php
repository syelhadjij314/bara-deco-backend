<?php

namespace App\Repositories\Interfaces;

use App\Models\Contact;
use Illuminate\Pagination\LengthAwarePaginator;

interface ContactRepositoryInterface
{
    public function getAll(array $filters = [], int $perPage = 20): LengthAwarePaginator;
    public function findById(int $id): ?Contact;
    public function create(array $data): Contact;
    public function markAsRead(int $id): Contact;
    public function markAsReplied(int $id): Contact;
    public function delete(int $id): bool;
    public function countUnread(): int;
}
