<?php

declare (strict_types = 1);

namespace App\Service;

use App\Model\User;
use Phantom\Helper\Session;
use Phantom\Model\Route;
use stdClass;

class Google
{
    private $client;
    private $url;
    private $route;
    public function __construct(string $project_location, Route $route)
    {
        $this->route = $route;
        $this->client = new \Google_client();
        $this->client->setAuthConfig('client_secret.json');
        $this->client->setAccessType('offline');

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

            $youtube = new \Google_Service_YouTube($this->client);
            $channel = $youtube->channels->listChannels('snippet,id,contentDetails', ["mine" => true]);
            return new User($channel[0]);
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
            $class = new stdClass();
            $class->login = false;

            $this->client->setState(json_encode($class));
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