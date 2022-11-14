<?php

declare (strict_types = 1);

namespace App\Service;

use App\Model\User;
use Phantom\Helper\Session;
use Phantom\Model\Route;

class Google
{
    private $client;
    private $url;
    private $route;
    private $youtube;

    public function __construct(string $project_location, Route $route)
    {
        $this->route = $route;
        $this->url = $project_location;
        $this->client = (new GoogleClient())->get();
        $this->youtube = (new YoutubeService($this->client));
    }
    # Method sign in user
    public function login()
    {
        if ($access_token = Session::get('access_token')) {
            $this->validateAccessToken($access_token);

            return new User($this->youtube->getMyChannel());
        }

        return null;
    }

    # Method logout user
    public function logout()
    {
        Session::clear('access_token');
        $this->client->revokeToken();
    }

    # Method returns link to sign in
    public function getGoogleLoginUrl()
    {
        return filter_var($this->client->createAuthUrl(), FILTER_SANITIZE_URL);
    }

    # Method return GoogleClient
    public function getClient()
    {
        return $this->client;
    }

    # Method return YoutubeService
    public function getYoutubeService()
    {
        return $this->youtube;
    }

    # Method checks if token is set and valid
    # if token is expired =>  refreshToken
    private function validateAccessToken($access_token)
    {
        $this->client->setAccessToken($access_token);

        if ($this->client->isAccessTokenExpired()) {
            $refreshToken = $this->client->getRefreshToken();
            $this->client->refreshToken($refreshToken);
            $newAccessToken = $this->client->getAccessToken();
            $newAccessToken['refresh_token'] = $refreshToken;
            $this->client->setAccessToken($newAccessToken);

            //! unknown error => token is not refresh => safe logout
            if ($this->client->getAccessToken()['access_token'] == Session::get('access_token')['access_token']) {
                header("Location: " . $this->url);
                Session::clear('access_token');
                Session::error("UPS! Coś poszło nie tak! Prosimy o ponowne zalogowanie się");
                exit();
            }

            Session::set('access_tonen', $newAccessToken);
        }
    }
}
