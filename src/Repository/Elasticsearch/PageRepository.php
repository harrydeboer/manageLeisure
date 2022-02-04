<?php

declare(strict_types=1);

namespace App\Repository\Elasticsearch;

use App\Entity\Elasticsearch\Page;
use Elastica\Client;
use Elastica\Query;

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

        $result = $this->client->getIndex('page')->search($query)[0];

        $page = new Page();
        foreach ($result->getData() as $key => $value) {
            if ($key === 'author_id') {
                continue;
            }
            $page->{'set' . ucwords($key)}($value);
        }

        return $page;
    }
}
