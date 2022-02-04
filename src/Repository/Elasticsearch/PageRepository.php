<?php

declare(strict_types=1);

namespace App\Repository\Elasticsearch;

use App\Entity\Elasticsearch\Page;
use Elastica\Client;
use Elastica\Query;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PageRepository implements PageRepositoryInterface
{
    private Client $client;

    public function __construct() {
        $this->client = new Client(['host' => 'host.docker.internal', 'port' => 9200]);
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

        $resultArray = $this->client->getIndex('page')->search($query);

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

    public function getBySlug(string $slug): Page
    {
        $query = new Query([
            'query' => [
                'match' => [
                    'slug' => $slug,
                ],
            ],
        ]);

        $resultArray = $this->client->getIndex('page')->search($query);

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
