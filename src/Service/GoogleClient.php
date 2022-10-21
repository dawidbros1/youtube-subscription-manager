<?php

declare (strict_types = 1);

namespace App\Service;

use Phantom\Helper\Session;
use Phantom\Model\Route;

class GoogleClient
{
    private $client;
    private $url;
    private $route;

    public function __construct(string $project_location, Route $route)
    {
        $this->route = $route;
        $this->client = new \Google_client();
        $this->client->setAuthConfig('client_secret.json');

        $this->client->setScopes([
            \Google_Service_YouTube::YOUTUBE_READONLY,
        ]);

        $this->client->setApplicationName('API code samples');
        $this->url = $project_location;
    }
    public function getGoogleLoginUrl()
    {
        return filter_var($this->client->createAuthUrl(), FILTER_SANITIZE_URL);
    }

    public function login()
    {
        if ($access_token = Session::get('access_token')) {
            $this->client->setAccessToken($access_token);
            $this->validateAccessToken();

            return $this->client;
        }

        return null;
    }
    public function logout()
    {
        Session::clear('access_token');
        $this->client->revokeToken();
    }

    private function validateAccessToken()
    {
        if ($this->client->isAccessTokenExpired()) {
            $authUrl = $this->client->createAuthUrl();
            header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
            exit();
        }
    }

    public function getClient()
    {
        return $this->client;
    }

    public function getYoutubeService()
    {
        return new YoutubeService($this->client);
    }
}
