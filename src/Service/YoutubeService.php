<?php

declare (strict_types = 1);

namespace App\Service;

class YoutubeService
{
    private $service;
    public function __construct($client)
    {
        $this->service = new \Google_Service_YouTube($client);
    }

    public function get()
    {
        return $this->service;
    }

    public function getMyChannel()
    {
        $channels = $this->service->channels->listChannels('snippet,id,contentDetails', [
            "mine" => true,
        ]);

        return $channels[0];
    }

    public function listSubscriptions($pageToken = null)
    {
        return $this->service->subscriptions->listSubscriptions('snippet', [
            "mine" => true,
            "maxResults" => 50,
            "pageToken" => $pageToken,
        ]);
    }

    public function getChannels(array $ids)
    {
        return $this->service->channels->listChannels('id,snippet,contentDetails', [
            'id' => $ids,
        ]);
    }
}
