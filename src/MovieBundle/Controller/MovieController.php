<?php

declare(strict_types=1);

namespace App\MovieBundle\Controller;

use App\Controller\AuthController;
use App\MovieBundle\Form\MovieType;
use App\MovieBundle\Service\IMDBIdRetriever;
use App\MovieBundle\Service\IMDBReviewsScraper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AuthController
{
    /**
     * @Route("/movie", name="movieHomepage")
     */
    public function view(Request $request): Response
    {
        $form = $this->createForm(MovieType::class, null, [
            'action' => $this->generateUrl('movieGetRating'),
            'method' => 'GET',
        ]);
        $form->handleRequest($request);

        return $this->renderForm('@MovieBundle/movie/view.html.twig', [
            'response' => null,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/movie/get-rating", name="movieGetRating")
     */
    public function getRating(Request $request): Response
    {
        $form = $this->createForm(MovieType::class, null, [
            'method' => 'GET',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $responseObject = IMDBIdRetriever::getResponseObject($form->get('title')->getData(),
                $this->getParameter('omdb_api_key'), $form->get('year')->getData() ?? null);


            if (isset($responseObject->Error) && $responseObject->Error == 'Movie not found!') {
                return $this->render('@MovieBundle/movie/warning.html.twig', [
                    'message' => $responseObject->Error,
                ], new Response('', 404));
            }

            if (isset($responseObject->Error)) {
                return $this->render('@MovieBundle/movie/warning.html.twig', [
                    'message' => $responseObject->Error,
                ]);
            }

            if (!isset($responseObject->Search)) {
                $reviewsRating = IMDBReviewsScraper::getRating($responseObject->imdbID);
            } else {
                $unique = [];
                foreach ($responseObject->Search as $item) {
                    $unique[$item->imdbID] = $item;
                }
                return $this->render('@MovieBundle/movie/multipleMovies.html.twig', ['results' => $unique]);
            }

            $data = [
                'reviewsRating' => $reviewsRating,
                'responseObject' => $responseObject,
            ];

            return $this->render('@MovieBundle/movie/getRating.html.twig', $data);
        }

        return $this->render('@MovieBundle/movie/warning.html.twig', [
            'message' => 'Invalid form data.',
        ]);
    }

    /**
     * @Route("/movie/single-movie/{id}", name="movieSingle")
     */
    public function singleMovie(string $id): Response
    {
        $responseObject = IMDBIdRetriever::getSingleMovie($id, $this->getParameter('omdb_api_key'));
        $reviewsRating = IMDBReviewsScraper::getRating($responseObject->imdbID);

        $data = [
            'reviewsRating' => $reviewsRating,
            'responseObject' => $responseObject,
        ];

        return $this->render('@Movie/movie/getRating.html.twig', $data);
    }
}
