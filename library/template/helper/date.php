<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Date Template Helper
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Template
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
            'format'   => $this->getTemplate()->getFormat() == 'rss' ? Date::RSS : $this->translate('DATE_FORMAT_LC1'),
            'default'  => '',
            'attribs'  => array()
        ));

        $return = $config->default;

        if(!in_array($config->date, array('0000-00-00 00:00:00', '0000-00-00'))) 
        {
            try 
            {
                $attribs = $this->buildAttributes($config->attribs);

                $date = new Date(array('date' => $config->date, 'timezone' => 'UTC'));
                $date->setTimezone(new \DateTimeZone($config->timezone));

                $return = '<time datetime="'.$date->format('Y-m-d').'" '.$attribs.'>';
                $return .= $date->format($config->format);
                $return .= '</time>';
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
            'default'         => $this->translate('Never'),
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