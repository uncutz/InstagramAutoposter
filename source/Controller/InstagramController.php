<?php

declare(strict_types=1);

namespace Aeon\Controller;

use Aeon\Clients\InstagramClient;

final class InstagramController
{
    /**
     * process to get the user id for posting etc
     *
     * @return int
     */
    public function getInstagramUserId(): int
    {
        $instagramClient = new InstagramClient();
        $request         = $instagramClient->buildFacebookPageIdRequest();
        $response        = $request->send();
        $receivedData    = $response->getParsedBody();
        $pageId          = (int)$receivedData['data'][0]['id'];

        if (!$pageId) {
            throw new \RuntimeException('invalid page id');
        }

        $request      = $instagramClient->buildInstagramBusinessAccountIdRequest($pageId);
        $response     = $request->send();
        $receivedData = $response->getParsedBody();
        $userId       = (int)$receivedData["instagram_business_account"]["id"];
        if (!$userId) {
            throw new \RuntimeException('invalid user id');
        }

        return $userId;
    }

    /**
     * upload Image on Instagram
     *
     * @param int         $userId
     * @param string      $imageUrl
     * @param string|null $caption
     *
     * @return array
     */
    public function postImageOnInstagram(int $userId, string $imageUrl, string $caption = ''): array
    {
        $instagramClient  = new InstagramClient();
        $request          = $instagramClient->buildInstagramMediaObjectRequest($userId, $imageUrl, $caption);
        $response         = $request->send();
        $receivedData     = $response->getParsedBody();
        $mediaContainerId = (int)$receivedData["id"];
        if (!$mediaContainerId) {
            throw new \RuntimeException('invalid media container id');
        }

        $request      = $instagramClient->buildInstagramPublishRequest($userId, $mediaContainerId);
        $response     = $request->send();

        return $response->getParsedBody();
    }
}