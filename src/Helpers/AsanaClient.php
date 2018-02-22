<?php
namespace App\Helpers;

use Asana\Client;

class AsanaClient
{

    private $token;

    public function setToken(string $token): AsanaClient
    {
        $this->token = $token;
        return $this;
    }

    public function getClient(): Client
    {
        return Client::accessToken($this->token);
    }
}
