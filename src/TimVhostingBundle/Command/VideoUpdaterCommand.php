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

class VideoUpdaterCommand extends ContainerAwareCommand
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
        // call: php app/console video:update isDebug
        $this
            ->setName('video:update')
            ->setDescription('Start update video information')
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

        try {
            $this->executeCommand($input, $output);
        }
        catch(\Exception $ex)
        {
            $this->logError('Error: '.$ex->getMessage().'; Trace: '.$ex->getTraceAsString());
        }

        $this->logMessage("Finish execute command.");
    }

    protected function executeCommand($input, $output)
    {
        $serviceYoutube = $this->container->get('tim_vhosting.google_api.handler');
        $videoHandler = $this->container->get('tim_vhosting.video.handler');

        $countRecords = $videoHandler->updateYoutubeVideoInfo($serviceYoutube);
        $this->logMessage("Records update: ".$countRecords);
    }

    public function stopCommand()
    {
        $this->logMessage("Stop signal from system.");
    }

    protected function logError($error)
    {
        $this->output->writeln($error);
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