<?php

declare(strict_types=1);

namespace App\MovieBundle;

class IMDBReviewsScrape
{
    public static function getRating(string $id): ?float
    {
        $rating = 0;
        $totalVotes = 0;

        // create curl resource
        $ch = curl_init();

        for ($index = 1; $index <= 10; $index++) {

            // set url
            curl_setopt($ch, CURLOPT_URL, "https://www.imdb.com/title/" . $id .
                "/reviews?sort=helpfulnessScore&dir=desc&ratingFilter=" . $index);

            //return the transfer as a string
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            // $output contains the output string
            $output = curl_exec($ch);

            preg_match("@<div\s+class=\"lister\">[\s\S]+?<div\s+class=\"header\">[\s\S]+?<div>" .
                "<span>([\s\S]+?)\s+Review@", $output, $match);

            if ($match === []) {
                return null;
            }

            $rating += str_replace(',', '', $match[1]) * $index;
            $totalVotes += str_replace(',', '', $match[1]);
        }

        // close curl resource to free up system resources
        curl_close($ch);

        if ($totalVotes == 0) {
            return null;
        }

        return round($rating / $totalVotes, 1);
    }
}
