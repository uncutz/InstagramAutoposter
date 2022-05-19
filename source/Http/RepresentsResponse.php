<?php

declare(strict_types=1);

namespace Aeon\Http;

interface RepresentsResponse
{
    /**
     * @return int
     */
    public function getStatusCode(): int;

    /**
     * @return array<string>
     */
    public function getHeaders(): array;

    /**
     * @return string
     */
    public function getBody(): string;

    /**
     * @return array<mixed>
     */
    public function getParsedBody(): array;

    /**
     * @param callable $callback
     *
     * @return array<mixed>
     */
    public function map(callable $callback): array;
}