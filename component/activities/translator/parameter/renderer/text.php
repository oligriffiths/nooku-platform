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
 * Text Activity Translator Parameter Renderer
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Nooku\Component\Activities
 */
class TranslatorParameterRendererText extends TranslatorParameterRendererAbstract
{
    /**
     * @see TranslatorParameterRendererInterface::render()
     */
    public function render(TranslatorParameterInterface $parameter)
    {
        $output = $parameter->getText();

        if ($parameter->isTranslatable())
        {
            $output = $parameter->getTranslator()->translate($output);
        }

        return $output;
    }
}