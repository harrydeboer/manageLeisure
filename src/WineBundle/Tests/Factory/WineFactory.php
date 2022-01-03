<?php

declare(strict_types=1);

namespace App\WineBundle\Tests\Factory;

use App\Tests\Factory\AbstractFactory;
use App\Tests\Factory\UserFactory;
use App\WineBundle\Entity\Wine;
use App\WineBundle\Repository\WineRepositoryInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;

class WineFactory extends AbstractFactory
{
    public function __construct(
        private GrapeFactory $grapeFactory,
        private RegionFactory $regionFactory,
        private TasteProfileFactory $tasteProfileFactory,
        private UserFactory $userFactory,
        private WineRepositoryInterface $wineRepository,
    ) {
    }

    /**
     * @throws Exception
     */
    public function create(array $params = []): Wine
    {
        /**
         * When a Wine is created the parent entities Grape, TasteProfile, Region and Country must have the same
         * user which is given in $params or created from the UserFactory.
         * The Wine Region must have the same country as Wine.
         */
        $paramsParent = [];
        if (isset($params['user'])) {
            $paramsParent['user'] = $params['user'];
        } else {
            $paramsParent['user'] = $this->userFactory->create();
        }
        $tasteProfile = $this->tasteProfileFactory->create($paramsParent);
        $grape = $this->grapeFactory->create($paramsParent);
        $grapes = new ArrayCollection();
        $grapes->add($grape);
        if (isset($params['country'])) {
            $paramsParent['country'] = $params['country'];
        }
        $region = $this->regionFactory->create($paramsParent);

        $wine = new Wine();
        $wine->setUser($region->getUser());
        $wine->setName(uniqid('wine'));
        $types = ['red', 'white', 'rosé', 'orange', 'sparkling', 'dessert', 'fortified'];
        $randomTypeKey = array_rand($types);
        $wine->setType($types[$randomTypeKey]);
        $wine->setRegion($region);
        $wine->setCountry($region->getCountry());
        $wine->setTasteProfile($tasteProfile);
        $wine->setGrapes($grapes);
        $wine->setCountry($region->getCountry());
        $wine->setLabelExtension('png');
        $wine->setYear(random_int(1000, 9999));
        $wine->setRating(random_int(1, 10));
        $wine->setPrice(random_int(1, 100));
        $wine->setCreatedAt(time());

        $this->setParams($params, $wine);

        return $this->wineRepository->create($wine);
    }
}
