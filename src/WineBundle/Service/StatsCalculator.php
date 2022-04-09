<?php

declare(strict_types=1);

namespace App\WineBundle\Service;

class StatsCalculator
{
    public static function average(array $wines): array
    {
        $average = [];
        $rating = 0;
        $price = 0;
        $percentage = 0;
        foreach ($wines as $wine) {
            $rating += $wine->getRating();
            $price += $wine->getPrice();
            $percentage += $wine->getPercentage();
        }
        if (count($wines) === 0) {
            $average['rating'] = 'N/A';
            $average['price'] = 'N/A';
            $average['percentage'] = 'N/A';
        } else {
            $average['rating'] = $rating / count($wines);
            $average['price'] = $price / count($wines);
            $average['percentage'] = $percentage / count($wines);
        }

        return $average;
    }

    public static function pieChart(array $wines): array
    {
        $country = [];
        foreach ($wines as $wine) {
            if (isset($country[$wine->getCountry()->getName()])) {
                $country[$wine->getCountry()->getName()]++;
            } else {
                $country[$wine->getCountry()->getName()] = 1;
            }
        }
        arsort($country);

        return $country;
    }
}
