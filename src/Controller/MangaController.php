<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MangaController extends AbstractController
{
    private $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    #[Route('/api/manga', name: 'app_manga')]
    public function getMangaImage(Request $request): Response
    {
        $title = $request->query->get('title');
        $limit = $request->query->get('limit', 1);
        $includes = 'cover_art'; // On s'assure de demander la couverture

        if (empty($title)) {
            return new Response('Le titre est requis.', 400);
        }

        $response = $this->httpClient->request('GET', 'https://api.mangadex.org/manga', [
            'query' => [
                'title' => $title,
                'limit' => $limit,
                'includes[]' => $includes,
            ],
            'headers' => [
                'User-Agent' => 'MonUserAgent',
            ],
        ]);

        $data = $response->toArray();
        $coverArtCollection = $data['data'][0]['relationships'];

        $coverArt = null;

        foreach ($coverArtCollection as $coverArtItem) {
            if ($coverArtItem['type'] === 'cover_art') {
                $coverArt = $coverArtItem;
                break;
            }
        }

        if (!$coverArt) {
            return new Response('Couverture non trouvée.', 404);
        }

        $coverId = $data['data'][0]['id'];
        $filename = $coverArt['attributes']['fileName'];

        $imageUrl = "https://uploads.mangadex.org/covers/{$coverId}/{$filename}";

        $imageResponse = $this->httpClient->request('GET', $imageUrl);
        $imageContent = $imageResponse->getContent();

        $image = @imagecreatefromstring($imageContent);

        if (!$image) {
            return new Response('Erreur lors du chargement de l\'image.', 500);
        }

        $targetWidth = 300;
        $targetHeight = 400;

        $resizedImage = imagecreatetruecolor($targetWidth, $targetHeight);

        imagecopyresampled(
            $resizedImage, $image,
            0, 0, 0, 0,
            $targetWidth, $targetHeight,
            imagesx($image), imagesy($image)
        );

        ob_start();
        imagejpeg($resizedImage);
        $outputImage = ob_get_clean();

        imagedestroy($image);
        imagedestroy($resizedImage);

        return new Response($outputImage, 200, [
            'Content-Type' => 'image/jpeg',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }
}
