<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Entity\Country;
use App\Entity\User;
use App\Repository\CountryRepositoryInterface;
use App\WineBundle\Entity\Grape;
use App\WineBundle\Entity\Region;
use App\WineBundle\Entity\TasteProfile;
use App\WineBundle\Entity\Wine;
use App\WineBundle\Repository\GrapeRepositoryInterface;
use App\WineBundle\Repository\RegionRepositoryInterface;
use App\WineBundle\Repository\TasteProfileRepositoryInterface;
use App\WineBundle\Repository\WineRepositoryInterface;

trait EntityFactory
{
    public function createCountry(User $user): Country
    {
        $country = new Country();
        $country->setUser($user);
        $country->setName(uniqid('country'));

        $countryRepository = static::getContainer()->get(CountryRepositoryInterface::class);

        return $countryRepository->create($country);
    }

    public function createRegion(User $user): Region
    {
        $country = $this->createCountry($user);

        $region = new Region();
        $region->setName(uniqid('region'));
        $region->setUser($user);
        $region->setCountry($country);

        $regionRepository = static::getContainer()->get(RegionRepositoryInterface::class);

        return $regionRepository->create($region);
    }

    public function createGrape(User $user): Grape
    {
        $grape = new Grape();
        $grape->setUser($user);
        $grape->setName(uniqid('grape'));
        $grape->setType(array_rand(['red' => 0, 'white' => 1]));

        $grapeRepository = static::getContainer()->get(GrapeRepositoryInterface::class);

        return $grapeRepository->create($grape);
    }

    public function createTasteProfile(User $user): TasteProfile
    {
        $tasteProfile = new TasteProfile();
        $tasteProfile->setUser($user);
        $tasteProfile->setName(uniqid('tasteProfile'));
        $tasteProfile->setSecondName(uniqid('tasteProfileSecond'));
        $tasteProfile->setDescription(uniqid('tasteProfileDescription'));

        $tasteProfileRepository = static::getContainer()->get(TasteProfileRepositoryInterface::class);

        return $tasteProfileRepository->create($tasteProfile);
    }

    public function createWine(User $user): Wine
    {
        $region = $this->createRegion($user);

        $wine = new Wine();
        $wine->setUser($user);
        $wine->setName(uniqid('wine'));
        $wine->setRegion($region);
        $wine->setCountry($region->getCountry());
        $wine->setLabelExtension('png');
        $wine->setYear(random_int(1000, 9999));
        $wine->setRating(random_int(1, 10));
        $wine->setPrice(random_int(1, 100));
        $wine->setCreatedAt(time());

        $wineRepository = static::getContainer()->get(WineRepositoryInterface::class);

        return $wineRepository->create($wine);
    }
}
