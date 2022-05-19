<?php

declare(strict_types=1);

namespace Aeon\Http;

interface RepresentsRequest
{
    public const GET  = 'GET';
    public const POST = 'POST';

    /**
     * @param string $name
     * @param string $value
     *
     * @return $this
     */
    public function withHeader(string $name, string $value): self;

    /**
     * @param string $body
     *
     * @return $this
     */
    public function withBody(string $body): self;

    /**
     * @return RepresentsResponse
     */
    public function send(): RepresentsResponse;

}