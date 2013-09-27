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
 * Activity Translator Interface
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Nooku\Component\Activities
 */
interface TranslatorInterface
{
    /**
     * Translates an activity string.
     *
     * @param string                                      $string               The activity string.
     * @param TranslatorParameterInterface[] $parameters           An optional array containing parameter
     *                                                                          objects.
     *
     * @return string The translated activity string.
     */
    public function translate($string, array $parameters = array());

    /**
     * Activity string parser.
     *
     * Identifies the components (such as parameters, words, etc.) of activity strings.
     *
     * @param string $string The activity string.
     *
     * @return array An associative array containing the activity string components.
     */
    public function parse($string);
}