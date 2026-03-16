<?php

namespace App\Modules\Crm\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Crm\Http\Requests\CreateContactRequest;
use App\Modules\Crm\Http\Requests\UpdateContactRequest;
use App\Modules\Crm\Models\Contact;
use App\Modules\Crm\Services\ContactService;
use Illuminate\Http\JsonResponse;

class ContactController extends Controller
{
    public function __construct(protected ContactService $service) {}

    public function index(): JsonResponse
    {
        return response()->json($this->service->listContacts());
    }

    public function show(int $contactId): JsonResponse
    {
        return response()->json($this->service->getContact(Contact::findOrFail($contactId)));
    }

    public function store(CreateContactRequest $request): JsonResponse
    {
        return response()->json($this->service->createContact($request->validated()), 201);
    }

    public function update(UpdateContactRequest $request, int $contactId): JsonResponse
    {
        return response()->json($this->service->updateContact(Contact::findOrFail($contactId), $request->validated()));
    }

    public function destroy(int $contactId): JsonResponse
    {
        $this->service->deleteContact(Contact::findOrFail($contactId));
        return response()->json(['message' => 'Contact deactivated.']);
    }
}
