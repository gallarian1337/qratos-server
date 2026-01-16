<?php

declare(strict_types=1);

namespace App\Tests\Functional\System\Front;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class IndexTest extends WebTestCase
{
    private KernelBrowser $client;
    private UrlGeneratorInterface $urlGenerator;

    protected function setUp(): void
    {
        $this->client = self::createClient();
        $this->urlGenerator = $this->client->getContainer()->get(UrlGeneratorInterface::class);
    }

    public function testIndex(): void
    {
        $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('front_index'));

        $this->assertPageTitleSame('accueil | Qratos');
        $this->assertSelectorExists('h1');
        $this->assertSelectorTextContains('h1', 'accueil');
    }
}
