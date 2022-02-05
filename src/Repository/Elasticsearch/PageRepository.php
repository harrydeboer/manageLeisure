<?php /** @noinspection MissingService */

declare(strict_types=1);

namespace App\Repository\Elasticsearch;

use App\Entity\Elasticsearch\Page;
use Elastica\Client;
use Elastica\Query;
use FOS\ElasticaBundle\Persister\ObjectPersisterInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelInterface;

class PageRepository implements PageRepositoryInterface
{
    private Client $client;

    public function __construct(
        private KernelInterface $kernel,
        private ParameterBagInterface $parameterBag,
        private ObjectPersisterInterface $objectPersister,
    ) {
        $host = $this->parameterBag->get('elasticsearch_host');
        $port = $this->parameterBag->get('elasticsearch_port');
        $this->client = new Client(['host' => $host, 'port' => $port]);
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
            $index = 'page_test' . $this->parameterBag->get('test_db');
        }
        $resultArray = $this->client->getIndex($index)->search($query);

        if ($resultArray->getResults() === []) {
            throw new NotFoundHttpException('The page could not be found.');
        }

        $page = new Page();
        foreach ($resultArray[0]->getData() as $key => $value) {
            $key = lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $key))));
            $key = lcfirst(str_replace(' ', '', ucwords(str_replace('.', ' ', $key))));
            $page->{'set' . $key}($value);
        }

        return $page;
    }
}
