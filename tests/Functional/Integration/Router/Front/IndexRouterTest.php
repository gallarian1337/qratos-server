<?php

declare(strict_types=1);

namespace App\Tests\Functional\Integration\Router\Front;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

final class IndexRouterTest extends WebTestCase
{
    private KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = self::createClient();
    }

    public function testIndexGet(): void
    {
        $this->client->request(Request::METHOD_GET, '/');

        self::assertResponseIsSuccessful();
    }
}
