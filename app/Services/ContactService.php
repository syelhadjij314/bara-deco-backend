<?php

namespace App\Services;

use App\Models\Contact;
use App\Repositories\Interfaces\ContactRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ContactService
{
    public function __construct(
        protected ContactRepositoryInterface $contactRepository
    ) {}

    public function submitContact(array $data): Contact
    {
        return $this->contactRepository->create(array_merge($data, [
            'status' => Contact::STATUS_NEW,
        ]));
    }

    public function getMessages(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        return $this->contactRepository->getAll($filters, $perPage);
    }

    public function getMessage(int $id): Contact
    {
        $contact = $this->contactRepository->findById($id);
        if (!$contact) abort(404, 'Message non trouvé.');
        return $contact;
    }

    public function readMessage(int $id): Contact
    {
        return $this->contactRepository->markAsRead($id);
    }

    public function replyMessage(int $id): Contact
    {
        return $this->contactRepository->markAsReplied($id);
    }

    public function deleteMessage(int $id): bool
    {
        return $this->contactRepository->delete($id);
    }

    public function getStats(): array
    {
        return [
            'unread'  => $this->contactRepository->countUnread(),
            'total'   => Contact::count(),
        ];
    }
}
