<?php
/**
 * @package     Nooku_Plugins
 * @subpackage  Koowa
 * @copyright  	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Application Event Subscriber Class
.*
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Nooku_Components
 * @subpackage  Debug
 */

class ComDebugEventSubscriberApplication extends KEventSubscriberAbstract
{
    public function __construct(KConfig $config)
	{
	    //Intercept the events for profiling
	    if($this->getService('application')->getCfg('debug'))
	    {
	        //Replace the event dispatcher
	        $this->getService()->setAlias('lib://nooku/event.dispatcher.default', 'com://admin/debug.event.profiler');
	          
	        //Add the database tracer
	        $this->getService('application.database')->addEventSubscriber('com://admin/debug.event.subscriber.database');
		}
		
		parent::__construct($config);
	}
}