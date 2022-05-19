<?php

declare(strict_types=1);

namespace Aeon\Clients;

use Aeon\Clients\InstagramClient\Request;
use Aeon\Http\RepresentsRequest;

final class InstagramClient
{

    private string $accessToken;

    private const ENDPOINTS = [
        'fb_page_id'      => 'me/accounts?access_token=',
        'ig_user_id'      => '?fields=instagram_business_account&access_token=',
        'ig_media_object' => '/media?image_url=',
        'ig_publish'      => '/media_publish?creation_id=',
    ];

    private string $url;


    public function __construct()
    {
        $localconf = json_decode(file_get_contents(__DIR__ . '/../../config/localconf.json'), true);
        if ($localconf) {
            $this->accessToken = $localconf['instagramApi']['accessToken'];
            $this->url         = $localconf['instagramApi']['url'];
        }
    }

    /**
     * @return RepresentsRequest
     */
    public function buildFacebookPageIdRequest(): RepresentsRequest
    {
        return new Request(
            $this->buildRequestUrl(self::ENDPOINTS['fb_page_id'] . $this->accessToken),
            RepresentsRequest::GET
        );
    }

    /**
     * @param int $pageId
     *
     * @return RepresentsRequest
     */
    public function buildInstagramBusinessAccountIdRequest(int $pageId): RepresentsRequest
    {
        return new Request(
            $this->buildRequestUrl($pageId . self::ENDPOINTS['ig_user_id'] . '&access_token=' . $this->accessToken),
            RepresentsRequest::GET
        );
    }

    /**
     * @param int    $userId
     * @param string $imageUrl
     * @param string $caption
     *
     * @return RepresentsRequest
     */
    public function buildInstagramMediaObjectRequest(int $userId, string $imageUrl, string $caption): RepresentsRequest
    {
        return new Request(
            $this->buildRequestUrl($userId .
                self::ENDPOINTS['ig_media_object'] . $imageUrl .
                '&caption=' . urlencode($caption) .
                '&access_token=' . $this->accessToken),
            RepresentsRequest::POST
        );
    }

    /**
     * @param int $userId
     * @param int $mediaContainerId
     *
     * @return RepresentsRequest
     */
    public function buildInstagramPublishRequest(int $userId, int $mediaContainerId): RepresentsRequest
    {
        return new Request(
            $this->buildRequestUrl($userId . self::ENDPOINTS['ig_publish'] . $mediaContainerId . '&access_token=' . $this->accessToken),
            RepresentsRequest::POST
        );
    }


    /**
     * @param string                $endpoint
     * @param array<string, string> $arguments
     *
     * @return string
     */
    private function buildRequestUrl(string $endpoint, array $arguments = []): string
    {
        $query = http_build_query($arguments);

        return sprintf(
            '%s/%s%s',
            $this->url,
            $endpoint,
            $query
        );
    }

}