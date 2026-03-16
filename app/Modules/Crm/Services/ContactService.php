<?php

namespace App\Modules\Crm\Services;

use App\Modules\Crm\Models\Contact;
use Illuminate\Database\Eloquent\Collection;

class ContactService
{
    public function listContacts(): Collection
    {
        return Contact::query()->with(['deals'])->orderByDesc('id')->get();
    }

    public function getContact(Contact $contact): Contact
    {
        return $contact->load(['deals', 'activities']);
    }

    public function createContact(array $data): Contact
    {
        return Contact::query()->create($data);
    }

    public function updateContact(Contact $contact, array $data): Contact
    {
        $contact->update($data);
        return $contact->fresh();
    }

    public function deleteContact(Contact $contact): void
    {
        $contact->update(['is_active' => false]);
    }
}
