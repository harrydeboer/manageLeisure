<?php

declare(strict_types=1);

namespace App\Tests\AdminBundle\Functional\Controller;

use App\Repository\UserRepositoryInterface;
use App\Tests\Functional\AuthAdminWebTestCase;

class UserControllerTest extends AuthAdminWebTestCase
{
    public function testCreateUpdateDelete(): void
    {
        $this->client->request('GET', '/admin/user');

        $this->assertResponseIsSuccessful();

        $crawler = $this->client->request('GET', '/admin/user/create');

        $buttonCrawlerNode = $crawler->selectButton('Create');

        $form = $buttonCrawlerNode->form();

        $form['create_user[name]'] = 'Test';
        $form['create_user[email]'] = 'test@test.com';
        $form['create_user[isVerified]'] = 1;
        $form['create_user[plainPassword][first]'] = 'secret';
        $form['create_user[plainPassword][second]'] = 'secret';

        $this->client->submit($form);

        $this->assertResponseRedirects('/admin/user');

        $userRepository = $this->getContainer()->get(UserRepositoryInterface::class);

        $user = $userRepository->findOneBy(['email' => 'test@test.com']);
        $id = $user->getId();

        $crawler = $this->client->request('GET', '/admin/user/edit/' . $id);

        $buttonCrawlerNode = $crawler->selectButton('Update');

        $form = $buttonCrawlerNode->form();

        $updatedName = 'Test2';
        $form['update_user[name]'] = $updatedName;

        $this->client->submit($form);

        $this->assertResponseRedirects('/admin/user');

        $userRepository = $this->getContainer()->get(UserRepositoryInterface::class);

        $user = $userRepository->findOneBy(['email' => 'test@test.com']);

        $this->assertEquals($updatedName, $user->getName());

        $crawler = $this->client->request('GET', '/admin/user/edit/' . $id);

        $buttonCrawlerNode = $crawler->selectButton('Delete');

        $form = $buttonCrawlerNode->form();

        $this->client->submit($form);

        $this->assertResponseRedirects('/admin/user');

        $userRepository = $this->getContainer()->get(UserRepositoryInterface::class);

        $this->assertNull($userRepository->find($id));
    }
}
