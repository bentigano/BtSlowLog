<?php
namespace BtSlowLog;

use Zend\EventManager\EventInterface;
use Zend\Http\PhpEnvironment\Request;
use Zend\Log\LoggerInterface;
use Zend\Mvc\MvcEvent;

class Module
{
	public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
	
    protected $start;

    public function onBootstrap(EventInterface $event)
    {
        $eventManager = $event->getApplication()->getEventManager();
        $eventManager->attach(MvcEvent::EVENT_FINISH, array($this, 'onFinish'));
        
        $this->start = microtime(true);
    }

    public function onFinish(MvcEvent $mvcEvent)
    {
        $serviceManager = $mvcEvent->getApplication()->getServiceManager();
        $config = $serviceManager->get('config');

        if (!isset($config['BtSlowLog']['logger'])) {
            return;
        }

        $threshold = 1000; // default to 1 second
        if (isset($config['BtSlowLog']['threshold'])) {
            $threshold = $config['BtSlowLog']['threshold'];
        }

        $timeElapsed = (microtime(true) - $this->start) * 1000;

        if ($timeElapsed > $threshold) {
            $request = $serviceManager->get('request');
            if (!$request instanceof Request) {
                return;
            }

            $loggerName = $config['BtSlowLog']['logger'];
		
        	if (!$serviceManager->has($loggerName)) {
        		continue;
        	}
		
            $logger = $serviceManager->get($loggerName);
            $message = sprintf(
                "Slow response on %s %s (%fms)",
                $request->getMethod(),
                $request->getUriString(),
                $timeElapsed
            );

            if ($logger instanceof LoggerInterface) {
                $logger->warn($message);
            } else {
               throw new \RuntimeException("Unknown logger - please verify the configuration");
            }
        }
    }
}