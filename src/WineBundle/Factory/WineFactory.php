<?php

declare(strict_types=1);

namespace App\WineBundle\Factory;

use App\Factory\AbstractFactory;
use App\Factory\UserFactory;
use App\WineBundle\Entity\Wine;
use App\WineBundle\Repository\WineRepositoryInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;

class WineFactory extends AbstractFactory
{
    public function __construct(
        private GrapeFactory $grapeFactory,
        private RegionFactory $regionFactory,
        private SubregionFactory $subregionFactory,
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
        $grape = $this->grapeFactory->create();
        $grapes = new ArrayCollection();
        $grapes->add($grape);
        if (isset($params['country'])) {
            $region = $this->regionFactory->create($params['country']);
        } else {
            $region = $this->regionFactory->create();
        }
        if (isset($params['region'])) {
            $subregion = $this->subregionFactory->create($params['region']);
        } else {
            $subregion = $this->subregionFactory->create();
        }

        $wine = new Wine();
        $wine->setUser($paramsParent['user']);
        $wine->setName(uniqid('wine'));
        $types = Wine::TYPES;
        $randomTypeKey = array_rand($types);
        $wine->setType($types[$randomTypeKey]);
        $wine->setRegion($region);
        $wine->setCountry($region->getCountry());
        $wine->setTasteProfile($tasteProfile);
        $wine->setGrapes($grapes);
        $wine->setCountry($region->getCountry());
        $wine->setSubregion($subregion);
        $wine->setLabelExtension('png');
        $wine->setYear(random_int(1000, 9999));
        $wine->setRating(random_int(1, 10));
        $wine->setPrice(random_int(1, 100));
        $wine->setPercentage(random_int(10, 20));
        $wine->setCreatedAt(time());

        $this->setParams($params, $wine);

        return $this->wineRepository->create($wine);
    }
}
