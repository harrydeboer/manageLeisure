<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Page;
use App\Repository\PageRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\HttpKernel\KernelInterface;
use Exception;

class PageFactory extends AbstractFactory
{
    public function __construct(
        private UserFactory $userFactory,
        private PageRepositoryInterface $pageRepository,
        private \App\Repository\Elasticsearch\PageRepositoryInterface $pageRepositoryElasticsearch,
    ) {
    }

    /**
     * @throws Exception
     */
    public function create(array $params = []): Page
    {
        $paramsParent = [];
        if (isset($params['user'])) {
            $paramsParent['user'] = $params['user'];
        } else {
            $paramsParent['user'] = $this->userFactory->create();
        }

        $page = new Page();
        $page->setAuthor($paramsParent['user']);
        $page->setTitle(uniqid('title'));
        $page->setSlug(uniqid('slug'));
        $page->setPublishedAt(time());
        $page->setSummary($this->generateRandomString(20));
        $page->setContent($this->generateRandomString(100));

        $this->setParams($params, $page);

        $page = $this->pageRepository->create($page);

        $this->pageRepositoryElasticsearch->index($page);

        sleep(2);

        return $page;
    }
}
