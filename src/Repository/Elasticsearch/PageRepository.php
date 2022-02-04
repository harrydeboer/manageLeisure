<?php

declare(strict_types=1);

namespace App\Repository\Elasticsearch;

use App\Entity\Elasticsearch\Page;
use Elastica\Client;
use Elastica\Query;
use FOS\ElasticaBundle\Persister\ObjectPersisterInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelInterface;

class PageRepository implements PageRepositoryInterface
{
    private Client $client;

    public function __construct(
        private KernelInterface $kernel,
        private ObjectPersisterInterface $objectPersister,
    ) {
        $this->client = new Client(['host' => 'host.docker.internal', 'port' => 9200]);
    }

    public function index(\App\Entity\Page $page): void
    {
        $this->objectPersister->insertOne($page);
    }

    public function getByTitle(string $title, ?string $testDb): Page
    {
        $query = new Query([
            'query' => [
                'match' => [
                    'title' => $title,
                ],
            ],
        ]);

        return $this->search($query, $testDb);
    }

    public function getBySlug(string $slug, ?string $testDb): Page
    {
        $query = new Query([
            'query' => [
                'match' => [
                    'slug' => $slug,
                ],
            ],
        ]);

        return $this->search($query, $testDb);
    }

    private function search(Query $query, ?string $testDb): Page
    {
        $index = 'page';
        if ($this->kernel->getEnvironment() === 'test') {
            $index = 'page_test' . $testDb;
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
