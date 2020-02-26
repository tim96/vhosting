<?php
/**
 * Created by PhpStorm.
 * User: tim
 * Date: 12/28/2015
 * Time: 9:05 PM
 */

namespace App\TimVhostingBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface as InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use App\TimVhostingBundle\Entity\Tags;
use App\TimVhostingBundle\Entity\Video;

class VideoSpiderCommand extends ContainerAwareCommand
{
    /** @var ContainerInterface */
    private $container;
    /** @var  \Symfony\Component\Console\Output\OutputInterface */
    private $output;
    /** @var  \Symfony\Component\Console\Input\InputInterface */
    private $input;
    /** @var  boolean */
    protected $isDebug;
    /** @var  string */
    protected $language;
    /** @var  string */
    protected $regionCode;
    /** @var  string */
    protected $maxResults;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    protected function configure()
    {
        // call: php bin/console video:spider isDebug
        $this
            ->setName('video:spider')
            ->setDescription('Start update video information')
            ->addOption('searchWords', null, InputOption::VALUE_OPTIONAL, 'The search words')
            ->addOption('maxResults', null, InputOption::VALUE_OPTIONAL, 'Count max results', 20)
            ->addOption('language', null, InputOption::VALUE_OPTIONAL, 'The language code.')
            ->addOption('regionCode', null, InputOption::VALUE_OPTIONAL, 'The language code.
                The parameter value is typically an ISO 639-1 two-letter language code')
            ->addArgument('isDebug', InputArgument::OPTIONAL, 'Turn on debug mode: true, false. Default - false', false)
        ;
    }

    protected function configureHandler()
    {
        declare(ticks = 1);
        register_shutdown_function(array($this, 'stopCommand'));
        set_error_handler(array($this, 'errorHandler'));
        if (function_exists("pcntl_signal")) {
            pcntl_signal(SIGTERM, [$this, 'stopCommand']);
            pcntl_signal(SIGINT, [$this, 'stopCommand']);
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
        $this->isDebug = $input->getArgument('isDebug');

        $this->configureHandler();

        $searchWords = $input->getOption('searchWords');
        $this->language = $input->getOption('language') ? $input->getOption('language') : 'en';
        $this->regionCode = $input->getOption('regionCode');
        $this->maxResults = $input->getOption('maxResults');

        $countRepeat = 5;
        $this->executeCommand($searchWords, $countRepeat, null);

        $this->logMessage("Finish execute command.");
    }

    protected function executeCommand($searchWords, $countRepeat, $token)
    {
        $serviceYoutube = $this->container->get('tim_vhosting.google_api.handler');

        $part = 'id,snippet';
        $parameters = [
            'maxResults' => $this->maxResults, /* from 0 to 50, default: 5 */
            'order' => 'viewCount', /* date, rating, relevance, title, videoCount, viewCount */
            'q' => $searchWords,
            'type' => 'video', /* channel, playlist, video */
            'relevanceLanguage' => $this->language,
            // 'hl' => 'em_US' The hl parameter specifies the language that should be used for text values in the API response. The default value is en_US.
        ];

        if ($this->regionCode) {
            $parameters['regionCode'] = $this->regionCode;
        }

        if (!empty($token)) {
            $parameters['pageToken'] = $token;
        }

        $data = $serviceYoutube->getYoutubeVideos($part, $parameters);
        $token = $data->getNextPageToken();
        $items = $data->getItems();
        $countRecords = 0;

        if (count($items) > 0) {

            $em = $this->container->get('doctrine')->getManager();

            $ids = array();
            /** @var \Google_Service_YouTube_SearchResult $item */
            foreach($items as $item) {
                $objectId = $item->getId();
                $ids[] = $objectId['videoId'];
            }

            $repository = $em->getRepository('TimVhostingBundle:Video');
            $repositoryTags = $em->getRepository('TimVhostingBundle:Tags');
            $results = $repository->getVideoListCompareList($ids)->getQuery()->getArrayResult();
            $resultsTags = $repositoryTags->getTagsQuery()->getQuery()->getResult();

            /** @var \Google_Service_YouTube_SearchResult $item */
            foreach($items as $item) {
                $objectId = $item->getId();

                if ($this->isVideoIdExists($results, $objectId['videoId'])) continue;

                $objectSnippet = $item->getSnippet();

                $name = $objectSnippet['title'];
                // Remove 4-byte UTF-8, two variants. todo: Find the best
                $name = preg_replace('/[\xF0-\xF7].../s', '', $name);
                $name = preg_replace('/[\x{10000}-\x{10FFFF}]/u', '', $name);

                $description = $objectSnippet['description'];
                // Remove 4-byte UTF-8, two variants. todo: Find the best
                $description = preg_replace('/[\xF0-\xF7].../s', '', $description);
                $description = preg_replace('/[\x{10000}-\x{10FFFF}]/u', '', $description);

                $video = new Video();
                $video->setYoutubeVideoId($objectId['videoId']);
                $video->setDescription($description);
                $video->setName($name);
                $video->setChannelId($objectSnippet['channelId']);
                $video->setPublishedAt(new \DateTime($objectSnippet['publishedAt']));
                $video->setIsPublic(false);

                /** @var Tags $tag */
                foreach($resultsTags as $tag) {
                    if (strpos($video->getDescription(), $tag->getName()) !== false) {
                        if (!$video->getTags()->contains($tag)) {
                            $video->addTag($tag);
                            $meta = $video->getMeta();
                            $video->setMeta($meta . ' ' . $tag->getName());
                        }
                    } else if (strpos($video->getName(), $tag->getName()) !== false) {
                        if (!$video->getTags()->contains($tag)) {
                            $video->addTag($tag);
                            $meta = $video->getMeta();
                            $video->setMeta($meta . ' ' . $tag->getName());
                        }
                    }
                }

                $video->setMeta($video->getMeta().' '.$video->getName());

                $em->persist($video);
                $em->flush();
                $countRecords++;
            }
        }

        $this->logMessage("Records created: ".$countRecords);

        if ($countRepeat > 0) {
            $countRepeat = $countRepeat - 1;
            $this->executeCommand($searchWords, $countRepeat, $token);
        }
    }

    private function isVideoIdExists($array, $videoId)
    {
        foreach ($array as $video)
        {
            if ($video['youtubeVideoId'] == $videoId) {
                return true;
            }
        }

        return false;
    }

    public function stopCommand()
    {
        $this->logMessage("Stop signal from system.");
    }

    public function errorHandler($errno, $errstr, $errfile, $errline, $errcontext)
    {
        $this->myErrorHandler($errno, $errstr, $errfile, $errline);
        $this->logMessage("Error handler.");
    }

    protected function logMessage($message)
    {
        if ($this->isDebug) {
            $this->output->writeln($message);
        }
    }

    protected function myErrorHandler($errno, $errstr, $errfile, $errline)
    {
        if (!(error_reporting() & $errno)) {
            // This error code is not included in error_reporting
            return;
        }

        switch ($errno) {
            case E_USER_ERROR:
                $this->logMessage("<b>My ERROR</b> [$errno] $errstr<br />\n");
                $this->logMessage( "  Fatal error on line $errline in file $errfile");
                $this->logMessage(", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n");
                $this->logMessage("Aborting...<br />\n");
                exit(1);
                break;

            case E_USER_WARNING:
                $this->logMessage("<b>My WARNING</b> [$errno] $errstr (line $errline) (file $errfile)<br />\n");
                break;

            case E_USER_NOTICE:
                $this->logMessage("<b>My NOTICE</b> [$errno] $errstr (line $errline) (file $errfile)<br />\n");
                break;

            default:
                $this->logMessage("Unknown error type: [$errno] $errstr (line $errline) (file $errfile)<br />\n");
                break;
        }

        /* Don't execute PHP internal error handler */
        return true;
    }
}