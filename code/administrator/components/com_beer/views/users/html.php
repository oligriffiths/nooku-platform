<?php
/**
 * Business Enterprise Employee Repository (B.E.E.R)
 * 
 * @version		$Id: html.php 234 2009-09-30 01:40:02Z johan $
 * @package		Beer
 * @copyright	Copyright (C) 2009 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

class BeerViewUsers extends KViewDefault
{
	public function display()
	{
		// Mixin a menubar object
		$this->mixin( KFactory::get('admin::com.beer.mixin.menu', array('mixer' => $this)));
		$this->displayMenutitle();
		$this->displayMenubar();

		//Create the toolbar
		KFactory::get('admin::com.beer.toolbar.users')
			->append('divider')	
			->append('enable')
			->append('disable');

		//Display the layout
		parent::display();
	}
}