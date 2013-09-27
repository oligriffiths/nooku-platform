<?php
/**
 * Created by JetBrains PhpStorm.
 * User: arunasmazeika
 * Date: 19/09/13
 * Time: 12:44
 * To change this template use File | Settings | File Templates.
 */

namespace Nooku\Component\Activities;

use Nooku\Library;

class TranslatorNooku extends Library\Object
{
    public function translate($string, $replacements)
    {
        return $this->_replace(\JText::_($string), $replacements);
    }

    protected function _replace($string, $replacements)
    {
        $search = array();

        foreach ($replacements as $label => $replacement) {
            $search[] = "{$label}";
        }

        return str_replace($search, array_values($replacements), $string);
    }

    public function isTranslatable() {

    }

    public function getKey() {

    }
}