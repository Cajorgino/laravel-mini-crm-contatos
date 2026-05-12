<?php

declare(strict_types=1);

namespace Infrastructure\Laravel\Http\Controllers;

use App\Http\Controllers\Controller;
use Application\Contact\DTOs\CreateContactDTO;
use Application\Contact\DTOs\UpdateContactDTO;
use Application\Contact\UseCases\CreateContactUseCase;
use Application\Contact\UseCases\DeleteContactUseCase;
use Application\Contact\UseCases\GetContactUseCase;
use Application\Contact\UseCases\ListContactsUseCase;
use Application\Contact\UseCases\UpdateContactUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Infrastructure\Laravel\Http\Requests\StoreContactRequest;
use Infrastructure\Laravel\Http\Requests\UpdateContactRequest;
use Infrastructure\Laravel\Http\Resources\ContactResource;

final class ContactController extends Controller
{
    public function __construct(
        private readonly CreateContactUseCase $createContactUseCase,
        private readonly ListContactsUseCase $listContactsUseCase,
        private readonly GetContactUseCase $getContactUseCase,
        private readonly UpdateContactUseCase $updateContactUseCase,
        private readonly DeleteContactUseCase $deleteContactUseCase,
    ) {
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $result = $this->listContactsUseCase->execute(
            page: $request->integer('page', 1),
            perPage: $request->integer('per_page', 15),
        );

        return ContactResource::collection($result['items'])->additional([
            'meta' => [
                'total' => $result['total'],
                'page' => $result['page'],
                'per_page' => $result['per_page'],
            ],
        ]);
    }

    public function store(StoreContactRequest $request): JsonResponse
    {
        $contact = $this->createContactUseCase->execute(new CreateContactDTO(
            name: (string) $request->string('name'),
            email: (string) $request->string('email'),
            phone: (string) $request->string('phone'),
        ));

        return (new ContactResource($contact))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(int $id): ContactResource
    {
        return new ContactResource($this->getContactUseCase->execute($id));
    }

    public function update(UpdateContactRequest $request, int $id): ContactResource
    {
        $contact = $this->updateContactUseCase->execute($id, new UpdateContactDTO(
            name: (string) $request->string('name'),
            email: (string) $request->string('email'),
            phone: (string) $request->string('phone'),
        ));

        return new ContactResource($contact);
    }

    public function destroy(int $id): Response
    {
        $this->deleteContactUseCase->execute($id);

        return response()->noContent();
    }
}
