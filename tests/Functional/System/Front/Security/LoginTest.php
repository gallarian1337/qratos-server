<?php

declare(strict_types=1);

namespace App\Tests\Functional\System\Front\Security;

use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class LoginTest extends WebTestCase
{
    private KernelBrowser $client;
    private UrlGeneratorInterface $urlGenerator;

    public function setUp(): void
    {
        $this->client = self::createClient();
        $this->urlGenerator = $this->client->getContainer()->get(UrlGeneratorInterface::class);
        $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('front_login'));
        $buttonForm = $crawler->selectButton('me connecter');
        $this->form = $buttonForm->form();
    }

    public function testLoginIsSuccessfully(): void
    {
        $this->form['email'] = 'admin1@quizz.test';
        $this->form['password'] = '1234567891011';
        $this->client->submit($this->form);

        $this->assertResponseRedirects($this->urlGenerator->generate('front_index'));
        $this->client->followRedirect();
    }

    public function testLoginWithRememberMe(): void
    {
        $this->form['email'] = 'admin1@quizz.test';
        $this->form['password'] = '1234567891011';
        $this->form['_remember_me'] = true;
        $this->client->submit($this->form);

        $this->assertResponseRedirects($this->urlGenerator->generate('front_index'));
        $this->client->followRedirect();

        $cookies = $this->client->getCookieJar();
        $this->assertNotNull($cookies->get('QRATOS_REMEMBER_ME'));
    }

    public function testRememberMePersistsAfterLogin(): void
    {
        $this->form['email'] = 'admin1@quizz.test';
        $this->form['password'] = '1234567891011';
        $this->form['_remember_me'] = true;
        $this->client->submit($this->form);

        $this->client->followRedirect();

        // on vérifie que le cookie "QRATOS_REMEMBER_ME" existe
        $rememberMeCookie = $this->client->getCookieJar()->get('QRATOS_REMEMBER_ME');
        $this->assertNotNull($rememberMeCookie, 'Le cookie "QRATOS_REMEMBER_ME" doit être présent');

        $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('front_profile_index'));
        $this->assertResponseIsSuccessful();
    }

    #[DataProvider('invalidData')]
    public function testForm(array $data, string $msgError): void
    {
        $this->form['email'] = $data['identifier'];
        $this->form['password'] = $data['password'];
        $this->client->submit($this->form);

        $this->assertResponseStatusCodeSame(302);
        $this->client->followRedirect();
        $this->assertSelectorExists('div.alert');
        $this->assertSelectorTextContains('div.alert', $msgError);
    }

    public static function invalidData(): iterable
    {
        yield 'empty email' => [
            'data' => [
                'email' => '',
                'password' => '12345'
            ],
            'msgError' => 'Invalid credentials.'
        ];
        yield 'invalid format email' => [
            'data' => [
                'email' => 'email-test',
                'password' => '12345'
            ],
            'msgError' => 'Invalid credentials.'
        ];
        yield 'empty password' => [
            'data' => [
                'email' => 'admin1@quizz.test',
                'password' => ''
            ],
            'msgError' => 'Invalid credentials.'
        ];
    }
}
