<?php

declare(strict_types=1);

namespace App\Tests\AdminBundle\Unit\Form;

use App\AdminBundle\Form\MediaFilterType;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\HttpKernel\KernelInterface;
use PHPUnit\Framework\MockObject\MockObject;

class MediaFilterTypeTest extends TypeTestCase
{
    private KernelInterface|MockObject $kernel;

    protected function setUp(): void
    {
        $this->kernel = $this->createMock(KernelInterface::class);
        $this->kernel->method('getProjectDir')->willReturn('/var/www/html');

        parent::setUp();
    }

    protected function getExtensions(): array
    {
        $type = new MediaFilterType($this->kernel);

        return [
            new PreloadedExtension([$type], []),
        ];
    }

    public function testSubmitModel(): void
    {
        $formData = [
            'year' => date('Y'),
            'month' => date('m'),
        ];

        $form = $this->factory->create(MediaFilterType::class);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
    }
}
