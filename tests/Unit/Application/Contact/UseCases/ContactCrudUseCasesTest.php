<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Contact\UseCases;

use Application\Contact\DTOs\CreateContactDTO;
use Application\Contact\DTOs\UpdateContactDTO;
use Application\Contact\UseCases\CreateContactUseCase;
use Application\Contact\UseCases\DeleteContactUseCase;
use Application\Contact\UseCases\GetContactUseCase;
use Application\Contact\UseCases\ListContactsUseCase;
use Application\Contact\UseCases\UpdateContactUseCase;
use Domain\Contact\Entities\Contact;
use Domain\Contact\Repositories\ContactRepositoryInterface;
use Domain\Contact\ValueObjects\ContactStatus;
use Domain\Contact\ValueObjects\Email;
use Domain\Contact\ValueObjects\Phone;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class ContactCrudUseCasesTest extends TestCase
{
    public function test_create_contact_use_case_persists_a_new_pending_contact(): void
    {
        $repository = $this->createMock(ContactRepositoryInterface::class);
        $repository
            ->expects($this->once())
            ->method('save')
            ->willReturnCallback(function (Contact $contact): Contact {
                $this->assertSame('Joao Silva', $contact->name());
                $this->assertSame('joao@empresa.com.br', $contact->email()->value());
                $this->assertSame('11987654321', $contact->phone()->value());
                $this->assertSame(0, $contact->score());
                $this->assertSame(ContactStatus::Pending, $contact->status());

                return $contact;
            });

        $useCase = new CreateContactUseCase($repository);

        $contact = $useCase->execute(new CreateContactDTO(
            name: 'Joao Silva',
            email: 'joao@empresa.com.br',
            phone: '11987654321',
        ));

        $this->assertSame(ContactStatus::Pending, $contact->status());
    }

    public function test_update_contact_use_case_updates_the_loaded_contact(): void
    {
        $contact = Contact::reconstitute(
            id: 1,
            name: 'Joao',
            email: new Email('joao@gmail.com'),
            phone: new Phone('21987654321'),
            score: 0,
            status: ContactStatus::Pending,
        );

        $repository = $this->createMock(ContactRepositoryInterface::class);
        $repository
            ->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($contact);
        $repository
            ->expects($this->once())
            ->method('save')
            ->willReturnCallback(function (Contact $updatedContact): Contact {
                $this->assertSame('Joao Silva', $updatedContact->name());
                $this->assertSame('joao@empresa.com.br', $updatedContact->email()->value());
                $this->assertSame('11987654321', $updatedContact->phone()->value());

                return $updatedContact;
            });

        $useCase = new UpdateContactUseCase($repository);

        $updatedContact = $useCase->execute(1, new UpdateContactDTO(
            name: 'Joao Silva',
            email: 'joao@empresa.com.br',
            phone: '11987654321',
        ));

        $this->assertSame('Joao Silva', $updatedContact->name());
    }

    public function test_get_contact_use_case_returns_the_contact(): void
    {
        $contact = Contact::reconstitute(
            id: 1,
            name: 'Joao Silva',
            email: new Email('joao@empresa.com.br'),
            phone: new Phone('11987654321'),
            score: 0,
            status: ContactStatus::Pending,
        );

        $repository = $this->createMock(ContactRepositoryInterface::class);
        $repository
            ->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($contact);

        $useCase = new GetContactUseCase($repository);

        $foundContact = $useCase->execute(1);

        $this->assertSame($contact, $foundContact);
    }

    public function test_get_contact_use_case_throws_when_contact_does_not_exist(): void
    {
        $repository = $this->createMock(ContactRepositoryInterface::class);
        $repository
            ->expects($this->once())
            ->method('findById')
            ->with(99)
            ->willReturn(null);

        $useCase = new GetContactUseCase($repository);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Contato não encontrado.');

        $useCase->execute(99);
    }

    public function test_list_contacts_use_case_returns_the_paginated_result(): void
    {
        $expectedResult = [
            'items' => [
                Contact::reconstitute(
                    id: 1,
                    name: 'Joao Silva',
                    email: new Email('joao@empresa.com.br'),
                    phone: new Phone('11987654321'),
                    score: 0,
                    status: ContactStatus::Pending,
                ),
            ],
            'total' => 1,
            'page' => 1,
            'per_page' => 15,
        ];

        $repository = $this->createMock(ContactRepositoryInterface::class);
        $repository
            ->expects($this->once())
            ->method('paginate')
            ->with(1, 15)
            ->willReturn($expectedResult);

        $useCase = new ListContactsUseCase($repository);

        $result = $useCase->execute();

        $this->assertSame($expectedResult, $result);
    }

    public function test_delete_contact_use_case_deletes_the_loaded_contact(): void
    {
        $contact = Contact::reconstitute(
            id: 1,
            name: 'Joao Silva',
            email: new Email('joao@empresa.com.br'),
            phone: new Phone('11987654321'),
            score: 0,
            status: ContactStatus::Pending,
        );

        $repository = $this->createMock(ContactRepositoryInterface::class);
        $repository
            ->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($contact);
        $repository
            ->expects($this->once())
            ->method('delete')
            ->with($contact);

        $useCase = new DeleteContactUseCase($repository);

        $useCase->execute(1);

        $this->assertTrue(true);
    }
}
