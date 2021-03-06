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
            $average['rating'] = round($rating / count($wines), 1);
            $average['price'] = round($price / count($wines), 1);
            $average['percentage'] = round($percentage / count($wines), 1);
        }

        return $average;
    }

    public static function pieChart(array $wines): string
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

        return json_encode($country);
    }
}
