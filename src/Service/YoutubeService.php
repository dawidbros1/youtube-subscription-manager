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

    # Method returns service
    public function get()
    {
        return $this->service;
    }

    # Method returns channel of logged in user
    public function getMyChannel()
    {
        $channels = $this->service->channels->listChannels('snippet,id,contentDetails', [
            "mine" => true,
        ]);

        return $channels[0];
    }

    # Method returns subscriptions of logged in user
    public function listSubscriptions($pageToken = null)
    {
        return $this->service->subscriptions->listSubscriptions('snippet', [
            "mine" => true,
            "maxResults" => 50,
            "pageToken" => $pageToken,
        ]);
    }
    # Method returns channels by IDS
    public function getChannels(array $ids)
    {
        return $this->service->channels->listChannels('id,snippet,contentDetails', [
            'id' => $ids,
        ]);
    }

    # Method returns videos form channels
    public function listVideos(array $channels)
    {
        $videos = [];

        foreach ($channels as $channel) {
            $result = $this->service->search->listSearch('snippet', [
                'channelId' => $channel->channelId,
                'maxResults' => 10, // get last 10 videos per canal
                'order' => 'date',
                'type' => "video",
            ]);

            $videos[] = array_merge($videos, $result->items);
        }

        dump($videos); //? Check if the video has an icon and a link from the video
        die();

        foreach ($videos as $video) {
            $ids[] = $video->id->videoId;
        }

        $videos = $this->service->videos->listVideos('player', ['id' => $ids ?? []]);

        return $videos;
    }
}
