<?php

declare(strict_types=1);

namespace IM\Fabric\Bundle\IdentityApiBundle\Client;

use Http\Client\Common\HttpMethodsClientInterface;
use IM\Fabric\Bundle\IdentityApiBundle\Api\ApiInterface;

interface ClientInterface
{
    public function prepareHttpClient(): HttpMethodsClientInterface;
    public function apiCall(string $name): ApiInterface;
}
