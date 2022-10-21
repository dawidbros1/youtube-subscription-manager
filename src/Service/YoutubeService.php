<?php

declare (strict_types = 1);

namespace App\Service;

class YoutubeService
{
    private $service;
    public function __construct($client)
    {
        $this->service = new \Google_Service_YouTube($client);
        // $response = $service->subscriptions->listSubscriptions('id', ["mine" => true]);
        // $channel = $response->items[0]->id;
        // $videos = $service->videos->listVideos('player', ['id' => 'Ks-_Mh1QhMc,c0KYU2j0TM4,eIho2S0ZahI']);
    }

    public function listSubscriptions()
    {
        return $this->service->subscriptions->listSubscriptions('id', ["mine" => true]);
    }
}
