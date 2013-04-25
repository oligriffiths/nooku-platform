<?php
/**
 * @package		Koowa_Template
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

namespace Nooku\Library;

 /**
  * Template Interface
  * 
  * @author		Johan Janssens <johan@nooku.org>
  * @package	Koowa_Template
  */
interface TemplateInterface
{
    /**
     * Render the template
     *
     * @return string    The rendered data
     */
    public function render();

    /**
     * Check if the template is in a render cycle
     *
     * @return boolean Return TRUE if the template is being rendered
     */
    public function isRendering();

    /**
     * Get the template file identifier
     *
     * @return	string
     */
    public function getPath();

	/**
	 * Get the template data
	 * 
	 * @return	mixed
	 */
	public function getData();

    /**
     * Get the template contents
     *
     * @return  string
     */
    public function getContent();

    /**
     * Get the view object attached to the template
     *
     * @return  ViewInterface
     */
	public function getView();

    /**
     * Method to set a view object attached to the template
     *
     * @param mixed  $view An object that implements ServiceInterface, ServiceIdentifier object
     *                     or valid identifier string
     * @throws \UnexpectedValueException    If the identifier is not a view identifier
     * @return TemplateAbstract
     */
	public function setView($view);

    /**
     * Load a template by path
     *
     * @param   string  $file     The template path
     * @param   array   $data     An associative array of data to be extracted in local template scope
     * @return TemplateAbstract
     */
	public function loadFile($file, $data = array());

    /**
     * Load a template from a string
     *
     * @param  string   $string     The template contents
     * @param  array    $data       An associative array of data to be extracted in local template scope
     * @return TemplateAbstract
     */
	public function loadString($string, $data = array());

    /**
     * Get a filter by identifier
     *
     * @param   mixed    $filter    An object that implements ServiceInterface, ServiceIdentifier object
                                    or valid identifier string
     * @param   array    $config    An optional associative array of configuration settings
     * @return TemplateFilterInterface
     */
    public function getFilter($filter, $config = array());

    /**
     * Attach one or more filters for template transformation
     *
     * @param   mixed  $filter An object that implements ServiceInterface, ServiceIdentifier object
     *                         or valid identifier string
     * @param   array $config  An optional associative array of configuration settings
     * @return TemplateAbstract
     */
    public function attachFilter($filter, $config = array());

    /**
     * Get a template helper
     *
     * @param    mixed    $helper ServiceIdentifierInterface
     * @param    array    $config An optional associative array of configuration settings
     * @return  TemplateHelperInterface
     */
    public function getHelper($helper, $config = array());

    /**
     * Load a template helper
     *
     * This functions accepts a partial identifier, in the form of helper.function. If a partial identifier is passed a
     * full identifier will be created using the template identifier.
     *
     * @param    string   $identifier Name of the helper, dot separated including the helper function to call
     * @param    array    $params     An optional associative array of functions parameters to be passed to the helper
     * @return   string   Helper output
     * @throws   \BadMethodCallException If the helper function cannot be called.
     */
	public function renderHelper($identifier, $config = array());

    /**
     * Searches for the file
     *
     * @param   string  $file The file path to look for.
     * @return  mixed   The full path and file name for the target file, or FALSE if the file is not found
     */
    public function findFile($file);
}