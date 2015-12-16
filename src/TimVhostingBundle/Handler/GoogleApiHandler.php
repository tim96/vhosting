<?php
/**
 * Created by PhpStorm.
 * User: tim
 * Date: 12/16/2015
 * Time: 8:51 PM
 */

namespace TimVhostingBundle\Handler;

use TimConfigBundle\Handler\Base\BaseContainerEmHandler;

class GoogleApiHandler extends BaseContainerEmHandler
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
}
