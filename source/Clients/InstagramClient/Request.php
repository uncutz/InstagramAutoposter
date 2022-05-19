<?php

declare(strict_types=1);

namespace Aeon\Clients\InstagramClient;

class Request extends \Aeon\Http\Request
{
    public function __construct(string $url, string $method = self::GET)
    {
        parent::__construct($url, $method);
    }
}