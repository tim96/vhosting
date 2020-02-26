<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: tim
 * Date: 12/16/2015
 * Time: 8:51 PM
 */

namespace App\TimVhostingBundle\Handler;

use App\TimConfigBundle\Handler\Base\BaseContainerEmHandler;
use App\TimVhostingBundle\Interfaces\YoutubeVideoInterface;

class GoogleApiHandler extends BaseContainerEmHandler implements YoutubeVideoInterface
{
    protected function initKey()
    {
        $client = new \Google_Client();
        $client->setApplicationName("Client_Library_Examples");
        $client->setDeveloperKey($this->container->getParameter('google_api_key'));

        return $client;
    }

    public function getYoutubeVideos($listSearch = 'id,snippet', $parameters = array())
    {
        $client = $this->initKey();
        $youtube = new \Google_Service_YouTube($client);

        try {
            // Call the search.list method to retrieve results matching the specified
            // query term.
            $searchResponse = $youtube->search->listSearch($listSearch, $parameters);

            return $searchResponse;

        } catch (\Google_Service_Exception $e) {
            throw $e;
        } catch (\Google_Exception $e) {
            throw $e;
        }
    }

    public function getYoutubeVideoInfo($videoId)
    {
        $client = $this->initKey();
        $youtube = new \Google_Service_YouTube($client);

        try {
            // $videoResponce = $youtube->videos->listVideos('contentDetails', array('id' => $videoId));
            $videoResponce = $youtube->videos->listVideos('snippet,contentDetails,statistics,status', array('id' => $videoId));
            if (!$videoResponce->count()) {
                throw new \RuntimeException('Not found video');
            }

            // video was found
            $items = $videoResponce->getItems();
            /** @var \Google_Service_YouTube_Video $item */
            $item = $items[0];

            return $item;

            } catch (\Google_Service_Exception $e) {
            throw $e;
        } catch (\Google_Exception $e) {
            throw $e;
        }
    }

    public function getYoutubeVideoDuration($videoId)
    {
        $data = $this->getYoutubeVideoInfo($videoId);
        return $this->getYoutubeVideoDurationFromData($data);
    }

    /**
     * @param $data \Google_Service_YouTube_Video
     * @return array
     */
    public function getYoutubeVideoDurationFromData($data)
    {
        /** @var \Google_Service_YouTube_VideoContentDetails $getContentDetails */
        $getContentDetails = $data->getContentDetails();
        return $this->convertYoutubeVideoDuration($getContentDetails->getDuration());
    }

    public function getYoutubeVideoStatistics($videoId)
    {
        $data = $this->getYoutubeVideoInfo($videoId);
        /** @var \Google_Service_YouTube_VideoStatistics $getStatistics */
        return $this->getYoutubeVideoStatisticsFromData($data);
    }

    /**
     * @param $data \Google_Service_YouTube_Video
     * @return \Google_Service_YouTube_VideoStatistics
     */
    public function getYoutubeVideoStatisticsFromData($data)
    {
        /** @var \Google_Service_YouTube_VideoStatistics $getStatistics */
        return $data->getStatistics();
    }

    public function convertYoutubeVideoDuration($youtubeTime)
    {
        preg_match_all('/(\d+)/', $youtubeTime ,$parts);

        $hours = floor($parts[0][0]/60);
        $minutes = $parts[0][0]%60;
        if (isset($parts[0][1])) {
            $seconds = $parts[0][1];
        } else {
            $seconds = '00';
        }

        return array('minutes' => $minutes, 'hours' => $hours, 'seconds' => $seconds);
    }
}
