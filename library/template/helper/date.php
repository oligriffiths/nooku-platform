<?php
/**
 * @package     Koowa_Template
 * @subpackage  Helper
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

namespace Nooku\Library;

/**
 * Template Helper Class
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Template
 * @subpackage  Helper
 */
class TemplateHelperDate extends TemplateHelperAbstract
{
    /**
     * Returns formatted date according to current local and adds time offset.
     *
     * @param  array  $config An optional array with configuration options.
     * @return string Formatted date.
     */
    public function format($config = array())
    {
        $config = new ObjectConfig($config);
        $config->append(array(
            'date'     => 'now',
            'timezone' => date_default_timezone_get(),
            'format'   => $this->getTemplate()->getFormat() == 'rss' ? Date::RSS : \JText::_('DATE_FORMAT_LC1'),
            'default'  => ''
        ));

        $return = $config->default;

        if(!in_array($config->date, array('0000-00-00 00:00:00', '0000-00-00'))) 
        {
            try 
            {
                $date = new Date(array('date' => $config->date, 'timezone' => 'UTC'));
                $date->setTimezone(new \DateTimeZone($config->timezone));

                $return = $date->format($config->format);
            } 
            catch(\Exception $e) {}
        }

        return $return;
    }

    /**
     * Returns human readable date.
     *
     * @param  array  $config An optional array with configuration options.
     * @return string Formatted date.
     */
    public function humanize($config = array())
    {
        $config = new ObjectConfig($config);
        $config->append(array(
            'date'            => 'now',
            'timezone'        => date_default_timezone_get(),
            'default'         => \JText::_('Never'),
            'smallest_period' => 'second'
        ));

        $result = $config->default;

        if(!in_array($config->date, array('0000-00-00 00:00:00', '0000-00-00')))
        {
            $periods = array('second', 'minute', 'hour', 'day', 'week', 'month', 'year');
            $lengths = array(60, 60, 24, 7, 4.35, 12, 10);
            $now     = new \DateTime();

            try
            {
                $date = new Date(array('date' => $config->date, 'timezone' => 'UTC'));
                $date->setTimezone(new \DateTimeZone($config->timezone));

                $result = $date->humanize($config->period);
            }
            catch(\Exception $e) {}
        }

        return $result;
    }
}