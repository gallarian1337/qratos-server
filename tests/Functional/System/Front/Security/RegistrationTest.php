<?php

declare(strict_types=1);

namespace App\Tests\Functional\System\Front\Security;

use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class RegistrationTest extends WebTestCase
{
    private KernelBrowser $client;
    private UrlGeneratorInterface $urlGenerator;

    public function setUp(): void
    {
        $this->client = self::createClient();
        $this->urlGenerator = $this->client->getContainer()->get(UrlGeneratorInterface::class);
        $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('front_registration'));
        $buttonForm = $crawler->selectButton('m\'inscrire');
        $this->form = $buttonForm->form();
    }

    public function testRegistrationIsSuccessfully(): void
    {
        $this->form['registration[email]'] = 'email-test@gmail.com';
        $this->form['registration[nickname]'] = 'email-test';
        $this->form['registration[password]'] = '123456789101112';
        $this->client->submit($this->form);

        $this->assertResponseRedirects($this->urlGenerator->generate('front_login'));
        $this->client->followRedirect();
    }

    #[DataProvider('invalidData')]
    public function testForm(array $data, string $msgError): void
    {
        $this->form['registration[email]'] = $data['email'];
        $this->form['registration[nickname]'] = $data['nickname'];
        $this->form['registration[password]'] = $data['password'];
        $this->client->submit($this->form);

        $this->assertResponseIsUnprocessable();
        $this->assertSelectorExists('ul li');
        $this->assertSelectorTextContains('ul li', $msgError);
    }

    public static function invalidData(): iterable
    {
        yield 'empty email' => [
            'data' => [
                'email' => '',
                'nickname' => 'email-test',
                'password' => '123456789101112'
            ],
            'msgError' => 'Vous devez entrer une adresse email valide'
        ];
        yield 'min email' => [
            'data' => [
                'email' => 'a@b.c',
                'nickname' => 'email-test',
                'password' => '123456789101112'
            ],
            'msgError' => 'l\'email doit faire au minimum 6 caracteres.'
        ];
        yield 'max email' => [
            'data' => [
                'email' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa@b.c', //@phpcs:ignore
                'nickname' => 'email-test',
                'password' => '123456789101112'
            ],
            'msgError' => 'l\'email doit faire au maximum 180 caracteres.'
        ];
        yield 'invalid format email' => [
            'data' => [
                'email' => 'email-test',
                'nickname' => 'email-test',
                'password' => '123456789101112'
            ],
            'msgError' => 'This value is not a valid email address.'
        ];
        yield 'empty nickname' => [
            'data' => [
                'email' => 'email-test@gmail.com',
                'nickname' => '',
                'password' => '123456789101112'
            ],
            'msgError' => 'Vous devez entrer un pseudonyme'
        ];
        yield 'min nickname' => [
            'data' => [
                'email' => 'email-test@gmail.com',
                'nickname' => 'a',
                'password' => '123456789101112'
            ],
            'msgError' => 'le pseudo doit faire au minimum 2 caracteres.'
        ];
        yield 'max nickname' => [
            'data' => [
                'email' => 'email-test@gmail.com',
                'nickname' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
                'password' => '123456789101112'
            ],
            'msgError' => 'le pseudo doit faire au maximum 25 caracteres.'
        ];
        yield 'empty password' => [
            'data' => [
                'email' => 'email-test@gmail.com',
                'nickname' => 'email-test',
                'password' => ''
            ],
            'msgError' => 'Vous devez entrer un mot de passe'
        ];
        yield 'min password' => [
            'data' => [
                'email' => 'email-test@gmail.com',
                'nickname' => 'email-test',
                'password' => 'a'
            ],
            'msgError' => 'le mot de passe doit faire au minimum 12 caracteres.'
        ];
        yield 'max password' => [
            'data' => [
                'email' => 'email-test@gmail.com',
                'nickname' => 'email-test',
                'password' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa' //@phpcs:ignore
            ],
            'msgError' => 'le mot de passe doit faire au maximum 255 caracteres.'
        ];
        // ajouter les tests apres avoir créer le validator custom
    }
}
