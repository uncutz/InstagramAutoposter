<?php

declare(strict_types=1);

namespace Aeon\Clients;

use Aeon\Clients\InstagramClient\Request;
use Aeon\Http\RepresentsRequest;

final class PixabayClient
{

    private string $accessToken;

    private const ENDPOINTS = [
        'pixabay_image' => '?key=',
    ];

    public const IMG_TYPE_PHOTO        = "photo";
    public const IMG_TYPE_ILLUSTRATION = "illustration";
    public const IMG_TYPE_VECTOR       = "vector";
    public const IMG_TYPE_ALL          = "all";

    public const LAYOUT_ALL        = "all";
    public const LAYOUT_VERTICAL   = "vertical";
    public const LAYOUT_HORIZONTAL = "horizontal";

    private string $url;


    public function __construct()
    {
        $localconf = json_decode(file_get_contents(__DIR__ . '/../../config/localconf.json'), true);
        if ($localconf) {
            $this->accessToken = $localconf['pixabayApi']['accessToken'];
            $this->url         = $localconf['pixabayApi']['url'];
        }
    }

    /**
     * @param string $searchQuery
     * @param string $imageType
     * @param string $orientation
     * @param string $category
     * @param int    $page
     * @param int    $limit
     *
     * @return RepresentsRequest
     */
    public function buildImageRequest(
        string $searchQuery,
        string $imageType = self::IMG_TYPE_PHOTO,
        string $orientation = self::LAYOUT_ALL,
        string $category = "all",
        int $page = 1,
        int $limit = 200
    ): RepresentsRequest {
        return new Request(
            $this->buildRequestUrl(
                self::ENDPOINTS['pixabay_image'] . $this->accessToken . '&q=' . urlencode($searchQuery) .
                '&image_type=' . $imageType .
                '&orientation=' . $orientation .
                '&category=' . $category .
                '&page=' . $page.
                '&per_page=' . $limit),
            RepresentsRequest::GET
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