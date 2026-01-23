<?php

declare(strict_types=1);

namespace App\Tests\Functional\Integration\Router\Front\Security;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

final class RegistrationRouterTest extends WebTestCase
{
    private KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = self::createClient();
    }

    public function testRegistrationGet(): void
    {
        $this->client->request(Request::METHOD_GET, '/inscription');

        $this->assertResponseIsSuccessful();
    }

    public function testRegistrationPost(): void
    {
        $this->client->request(Request::METHOD_POST, '/inscription', ['registration' => []]);

        $this->assertResponseIsUnprocessable();
    }
}
