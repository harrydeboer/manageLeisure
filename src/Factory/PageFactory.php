<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Page;
use App\Repository\PageRepositoryInterface;

class PageFactory extends AbstractFactory
{
    public function __construct(
        private UserFactory $userFactory,
        private PageRepositoryInterface $pageRepository,
    ) {
    }

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

        return $this->pageRepository->create($page);
    }
}
