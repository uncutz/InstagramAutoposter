<?php

declare(strict_types=1);

namespace Aeon\Controller;

use Aeon\Clients\PixabayClient;

final class PixabayController
{
    /**
     * returns imageUrl and hashtags
     *
     * @param string $searchTerm
     *
     * @return array
     * @throws \Exception
     */
    public function getImage(string $searchTerm): array
    {
        $pixabayClient = new PixabayClient();
        $request       = $pixabayClient->buildImageRequest($searchTerm);
        $response      = $request->send();

        $receivedData = $response->getParsedBody();

        $randomIndex = random_int(0, 199);

        if (!$randomIndex) {
            $randomIndex = 0;
        }

        $image = $receivedData['hits'][$randomIndex];

        $imageUrl = $image['largeImageURL'];
        $tags     = explode(',', str_replace(' ', '', $image['tags']));
        $hashtags = [];
        foreach ($tags as $tag) {
            $hashtags[] = '#' . $tag;
        }

        $hashtags = implode($hashtags);

        return [
            'imageUrl' => $imageUrl,
            'hashtags' => $hashtags,
        ];
    }
}