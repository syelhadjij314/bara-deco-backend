<?php

namespace App\Repositories;

use App\Models\Contact;
use App\Repositories\Interfaces\ContactRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ContactRepository implements ContactRepositoryInterface
{
    public function __construct(protected Contact $model) {}

    public function getAll(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $query = $this->model->latest();

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', "%{$filters['search']}%")
                  ->orWhere('email', 'like', "%{$filters['search']}%")
                  ->orWhere('phone', 'like', "%{$filters['search']}%");
            });
        }

        return $query->paginate($perPage);
    }

    public function findById(int $id): ?Contact
    {
        return $this->model->find($id);
    }

    public function create(array $data): Contact
    {
        return $this->model->create($data);
    }

    public function markAsRead(int $id): Contact
    {
        $contact = $this->model->findOrFail($id);
        $contact->markAsRead();
        return $contact->fresh();
    }

    public function markAsReplied(int $id): Contact
    {
        $contact = $this->model->findOrFail($id);
        $contact->update(['status' => Contact::STATUS_REPLIED]);
        return $contact->fresh();
    }

    public function delete(int $id): bool
    {
        return $this->model->findOrFail($id)->delete();
    }

    public function countUnread(): int
    {
        return $this->model->unread()->count();
    }
}
