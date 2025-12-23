<?php

declare(strict_types=1);

namespace Tests\Application\Actions\User;

use App\Application\Actions\ActionPayload;
use App\Domain\User\UserRepository;
use App\Domain\User\User;
use DI\Container;
use Tests\TestCase;

final class ListUserActionTest extends TestCase
{
    public function testAction(): void
    {
        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        $user = new User(1, 'bill.gates', 'Bill', 'Gates');

        $objectProphecy = $this->prophesize(UserRepository::class);
        $objectProphecy
            ->findAll()
            ->willReturn([$user])
            ->shouldBeCalledOnce();

        $container->set(UserRepository::class, $objectProphecy->reveal());

        $serverRequest = $this->createRequest('GET', '/users');
        $response = $app->handle($serverRequest);

        $payload = (string) $response->getBody();
        $actionPayload = new ActionPayload(200, [$user]);
        $serializedPayload = json_encode($actionPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }
}
