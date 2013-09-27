<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Activities;

use Nooku\Library;

/**
 * Abstract Activity Translator Parameter Renderer
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Nooku\Component\Activities
 */
abstract class TranslatorParameterRendererAbstract extends Library\Object implements TranslatorParameterRendererInterface, Library\ObjectInstantiable
{
    public static function getInstance(Library\ObjectConfig $config, Library\ObjectManagerInterface $manager)
    {
        // Singleton behavior.
        $classname = $config->object_identifier->classname;
        $instance  = new $classname($config);
        $manager->setObject($config->object_identifier, $instance);

        return $instance;
    }
}
