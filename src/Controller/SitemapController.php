<?php

declare(strict_types=1);

namespace App\Controller;

use SimpleXMLElement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

class SitemapController extends AbstractController
{
    public function __construct(
        private KernelInterface $kernel,
    ) {
    }

    /**
     * @Route("/sitemap", name="sitemap")
     */
    public function view(): Response
    {
        $sitemap = new SimpleXMLElement(
            '<?xml version="1.0" encoding="UTF-8"?>' .
            '<?xml-stylesheet type="text/xsl" href="/css/sitemap.xsl' . '"?>' .
            '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"/>'
        );

        $baseUrl = match ($this->kernel->getEnvironment()) {
            'dev', 'test' => "http://manageleisure",
            'prod' => "https://manageleisure.com",
            'staging' => "https://staging.manageleisure.com",
        };

        $update = '2021-12-18';

        $pages = ['/', '/contact', '/movie', '/login', '/register'];

        foreach ($pages as $page) {
            $url = $sitemap->addChild('url');
            $url->addChild('loc', $baseUrl . $page);
            $url->addChild('priority', '1.0');
            $url->addChild('lastmod', $update);
            $url->addChild('changefreq', 'monthly');
        }

        $sitemapXML =  $sitemap->saveXML();

        $response = new Response($sitemapXML);
        $response->headers->set('Content-Type', 'text/xml');

        return $response;
    }
}
