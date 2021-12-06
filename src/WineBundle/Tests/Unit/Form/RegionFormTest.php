<?php

declare(strict_types=1);

namespace App\WineBundle\Tests\Unit\Form;

use App\Entity\User;
use App\WineBundle\Entity\Region;
use App\WineBundle\Form\RegionForm;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Authenticator\Token\PostAuthenticationToken;

class RegionFormTest extends TypeTestCase
{
    private object $token;

    protected function setUp(): void
    {
        $this->token = $this->createMock(TokenStorageInterface::class);
        $this->token->method('getToken')->willReturn(new PostAuthenticationToken(new User(), 'main', ['ROLE_USER']));

        parent::setUp();
    }

    protected function getExtensions(): array
    {
       $type = new RegionForm($this->token);

        return [
            new PreloadedExtension([$type], []),
        ];
    }

    public function testSubmitModel()
    {
        $name = 'Bordeaux';
        $formData = [
            'name' => $name,
            'country' => 1,
        ];

        $region = new Region();

        $form = $this->factory->create(RegionForm::class, $region);

        $expected = new Region();
        $expected->setName($name);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($expected, $region);
    }
}
