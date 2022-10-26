<?php

declare (strict_types = 1);

namespace App\Service;

class GoogleClient
{
    private $client;
    public function __construct()
    {
        $this->client = new \Google_client();
        $this->client->setAuthConfig('client_secret.json');
        $this->client->setAccessType('offline');
        $this->client->setApprovalPrompt("force");

        $this->client->setScopes([
            \Google_Service_YouTube::YOUTUBE_READONLY,
        ]);

        $this->client->setApplicationName('API code samples');
    }

    public function get()
    {
        return $this->client;
    }
}
