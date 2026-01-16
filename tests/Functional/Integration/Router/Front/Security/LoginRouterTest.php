<?php

declare(strict_types=1);

namespace App\Tests\Functional\Integration\Router\Front\Security;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

final class LoginRouterTest extends WebTestCase
{
    private KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = self::createClient();
    }

    public function testLoginGet(): void
    {
        $this->client->request(Request::METHOD_GET, '/connexion');

        $this->assertResponseIsSuccessful();
    }

    public function testLoginPost(): void
    {
        $this->client->request(Request::METHOD_POST, '/connexion');

        $this->assertResponseStatusCodeSame(400);
    }
}
