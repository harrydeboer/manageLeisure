<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use App\Tests\Functional\WebTestCase;

class RegisterSecurityChangePasswordTest extends WebTestCase
{
    public function testRegisterLoginLogout(): void
    {
        $crawler = $this->client->request('GET', '/register');

        $buttonCrawlerNode = $crawler->selectButton('Register');

        $form = $buttonCrawlerNode->form();

        $form['registration[name]'] = 'John';
        $form['registration[email]'] = 'john@secret.com';
        $form['registration[plainPassword][first]'] = 'secret';
        $form['registration[plainPassword][second]'] = 'secret';

        $this->client->submit($form);

        $this->assertResponseRedirects('/');

        $crawler = $this->client->request('GET', '/login');

        $buttonCrawlerNode = $crawler->selectButton('Sign in');

        $form = $buttonCrawlerNode->form();

        $form['email'] = 'john@secret.com';
        $form['password'] = 'secret';

        $this->client->submit($form);

        $this->assertResponseRedirects('/');

        $crawler = $this->client->request('GET', '/change-password');

        $buttonCrawlerNode = $crawler->selectButton('Change password');

        $form = $buttonCrawlerNode->form();

        $form['change_password[plainPassword][first]'] = 'newNew';
        $form['change_password[plainPassword][second]'] = 'newNew';

        $this->client->submit($form);

        $this->assertResponseRedirects('/');

        $this->client->request('GET', '/logout');

        $this->assertResponseRedirects();
    }
}
