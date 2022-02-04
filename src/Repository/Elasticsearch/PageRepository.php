<?php

declare(strict_types=1);

namespace App\Repository\Elasticsearch;

use App\Entity\Elasticsearch\Page;
use Elastica\Client;
use Elastica\Query;
use FOS\ElasticaBundle\Persister\ObjectPersisterInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelInterface;
use Psr\Container\ContainerInterface;

class PageRepository implements PageRepositoryInterface
{
    private Client $client;

    public function __construct(
        private KernelInterface $kernel,
        private ContainerInterface $container,
        private ObjectPersisterInterface $objectPersister,
    ) {
        $this->client = new Client(['host' => 'host.docker.internal', 'port' => 9200]);
    }

    public function index(\App\Entity\Page $page): void
    {
        $this->objectPersister->insertOne($page);
    }

    public function getByTitle(string $title): Page
    {
        $query = new Query([
            'query' => [
                'match' => [
                    'title' => $title,
                ],
            ],
        ]);

        return $this->search($query);
    }

    public function getBySlug(string $slug): Page
    {
        $query = new Query([
            'query' => [
                'match' => [
                    'slug' => $slug,
                ],
            ],
        ]);

        return $this->search($query);
    }

    private function search(Query $query): Page
    {
        $index = 'page';
        if ($this->kernel->getEnvironment() === 'test') {
            $index = 'page_test' . $this->container->getParameter('test_db');
        }
        $resultArray = $this->client->getIndex($index)->search($query);

        if ($resultArray->getResults() === []) {
            throw new NotFoundHttpException('The page could not be found.');
        }

        $page = new Page();
        foreach ($resultArray[0]->getData() as $key => $value) {
            if ($key === 'author_id') {
                continue;
            }
            $page->{'set' . ucwords($key)}($value);
        }

        return $page;
    }
}
