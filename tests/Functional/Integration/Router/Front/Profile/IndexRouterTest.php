<?php

declare(strict_types=1);

namespace App\Tests\Functional\Integration\Router\Front\Profile;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

final class IndexRouterTest extends WebTestCase
{
    private KernelBrowser $client;
    private UserRepository $userRepository;

    public function setUp(): void
    {
        $this->client = self::createClient();
        $this->userRepository = $this->client->getContainer()->get(UserRepository::class);
    }

    public function testAccessProfileIfIsNotLogged(): void
    {
        $this->client->request(Request::METHOD_GET, '/profil');

        self::assertResponseRedirects();
    }

    public function testAccessProfileIfIsLogged(): void
    {
        $user = $this->userRepository->findOneBy(['email' => 'admin1@quizz.test']);
        $this->client->loginUser($user);

        $this->client->request(Request::METHOD_GET, '/profil');

        $this->assertResponseIsSuccessful();
    }
}
