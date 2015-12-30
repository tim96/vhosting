<?php
/**
 * Created by PhpStorm.
 * User: tim
 * Date: 12/28/2015
 * Time: 9:05 PM
 */

namespace TimVhostingBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface as InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use TimVhostingBundle\Entity\Video;

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

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    protected function configure()
    {
        // call: php app/console video:spider isDebug
        $this
            ->setName('video:spider')
            ->setDescription('Start update video information')
            ->addOption('searchWords', null, InputOption::VALUE_OPTIONAL, 'The search words')
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
        $this->executeCommand($searchWords);

        $this->logMessage("Finish execute command.");
    }

    protected function executeCommand($searchWords)
    {
        $serviceYoutube = $this->container->get('tim_vhosting.google_api.handler');

        $part = 'id,snippet';
        $parameters = array(
            'maxResults' => 20, /* from 0 to 50, default: 5 */
            'order' => 'rating', /* date, rating, relevance, title, videoCount, viewCount */
            'q' => $searchWords,
            'type' => 'video', /* channel, playlist, video */
        );

        $data = $serviceYoutube->getYoutubeVideos($part, $parameters);
        $token = $data->getNextPageToken();
        $items = $data->getItems();
        $countRecords = 0;
        if (count($items) > 0) {

            // todo: check videoId before save
            $em = $this->container->get('doctrine')->getManager();

            /** @var \Google_Service_YouTube_SearchResult $item */
            foreach($items as $item) {
                $objectId = $item->getId();
                $objectSnippet = $item->getSnippet();

                $video = new Video();
                $video->setYoutubeVideoId($objectId['videoId']);
                $video->setDescription($objectSnippet['description']);
                $video->setName($objectSnippet['title']);
                $video->setChannelId($objectSnippet['channelId']);
                $video->setPublishedAt(new \DateTime($objectSnippet['publishedAt']));
                $video->setIsPublic(false);

                // todo: add parsing tags, meta and search user
                $em->persist($video);
                $em->flush();
                $countRecords++;
            }
        }

        // todo: add work logic with nextPageToken
        $this->logMessage("Records created: ".$countRecords);
    }

    public function stopCommand()
    {
        $this->logMessage("Stop signal from system.");
    }

    public function errorHandler()
    {
        $this->logMessage("Error handler.");
    }

    protected function logMessage($message)
    {
        if ($this->isDebug) {
            $this->output->writeln($message);
        }
    }
}