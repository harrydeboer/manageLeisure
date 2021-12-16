<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use App\Repository\MailUserRepositoryInterface;
use App\Repository\UserRepositoryInterface;
use App\Tests\Functional\AuthWebTestCase;

class MailUserControllerTest extends AuthWebTestCase
{
    public function testCreateUpdateDelete(): void
    {
        $userRepository = $this->getContainer()->get(UserRepositoryInterface::class);
        $this->user->setRoles(['ROLE_ADMIN']);
        $userRepository->update();
        $this->client->loginUser($this->user);

        $this->client->request('GET', '/mail-user');

        $this->assertResponseIsSuccessful();

        $crawler = $this->client->request('GET', '/mail-user/create');

        $buttonCrawlerNode = $crawler->selectButton('Create');

        $form = $buttonCrawlerNode->form();

        $form['create_mail_user[domain]'] = 'test.com';
        $form['create_mail_user[password]'] = 'testTest';
        $form['create_mail_user[email]'] = 'test@test.nl';

        $this->client->submit($form);

        $this->assertResponseRedirects('/mail-user');

        $mailUserRepository = $this->getContainer()->get(MailUserRepositoryInterface::class);

        $mailUser = $mailUserRepository->findOneBy(['email' => 'test@test.nl']);

        $crawler = $this->client->request('GET', '/mail-user/edit/' . $mailUser->getId());

        $buttonCrawlerNode = $crawler->selectButton('Update');

        $form = $buttonCrawlerNode->form();

        $form['update_mail_user[domain]'] = 'test2.com';

        $this->client->submit($form);

        $this->assertResponseRedirects('/mail-user');

        $crawler = $this->client->request('GET', '/mail-user/edit/' . $mailUser->getId());

        $buttonCrawlerNode = $crawler->selectButton('Delete');

        $form = $buttonCrawlerNode->form();

        $this->client->submit($form);

        $this->assertResponseRedirects('/mail-user');
    }
}
