<?php

declare(strict_types=1);

namespace Aeon\Http;

class Response implements RepresentsResponse
{
    /** @var resource */
    private $stream;

    /**
     * @param resource $stream
     */
    public function __construct($stream)
    {
        $this->guardStreamIsValidResource($stream);

        $this->stream = $stream;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        preg_match('|HTTP/\d\.\d\s+(\d+)\s+.*|', $this->getHeaders()[0], $matches);

        return (int)($matches[1] ?? '200');
    }

    /**
     * @return array<string>
     */
    public function getHeaders(): array
    {
        return (array)stream_get_meta_data($this->stream)['wrapper_data'];
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return (string)stream_get_contents($this->stream);
    }

    /**
     * @return array<mixed>
     */
    public function getParsedBody(): array
    {
        return (array)json_decode($this->getBody(), true);
    }

    /**
     * @param callable $callback
     *
     * @return array<mixed>
     */
    public function map(callable $callback): array
    {
        return array_map($callback, $this->getParsedBody()['data'] ?? []);
    }

    /**
     * @param resource $stream
     */
    private function guardStreamIsValidResource($stream): void
    {
        if (!is_resource($stream)) {
            throw new \RuntimeException('must be a valid stream');
        }
    }
}