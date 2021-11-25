<?php

declare(strict_types=1);

namespace App\Tests\Feature\Controller;

use App\Tests\MigrationsTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;

class RegisterSecurityTest extends WebTestCase
{
    use MigrationsTrait;

    protected AbstractBrowser $client;

    public function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();

        $this->migrate();
    }

    public function testRegisterLoginLogout(): void
    {
        $crawler = $this->client->request('GET', '/register');

        $buttonCrawlerNode = $crawler->selectButton('Register');

        $form = $buttonCrawlerNode->form();

        $form['registration_form[username]'] = 'john';
        $form['registration_form[email]'] = 'john@secret.com';
        $form['registration_form[plainPassword]'] = 'secret';
        $form['registration_form[agreeTerms]'] = '1';

        $this->client->submit($form);

        $this->assertResponseRedirects('/');

        $crawler = $this->client->request('GET', '/login');

        $buttonCrawlerNode = $crawler->selectButton('Sign in');

        $form = $buttonCrawlerNode->form();

        $form['username'] = 'john@secret.com';
        $form['password'] = 'secret';

        $this->client->submit($form);

        $this->assertResponseRedirects('/');

        $this->client->request('GET', '/logout');

        $this->assertResponseRedirects();
    }

    public function tearDown(): void
    {
        parent::tearDown();

        $this->drop();
    }
}
