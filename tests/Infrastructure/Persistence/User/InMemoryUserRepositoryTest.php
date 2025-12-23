<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Persistence\User;

use App\Domain\User\User;
use App\Domain\User\UserNotFoundException;
use App\Infrastructure\Persistence\User\InMemoryUserRepository;
use Tests\TestCase;

final class InMemoryUserRepositoryTest extends TestCase
{
    public function testFindAll(): void
    {
        $user = new User(1, 'bill.gates', 'Bill', 'Gates');

        $inMemoryUserRepository = new InMemoryUserRepository([1 => $user]);

        $this->assertEquals([$user], $inMemoryUserRepository->findAll());
    }

    public function testFindAllUsersByDefault(): void
    {
        $users = [
            1 => new User(1, 'bill.gates', 'Bill', 'Gates'),
            2 => new User(2, 'steve.jobs', 'Steve', 'Jobs'),
            3 => new User(3, 'mark.zuckerberg', 'Mark', 'Zuckerberg'),
            4 => new User(4, 'evan.spiegel', 'Evan', 'Spiegel'),
            5 => new User(5, 'jack.dorsey', 'Jack', 'Dorsey'),
        ];

        $inMemoryUserRepository = new InMemoryUserRepository();

        $this->assertEquals(array_values($users), $inMemoryUserRepository->findAll());
    }

    public function testFindUserOfId(): void
    {
        $user = new User(1, 'bill.gates', 'Bill', 'Gates');

        $inMemoryUserRepository = new InMemoryUserRepository([1 => $user]);

        $this->assertEquals($user, $inMemoryUserRepository->findUserOfId(1));
    }

    public function testFindUserOfIdThrowsNotFoundException(): void
    {
        $inMemoryUserRepository = new InMemoryUserRepository([]);
        $this->expectException(UserNotFoundException::class);
        $inMemoryUserRepository->findUserOfId(1);
    }
}
