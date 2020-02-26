<?php

namespace App\TimVhostingBundle\Interfaces;

interface YoutubeVideoInterface
{
    public function getYoutubeVideoInfo($videoId);

    public function getYoutubeVideoDurationFromData($data);

    public function getYoutubeVideoStatisticsFromData($data);
}