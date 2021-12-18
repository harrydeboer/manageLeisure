<?php

declare(strict_types=1);

namespace App\MovieBundle;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class IMDBId
{
    public static function getResponseObject(string $title, string $apikey, int $year=null): object
    {
        // create curl resource
        $ch = curl_init();

        $contentObj = self::getCurlResult($ch, False, $title, $apikey, $year);

        if (isset($contentObj->Error)) {
            curl_close($ch);
            return $contentObj;
        }

        if (count($contentObj->Search) === 1) {
            return self::getCurlResult($ch, True, $title, $apikey, $year);
        }

        curl_close($ch);

        return $contentObj;
    }

    /**
     * @throws NotFoundHttpException
     */
    private static function getCurlResult($ch, bool $isSingle, string $title, string $apikey, ?int $year): ?object
    {
        if ($isSingle) {
            $queryString = 't';
        } else {
            $queryString = 's';
        }

        if (is_null($year)) {
            curl_setopt($ch, CURLOPT_URL, "https://www.omdbapi.com/?apikey=" . $apikey .
                "&" . $queryString . "=" . urlencode($title));
        } else {
            curl_setopt($ch, CURLOPT_URL, "https://www.omdbapi.com/?apikey=" . $apikey .
                "&" . $queryString . "=" . urlencode($title) . "&y=" . (string) $year);
        }

        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // $output contains the output string
        $output = curl_exec($ch);

        return json_decode($output);
    }

    public static function getSingleMovie(string $id, string$apikey): object
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://www.omdbapi.com/?apikey=" . $apikey .
            "&i=" . $id);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // $output contains the output string
        $output = curl_exec($ch);

        curl_close($ch);

        return json_decode($output);
    }
}
