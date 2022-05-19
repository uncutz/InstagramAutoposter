<?php

declare(strict_types=1);

namespace Aeon\Http;

class Request implements RepresentsRequest
{
    private string $url;

    private string $method;

    private string $body = "";

    private array $headers = [];

    /**
     * @param string $url
     * @param string $method
     */
    public function __construct(string $url, string $method)
    {
        $this->url    = $url;
        $this->method = $method;
    }

    /**
     * @return string[]
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }


    /**
     * @param string $name
     * @param string $value
     *
     * @return RepresentsRequest
     */
    public function withHeader(string $name, string $value): RepresentsRequest
    {
        $this->headers[strtolower($name)] = "{$name}: {$value}";

        return $this;
    }

    /**
     * @param string $body
     *
     * @return RepresentsRequest
     */
    public function withBody(string $body): RepresentsRequest
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @param array $payload
     *
     * @return RepresentsRequest
     */
    public function withJson(array $payload): RepresentsRequest
    {
        $json = (string)json_encode($payload, JSON_UNESCAPED_SLASHES);

        return $this
            ->withHeader('Content-Type', 'application/json')
            ->withHeader('Content-Length', (string)strlen($json))
            ->withBody($json);
    }

    public function send(): RepresentsResponse
    {
        return new Response($this->createStream());
    }

    /**
     * @return resource
     */
    protected function createStream()
    {
        $context = stream_context_create(
            [
                'http' => [
                    'ignore_errors' => false,
                    'method'        => $this->method,
                    'header'        => implode("\r\n", $this->headers) . "\r\n",
                    'content'       => $this->body,
                ],
            ]
        );

        $stream = fopen($this->url, 'rb', false, $context);
        if (!is_resource($stream)) {
            throw new \RuntimeException('stream could not be opened');
        }

        return $stream;
    }
}