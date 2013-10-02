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
 * Default Activity Parameter Translator
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Nooku\Component\Activities
 */
class TranslatorParameterDefault extends Library\Object implements TranslatorParameterInterface
{
    /**
     * @var string The parameter label.
     */
    protected $_label;

    /**
     * @var mixed The parameter translator.
     */
    protected $_translator;

    /**
     * @var string The parameter text.
     */
    protected $_text;

    /**
     * @var boolean Determines if the parameter is translatable (true) or not (false).
     */
    protected $_translate;

    /**
     * @var array The parameter attributes.
     */
    protected $_attributes;

    /**
     * @var array The parameter link attributes.
     */
    protected $_link_attributes;

    /**
     * @var string The parameter url.
     */
    protected $_url;

    /**
     * @var mixed The parameter renderer.
     */
    protected $_renderer;

    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        if (!$config->label)
        {
            throw new InvalidArgumentException('A translator parameter must have a label');
        }

        $this->_label      = $config->label;
        $this->_renderer   = $config->renderer;
        $this->_translator = $config->translator;

        $this->setAttributes(Library\ObjectConfig::unbox($config->attributes));
        $this->setLinkAttributes(Library\ObjectConfig::unbox($config->link_attributes));
        $this->setTranslatable($config->translate);
        $this->setText($config->text);
        $this->setUrl($config->url);
    }

    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'html'            => true,
            'translate'       => false,
            'link_attributes' => array(),
            'attributes'      => array('class' => array('parameter')),
            'translator'      => 'com:application.translator',
        ))->append(array(
                'renderer' => 'com:activities.translator.parameter.renderer.' . ($config->html ? 'html' : 'text')));

        parent::_initialize($config);
    }

    /**
     * @see TranslatorParameterInterface::setTranslatable()
     */
    public function setTranslatable($state)
    {
        $this->_translate = (bool) $state;
        return $this;
    }

    /**
     * @see TranslatorParameterInterface::setText()
     */
    public function setText($text)
    {
        $this->_text = (string) $text;
        return $this;
    }

    /**
     * @see TranslatorParameterInterface::getText()
     */
    public function getText()
    {
        return $this->_text;
    }

    /**
     * @see TranslatorParameterInterface::isTranslatable()
     */
    public function isTranslatable()
    {
        return (bool) $this->_translate;
    }

    /**
     * @see TranslatorParameterInterface::setTranslator()
     */
    public function setTranslator(Library\Translator $translator)
    {
        $this->_translator = $translator;
        return $this;
    }

    /**
     * @see TranslatorParameterInterface::getTranslator()
     */
    public function getTranslator()
    {
        if (!$this->_translator instanceof Library\Translator)
        {
            $this->setTranslator($this->getObject($this->_translator));
        }
        return $this->_translator;
    }

    /**
     * @see TranslatorParameterInterface::getLabel()
     */
    public function getLabel()
    {
        return $this->_label;
    }

    /**
     * @see TranslatorParameterInterface::setRenderer()
     */
    public function setRenderer(TranslatorParameterRendererInterface $renderer)
    {
        $this->_renderer = $renderer;
        return $this;
    }

    /**
     * @see TranslatorParameterInterface::getRenderer()
     */
    public function getRenderer()
    {
        if (!$this->_renderer instanceof TranslatorParameterRendererInterface)
        {
            $this->setRenderer($this->getObject($this->_renderer));
        }

        return $this->_renderer;
    }

    /**
     * @see TranslatorParameterInterface::render()
     */
    public function render()
    {
        return (string) $this->getRenderer()->render($this);
    }

    /**
     * @see TranslatorParameterInterface::setAttributes()
     */
    public function setAttributes($attributes)
    {
        $this->_attributes = $attributes;
        return $this;
    }

    /**
     * @see TranslatorParameterInterface::getAttributes()
     */
    public function getAttributes()
    {
        return $this->_attributes;
    }

    /**
     * @see TranslatorParameterInterface::setLinkAttributes()
     */
    public function setLinkAttributes($attributes)
    {
        $this->_link_attributes = $attributes;
        return $this;
    }

    /**
     * @see TranslatorParameterInterface::getLinkAttributes()
     */
    public function getLinkAttributes()
    {
        return $this->_link_attributes;
    }

    /**
     * @see TranslatorParameterInterface::setUrl()
     */
    public function setUrl($url)
    {
        $this->_url = (string) $url;
        return $this;
    }

    /**
     * @see TranslatorParameterInterface::getUrl()
     */
    public function getUrl()
    {
        return $this->_url;
    }

    /**
     * @see TranslatorParameterInterface::isLinkable()
     */
    public function isLinkable()
    {
        return (bool) $this->getUrl();
    }
}
