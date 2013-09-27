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
 * Activity Translator Parameter Renderer Interface
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Nooku\Component\Activities
 */
interface TranslatorParameterRendererInterface
{
    /**
     * Renders a parameter object.
     *
     * @param $parameter TranslatorParameterInterface The parameter object.
     *
     * @return string The rendered parameter object.
     */
    public function render(TranslatorParameterInterface $parameter);
}