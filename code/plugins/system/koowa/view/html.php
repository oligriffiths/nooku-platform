<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package     Koowa_View
 * @subpackage  Html
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * View HTML Class
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_View
 * @subpackage  Html
 */
class KViewHtml extends KViewAbstract
{
	public function __construct($options = array())
	{
		$options = $this->_initialize($options);
		
		// Add a rule to the template for form handling and secrity tokens
		KTemplate::addRules(array(KFactory::get('lib.koowa.template.filter.form')));
		
		// Set a base path for use by the view
		$this->assign('baseurl', $options['base_url']);

		parent::__construct($options);
	}
	
	public function display()
	{
		$prefix = $this->getClassName('prefix');
		$suffix = $this->getClassName('suffix');

		$app = KFactory::get('lib.joomla.application')->getName();
		
		//Set the main stylesheet for the component
		KTemplate::loadHelper('stylesheet', $prefix.'.css', 'media/com_'.$prefix.'/css/');
		
		//Push the toolbar output into the document buffer
		$this->_document->setBuffer(
			KFactory::get($app.'::com.'.$prefix.'.toolbar.'.$suffix)->render(), 
			'modules', 
			'toolbar'
		);	

		parent::display();
	}
}
